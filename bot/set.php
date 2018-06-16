<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 15.06.2018
 * Time: 22:51
 */

// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key  = '448267241:AAEf9HZur3R43pBix_KKw2VMKNWeEVMH5qY';
$bot_username = 'status_claster_bot';
$hook_url = 'https://tgm-bot.ru/handle.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}