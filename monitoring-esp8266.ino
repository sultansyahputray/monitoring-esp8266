#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>

const char* ssid = "Redmi";
const char* password = "hayomaungapain";
const char* host = "192.168.214.166";
const String serverURL = "http://192.168.214.166/sensor_dbb/test_data.php";
const String url = "/sensor_dbb/buzzer.php";
const int httpPort = 80;

const int buttonPin0 = 3;  // Button sektor
const int buttonPin1 = 0;  // Button hari
const int buttonPin2 = 2;  // Button jam
const int buttonPin3 = 14; // Button menit
const int buttonPin4 = 12; // Button start
const int buttonPin5 = 13; // Button reset

const int buzzerPin = 16;
bool statusBuzzer = LOW;

unsigned long timeout;

LiquidCrystal_I2C lcd(0x27, 16, 2);

int days = 0;
int hours = 0;
int minutes = 0;
int seconds = 0;
int sector = 0;
int notif = 0;
int delaySendData = 0;
bool buzzerOn = false;
bool countdownStarted = false;
bool secureBuzzer = false;

const unsigned long buttonPollInterval = 1000;
unsigned long previousButtonMillis = 0;

void button0Callback() {
  if (!countdownStarted) {
    if (sector <=3)
    {
      sector++;
    }

    else if (sector > 3) {
      sector = 0;      
    }
    lcd.backlight();
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Time: ");
    lcd.print(days);
    lcd.print(":");
    lcd.print(hours);
    lcd.print(":");
    lcd.print(minutes);
    lcd.print(":");
    lcd.print(seconds);
    lcd.setCursor(0, 1);
    lcd.print("Sector: ");
    lcd.print(sector);
  }
}

void button1Callback() {
  if (!countdownStarted) {
    days += 30;
    lcd.backlight();
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print(days);
    lcd.print(":");
    lcd.print(hours);
    lcd.print(":");
    lcd.print(minutes);
    lcd.print(":");
    lcd.print(seconds);
    lcd.setCursor(0, 1);
    lcd.print("Sector: ");
    lcd.print(sector);
  }
}

void button2Callback() {
  if (!countdownStarted) {
    days++;
    lcd.backlight();
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print(days);
    lcd.print(":");
    lcd.print(hours);
    lcd.print(":");
    lcd.print(minutes);
    lcd.print(":");
    lcd.print(seconds);
    lcd.setCursor(0, 1);
    lcd.print("Sector: ");
    lcd.print(sector);
  }
}

void button3Callback() {
  if (!countdownStarted) {
    minutes++;
    lcd.backlight();
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print(days);
    lcd.print(":");
    lcd.print(hours);
    lcd.print(":");
    lcd.print(minutes);
    lcd.print(":");
    lcd.print(seconds);
    lcd.setCursor(0, 1);
    lcd.print("Sector: ");
    lcd.print(sector);
  }
}

void button4Callback() {
  if (!countdownStarted) {
    countdownStarted = true;
    lcd.backlight();
    lcd.clear();
  }
}

void button5Callback() {
  countdownStarted = false;
  secureBuzzer = false;
  days = 0;
  hours = 0;
  minutes = 0;
  seconds = 0;
  sector = 0;
  notif = 0;
  String panen = "----------";
  lcd.backlight();
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("Time: 00:00:00:00");
  lcd.setCursor(0, 1);
  lcd.print("Sector: ");
  lcd.print(sector);
  sendDataToServer(panen);
}

void checkButtons() {
  bool button0State = digitalRead(buttonPin0);
  bool button1State = digitalRead(buttonPin1);
  bool button2State = digitalRead(buttonPin2);
  bool button3State = digitalRead(buttonPin3);
  bool button4State = digitalRead(buttonPin4);
  bool button5State = digitalRead(buttonPin5);

  if (!button0State) {
    button0Callback();
  }
  if (!button1State) {
    button1Callback();
  }
  if (!button2State) {
    button2Callback();
  }
  if (!button3State) {
    button3Callback();
  }
  if (!button4State) {
    button4Callback();
  }
  if (!button5State) {
    button5Callback();
  }
}

void setup() {
  Serial.begin(9600);
  pinMode(buttonPin0, INPUT_PULLUP);
  pinMode(buttonPin1, INPUT_PULLUP);
  pinMode(buttonPin2, INPUT_PULLUP);
  pinMode(buttonPin3, INPUT_PULLUP);
  pinMode(buttonPin4, INPUT_PULLUP);
  pinMode(buttonPin5, INPUT_PULLUP);
  pinMode(buzzerPin, OUTPUT);

  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("ALREADY CONNECT");
  delay(1000);
  lcd.clear();
  delay(1000);
  lcd.print("Time: 00:00:00:00");
  lcd.setCursor(0, 1);
  lcd.print("Sector: ");
  lcd.print(sector);

  connectWiFi();
}

void loop() {
  unsigned long currentMillis = millis();
  if (currentMillis - previousButtonMillis >= buttonPollInterval) {
    previousButtonMillis = currentMillis;
    checkButtons();
  }

  if (countdownStarted) {
    String timeStr = String(days) + ":" + String(hours) + ":" + String(minutes) + ":" + String(seconds);

    lcd.setCursor(0, 0);
    lcd.backlight();
    lcd.print(timeStr);
    lcd.setCursor(0, 1);
    lcd.print("Sector: ");
    lcd.print(sector);

    if (seconds > 0) {
      delay(1000);
      seconds--;
      delaySendData++;
    } else if (minutes > 0) {
      minutes--;
      seconds = 59;
    } else if (hours > 0) {
      hours--;
      minutes = 59;
      seconds = 59;
    } else if (days > 0){
      days--;
      hours = 23;
      minutes = 59;
      seconds = 59;            
    }

    if (days == 0 && hours == 0 && minutes == 0 && seconds == 0) {
      digitalWrite(buzzerPin, HIGH);
      delay(5000);
      digitalWrite(buzzerPin, LOW);

      countdownStarted = false;
      secureBuzzer = true;
      days = 0;            
      hours = 0;
      minutes = 0;
      seconds = 0;
      notif = 1;
      String panen = "WAKTUNYA PANEN ";      
      lcd.clear();
      lcd.backlight();
      lcd.setCursor(0, 0);
      lcd.print("WAKTUNYA PANEN");
      lcd.setCursor(0, 1);
      lcd.print("Sector: ");
      lcd.print(sector);
      sendDataToServer(panen);
    }

    if (delaySendData >= 20)
    {
      Serial.println("already send");      
      sendDataToServer(timeStr);
      delaySendData = 0;
    }
  }
  
  else if (!countdownStarted && secureBuzzer){
      buzzerControl();
  }
}

void connectWiFi() {
  WiFi.mode(WIFI_OFF);
  delay(1000);

  WiFi.mode(WIFI_STA);

  WiFi.begin(ssid, password);
  Serial.println("Connecting to wifi");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.println(".");
  }

  Serial.print("Connected to: ");
  Serial.println(ssid);
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
}

void sendDataToServer(String timeStr) {
  if (WiFi.status() == WL_CONNECTED) {
    String postData = "time=" + timeStr + "&sector=" + String(sector) + "&notif=" + String(notif);
    HTTPClient http;
    WiFiClient client;
    http.begin(client, serverURL);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpCode = http.POST(postData);
    String payload = http.getString();

    Serial.print("URL : ");
    Serial.println(serverURL);
    Serial.print("Data : ");
    Serial.println(postData);
    Serial.print("httpCode : ");
    Serial.println(httpCode);
    Serial.print("payload : ");
    Serial.println(payload);
    Serial.println("-------------------------------------------");

    http.end();
  }
}

void buzzerControl() {
  WiFiClient client;
  if (!client.connect(host, httpPort)) {
    Serial.println("connection failde");
  }

  Serial.print("Requesting URL : ");
  Serial.println(url);

  client.print(String("GET ") + url + " HTTP/1.1\r\n" +
              "Host: " + host + "\r\n" + 
              "Connection: close\r\n\r\n");

  timeout = millis();
  while (client.available() == 0) {
    if (millis() - timeout > 5000) {
      Serial.println(">>> Client Timeout !");
      client.stop();
      return;
    }
  }

  while(client.available()){
    if(client.find("ON")){
      digitalWrite(buzzerPin,HIGH);     //Buzzer on
      delay(1000);
      digitalWrite(buzzerPin,LOW);     
      delay(1000);
      Serial.println("Buzzer ON");
    }else{  
      digitalWrite(buzzerPin,LOW);    //Buzzer off
      Serial.println("Buzzer OFF");
    }
  }

  Serial.println();
  Serial.println("closing connection");
  Serial.println();
}
