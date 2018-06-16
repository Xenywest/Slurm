<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Longman\TelegramBot\Commands\SystemCommands;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Request;
/**
 * Start command
 *
 * Gets executed when a user first starts using the bot.
 */
class TokenCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'token';
    /**
     * @var string
     */
    protected $description = 'Assign token';
    /**
     * @var string
     */
    protected $usage = '/token';
    /**
     * @var string
     */
    protected $version = '1.1.0';
    /**
     * @var bool
     */
    protected $private_only = true;
    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        $exploded_message = explode(' ', $message->getText());
        $connection = new \PDO('sqlite:../protected/bot_db.db',null,null);

        $statement = $connection->prepare('SELECT * FROM users WHERE token = :token AND chat_id = :chat_id');
        $statement->execute(array(':token' => $exploded_message[1], ':chat_id' => $chat_id));
        $row = $statement->fetch(\PDO::FETCH_ASSOC);
        if($row)
        {
            $data = array('chat_id' => $chat_id,
                'text' => 'You can\'t assign from this account and this token twice'  );
        }
        else
        {
            $query = $connection->prepare('INSERT INTO users (token, chat_id) VALUES (:token, :chat_id)');

            $query->execute( array( ':token'=>$exploded_message[1], ':chat_id'=>$message->getChat()->getId()) );
            $data = array('chat_id' => $chat_id,
                'text' => 'Successfully assigned token and your chat_id');
        }
        return Request::sendMessage($data);
    }
}