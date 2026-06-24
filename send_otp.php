<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$email = $_POST['email'];

$otp = rand(100000,999999);

$mail = new PHPMailer(true);

try{

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'your email here';

    $mail->Password = 'YOUR_GMAIL_APP_PASSWORD';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    $mail->Port = 465;

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    $mail->setFrom(
        'YOUR_EMAIL_HERE@gmail.com',
        'Food Donate'
    );

    $mail->addAddress($email);

    $mail->Subject = 'Food Donate OTP Verification';

    $mail->Body =
    "Your OTP for Food Donate Signup is: ".$otp;

    $mail->send();

    echo $otp;

}
catch(Exception $e){

    echo "ERROR: ".$mail->ErrorInfo;

}

?>