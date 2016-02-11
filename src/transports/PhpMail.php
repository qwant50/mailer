<?php

namespace Qwant\transports;

use Qwant\Message;


class PhpMail implements IMailer
{
    const EOL = "\r\n";

    /**
     * @param Message $message
     * @param array $config
     * @return bool
     */
    public function send(Message $message, array $config = [])
    {
        $subject = '';
        $preparedHeaders = '';
        foreach ($message->headers as $key => $header):
            if (strtolower(trim($key)) == 'subject') {
                $subject = $header;
            } else {
                $preparedHeaders .= $key . ': ' . $header . self::EOL;
            }
        endforeach;
        return mail($message->mailTo, $subject, $message->body, $preparedHeaders);
    }
}