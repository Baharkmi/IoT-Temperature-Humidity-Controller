#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
#include "DHT.h"
#include <String.h>

#define Airconditioner 16  //int1
#define Heater 5           //int2
#define Fan 4              //int3
#define Humidifier 0       //int4  
#define DHTPIN 13
#define DHTTYPE DHT11   // DHT 11
float hhumid = 80 ;
float lhumid = 40 ;
float htemp = 30 ;
float ltemp = 28 ;
const char *ssid = "Honor 9 Lite";
const char *password = "123456789";

//Web/Server address to read/write from
String  Link = "http://192.168.43.158:81/iot2/iot.php";
ESP8266WiFiMulti WiFiMulti;


DHT dht(DHTPIN, DHTTYPE);
void setup() {
  pinMode(Airconditioner, OUTPUT);
  pinMode(Heater, OUTPUT);
  pinMode(Fan, OUTPUT);
  pinMode(Humidifier, OUTPUT);
  digitalWrite(Heater, HIGH);
  digitalWrite(Airconditioner, HIGH);
  digitalWrite(Humidifier, HIGH);
  digitalWrite(Fan, HIGH);
  delay(1000);
  Serial.begin(115200);
  WiFi.mode(WIFI_OFF);        //Prevents reconnection issue (taking too long to connect)
  delay(1000);
  WiFi.mode(WIFI_STA);        //This line hides the viewing of ESP as wifi hotspot

  WiFi.begin(ssid, password);     //Connect to your WiFi router
  Serial.println("");

  Serial.print("Connecting");
  // Wait for connection
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  dht.begin();
  //If connection successful show IP address in serial monitor
  Serial.println("");
  Serial.print("Connected to ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());  //IP address assigned to your ESP
}

void loop() {
  float h = dht.readHumidity();
  float t = dht.readTemperature();
  sendDatas(t, h);
  getDatas();
  control(t, h);  
}

void control(float rt , float rh) {

  if(!isnan(htemp) && !isnan(ltemp) && !isnan(hhumid) && !isnan(lhumid)){
    if (rt > htemp) {
      digitalWrite(Heater, HIGH);
      digitalWrite(Airconditioner, LOW);
    }
    else if (rt <= htemp){
      digitalWrite(Airconditioner, HIGH);
    }
    if (rt < ltemp) {
      digitalWrite(Heater, LOW);
      digitalWrite(Airconditioner, HIGH);
    }
    else if (rt >= ltemp) {
      digitalWrite(Heater, HIGH);
    }
    if (rh > hhumid) {
      digitalWrite(Humidifier, LOW);
    }
    else if (rh < lhumid) {
      digitalWrite(Humidifier, HIGH);
    }
  }
}

void sendDatas(float t, float h){
  //Declare object of class HTTPClient
  HTTPClient http;
  String temperature, humidity, postData;

  temperature = String(t);
  humidity = String(h);

  Serial.println(temperature);

  //prepare request
  postData = "?sensor=1&temp=" + temperature + "&humid=" + humidity ;
  http.begin(Link);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST(postData);
  String payload = http.getString();

  //Serial.println(httpCode);
  //Serial.println(payload);
  http.end();
  delay(1000);
}

void getDatas(){
   // wait for WiFi connection
  if ((WiFiMulti.run() == WL_CONNECTED)) {

    WiFiClient client;
    HTTPClient http; //must be declared after WiFiClient for correct destruction order, because used by http.begin(client,...)

    Serial.print("[HTTP] begin...\n");

    // configure server and url
    http.begin(client, "http://192.168.43.158:81/iot2/control.php");
    //http.begin(client, "jigsaw.w3.org", 80, "/HTTP/connection.html");

    Serial.print("[HTTP] GET...\n");
    // start connection and send HTTP header
    int httpCode = http.GET();
    if (httpCode > 0) {
      // HTTP header has been send and Server response header has been handled
     // Serial.printf("[HTTP] GET... code: %d\n", httpCode);

      // file found at server
      if (httpCode == HTTP_CODE_OK) {

        // get lenght of document (is -1 when Server sends no Content-Length header)
        int len = http.getSize();

        // create buffer for read
        uint8_t buff[1] = { 0 };

        String line = "";
        String temp = "";

        // get tcp stream
        //WiFiClient * stream = &client;

        // read all data from server
        while (http.connected()) {
          // read up to 128 byte
          //int c = stream->readBytes(buff, std::min((size_t)len, sizeof(buff)));
          char c = client.read();
          //Serial.printf("readBytes: %d\n", c);
          if (!c) {
            Serial.println("read timeout");
          }
         line = line + c;
         if(c=='\n'){
            // write it to Serial
            //Serial.print(line);
            if(line.indexOf("htemp") > 0){
               String temp = line.substring(line.lastIndexOf('=')+2, line.lastIndexOf('"'));
               htemp = temp.toFloat();
            }
            if(line.indexOf("ltemp") > 0){
               String temp = line.substring(line.lastIndexOf('=')+2, line.lastIndexOf('"'));
               ltemp = temp.toFloat();
            }
            if(line.indexOf("hhumid") > 0){
               String temp = line.substring(line.lastIndexOf('=')+2, line.lastIndexOf('"'));
               hhumid = temp.toFloat();
            }
            if(line.indexOf("lhumid") > 0){
               String temp = line.substring(line.lastIndexOf('=')+2, line.lastIndexOf('"'));
               lhumid = temp.toFloat();
            }
            
            line = "";
         }
        }
        Serial.println();
        Serial.print("[HTTP] connection closed or file end.\n");
        Serial.printf("htemp=%f ltemp=%f hhumid=%f lhumid=%f", htemp, ltemp, hhumid, lhumid);
      }
    } else {
      Serial.printf("[HTTP] GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
    }

    http.end();
  }

}
