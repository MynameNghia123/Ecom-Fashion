<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    // Các path sẽ áp dụng CORS - bao gồm toàn bộ route API + route lấy CSRF cookie cho Sanctum
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    // Cho phép mọi HTTP method (GET, POST, PUT, DELETE...)
    'allowed_methods' => ['*'],

    // Danh sách domain được phép gọi API - THAY bằng domain frontend thật của bạn
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'),
        'https://ecom-fashion.onrender.com',
    ],

    // Nếu muốn cho phép nhiều domain khớp theo pattern (ví dụ preview branch), dùng regex ở đây
    'allowed_origins_patterns' => [],

    // Cho phép mọi header gửi lên (Authorization, Content-Type, X-XSRF-TOKEN...)
    'allowed_headers' => ['*'],

    // Header nào trình duyệt được phép đọc từ response (thường để trống)
    'exposed_headers' => [],

    // Thời gian (giây) trình duyệt cache kết quả preflight OPTIONS request
    'max_age' => 0,

    // QUAN TRỌNG: phải để true nếu dùng Sanctum (cookie-based auth) để gửi kèm cookie/session
    'supports_credentials' => true,

];