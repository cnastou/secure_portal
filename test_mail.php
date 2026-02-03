<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;

try {
    Mail::raw('Test email from Laravel SMTP', function ($message) {
        $message->to('c.nastou@gmail.com') // Change to your test email
                ->subject('SMTP Test');
    });
    echo "Email sent successfully!\n";
} catch (Exception $e) {
    echo "Error sending email: " . $e->getMessage() . "\n";
}