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


$mailSMTP = new \qwantmailer\Mailer();

$mailSMTP->headers['X-Return-Path'] = 'sergeyhdd@mail.ru';
$mailSMTP->headers['Error-to'] = 'sergeyhdd@mail.ru';
$mailSMTP->sendMail("dasd_90@hotmail.com", "Right headers. Please check.");  //"dasd_90@hotmail.com"

