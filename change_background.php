<?php
session_start();
include("connect.php");

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    
    // Handle background image upload
    if (isset($_FILES['profile_background'])) {
        $file_name = $_FILES['profile_background']['name'];
        $temp_name = $_FILES['profile_background']['tmp_name'];
        $file_type = $_FILES['profile_background']['type'];

        if (strpos($file_type, "image") !== false) {
            $path = "assets/backgrounds/";
            $location = $path . $file_name;
            move_uploaded_file($temp_name, $location);
            $query = mysqli_query($conn, "UPDATE `users` SET user_profile_background='$file_name' WHERE email='$email'");
            if ($query) {
                // Background updated successfully
                // Redirect or show success message
                header("Location: aAccount.php"); // Redirect to profile page or wherever appropriate
                exit();
            } else {
                // Handle database update failure
                echo "Failed to update background.";
            }
        } else {
            echo "Invalid file type. Only image files are allowed.";
        }
    }
}
?>
