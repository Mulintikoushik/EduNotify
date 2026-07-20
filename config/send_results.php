<?php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/mail.php';

function sendSemesterResultEmails($semester_id)
{
    global $conn;

    $sent = 0;
    $failed = 0;
        $sql = "
    SELECT DISTINCT
        students.student_id,
        students.full_name,
        students.email,
        students.hall_ticket,
        semesters.semester_name

    FROM results

    INNER JOIN students
        ON students.student_id = results.student_id

    INNER JOIN semesters
        ON semesters.semester_id = results.semester_id

    WHERE results.semester_id = ?
      AND students.email IS NOT NULL
      AND students.email <> ''

    ORDER BY students.full_name
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $semester_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
        while ($student = mysqli_fetch_assoc($result)) {

        $subject = "Semester Results Published";

        $body = "
        <h2>Result Alert System</h2>

        <p>Dear <strong>{$student['full_name']}</strong>,</p>

        <p>Your <strong>{$student['semester_name']}</strong> results have been published.</p>

        <p><b>Hall Ticket:</b> {$student['hall_ticket']}</p>

        <p>You can now log in to the Result Alert System and view your marks.</p>

        <br>

        <p>Regards,</p>

        <p><b>Examination Cell</b></p>
        ";

        $mail = sendMail(
            $student['email'],
            $student['full_name'],
            $subject,
            $body
        );

        if ($mail === true) {
            $sent++;
        } else {
            $failed++;
        }
    }

    return [
        'sent' => $sent,
        'failed' => $failed
    ];
}