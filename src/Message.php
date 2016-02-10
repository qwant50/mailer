<?php
/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 10-Feb-16
 * Time: 19:00
 */

namespace Qwant;


class Message
{

    public $mailTo;
    public $headers = [];
    public $body;

    public function addHeader($name, $value = null)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody($value)
    {
        $this->body = $value;
        return $this;
    }
    public function setMailTo($value)
    {
        $this->mailTo = $value;
        return $this;
    }
}