<?php
session_start();

if(!isset($_SESSION['ngo_id'])){
    header("Location: login.php");
}

$connection = mysqli_connect("localhost","root","","demo");

$city = $_SESSION['city'];
$ngo_name = $_SESSION['ngo_name'];

/*
CHANGE food_donate BELOW
IF YOUR DONATION TABLE NAME IS DIFFERENT
*/

$query = "SELECT * FROM food_donations
          WHERE LOWER(address)
          LIKE LOWER('%$city%')
          AND status='available'";
$result = mysqli_query($connection,$query);

if(!$result){
    die(mysqli_error($connection));
}

$result = mysqli_query($connection,$query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NGO Dashboard</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial;
        }

        body{
            background:#f4f7fb;
        }

        .navbar{
            width:100%;
            background:#06C167;
            padding:20px;
            color:white;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .navbar h2{
            font-size:28px;
        }

        .logout-btn{
            background:white;
            color:#06C167;
            padding:10px 18px;
            border-radius:8px;
            text-decoration:none;
            font-weight:bold;
        }

        .container{
            width:90%;
            margin:30px auto;
        }

        .welcome-box{
            background:white;
            padding:25px;
            border-radius:15px;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
            margin-bottom:25px;
        }

        .welcome-box h1{
            color:#06C167;
            margin-bottom:10px;
        }

        .card-container{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
            gap:20px;
            margin-bottom:30px;
        }

        .card{
            background:white;
            padding:25px;
            border-radius:15px;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
            text-align:center;
        }

        .card i{
            font-size:40px;
            color:#06C167;
            margin-bottom:15px;
        }

        .card h3{
            margin-bottom:10px;
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

        table th{
            background:#06C167;
            color:white;
            padding:15px;
        }

        table td{
            padding:12px;
            border-bottom:1px solid #ddd;
            text-align:center;
        }

        tr:hover{
            background:#f1f1f1;
        }
        .request-btn{

    background: linear-gradient(135deg,#06C167,#04a357);
    color:white;
    border:none;
    padding:12px 20px;
    border-radius:30px;
    cursor:pointer;
    font-weight:bold;
    font-size:14px;
    transition:0.3s ease;
    box-shadow:0 5px 15px rgba(6,193,103,0.3);
}

.request-btn:hover{

    transform:translateY(-3px) scale(1.05);
    box-shadow:0 8px 20px rgba(6,193,103,0.4);
}

.request-btn:active{

    transform:scale(0.98);
}

    </style>

</head>
<body>

<div class="navbar">

    <h2>
        <i class="fa fa-hand-holding-heart"></i>
        NGO Dashboard
    </h2>

    <div>

        <a href="requested.php"
           class="logout-btn"
           style="margin-right:10px;">
           Requested
        </a>

        <a href="../index.html"
           class="logout-btn">
           Logout
        </a>

    </div>

</div>

<div class="container">

    <div class="welcome-box">

        <h1>Welcome <?php echo $ngo_name; ?></h1>

        <p>
            Area: <b><?php echo $city; ?></b>
        </p>

    </div>

    <div class="card-container">

        <div class="card">
            <i class="fa fa-box"></i>
            <h3>Food Donations</h3>

            <?php
            $count_query = mysqli_query(
$connection,
"SELECT * FROM food_donations
WHERE LOWER(address)
LIKE LOWER('%$city%')
AND status='available'"
);

$count = mysqli_num_rows($count_query);
            ?>

            <h1><?php echo $count; ?></h1>
        </div>

        <div class="card">
            <i class="fa fa-location-dot"></i>
            <h3>Coverage Area</h3>

            <h2><?php echo $city; ?></h2>
        </div>

        <div class="card">
            <i class="fa fa-users"></i>
            <h3>NGO Name</h3>

            <h2><?php echo $ngo_name; ?></h2>
        </div>

    </div>

    <div class="table-box">

        <h2 style="margin-bottom:20px;">
            Available Food Donations
        </h2>

        <table>

    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Food</th>
        <th>Category</th>
        <th>Quantity</th>
        <th>Date</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Image</th>
        <th>Action</th>
    </tr>

    <?php

    

    if(mysqli_num_rows($result) > 0){

while($row = mysqli_fetch_assoc($result)){

    ?>

    <tr>

        <td><?php echo $row['name']; ?></td>

        <td><?php echo $row['email']; ?></td>

        <td><?php echo $row['food']; ?></td>

        <td><?php echo $row['category']; ?></td>

        <td><?php echo $row['quantity']; ?></td>

        <td><?php echo $row['date']; ?></td>

        <td><?php echo $row['address']; ?></td>

        <td><?php echo $row['phoneno']; ?></td>

<td>
    <?php if(!empty($row['image'])) { ?>
        <img
            src="../uploads/<?php echo $row['image']; ?>"
            width="100"
            height="100"
            style="object-fit:cover;border-radius:8px;">
    <?php } else { ?>
        No Image
    <?php } ?>
</td>

<td>

    <button
class="request-btn"
onclick="requestFood(<?php echo $row['Fid']; ?>, this)";>

    <i class="fa fa-paper-plane"></i>

    Request

</button>
        </td>

    </tr>

    <?php
  }

}
else{

echo "<tr>
<td colspan='10'>No Donations Found</td>
</tr>";

}
    ?>

</table>
    </div>

</div>

<script>

function requestFood(id, button){

    fetch("request.php?Fid=" + id)

    .then(response => response.text())

    .then(data => {

        button.innerHTML =
        "<i class='fa fa-check'></i> Requested";

        button.disabled = true;

        button.style.background = "gray";

        let row = button.closest("tr");

        setTimeout(() => {

            row.style.display = "none";

        }, 1000);

    });

}

</script>
</body>
</html>