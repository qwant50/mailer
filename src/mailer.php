<?php

/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:58
 */

namespace qwwantmailer;

class Mailer
{
    public $CRLF = "\r\n";
    public $host = 'smtp.mail.ru';
    public $port = 587;
    public $smtp_username = 'sergeyhdd@mail.ru';
    public $smtp_password = 'kansai50mai';
    public $error;
    public $do_debug = 5;
    public $connect;
    public $to;
    public $subject;
    public $message;
    public $headers;

    public function set($to = '', $subject = '', $message = '', $headers = ''){
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->headers = $headers;
    }

    public function sendCommand($command){
        if ($this->connect) {
            fputs($this->connect, $command . $this->CRLF);
            if($this->do_debug >= 1) {
                echo '<span style="color : green">' . htmlspecialchars($command) . '</span><br>';
            }
        }
        else {
            echo '<span style="color : red">connection lost!</span>';
        }
    }

    public function sendMail()
    {
        //SendMail("", "sam@dclink.com.ua", "DC-Link", "info@dclink.com.ua", "Trying to sent mailing list from$reason Тема:".$subject, $message, $headers, $baseaddr);
        //mail($this->to, $this->subject, $this->message, "From: webmaster@com.ua\r\n");
        $errno = $errstr = '';
        if ($this->connect = fsockopen($this->host,$this->port, $error, $error, 30)) {
            stream_set_timeout($this->connect, 1);
            echo '<span style="color : green">Connected to: '. $this->host . ':' . $this->port . '</span><br>';
            echo $this->get_lines() . '<br>';

            $this->sendCommand('STARTTLS');
            echo $this->get_lines() . '<br>';
            stream_socket_enable_crypto($this->connect, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);

            $this->sendCommand('HELO ' . $_SERVER["SERVER_NAME"]);
            echo $this->get_lines() . '<br>';



            $this->sendCommand('AUTH LOGIN');
            $temp = $this->get_lines();
            echo substr($temp,0,4) . base64_decode(substr($temp,3)) . '<br><br>';

            $this->sendCommand(base64_encode($this->smtp_username));
            $temp = $this->get_lines();
            echo substr($temp,0,4) . base64_decode(substr($temp,3)) . '<br><br>';

            $this->sendCommand(base64_encode($this->smtp_password));
            echo $this->get_lines() . '<br>';



            $this->sendCommand('MAIL FROM: <sergeyhdd@mail.ru>');
            echo $this->get_lines() . '<br>';



            $this->sendCommand('RCPT TO: <qwantonline@gmail.com>');
            echo $this->get_lines() . '<br>';

            $this->sendCommand('DATA');
            echo $this->get_lines() . '<br>';

            $this->sendCommand('Test message!');
            echo $this->get_lines() . '<br>';

            $this->sendCommand('.');
            echo $this->get_lines() . '<br>';

            $this->sendCommand('QUIT');
            echo $this->get_lines() . '<br>';
            fclose($this->connect);
        }
        exit;
    }

    private function get_lines() {
        $data = '';
        while($str = fgets($this->connect,255)) {
            $data .= $str . '<br>';
        }
        return $data;
    }
}