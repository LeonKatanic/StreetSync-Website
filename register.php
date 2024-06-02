<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    $first_name = $conn->real_escape_string($first_name);
    $last_name = $conn->real_escape_string($last_name);

    $user_email = $_SESSION['user_email'];

    $stmt = $conn->prepare("UPDATE users SET user_name = ?, user_surname = ? WHERE user_email = ?");
    $stmt->bind_param("sss", $first_name, $last_name, $user_email);

    if ($stmt->execute()) {
        header("Location: Account.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>