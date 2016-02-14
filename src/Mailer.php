<?php


namespace Qwant;

use Qwant\transports\SmtpMail;
use Qwant\transports\PhpMail;

class Mailer
{

    private $config;


    /**
     * Mailer constructor. Set config data
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Adapter for selecting a transport
     *
     * @param Message $message
     * @return bool
     * @throws MailerException
     */
    public function send(Message $message)
    {
        if ('smtp' == $this->config['transport']) {
            $transport = new SmtpMail();
        } elseif ('mail' == $this->config['transport']) {
            $transport = new PhpMail();
        } else {
            throw new MailerException("Transport '. $this->config['transport'] . ' unknown.");
        }
        return $transport->send($message, $this->config);
    }
}