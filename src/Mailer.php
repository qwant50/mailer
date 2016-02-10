<?php

/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:58
 */

namespace Qwant;

use Qwant\transports\SmtpMail;
use Qwant\transports\PhpMail;

class Mailer
{

    private $config;

    public function __construct(array $config = null)
    {
        if (is_null($config)){
            throw new MailerException("Config is empty.");
        }
        $this->config = $config;
    }

    public function send(Message $message)
    {
        if ('smtp' == $this->config['transport']) {
            $transport = new SmtpMail();
        }
        elseif ('mail' == $this->config['transport']){
            $transport = new PhpMail();
        }
        else {
            throw new MailerException("Transport '. $this->config['transport'] . ' unknown.");
        }
        return $transport->send($message, $this->config);
    }
}