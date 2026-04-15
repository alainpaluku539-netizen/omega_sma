from flask import Flask, jsonify, request
from flask_cors import CORS
import mysql.connector
import paho.mqtt.client as mqtt
import json
import requests
from datetime import datetime
import uuid

# ==========================================================
# FLASK APP
# ==========================================================
app = Flask(__name__)
CORS(app)

# ==========================================================
# MYSQL CONFIG (Laragon)
# ==========================================================
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'smart_home'
}

def get_db_connection():
    return mysql.connector.connect(**db_config)

# ==========================================================
# MQTT CONFIG
# ==========================================================
MQTT_BROKER = "test.mosquitto.org"
MQTT_PORT = 1883
TOPIC_TELEMETRY = "esp32/01/data"
TOPIC_COMMAND = "esp32/01/cmd"

# ==========================================================
# MQTT CALLBACKS
# ==========================================================
def on_connect(client, userdata, flags, rc):
    if rc == 0:
        print("[MQTT] Connected successfully")
        client.subscribe(TOPIC_TELEMETRY)
    else:
        print(f"[MQTT] Connection failed code {rc}")

def on_message(client, userdata, msg):
    conn = None
    cursor = None

    try:
        payload = json.loads(msg.payload.decode())
        now = datetime.now()

        if "temp" in payload:
            device = payload.get("device", "ESP32-OMEGA")
            temp = float(payload.get("temp", 0))
            hum = float(payload.get("hum", 0))

            conn = get_db_connection()
            cursor = conn.cursor()

            # =========================
            # SENSOR DATA
            # =========================
            cursor.execute("""
                INSERT INTO sensor_data 
                (device_id, temperature, humidity, measured_at, created_at, updated_at)
                VALUES (%s, %s, %s, %s, %s, %s)
            """, (device, temp, hum, now, now, now))

            # =========================
            # ENERGY SIMULATION
            # =========================
            usage_kw = 0.4 + (temp / 100)

            cursor.execute("""
                INSERT INTO energy_logs 
                (usage_kw, recorded_at, created_at, updated_at)
                VALUES (%s, %s, %s, %s)
            """, (usage_kw, now, now, now))

            conn.commit()

            print(f"[DB] {device} | {temp}°C | {hum}% | OK")

            # =========================
            # OPTIONAL: notify Laravel
            # =========================
            try:
                requests.get("http://127.0.0.1:8000/api/sensors/latest", timeout=1)
            except:
                pass

    except Exception as e:
        print(f"[ERROR] {e}")

    finally:
        if cursor:
            cursor.close()
        if conn and conn.is_connected():
            conn.close()

# ==========================================================
# MQTT CLIENT (FIXED VERSION)
# ==========================================================
client_id = f"gateway_{uuid.uuid4().hex[:6]}"

mqtt_client = mqtt.Client(client_id=client_id)

mqtt_client.on_connect = on_connect
mqtt_client.on_message = on_message

mqtt_client.reconnect_delay_set(min_delay=1, max_delay=120)

# ==========================================================
# CONNECT MQTT (ONLY ONCE - FIXED BUG)
# ==========================================================
try:
    print(f"[MQTT] Connecting to {MQTT_BROKER}...")
    mqtt_client.connect(MQTT_BROKER, MQTT_PORT, 60)
    mqtt_client.loop_start()
except Exception as e:
    print(f"[MQTT ERROR] {e}")

# ==========================================================
# API: CONTROL RELAY
# ==========================================================
@app.route('/api/led', methods=['POST'])
def control_relay():
    try:
        data = request.get_json()

        relay = data.get('relay')
        action = data.get('action')

        if relay is None or action not in ["ON", "OFF"]:
            return jsonify({"error": "Invalid data"}), 400

        payload = json.dumps({
            "relay": int(relay),
            "state": action
        })

        mqtt_client.publish(TOPIC_COMMAND, payload)

        return jsonify({
            "status": "success",
            "sent": payload
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500

# ==========================================================
# STATUS API
# ==========================================================
@app.route('/status', methods=['GET'])
def status():
    return jsonify({
        "gateway": "online",
        "mqtt_broker": MQTT_BROKER,
        "time": datetime.now().isoformat()
    })

# ==========================================================
# RUN SERVER
# ==========================================================
if __name__ == '__main__':
    print("\n==============================")
    print(" OMEGA IOT GATEWAY STARTED")
    print("==============================\n")

    app.run(host='0.0.0.0', port=5000, debug=False)