<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>DineMate</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Pacifico&display=swap" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>

/* NAVBAR */

.navbar-modern{
position:absolute;
top:0;
left:0;
width:100%;

background:rgba(255,255,255,0.05);
backdrop-filter:blur(8px);

padding:18px 50px;

z-index:999;

transition:0.4s;
}

.navbar-modern.scrolled{
background:#0f172a;
box-shadow:0 5px 20px rgba(0,0,0,0.3);
}

/* LOGO */

.logo{
font-family:'Pacifico',cursive;
font-size:28px;
color:#f4b400;
text-decoration:none;
}

/* NAV LINKS */

.nav-links a{
color:black;
margin-left:25px;
text-decoration:none;
font-weight:500;
transition:0.3s;
}

.nav-links a:hover{
color:#f4b400;
}

/* BUTTON */

.btn-book{
background:#f4b400;
border:none;
padding:10px 20px;
border-radius:30px;
font-weight:600;
transition:0.3s;
}

.btn-book:hover{
background:#e0a800;
transform:scale(1.05);
}

/* LOGOUT */

.btn-logout{
border:1px solid white;
color:white;
padding:8px 18px;
border-radius:25px;
transition:0.3s;
}

.btn-logout:hover{
background:white;
color:black;
}

</style>

</head>

<body>

<nav class="navbar navbar-modern navbar-expand-lg">

<div class="container-fluid">

<a class="logo" href="/dinemate/index.php">
DineMate
</a>

<button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse justify-content-end" id="navMenu">

<div class="nav-links d-flex align-items-center">

<?php if(isset($_SESSION['user_id'])): ?>

<a href="/dinemate/index.php">Home</a>
<a href="/dinemate/about.php">About</a>

<a href="/dinemate/admin/admin-login.php">Admin</a>

<a href="/dinemate/auth/login.php">User Login</a>

<a href="/dinemate/bookings/book-table.php" class="btn btn-book ms-3">
<i class="fa fa-calendar-check"></i> Book Table
</a>

<a href="/dinemate/auth/logout.php" class="btn btn-logout ms-3">
Logout
</a>

<?php else: ?>

<a href="/dinemate/index.php">Home</a>

<a href="/dinemate/auth/login.php">Login</a>

<a href="/dinemate/auth/register.php" class="btn btn-book ms-3">
Register
</a>

<?php endif; ?>

</div>

</div>

</div>

</nav>