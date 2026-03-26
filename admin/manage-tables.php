<?php
require_once "../config/db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
header("Location: admin-login.php");
exit;
}

/* ADD TABLE */

if(isset($_POST['add_table'])){

$number=$_POST['table_number'];
$capacity=$_POST['capacity'];

$stmt=$pdo->prepare("INSERT INTO restaurant_tables(table_number,capacity) VALUES(?,?)");
$stmt->execute([$number,$capacity]);

header("Location: manage-tables.php");
exit;

}

/* DELETE TABLE */

if(isset($_GET['delete'])){
$id=$_GET['delete'];

$stmt=$pdo->prepare("DELETE FROM restaurant_tables WHERE table_id=?");
$stmt->execute([$id]);

header("Location: manage-tables.php");
exit;
}

/* FETCH TABLES */

$stmt=$pdo->query("SELECT * FROM restaurant_tables ORDER BY table_number ASC");
$tables=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Tables</title>

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

.btn-add{
background:#f4b400;
border:none;
padding:10px 16px;
border-radius:8px;
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

<h5>Manage Tables</h5>

</div>

<!-- MAIN -->

<div class="main">

<div class="row">

<!-- ADD TABLE -->

<div class="col-md-4">

<div class="card-box">

<h5>Add New Table</h5>

<form method="POST">

<div class="mb-3">
<label>Table Number</label>
<input type="number" name="table_number" class="form-control" required>
</div>

<div class="mb-3">
<label>Capacity</label>
<input type="number" name="capacity" class="form-control" required>
</div>

<button name="add_table" class="btn-add w-100">Add Table</button>

</form>

</div>

</div>

<!-- TABLE LIST -->

<div class="col-md-8">

<div class="card-box">

<div class="d-flex justify-content-between mb-3">

<h5>Restaurant Tables</h5>

<input type="text" id="search" class="form-control w-25" placeholder="Search table">

</div>

<table class="table table-hover" id="tableList">

<thead>

<tr>
<th>Table Number</th>
<th>Capacity</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php foreach($tables as $t): ?>

<tr>

<td>Table <?= $t['table_number'] ?></td>

<td><?= $t['capacity'] ?> Guests</td>

<td>

<a href="?delete=<?= $t['table_id'] ?>" 
class="btn-delete"
onclick="return confirm('Delete this table?')">
Delete
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>

</div>

<script>

/* SEARCH TABLE */

document.getElementById("search").addEventListener("keyup",function(){

let value=this.value.toLowerCase();
let rows=document.querySelectorAll("#tableList tbody tr");

rows.forEach(row=>{

row.style.display=row.innerText.toLowerCase().includes(value)?"":"none";

});

});

</script>

</body>
</html>