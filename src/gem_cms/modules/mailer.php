<?php

/**
* Mailer
*
* @copyright   Copyright (c) 2018 Gert-Jan Wille (http://www.gert-janwille.com)
* @version     v1.0.0
* @author      Gert-Jan Wille <hello@gert-janwille.be>
*
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require  WWW_ROOT . '/libs/' . DS . 'autoload.php';

class mailer {
	public static function sendMail($to,$subject,$htmltext) {
		$mail = new PHPMailer(true);

		$mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $_ENV['MAIL_HOST'];  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = $_ENV['MAIL_USERNAME'];                 // SMTP username
    $mail->Password = $_ENV['MAIL_PASSWORD'];                           // SMTP password
    // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = $_ENV['MAIL_PORT'];                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('hello@gert-janwille.com', 'Your CMS');
    $mail->addAddress($to);     // Add a recipient

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $htmltext;

		return $mail->send();

	}

}
?>
