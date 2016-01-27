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
    public $doDebug = 5;
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
     */
    public function __Construct($host, $port, $smtp_username, $smtp_password, $mailFrom)
    {
        $this->host = $host;
        $this->port = $port;
        $this->smtp_password = $smtp_password;
        $this->smtp_username = $smtp_username;
        $this->mailFrom = $mailFrom;
    }

    public function sendCommand($command, $printEcho = false)
    {
        if ($this->connect) {
            fputs($this->connect, $command . $this->CRLF);
            if ($this->doDebug >= 1) {
                echo '<span style="color : green">' . htmlspecialchars($command) . '</span><br>';
                if ($printEcho) {
                    echo $this->get_lines() . '<br>';
                }
            };
        } else {
            echo '<span style="color : red">connection lost!</span>';
        }
    }

    public function sendMail($mailTo, $message)
    {
        $errno = $errstr = '';
        if ($this->connect = fsockopen($this->host, $this->port, $error, $error, 30)) {
            // stream_set_timeout($this->connect, 1);
            echo '<span style="color : green">Connected to: ' . $this->host . ':' . $this->port . '</span><br>';
            // expectedResult = 220 smtp43.i.mail.ru ESMTP ready
            echo $this->get_lines() . '<br>';

            $this->sendCommand('STARTTLS', true);
            // expectedResult = 220 2.0.0 Start TLS
            stream_socket_enable_crypto($this->connect, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);

            // HELO/EHLO  command for greeting with server
            $this->sendCommand('EHLO ' . $_SERVER["SERVER_NAME"], true);

            $this->sendCommand('AUTH LOGIN');
            $temp = $this->get_lines();
            echo substr($temp, 0, 4) . base64_decode(substr($temp, 3)) . '<br><br>';

            $this->sendCommand(base64_encode($this->smtp_username));
            $temp = $this->get_lines();
            echo substr($temp, 0, 4) . base64_decode(substr($temp, 3)) . '<br><br>';

            $this->sendCommand(base64_encode($this->smtp_password), true);

            $this->sendCommand('MAIL FROM: <' . $this->mailFrom . '>', true);

            $this->sendCommand('RCPT TO: <' . $mailTo . '>', true);

            $this->sendCommand('DATA', true);

            $this->sendCommand($message, true);

            $this->sendCommand('.', true);

            $this->sendCommand('QUIT', true);
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