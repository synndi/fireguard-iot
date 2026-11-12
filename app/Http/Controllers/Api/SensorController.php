<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SensorLog;

class SensorController extends Controller
{
    public function store(Request $request)
    {
        // Memeriksa data yang dikirim oleh ESP32
        $validated = $request->validate([
            'mq2_value' => 'required|integer',
            'flame_value' => 'required|integer',
            'status' => 'required|string|in:AMAN,BAHAYA',
        ]);

        // Menyimpan data sensor ke database
        $sensor = SensorLog::create($validated);

        // Mengirimkan respons setelah data berhasil disimpan
        return response()->json([
            'message' => 'Data sensor berhasil disimpan',
            'data' => $sensor
        ], 201);
    }
}
