<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");

$error = "";

if (isset($_POST['save_result'])) {

    $student_id  = $_POST['student_id'];
    $subject_id  = $_POST['subject_id'];
    $semester_id = $_POST['semester_id'];
    $marks       = $_POST['marks'];

    if ($marks >= 90) $grade = "A+";
    elseif ($marks >= 80) $grade = "A";
    elseif ($marks >= 70) $grade = "B";
    elseif ($marks >= 60) $grade = "C";
    elseif ($marks >= 50) $grade = "D";
    else $grade = "F";

    $status = ($marks >= 50) ? "PASS" : "FAIL";

    $check = "SELECT result_id FROM results
              WHERE student_id=? AND subject_id=? AND semester_id=?";

    $check_stmt = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($check_stmt, "iii", $student_id, $subject_id, $semester_id);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {
        $error = "Result already exists for this student, subject and semester.";
    } else {

        $sql = "INSERT INTO results
                (student_id, subject_id, semester_id, marks, grade, status)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iiiiss",
            $student_id,
            $subject_id,
            $semester_id,
            $marks,
            $grade,
            $status
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: results.php");
            exit();
        } else {
            $error = "Unable to save result.";
        }
    }
}

include("../includes/header.php");
?>

<div class="container-fluid">
<div class="row mt-4">

<?php include("../includes/sidebar.php"); ?>

<div class="col-md-9">

<div class="card shadow">

<div class="card-header bg-success text-white">
<h3>Add Result</h3>
</div>

<div class="card-body">

<?php
if(!empty($error)){
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="POST">

<div class="mb-3">
<label class="form-label">Student</label>
<select name="student_id" class="form-select" required>
<option value="">Select Student</option>
<?php
$students = mysqli_query($conn,"SELECT * FROM students ORDER BY full_name");
while($student = mysqli_fetch_assoc($students)){
?>
<option value="<?php echo $student['student_id']; ?>">
<?php echo $student['full_name']." (".$student['hall_ticket'].")"; ?>
</option>
<?php } ?>
</select>
</div>

<div class="mb-3">
<label class="form-label">Subject</label>
<select name="subject_id" class="form-select" required>
<option value="">Select Subject</option>
<?php
$subjects = mysqli_query($conn,"SELECT * FROM subjects ORDER BY subject_name");
while($subject = mysqli_fetch_assoc($subjects)){
?>
<option value="<?php echo $subject['subject_id']; ?>">
<?php echo $subject['subject_code']." - ".$subject['subject_name']; ?>
</option>
<?php } ?>
</select>
</div>

<div class="mb-3">
<label class="form-label">Semester</label>
<select name="semester_id" class="form-select" required>
<option value="">Select Semester</option>
<?php
$semesters = mysqli_query($conn,"SELECT * FROM semesters ORDER BY semester_id");
while($semester = mysqli_fetch_assoc($semesters)){
?>
<option value="<?php echo $semester['semester_id']; ?>">
<?php echo $semester['semester_name']." (".$semester['academic_year'].")"; ?>
</option>
<?php } ?>
</select>
</div>

<div class="mb-3">
<label class="form-label">Marks</label>
<input type="number" name="marks" class="form-control" min="0" max="100" required>
</div>

<button type="submit" name="save_result" class="btn btn-success">Save Result</button>
<a href="results.php" class="btn btn-secondary">Cancel</a>

</form>

</div>
</div>
</div>
</div>
</div>

<?php include("../includes/footer.php"); ?>
