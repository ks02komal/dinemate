<?php
require_once "../config/db.php";
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin'){
header("Location: admin-login.php");
exit;
}

/* DASHBOARD DATA */

$totalBookings = $pdo->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();
$totalTables = $pdo->query("SELECT COUNT(*) FROM restaurant_tables")->fetchColumn();
$todayBookings = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_date = CURDATE()")->fetchColumn();

/* LATEST BOOKINGS */

$stmt = $pdo->query("
SELECT b.*, u.name, t.table_number
FROM bookings b
JOIN users u ON b.user_id = u.user_id
JOIN restaurant_tables t ON b.table_id = t.table_id
ORDER BY b.created_at DESC
LIMIT 5
");

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* TABLE STATUS */

$tableStatus = $pdo->query("
SELECT 
t.table_id,
t.table_number,
t.capacity,
CASE
WHEN COUNT(b.booking_id) > 0 THEN 'booked'
ELSE 'available'
END AS status
FROM restaurant_tables t
LEFT JOIN bookings b
ON t.table_id = b.table_id
AND b.booking_date = CURDATE()
AND b.status = 'confirmed'
GROUP BY t.table_id
ORDER BY t.table_number ASC
")->fetchAll(PDO::FETCH_ASSOC);


/* BOOKING ANALYTICS DATA */

$chartQuery = $pdo->query("
SELECT DATE(booking_date) AS day, COUNT(*) AS total
FROM bookings
GROUP BY DATE(booking_date)
ORDER BY DATE(booking_date) ASC
LIMIT 7
");

$chartData = $chartQuery->fetchAll(PDO::FETCH_ASSOC);

$days = [];
$totals = [];

foreach($chartData as $row){
    $days[] = $row['day'];
    $totals[] = $row['total'];
}

?>

<!DOCTYPE html>
<html>
<head>

<title>DineMate Admin Dashboard</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

body{
margin:0;
font-family:'Poppins',sans-serif;
background:#f4f6f9;
transition:0.3s;
}

.dark-mode{
background:#111827;
color:white;
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

.sidebar a i{
margin-right:8px;
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

.dark-mode .topbar{
background:#1f2937;
color:white;
}

/* MAIN */

.main{
margin-left:240px;
padding:30px;
}

/* STATS */

.stat-card{
background:white;
padding:25px;
color: blue;
border-radius:14px;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
transition:0.3s;
text-align:center;
}

.stat-card:hover{
transform:translateY(-6px);
}

.stat-card h2{
color:#f4b400;
}

/* TABLE */

.table{
background:white;
border-radius:10px;
overflow:hidden;
}

/* TABLE TRACKER */

.table-grid{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(140px,1fr));
gap:18px;
}

.table-box{
padding:22px;
border-radius:14px;
text-align:center;
color:white;
font-weight:600;
transition:0.3s;
box-shadow:0 10px 20px rgba(0,0,0,0.08);
}

.table-box i{
font-size:20px;
margin-bottom:6px;
}

.table-title{
font-size:16px;
}

.table-capacity{
font-size:12px;
opacity:0.8;
}

.table-status{
font-size:12px;
margin-top:5px;
font-weight:500;
}

.available{
background:linear-gradient(135deg,#22c55e,#16a34a);
}

.booked{
background:linear-gradient(135deg,#ef4444,#dc2626);
}

.table-box:hover{
transform:translateY(-5px) scale(1.04);
}

.available{
background:#22c55e;
}

.booked{
background:#ef4444;
}

/* NOTIFICATION */

.notification{
position:relative;
}

.notification-badge{
position:absolute;
top:-5px;
right:-8px;
background:red;
color:white;
font-size:12px;
padding:3px 6px;
border-radius:50%;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h4>DineMate</h4>

<a href="dashboard.php"><i class="fa fa-chart-line"></i>Dashboard</a>
<a href="manage-bookings.php"><i class="fa fa-calendar-check"></i>Bookings</a>
<a href="manage-tables.php"><i class="fa fa-chair"></i>Tables</a>
<a href="manage-users.php"><i class="fa fa-users"></i>Users</a>
<a href="../auth/logout.php"><i class="fa fa-sign-out-alt"></i>Logout</a>

</div>

<!-- TOPBAR -->

<div class="topbar">

<h5>Admin Dashboard</h5>

<div>

<button onclick="toggleDark()" class="btn btn-sm btn-dark me-3">
🌙 Dark Mode
</button>

<span class="notification">
<i class="fa fa-bell"></i>
<span class="notification-badge"><?= $todayBookings ?></span>
</span>

</div>

</div>

<!-- MAIN -->

<div class="main">

<h4 class="mb-4">Overview</h4>

<div class="row g-4 mb-4">

<div class="col-md-3">
<div class="stat-card">
<i class="fa fa-calendar-check fa-2x mb-2"></i>
<h5>Total Bookings</h5>
<h2><?= $totalBookings ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="stat-card">
<i class="fa fa-users fa-2x mb-2"></i>
<h5>Customers</h5>
<h2><?= $totalUsers ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="stat-card">
<i class="fa fa-chair fa-2x mb-2"></i>
<h5>Tables</h5>
<h2><?= $totalTables ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="stat-card">
<i class="fa fa-clock fa-2x mb-2"></i>
<h5>Today's Bookings</h5>
<h2><?= $todayBookings ?></h2>
</div>
</div>

</div>

<!-- CHART -->

<div class="card p-4 mb-4">

<h5>Reservation Analytics</h5>

<canvas id="bookingChart"></canvas>

</div>


<!-- LATEST BOOKINGS -->

<div class="card p-4 mb-4">

<h5>Latest Reservations</h5>

<table class="table">

<thead>
<tr>
<th>Customer</th>
<th>Table</th>
<th>Date</th>
<th>Guests</th>
</tr>
</thead>

<tbody>

<?php foreach($bookings as $b): ?>

<tr>
<td><?= $b['name'] ?></td>
<td><?= $b['table_number'] ?></td>
<td><?= $b['booking_date'] ?></td>
<td><?= $b['number_of_guests'] ?></td>
</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<!-- TABLE TRACKER -->

<div class="card p-4">

<h5 class="mb-3">Table Availability</h5>

<div class="table-grid">

<?php foreach($tableStatus as $t): ?>

<div class="table-box <?= $t['status'] ?>">

<i class="fa fa-chair"></i>

<div class="table-title">
Table <?= $t['table_number'] ?>
</div>

<div class="table-capacity">
<?= $t['capacity'] ?> seats
</div>

<div class="table-status">
<?= $t['status'] == 'booked' ? 'Booked' : 'Available' ?>
</div>

</div>

<?php endforeach; ?>

</div>

</div>

</div>

<script>
    

/* DARK MODE */

function toggleDark(){
document.body.classList.toggle("dark-mode");
}

/* CHART */

const ctx=document.getElementById('bookingChart');

new Chart(ctx,{
type:'line',
data:{
labels: <?= json_encode($days) ?>,
datasets:[{
label:'Bookings',
data: <?= json_encode($totals) ?>,
borderColor:'#f4b400',
backgroundColor:'rgba(244,180,0,0.2)',
fill:true,
tension:0.4
}]
},
options:{
responsive:true,
plugins:{
legend:{
display:true
}
},
scales:{
y:{
beginAtZero:true
}
}
}
});

</script>

</body>
</html>