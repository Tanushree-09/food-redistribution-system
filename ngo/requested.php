<?php
session_start();

if(!isset($_SESSION['ngo_id'])){
    header("Location: login.php");
    exit();
}

$connection = mysqli_connect(
    "localhost",
    "root",
    "",
    "demo"
);

$ngo_email = $_SESSION['email'];


$query = "
SELECT *
FROM delivery_orders d1
WHERE ngo_email='YOUR_EMAIL_HERE@gmail.com'
AND id = (
    SELECT MAX(id)
    FROM delivery_orders d2
    WHERE d2.Fid = d1.Fid
)
ORDER BY id DESC
";

$result = mysqli_query($connection,$query);

if(!$result){
    die(mysqli_error($connection));
}

$ngo_name = $_SESSION['ngo_name'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Requested Donations</title>

<style>

body{
    font-family:Arial;
    background:#f4f7fb;
    margin:0;
}

.navbar{
    background:#06C167;
    color:white;
    padding:20px;
    display:flex;
    justify-content:space-between;
}

.btn{
    background:white;
    color:#06C167;
    padding:10px 15px;
    text-decoration:none;
    border-radius:8px;
    font-weight:bold;
}

.container{
    width:95%;
    margin:30px auto;
}

.table-box{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#06C167;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

img{
    border-radius:8px;
    object-fit:cover;
}

</style>

</head>
<body>

<div class="navbar">

    <h2>Requested Donations</h2>

    <div>
        <a href="dashboard.php" class="btn">
            Back
        </a>
    </div>

</div>

<div class="container">

<div class="table-box">

<h2>My Requested Donations</h2>

<br>

<table>

<tr>
    <th>Donor</th>
    <th>Food</th>
    <th>Quantity</th>
    <th>Address</th>
    <th>Phone</th>
    <th>Image</th>
    <th>Status</th>
</tr>

<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

    $fid = $row['Fid'];

    $donation = mysqli_query(
        $connection,
        "SELECT image FROM food_donations WHERE Fid='$fid'"
    );

    $img = mysqli_fetch_assoc($donation);

?>

<tr>

<td><?php echo $row['donor_name']; ?></td>

<td><?php echo $row['food_item']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td><?php echo $row['address']; ?></td>

<td><?php echo $row['phone']; ?></td>

<td>

<?php if(!empty($img['image'])){ ?>

<img
src="../uploads/<?php echo $img['image']; ?>"
width="100"
height="100">

<?php } else { ?>

No Image

<?php } ?>

</td>

<td>

<?php
echo ucfirst(
$row['status'] ?? 'requested'
);
?>

</td>

</tr>

<?php
}

}else{

echo "
<tr>
<td colspan='7'>
No requested donations found
</td>
</tr>";
}
?>

</table>

</div>

</div>

</body>
</html>