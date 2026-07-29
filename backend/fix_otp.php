<?php
// Fix OTP column and verify
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$app->boot();

// Perform the ALTER
$result = DB::statement("ALTER TABLE customer_password_otps MODIFY COLUMN otp VARCHAR(255) NOT NULL");
file_put_contents('/tmp/fix_result.txt', 'ALTER_RESULT: ' . ($result ? 'true' : 'false') . PHP_EOL, FILE_APPEND);

// Verify
$cols = DB::select("SELECT COLUMN_TYPE as ct FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='customer_password_otps' AND COLUMN_NAME='otp' AND TABLE_SCHEMA='ecom_fashion'");
foreach ($cols as $col) {
    file_put_contents('/tmp/fix_result.txt', 'COLUMN_TYPE: ' . $col->ct . PHP_EOL, FILE_APPEND);
}

// Test insert
try {
    DB::table('customer_password_otps')->insert([
        'email' => 'fix_test@test.com',
        'otp' => '517363',
        'expires_at' => now()->addMinutes(10),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    file_put_contents('/tmp/fix_result.txt', 'INSERT_TEST: SUCCESS' . PHP_EOL, FILE_APPEND);
    DB::table('customer_password_otps')->where('email', 'fix_test@test.com')->delete();
} catch (\Exception $e) {
    file_put_contents('/tmp/fix_result.txt', 'INSERT_TEST_ERROR: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
}

echo file_get_contents('/tmp/fix_result.txt');
