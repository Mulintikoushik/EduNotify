<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");

if (!isset($_GET['id'])) {
    header("Location: semesters.php");
    exit();
}

$semester_id = $_GET['id'];

$error = "";

// UPDATE SEMESTER
if (isset($_POST['update_semester'])) {

    $semester_name = trim($_POST['semester_name']);
    $academic_year = trim($_POST['academic_year']);

    // Check duplicate semester (except current record)
    $check_sql = "SELECT * FROM semesters
                  WHERE semester_name = ?
                  AND semester_id != ?";

    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "si", $semester_name, $semester_id);
    mysqli_stmt_execute($check_stmt);

    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {

        $error = "Semester already exists.";

    } else {

        $update_sql = "UPDATE semesters
                       SET semester_name = ?,
                           academic_year = ?
                       WHERE semester_id = ?";

        $update_stmt = mysqli_prepare($conn, $update_sql);

        mysqli_stmt_bind_param(
            $update_stmt,
            "ssi",
            $semester_name,
            $academic_year,
            $semester_id
        );

        if (mysqli_stmt_execute($update_stmt)) {
            header("Location: semesters.php");
            exit();
        } else {
            $error = "Unable to update semester.";
        }
    }
}

// FETCH SEMESTER
$sql = "SELECT * FROM semesters WHERE semester_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $semester_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$semester = mysqli_fetch_assoc($result);

include("../includes/header.php");
?>

<div class="container-fluid">

<div class="row mt-4">

<?php include("../includes/sidebar.php"); ?>

<div class="col-md-8 mx-auto">

<div class="card shadow">

<div class="card-header bg-warning text-dark">

<h3>✏️ Edit Semester</h3>

</div>

<div class="card-body">

<?php
if (!empty($error)) {
    echo "<div class='alert alert-danger'>$error</div>";
}
?>

<form method="POST">

<div class="mb-3">
    <label class="form-label">Semester Name</label>
    <input
        type="text"
        name="semester_name"
        class="form-control"
        value="<?php echo $semester['semester_name']; ?>"
        required>
</div>

<div class="mb-3">
    <label class="form-label">Academic Year</label>
    <input
        type="text"
        name="academic_year"
        class="form-control"
        value="<?php echo $semester['academic_year']; ?>"
        required>
</div>

<button
    type="submit"
    name="update_semester"
    class="btn btn-success">
    💾 Update Semester
</button>

<a href="semesters.php" class="btn btn-secondary">
    Cancel
</a>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include("../includes/footer.php"); ?>