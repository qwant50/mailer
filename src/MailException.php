<?php

namespace Qwant;

/**
 * Class MailerException
 * @package Qwant
 * @author   Sergey Malahov
 */
class MailerException extends \Exception
{
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        $message = __CLASS__ . ' ' . $message;
        parent::__construct($message, $code, $previous);
    }
}
