<?php

namespace Qwant\transports;

use Qwant\Message;
use Qwant\MailerException;

/**
 * Class SmtpTransport
 * @package Qwant\transports
 * @author   Sergey Malahov
 */
class SmtpTransport extends AbstractTransport
{
    private $config;
    private $lastLines;
    private $connect;
    private $timeLimit = 2;

    /**
     *
     * Loggin info
     *
     * @param string $infoMessage
     */
    private function echoInfo($infoMessage)
    {
        if ($this->config['debug'] > 0) {
            $this->log(strip_tags($infoMessage));
        }
    }

    /**
     *
     * Send a command to the remote server
     *
     * @param string $command
     * @return string mixed  Answer from a remote server
     */
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

    /**
     * Send message via smtp protocol
     *
     * @param Message $message
     * @param array $config
     * @return bool
     * @throws MailerException
     */
    public function send(Message $message, array $config)
    {
        $this->config = $config;
        if (!$message->mailTo) {
            throw new MailerException("Error mail send: 'RCPT TO' isn\'t defined");
        }
        $errno = $errstr = '';
        if (!$this->connect = fsockopen($config['host'], $config['port'], $errno, $errstr, 30)) {
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
            $this->sendCommand(base64_encode($config['smtp_username']));
            $this->echoInfo(substr($this->lastLines, 0, 4) . base64_decode(substr($this->lastLines, 3)) . '<br>');

            // password send in base64
            $this->sendCommand(base64_encode($config['smtp_password']));

            $this->sendCommand('MAIL FROM: <' . $config['mailFrom'] . '>');  // Return-Path

            $this->sendCommand('RCPT TO: <' . $message->mailTo . '>');

            $this->sendCommand('DATA');


            foreach ($message->headers as $key => $header):
                $this->sendCommand($key . ': ' . $header);
            endforeach;
            $this->sendCommand('');  // empty line to separate headers from body

            $this->sendCommand($message->body);

            // expectedResult = 250 OK id=1aOrwv-00040C-2x
            if (substr($this->sendCommand('.'), 0, 3) != '250') {
                return false;
            };

            $this->sendCommand('QUIT');
            fclose($this->connect);

            return true;
        }
    }

    /**
     *
     * Gets answer from remote server
     *
     * @return string
     */
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
            // Now check if reads took too long EXTRA!!
            if ($endtime and time() > $endtime) {
                break;
            }
        }
        $this->lastLines = $data;
    }
}