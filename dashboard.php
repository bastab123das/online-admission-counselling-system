<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.html");
    exit();
}


include('db_connect.php');

$student_id = $_SESSION['student_id'];


$sql = "SELECT * FROM students WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();


$allotment_sql = "SELECT * FROM seat_allotment WHERE student_id = ?";
$allotment_stmt = $conn->prepare($allotment_sql);
$allotment_stmt->bind_param("i", $student_id);
$allotment_stmt->execute();
$allotment_result = $allotment_stmt->get_result();
$allotment = $allotment_result->fetch_assoc();


$stmt->close();
$allotment_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($student['name']); ?>!</h1>
        <p>Your Admission Portal Dashboard</p>
    </header>

    <div class="container">
        <h2>Admission Details</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone']); ?></p>
        <p><strong>Rank:</strong> <?php echo htmlspecialchars($student['rank']); ?></p>
        <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
        <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($student['payment_status']); ?></p>
        <p><strong>Document Status:</strong> <?php echo htmlspecialchars($student['document_status']); ?></p>
        <p><strong>Login Status:</strong> <?php echo htmlspecialchars($student['login_status']); ?></p>

        
        <h3>Seat Allotment Status</h3>
        <?php if ($allotment) { ?>
            <p><strong>Allotted College:</strong> <?php echo htmlspecialchars($allotment['college_name']); ?></p>
            <p><strong>Allotted Course:</strong> <?php echo htmlspecialchars($allotment['course_name']); ?></p>
            <p><strong>Allotted Department:</strong> <?php echo htmlspecialchars($allotment['department_name']); ?></p>
            <p><strong>Allotment Status:</strong> Allotted</p>
        <?php } else { ?>
            <p><strong>Seat Allotment Status:</strong> Not Allotted Yet</p>
        <?php } ?>

        
        <a href="logout.php">Log Out</a>
    </div>
    
    <footer>
        <p>&copy; 2025 Online Admission Portal. All rights reserved.</p>
    </footer>
</body>
</html>
