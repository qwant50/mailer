<?php
/**
 *  Skeleton application
 */

use Qwant\Config;
use Qwant\Mailer;
use Qwant\Message;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = require __DIR__ . '/vendor/autoload.php';

$conf = new Config(__DIR__ . '/src/configs/');

// Create a new message
$message = new Message();
// This is optional headers for example only
$message->addHeader('Error-to', 'sergeyhdd@mail.ru')
    ->addHeader('Subject', 'Must to WORK!')
    ->addHeader('From', 'sergeyhdd@mail.ru')  // strongly recommend
    ->addHeader('Content-Type', 'text/plain; charset=UTF-8')
    ->setBody('Content-Type and charset added.')
    ->setMailTo('web-PFUSOU@mail-tester.com');  // mailTo MUST!

// Use information about the transport from a config file
$mailer = new Mailer($conf->getData('mailer'));

if ($mailer->send($message)) {
    echo 'Success';
    // Success
} else {
    // Error
}