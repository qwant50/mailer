<?php
/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:57
 */
$loader = require __DIR__ . '/vendor/autoload.php';

$mymail = new \qwantmailer\Mailer('smtp.mail.ru', 587, 'sergeyhdd@mail.ru', 'kansai50mai', 'sergeyhdd@mail.ru');

$mymail->sendMail('qwantonline@gmail.com','Test message from mailer module2!');

