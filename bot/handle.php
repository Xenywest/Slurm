<?php
/**
 * README
 * This configuration file is intended to run the bot with the webhook method.
 * Uncommented parameters must be filled
 *
 * Please note that if you open this file with your browser you'll get the "Input is empty!" Exception.
 * This is a normal behaviour because this address has to be reached only by the Telegram servers.
 */
// Load composer
require_once __DIR__ . '/vendor/autoload.php';
// Add you bot's API key and name
$bot_api_key  = '448267241:AAEf9HZur3R43pBix_KKw2VMKNWeEVMH5qY';
$bot_username = 'status_claster_bot';
// Define all paths for your custom commands in this array (leave as empty array if not used)
$commands_paths = array(
    __DIR__ . '/Commands/',
);
try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);
    // Add commands paths containing your custom commands
    $telegram->addCommandsPaths($commands_paths);

    $telegram->enableLimiter();
    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    //echo $e;
    // Log telegram errors
    Longman\TelegramBot\TelegramLog::error($e);
} catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
    // Silence is golden!
    // Uncomment this to catch log initialisation errors
    //echo $e;
}