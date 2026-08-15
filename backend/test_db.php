<?php

use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Contracts\Console\Kernel;

try {
    // Tải Laravel bootstrap
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';

    $kernel = $app->make(Kernel::class);
    $kernel->bootstrap();

    $product = Product::with(['productVariants.attributeValues.attribute'])->find(2);

    $data = [
        'attributes' => Attribute::all()->toArray(),
        'product' => $product ? $product->toArray() : null,
    ];

    file_put_contents('storage/test_output.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo "SUCCESS\n";
} catch (Throwable $e) {
    echo 'ERROR: '.$e->getMessage()."\n";
    echo 'TRACE: '.$e->getTraceAsString()."\n";
}
