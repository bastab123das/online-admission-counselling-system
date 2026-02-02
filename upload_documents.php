<?php
session_start();
include 'db_connect.php'; 


if (!isset($_SESSION['student_id'])) {
    echo "<p style='color:red;'>Student not logged in.</p>";
    exit;
}

$student_id = $_SESSION['student_id'];


$stmt = $conn->prepare("SELECT seat_allotment_status FROM students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<p style='color:red;'>Student record not found.</p>";
    exit;
}

$row = $result->fetch_assoc();

if ($row['seat_allotment_status'] == 'Not Allotted') {
    echo "<p style='color:red;'>You cannot upload documents until a seat is allotted.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $target_directory = "uploads/"; 
        $target_file = $target_directory . basename($_FILES["document"]["name"]);

        
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_file_types = array("pdf", "docx", "jpg", "jpeg", "png");

        if (!in_array($file_type, $valid_file_types)) {
            echo "<p style='color:red;'>Invalid file type. Please upload a PDF, DOCX, JPG, or PNG file.</p>";
            exit;
        }

        
        if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
            
            $stmt = $conn->prepare("UPDATE students SET document_uploaded = ? WHERE student_id = ?");
            $stmt->bind_param("si", $target_file, $student_id);
            $stmt->execute();

            echo "<p style='color:green;'>The file has been uploaded successfully.</p>";
        } else {
            echo "<p style='color:red;'>Sorry, there was an error uploading your file.</p>";
        }
    } else {
        echo "<p style='color:red;'>No file selected or there was an error with the file.</p>";
    }
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <label for="document">Upload Document:</label>
    <input type="file" name="document" required>
    <button type="submit">Upload</button>
</form>
