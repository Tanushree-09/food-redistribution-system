<?php

include '../connection.php';

if(isset($_POST['sign']))
{

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $location = $_POST['district'];

    $pass = password_hash($password,PASSWORD_DEFAULT);

    $sql = "SELECT * FROM delivery_persons
            WHERE email='$email'";

    $result = mysqli_query($connection,$sql);

    $num = mysqli_num_rows($result);

    if($num == 1){

        echo "<h1><center>
        Account already exists
        </center></h1>";

    }
    else{

        $query = "INSERT INTO
        delivery_persons(name,email,password,city)

        VALUES(
        '$username',
        '$email',
        '$pass',
        '$location'
        )";

        $query_run = mysqli_query(
        $connection,
        $query
        );

        if($query_run){

            header("location:deliverylogin.php");

        }
        else{

            echo '<script>
            alert("Data not saved")
            </script>';

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Delivery Registration</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial;
}

body{

    height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    background:
    linear-gradient(
    135deg,
    #06C167,
    #0f3443
    );

}

.register-box{

    width:420px;

    background:white;

    padding:40px;

    border-radius:15px;

    box-shadow:
    0 10px 30px rgba(0,0,0,0.2);

}

.register-box h1{

    text-align:center;

    color:#06C167;

    margin-bottom:25px;

}

.input-box{

    position:relative;

    margin-bottom:20px;

}

.input-box i{

    position:absolute;

    left:15px;

    top:15px;

    color:#06C167;

}

.input-box input,
.input-box select{

    width:100%;

    padding:14px 14px 14px 45px;

    border:1px solid #ccc;

    border-radius:8px;

    outline:none;

    font-size:15px;

}

.btn{

    width:100%;

    padding:14px;

    border:none;

    background:#06C167;

    color:white;

    font-size:16px;

    border-radius:8px;

    cursor:pointer;

    transition:0.3s;

}

.btn:hover{

    background:#049c52;

}

.login-link{

    margin-top:20px;

    text-align:center;

}

.login-link a{

    text-decoration:none;

    color:#06C167;

    font-weight:bold;

}

.otp-btn{

    position:absolute;

    right:5px;

    top:5px;

    padding:10px 15px;

    border:none;

    background:#06C167;

    color:white;

    border-radius:6px;

    cursor:pointer;

    font-size:13px;

}

.otp-btn:hover{

    background:#049c52;

}

.verify-btn{

    width:100%;

    padding:12px;

    border:none;

    background:#0f3443;

    color:white;

    border-radius:8px;

    cursor:pointer;

    font-size:15px;

}

.verify-btn:hover{

    background:#081f29;

}

.verify-btn:disabled,
.otp-btn:disabled{

    background:gray;

    cursor:not-allowed;

}

#otpmessage{

    text-align:center;

    margin-bottom:15px;

    font-weight:bold;

}

</style>

</head>

<body>

<div class="register-box">

<h1>Delivery Registration</h1>

<form method="POST">

<div class="input-box">

<i class="fa fa-user"></i>

<input
type="text"
name="username"
placeholder="Username"
required>

</div>

<div class="input-box">

<i class="fa fa-envelope"></i>

<input
type="email"
id="email"
name="email"
placeholder="Email Address"
required>

</div>

<div class="input-box">

<i class="fa fa-envelope-circle-check"></i>

<input
type="text"
id="otp"
placeholder="Enter OTP"
disabled
style="padding-right:120px;">

<button
type="button"
id="sendotp"
class="otp-btn">

Send OTP

</button>

</div>

<div class="input-box">

<button
type="button"
id="verifyotp"
class="verify-btn"
disabled>

Verify OTP

</button>

</div>

<p id="otpmessage"></p>

<div class="input-box">

<i class="fa fa-lock"></i>

<input
type="password"
name="password"
id="password"
placeholder="Password"
required
disabled>

</div>

<div class="input-box">

<i class="fa fa-location-dot"></i>

<select
id="district"
name="district"
required>

<option value="">
Select Area
</option>

<option value="Bangalore">
Bangalore
</option>

<option value="Mysore">
Mysore
</option>

<option value="Tumkur">
Tumkur
</option>

</select>

</div>

<button
type="submit"
name="sign"
class="btn">

Register

</button>

</form>

<div class="login-link">

Already a member?

<a href="deliverylogin.php">
Sign In
</a>

</div>

</div>

<script>

let generatedOTP = "";

document.getElementById("sendotp").onclick =
function(){

    let email =
    document.getElementById("email").value;

    if(email == ""){

        alert("Enter Email First");

        return;

    }

    let formData = new FormData();

    formData.append("email", email);

    fetch("../send_otp.php", {

        method: "POST",

        body: formData

    })

    .then(response => response.text())

    .then(data => {

        generatedOTP = data;

        document.getElementById("otp")
        .disabled = false;

        document.getElementById("verifyotp")
        .disabled = false;

        document.getElementById("otpmessage")
        .innerHTML =
        "OTP Sent Successfully";

        document.getElementById("otpmessage")
        .style.color = "green";

    });

}

document.getElementById("verifyotp").onclick =
function(){

    let enteredOTP =
    document.getElementById("otp").value;

    if(enteredOTP == generatedOTP){

        document.getElementById("password")
        .disabled = false;

        document.getElementById("otpmessage")
        .innerHTML =
        "Email Verified Successfully";

        document.getElementById("otpmessage")
        .style.color = "green";

    }
    else{

        document.getElementById("otpmessage")
        .innerHTML =
        "Invalid OTP";

        document.getElementById("otpmessage")
        .style.color = "red";

    }

}

</script>

</body>
</html>