<?php

namespace Qwant\transports;

use Qwant\Message;
/**
 * Mailer interface.
 */
interface IMailer
{
      function send(Message $message, array $config);
}