# 🏠 Omega Smart Home IoT System

A complete Smart Home IoT platform built with Laravel, ESP32, MQTT, Python, and real-time dashboard.

---

## 🚀 Overview

This project is a full IoT Smart Home system that allows real-time monitoring and control of devices such as sensors, lights, and energy systems.

It connects embedded devices (ESP32) with a Laravel backend and provides a real-time web dashboard.

---

## 🧠 Architecture

ESP32 Devices  
↓  
MQTT Broker (Mosquitto)  
↓  
Python Bridge (optional processing)  
↓  
Laravel Backend API  
↓  
MySQL Database  
↓  
Livewire Real-time Dashboard  
↓  
User Interface

---

## ⚙️ Tech Stack

- Laravel 13 (Backend API)
- Livewire (Realtime UI)
- MQTT (IoT communication)
- ESP32 (Hardware devices)
- Python (Bridge / processing)
- MySQL (Database)
- Reverb (WebSockets)
- Cloudflare Tunnel (public access)

---

## 📡 Features

- 🌡 Temperature & humidity monitoring
- ⚡ Energy consumption tracking
- 🔔 Smart alerts system
- 📲 Real-time device control
- 📡 MQTT communication
- 🔄 Live dashboard updates
- 🔐 Secure API for IoT devices

---

## 📁 Project Structure

/app        → Laravel backend  
/routes     → API routes  
/resources  → Livewire UI  
/iot        → ESP32 firmware  
/bridge     → Python MQTT processing  
/scripts    → automation tools  

---

## 🚀 Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate