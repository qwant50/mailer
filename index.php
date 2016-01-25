<?php
/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:57
 */

$mymail = new \qwwantmailer\Mailer();
$mymail->set('qwantonline@gmail.com','subject','body', "From: webmaster@com.ua\r\n");
$mymail->sendMail();

