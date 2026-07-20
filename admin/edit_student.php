<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");

// Check if student ID is passed
if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$student_id = $_GET['id'];

// ==========================
// UPDATE STUDENT
// ==========================
if (isset($_POST['update_student'])) {

    $full_name  = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $department = trim($_POST['department']);
    $year       = $_POST['year'];

    $update_sql = "UPDATE students
                   SET full_name = ?,
                       email = ?,
                       phone = ?,
                       department = ?,
                       year = ?
                   WHERE student_id = ?";

    $update_stmt = mysqli_prepare($conn, $update_sql);

    mysqli_stmt_bind_param(
        $update_stmt,
        "ssssii",
        $full_name,
        $email,
        $phone,
        $department,
        $year,
        $student_id
    );

    if (mysqli_stmt_execute($update_stmt)) {
        header("Location: students.php");
        exit();
    } else {
        $error = "Failed to update student.";
    }
}

// ==========================
// FETCH STUDENT DETAILS
// ==========================

$select_sql = "SELECT * FROM students WHERE student_id = ?";

$select_stmt = mysqli_prepare($conn, $select_sql);

mysqli_stmt_bind_param($select_stmt, "i", $student_id);

mysqli_stmt_execute($select_stmt);

$result = mysqli_stmt_get_result($select_stmt);

$student = mysqli_fetch_assoc($result);

include("../includes/header.php");
?>

<div class="container-fluid">

    <div class="row mt-4">

        <?php include("../includes/sidebar.php"); ?>

        <div class="col-md-9">

            <div class="card shadow">

                <div class="card-header bg-warning">
                    <h3>Edit Student</h3>
                </div>

                <div class="card-body">

                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Hall Ticket</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="<?php echo $student['hall_ticket']; ?>"
                                    readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input
                                    type="text"
                                    name="full_name"
                                    class="form-control"
                                    value="<?php echo $student['full_name']; ?>"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?php echo $student['email']; ?>">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    value="<?php echo $student['phone']; ?>"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Department</label>
                                <select
                                    name="department"
                                    class="form-select"
                                    required>

                                    <option value="CSE" <?php if($student['department']=="CSE") echo "selected"; ?>>CSE</option>
                                    <option value="ECE" <?php if($student['department']=="ECE") echo "selected"; ?>>ECE</option>
                                    <option value="EEE" <?php if($student['department']=="EEE") echo "selected"; ?>>EEE</option>
                                    <option value="MECH" <?php if($student['department']=="MECH") echo "selected"; ?>>MECH</option>
                                    <option value="CIVIL" <?php if($student['department']=="CIVIL") echo "selected"; ?>>CIVIL</option>

                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Year</label>

                                <select
                                    name="year"
                                    class="form-select"
                                    required>

                                    <option value="1" <?php if($student['year']==1) echo "selected"; ?>>1st Year</option>
                                    <option value="2" <?php if($student['year']==2) echo "selected"; ?>>2nd Year</option>
                                    <option value="3" <?php if($student['year']==3) echo "selected"; ?>>3rd Year</option>
                                    <option value="4" <?php if($student['year']==4) echo "selected"; ?>>4th Year</option>

                                </select>
                            </div>

                        </div>

                        <button
                            type="submit"
                            name="update_student"
                            class="btn btn-success">
                            Update Student
                        </button>

                        <a href="students.php" class="btn btn-secondary">
                            Cancel
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>