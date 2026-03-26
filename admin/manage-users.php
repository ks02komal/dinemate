<?php
require_once "../config/db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
header("Location: admin-login.php");
exit;
}

/* PROMOTE USER */

if(isset($_GET['promote'])){
$pdo->prepare("UPDATE users SET role='admin' WHERE user_id=?")
->execute([intval($_GET['promote'])]);

header("Location: manage-users.php");
exit;
}

/* DELETE USER */

if(isset($_GET['delete'])){
$pdo->prepare("DELETE FROM users WHERE user_id=?")
->execute([intval($_GET['delete'])]);

header("Location: manage-users.php");
exit;
}

/* FETCH USERS WITH BOOKING COUNT */

$stmt=$pdo->query("
SELECT u.*,
COUNT(b.booking_id) AS bookings
FROM users u
LEFT JOIN bookings b ON u.user_id=b.user_id
GROUP BY u.user_id
ORDER BY u.name ASC
");

$users=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Users</title>

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

.role-admin{
background:#22c55e;
padding:5px 10px;
border-radius:6px;
color:white;
}

.role-customer{
background:#3b82f6;
padding:5px 10px;
border-radius:6px;
color:white;
}

/* BUTTONS */

.btn-promote{
background:#22c55e;
color:white;
border:none;
padding:6px 12px;
border-radius:6px;
}

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

<h5>Manage Users</h5>

</div>

<!-- MAIN -->

<div class="main">

<div class="card-box">

<div class="d-flex justify-content-between mb-3">

<h4>Registered Users</h4>

<input type="text" id="search" class="form-control w-25" placeholder="Search users">

</div>

<table class="table table-hover" id="userTable">

<thead>

<tr>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Bookings</th>
<th>Actions</th>
</tr>

</thead>

<tbody>

<?php foreach($users as $u): ?>

<tr>

<td><?= $u['name'] ?></td>

<td><?= $u['email'] ?></td>

<td>

<?php if($u['role']=="admin"): ?>

<span class="role-admin">Admin</span>

<?php else: ?>

<span class="role-customer">Customer</span>

<?php endif; ?>

</td>

<td><?= $u['bookings'] ?></td>

<td>

<?php if($u['role']=="customer"): ?>

<a href="?promote=<?= $u['user_id'] ?>" class="btn-promote">
Make Admin
</a>

<?php endif; ?>

<a href="?delete=<?= $u['user_id'] ?>" 
class="btn-delete"
onclick="return confirm('Delete this user?')">
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

/* SEARCH USERS */

document.getElementById("search").addEventListener("keyup",function(){

let value=this.value.toLowerCase();
let rows=document.querySelectorAll("#userTable tbody tr");

rows.forEach(row=>{

row.style.display=row.innerText.toLowerCase().includes(value)?"":"none";

});

});

</script>

</body>
</html>