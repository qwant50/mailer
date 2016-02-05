<?php
/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:57
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = require __DIR__ . '/vendor/autoload.php';

$mailSMTP = new \Qwant\Mailer\Mailer();

// This is optional headers for example only
$mailSMTP->headers['Error-to'] = 'sergeyhdd@mail.ru';
$mailSMTP->headers['Subject'] = 'Must to WORK!';
$mailSMTP->headers['To'] = 'dasd_90@hotmail.com';
$mailSMTP->headers['From'] = 'sergeyhdd@mail.ru';
$mailSMTP->headers['Content-Type'] = 'text/html; charset=UTF-8';

// Body & mailTo MUST!
$mailSMTP->body = 'Content-Type and charset added.';
$mailSMTP->mailTo = 'qwantonline@gmail.com';  //dasd_90@hotmail.com


if ($mailSMTP->sendMail()) {
    echo 'Successe';
    // Success
} else {
    // Error
};