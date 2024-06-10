<?php 
include 'connect.php';

if(isset($_POST['signUp'])){
    $firstName = $_POST['userName'];
    $lastName = $_POST['userSurname'];
    $email = $_POST['registerEmail'];
    $password = $_POST['registerPassword'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

     $checkEmail = "SELECT * FROM users WHERE email = '$email'";
     $result = $conn->query($checkEmail);
     if($result->num_rows > 0){
        echo "Email Address Already Exists !";
     }
     else{
        $insertQuery = "INSERT INTO users (firstName, lastName, email, password)
                       VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
        if($conn->query($insertQuery) === TRUE){
            header("location: index.php");
        }
        else{
            echo "Error: " . $conn->error;
        }
     }
}

if(isset($_POST['signIn'])){
   $email = $_POST['loginEmail'];
   $password = $_POST['loginPassword'];
   
   $sql = "SELECT * FROM users WHERE email = '$email'";
   $result = $conn->query($sql);
   if($result->num_rows > 0){
       $row = $result->fetch_assoc();
       if (password_verify($password, $row['password'])) {
           session_start();
           $_SESSION['email'] = $row['email'];
           header("Location: homepage.php");
           exit();
       } else {
           echo "Incorrect Email or Password";
       }
   }
   else{
       echo "Incorrect Email or Password";
   }
}
?>
