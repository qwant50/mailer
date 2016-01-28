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
 *
 */

namespace qwantmailer\Config;

class Config
{
    public $host = '';
    public $port = 587;
    public $smtp_username = '';
    public $smtp_password = '';
    public $mailFrom = '';
    public $debug = 5;
}