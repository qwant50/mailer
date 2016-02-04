ITCourses framework mailer component README
============

**ITCourses framework mailer component**



## Installation

The preferred way to install this ITCourses framework mailer component is through [composer](http://getcomposer.org/download/).

Either run

```sh
php composer.phar require "qwant/mailer"
```

or add

```json
"qwant/mailer": "~2.0.*"
```

to the require section of your composer.json.


## Usage

###1. You MUST to set transport field in src/Mailer/config/configMailer.php.

```php
return [
    'host' => 'smtp.domain.com',
    'port' => 587,
    'smtp_username' => 'username',
    'smtp_password' => 'password',
    'mailFrom' => 'transportMailAddress@domain.com',
    'debug' => 5,  //  0 - disable messages
];
```

###2. Set some message headers "RECOMMENDED":

```php
$mailSMTP = new \Qwant\Mailer\Mailer();

$mailSMTP->headers['Error-to'] = 'example@domain.com';
$mailSMTP->headers['From'] = 'example@domain.com';
$mailSMTP->headers['To'] = 'example@domain.com';
$mailSMTP->headers['Subject'] = 'Text field.';
```

###3. Set body and mailTo fields MUST.

```php
$mailSMTP->body = 'Message\'s body.';
$mailSMTP->mailTo = 'example@domain.com';
```

###4. Send a message

```php
if ($mailSMTP->sendMail()) {
    // Success
} else {
    // Error
};
```

Copyright © 2015-2016, ITCourses
