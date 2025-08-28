<?php

require_once 'vendor/autoload.php';

use Illuminate\Http\Request;

// Create a simple test to see the actual response format
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create test user
$user = \App\Models\User::factory()->create([
    'type' => 'supplier',
    'status' => 'active',
    'email' => 'test@example.com',
    'password' => bcrypt('password123')
]);

// Test the login endpoint
$request = Request::create('/vendor/v1/login', 'POST', [
    'username' => 'test@example.com',
    'password' => 'password123'
]);

$response = $app->handle($request);
echo "Status: " . $response->getStatusCode() . "\n";
echo "Content: " . $response->getContent() . "\n";

// Clean up
$user->delete();
