<?php

/**
 *  mailerConfig
 *  example:
 *  $transport = 'smtp';  transport = 'smtp' or 'mail';
 *  $host = 'smtp.mail.ru';
 *  $port = 587;
 *  $smtp_username = '********@mail.ru';
 *  $smtp_password = '*************';
 *  $mailFrom = '*********@mail.ru';
 *  $debug @var Integer 0 - no messages, 1 - info messages, 2 - info & error messages
 *
 */

return [
    'transport' => 'smtp',
    'host' => '127.0.0.1',
    'port' => 587,
    'smtp_username' => '',
    'smtp_password' => '',
    'mailFrom' => '',
    'debug' => 5,
];