<?php
session_start();
include 'connect.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "You need to be logged in to change your name.";
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Sanitize inputs
    $first_name = $conn->real_escape_string($first_name);
    $last_name = $conn->real_escape_string($last_name);

    $email = $_SESSION['email'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE users SET firstName = ?, lastName = ? WHERE email = ?");
    $stmt->bind_param("sss", $first_name, $last_name, $email);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to Account.php after successful update
        header("Location: Account.php");
        exit();
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

$conn->close();
?>
