<?php

include('db_connect.php');


$email = $_POST['email'];
$password = $_POST['password'];


if ($conn === null) {
    die("Database connection failed: " . mysqli_connect_error());
}


$query = "SELECT student_id, name, email, password FROM students WHERE email = ?";
$stmt = $conn->prepare($query);


if ($stmt === false) {
    die('SQL Error: ' . $conn->error);
}


$stmt->bind_param("s", $email);


$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows > 0) {
    
    $user = $result->fetch_assoc();

    
    if (password_verify($password, $user['password'])) {
        
        session_start();
        $_SESSION['student_id'] = $user['student_id']; 
        $_SESSION['name'] = $user['name']; 

        
        header("Location: dashboard.php");
        exit();
    } else {
        
        echo "Invalid email or password!";
    }
} else {
    
    echo "Invalid email or password!";
}


$stmt->close();
$conn->close();
?>
