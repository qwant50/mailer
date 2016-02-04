<?php
/**
 * Created by PhpStorm.
 * User: phpstudent
 * Date: 2/2/16
 * Time: 5:14 PM
 */

//namespace Qwant\Mailer;

use Qwant\Mailer\Mailer;


class MailerTest extends \PHPUnit_Framework_TestCase
{
    public function testGoodConfiguration()
    {
        $mailSMTP = new Mailer();
        $_SERVER["SERVER_NAME"] = 'testLocalHost';
        // This is optional headers for example only
        $mailSMTP->headers['Error-to'] = 'sergeyhdd@mail.ru';
        $mailSMTP->headers['Subject'] = 'Must to WORK!';
        $mailSMTP->headers['To'] = 'qwantonline@gmail.com';
        $mailSMTP->headers['From'] = 'sergeyhdd@mail.ru';

        // Body & mailTo MUST!
        $mailSMTP->body = 'Right headers. Right body. This is test message sent via SMTP module.';
        $mailSMTP->mailTo = 'qwantonline@gmail.com';

        $this->assertEquals(true, $mailSMTP->sendMail());
    }
}
