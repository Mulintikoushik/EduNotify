<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");
include("../includes/header.php");
?>

<div class="container-fluid">

    <div class="row mt-4">

        <!-- Sidebar -->
        <?php include("../includes/sidebar.php"); ?>

        <!-- Main Content -->
        <div class="col-md-9">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📅 Semester Management</h2>

                <a href="add_semester.php" class="btn btn-success">
                    ➕ Add Semester
                </a>
            </div>

            <div class="card shadow">

                <div class="card-header bg-primary text-white">
                    Semester List
                </div>

                <div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

                        <thead class="table-dark text-center">
<tr>
    <th>ID</th>
    <th>Semester</th>
    <th>Academic Year</th>
    <th>Status</th>
    <th>Created At</th>
    <th>Actions</th>
</tr>
</thead>

                        <tbody>

                        <?php

$result = mysqli_query(
    $conn,
    "SELECT
        semester_id,
        semester_name,
        academic_year,
        is_published,
        created_at
     FROM semesters
     ORDER BY semester_id ASC"
);

while($row = mysqli_fetch_assoc($result)) {

?>

<tr>

<td class="text-center">
    <?php echo $row['semester_id']; ?>
</td>
    <td><?php echo $row['semester_name']; ?></td>

    <td><?php echo $row['academic_year']; ?></td>

<td class="text-center">
        <?php if($row['is_published']) { ?>

            <span class="badge bg-success">
                Published
            </span>

        <?php } else { ?>

            <span class="badge bg-warning text-dark">
    Not Published
</span>

        <?php } ?>

    </td>

    <td><?php echo $row['created_at']; ?></td>

<td class="text-center">

    <a href="edit_semester.php?id=<?php echo $row['semester_id']; ?>"
       class="btn btn-warning btn-sm me-1">
        ✏️ Edit
    </a>

    <a href="#"
       class="btn btn-danger btn-sm">
        🗑 Delete
    </a>

</td>
</tr>

<?php } ?>

                        </tbody>

</table>

</div>
                </div>

            </div>

        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>