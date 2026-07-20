<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");

$error = "";

if (isset($_POST['add_semester'])) {

    $semester_name = trim($_POST['semester_name']);
    $academic_year = trim($_POST['academic_year']);

    // Check if semester already exists
    $check_sql = "SELECT * FROM semesters WHERE semester_name = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "s", $semester_name);
    mysqli_stmt_execute($check_stmt);
    $check_result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($check_result) > 0) {

        $error = "Semester already exists.";

    } else {

        $sql = "INSERT INTO semesters
                (semester_name, academic_year)
                VALUES (?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "ss",
            $semester_name,
            $academic_year
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: semesters.php");
            exit();
        } else {
            $error = "Unable to add semester.";
        }
    }
}

include("../includes/header.php");
?>

<div class="container-fluid">

    <div class="row mt-4">

        <?php include("../includes/sidebar.php"); ?>

        <div class="col-md-8 mx-auto">

            <div class="card shadow">

                <div class="card-header bg-success text-white">
                    <h3>➕ Add Semester</h3>
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
        placeholder="Example: Semester 1"
        required>
</div>

<div class="mb-3">
    <label class="form-label">Academic Year</label>
    <input
        type="text"
        name="academic_year"
        class="form-control"
        placeholder="Example: 2025-2026"
        required>
</div>

<button
    type="submit"
    name="add_semester"
    class="btn btn-success">
    ➕ Add Semester
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