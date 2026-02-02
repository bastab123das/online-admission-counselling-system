<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];

    if (isset($_POST['payment_method']) && isset($_POST['payment_amount'])) {
        $payment_method = $_POST['payment_method'];
        $payment_amount = $_POST['payment_amount'];
        $payment_status = 'Completed';

        $stmt = $conn->prepare("UPDATE students SET payment_method = ?, payment_amount = ?, payment_status = ? WHERE student_id = ?");
        $stmt->bind_param("sdsi", $payment_method, $payment_amount, $payment_status, $student_id);
        
        if ($stmt->execute()) {
            echo "<p style='color:green;'>Payment has been successfully processed.</p>";
        } else {
            echo "<p style='color:red;'>Database update failed: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid payment details.</p>";
    }
} else {
    echo "<p style='color:red;'>You must be logged in to make a payment.</p>";
}
?>
