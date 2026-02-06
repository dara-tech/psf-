<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\User;
use Illuminate\Support\Facades\Hash;

echo "=== Reset Admin Password ===\n\n";

// Find admin user by name or email
$user = User::where('name', 'Admin')->orWhere('email', 'admin@admin.com')->first();

if (!$user) {
    echo "❌ Admin user not found!\n";
    echo "Creating admin user...\n";
    
    $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@admin.com',
        'password' => Hash::make('password')
    ]);
    
    // Assign administrator role if Spatie is available
    if (method_exists($user, 'assignRole')) {
        try {
            $user->assignRole('administrator');
        } catch (Exception $e) {
            echo "⚠️  Could not assign role (this is okay if roles don't exist yet)\n";
        }
    }
    
    echo "✅ Admin user created!\n\n";
} else {
    // Reset password
    $user->password = Hash::make('password');
    $user->save();
    echo "✅ Password reset successfully!\n\n";
}

echo "Login Credentials:\n";
echo "  Name/Username: " . $user->name . "\n";
echo "  Email: " . $user->email . "\n";
echo "  Password: password\n\n";
echo "Access at: http://127.0.0.1:8000/login\n";

