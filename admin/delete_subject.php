<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");

// Check if subject ID exists
if (!isset($_GET['id'])) {
    header("Location: subjects.php");
    exit();
}

$subject_id = $_GET['id'];

// Delete Query
$sql = "DELETE FROM subjects WHERE subject_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $subject_id);

if (mysqli_stmt_execute($stmt)) {

    header("Location: subjects.php");
    exit();

} else {

    echo "Unable to delete subject.";

}
?>