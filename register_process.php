<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("HTTP/1.1 405 Method Not Allowed");
    exit("405 - Method Not Allowed");
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$address = trim($_POST['address']);
$course = $_POST['course'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$rank = intval($_POST['rank']);


$checkStmt = $conn->prepare("SELECT email FROM students WHERE email = ?");
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(["error" => "Email already registered! Please use another email."]);
    $checkStmt->close();
    $conn->close();
    exit;
}
$checkStmt->close();


$uploadDir = "uploads/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$profilePicture = $_FILES['photo']['name'] ? basename($_FILES['photo']['name']) : null;
$idProof = $_FILES['id_proof']['name'] ? basename($_FILES['id_proof']['name']) : null;

$profileTarget = $uploadDir . $profilePicture;
$idTarget = $uploadDir . $idProof;

$uploadSuccess = true;
$uploadErrors = [];

if ($profilePicture) {
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $profileTarget)) {
        $uploadSuccess = false;
        $uploadErrors[] = "Error uploading profile picture!";
    }
}

if ($idProof) {
    if (!move_uploaded_file($_FILES['id_proof']['tmp_name'], $idTarget)) {
        $uploadSuccess = false;
        $uploadErrors[] = "Error uploading ID proof!";
    }
}

if (!$uploadSuccess) {
    echo json_encode(["error" => implode(" ", $uploadErrors)]);
    exit;
}


$stmt = $conn->prepare("INSERT INTO students (name, email, phone, dob, gender, address, course, rank, password, profile_picture, id_proof) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssss", $name, $email, $phone, $dob, $gender, $address, $course, $rank, $password, $profilePicture, $idProof);


if ($stmt->execute()) {
    echo json_encode(["message" => "Registration Successful"]);
} else {
    echo json_encode(["error" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
