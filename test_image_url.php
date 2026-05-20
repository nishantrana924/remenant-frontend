<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = App\Models\Order::find(7);
if ($order) {
    foreach($order->orderItems as $item) {
        if ($item->product) {
            echo "Product: " . $item->product->name . "\n";
            echo "Image: " . $item->product->image . "\n";
            echo "Image URL: " . $item->product->image_url . "\n";
        }
    }
}
