<?php

include 'connect.php';

if (isset($_POST['signUp'])) {
    $user_name = $_POST['userName'] ?? '';
    $user_surname = $_POST['userSurname'] ?? '';
    $user_email = $_POST['registerEmail'] ?? '';
    $password = $_POST['registerPassword'] ?? '';
    $password = md5($password);

    $checkEmail = "SELECT * FROM users WHERE user_email='$user_email'";
    $result = $conn->query($checkEmail);
    if ($result && $result->num_rows > 0) {
        echo "Email address already exists!";
    } else {
        $insertQuery = "INSERT INTO users(user_email, password, user_name, user_surname) VALUES ('$user_email', '$password', '$user_name', '$user_surname')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: LoginRegister.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $user_email = $_POST['loginEmail'] ?? '';
    $password = $_POST['loginPassword'] ?? '';
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE user_email='$user_email' AND password='$password'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['user_email'] = $row['user_email'];
        header("Location: HomePage.php");
        exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}
?>
