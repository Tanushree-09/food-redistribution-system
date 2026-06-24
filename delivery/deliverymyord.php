<?php
ob_start(); 

// $connection = mysqli_connect("localhost:3307", "root", "");
// $db = mysqli_select_db($connection, 'demo');
include '../connection.php';
 include("connect.php"); 
if($_SESSION['name']==''){
	header("location:deliverylogin.php");
}
$name=$_SESSION['name'];
$id = $_SESSION['Did'] ?? '';


if(isset($_POST['verify_otps'])){

    $order_id = $_POST['complete_order_id'];

    $donor_otp = $_POST['entered_donor_otp'];

    $ngo_otp = $_POST['entered_ngo_otp'];

    $check = mysqli_query(
    $connection,
    "SELECT donor_otp,ngo_otp
     FROM delivery_orders
     WHERE id='$order_id'"
    );

    $row = mysqli_fetch_assoc($check);

    if(
        $row['donor_otp']==$donor_otp
        &&
        $row['ngo_otp']==$ngo_otp
    ){

        mysqli_query(
        $connection,
        "UPDATE delivery_orders
         SET status='completed'
         WHERE id='$order_id'"
        );

        mysqli_query(
        $connection,
        "UPDATE food_donations
         SET status='completed'
         WHERE Fid=
         (
            SELECT Fid
            FROM delivery_orders
            WHERE id='$order_id'
         )"
        );

        echo "<script>
        alert('Delivery Completed');
        location.href='deliverymyord.php';
        </script>";

        exit();

    }else{

        echo "<script>
        alert('OTP Mismatch');
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
</head>
<body>
<header>
        <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="delivery.php" >Home</a></li>
                <li><a href="openmap.php" >map</a></li>
                <li><a href="deliverymyord.php"  class="active">myorders</a></li>
    
            </ul>
        </nav>
    </header>
    <br>
    <script>
        hamburger=document.querySelector(".hamburger");
        hamburger.onclick =function(){
            navBar=document.querySelector(".nav-bar");
            navBar.classList.toggle("active");
        }
    </script>
    <style>
        .itm{
            background-color: white;
            display: grid;
        }
        .itm img{
            width: 400px;
            height: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        p{
            text-align: center; font-size: 28PX;color: black; 
        }
        a{
            /* text-decoration: underline; */
        }
        @media (max-width: 767px) {
            .itm{
                /* float: left; */
                
            }
            .itm img{
                width: 350px;
                height: 350px;
            }
        }
    </style>

        <div class="itm" >

            <img src="../img/delivery.gif" alt="" width="400" height="400"> 
          
        </div>

        <div class="get">
            <?php


// Define the SQL query to fetch unassigned orders
$sql = "
SELECT *
FROM delivery_orders
WHERE Did='$id'
AND status='accepted'
";

// Execute the query
$result=mysqli_query($connection, $sql);



// Check for errors
if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

// Fetch the data as an associative array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// If the delivery person has taken an order, update the assigned_to field in the database
if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {
    $order_id = $_POST['order_id'];
    $delivery_person_id = $_POST['delivery_person_id'];

    $sql = "UPDATE food_donations SET delivery_by = $delivery_person_id WHERE Fid = $order_id";
    // $result = mysqli_query($conn, $sql);
    $result=mysqli_query($connection, $sql);


    if (!$result) {
        die("Error assigning order: " . mysqli_error($conn));
    }

    // Reload the page to prevent duplicate assignments
    header('Location: ' . $_SERVER['REQUEST_URI']);
    // exit;
    ob_end_flush();
}
// mysqli_close($conn);


?>
<div class="log">
<!-- <button type="submit" name="food" onclick="">My orders</button> -->
<a href="delivery.php">Take orders</a>
<p>Order assigned to you</p>
<br>
</div>
  

<!-- Display the orders in an HTML table -->
<div class="table-container">
         <!-- <p id="heading">donated</p> -->
         <div class="table-wrapper">
        <table class="table">
        <thead>
        <tr>
    <th>Donor Name</th>
    <th>Food Item</th>
    <th>Quantity</th>
    <th>Address</th>
    <th>Phone</th>
    
    <th>Status</th>
<th>Action</th>
</tr>
        </thead>
       <tbody>

        <?php foreach ($data as $row) { ?>

<tr>

<td><?php echo $row['donor_name']; ?></td>

<td><?php echo $row['food_item']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td><?php echo $row['address']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td><?php echo $row['status']; ?></td>

<td>

<?php if($row['status']=='accepted'){ ?>

<form method="post">

    <input type="hidden"
           name="order_id"
           value="<?php echo $row['id']; ?>">

    <button type="button"
            onclick="showOTP(<?php echo $row['id']; ?>)">
        Mark Complete
    </button>

</form>

<?php }else{ ?>

Completed

<?php } ?>

</td>

</tr>

<?php } ?>
    </tbody>
</table>

            </div>

<div id="otpBox"
style="
display:none;
position:fixed;
top:30%;
left:40%;
background:white;
padding:20px;
border:1px solid #ccc;
">

<form method="post">

<input type="hidden"
       name="complete_order_id"
       id="complete_order_id">

<label>Donor OTP</label><br>

<input type="text"
       name="entered_donor_otp"
       required>

<br><br>

<label>NGO OTP</label><br>

<input type="text"
       name="entered_ngo_otp"
       required>

<br><br>

<button type="submit"
        name="verify_otps">
    Verify
</button>

</form>

</div>
     
        

   <br>
   <br>

   <script>

function showOTP(id){

    document.getElementById(
    "otpBox").style.display="block";

    document.getElementById(
    "complete_order_id").value=id;
}

</script>
</body>
</html>