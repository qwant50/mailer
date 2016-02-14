<?php

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
        $this->addHeader('To', $value);
        return $this;
    }
}