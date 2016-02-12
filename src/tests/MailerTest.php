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
        $confObj = new Config(__DIR__ . '/src/configs/');

        $message = new Message();
        // This is optional headers for example only
        $message->addHeader('Error-to', 'sergeyhdd@mail.ru')
            ->addHeader('Subject', 'Must to WORK!')
            ->addHeader('To', 'dasd_90@hotmail.com')
            ->addHeader('From', 'sergeyhdd@mail.ru')
            ->addHeader('Content-Type', 'text/html; charset=UTF-8')
            ->setBody('Content-Type and charset added.')
            ->setMailTo('qwantonline@gmail.com');  // mailTo MUST!

        $confObj->data['transport'] = 'smtp';
        $mailer = new Mailer($confObj->getData('mailer'));

        $this->assertEquals(true, $mailer->send($message));
        unset($mailer);
        ob_end_clean();
    }

    public function testGoodConfigurationMailTransport()
    {
        ob_start();
        $confObj = new Config(__DIR__ . '/src/configs/');

        $message = new Message();
        // This is optional headers for example only
        $message->addHeader('Error-to', 'sergeyhdd@mail.ru')
            ->addHeader('Subject', 'Must to WORK!')
            ->addHeader('To', 'dasd_90@hotmail.com')
            ->addHeader('From', 'sergeyhdd@mail.ru')
            ->addHeader('Content-Type', 'text/html; charset=UTF-8')
            ->setBody('Content-Type and charset added.')
            ->setMailTo('qwantonline@gmail.com');  // mailTo MUST!

        $confObj->data['transport'] = 'mail';
        $mailer = new Mailer($confObj->getData('mailer'));

        $this->assertEquals(true, $mailer->send($message));
        unset($mailer);
        ob_end_clean();
    }
}
