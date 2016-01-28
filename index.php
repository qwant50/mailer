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

$config = new \qwantmailer\config\Config();
$myMail = new \qwantmailer\Mailer($config->host, $config->port, $config->smtp_username, $config->smtp_password, $config->mailFrom, $config->debug);

$myMail->sendMail("dasd_90@hotmail.com", "Hello! This is message!");

