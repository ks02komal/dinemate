<?php
session_start();
include "../includes/header.php";
?>

<div class="row justify-content-center">
<div class="col-md-5">
<div class="card shadow p-4">
<h3 class="text-center mb-3">Login</h3>

<?php if(isset($_SESSION['error'])): ?>
<div class="alert alert-danger">
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>

<?php if(isset($_SESSION['success'])): ?>
<div class="alert alert-success">
    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
</div>
<?php endif; ?>

<form method="POST" action="process-login.php">

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-warning w-100">Login</button>

</form>

<div class="text-center mt-3">
<a href="register.php">Create Account</a>
</div>

</div>
</div>
</div>

<?php include "../includes/footer.php"; ?>