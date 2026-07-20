<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");

/* ===========================
   Total Students
=========================== */
$sql = "SELECT COUNT(*) AS total_students FROM students";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$studentCount = $row['total_students'];

/* ===========================
   Total Admins
=========================== */
$sql = "SELECT COUNT(*) AS total_admins FROM admin";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$adminCount = $row['total_admins'];

/* ===========================
   Total Subjects
=========================== */
$sql = "SELECT COUNT(*) AS total_subjects FROM subjects";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$subjectCount = $row['total_subjects'];

/* ===========================
   Total Results
=========================== */
$sql = "SELECT COUNT(*) AS total_results FROM results";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$resultCount = $row['total_results'];

/* ===========================
   Passed Results
=========================== */
$sql = "SELECT COUNT(*) AS total_pass FROM results WHERE status='PASS'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$passCount = $row['total_pass'];

/* ===========================
   Failed Results
=========================== */
$sql = "SELECT COUNT(*) AS total_fail FROM results WHERE status='FAIL'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$failCount = $row['total_fail'];
/* ===========================
   Top Scorer
=========================== */

$sql = "
SELECT
    s.full_name,
    s.hall_ticket,
    AVG(r.marks) AS average_marks
FROM students s
INNER JOIN results r
ON s.student_id = r.student_id
GROUP BY
    s.student_id,
    s.full_name,
    s.hall_ticket
ORDER BY average_marks DESC
LIMIT 1
";

$result = mysqli_query($conn, $sql);
$topScorer = mysqli_fetch_assoc($result);
include("../includes/header.php");
?>

<div class="container-fluid">

    <div class="row mt-4">

        <!-- Sidebar -->
        <?php include("../includes/sidebar.php"); ?>

        <!-- Main Content -->
        <div class="col-md-9">

            <h2 class="mb-4">📊 Admin Dashboard</h2>

            <!-- Dashboard Cards -->
            <div class="row">

                <!-- Students -->
                <div class="col-md-4 mb-4">
                    <div class="card bg-primary text-white shadow">
                        <div class="card-body text-center">
                            <h5>👨‍🎓 Total Students</h5>
                            <h1><?php echo $studentCount; ?></h1>
                        </div>
                    </div>
                </div>

                <!-- Subjects -->
                <div class="col-md-4 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body text-center">
                            <h5>📚 Total Subjects</h5>
                            <h1><?php echo $subjectCount; ?></h1>
                        </div>
                    </div>
                </div>

                <!-- Results -->
                <div class="col-md-4 mb-4">
                    <div class="card bg-warning text-dark shadow">
                        <div class="card-body text-center">
                            <h5>📝 Total Results</h5>
                            <h1><?php echo $resultCount; ?></h1>
                        </div>
                    </div>
                </div>
                <!-- Passed -->
                <div class="col-md-6 mb-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body text-center">
                            <h5>✅ Passed Results</h5>
                            <h1><?php echo $passCount; ?></h1>
                        </div>
                    </div>
                </div>

                <!-- Failed -->
                <div class="col-md-6 mb-4">
                    <div class="card bg-danger text-white shadow">
                        <div class="card-body text-center">
                            <h5>❌ Failed Results</h5>
                            <h1><?php echo $failCount; ?></h1>
                        </div>
                    </div>
                </div>

            </div>
<!-- Top Scorer -->
<div class="card border-primary shadow mb-4">

    <div class="card-header bg-primary text-white">
        🏆 Top Scorer
    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <h4><?= htmlspecialchars($topScorer['full_name']) ?></h4>
            </div>

            <div class="col-md-4">
                <strong>Hall Ticket:</strong><br>
                <?= htmlspecialchars($topScorer['hall_ticket']) ?>
            </div>

            <div class="col-md-4">
                <strong>Average Marks:</strong><br>
                <?= round($topScorer['average_marks'],2) ?>%
            </div>

        </div>

    </div>
<?php

/* ===========================
   Highest Scoring Subject
=========================== */

$subjectQuery = "
SELECT
    sub.subject_name,
    ROUND(AVG(r.marks),2) AS average_marks,
    COUNT(r.result_id) AS total_results
FROM results r
INNER JOIN subjects sub
ON r.subject_id = sub.subject_id
GROUP BY sub.subject_name
ORDER BY average_marks DESC
LIMIT 1
";

$subjectResult = mysqli_query($conn, $subjectQuery);
$topSubject = mysqli_fetch_assoc($subjectResult);


/* ===========================
   Department Wise Analytics
=========================== */

$departmentQuery = "
SELECT
    'Computer Science' AS department,
    COUNT(DISTINCT s.student_id) AS total_students,
    COUNT(r.result_id) AS total_results,
    SUM(CASE WHEN r.status='PASS' THEN 1 ELSE 0 END) AS passed,
    SUM(CASE WHEN r.status='FAIL' THEN 1 ELSE 0 END) AS failed,
    ROUND(AVG(r.marks),2) AS average_marks
FROM students s
LEFT JOIN results r
ON s.student_id = r.student_id
";

$departmentResult = mysqli_query($conn, $departmentQuery);

?>
</div>
<!-- Department Wise Analytics -->
<div class="card shadow mb-4">

    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">📊 Department Wise Analytics</h5>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-primary">
                    <tr>
                        <th>Department</th>
                        <th>Total Students</th>
                        <th>Total Results</th>
                        <th>Passed</th>
                        <th>Failed</th>
                        <th>Average Marks</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($dept = mysqli_fetch_assoc($departmentResult)) { ?>

                    <tr>

                        <td><?= htmlspecialchars($dept['department']) ?></td>

                        <td><?= $dept['total_students'] ?></td>

                        <td><?= $dept['total_results'] ?></td>

                        <td class="text-success fw-bold">
                            <?= $dept['passed'] ?>
                        </td>

                        <td class="text-danger fw-bold">
                            <?= $dept['failed'] ?>
                        </td>

                        <td><?= $dept['average_marks'] ?>%</td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>
            <!-- Quick Actions -->
            <div class="card shadow">

                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">⚡ Quick Actions</h4>
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3 mb-3">
                            <a href="add_student.php" class="btn btn-success w-100">
                                ➕ Add Student
                            </a>
                        </div>

                        <div class="col-md-3 mb-3">
                            <a href="students.php" class="btn btn-primary w-100">
                                👨‍🎓 View Students
                            </a>
                        </div>

                        <div class="col-md-3 mb-3">
                            <a href="add_result.php" class="btn btn-warning w-100">
                                📝 Add Result
                            </a>
                        </div>

                        <div class="col-md-3 mb-3">
                            <a href="results.php" class="btn btn-info w-100 text-white">
                                📊 View Results
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>