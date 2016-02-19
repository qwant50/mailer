<?php


namespace Qwant;

/**
 * Class Mailer
 * @package Qwant
 * @author   Sergey Malahov
 */
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
     * Adapter for selected a transport
     *
     * @param Message $message
     * @return bool
     * @throws MailerException
     */
    public function send(Message $message)
    {
        $adapterClass = 'Qwant\\transports\\' . ucfirst($this->config['transport']);
        if (!class_exists($adapterClass) || !is_subclass_of($adapterClass, 'Qwant\\transports\\AbstractTransport')) {
            throw new MailerException("Transport `" . $this->config['transport'] . "` unknown.");
        } else {
            $transport = new $adapterClass;
        }
        return $transport->send($message, $this->config);
    }
}