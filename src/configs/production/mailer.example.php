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
    'transport' => 'SmtpTransport',
    'mandrill' => 'LMRgODKDpNQJDR7jp0UAvg',
    'host' => 'smtp.mail.ru',
    'port' => 587,
    'smtp_username' => 'sergeyhdd@mail.ru',
    'smtp_password' => '',
    'mailFrom' => 'sergeyhdd@mail.ru',
    'debug' => 5,
];
