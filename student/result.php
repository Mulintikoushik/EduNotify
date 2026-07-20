<?php
require_once("../config/database.php");

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: login.php");
    exit();
}

$hall_ticket = trim($_POST['hall_ticket']);
$semester_id = intval($_POST['semester_id']);

if (empty($hall_ticket) || empty($semester_id)) {
    die("Invalid Request");
}

/* Check whether the selected semester is published */
$semesterStmt = $conn->prepare("
    SELECT semester_name, academic_year
    FROM semesters
    WHERE semester_id = ?
      AND is_published = 1
");

$semesterStmt->bind_param("i", $semester_id);
$semesterStmt->execute();
$semesterResult = $semesterStmt->get_result();

if ($semesterResult->num_rows == 0) {
    die("
    <div style='font-family:Arial;text-align:center;margin-top:80px'>
        <h2>Results Not Published</h2>
        <p>The selected semester results have not been published yet.</p>
        <a href='login.php'>Go Back</a>
    </div>");
}

$semester = $semesterResult->fetch_assoc();

/* Fetch Student Details */
$studentStmt = $conn->prepare("
    SELECT *
    FROM students
    WHERE hall_ticket = ?
");

$studentStmt->bind_param("s", $hall_ticket);
$studentStmt->execute();

$studentResult = $studentStmt->get_result();

if ($studentResult->num_rows == 0) {
    die("
    <div style='font-family:Arial;text-align:center;margin-top:80px'>
        <h2>Student Not Found</h2>
        <p>No student found with the entered Hall Ticket Number.</p>
        <a href='login.php'>Go Back</a>
    </div>");
}

$student = $studentResult->fetch_assoc();

/* Fetch Subject-wise Results */
$resultStmt = $conn->prepare("
SELECT
subjects.subject_code,
subjects.subject_name,
subjects.credits,
results.marks,
results.grade,
results.status
FROM results
INNER JOIN subjects
ON subjects.subject_id = results.subject_id
WHERE
results.student_id = ?
AND results.semester_id = ?
ORDER BY subjects.subject_code
");

$resultStmt->bind_param(
    "ii",
    $student['student_id'],
    $semester_id
);

$resultStmt->execute();

$resultData = $resultStmt->get_result();

if ($resultData->num_rows == 0) {
    die("
    <div style='font-family:Arial;text-align:center;margin-top:80px'>
        <h2>No Results Found</h2>
        <p>No marks are available for this semester.</p>
        <a href='login.php'>Go Back</a>
    </div>");
}

$totalMarks = 0;
$totalSubjects = 0;
$overallStatus = "PASS";
?>
<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>EduNotify | Student Result</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    background:#f5f7fb;
}

.card{
    border:none;
    border-radius:15px;
}

.table th{
    background:#0d6efd;
    color:white;
}

</style>

</head>

<body>

<div class="container py-5">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<div class="d-flex justify-content-between align-items-center">

<h3 class="mb-0">

<i class="fas fa-graduation-cap"></i>

EduNotify Student Result

</h3>

<button
onclick="window.print();"
class="btn btn-light">

<i class="fas fa-print"></i>

Print

</button>

</div>

</div>

<div class="card-body">

<div class="alert alert-primary mt-3">

    <h5>
        Welcome,
        <strong><?php echo htmlspecialchars($student['full_name']); ?></strong> 👋
    </h5>

    <p class="mb-0">
        Your results for
        <strong><?php echo htmlspecialchars($semester['semester_name']); ?></strong>
        are displayed below.
    </p>

</div>

<div class="row mb-4">

<div class="col-md-6">

<table class="table table-borderless">

<tr>

<th width="180">Hall Ticket</th>

<td><?php echo htmlspecialchars($student['hall_ticket']); ?></td>

</tr>

<tr>

<th>Student Name</th>

<td><?php echo htmlspecialchars($student['full_name']); ?></td>

</tr>

<tr>

<th>Department</th>

<td><?php echo htmlspecialchars($student['department']); ?></td>

</tr>

<tr>

<th>Email</th>

<td><?php echo htmlspecialchars($student['email']); ?></td>

</tr>

<tr>

<th>Year</th>

<td><?php echo htmlspecialchars($student['year']); ?></td>

</tr>

</table>

</div>

<div class="col-md-6">

<table class="table table-borderless">

<tr>

<th>Semester</th>

<td>

<?php echo htmlspecialchars($semester['semester_name']); ?>

<span class="badge bg-success ms-2">

Published

</span>

</td>

</tr>

<th>Academic Year</th>

<td><?php echo htmlspecialchars($semester['academic_year']); ?></td>

</tr>

<tr>

<th>Date</th>

<td><?php echo date("d-m-Y"); ?></td>

</tr>

</table>

</div>

</div>

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>Code</th>

<th>Subject</th>

<th>Credits</th>

<th>Marks</th>

<th>Grade</th>

<th>Status</th>

</tr>

</thead>

<tbody>

<?php

while($row = $resultData->fetch_assoc())
{

$totalMarks += $row['marks'];

$totalSubjects++;

if(strtoupper($row['status']) != "PASS")
{
    $overallStatus = "FAIL";
}

?>

<tr>

<td><?php echo htmlspecialchars($row['subject_code']); ?></td>

<td><?php echo htmlspecialchars($row['subject_name']); ?></td>

<td><?php echo $row['credits']; ?></td>

<td><?php echo $row['marks']; ?></td>

<td><?php echo htmlspecialchars($row['grade']); ?></td>

<td>

<?php
if($row['status']=="PASS")
{
?>
<span class="badge bg-success">PASS</span>
<?php
}
else
{
?>
<span class="badge bg-danger">FAIL</span>
<?php
}
?>

</td>

</tr>

<?php

}

$percentage = ($totalSubjects>0)
? ($totalMarks/($totalSubjects*100))*100
: 0;

if($percentage>=90)
    $overallGrade="A+";
elseif($percentage>=80)
    $overallGrade="A";
elseif($percentage>=70)
    $overallGrade="B";
elseif($percentage>=60)
    $overallGrade="C";
elseif($percentage>=50)
    $overallGrade="D";
elseif($percentage>=35)
    $overallGrade="E";
else
    $overallGrade="F";

?>

</tbody>

</table>

</div>
<div class="row mt-4">

    <div class="col-md-3">
        <div class="card text-center bg-primary text-white">
            <div class="card-body">
                <h6>Total Subjects</h6>
                <h3><?php echo $totalSubjects; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center bg-success text-white">
            <div class="card-body">
                <h6>Total Marks</h6>
                <h3><?php echo $totalMarks; ?></h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center bg-warning text-dark">
            <div class="card-body">
                <h6>Percentage</h6>
                <h3><?php echo number_format($percentage,2); ?>%</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-center <?php echo ($overallStatus=="PASS") ? "bg-info" : "bg-danger"; ?> text-white">
            <div class="card-body">
                <h6>Overall Result</h6>
                <h4><?php echo $overallStatus; ?></h4>
                <small>Grade : <?php echo $overallGrade; ?></small>
            </div>
        </div>
    </div>

</div>

<div class="text-center mt-4">

    <a href="login.php" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i>
        Search Another Result
    </a>

    <button onclick="window.print();" class="btn btn-primary">
        <i class="fas fa-print"></i>
        🖨 Print / Save as PDF
    </button>

<a href="download_pdf.php?hall_ticket=<?php echo urlencode($student['hall_ticket']); ?>&semester_id=<?php echo $semester_id; ?>"
   class="btn btn-danger">

    <i class="fas fa-file-pdf"></i>
    Download PDF

</a>

</div>

</div>

</div>

</div>

</body>

</html>