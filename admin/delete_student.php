<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../config/database.php");

// Check whether ID is passed
if (!isset($_GET['id'])) {
    header("Location: students.php");
    exit();
}

$student_id = $_GET['id'];

// SQL DELETE Query
$sql = "DELETE FROM students WHERE student_id = ?";

// Prepare Statement
$stmt = mysqli_prepare($conn, $sql);

// Bind Parameter
mysqli_stmt_bind_param($stmt, "i", $student_id);

// Execute Query
mysqli_stmt_execute($stmt);

// Redirect back
header("Location: students.php");
exit();
?>