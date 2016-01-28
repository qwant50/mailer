<?php
/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 27-Jan-16
 * Time: 14:30
 */

/**
 * Class config
 *  example:
 *  $host = 'smtp.mail.ru';
 *  $port = 587;
 *  $smtp_username = '********@mail.ru';
 *  $smtp_password = '*************';
 *  $mailFrom = '*********@mail.ru';
 *  $debug @var Integer 0 - no meesages, 1 - info messages, 2 - info & error messages
 *
 */

namespace qwantmailer\Config;

class Config
{
    public $host = '127.0.0.1';
    public $port = 587;
    public $smtp_username = '';
    public $smtp_password = '';
    public $mailFrom = '';
    public $debug = 5;
}