<?php
session_start();
include('db_connect.php'); // Database connection file

if (!isset($_SESSION['admin_id'])) {
    // If the admin is not logged in, redirect to the admin login page
    header('Location: admin_login.html');
    exit();
}

$student_id = $_POST['student_id']; // Student ID for whom seat is being allotted
$rank = $_POST['rank']; // Rank of the student
$seat_alloted = "No seat allotted"; // Default seat status

// Fetch the student's choices from the student_choices table
$query = "SELECT * FROM student_choices WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if ($row) {
    // Allocating seat based on rank and available seats
    $college1 = $row['college1'];
    $course1 = $row['course1'];
    $college2 = $row['college2'];
    $course2 = $row['course2'];
    $college3 = $row['college3'];
    $course3 = $row['course3'];

    
    if ($rank <= 100) {
        $seat_alloted = "College: $college1, Course: $course1";
    } elseif ($rank <= 200) {
        $seat_alloted = "College: $college2, Course: $course2";
    } elseif ($rank <= 300) {
        $seat_alloted = "College: $college3, Course: $course3";
    } else {
        $seat_alloted = "No seat allotted";
    }

    
    $update_query = "UPDATE students SET seat_allotment = '$seat_alloted' WHERE student_id = '$student_id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Seat allocation successful.');</script>";
        echo "<script>window.location.href = 'admin_dashboard.html';</script>";
    } else {
        echo "<script>alert('Failed to allocate seat. Please try again.');</script>";
    }
} else {
    echo "<script>alert('Student not found or choices not filled.');</script>";
}

mysqli_close($conn); 
?>
