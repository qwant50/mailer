<?php
/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:57
 */

use Qwant\Config;
use Qwant\Mailer;
use Qwant\Message;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = require __DIR__ . '/vendor/autoload.php';

$conf = new Config(__DIR__ . '/src/configs/');

$message = new Message();
// This is optional headers for example only
$message->addHeader('Error-to', 'sergeyhdd@mail.ru')
    ->addHeader('Subject', 'Must to WORK!')
    ->addHeader('From', 'sergeyhdd@mail.ru')
    ->addHeader('Content-Type', 'text/html; charset=UTF-8')
    ->setBody('Content-Type and charset added.')
    ->setMailTo('qwantonline@gmail.com');  // mailTo MUST!             :  dasd_90@hotmail.com

$mailer = new Mailer($conf->getData('mailer'));

if ($mailer->send($message)) {
    // Success
} else {
    // Error
}