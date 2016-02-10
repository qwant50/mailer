<?php
/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 10-Feb-16
 * Time: 18:47
 */

namespace Qwant\transports;

use Qwant\Message;


class PhpMail implements IMailer
{
    const EOL = "\r\n";

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