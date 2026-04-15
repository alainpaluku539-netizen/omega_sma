// ==========================================================
// ESP32 OMEGA PRO - IoT STABLE (MQTT + DHT11 + 4 RELAIS + GLOW)
// ==========================================================

#include <WiFi.h>
#include <PubSubClient.h>
#include <ArduinoJson.h>
#include <DHT.h>

// ---------------- WIFI ----------------
const char* ssid     = "Allinone";
const char* password = "Allen1981";

// ---------------- MQTT ----------------
const char* mqtt_server = "test.mosquitto.org";
const int   mqtt_port   = 1883;
const char* device_id   = "OMEGA_NODE_01";

// ---------------- HARDWARE ----------------
#define DHTPIN 4
#define DHTTYPE DHT11

const int relayPins[] = {12, 13, 14, 27};      // Sortie Relais
const int ledWitnessPins[] = {18, 19, 21, 22}; // Leds Témoins GLOW
const int numRelays = 4; 
const int LED_STATUS = 2; // Led interne bleue
const int connLed = 5;    // Led externe statut WiFi

// ---------------- OBJETS ----------------
DHT dht(DHTPIN, DHTTYPE);
WiFiClient espClient;
PubSubClient client(espClient);

// ---------------- TIMERS & CACHE ----------------
unsigned long lastSensorRead = 0;
unsigned long lastMqttRetry  = 0;
unsigned long lastBlink      = 0;
const long SENSOR_INTERVAL   = 10000;
const long MQTT_RETRY_TIME   = 5000;
const long BLINK_INTERVAL    = 1000; // Clignote toutes les 1s

float lastTemp = 0;
float lastHum  = 0;
bool connLedState = LOW;

// ==========================================================
// WIFI CONNECT
// ==========================================================
void connectWiFi() {
    if (WiFi.status() == WL_CONNECTED) return;
    WiFi.begin(ssid, password);
    Serial.print("WiFi connexion");
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
        digitalWrite(LED_STATUS, !digitalRead(LED_STATUS));
    }
    Serial.println("\nWiFi OK IP: " + WiFi.localIP().toString());
    digitalWrite(LED_STATUS, LOW);
}

// ==========================================================
// MQTT CALLBACK (RÉCEPTION DES ORDRES LARAVEL)
// ==========================================================
void callback(char* topic, byte* payload, unsigned int length) {
    StaticJsonDocument<256> doc;
    DeserializationError error = deserializeJson(doc, payload, length);
    if (error) return;

    // 1. COMMANDE INDIVIDUELLE (ex: {"relay": 0, "state": "ON"})
    if (doc.containsKey("relay") && doc.containsKey("state")) {
        int idx = doc["relay"];
        const char* state = doc["state"];

        if (idx >= 0 && idx < numRelays) {
            bool isON = (strcmp(state, "ON") == 0);
            digitalWrite(relayPins[idx], isON ? LOW : HIGH); // Relais (logique inversée)
            digitalWrite(ledWitnessPins[idx], isON ? HIGH : LOW); // Témoin (logique normale)
            Serial.printf("Canal %d => %s\n", idx, state);
        }
    }
    
    // 2. COMMANDE GLOBALE (ex: {"cmd": "ON"}) - Pour boutons "Tout allumer/éteindre"
    else if (doc.containsKey("cmd")) {
        const char* cmd = doc["cmd"];
        bool isON = (strcmp(cmd, "ON") == 0);
        
        for (int i = 0; i < numRelays; i++) {
            digitalWrite(relayPins[i], isON ? LOW : HIGH);
            digitalWrite(ledWitnessPins[i], isON ? HIGH : LOW);
        }
        Serial.printf("ALL => %s\n", cmd);
    }
    
    digitalWrite(LED_STATUS, HIGH); delay(50); digitalWrite(LED_STATUS, LOW);
}

// ==========================================================
// MQTT RECONNECT
// ==========================================================
void reconnectMQTT() {
    if (client.connected()) return;
    unsigned long now = millis();
    if (now - lastMqttRetry < MQTT_RETRY_TIME) return;
    lastMqttRetry = now;

    Serial.print("MQTT connexion...");
    if (client.connect(device_id, "esp32/01/status", 1, true, "offline")) {
        Serial.println(" OK");
        client.publish("esp32/01/status", "online", true);
        client.subscribe("esp32/01/cmd");
    } else {
        Serial.print(" FAIL rc="); Serial.println(client.state());
        digitalWrite(connLed, LOW); // Éteinte si échec total
    }
}

// ==========================================================
// READ DHT11 SAFE
// ==========================================================
bool readSensor() {
    dht.readHumidity(); delay(100); 
    float h = dht.readHumidity();
    float t = dht.readTemperature();

    if (isnan(h) || isnan(t) || h == 0) {
        Serial.println("DHT11 invalid -> check cables or use 5V");
        return false;
    }
    lastTemp = t; lastHum  = h;
    return true;
}

// ==========================================================
// SETUP
// ==========================================================
void setup() {
    Serial.begin(115200);
    pinMode(LED_STATUS, OUTPUT);
    pinMode(connLed, OUTPUT);

    // Initialisation Relais (OFF) et Témoins (OFF)
    for (int i = 0; i < numRelays; i++) {
        pinMode(relayPins[i], OUTPUT);
        digitalWrite(relayPins[i], HIGH);
        pinMode(ledWitnessPins[i], OUTPUT);
        digitalWrite(ledWitnessPins[i], LOW);
    }

    dht.begin();
    delay(1000); 
    connectWiFi();
    client.setServer(mqtt_server, mqtt_port);
    client.setCallback(callback);
}

// ==========================================================
// LOOP
// ==========================================================
void loop() {
    if (WiFi.status() != WL_CONNECTED) connectWiFi();
    
    reconnectMQTT();
    client.loop();

    unsigned long now = millis();

    // --- BLINK CONN LED (Indique que le système tourne et est connecté) ---
    if (client.connected() && (now - lastBlink > BLINK_INTERVAL)) {
        lastBlink = now;
        connLedState = !connLedState;
        digitalWrite(connLed, connLedState);
    }

    // --- ENVOI TÉLÉMÉTRIE ---
    if (now - lastSensorRead > SENSOR_INTERVAL) {
        lastSensorRead = now;
        if (readSensor()) {
            StaticJsonDocument<256> doc;
            doc["device"] = device_id;
            doc["temp"] = lastTemp;
            doc["hum"] = lastHum;
            doc["wifi_rssi"] = WiFi.RSSI();

            char buffer[256];
            serializeJson(doc, buffer);
            client.publish("esp32/01/data", buffer);
            Serial.printf("MQTT SENT -> T: %.1f°C H: %.1f%%\n", lastTemp, lastHum);
        }
    }
}
