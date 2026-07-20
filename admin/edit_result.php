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
    results.*,
    students.full_name,
    students.student_id,
    subjects.subject_name,
    subjects.subject_id,
    semesters.semester_name,
    semesters.semester_id
FROM results
INNER JOIN students
    ON students.student_id = results.student_id
INNER JOIN subjects
    ON subjects.subject_id = results.subject_id
INNER JOIN semesters
    ON semesters.semester_id = results.semester_id
WHERE results.result_id = ?
");

mysqli_stmt_bind_param($stmt, "i", $result_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: results.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

$students = mysqli_query($conn,
"SELECT student_id, full_name
FROM students
ORDER BY full_name");

$subjects = mysqli_query($conn,
"SELECT subject_id, subject_name
FROM subjects
ORDER BY subject_name");

$semesters = mysqli_query($conn,
"SELECT semester_id, semester_name
FROM semesters
ORDER BY semester_id");

include("../includes/header.php");
?>

<div class="container-fluid mt-4">

<div class="row">

<?php include("../includes/sidebar.php"); ?>

<div class="col-lg-10">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

<h4 class="mb-0">

<i class="fas fa-edit"></i>

Edit Result

</h4>

</div>

<div class="card-body">
    <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id  = (int)$_POST['student_id'];
    $subject_id  = (int)$_POST['subject_id'];
    $semester_id = (int)$_POST['semester_id'];
    $marks       = (int)$_POST['marks'];
    $marks = (int)$_POST['marks'];

if ($marks >= 90) {
    $grade = "A+";
    $status = "PASS";
}
elseif ($marks >= 80) {
    $grade = "A";
    $status = "PASS";
}
elseif ($marks >= 70) {
    $grade = "B";
    $status = "PASS";
}
elseif ($marks >= 60) {
    $grade = "C";
    $status = "PASS";
}
elseif ($marks >= 50) {
    $grade = "D";
    $status = "PASS";
}
elseif ($marks >= 35) {
    $grade = "E";
    $status = "PASS";
}
else {
    $grade = "F";
    $status = "FAIL";
}

    $update = mysqli_prepare($conn, "
        UPDATE results
        SET
            student_id=?,
            subject_id=?,
            semester_id=?,
            marks=?,
            grade=?,
            status=?
        WHERE result_id=?
    ");

    mysqli_stmt_bind_param(
        $update,
        "iiiissi",
        $student_id,
        $subject_id,
        $semester_id,
        $marks,
        $grade,
        $status,
        $result_id
    );

    if (mysqli_stmt_execute($update)) {

        echo '<div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                Result updated successfully.
              </div>';

        echo "<script>
                setTimeout(function(){
                    window.location='results.php';
                },1000);
              </script>";

    } else {

        echo '<div class="alert alert-danger">
                Unable to update the result.
              </div>';
    }
}

?>

<form method="POST">

<div class="row">

<div class="col-md-6 mb-3">

<label class="form-label">

Student

</label>

<select
name="student_id"
class="form-select"
required>

<?php while($student=mysqli_fetch_assoc($students)){ ?>

<option
value="<?php echo $student['student_id']; ?>"
<?php
if($student['student_id']==$row['student_id'])
echo "selected";
?>>

<?php echo htmlspecialchars($student['full_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Subject

</label>

<select
name="subject_id"
class="form-select"
required>

<?php while($subject=mysqli_fetch_assoc($subjects)){ ?>

<option
value="<?php echo $subject['subject_id']; ?>"
<?php
if($subject['subject_id']==$row['subject_id'])
echo "selected";
?>>

<?php echo htmlspecialchars($subject['subject_name']); ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-6 mb-3">

<label class="form-label">

Semester

</label>

<select
name="semester_id"
class="form-select"
required>

<?php while($semester=mysqli_fetch_assoc($semesters)){ ?>

<option
value="<?php echo $semester['semester_id']; ?>"
<?php
if($semester['semester_id']==$row['semester_id'])
echo "selected";
?>>

<?php echo htmlspecialchars($semester['semester_name']); ?>

</option>

<?php } ?>

</select>

</div>
<div class="col-md-6 mb-3">

<label class="form-label">

Marks

</label>

<input
type="number"
name="marks"
class="form-control"
min="0"
max="100"
required
value="<?php echo $row['marks']; ?>">

</div>

<div class="col-md-3 mb-3">

<label class="form-label">

Grade

</label>

<input
type="text"
name="grade"
class="form-control"
value="<?php echo htmlspecialchars($row['grade']); ?>"
readonly>

</div>

</div>
<div class="col-12 mb-4">

<div
id="resultPreview"
class="alert alert-info text-center fw-bold fs-5">

Enter marks to see the result preview.

</div>

</div>
<hr>

<div class="d-flex justify-content-between">

<a
href="results.php"
class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>

Back

</a>

<button
type="submit"
class="btn btn-success">

<i class="fas fa-save"></i>

Update Result

</button>

</div>

</form>
</div>

</div>

</div>

</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const marksInput = document.querySelector("input[name='marks']");
    const gradeInput = document.querySelector("input[name='grade']");
    const preview = document.getElementById("resultPreview");

    if (marksInput) {

        marksInput.addEventListener("input", function () {

            let marks = parseInt(this.value);

            if (isNaN(marks)) {
                gradeInput.value = "";
                return;
            }

           let grade = "";
let status = "";

if (marks >= 90) {
    grade = "A+";
    status = "PASS";
}
else if (marks >= 80) {
    grade = "A";
    status = "PASS";
}
else if (marks >= 70) {
    grade = "B";
    status = "PASS";
}
else if (marks >= 60) {
    grade = "C";
    status = "PASS";
}
else if (marks >= 50) {
    grade = "D";
    status = "PASS";
}
else if (marks >= 35) {
    grade = "E";
    status = "PASS";
}
else {
    grade = "F";
    status = "FAIL";
}

gradeInput.value = grade;

if (status === "PASS") {

    preview.className = "alert alert-success text-center fw-bold fs-5";

    preview.innerHTML =
        '<i class="fas fa-check-circle"></i> PASS (Grade ' + grade + ')';

}
else {

    preview.className = "alert alert-danger text-center fw-bold fs-5";

    preview.innerHTML =
        '<i class="fas fa-times-circle"></i> FAIL (Grade ' + grade + ')';

}
        });
marksInput.dispatchEvent(new Event("input"));
    }

});

</script>

<?php include("../includes/footer.php"); ?>