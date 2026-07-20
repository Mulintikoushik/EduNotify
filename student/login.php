<?php
require_once("../config/database.php");

$semesterQuery = mysqli_query(
    $conn,
    "SELECT semester_id, semester_name, academic_year
     FROM semesters
     WHERE is_published = 1
     ORDER BY semester_id"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>EduNotify | Student Portal</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    background:#f5f7fb;
}

.portal-card{
    max-width:700px;
    margin:auto;
    margin-top:70px;
    border:none;
    border-radius:18px;
}

.portal-header{
    background:#0d6efd;
    color:#fff;
    padding:30px;
    text-align:center;
    border-radius:18px 18px 0 0;
}

.portal-body{
    padding:35px;
    background:#fff;
}

.footer-text{
    text-align:center;
    color:#6c757d;
    margin-top:25px;
}

</style>

</head>

<body>

<div class="container">

<div class="card shadow-lg portal-card">

<div class="portal-header">

<h2>
<i class="fas fa-graduation-cap"></i>
EduNotify
</h2>

<p class="mb-0">
EduNotify Student Portal
</p>

</div>

<div class="portal-body">

<form action="result.php" method="POST">
    
<div class="mb-3">

<label class="form-label">

Hall Ticket Number

</label>

<input
type="text"
name="hall_ticket"
class="form-control"
placeholder="Enter Hall Ticket Number"
required>

</div>

<div class="mb-4">

<label class="form-label">

Semester

</label>

<select
name="semester_id"
class="form-select"
required>

<option value="">Select Semester</option>

<?php while($semester=mysqli_fetch_assoc($semesterQuery)){ ?>

<option value="<?php echo $semester['semester_id']; ?>">

<?php
echo $semester['semester_name']
." ("
.$semester['academic_year']
.")";
?>

</option>

<?php } ?>

</select>

</div>

<div class="d-grid">

<button
class="btn btn-primary btn-lg">

<i class="fas fa-search"></i>

View Result

</button>

</div>

</form>

<div class="footer-text">

Only published semester results are available.

</div>

</div>

</div>

</div>

</body>

</html>