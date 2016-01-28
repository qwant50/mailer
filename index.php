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


$myMail = new \qwantmailer\Mailer();

$myMail->sendMail("qwantonline@gmail.com", "Hello! This is message!");  //"dasd_90@hotmail.com"

