<?php

include 'connect.php';

if (isset($_POST['signUp'])) {
    $username = $_POST['userName'] ?? '';
    $surname = $_POST['userSurname'] ?? '';
    $email = $_POST['registerEmail'] ?? '';
    $password = $_POST['registerPassword'] ?? '';
    $password = md5($password);

    $checkEmail = "SELECT * FROM users WHERE user_email='$email'";
    $result = $conn->query($checkEmail);
    if ($result->num_rows > 0) {
        echo "Email address already exists!";
    } else {
        $insertQuery = "INSERT INTO users(user_name, user_surname, user_email, password) VALUES ('$username', '$surname', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: LoginRegister.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['loginEmail'] ?? '';
    $password = $_POST['loginPassword'] ?? '';
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE user_email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['user_email'];
        header("Location: HomePage.php");
        exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}

?>
