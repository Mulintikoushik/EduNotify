<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

require_once("../config/database.php");
require_once("../config/send_results.php");

/* ===========================
   Publish / Unpublish
=========================== */

if (isset($_GET['action']) && isset($_GET['id'])) {

    $semester_id = (int)$_GET['id'];

    if ($_GET['action'] == "publish") {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE semesters
             SET is_published = 1,
                 published_at = NOW()
             WHERE semester_id = ?"
        );

    } elseif ($_GET['action'] == "unpublish") {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE semesters
             SET is_published = 0,
                 published_at = NULL
             WHERE semester_id = ?"
        );

    }

    if (isset($stmt)) {

    mysqli_stmt_bind_param($stmt, "i", $semester_id);
    mysqli_stmt_execute($stmt);

    if ($_GET['action'] == "publish") {

        $emailResult = sendSemesterResultEmails($semester_id);

        $sent = $emailResult['sent'];
        $failed = $emailResult['failed'];

        header(
            "Location: publish_results.php?success=1&sent=$sent&failed=$failed"
        );
        exit();
    }

    header("Location: publish_results.php");
    exit();
}
}

/* ===========================
   Fetch Semesters
=========================== */

$query = "
SELECT
    semester_id,
    semester_name,
    academic_year,
    is_published,
    published_at
FROM semesters
ORDER BY semester_id
";

$result = mysqli_query($conn, $query);

include("../includes/header.php");
?>

<div class="container-fluid mt-4">

<div class="row">

<?php include("../includes/sidebar.php"); ?>

<div class="col-lg-10">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3 class="mb-0">

<i class="fas fa-bullhorn"></i>

Publish Results

</h3>

</div>

<div class="card-body">
        <?php if (isset($_GET['success'])) { ?>

<div class="alert alert-success">

    <h5>
        <i class="fas fa-check-circle"></i>
        Results Published Successfully
    </h5>

    <hr>

    <strong>Emails Sent:</strong>
    <?php echo (int)$_GET['sent']; ?>

    <br>

    <strong>Failed:</strong>
    <?php echo (int)$_GET['failed']; ?>

</div>

<?php } ?>
    <div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="80">ID</th>

<th>Semester</th>

<th>Academic Year</th>

<th>Status</th>

<th>Published On</th>

<th width="220">Action</th>

</tr>

</thead>

<tbody>

<?php

if(mysqli_num_rows($result) > 0)
{

while($row = mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<?php echo $row['semester_id']; ?>

</td>

<td>

<strong>

<?php echo htmlspecialchars($row['semester_name']); ?>

</strong>

</td>

<td>

<?php echo htmlspecialchars($row['academic_year']); ?>

</td>

<td>

<?php

if($row['is_published'])
{

?>

<span class="badge bg-success">

<i class="fas fa-check-circle"></i>

Published

</span>

<?php

}
else
{

?>

<span class="badge bg-danger">

<i class="fas fa-times-circle"></i>

Unpublished

</span>

<?php

}

?>

</td>

<td>

<?php

if($row['published_at'] != NULL)
{

echo date(
"d M Y h:i A",
strtotime($row['published_at'])
);

}
else
{

echo "-";

}

?>

</td>

<td>

<?php

if($row['is_published'])
{

?>

<a
href="publish_results.php?action=unpublish&id=<?php echo $row['semester_id']; ?>"
class="btn btn-danger btn-sm">

<i class="fas fa-eye-slash"></i>

Unpublish

</a>

<?php

}
else
{

?>

<a
href="publish_results.php?action=publish&id=<?php echo $row['semester_id']; ?>"
class="btn btn-success btn-sm">

<i class="fas fa-bullhorn"></i>

Publish

</a>

<?php

}

?>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="6" class="text-center">

No semesters found.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>
<?php

$totalSemesters = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM semesters"
    )
)['total'];

$publishedCount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM semesters
         WHERE is_published = 1"
    )
)['total'];

$unpublishedCount = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM semesters
         WHERE is_published = 0"
    )
)['total'];

?>

<hr>

<div class="row mt-4">

<div class="col-md-4 mb-3">

<div class="card bg-primary text-white shadow">

<div class="card-body text-center">

<h5>Total Semesters</h5>

<h2>

<?php echo $totalSemesters; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-4 mb-3">

<div class="card bg-success text-white shadow">

<div class="card-body text-center">

<h5>Published</h5>

<h2>

<?php echo $publishedCount; ?>

</h2>

</div>

</div>

</div>

<div class="col-md-4 mb-3">

<div class="card bg-danger text-white shadow">

<div class="card-body text-center">

<h5>Unpublished</h5>

<h2>

<?php echo $unpublishedCount; ?>

</h2>

</div>

</div>

</div>

</div>

<div class="alert alert-info mt-3">

<i class="fas fa-info-circle"></i>

<strong>Note:</strong>

Students can view results only for semesters that are marked as
<strong>Published</strong>.

</div>
</div>

</div>

</div>

</div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const actionButtons = document.querySelectorAll("a[href*='action=publish'], a[href*='action=unpublish']");

    actionButtons.forEach(function(button){

        button.addEventListener("click", function(e){

            let action = this.href.includes("publish")
                ? "publish"
                : "unpublish";

            let message =
                action === "publish"
                ? "Are you sure you want to publish these results?"
                : "Are you sure you want to unpublish these results?";

            if(!confirm(message)){
                e.preventDefault();
            }

        });

    });

});

</script>

<?php include("../includes/footer.php"); ?>