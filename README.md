ITCourses framework mailer component README
============

**ITCourses framework mailer component**



## Installation

The preferred way to install this ITCourses framework mailer component is through [composer](http://getcomposer.org/download/).

Either run

```sh
php composer.phar require "qwant50/mailer"
```

or add

```json
"qwant50/mailer": "~3.0.*"
```

to the require section of your composer.json.


## Usage

####1. You MUST to init `$config` array from the config file `path/to/config/mailer.php`

```php
return [
    'transport' => 'SmtpTransport',
    'host' => 'smtp.domain.com',
    'port' => 587,
    'smtp_username' => 'username',
    'smtp_password' => 'password',
    'mailFrom' => 'transportMailAddress@domain.com',
    'debug' => 5,  //  0 - disable debug messages
];
```

####2. Set some message headers. "RECOMMENDED"

```php
$message = new Message();

$message->addHeader('Error-to', 'example@domain.com')
        ->addHeader('From', 'example@domain.com')
        ->addHeader('To', 'example@domain.com')
        ->addHeader('Subject', 'Text field.');
```

####3. Set body and mailTo fields MUST

```php
$message->setBody('Message's body.');
$message->setMailTo('example@domain.com');
```

####4. Send a message

```php
$mailer = new Mailer($config);

if ($mailer->send($message)) {
    // Success
} else {
    // Error
};
```

Copyright © 2015-2016, ITCourses
