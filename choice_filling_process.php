<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['student_id'])) {
    header('Location: login.html');
    exit();
}

$student_id = $_SESSION['student_id'];

$college1 = mysqli_real_escape_string($conn, $_POST['college1']);
$course1 = mysqli_real_escape_string($conn, $_POST['course1']);
$college2 = mysqli_real_escape_string($conn, $_POST['college2']);
$course2 = mysqli_real_escape_string($conn, $_POST['course2']);
$college3 = mysqli_real_escape_string($conn, $_POST['college3']);
$course3 = mysqli_real_escape_string($conn, $_POST['course3']);


$course_name = 'B.Tech';
$department_name = 'Computer Science'; 
$preference_rank = 1;


$query = "SELECT * FROM student_choices WHERE student_id = '$student_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $update_query = "UPDATE student_choices SET 
        department_name = '$department_name',
        course_name = '$course_name',
        preference_rank = '$preference_rank',
        college1 = '$college1', course1 = '$course1', 
        college2 = '$college2', course2 = '$course2', 
        college3 = '$college3', course3 = '$course3' 
        WHERE student_id = '$student_id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Your choices have been updated successfully.');</script>";
        echo "<script>window.location.href = 'index.html';</script>";
    } else {
        echo "<script>alert('Failed to update your choices.');</script>";
    }
} else {
    $insert_query = "INSERT INTO student_choices 
        (student_id, department_name, course_name, preference_rank, college1, course1, college2, course2, college3, course3) 
        VALUES ('$student_id', '$department_name', '$course_name', '$preference_rank', '$college1', '$course1', '$college2', '$course2', '$college3', '$course3')";
    
    if (mysqli_query($conn, $insert_query)) {
        echo "<script>alert('Your choices have been saved successfully.');</script>";
        echo "<script>window.location.href = 'index.html';</script>";
    } else {
        echo "<script>alert('Failed to save your choices.');</script>";
    }
}

mysqli_close($conn);
?>
