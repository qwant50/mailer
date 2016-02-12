<?php

namespace Qwant\transports;

use Qwant\Message;

/**
 * Mailer transport abstract class.
 */
abstract class AbstractTransport
{
    private $fileAdapter;
    private $logger;

    abstract public function send(Message $message, array $config);

    public function __construct()
    {
        $this->fileAdapter = new \Rioter\Logger\Adapters\FileAdapter('log.txt');
        var_dump($this->fileAdapter);
        $this->fileAdapter->setAdapterName('fileAdapter');

        $this->logger = new \Rioter\Logger\Logger($this->fileAdapter);
    }

    public function log($infoMessage)
    {
        $this->logger->info(strip_tags($infoMessage), array());
    }
}