<?php

namespace Qwant\Test\MailerTest;

use Qwant\Mailer;
use Qwant\Config;
use Qwant\Message;


class MailerTest extends \PHPUnit_Framework_TestCase
{

    public $conf;
    public $message;

    public function setUp()
    {
        $this->conf = new Config(dirname(dirname(__DIR__)) . '/src/configs/');
        $this->message = new Message();
        // This is optional headers for example only
        $this->message->addHeader('Error-to', 'sergeyhdd@mail.ru')
            ->addHeader('From', 'sergeyhdd@mail.ru')
            ->addHeader('Subject', 'Must to WORK!')
            ->addHeader('Content-Type', 'text/html; charset=UTF-8')
            ->setBody('Content-Type and charset added.')
            ->setMailTo('qwantonline@gmail.com');  // mailTo MUST!
    }

    public function testGoodConfigurationSmtpTransport()
    {
        ob_start();
        $data = $this->conf->getData('mailer.example');
        $_SERVER['SERVER_NAME'] = 'localhost';
        $mailer = new Mailer($data);

        $this->assertEquals(true, $mailer->send($this->message));
        unset($mailer);
        ob_end_clean();
    }

    public function testGoodConfigurationMailTransport()
    {
        ob_start();
        $data = $this->conf->getData('mailer');
        $data['transport'] = 'PhpMailTransport';
        $mailer = new Mailer($data);

        $this->assertEquals(true, $mailer->send($this->message));
        unset($mailer);
        ob_end_clean();
    }
}
