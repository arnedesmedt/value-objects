#!/usr/bin/env php
<?php

declare(strict_types=1);

// phpcs:ignore
include $_composer_autoload_path ?? __DIR__ . '/../vendor/autoload.php';

use ADS\ValueObjects\Service\EncryptDecryptService;

$allowedTypes = ['encrypt', 'decrypt'];

// Get the first argument from the command line
$type = $argv[1];

// Check if the type is allowed
if (! in_array($type, $allowedTypes)) {
    echo 'Invalid type. Allowed types are: ' . implode(', ', $allowedTypes) . PHP_EOL;
    exit(1);
}

// Ask secret key as hidden input
echo 'Enter secret key: ';
// phpcs:ignore
$inputSecret = `stty -echo; head -n1; stty echo`;
if ($inputSecret === false) {
    echo 'Error reading input' . PHP_EOL;
    exit(1);
}

$secret = trim($inputSecret);
echo PHP_EOL;
$_ENV[EncryptDecryptService::ENVIRONMENT_SECRET_KEY_KEY] = $secret;

// Ask for the message
echo 'Enter message: ';
$inputMessage = fgets(STDIN);
if ($inputMessage === false) {
    echo 'Error reading input' . PHP_EOL;
    exit(1);
}

$message = trim($inputMessage);

// Encrypt or decrypt the message
switch ($type) {
    case 'encrypt':
        $encrypted = EncryptDecryptService::encrypt($message);
        echo sprintf('Encrypted message: %s', $encrypted) . PHP_EOL;
        break;
    case 'decrypt':
        $decrypted = EncryptDecryptService::decrypt($message);
        echo sprintf('Decrypted message: %s', $decrypted) . PHP_EOL;
        break;
}
