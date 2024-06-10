<?php
session_start();
include 'connect.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo "You need to be logged in to change your profile picture.";
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email'];

    // Check if file was uploaded without errors
    if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == 0) {
        $target_dir = "assets/profile/"; // Change this to your desired directory
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Generate a unique filename to prevent overwriting existing images
        $new_filename = uniqid() . "." . $imageFileType;
        $new_target_file = $target_dir . $new_filename;

        // Delete the previous profile image if it exists
        $select_query = "SELECT user_profile_image FROM users WHERE email = '$email'";
        $result = $conn->query($select_query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $previous_image = $row["user_profile_image"];
            if (!empty($previous_image)) {
                $previous_image_path = $target_dir . $previous_image;
                if (file_exists($previous_image_path)) {
                    unlink($previous_image_path);
                }
            }
        }

        // Move the uploaded file to the desired location
        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $new_target_file)) {
            // Update the user's profile image in the database
            $update_query = "UPDATE users SET user_profile_image = '$new_filename' WHERE email = '$email'";
            if ($conn->query($update_query) === TRUE) {
                // Redirect to Account.php after successful update
                header("Location: Account.php");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "No file uploaded or an error occurred.";
    }
}

$conn->close();
?>
