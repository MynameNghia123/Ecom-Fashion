<?php

return [
    /*
    |--------------------------------------------------------------------------
    | VNPAY Configuration
    |--------------------------------------------------------------------------
    | Thông tin tích hợp cổng thanh toán VNPAY Sandbox.
    | Production: thay bằng thông tin merchant thật từ VNPAY.
    */

    // Mã website tại hệ thống VNPAY
    'tmn_code' => env('VNPAY_TMN_CODE', 'DEMOV210'),

    // Chuỗi bí mật (Hash Secret) để tạo và xác minh chữ ký
    'hash_secret' => env('VNPAY_HASH_SECRET', 'RAOEXHYVSDDIIENL'),

    // URL cổng thanh toán VNPAY (sandbox)
    'payment_url' => env('VNPAY_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),

    // URL backend nhận kết quả IPN (server-to-server)
    'ipn_url' => env('VNPAY_IPN_URL', 'http://localhost:8000/api/client/vnpay/ipn'),

    // URL frontend nhận redirect sau khi thanh toán
    'return_url' => env('VNPAY_RETURN_URL', 'http://localhost:5173/checkout/vnpay-return'),
];
