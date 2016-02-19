<?php
/**
 * Created by PhpStorm.
 * User: Qwant
 * Date: 19-Feb-16
 * Time: 18:18
 */

namespace Qwant\transports;

use Mandrill;
use Qwant\Message;
use Qwant\MailerException;

class MandrillTransport extends AbstractTransport
{

    /**
     * @param Message $message
     * @param array $config
     * @return bool
     */
    public function send(Message $message, array $config = [])
    {
        //require_once ('Mandrill.php');
        $mandrill = new Mandrill();
        var_dump($mandrill->users->info('LMRgODKDpNQJDR7jp0UAvg'));

    }
}