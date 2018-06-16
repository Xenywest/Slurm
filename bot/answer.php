<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 15.06.2018
 * Time: 18:10
 */
include 'vendor/autoload.php';
require __DIR__ . '/vendor/autoload.php';

use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

$API_KEY  = '448267241:AAEf9HZur3R43pBix_KKw2VMKNWeEVMH5qY';
$BOT_NAME = 'status_claster_bot';

$telegram = new Telegram($API_KEY, $BOT_NAME);

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
        $data = array('chat_id' => $raw['chat_id'], 'text' => "Job $job_id finished");
        $result = Request::sendMessage($data);
        if ($result->isOk()) {
            echo 'Message sent succesfully to: ' . $raw['chat_id'];
        } else {
            echo 'Sorry message not sent to: ' . $raw['chat_id'];
        }
    }
}

function decode_content($content)
{
    $content = base64_decode($content);
    return json_decode($content,true);
}