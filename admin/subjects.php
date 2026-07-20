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

        <?php include("../includes/sidebar.php"); ?>

        <div class="col-md-9">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2>📚 Subject Management</h2>

                <a href="add_subject.php" class="btn btn-success">
                    ➕ Add Subject
                </a>

            </div>

            <div class="card shadow">

                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Subject List</h4>
                </div>

                <div class="card-body">

                    <table class="table table-bordered table-hover">

                        <thead class="table-dark">

                            <tr>
                                <th>ID</th>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Semester</th>
                                <th>Credits</th>
                                <th>Actions</th>
                            </tr>

                        </thead>

                        <tbody>

                        <?php

                        $sql = "SELECT * FROM subjects ORDER BY subject_code";

                        $result = mysqli_query($conn, $sql);

                        if(mysqli_num_rows($result) > 0)
                        {
                            while($row = mysqli_fetch_assoc($result))
                            {
                        ?>

                        <tr>

                            <td><?php echo $row['subject_id']; ?></td>

                            <td><?php echo $row['subject_code']; ?></td>

                            <td><?php echo $row['subject_name']; ?></td>

                            <td><?php echo $row['semester']; ?></td>

                            <td><?php echo $row['credits']; ?></td>

                            <td>

                                <a href="edit_subject.php?id=<?php echo $row['subject_id']; ?>"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="delete_subject.php?id=<?php echo $row['subject_id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this subject?');">
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

                            <td colspan="6" class="text-center text-danger">

                                No Subjects Found

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

<?php include("../includes/footer.php"); ?>