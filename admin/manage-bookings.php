<?php
require_once "../config/db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
header("Location: admin-login.php");
exit;
}

/* DELETE BOOKING */

if(isset($_GET['delete'])){
$id=$_GET['delete'];
$stmt=$pdo->prepare("DELETE FROM bookings WHERE booking_id=?");
$stmt->execute([$id]);
header("Location: manage-bookings.php");
exit;
}

/* FETCH BOOKINGS */

$stmt=$pdo->query("
SELECT b.*,u.name,t.table_number
FROM bookings b
JOIN users u ON b.user_id=u.user_id
JOIN restaurant_tables t ON b.table_id=t.table_id
ORDER BY b.booking_date DESC
");

$bookings=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Bookings</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
margin:0;
font-family:'Poppins',sans-serif;
background:#f4f6f9;
}

/* SIDEBAR */

.sidebar{
width:240px;
height:100vh;
position:fixed;
background:#111827;
color:white;
padding:25px;
}

.sidebar h4{
color:#f4b400;
margin-bottom:30px;
}

.sidebar a{
display:block;
padding:12px;
color:#ddd;
text-decoration:none;
border-radius:8px;
margin-bottom:10px;
}

.sidebar a:hover{
background:#1f2937;
}

/* HEADER */

.topbar{
margin-left:240px;
height:70px;
background:white;
display:flex;
align-items:center;
justify-content:space-between;
padding:0 30px;
box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

/* MAIN */

.main{
margin-left:240px;
padding:30px;
}

/* CARD */

.card-box{
background:white;
padding:25px;
border-radius:14px;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

/* TABLE */

.table th{
background:#f4b400;
color:white;
}

/* BADGES */

.badge-confirm{
background:#22c55e;
}

.badge-cancel{
background:#ef4444;
}

/* BUTTONS */

.btn-delete{
background:#ef4444;
color:white;
border:none;
padding:6px 12px;
border-radius:6px;
}

.btn-delete:hover{
background:#dc2626;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h4>DineMate</h4>

<a href="dashboard.php">Dashboard</a>
<a href="manage-bookings.php">Bookings</a>
<a href="manage-tables.php">Tables</a>
<a href="manage-users.php">Users</a>
<a href="../auth/logout.php">Logout</a>

</div>

<!-- TOPBAR -->

<div class="topbar">

<h5>Manage Reservations</h5>

</div>

<!-- MAIN -->

<div class="main">

<div class="card-box">

<div class="d-flex justify-content-between mb-3">

<h4>All Bookings</h4>

<input type="text" id="search" class="form-control w-25" placeholder="Search booking">

</div>

<table class="table table-hover" id="bookingTable">

<thead>
<tr>
<th>Customer</th>
<th>Table</th>
<th>Date</th>
<th>Time</th>
<th>Guests</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php foreach($bookings as $b): ?>

<tr>

<td><?= $b['name'] ?></td>

<td>Table <?= $b['table_number'] ?></td>

<td><?= $b['booking_date'] ?></td>

<td><?= $b['booking_time'] ?></td>

<td><?= $b['number_of_guests'] ?></td>

<td>

<span class="badge badge-confirm">
Confirmed
</span>

</td>

<td>

<a href="?delete=<?= $b['booking_id'] ?>" 
class="btn-delete"
onclick="return confirm('Delete this booking?')">
Delete
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

<script>

/* SEARCH FILTER */

document.getElementById("search").addEventListener("keyup",function(){

let value=this.value.toLowerCase();
let rows=document.querySelectorAll("#bookingTable tbody tr");

rows.forEach(row=>{

row.style.display=row.innerText.toLowerCase().includes(value)?"":"none";

});

});

</script>

</body>
</html>