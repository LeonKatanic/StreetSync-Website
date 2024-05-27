<?php

include 'connect.php';

if (isset($_POST['signUp'])) {
    $fullname = $_POST['fullName'] ?? '';
    $username = $_POST['userName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password = md5($password);

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "Email address already exists!";
    } else {
        $insertQuery = "INSERT INTO users(username, email, password, fullname) VALUES ('$username', '$email', '$password', '$fullname')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: LoginRegister.php");
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        header("Location: HomePage.php");
        exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}
?>
