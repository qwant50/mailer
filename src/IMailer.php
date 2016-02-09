<?php

namespace Qwant;
/**
 * Mailer interface.
 */
interface IMailer
{
      function sendMail(Message $mail);
}