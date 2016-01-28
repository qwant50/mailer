<?php

/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:58
 */

namespace qwantmailer;

use qwantmailer\config\Config;

class Mailer extends Config
{
    public $CRLF = "\r\n";
    public $connect;
    public $body;
    public $headers = [];

    public $Timelimit = 2;
    public $Timeout;

    private function echoInfo($infoMessage)
    {
        if ($this->debug > 0) {
            echo $infoMessage;
        }
        return $infoMessage;
    }

    private function sendCommand($command)
    {
        $this->echoInfo('<span style="color : green">' . htmlspecialchars($command) . '</span><br>');
        if ($this->connect) {
            fputs($this->connect, $command . $this->CRLF);
            $response = $this->get_lines();
            $this->echoInfo($response . '<br>');
        } else {
            $this->echoInfo('<span style="color : red">connection lost!</span>');
        }
        return $response;
    }


    /**
     * @param $mailTo string - receiver of email message
     * @param $message string
     * @return bool
     */
    public function sendMail($mailTo, $message)
    {
        $errno = $errstr = '';
        if ($this->connect = fsockopen($this->host, $this->port, $errno, $errstr, 30)) {
            // expectedResult = 220 smtp43.i.mail.ru ESMTP ready
            if (substr($this->echoInfo($this->get_lines() . '<br>'), 0, 3) != '220') {
                return false;
            }

            // expectedResult = 220 2.0.0 Start TLS
            if (substr($this->sendCommand('STARTTLS'), 0, 3) != '220') {
                return false;
            };
            stream_socket_enable_crypto($this->connect, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);

            // HELO/EHLO  command for greeting with server  HELO = SMTP, EHLO = ESMTP.  EHLO is better.
            $this->sendCommand('EHLO ' . $_SERVER["SERVER_NAME"]);

            $response = $this->sendCommand('AUTH LOGIN');
            $this->echoInfo(substr($response, 0, 4) . base64_decode(substr($response, 3)) . '<br>');

            // username send in base64
            $response = $this->sendCommand(base64_encode($this->smtp_username));
            $this->echoInfo(substr($response, 0, 4) . base64_decode(substr($response, 3)) . '<br>');

            // password send in base64
            $this->sendCommand(base64_encode($this->smtp_password));

            $this->sendCommand('MAIL FROM: <' . $this->mailFrom . '>');  // Return-Path

            $this->sendCommand('RCPT TO: <' . $mailTo . '>');

            $this->sendCommand('DATA');

            if ($this->headers) {
                foreach ($this->headers as $key => $header):
                    $this->sendCommand($key .': '. $header);
                endforeach;
                $this->sendCommand('');  // empty line to separate headers from body
            }

            $this->sendCommand($message);

            $this->sendCommand('.');

            $this->sendCommand('QUIT');
            fclose($this->connect);

            return true;
        };
        return false;
    }

    private function get_lines()
    {
        // If the connection is bad, give up straight away
        if (!is_resource($this->connect)) {
            return '';
        }
        $data = '';
        $endtime = 0;
        stream_set_timeout($this->connect, 1);
        if ($this->Timelimit > 0) {
            $endtime = time() + $this->Timelimit;
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
        return $data;
    }
}