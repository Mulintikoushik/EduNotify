<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once("../config/database.php");

/* ==========================================
   FILTERS
========================================== */

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$department = isset($_GET['department']) ? trim($_GET['department']) : "";
$year = isset($_GET['year']) ? trim($_GET['year']) : "";

/* ==========================================
   DASHBOARD COUNTS
========================================== */

$totalStudents = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM students")
)['total'];

$totalDepartments = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(DISTINCT department) total FROM students"
    )
)['total'];

$totalYears = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(DISTINCT year) total FROM students"
    )
)['total'];

$totalMale = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total
         FROM students
         WHERE gender='Male'"
    )
)['total'];

$totalFemale = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) total
         FROM students
         WHERE gender='Female'"
    )
)['total'];

/* ==========================================
   MAIN QUERY
========================================== */

$sql = "
SELECT
    student_id,
    hall_ticket,
    full_name,
    gender,
    dob,
    email,
    phone,
    department,
    year

FROM students

WHERE 1=1
";

$params = [];
$types = "";

if ($search != "") {

    $sql .= "
    AND
    (
        hall_ticket LIKE ?
        OR full_name LIKE ?
    )
    ";

    $like = "%".$search."%";

    $params[] = $like;
    $params[] = $like;

    $types .= "ss";
}

if ($department != "") {

    $sql .= " AND department=?";

    $params[] = $department;

    $types .= "s";
}

if ($year != "") {

    $sql .= " AND year=?";

    $params[] = $year;

    $types .= "s";
}

$sql .= "
ORDER BY full_name ASC
";

$stmt = mysqli_prepare($conn, $sql);

if(count($params)>0)
{
    mysqli_stmt_bind_param(
        $stmt,
        $types,
        ...$params
    );
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

/* ==========================================
   DROPDOWNS
========================================== */

$departmentList = mysqli_query(
    $conn,
    "SELECT DISTINCT department
     FROM students
     ORDER BY department"
);

$yearList = mysqli_query(
    $conn,
    "SELECT DISTINCT year
     FROM students
     ORDER BY year"
);

include("../includes/header.php");
?>

<div class="container-fluid mt-4">

<div class="row">

<?php include("../includes/sidebar.php"); ?>

<div class="col-lg-10">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h2 class="fw-bold">

<i class="fas fa-user-graduate text-primary"></i>

Student Management

</h2>

<p class="text-muted">

Manage all registered students

</p>

</div>

<a href="add_student.php"
class="btn btn-success">

<i class="fas fa-plus-circle"></i>

Add Student

</a>

</div>
<!-- Statistics Cards -->

<div class="row mb-4">

    <div class="col-md-3 mb-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Total Students
                        </small>

                        <h3 class="fw-bold">
                            <?php echo $totalStudents; ?>
                        </h3>

                    </div>

                    <div class="text-primary">

                        <i class="fas fa-user-graduate fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3 mb-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Departments
                        </small>

                        <h3 class="fw-bold">
                            <?php echo $totalDepartments; ?>
                        </h3>

                    </div>

                    <div class="text-success">

                        <i class="fas fa-building fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3 mb-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Academic Years
                        </small>

                        <h3 class="fw-bold">
                            <?php echo $totalYears; ?>
                        </h3>

                    </div>

                    <div class="text-warning">

                        <i class="fas fa-calendar-alt fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3 mb-3">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <small class="text-muted">
                            Male / Female
                        </small>

                        <h4 class="fw-bold">

                            <?php echo $totalMale; ?>

                            /

                            <?php echo $totalFemale; ?>

                        </h4>

                    </div>

                    <div class="text-danger">

                        <i class="fas fa-users fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Search Card -->

<div class="card shadow-sm mb-4">

<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-4">

<label class="form-label">

Search Student

</label>

<input
type="text"
name="search"
class="form-control"
placeholder="Hall Ticket / Student Name"
value="<?php echo htmlspecialchars($search); ?>">

</div>

<div class="col-md-3">

<label class="form-label">

Department

</label>

<select
class="form-select"
name="department">

<option value="">

All Departments

</option>

<?php

while($dept=mysqli_fetch_assoc($departmentList))
{

?>

<option
value="<?php echo htmlspecialchars($dept['department']); ?>"

<?php

if($department==$dept['department'])
echo "selected";

?>

>

<?php echo htmlspecialchars($dept['department']); ?>

</option>

<?php

}

?>

</select>

</div>

<div class="col-md-2">

<label class="form-label">

Year

</label>

<select
class="form-select"
name="year">

<option value="">

All Years

</option>

<?php

while($yr=mysqli_fetch_assoc($yearList))
{

?>

<option
value="<?php echo htmlspecialchars($yr['year']); ?>"

<?php

if($year==$yr['year'])
echo "selected";

?>

>

<?php echo htmlspecialchars($yr['year']); ?>

</option>

<?php

}

?>

</select>

</div>

<div class="col-md-3 d-flex align-items-end">

<button
class="btn btn-primary me-2">

<i class="fas fa-search"></i>

Search

</button>

<a
href="students.php"
class="btn btn-secondary">

<i class="fas fa-sync-alt"></i>

Reset

</a>

</div>

</div>

</form>

</div>

</div>

<!-- Student Table -->

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="fas fa-table"></i>

Student List

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Hall Ticket</th>

<th>Name</th>

<th>Gender</th>

<th>Email</th>

<th>Phone</th>

<th>Department</th>

<th>Year</th>

<th width="170">

Actions

</th>

</tr>

</thead>

<tbody>
    <?php

if(mysqli_num_rows($result)>0)
{

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<span class="badge bg-dark">

<?php echo htmlspecialchars($row['hall_ticket']); ?>

</span>

</td>

<td>

<strong>

<?php echo htmlspecialchars($row['full_name']); ?>

</strong>

</td>

<td>

<?php

if($row['gender']=="Male")
{

?>

<span class="badge bg-primary">

Male

</span>

<?php

}
else
{

?>

<span class="badge bg-danger">

Female

</span>

<?php

}

?>

</td>

<td>

<?php echo htmlspecialchars($row['email']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['phone']); ?>

</td>

<td>

<span class="badge bg-info text-dark">

<?php echo htmlspecialchars($row['department']); ?>

</span>

</td>

<td>

<span class="badge bg-warning text-dark">

<?php echo htmlspecialchars($row['year']); ?>

</span>

</td>

<td>

<a
href="edit_student.php?id=<?php echo $row['student_id']; ?>"
class="btn btn-sm btn-primary">

<i class="fas fa-edit"></i>

Edit

</a>

<a
href="delete_student.php?id=<?php echo $row['student_id']; ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Are you sure you want to delete this student?');">

<i class="fas fa-trash"></i>

Delete

</a>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="8" class="text-center p-5">

<i class="fas fa-user-slash fa-3x text-secondary mb-3"></i>

<h5>

No Students Found

</h5>

<p class="text-muted">

Try changing your search or filters.

</p>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

</div>
<!-- End Student Table -->

</div>

</div>

</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    // Enable Bootstrap Tooltips
    var tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );

    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

});

</script>

<?php

mysqli_stmt_close($stmt);
mysqli_close($conn);

include("../includes/footer.php");

?>