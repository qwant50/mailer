<?php

/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:58
 */

namespace Qwant;


class Mailer
{
    const EOL = "\r\n";
    public $options = [];
    public $connect;
    public $mailTo;
    public $headers = [];
    public $body;

    public $timeLimit = 2;
    public $timeOut;
    private $lastLines;

    public function addHeader($name, $value = null)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    private function echoInfo($infoMessage)
    {
        if ($this->options['debug'] > 0) {
            echo $infoMessage;
        }
    }

    private function sendCommand($command)
    {
        $this->echoInfo('<span style="color : green">' . htmlspecialchars($command) . '</span><br>');
        if ($this->connect) {
            fputs($this->connect, $command . self::EOL);
            $this->getLines();
            $this->echoInfo($this->lastLines . '<br>');
        } else {
            $this->echoInfo('<span style="color : red">connection lost!</span>');
        }
        return $this->lastLines;
    }

    public function sendMail()
    {
        return ($this->options['transport'] == 'smtp')
            ? $this->sendViaSMTP() : (($this->options['transport'] == 'mail')
                ? $this->sendViaSendMail() : false);
    }

    public function sendViaSendMail()
    {
        $subject = '';
        $preparedHeaders = '';
        foreach ($this->headers as $key => $header):
            if (strtolower(trim($key)) == 'subject') {
                $subject = $header;
            } else {
                $preparedHeaders .= $key . ': ' . $header . self::EOL;
            }
        endforeach;
        return mail($this->mailTo, $subject, $this->body, $preparedHeaders);
    }

    /**
     * @return bool
     * @throws MailException
     */
    public function sendViaSMTP()
    {
        if (!$this->options['host']) {
            throw new MailException("Error mail send: Host isn\'t defined");
        }
        if (!$this->options['port']) {
            throw new MailException("Error mail send: Port isn\'t defined");
        }
        $errno = $errstr = '';
        if (!$this->connect = fsockopen($this->options['host'], $this->options['port'], $errno, $errstr, 30)) {
            return false;
        } else {
            // expectedResult = 220 smtp43.i.mail.ru ESMTP ready
            $this->getLines();
            $this->echoInfo($this->lastLines . '<br>');
            if (substr($this->lastLines, 0, 3) != '220') {
                return false;
            }

            // expectedResult = 220 2.0.0 Start TLS
            if (substr($this->sendCommand('STARTTLS'), 0, 3) != '220') {
                return false;
            };
            stream_socket_enable_crypto($this->connect, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);

            // HELO/EHLO  command for greeting with server  HELO = SMTP, EHLO = ESMTP.  EHLO is better.
            $this->sendCommand('EHLO ' . $_SERVER["SERVER_NAME"]);

            $this->sendCommand('AUTH LOGIN');
            $this->echoInfo(substr($this->lastLines, 0, 4) . base64_decode(substr($this->lastLines, 3)) . '<br>');

            // username send in base64
            $this->sendCommand(base64_encode($this->options['smtp_username']));
            $this->echoInfo(substr($this->lastLines, 0, 4) . base64_decode(substr($this->lastLines, 3)) . '<br>');

            // password send in base64
            $this->sendCommand(base64_encode($this->options['smtp_password']));

            $this->sendCommand('MAIL FROM: <' . $this->options['mailFrom'] . '>');  // Return-Path

            $this->sendCommand('RCPT TO: <' . $this->mailTo . '>');

            $this->sendCommand('DATA');

            foreach ($this->headers as $key => $header):
                $this->sendCommand($key . ': ' . $header);
            endforeach;
            $this->sendCommand('');  // empty line to separate headers from body

            $this->sendCommand($this->body);

            // expectedResult = 250 OK id=1aOrwv-00040C-2x
            if (substr($this->sendCommand('.'), 0, 3) != '250') {
                return false;
            };

            $this->sendCommand('QUIT');
            fclose($this->connect);

            return true;
        }
    }

    private function getLines()
    {
        // If the connection is bad, give up straight away
        if (!is_resource($this->connect)) {
            return '';
        }
        $data = '';
        $endtime = 0;
        stream_set_timeout($this->connect, 1);
        if ($this->timeLimit > 0) {
            $endtime = time() + $this->timeLimit;
        }
        while (is_resource($this->connect) && !feof($this->connect)) {
            $str = @fgets($this->connect, 515);
            $data .= $str . '<br>';
            // If 4th character is a space, we are done reading, break the loop, micro-optimisation over strlen
            if ((isset($str[3]) and $str[3] == ' ')) {
                break;
            }
            // Timed-out? Log and break
            $info = stream_get_meta_data($this->connect);
            if ($info['timed_out']) {
                break;
            }
            // Now check if reads took too long  EXTRA!!
            if ($endtime and time() > $endtime) {
                break;
            }
        }
        $this->lastLines = $data;
    }
}