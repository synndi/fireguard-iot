<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SensorLog;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttSubscribe extends Command
{
    protected $signature = 'app:mqtt-subscribe';

    protected $description = 'Menerima data sensor melalui MQTT';

    public function handle()
    {
        // Alamat broker MQTT
        $server = 'broker.emqx.io';
        $port = 1883;

        // Membuat identitas untuk koneksi Laravel
        $clientId = 'fireguard-laravel-' . uniqid();

        // Membuat koneksi MQTT
        $mqtt = new MqttClient(
            $server,
            $port,
            $clientId
        );

        // Menghubungkan Laravel ke broker
        $settings = (new ConnectionSettings())
        ->setConnectTimeout(10)
        ->setKeepAliveInterval(10)
        ->setSocketTimeout(30);

        $mqtt->connect($settings, true);

        $this->info('Laravel berhasil terhubung ke MQTT!');

        // Mendengarkan topik dari ESP32
        $mqtt->subscribe('firealarm/status', function ($topic, $message) {

            // Mengubah pesan JSON menjadi array PHP
            $data = json_decode($message, true);

            // Memastikan data bisa dibaca
            if (!is_array($data)) {
                $this->error('Format data tidak valid.');
                return;
            }

            // Menyimpan data sensor ke database
            SensorLog::create([
                'mq2_value' => $data['gas'],
                'flame_value' => $data['flame'] ? 1 : 0,
                'status' => $data['status'],
            ]);

            $this->info('Data sensor berhasil disimpan!');
        }, 0);

        // Menjaga koneksi tetap aktif
        $mqtt->loop(true);
    }
}
