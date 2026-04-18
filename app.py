# ==========================================================
# OMEGA IOT GATEWAY PRO MAX 2026
# Flask + MQTT + MySQL + ESP32 + Laravel
# ==========================================================

from flask import Flask, jsonify, request
from flask_cors import CORS

import mysql.connector
from mysql.connector import Error

import paho.mqtt.client as mqtt
import json
import uuid
import time
import requests

from datetime import datetime

# ==========================================================
# FLASK APP
# ==========================================================
app = Flask(__name__)
CORS(app)

# ==========================================================
# MYSQL CONFIG
# ==========================================================
db_config = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "smart_home"
}

# ==========================================================
# MQTT CONFIG
# ==========================================================
MQTT_BROKER = "test.mosquitto.org"
MQTT_PORT   = 1883

TOPIC_DATA   = "esp32/01/data"
TOPIC_CMD    = "esp32/01/cmd"
TOPIC_STATUS = "esp32/01/status"

client_id = f"gateway_{uuid.uuid4().hex[:6]}"

# ==========================================================
# GLOBAL STATE
# ==========================================================
mqtt_connected = False
last_data_time = None

# ==========================================================
# MYSQL
# ==========================================================
def get_db():
    return mysql.connector.connect(**db_config)

# ==========================================================
# SAVE SENSOR DATA
# ==========================================================
def save_sensor(payload):

    conn = None
    cur = None

    try:
        conn = get_db()
        cur = conn.cursor()

        now = datetime.now()

        device_id = payload.get("device", "ESP32")
        temp      = payload.get("temp")
        hum       = payload.get("hum")
        rssi      = payload.get("rssi")
        uptime    = payload.get("uptime", 0)

        cur.execute("""
            INSERT INTO sensor_data
            (
                device_id,
                temperature,
                humidity,
                rssi,
                measured_at,
                created_at,
                updated_at
            )
            VALUES (%s,%s,%s,%s,%s,%s,%s)
        """, (
            device_id,
            temp,
            hum,
            rssi,
            now,
            now,
            now
        ))

        # Optional energy log
        usage_kw = 0.4 + (float(temp or 0) / 100)

        cur.execute("""
            INSERT INTO energy_logs
            (
                usage_kw,
                recorded_at,
                created_at,
                updated_at
            )
            VALUES (%s,%s,%s,%s)
        """, (
            usage_kw,
            now,
            now,
            now
        ))

        conn.commit()

        print(
            f"[DB] {device_id} | "
            f"T:{temp}°C "
            f"H:{hum}% "
            f"RSSI:{rssi}"
        )

    except Error as e:
        print("[MYSQL ERROR]", e)

    finally:
        if cur:
            cur.close()
        if conn and conn.is_connected():
            conn.close()

# ==========================================================
# MQTT CALLBACKS
# ==========================================================
def on_connect(client, userdata, flags, rc):

    global mqtt_connected

    if rc == 0:
        mqtt_connected = True

        print("[MQTT] Connected")

        client.subscribe(TOPIC_DATA)
        client.subscribe(TOPIC_STATUS)

    else:
        mqtt_connected = False
        print("[MQTT] Failed rc =", rc)

# ----------------------------------------------------------
def on_disconnect(client, userdata, rc):

    global mqtt_connected

    mqtt_connected = False

    print("[MQTT] Disconnected")

# ----------------------------------------------------------
def on_message(client, userdata, msg):

    global last_data_time

    topic = msg.topic

    try:
        raw = msg.payload.decode()

        # DEVICE STATUS
        if topic == TOPIC_STATUS:
            print("[STATUS]", raw)
            return

        # SENSOR DATA
        if topic == TOPIC_DATA:

            payload = json.loads(raw)

            last_data_time = datetime.now()

            save_sensor(payload)

            # Notify Laravel (optional)
            try:
                requests.get(
                    "http://127.0.0.1:8000/api/sensors/latest",
                    timeout=1
                )
            except:
                pass

    except Exception as e:
        print("[MQTT ERROR]", e)

# ==========================================================
# MQTT CLIENT
# ==========================================================
mqtt_client = mqtt.Client(client_id=client_id)

mqtt_client.on_connect    = on_connect
mqtt_client.on_disconnect = on_disconnect
mqtt_client.on_message    = on_message

mqtt_client.reconnect_delay_set(
    min_delay=2,
    max_delay=60
)

# ==========================================================
# START MQTT
# ==========================================================
def start_mqtt():

    while True:
        try:
            print("[MQTT] Connecting...")

            mqtt_client.connect(MQTT_BROKER, MQTT_PORT, 60)
            mqtt_client.loop_start()

            break

        except Exception as e:
            print("[MQTT ERROR]", e)
            time.sleep(5)

# ==========================================================
# API CONTROL RELAY
# ==========================================================
@app.route("/api/relay", methods=["POST"])
def relay():

    try:
        data = request.get_json()

        command = data.get("command")

        # ALL ON / OFF
        if command in ["ON", "OFF"]:

            payload = json.dumps({
                "cmd": command
            })

            mqtt_client.publish(TOPIC_CMD, payload)

            return jsonify({
                "success": True,
                "sent": payload
            })

        # SINGLE RELAY
        relay_id = data.get("relay")
        action   = data.get("action")

        if relay_id is not None and action in ["ON", "OFF"]:

            payload = json.dumps({
                "relay": int(relay_id),
                "state": action
            })

            mqtt_client.publish(TOPIC_CMD, payload)

            return jsonify({
                "success": True,
                "sent": payload
            })

        return jsonify({
            "success": False,
            "message": "Invalid data"
        }), 400

    except Exception as e:
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

# ==========================================================
# API STATUS
# ==========================================================
@app.route("/status")
def status():

    return jsonify({
        "gateway": "online",
        "mqtt_connected": mqtt_connected,
        "broker": MQTT_BROKER,
        "last_sensor": str(last_data_time),
        "server_time": datetime.now().isoformat()
    })

# ==========================================================
# API LAST DATA
# ==========================================================
@app.route("/api/last")
def last():

    conn = None
    cur = None

    try:
        conn = get_db()
        cur = conn.cursor(dictionary=True)

        cur.execute("""
            SELECT *
            FROM sensor_data
            ORDER BY id DESC
            LIMIT 1
        """)

        row = cur.fetchone()

        return jsonify(row if row else {})

    except Exception as e:
        return jsonify({
            "error": str(e)
        }), 500

    finally:
        if cur:
            cur.close()
        if conn and conn.is_connected():
            conn.close()

# ==========================================================
# MAIN
# ==========================================================
if __name__ == "__main__":

    print("")
    print("====================================")
    print(" OMEGA IOT GATEWAY PRO MAX STARTED ")
    print("====================================")
    print("")

    start_mqtt()

    app.run(
        host="0.0.0.0",
        port=5000,
        debug=False
    )