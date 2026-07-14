<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


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