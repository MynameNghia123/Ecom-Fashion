<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return response()->json(['message' => 'EcomFashion API Backend is running.']);
});

Route::get('/test-mail', function () {
    Mail::raw('Mã OTP của bạn là: 123456', function ($message) {
        $message->to('test-user@example.com')
            ->subject('Test Mail OTP từ Laravel Backend');
    });

    return 'Email đã được gửi! Hãy kiểm tra Mailpit ở http://localhost:8025';
});

// ── Storage serve route ───────────────────────────────────────────────────────
// Fix: php artisan serve không follow symlink trong Docker/WSL
// Route này đọc file trực tiếp từ disk 'public' và trả về đúng Content-Type
Route::get('/storage/{path}', function (string $path) {
    if (! Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($path);
    $mimeType = Storage::disk('public')->mimeType($path);

    return response($file, 200)->header('Content-Type', $mimeType);
})->where('path', '.*');
