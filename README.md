
# FireGuard IoT Monitoring System

FireGuard merupakan sistem pendeteksi kebakaran berbasis Internet of Things (IoT).

Sistem ini menggunakan ESP32 dan sensor untuk mendeteksi kondisi gas/asap serta api. Data sensor dikirim melalui MQTT dan ditampilkan pada dashboard berbasis Laravel.

## Teknologi

- Laravel
- PHP
- MySQL
- ESP32
- MQTT
- MQ-2 Sensor
- Flame Sensor
- Telegram Bot

## Fitur

- Monitoring nilai sensor MQ-2
- Monitoring Flame Sensor
- Penyimpanan data sensor
- Dashboard monitoring
- Riwayat pembacaan sensor
- Notifikasi Telegram

## MQTT Configuration

- Broker: broker.emqx.io
- Port: 1883
- Topic: firealarm/status

## Developer

FireGuard IoT Project
