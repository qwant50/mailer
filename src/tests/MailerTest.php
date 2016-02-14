<?php

namespace Qwant\Test\MailerTest;

use Qwant\Mailer;
use Qwant\Config;
use Qwant\Message;


class MailerTest extends \PHPUnit_Framework_TestCase
{
    public function testGoodConfigurationSmtpTransport()
    {
        ob_start();
        $conf = new Config(dirname(dirname(__DIR__)) . '/src/configs/');

        $message = new Message();
        // This is optional headers for example only
        $message->addHeader('Error-to', 'sergeyhdd@mail.ru')
            ->addHeader('From', 'sergeyhdd@mail.ru')
            ->addHeader('Subject', 'Must to WORK!')
            ->addHeader('Content-Type', 'text/html; charset=UTF-8')
            ->setBody('Content-Type and charset added.')
            ->setMailTo('qwantonline@gmail.com');  // mailTo MUST!

        $data = $conf->getData('mailer.example');
        $_SERVER['SERVER_NAME'] = 'localhost';
        $mailer = new Mailer($data);

        $this->assertEquals(true, $mailer->send($message));
        unset($mailer);
        ob_end_clean();
    }

    public function testGoodConfigurationMailTransport()
    {
        ob_start();
        $conf = new Config(dirname(dirname(__DIR__))  . '/src/configs/');

        $message = new Message();
        // This is optional headers for example only
        $message->addHeader('Error-to', 'sergeyhdd@mail.ru')
            ->addHeader('Subject', 'Must to WORK!')
            ->addHeader('From', 'sergeyhdd@mail.ru')
            ->addHeader('Content-Type', 'text/html; charset=UTF-8')
            ->setBody('Content-Type and charset added.')
            ->setMailTo('qwantonline@gmail.com');  // mailTo MUST!

        $data = $conf->getData('mailer');
        $data['transport']= 'mail';
        $mailer = new Mailer($data);

        $this->assertEquals(true, $mailer->send($message));
        unset($mailer);
        ob_end_clean();
    }
}
