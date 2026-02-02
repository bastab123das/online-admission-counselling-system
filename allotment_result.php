<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rank = $_POST['rank'];

    $query = "SELECT s.name, s.course, sa.department_name, sa.course_name, sa.allotment_status
              FROM students s
              JOIN seat_allotment sa ON s.student_id = sa.student_id
              WHERE s.rank = ?";
              
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $rank); 
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo "<h2>Seat Allotment Result</h2>";
        echo "<p><strong>Name:</strong> " . $row['name'] . "</p>";
        echo "<p><strong>Course:</strong> " . $row['course'] . "</p>";
        echo "<p><strong>Department Allotted:</strong> " . $row['department_name'] . "</p>";
        echo "<p><strong>Course Allotted:</strong> " . $row['course_name'] . "</p>";
        echo "<p><strong>Status:</strong> " . $row['allotment_status'] . "</p>";
    } else {
        echo "<p>No allotment found for this rank.</p>";
    }
} else {
    echo "<p>Invalid request.</p>";
}

mysqli_close($conn);
?>
