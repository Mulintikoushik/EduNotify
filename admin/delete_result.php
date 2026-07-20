<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once("../config/database.php");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: results.php");
    exit();
}

$result_id = (int)$_GET['id'];

$stmt = mysqli_prepare($conn, "
SELECT
    r.result_id,
    r.marks,
    r.grade,
    r.status,
    s.hall_ticket,
    s.full_name,
    sub.subject_name,
    sem.semester_name
FROM results r
INNER JOIN students s
    ON s.student_id = r.student_id
INNER JOIN subjects sub
    ON sub.subject_id = r.subject_id
INNER JOIN semesters sem
    ON sem.semester_id = r.semester_id
WHERE r.result_id = ?
");

mysqli_stmt_bind_param($stmt, "i", $result_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: results.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

include("../includes/header.php");
?>

<div class="container-fluid mt-4">

<div class="row">

<?php include("../includes/sidebar.php"); ?>

<div class="col-lg-10">

<div class="card shadow border-danger">

<div class="card-header bg-danger text-white">

<h3 class="mb-0">

<i class="fas fa-trash-alt"></i>

Delete Result

</h3>

</div>

<div class="card-body">
    <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $delete = mysqli_prepare($conn,
        "DELETE FROM results WHERE result_id = ?"
    );

    mysqli_stmt_bind_param($delete, "i", $result_id);

    if (mysqli_stmt_execute($delete)) {

        echo "<script>
                alert('Result deleted successfully.');
                window.location='results.php';
              </script>";
        exit();

    } else {

        echo '<div class="alert alert-danger">
                Unable to delete the result.
              </div>';
    }
}

?>

<div class="alert alert-danger">

<h4>

<i class="fas fa-exclamation-triangle"></i>

Warning!

</h4>

Deleting this result is permanent and cannot be undone.

</div>

<table class="table table-bordered">

<tr>

<th width="220">Hall Ticket</th>

<td><?php echo htmlspecialchars($row['hall_ticket']); ?></td>

</tr>

<tr>

<th>Student Name</th>

<td><?php echo htmlspecialchars($row['full_name']); ?></td>

</tr>

<tr>

<th>Subject</th>

<td><?php echo htmlspecialchars($row['subject_name']); ?></td>

</tr>

<tr>

<th>Semester</th>

<td><?php echo htmlspecialchars($row['semester_name']); ?></td>

</tr>

<tr>

<th>Marks</th>

<td><?php echo $row['marks']; ?></td>

</tr>

<tr>

<th>Grade</th>

<td>

<span class="badge bg-secondary">

<?php echo htmlspecialchars($row['grade']); ?>

</span>

</td>

</tr>

<tr>

<th>Status</th>

<td>

<?php if ($row['status'] == "PASS") { ?>

<span class="badge bg-success">

PASS

</span>

<?php } else { ?>

<span class="badge bg-danger">

FAIL

</span>

<?php } ?>

</td>

</tr>

</table>

<form method="POST">

<div class="d-flex justify-content-between">

<a
href="results.php"
class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>

Cancel

</a>

<button
type="submit"
class="btn btn-danger">

<i class="fas fa-trash"></i>

Yes, Delete Result

</button>

</div>

</form>
</div>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>