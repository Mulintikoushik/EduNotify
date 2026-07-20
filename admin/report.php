<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");
include("../includes/header.php");
include("../includes/sidebar.php");

$student = null;
$results = [];
$summary = [
    "total_subjects" => 0,
    "total_marks" => 0,
    "percentage" => 0,
    "overall" => "FAIL"
];

if(isset($_POST['search']) && !empty($_POST['hall_ticket'])){
    $hall = trim($_POST['hall_ticket']);

    $stmt = $conn->prepare("SELECT * FROM students WHERE hall_ticket = ?");
    $stmt->bind_param("s",$hall);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if($student){
        $stmt = $conn->prepare("
            SELECT s.subject_name, r.marks, r.status
            FROM results r
            INNER JOIN subjects s ON r.subject_id = s.subject_id            WHERE r.student_id = ?
            ORDER BY s.subject_name
        ");
            $stmt->bind_param("i",$student['student_id']);        
            $stmt->execute();
        $res = $stmt->get_result();

        $pass = true;
        while($row = $res->fetch_assoc()){
            $results[] = $row;
            $summary["total_subjects"]++;
            $summary["total_marks"] += (float)$row["marks"];
            if(strtoupper($row["status"]) !== "PASS"){
                $pass = false;
            }
        }
        $stmt->close();

        if($summary["total_subjects"]>0){
            $summary["percentage"] = round($summary["total_marks"]/$summary["total_subjects"],2);
        }
        $summary["overall"] = $pass ? "PASS" : "FAIL";
    }
}
?>

<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Result Report</h4>
        </div>
        <div class="card-body">

<form method="POST" class="row g-3 mb-4">

    <div class="col-md-10">
        <input type="text"
               name="hall_ticket"
               class="form-control"
               placeholder="Enter Hall Ticket Number"
               value="<?= isset($_POST['hall_ticket']) ? htmlspecialchars($_POST['hall_ticket']) : '' ?>"
               required>
    </div>

    <div class="col-md-2">
        <button
            type="submit"
            name="search"
            class="btn btn-primary w-100">
            Search
        </button>
    </div>

</form>

            <?php if(isset($_POST['search']) && !$student): ?>
                <div class="alert alert-danger">No student found.</div>
            <?php endif; ?>

            <?php if($student): ?>
                <div class="text-end mb-3">

    <a href="print_report.php?hall_ticket=<?= urlencode($student['hall_ticket']) ?>"
       target="_blank"
       class="btn btn-success">

        🖨 Print Result

    </a>

</div>
    <div class="card mb-3 shadow-sm">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Student Details</h5>
        </div>

        <div class="card-body">

            <div class="row mb-3">

                <div class="col-md-6">
                    <strong>Name :</strong><br>
                    <?= htmlspecialchars($student['full_name']) ?>
                </div>

                <div class="col-md-6">
                    <strong>Hall Ticket :</strong><br>
                    <?= htmlspecialchars($student['hall_ticket']) ?>
                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-6">
                    <strong>Department :</strong><br>
                    <?= htmlspecialchars($student['department']) ?>
                </div>

                <div class="col-md-6">
                    <strong>Year :</strong><br>
                    <?= htmlspecialchars($student['year']) ?>
                </div>

            </div>

            <div class="row mb-3">

                <div class="col-md-6">
                    <strong>Gender :</strong><br>
                    <?= htmlspecialchars($student['gender']) ?>
                </div>

                <div class="col-md-6">
                    <strong>Date of Birth :</strong><br>
                    <?= htmlspecialchars($student['dob']) ?>
                </div>

            </div>

            <div class="row">

                <div class="col-md-6">
                    <strong>Email :</strong><br>
                    <?= htmlspecialchars($student['email']) ?>
                </div>

                <div class="col-md-6">
                    <strong>Phone :</strong><br>
                    <?= htmlspecialchars($student['phone']) ?>
                </div>

            </div>

        </div>
    </div>

                <table class="table table-bordered table-striped">
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
                            <td><?= htmlspecialchars($row['marks']) ?></td>
                            <td>
                                <span class="badge bg-<?= strtoupper($row['status'])=="PASS"?"success":"danger" ?>">
                                    <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="alert alert-info"><strong>Total Subjects:</strong><br><?= $summary["total_subjects"] ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-primary"><strong>Total Marks:</strong><br><?= $summary["total_marks"] ?></div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-warning"><strong>Percentage:</strong><br><?= $summary["percentage"] ?>%</div>
                    </div>
                    <div class="col-md-3">
                        <div class="alert alert-<?= $summary["overall"]=="PASS"?"success":"danger" ?>">
                            <strong>Overall Result:</strong><br><?= $summary["overall"] ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include("../includes/footer.php"); ?>