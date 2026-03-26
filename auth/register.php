<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>DineMate | Register</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
margin:0;
font-family:'Poppins',sans-serif;
background:linear-gradient(-45deg,#f4d58d,#f6f1e9,#e9edc9,#f4b400);
background-size:400% 400%;
animation:gradientBG 12s ease infinite;
}

@keyframes gradientBG{
0%{background-position:0% 50%;}
50%{background-position:100% 50%;}
100%{background-position:0% 50%;}
}

.auth-wrapper{
min-height:100vh;
display:flex;
align-items:center;
justify-content:center;
padding:30px;
}

.auth-box{
width:100%;
max-width:1100px;
background:rgba(255,255,255,0.6);
backdrop-filter:blur(15px);
border-radius:25px;
overflow:hidden;
display:flex;
box-shadow:0 25px 60px rgba(0,0,0,0.15);
}

.auth-left{
flex:1;
padding:60px;
}

.auth-right{
flex:1;
background:url('https://images.unsplash.com/photo-1528605248644-14dd04022da1') center/cover no-repeat;
position:relative;
}

.auth-overlay{
position:absolute;
inset:0;
background:rgba(0,0,0,0.5);
}

.auth-right-content{
position:absolute;
bottom:40px;
left:40px;
color:white;
}

/* Inputs */

.form-control{
border-radius:50px;
padding:14px 22px;
border:none;
background:#f7f7f7;
transition:0.3s;
}

.form-control:focus{
box-shadow:0 0 0 3px #f4b400;
background:white;
}

/* Button */

.btn-main{
background:#f4b400;
border:none;
border-radius:50px;
padding:14px;
font-weight:600;
transition:0.3s;
}

.btn-main:hover{
background:#d39e00;
transform:scale(1.03);
}

/* Password */

.password-wrapper{
position:relative;
}

.eye{
position:absolute;
right:20px;
top:50%;
transform:translateY(-50%);
cursor:pointer;
}

/* Strength */

.strength{
height:6px;
border-radius:5px;
margin-top:6px;
width:0%;
transition:0.3s;
}

/* Toast */

.toast-box{
position:fixed;
top:30px;
right:30px;
background:#333;
color:white;
padding:15px 20px;
border-radius:10px;
display:none;
z-index:999;
}

/* Success animation */

.success-overlay{
position:fixed;
inset:0;
background:rgba(0,0,0,0.6);
display:none;
align-items:center;
justify-content:center;
color:white;
font-size:32px;
font-weight:bold;
z-index:9999;
}

</style>
</head>

<body>

<div class="toast-box" id="toast"></div>

<div class="success-overlay" id="successAnim">
Account Created Successfully 🎉
</div>

<div class="auth-wrapper">
<div class="auth-box">

<div class="auth-left">

<h2>Create Account</h2>
<p class="mb-4">Join DineMate and book effortlessly.</p>

<form method="POST" action="process-register.php" onsubmit="return validateForm()">

<div class="mb-3">
<input type="text" name="name" class="form-control" placeholder="Full Name" required>
</div>

<div class="mb-3">
<input type="email" id="email" name="email" class="form-control" placeholder="Email Address" required onkeyup="checkEmail()">
<small id="emailMsg"></small>
</div>

<div class="mb-3">
<input type="text" name="phone" class="form-control" placeholder="Phone Number">
</div>

<div class="mb-3 password-wrapper">
<input type="password" id="password" name="password" class="form-control" placeholder="Password" required onkeyup="checkStrength()">
<span class="eye" onclick="togglePassword()">👁</span>
<div id="strengthBar" class="strength bg-danger"></div>
</div>

<div class="mb-3">
<input type="password" id="confirm" name="confirm_password" class="form-control" placeholder="Confirm Password" onkeyup="matchPassword()" required>
<small id="matchMsg"></small>
</div>

<button class="btn btn-main w-100 mb-3">Register</button>

</form>

<div class="text-center">
Already have an account?
<a href="login.php">Sign In</a>
</div>

</div>

<div class="auth-right">
<div class="auth-overlay"></div>
<div class="auth-right-content">
<h3>Premium Dining Experience</h3>
<p>Reserve smart. Dine better.</p>
</div>
</div>

</div>
</div>

<script>

/* TOAST */

function showToast(msg){
const toast=document.getElementById("toast");
toast.innerText=msg;
toast.style.display="block";
setTimeout(()=>{toast.style.display="none";},3000);
}

/* PASSWORD TOGGLE */

function togglePassword(){
const pass=document.getElementById("password");
pass.type=pass.type==="password"?"text":"password";
}

/* PASSWORD STRENGTH */

function checkStrength(){

const pass=document.getElementById("password").value;
const bar=document.getElementById("strengthBar");

if(pass.length<6){
bar.style.width="30%";
bar.className="strength bg-danger";
}
else if(pass.length<10){
bar.style.width="60%";
bar.className="strength bg-warning";
}
else{
bar.style.width="100%";
bar.className="strength bg-success";
}

}

/* PASSWORD MATCH */

function matchPassword(){

const p=document.getElementById("password").value;
const c=document.getElementById("confirm").value;
const msg=document.getElementById("matchMsg");

if(c===""){
msg.innerHTML="";
return;
}

if(p===c){
msg.innerHTML="Passwords match ✔";
msg.style.color="green";
}else{
msg.innerHTML="Passwords do not match";
msg.style.color="red";
}

}

/* EMAIL CHECK */

function checkEmail(){

const email=document.getElementById("email").value;
const msg=document.getElementById("emailMsg");

fetch("check-email.php?email="+email)
.then(res=>res.text())
.then(data=>{
if(data==="exists"){
msg.innerHTML="Email already registered";
msg.style.color="red";
}
else{
msg.innerHTML="Email available ✔";
msg.style.color="green";
}
});

}

/* FORM VALIDATION */

function validateForm(){

const p=document.getElementById("password").value;
const c=document.getElementById("confirm").value;

if(p!==c){
showToast("Passwords do not match");
return false;
}

document.getElementById("successAnim").style.display="flex";

setTimeout(()=>{
document.getElementById("successAnim").style.display="none";
},2000);

return true;

}

</script>

</body>
</html>