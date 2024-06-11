<?php
session_start();

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);

        // Verify the user is logged in and the session user_id matches the request
        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id) {
            $sql = "DELETE FROM users WHERE user_id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param('i', $user_id);

                if ($stmt->execute()) {
                    // Successfully deleted
                    session_destroy();
                    header("Location: LoginRegister.php");
                    exit();
                } else {
                    echo "Error deleting account. Please try again later.";
                }

                $stmt->close();
            } else {
                echo "Failed to prepare SQL statement.";
            }
        } else {
            echo "Unauthorized request.";
        }
    } else {
        echo "User ID not set.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
