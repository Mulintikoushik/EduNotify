<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once("../config/database.php");

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$semester = isset($_GET['semester']) ? (int)$_GET['semester'] : 0;
$status = isset($_GET['status']) ? trim($_GET['status']) : "";

/* ===============================
   DASHBOARD COUNTS
=================================*/

$totalResults = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM results")
)['total'];

$totalPass = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM results WHERE status='PASS'")
)['total'];

$totalFail = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM results WHERE status='FAIL'")
)['total'];

$totalSemesters = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM semesters")
)['total'];

/* ===============================
   MAIN QUERY
=================================*/

$sql = "
SELECT
results.result_id,
students.hall_ticket,
students.full_name,
subjects.subject_name,
semesters.semester_name,
results.marks,
results.grade,
results.status

FROM results

INNER JOIN students
ON students.student_id=results.student_id

INNER JOIN subjects
ON subjects.subject_id=results.subject_id

LEFT JOIN semesters
ON semesters.semester_id=results.semester_id

WHERE 1=1
";

$params = [];
$types = "";

if($search!="")
{
    $sql.=" AND
    (
        students.full_name LIKE ?
        OR
        students.hall_ticket LIKE ?
    )";

    $like="%".$search."%";

    $params[]=$like;
    $params[]=$like;

    $types.="ss";
}

if($semester>0)
{
    $sql.=" AND semesters.semester_id=?";

    $params[]=$semester;

    $types.="i";
}

if($status!="")
{
    $sql.=" AND results.status=?";

    $params[]=$status;

    $types.="s";
}

$sql.=" ORDER BY students.full_name ASC";

$stmt=mysqli_prepare($conn,$sql);

if(count($params)>0)
{
    mysqli_stmt_bind_param($stmt,$types,...$params);
}

mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);

$semesterList=mysqli_query(
$conn,
"SELECT semester_id,semester_name
FROM semesters
ORDER BY semester_id"
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
<i class="fas fa-chart-line text-primary"></i>
Result Management
</h2>

<p class="text-muted mb-0">
Manage all student examination results
</p>

</div>

<a href="add_result.php"
class="btn btn-success">

<i class="fas fa-plus-circle"></i>

Add Result

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
                            Total Results
                        </small>

                        <h3 class="fw-bold">
                            <?php echo $totalResults; ?>
                        </h3>

                    </div>

                    <div class="text-primary">

                        <i class="fas fa-file-alt fa-2x"></i>

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
                            Pass Results
                        </small>

                        <h3 class="fw-bold text-success">

                            <?php echo $totalPass; ?>

                        </h3>

                    </div>

                    <div class="text-success">

                        <i class="fas fa-check-circle fa-2x"></i>

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
                            Fail Results
                        </small>

                        <h3 class="fw-bold text-danger">

                            <?php echo $totalFail; ?>

                        </h3>

                    </div>

                    <div class="text-danger">

                        <i class="fas fa-times-circle fa-2x"></i>

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
                            Semesters
                        </small>

                        <h3 class="fw-bold">

                            <?php echo $totalSemesters; ?>

                        </h3>

                    </div>

                    <div class="text-warning">

                        <i class="fas fa-layer-group fa-2x"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- Search & Filter Card -->

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

                        Semester

                    </label>

                    <select
                        class="form-select"
                        name="semester">

                        <option value="0">

                            All Semesters

                        </option>

                        <?php

                        while($sem=mysqli_fetch_assoc($semesterList))
                        {

                        ?>

                        <option
                        value="<?php echo $sem['semester_id']; ?>"
                        <?php
                        if($semester==$sem['semester_id'])
                            echo "selected";
                        ?>>

                        <?php
                        echo $sem['semester_name'];
                        ?>

                        </option>

                        <?php
                        }
                        ?>

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        class="form-select"
                        name="status">

                        <option value="">
                            All
                        </option>

                        <option
                        value="PASS"
                        <?php if($status=="PASS") echo "selected"; ?>>

                        PASS

                        </option>

                        <option
                        value="FAIL"
                        <?php if($status=="FAIL") echo "selected"; ?>>

                        FAIL

                        </option>

                    </select>

                </div>

                <div class="col-md-3 d-flex align-items-end">

                    <button
                    class="btn btn-primary me-2">

                    <i class="fas fa-search"></i>

                    Search

                    </button>

                    <a
                    href="results.php"
                    class="btn btn-secondary">

                    <i class="fas fa-sync-alt"></i>

                    Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h5 class="mb-0">

<i class="fas fa-table"></i>

Result List

</h5>

</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-dark">

<tr>

<th>Hall Ticket</th>

<th>Student</th>

<th>Subject</th>

<th>Semester</th>

<th>Marks</th>

<th>Grade</th>

<th>Status</th>

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

<span class="fw-bold">

<?php echo htmlspecialchars($row['hall_ticket']); ?>

</span>

</td>

<td>

<?php echo htmlspecialchars($row['full_name']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['subject_name']); ?>

</td>

<td>

<span class="badge bg-info text-dark">

<?php echo htmlspecialchars($row['semester_name']); ?>

</span>

</td>

<td>

<strong>

<?php echo $row['marks']; ?>

</strong>

</td>

<td>

<span class="badge bg-secondary">

<?php echo htmlspecialchars($row['grade']); ?>

</span>

</td>

<td>

<?php

if($row['status']=="PASS")
{

?>

<span class="badge bg-success">

<i class="fas fa-check-circle"></i>

PASS

</span>

<?php

}
else
{

?>

<span class="badge bg-danger">

<i class="fas fa-times-circle"></i>

FAIL

</span>

<?php

}

?>

</td>

<td>

<a
href="edit_result.php?id=<?php echo $row['result_id']; ?>"
class="btn btn-warning btn-sm">

<i class="fas fa-edit"></i>

Edit

</a>

<a
href="delete_result.php?id=<?php echo $row['result_id']; ?>"
class="btn btn-danger btn-sm"

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

<td colspan="8" class="text-center py-5">

<i class="fas fa-folder-open fa-3x text-secondary mb-3"></i>

<h5>

No Results Found

</h5>

<p class="text-muted">

Try changing the search or filter.

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
</div>

</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const searchBox = document.querySelector("input[name='search']");

    if(searchBox){
        searchBox.focus();
    }

});

</script>

<?php include("../includes/footer.php"); ?>