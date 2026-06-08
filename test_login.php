<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/login', 'GET', [], [], [], ['HTTP_REFERER' => 'http://localhost/']);
$response = $kernel->handle($request);
echo "Session url.intended: " . $request->session()->get('url.intended') . "\n";
