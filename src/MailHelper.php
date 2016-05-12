<?php

abstract class MailHelper {

  public static function send($recipients, $subject, $body) {
    $mail = new PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->isHTML(FALSE);
    
    $mail->isSendmail();

    $mail->setFrom('no-reply@esfbook.net', 'ESFBOOK');

    foreach ($recipients as $recipient) {
      $mail->addAddress($recipient);
    }

    $mail->Subject = $subject;

    if (is_array($body)) {
      $mail->Body = Flight::view()->fetch('mail/' . $body[0], $body[1]);
    }
    else {
      $mail->Body = $body;
    }

    if (!$mail->send()) {
      throw new Exception($mail->ErrorInfo, 500);
    }
  }

}
