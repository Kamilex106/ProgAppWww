<?php

function loadEnv($path) {
    if (!file_exists($path)) {
        echo $path;
        throw new Exception('.env file not found');
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;

        [$name, $value] = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
        putenv("$name=$value");
    }
}

// Load the .env file
loadEnv(__DIR__ . '/.env');

$dbHost = getenv('DB_HOST');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');
$dbName = getenv('DB_NAME');
$captchaKey = getenv('CAPTCHA_KEY');
$captchaSecret = getenv('CAPTCHA_SECRET');

?>