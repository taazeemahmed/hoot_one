<?php
// Helper script to update .env file
$envFile = __DIR__ . '/.env';
$content = file_get_contents($envFile);

// Update database connection
$content = preg_replace('/DB_CONNECTION=sqlite/', 'DB_CONNECTION=mysql', $content);
$content = preg_replace('/# DB_HOST=127\.0\.0\.1/', 'DB_HOST=127.0.0.1', $content);
$content = preg_replace('/# DB_PORT=3306/', 'DB_PORT=3306', $content);
$content = preg_replace('/# DB_DATABASE=laravel/', 'DB_DATABASE=hoot_one', $content);
$content = preg_replace('/# DB_USERNAME=root/', 'DB_USERNAME=root', $content);
$content = preg_replace('/# DB_PASSWORD=/', 'DB_PASSWORD=', $content);

// Update app name
$content = preg_replace('/APP_NAME=Laravel/', 'APP_NAME=HootoneOne', $content);

file_put_contents($envFile, $content);
echo "Environment file updated successfully!\n";
