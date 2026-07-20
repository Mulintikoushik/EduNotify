<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");

if (isset($_POST['save_student'])) {

    $hall_ticket = trim($_POST['hall_ticket']);
    $full_name   = trim($_POST['full_name']);
    $gender      = $_POST['gender'];
    $dob         = $_POST['dob'];
    $email       = trim($_POST['email']);
    $phone       = trim($_POST['phone']);
    $department  = $_POST['department'];
    $year        = $_POST['year'];

    $sql = "INSERT INTO students
            (hall_ticket, full_name, gender, dob, email, phone, department, year)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssis",
        $hall_ticket,
        $full_name,
        $gender,
        $dob,
        $email,
        $phone,
        $department,
        $year
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: students.php");
        exit();

    } else {

        $error = "Unable to save student.";
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
                    <h4>Add Student</h4>
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
                                <label>Hall Ticket</label>
                                <input type="text" name="hall_ticket" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Full Name</label>
                                <input type="text" name="full_name" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Gender</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">Select</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Department</label>
                                <select name="department" class="form-control" required>
                                    <option value="">Select</option>
                                    <option>CSE</option>
                                    <option>ECE</option>
                                    <option>EEE</option>
                                    <option>MECH</option>
                                    <option>CIVIL</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Year</label>
                                <select name="year" class="form-control" required>
                                    <option value="">Select</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                </select>
                            </div>

                        </div>

                        <button
                            type="submit"
                            name="save_student"
                            class="btn btn-success">
                            Save Student
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