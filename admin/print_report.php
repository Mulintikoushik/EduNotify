<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");

if (!isset($_GET['hall_ticket'])) {
    die("Hall Ticket Missing");
}

$hall = trim($_GET['hall_ticket']);

$student = null;
$results = [];
$summary = [
    "total_subjects" => 0,
    "total_marks" => 0,
    "percentage" => 0,
    "overall" => "FAIL"
];

$stmt = $conn->prepare("SELECT * FROM students WHERE hall_ticket = ?");
$stmt->bind_param("s", $hall);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) {
    die("Student Not Found");
}

$stmt = $conn->prepare("
SELECT
    s.subject_name,
    r.marks,
    r.status
FROM results r
INNER JOIN subjects s
ON r.subject_id = s.subject_id
WHERE r.student_id = ?
ORDER BY s.subject_name
");

$stmt->bind_param("i", $student['student_id']);
$stmt->execute();
$res = $stmt->get_result();

$pass = true;

while($row = $res->fetch_assoc()){

    $results[] = $row;

    $summary["total_subjects"]++;

    $summary["total_marks"] += $row["marks"];

    if(strtoupper($row["status"])!="PASS"){
        $pass = false;
    }
}

$stmt->close();

$summary["percentage"] =
round(
$summary["total_marks"]/$summary["total_subjects"],
2
);

$summary["overall"] = $pass ? "PASS" : "FAIL";
?>

<!DOCTYPE html>
<html>

<head>

<title>Student Result</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

body{
    padding:40px;
}

table{
    margin-top:20px;
}

@media print{

button{
display:none;
}

}

</style>

</head>

<body>

<div class="container">
    
<h4 class="text-center mb-5">

Student Result Report

</h4>

<div class="row mb-4">

<div class="col-6">

<b>Name :</b>

<?= htmlspecialchars($student['full_name']) ?>

</div>

<div class="col-6">

<b>Hall Ticket :</b>

<?= htmlspecialchars($student['hall_ticket']) ?>

</div>

</div>

<div class="row mb-4">

<div class="col-6">

<b>Department :</b>

<?= htmlspecialchars($student['department']) ?>

</div>

<div class="col-6">

<b>Year :</b>

<?= htmlspecialchars($student['year']) ?>

</div>

</div>

<table class="table table-bordered">

<thead class="table-dark">

<tr>

<th>#</th>

<th>Subject</th>

<th>Marks</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php foreach($results as $i=>$row): ?>

<tr>

<td><?= $i+1 ?></td>

<td><?= htmlspecialchars($row['subject_name']) ?></td>

<td><?= $row['marks'] ?></td>

<td><?= $row['status'] ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<div class="row mt-4">

<div class="col-3">

<b>Total Subjects</b>

<br>

<?= $summary["total_subjects"] ?>

</div>

<div class="col-3">

<b>Total Marks</b>

<br>

<?= $summary["total_marks"] ?>

</div>

<div class="col-3">

<b>Percentage</b>

<br>

<?= $summary["percentage"] ?>%

</div>

<div class="col-3">

<b>Overall</b>

<br>

<?= $summary["overall"] ?>

</div>

</div>

<div class="text-center mt-5">

<button
class="btn btn-primary"
onclick="window.print()">

Print

</button>

<script>
window.onload = function () {
    window.print();
};
</script>

</body>
</html>