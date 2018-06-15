<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 15.06.2018
 * Time: 18:10
 */
include 'vendor/autoload.php';

$bot = new \TelegramBot\Api\BotApi('448267241:AAEf9HZur3R43pBix_KKw2VMKNWeEVMH5qY');

function decode_content($content)
{
    $content = base64_decode($content);
    return json_decode($content,true);
}

$text = $_POST['data'];
$decoded_text = decode_content($text);

$connection = new PDO('sqlite:protected/bot_db.db',null,null);
$statement = $connection->prepare('SELECT chat_id FROM users WHERE token = :token');
foreach ($decoded_text as $job_id => $data)
{
    $statement->execute(array(':token' => $data));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $raw)
    {
        $bot->sendMessage($raw['chat_id'], "$job_id выполнено");
    }
}