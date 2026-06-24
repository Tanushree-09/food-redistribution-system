<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
require '../vendor/autoload.php';

$connection = mysqli_connect("localhost","root","","demo");

$id = $_GET['Fid'];

/* 1. Update donation status */
$query = "UPDATE food_donations SET status='requested' WHERE Fid='$id'";
$result = mysqli_query($connection, $query);

/* 2. Get donation details */
$donation_query = "SELECT * FROM food_donations WHERE Fid='$id'";
$donation_result = mysqli_query($connection, $donation_query);
$donation = mysqli_fetch_assoc($donation_result);

/* 3. Delivery persons by city */
$city = $donation['address'];

$delivery_query = "SELECT * FROM delivery_persons WHERE city='$city'";
$delivery_result = mysqli_query($connection, $delivery_query);

/* 4. Emails */
$donor_email = $donation['email'];

/* SIMPLE FIX (NO SESSION BUGS) */
$ngo_email = "YOUR_EMAIL_HERE@gmail.com";  // 🔥 hardcoded stable fix

/* 5. Safety check */
if (!$delivery_result || mysqli_num_rows($delivery_result) == 0) {
    die("No delivery persons found for city: " . $city);
}

/* 6. Send emails + insert delivery orders */
while ($delivery = mysqli_fetch_assoc($delivery_result)) {

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'YOUR_EMAIL_HERE@gmail.com';
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

        $mail->setFrom('YOUR_EMAIL_HERE@gmail.com', 'Food Donate');
        $mail->addAddress($delivery['email']);

        $did = $delivery['Did'];

        /* INSERT INTO DELIVERY ORDERS */
        mysqli_query($connection, "
            INSERT INTO delivery_orders
            (Fid, Did, donor_name, food_item, quantity, address, phone, donor_email, ngo_email)
            VALUES
            (
                '$id',
                '$did',
                '{$donation['name']}',
                '{$donation['food']}',
                '{$donation['quantity']}',
                '{$donation['address']}',
                '{$donation['phoneno']}',
                '$donor_email',
                '$ngo_email'
            )
        ");

        $mail->Subject = "New Food Pickup Request";

        $mail->Body =
            "New Food Pickup Request\n\n" .
            "Donor Name: " . $donation['name'] . "\n" .
            "Food Item: " . $donation['food'] . "\n" .
            "Quantity: " . $donation['quantity'] . "\n" .
            "Address: " . $donation['address'] . "\n" .
            "Phone Number: " . $donation['phoneno'] . "\n\n" .
            "Please login and accept the pickup request.";

        $mail->send();

    } catch (Exception $e) {
        file_put_contents("mail_error.txt", $mail->ErrorInfo);
    }
}

if ($result) {
    echo "success";
} else {
    echo mysqli_error($connection);
}

?>