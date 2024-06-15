<?php
session_start(); // Start the session to manage user sessions

include("connect.php"); // Include the file that connects to the database

// Handle POST request to insert comment into the database
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $comment = $_POST['comment'];       // Get comment content from form
    $user_id = $_POST['user_id'];       // Get user ID from form
    $thread_id = $_POST['thread_id'];   // Get thread ID from form

    // Prepare SQL statement to insert comment into 'comments' table
    $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);       // Prepare statement
    $stmt->bind_param("sii", $comment, $thread_id, $user_id); // Bind parameters
    $result = $stmt->execute();         // Execute the statement

    if ($result) {
        // Redirect to the same page to prevent form resubmission
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        echo "Error: " . $stmt->error;   // Display error if execution fails
    }
}

// Get thread information based on thread ID from URL parameter
$id = $_GET['threadid'];
$sql = "SELECT * FROM `threads` WHERE thread_id = ?";
$stmt = $conn->prepare($sql);           // Prepare statement
$stmt->bind_param("i", $id);             // Bind parameter
$stmt->execute();                       // Execute the statement
$result = $stmt->get_result();           // Get result from execution

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $title = $row['thread_title'];      // Get thread title
        $desc = $row['thread_desc'];        // Get thread description
        $thuser_id = $row['thread_user_id'];// Get user ID who posted the thread

        // Query to fetch user's first and last name based on user ID
        $sql2 = "SELECT firstName, lastName FROM `users` WHERE user_id = ?";
        $stmt2 = $conn->prepare($sql2);     // Prepare statement
        $stmt2->bind_param("i", $thuser_id);// Bind parameter
        $stmt2->execute();                  // Execute the statement
        $result2 = $stmt2->get_result();    // Get result from execution

        if ($result2) {
            $row2 = mysqli_fetch_assoc($result2);
            $postedby = $row2['firstName'] . ' ' . $row2['lastName']; // Get posted by user's full name
        } else {
            echo "Error: " . $stmt2->error;  // Display error if execution fails
        }
    }
} else {
    echo "Error: " . $stmt->error;          // Display error if execution fails
}

// Get user information if logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'");
    if ($query && mysqli_num_rows($query) > 0) {
        $userInfo = mysqli_fetch_assoc($query);
        // Set profile picture source based on user's profile image availability
        $profilePicSrc = empty($userInfo['user_profile_image']) ? 'assets/profile/defaultPic.png' : 'assets/profile/'.$userInfo['user_profile_image'];
    }
}
?>


<!doctype html>
<html lang="en">
<head>
    <title>StreetSync</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <!-- Internal CSS styles -->
    <style>
    #footer {
        min-height: 500px;
    }
    </style>
    
    <!-- Meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- External CSS for specific styles -->
    <link rel="stylesheet" href="style/threads.css">
    <link rel="icon" type="image/x-icon" href="assets/images/StreetSyncLogo.png">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>
    <!-- Navbar section -->
    <div class="navbar">
        <ul>
            <div class="navlinks">
                <!-- Logo and home link -->
                <li style="float:left">
                    <a href="HomePage.php">
                        <img src="assets/images/StreetSyncName.png" alt="StreetSyncName">
                    </a>
                </li>
                <!-- Other navigation links -->
                <li>
                    <a href="AboutUs.php">About Us</a>
                </li>
                <li>
                    <a href="PrivacyPolicy.php">Privacy Policy</a>
                </li>
            </div>
            <!-- User profile and submenu -->
            <div class="hero">
                <nav>
                    <!-- Display user profile picture as clickable -->
                    <img src="<?php echo $profilePicSrc; ?>" class="user-pic" onclick="toggleMenu()">
                    <!-- Submenu for user options -->
                    <div class="sub-menu-wrap" id="subMenu">
                        <div class="sub-menu">
                            <div class="user-info">
                                <!-- Display user name if logged in -->
                                <img src="<?php echo $profilePicSrc; ?>" alt="Profile Picture">
                                <?php if (!empty($userInfo)) : ?>
                                    <p><?php echo $userInfo['firstName'] . ' ' . $userInfo['lastName']; ?></p>
                                <?php endif; ?>
                            </div>
                            <hr>
                            <!-- User account and logout links -->
                            <a href="Account.php" class="sub-menu-link">
                                <i class="bi bi-house"></i>
                                <p>Account</p>
                                <span>&gt;</span>
                            </a>
                            <a href="logout.php" class="sub-menu-link">
                                <i class="bi bi-box-arrow-left"></i>
                                <p>Log out</p>
                                <span>&gt;</span>
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </ul>
    </div>

    <!-- Container for thread details -->
    <div class="container my-3">
        <div class="jumbotron">
            <h1 class="display-3"><?php echo htmlspecialchars($title); ?></h1>
            <p class="lead"><?php echo htmlspecialchars($desc); ?></p>
            <hr class="my-2">
            <p>Posted by: <b><?php echo htmlspecialchars($postedby); ?></b></p>
        </div>
    </div>

    <!-- Container for posting comments -->
    <div class="container my-3">
        <h2 class="my-3">Post a comment</h2>
        <?php
        // Check if user is logged in to display comment form
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<form action="' . htmlspecialchars($_SERVER["REQUEST_URI"]) . '" method="post">
                <div class="form-group">
                   <textarea name="comment" rows="3" class="form-control"></textarea>
                   <input type="hidden" name="user_id" value="' . htmlspecialchars($_SESSION["user_id"]) . '">
                   <input type="hidden" name="thread_id" value="' . htmlspecialchars($id) . '">
                </div>
                <button type="submit" class="btn btn-primary">Post</button>
            </form>';
        } else {
            // Display message if user is not logged in
            echo '<div class="jumbotron">
            <h1 class="display-4">You are not able to post a comment</h1>
            <p class="lead">If you want to post a comment... Login First</p>
            <hr class="my-2">
            </div>';
        }
        ?>
    </div>

    <!-- Container for displaying comments -->
    <div class="container my-4" id="footer">
        <h2 class="text-center">Comments</h2>
        <div class="container my-3">
            <?php
            // Query to fetch comments associated with the current thread
            $sql = "SELECT * FROM `comments` WHERE thread_id = ?";
            $stmt = $conn->prepare($sql);       // Prepare statement
            $stmt->bind_param("i", $id);         // Bind parameter
            $stmt->execute();                   // Execute the statement
            $result = $stmt->get_result();      // Get result from execution

            if ($result) {
                $noresult = true;
                while ($row = mysqli_fetch_assoc($result)) {
                    $noresult = false;
                    $comment = $row['comment_content'];  // Get comment content
                    $postedby = $row['comment_by'];      // Get user who posted the comment

                    // Query to fetch user's information (first name, last name, profile picture)
                    $sql2 = "SELECT firstName, lastName, user_profile_image FROM `users` WHERE user_id = ?";
                    $stmt2 = $conn->prepare($sql2);     // Prepare statement
                    $stmt2->bind_param("i", $postedby);// Bind parameter
                    $stmt2->execute();                  // Execute the statement
                    $result2 = $stmt2->get_result();    // Get result from execution

                    if ($result2) {
                        $row2 = mysqli_fetch_assoc($result2);
                        $firstName = $row2['firstName'];  // Get user's first name
                        $lastName = $row2['lastName'];    // Get user's last name
                        // Set profile picture source based on availability
                        $profilePic = empty($row2['user_profile_image']) ? 'assets/profile/defaultPic.png' : 'assets/profile/'.$row2['user_profile_image'];

                        // Display the comment with user's profile picture
                        echo '<div class="media my-3">
                            <a class="d-flex" href="#">
                                <img src="'.$profilePic.'" class="user-pic" height="55px" alt="Profile Picture">
                            </a>
                            <div class="media-body ml-2">
                                <p class="font-weight-bold my-0">' . htmlspecialchars($firstName . ' ' . $lastName) . '</p>
                                ' . htmlspecialchars($comment) . '
                            </div>
                        </div>';
                    } else {
                        echo "Error: " . $stmt2->error;  // Display error if execution fails
                    }
                }

                // Display message if no comments are found
                if ($noresult) {
                    echo ' <div class="jumbotron my-3">
                    <h1 class="display-3">No Comments Found</h1>
                    <p class="lead">Be the first person to post a comment</p>
                    <hr class="my-2">
                    </div>';
                }
            } else {
                echo "Error: " . $stmt->error;      // Display error if execution fails
            }
            ?>
        </div>
    </div>

    <!-- JavaScript for toggling submenu visibility -->
    <script>
        const subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>
</body>
</html>
