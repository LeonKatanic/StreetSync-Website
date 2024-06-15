<?php 
include 'connect.php'; // Including the database connection file

// Handling registration form submission
if(isset($_POST['signUp'])){
    $firstName = $_POST['userName'];
    $lastName = $_POST['userSurname'];
    $email = $_POST['registerEmail'];
    $password = $_POST['registerPassword'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashing the password for security

    // Checking if the email already exists in the database
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);
    if($result->num_rows > 0){
        echo "Email Address Already Exists !"; // Display message if email already registered
    }
    else{
        // Inserting new user into the database
        $insertQuery = "INSERT INTO users (firstName, lastName, email, password)
                       VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";
        if($conn->query($insertQuery) === TRUE){
            header("location: LoginRegister.php"); // Redirect to homepage after successful registration
        }
        else{
            echo "Error: " . $conn->error; // Display error if insertion fails
        }
    }
}

// Handling login form submission
if(isset($_POST['signIn'])){
   $email = $_POST['loginEmail'];
   $password = $_POST['loginPassword'];
   
   // Querying the database to find the user with the provided email
   $sql = "SELECT * FROM users WHERE email = '$email'";
   $result = $conn->query($sql);
   if($result->num_rows > 0){
       $row = $result->fetch_assoc();
       // Verifying the provided password against the hashed password in the database
       if (password_verify($password, $row['password'])) {
           // Starting a session and setting session variables upon successful login
           session_start();
           $_SESSION['email'] = $row['email'];
           $_SESSION['loggedin'] = true;
           $_SESSION['user_id'] = $row['user_id'];
           header("Location: homepage.php"); // Redirecting to homepage upon successful login
           exit();
       } else {
           echo "Incorrect Email or Password"; // Display message if password is incorrect
       }
   }
   else{
       echo "Incorrect Email or Password"; // Display message if email is not found in the database
   }
}
?>
