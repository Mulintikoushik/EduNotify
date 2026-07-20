<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>EduNotify | Smart Student Result Management</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Segoe UI',sans-serif;
}

body{

background:linear-gradient(135deg,#0d6efd,#6610f2);

min-height:100vh;

overflow-x:hidden;

}

.navbar{

background:white;

box-shadow:0 5px 20px rgba(0,0,0,.15);

}

.logo{

font-size:28px;

font-weight:bold;

color:#0d6efd;

}

.hero{

padding:80px 20px;

color:white;

text-align:center;

}

.hero i{

font-size:80px;

margin-bottom:20px;

}

.hero h1{

font-size:52px;

font-weight:700;

}

.hero p{

font-size:22px;

margin-top:15px;

opacity:.95;

}

.cards{

margin-top:-30px;

}

.feature{

border:none;

border-radius:20px;

transition:.35s;

box-shadow:0 10px 25px rgba(0,0,0,.12);

height:100%;

}

.feature:hover{

transform:translateY(-8px);

}

.feature i{

font-size:50px;

margin-bottom:20px;

}

.login-card{

margin-top:40px;

border-radius:25px;

border:none;

box-shadow:0 10px 25px rgba(0,0,0,.2);

}

.footer{

margin-top:70px;

padding:25px;

text-align:center;

color:white;

}

.btn{

padding:13px;

font-weight:600;

}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg">

<div class="container">

<a class="navbar-brand logo" href="#">

<i class="fas fa-graduation-cap"></i>

EduNotify

</a>

<div>

<a href="admin/login.php" class="btn btn-primary me-2">

Admin

</a>

<a href="student/login.php" class="btn btn-success">

Student

</a>

</div>

</div>

</nav>

<section class="hero">

<div class="container">

<i class="fas fa-user-graduate"></i>

<h1>EduNotify</h1>

<p>

Smart Student Result Management & Notification System

</p>

<p class="mt-2">

Secure • Fast • Automated

</p>

</div>

</section>

<div class="container cards">

<div class="row g-4">

<div class="col-md-4">

<div class="card feature text-center p-4">

<i class="fas fa-envelope text-primary"></i>

<h4>Instant Email Alerts</h4>

<p>

Students automatically receive email notifications whenever their semester results are published.

</p>

</div>

</div>

<div class="col-md-4">

<div class="card feature text-center p-4">

<i class="fas fa-lock text-success"></i>

<h4>Secure Login</h4>

<p>

Separate authentication for administrators and students keeps academic data protected.

</p>

</div>

</div>

<div class="col-md-4">

<div class="card feature text-center p-4">

<i class="fas fa-chart-line text-warning"></i>

<h4>Result Management</h4>

<p>

Manage students, semesters, subjects and results from a centralized dashboard.

</p>

</div>

</div>

</div>

<div class="row justify-content-center">

<div class="col-lg-7">

<div class="card login-card">

<div class="card-body text-center p-5">

<h2 class="mb-3">

Choose Your Portal

</h2>

<p class="text-muted">

Access the appropriate portal to continue.

</p>

<div class="d-grid gap-3 mt-4">

<a href="admin/login.php" class="btn btn-primary btn-lg">

<i class="fas fa-user-shield"></i>

Administrator Login

</a>

<a href="student/login.php" class="btn btn-success btn-lg">

<i class="fas fa-user-graduate"></i>

Student Login

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<div class="footer">

<p class="mb-0">
© 2026 EduNotify. All Rights Reserved.
</p>

</div>

</body>

</html>