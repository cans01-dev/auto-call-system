<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail extends PHPMailer {
  function __construct()
  {
    parent::__construct(true);

    // $this->SMTPDebug = SMTP::DEBUG_SERVER;
    $this->isSMTP();
    $this->Host = 'sv14935.xserver.jp';
    $this->SMTPAuth = true;
    $this->Username = 'autocall@e-ivr.net';
    $this->Password = 'cans555-';
    $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $this->Port = 465;
    $this->CharSet = 'UTF-8';  
  }
}

// $mail->setFrom('info@e-ivr.net', 'AutoCallシステム');
// foreach ($addresses as $address) $mail->addAddress($address);

// $mail->addAttachment('/var/tmp/file.tar.gz');
// $mail->addAttachment('/tmp/image.jpg', 'new.jpg');

// $mail->isHTML(true);
// $mail->Subject = 'Here is the subject';
// $mail->Body    = 'aaaあああThis is the HTML message body <b>in bold!</b>';
// $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

// $mail->send();
