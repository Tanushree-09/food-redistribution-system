<?php
session_start();

include '../connection.php';

$msg = 0;

if(isset($_POST['sign']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sanitized_emailid =
    mysqli_real_escape_string(
    $connection,
    $email
    );

    $sanitized_password =
    mysqli_real_escape_string(
    $connection,
    $password
    );

    $sql =
    "SELECT * FROM delivery_persons
    WHERE email='$sanitized_emailid'";

    $result = mysqli_query(
    $connection,
    $sql
    );

    $num = mysqli_num_rows($result);

    if($num == 1)
    {
        while($row = mysqli_fetch_assoc($result))
        {
            if(password_verify(
                $sanitized_password,
                $row['password']
            ))
            {
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $row['name'];
                $_SESSION['Did'] = $row['Did'];
                $_SESSION['city'] = $row['city'];

                header("location:delivery.php");
                exit();
            }
            else
            {
                $msg = 1;
            }
        }
    }
    else
    {
        $msg = 2;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Delivery Login</title>

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

.login-box{

    width:400px;

    background:white;

    padding:40px;

    border-radius:15px;

    box-shadow:
    0 10px 30px rgba(0,0,0,0.2);
}

.login-box h1{

    text-align:center;

    color:#06C167;

    margin-bottom:25px;
}

.input-box{

    position:relative;

    margin-bottom:20px;
}

.input-box i:first-child{

    position:absolute;

    left:15px;

    top:15px;

    color:#06C167;
}

.input-box input{

    width:100%;

    padding:14px 45px;

    border:1px solid #ccc;

    border-radius:8px;

    outline:none;

    font-size:15px;
}

.password-toggle{

    position:absolute;

    right:15px;

    top:15px;

    color:#06C167;

    cursor:pointer;
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

.signup-link{

    margin-top:20px;

    text-align:center;
}

.signup-link a{

    text-decoration:none;

    color:#06C167;

    font-weight:bold;
}

.error{

    text-align:center;

    color:red;

    margin-bottom:15px;

    font-weight:bold;
}

</style>

</head>

<body>

<div class="login-box">

<h1>Delivery Login</h1>

<form method="POST">

<div class="input-box">

<i class="fa fa-envelope"></i>

<input
type="email"
name="email"
placeholder="Email Address"
required>

</div>

<div class="input-box">

<i class="fa fa-lock"></i>

<input
type="password"
name="password"
id="password"
placeholder="Password"
required>

<i
class="fa fa-eye password-toggle"
id="togglePassword">
</i>

</div>

<?php
if($msg == 1)
{
    echo "<p class='error'>
    Incorrect Password
    </p>";
}

if($msg == 2)
{
    echo "<p class='error'>
    Account does not exist
    </p>";
}
?>

<button
type="submit"
name="sign"
class="btn">

Login

</button>

</form>

<div class="signup-link">

Not a member?

<a href="deliverysignup.php">
Sign Up
</a>

</div>

</div>

<script>

document
.getElementById("togglePassword")
.addEventListener(
"click",
function(){

    let password =
    document.getElementById(
    "password"
    );

    if(password.type === "password")
    {
        password.type = "text";

        this.classList.remove(
        "fa-eye"
        );

        this.classList.add(
        "fa-eye-slash"
        );
    }
    else
    {
        password.type = "password";

        this.classList.remove(
        "fa-eye-slash"
        );

        this.classList.add(
        "fa-eye"
        );
    }
}
);

</script>

</body>
</html>