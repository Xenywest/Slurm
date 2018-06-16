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
class StartCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'start';
    /**
     * @var string
     */
    protected $description = 'Start command';
    /**
     * @var string
     */
    protected $usage = '/start';
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
        $new_token = $this->generateToken();

        $data = array('chat_id' => $chat_id, 'text'=>"Enter your unique authentication token in format\n/token <here>\n
or enter /token $new_token token and tell administrator this new one: $new_token\nKeep it safe!"  );

        return Request::sendMessage($data);
    }

    private function generateToken()
    {
        return rand(0,99).rand(0,99).rand(0,99).rand(0,99).rand(0,99).
            rand(0,99).rand(0,99).rand(0,99).rand(0,99).rand(0,99).rand(0,99).rand(0,99);
    }
}