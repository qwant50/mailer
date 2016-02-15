<?php

namespace Qwant\transports;

use Qwant\Message;
use Rioter\Logger\Adapters\FileAdapter;
use Rioter\Logger\Logger;

/**
 * Mailer transport abstract class.
 *
 * @author   Sergey Malahov
 */
abstract class AbstractTransport
{
    const EOL = "\r\n";
    private $fileAdapter;
    private $logger;

    abstract public function send(Message $message, array $config);

    /**
     *  Create a logger. Logging level is 'info' into file 'log.txt'
     *
     */
    public function __construct()
    {
        $this->fileAdapter = new FileAdapter('log.txt');
        $this->fileAdapter->setAdapterName('fileAdapter');
        $this->logger = new Logger($this->fileAdapter);
    }

    /**
     * @param string $infoMessage
     */
    public function log($infoMessage)
    {
        $this->logger->info(strip_tags($infoMessage), array());
    }
}