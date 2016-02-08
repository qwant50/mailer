<?php
/**
 * Created by PhpStorm.
 * User: phpstudent
 * Date: 2/2/16
 * Time: 5:14 PM
 */

namespace Qwant\Test\MailerTest;

use Qwant\Mailer;
use Qwant\Config;


class MailerTest extends \PHPUnit_Framework_TestCase
{
    public function testGoodConfigurationSmtpTransport()
    {
        ob_start();
        $confObj = new Config(__DIR__ . '/src/Mailer/configs/');
        $mailSMTP = new Mailer();
        $mailSMTP->options = $confObj->getData('mailer');
        $mailSMTP->options['transport'] = 'smtp';

        $_SERVER["SERVER_NAME"] = 'testLocalHost';
        // This is optional headers for example only
        $mailSMTP->addHeader('Error-to', 'sergeyhdd@mail.ru')
            ->addHeader('Subject', 'Must to WORK!')
            ->addHeader('To', 'dasd_90@hotmail.com')
            ->addHeader('From', 'sergeyhdd@mail.ru')
            ->addHeader('Content-Type', 'text/html; charset=UTF-8');

        // Body & mailTo MUST!
        $mailSMTP->body = 'Right headers. Right body. This is test message sent via SMTP module.';
        $mailSMTP->mailTo = 'qwantonline@gmail.com';

        $this->assertEquals(true, $mailSMTP->sendMail());
        unset($mailSMTP);
        ob_end_clean();
    }

    public function testGoodConfigurationMailTransport()
    {
        ob_start();
        $confObj = new Config(__DIR__ . '/src/Mailer/configs/');
        $mailSMTP = new Mailer();
        $mailSMTP->options = $confObj->getData('mailer');
        $mailSMTP->options['transport'] = 'mail';
        $_SERVER["SERVER_NAME"] = 'testLocalHost';
        // This is optional headers for example only
        $mailSMTP->addHeader('Error-to', 'sergeyhdd@mail.ru')
            ->addHeader('Subject', 'Must to WORK!')
            ->addHeader('To', 'dasd_90@hotmail.com')
            ->addHeader('From', 'sergeyhdd@mail.ru')
            ->addHeader('Content-Type', 'text/html; charset=UTF-8');

        // Body & mailTo MUST!
        $mailSMTP->body = 'Right headers. Right body. This is test message sent via SMTP module.';
        $mailSMTP->mailTo = 'qwantonline@gmail.com';

        $this->assertEquals(true, $mailSMTP->sendMail());
        unset($mailSMTP);
        ob_end_clean();
    }
}