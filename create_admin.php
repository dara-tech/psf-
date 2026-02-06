<?php

/**
 * Script to create a new admin user
 * Usage: php create_admin.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\User;
use Spatie\Permission\Models\Role;

echo "=== Create New Admin User ===\n\n";

// Get input from user
echo "Enter admin details:\n";
echo "Name (default: admin2): ";
$name = trim(fgets(STDIN)) ?: 'admin2';

echo "Email (default: admin2@admin.com): ";
$email = trim(fgets(STDIN)) ?: 'admin2@admin.com';

echo "Password (default: admin123): ";
$password = trim(fgets(STDIN)) ?: 'admin123';

// Check if user already exists
if (User::where('email', $email)->exists()) {
    echo "\n❌ Error: User with email '$email' already exists!\n";
    exit(1);
}

// Check if administrator role exists
$adminRole = Role::where('name', 'administrator')->first();
if (!$adminRole) {
    echo "\n❌ Error: Administrator role does not exist!\n";
    echo "Please run: php artisan db:seed --class=RoleSeed\n";
    exit(1);
}

try {
    // Create user
    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => bcrypt($password)
    ]);

    // Assign administrator role
    $user->assignRole('administrator');

    echo "\n✅ Admin user created successfully!\n";
    echo "   Name: $name\n";
    echo "   Email: $email\n";
    echo "   Password: $password\n";
    echo "   Role: administrator\n\n";
    echo "You can now login at: http://127.0.0.1:8000/login\n";
    
} catch (Exception $e) {
    echo "\n❌ Error creating user: " . $e->getMessage() . "\n";
    exit(1);
}

