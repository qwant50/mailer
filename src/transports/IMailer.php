<?php

namespace Qwant\transports;

use Qwant\Message;

/**
 * Mailer transport interface.
 */
interface IMailer
{
    public function send(Message $message, array $config);
}