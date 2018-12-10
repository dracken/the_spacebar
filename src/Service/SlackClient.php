<?php

namespace App\Service;

use \App\Helper\LoggerTrait;
use Nexy\Slack\Client;

class SlackClient
{
    use LoggerTrait;

    private $slack;


    /**
     * @var Client
     */
    public function __construct(Client $slack)
    {

        $this->slack = $slack;
    }

    public function sendMessage(string $from, string $message)
    {

        $this->logInfo('Beaming a message to Slack!', [
            'message' => $message,
            'from' => $from,
            'ip' => $_SERVER['REMOTE_ADDR'],
        ]);

        /*
        if ($this->logger) {
            $this->logger->info("Beaming a message to Slack!");
        }
        */

        $slackMessage = $this->slack->createMessage()
            ->from($from)
            ->withIcon(':ghost:')
            ->setText($message);

        $this->slack->sendMessage($slackMessage);
    }
}