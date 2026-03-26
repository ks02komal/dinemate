<!-- FOOTER -->

<footer class="footer">

<div class="container">

<div class="row">

<!-- BRAND -->

<div class="col-md-4 footer-brand">

<h4>DineMate</h4>

<p>
Modern reservation platform for Old Canberra Inn.
Book tables instantly, avoid waiting times, and enjoy
a seamless dining experience.
</p>

<div class="social-icons">

<a href="#"><i class="fab fa-facebook"></i></a>
<a href="#"><i class="fab fa-instagram"></i></a>
<a href="#"><i class="fab fa-twitter"></i></a>
<a href="#"><i class="fab fa-linkedin"></i></a>

</div>

<button onclick="scrollTopPage()" class="back-top">
↑ Back to Top
</button>

</div>


<!-- SITE MAP -->

<div class="col-md-4">

<h5>Site Map</h5>

<ul>

<li><a href="index.php">Homepage</a></li>
<li><a href="bookings/book-table.php">Book Table</a></li>
<li><a href="auth/login.php">Login</a></li>
<li><a href="auth/register.php">Register</a></li>
<li><a href="contact.php">Contact Us</a></li>

</ul>

</div>


<!-- LEGAL -->

<div class="col-md-4">

<h5>Legal</h5>

<ul>

<li><a href="#">Privacy Policy</a></li>
<li><a href="#">Terms of Service</a></li>
<li><a href="#">Restaurant Policies</a></li>

</ul>

</div>

</div>

</div>

<div class="footer-bottom">

© 2026 Old Canberra Inn — Powered by DineMate

</div>

</footer>


<style>

/* FOOTER */

.footer{
background:#0f172a;
color:#d1d5db;
padding:60px 0 0 0;
margin-top:80px;
}

.footer h4{
color:#f4b400;
font-weight:700;
margin-bottom:15px;
}

.footer h5{
color:white;
margin-bottom:15px;
}

.footer ul{
list-style:none;
padding:0;
}

.footer ul li{
margin-bottom:8px;
}

.footer ul li a{
text-decoration:none;
color:#cbd5e1;
transition:0.3s;
}

.footer ul li a:hover{
color:#f4b400;
}

/* SOCIAL */

.social-icons a{
color:white;
margin-right:15px;
font-size:20px;
transition:0.3s;
}

.social-icons a:hover{
color:#f4b400;
}

/* BACK TOP */

.back-top{
margin-top:20px;
padding:10px 18px;
border:1px solid #f4b400;
background:none;
color:white;
border-radius:6px;
transition:0.3s;
}

.back-top:hover{
background:#f4b400;
color:black;
}

/* FOOTER BOTTOM */

.footer-bottom{
text-align:center;
background:#f4b400;
color:black;
padding:10px;
margin-top:40px;
font-weight:500;
}

</style>

<script>

function scrollTopPage(){
window.scrollTo({
top:0,
behavior:'smooth'
});
}

</script>