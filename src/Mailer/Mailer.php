<?php

/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 25-Jan-16
 * Time: 21:58
 */

namespace Qwant\Mailer;

class Mailer
{
    public $options = [];
    private $CRLF = "\r\n";
    public $connect;
    public $mailTo;
    public $headers = [];
    public $body;

    public $timeLimit = 2;
    public $timeOut;


    public function __construct()
    {
        $this->options['mailer'] = $this->loadConfig();
    }

    public function loadConfig($path = __DIR__ . '/config/mailerConfig.php')
    {
        if (is_file($path) && is_readable($path)) {
            return include $path;
        }
    }

    public function saveConfig($path = __DIR__ . 'config/mailerConfig.php')
    {
        $content = "<?php" . PHP_EOL . "return " . var_export($this->options['mailer'], true) . ";";
        return file_put_contents($path, $content);
    }

    private function echoInfo($infoMessage)
    {
        if ($this->options['mailer']['debug'] > 0) {
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

    public function sendMail(){
        return ($this->options['mailer']['transport'] == 'smtp1') ? $this->sendViaSMTP() : $this->options['mailer']['transport'] == 'smtp2'
        ? $this->sendViaSendMail() : false;
             }

    public function sendViaSendMail()
    {
        $subject = '';
        $preparedHeaders = '';
        if ($this->headers) {
            foreach ($this->headers as $key => $header):
                if (strtolower(trim($key)) == 'subject') {
                    $subject = $header;
                } else {
                    $preparedHeaders .= $key . ': ' . $header . $this->CRLF;
                }
            endforeach;
        }
        return mail($this->mailTo, $subject, $this->body, $preparedHeaders);
    }

    /**
     * @param $mailTo string - receiver of email message
     * @return bool
     */
    public function sendViaSMTP()
    {
        if (!$this->options['mailer']['host']) {
            throw new MailException("Error mail send: Host isn\'t defined");
        }
        $errno = $errstr = '';
        if ($this->connect = fsockopen($this->options['mailer']['host'], $this->options['mailer']['port'], $errno,
            $errstr, 30)
        ) {
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
            $response = $this->sendCommand(base64_encode($this->options['mailer']['smtp_username']));
            $this->echoInfo(substr($response, 0, 4) . base64_decode(substr($response, 3)) . '<br>');

            // password send in base64
            $this->sendCommand(base64_encode($this->options['mailer']['smtp_password']));

            $this->sendCommand('MAIL FROM: <' . $this->options['mailer']['mailFrom'] . '>');  // Return-Path

            $this->sendCommand('RCPT TO: <' . $this->mailTo . '>');

            $this->sendCommand('DATA');

            if ($this->headers) {
                foreach ($this->headers as $key => $header):
                    $this->sendCommand($key . ': ' . $header);
                endforeach;
                $this->sendCommand('');  // empty line to separate headers from body
            }

            $this->sendCommand($this->body);

            // expectedResult = 250 OK id=1aOrwv-00040C-2x
            if (substr($this->sendCommand('.'), 0, 3) != '250') {
                return false;
            };

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
        return $data;
    }
}