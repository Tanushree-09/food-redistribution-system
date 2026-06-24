<?php
session_start();

$connection = mysqli_connect("localhost","root","","demo");

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM ngo WHERE email='$email' AND password='$password'";

    $result = mysqli_query($connection,$query);

    if(mysqli_num_rows($result)>0){

        $row = mysqli_fetch_assoc($result);

        $_SESSION['ngo_id'] = $row['id'];
        $_SESSION['ngo_name'] = $row['ngo_name'];
        $_SESSION['city'] = $row['city'];
        $_SESSION['ngo_email'] = $row['email'];

        header("Location: dashboard.php");
    }
    else{
        echo "<script>alert('Invalid Login');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Login</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            background:linear-gradient(135deg,#06C167,#0f3443);
        }

        .login-box{
            width:380px;
            background:white;
            padding:40px;
            border-radius:15px;
            box-shadow:0 10px 30px rgba(0,0,0,0.2);
            text-align:center;
        }

        .login-box h1{
            margin-bottom:10px;
            color:#06C167;
        }

        .login-box p{
            margin-bottom:25px;
            color:gray;
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

        .input-box input{
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

        .register-link{
            margin-top:20px;
        }

        .register-link a{
            text-decoration:none;
            color:#06C167;
            font-weight:bold;
        }

    </style>

</head>
<body>

<div class="login-box">

    <h1>NGO Login</h1>
    <p>Food Waste Management System</p>

    <form method="POST">

        <div class="input-box">
            <i class="fa fa-envelope"></i>
            <input type="email" name="email" placeholder="Enter NGO Email" required>
        </div>

        <div class="input-box">
            <i class="fa fa-lock"></i>
            <input type="password" name="password" placeholder="Enter Password" required>
        </div>

        <button type="submit" name="login" class="btn">Login</button>

    </form>

    <div class="register-link">
        <p>New NGO? <a href="register.php">Register Here</a></p>
    </div>

</div>

</body>
</html>