
<?php

use Illuminate\Support\Facades\Route;
use App\Models\SensorLog;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {

    // Mengambil data sensor terbaru
    $latest = SensorLog::latest()->first();

    // Mengambil 10 data sensor terakhir
    $logs = SensorLog::latest()->take(10)->get()->reverse();

    // Menyiapkan data untuk grafik
    $chartLabels = $logs->map(function ($log) {
        return $log->created_at->format('H:i:s');
    })->values();

    $chartValues = $logs->pluck('mq2_value')->values();

    // Menghitung jumlah data
    $totalLogs = SensorLog::count();

    return view('dashboard', compact(
        'latest',
        'logs',
        'chartLabels',
        'chartValues',
        'totalLogs'
    ));

})->name('dashboard');
