<?php

/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:58
 */

namespace qwantmailer;

class Mailer
{
    public $CRLF = "\r\n";
    public $host;
    public $port;
    public $smtp_username;
    public $smtp_password;
    public $mailFrom;
    public $debug = 5;
    public $error;
    public $connect;
    public $subject;
    public $message;
    public $headers;

    public $Timelimit = 1;
    public $Timeout;

    /**
     * @param $host string
     * @param $port int
     * @param $smtp_username string
     * @param $smtp_password string
     * @param $mailFrom string email address
     * @param $debug int 0 - no meesages, >0 - debug messages
     */
    public function __Construct($host, $port, $smtp_username, $smtp_password, $mailFrom, $debag)
    {
        $this->host = $host;
        $this->port = $port;
        $this->smtp_password = $smtp_password;
        $this->smtp_username = $smtp_username;
        $this->mailFrom = $mailFrom;
        $this->debug = $debag;
    }

    public function echoInfo($infoMessage)
    {
        if ($this->debug > 0) {
            echo $infoMessage;
        }
    }

    public function sendCommand($command)
    {
        if ($this->connect) {
            fputs($this->connect, $command . $this->CRLF);
            $this->echoInfo('<span style="color : green">' . htmlspecialchars($command) . '</span><br>');
            $this->echoInfo($this->get_lines() . '<br>');
        } else {
            $this->echoInfo('<span style="color : red">connection lost!</span>');
        }
    }

    public function sendMail($mailTo, $message)
    {
        $errno = $errstr = '';
        if ($this->connect = fsockopen($this->host, $this->port, $error, $error, 30)) {
            $this->echoInfo('<span style="color : green">Connected to: ' . $this->host . ':' . $this->port . '</span><br>');
            // expectedResult = 220 smtp43.i.mail.ru ESMTP ready
            $this->echoInfo($this->get_lines() . '<br>');

            $this->sendCommand('STARTTLS');
            // expectedResult = 220 2.0.0 Start TLS
            stream_socket_enable_crypto($this->connect, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);

            // HELO/EHLO  command for greeting with server
            $this->sendCommand('EHLO ' . $_SERVER["SERVER_NAME"]);

            $this->sendCommand('AUTH LOGIN');
            $temp = $this->get_lines();
            $this->echoInfo(substr($temp, 0, 4) . base64_decode(substr($temp, 3)) . '<br>');

            $this->sendCommand(base64_encode($this->smtp_username));
            $temp = $this->get_lines();
            $this->echoInfo(substr($temp, 0, 4) . base64_decode(substr($temp, 3)) . '<br>');

            $this->sendCommand(base64_encode($this->smtp_password));

            $this->sendCommand('MAIL FROM: <' . $this->mailFrom . '>');

            $this->sendCommand('RCPT TO: <' . $mailTo . '>');

            $this->sendCommand('DATA');

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