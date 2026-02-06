<?php

// Simple password reset script
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== Reset Admin Password ===\n\n";

// Hash the password
$hashedPassword = Hash::make('password');
echo "✅ Password hashed\n";

// Update admin user password
$result = DB::table('users')
    ->where('name', 'Admin')
    ->orWhere('email', 'admin@admin.com')
    ->update(['password' => $hashedPassword]);

if ($result > 0) {
    echo "✅ Password reset successfully!\n\n";
    echo "Login Credentials:\n";
    echo "  Name/Username: Admin\n";
    echo "  Email: admin@admin.com\n";
    echo "  Password: password\n\n";
    echo "Access at: http://127.0.0.1:8000/login\n";
} else {
    echo "⚠️  No user found. Trying to create one...\n";
    
    // Try to create the user
    try {
        $userId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => $hashedPassword,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        echo "✅ Admin user created with ID: $userId\n";
        echo "\nLogin Credentials:\n";
        echo "  Name/Username: Admin\n";
        echo "  Email: admin@admin.com\n";
        echo "  Password: password\n\n";
        echo "Access at: http://127.0.0.1:8000/login\n";
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
    }
}

