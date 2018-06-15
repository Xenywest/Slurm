<?php
/**
 * Created by PhpStorm.
 * User: Lenovo
 * Date: 15.06.2018
 * Time: 17:16
 */


require_once "vendor/autoload.php";

try
{
    $bot = new \TelegramBot\Api\Client('448267241:AAEf9HZur3R43pBix_KKw2VMKNWeEVMH5qY');

    $bot->command('start', function ($message) use ($bot) {
        $new_token = generateToken();
        $bot->sendMessage($message->getChat()->getId(),"Enter your unique authentication token in format\n/token <here>\n
or enter /token $new_token token and tell administrator this new one: $new_token\nKeep it safe!"  );
    });


    $bot->command('token', function ($message) use ($bot) {
        $exploded_message = $message->getText();
        $connection = new PDO('sqlite:protected/bot_db.db',null,null);

        $statement = $connection->prepare('SELECT * FROM users WHERE token = :token AND chat_id = :chat_id');
        $statement->execute(array(':token' => $exploded_message[1], ':chat_id' => $message->getChat()->getId()));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        if($row)
        {
            $bot->sendMessage($message->getChat()->getId(), 'You can\'t assign from this account and this token twice');
        }
        else
        {
            $query = $connection->prepare('INSERT INTO users (token, chat_id) VALUES (:token, :chat_id)');

            $query->execute( array( ':token'=>$exploded_message[1], ':chat_id'=>$message->getChat()->getId()) );
            $bot->sendMessage($message->getChat()->getId(), 'Successfully assigned token and your chat_id');
        }

    });

    $bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    $e->getMessage();
}

function generateToken()
{
    return rand(0,99).rand(0,99).rand(0,99).rand(0,99).rand(0,99).
        rand(0,99).rand(0,99).rand(0,99).rand(0,99).rand(0,99).rand(0,99).rand(0,99);
}