<?php 
// Start the session to manage user sessions
session_start();

// Include the file that connects to the database
include("connect.php");

// Fetch category ID from the URL parameter
$id = $_GET['catid'];

// Query to fetch category details based on the category ID
$sql = "SELECT * FROM `categories` WHERE category_id = '$id'";
$result = mysqli_query($conn, $sql);

// Loop through the results and retrieve category information
while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['category_id'];          // Update category ID
    $catname = $row['category_name'];   // Category name
    $catdesc = $row['category_description'];  // Category description
    
    // Limit description to 100 characters and add "..." if longer
    $catdesc = strlen($catdesc) > 100 ? substr($catdesc, 0, 100) . "..." : $catdesc;
}

// Handle form submission when POST method is used
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title'];       // Get thread title from form
    $desc = $_POST['desc'];         // Get thread description from form
    $user_id = $_POST['user_id'];   // Get user ID from form

    // Insert new thread into the database
    $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`) 
            VALUES ('$title', '$desc', '$id', '$user_id')";
    $result = mysqli_query($conn, $sql);
    
    // Optionally handle success message or redirect
    if ($result) {
        // Redirect to the same page after successful submission
        header("Location: ".$_SERVER['REQUEST_URI']);
        exit();
    }
}

// Default profile picture source
$profilePicSrc = 'assets/profile/defaultPic.png';
$userInfo = []; // Initialize an empty array for user information

// Check if user session is active
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];    // Get user email from session
    
    // Query to fetch user information based on email
    $query = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'");
    
    // If query is successful and user exists
    if ($query && mysqli_num_rows($query) > 0) {
        // Fetch user data
        $userInfo = mysqli_fetch_assoc($query);
        
        // Set profile picture source based on user's profile image
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

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/StreetSyncLogo.png">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>
    <?php
    // Display any message stored in the session (if exists)
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']); // Clear the message after displaying
    }
    ?>

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

    <!-- Container for category details -->
    <div class="container my-3">
        <div class="jumbotron">
            <h1 class="display-3"><?php echo $catname; ?></h1> <!-- Display category name -->
            <p class="lead"><?php echo $catdesc; ?></p> <!-- Display truncated category description -->
            <hr class="my-2">
        </div>
    </div>

    <!-- Container for starting a discussion (form) -->
    <div class="container my-3">
        <h2 class="my-3">Start a Discussion</h2>
        <?php
        // Check if user is logged in to display the form
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<form action="'.$_SERVER["REQUEST_URI"].'" method="post">
            <div class="form-group">
                <label for="">Title</label>
                <input type="text" class="form-control" name="title" id="" aria-describedby="helpId" placeholder="">
                </div>
                <input type="hidden" name="user_id" value="'.$_SESSION["user_id"].'">
                <div class="form-group">
                <label for="">Subject</label>
                <textarea name="desc" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>';
        } else {
            // Display message if user is not logged in
            echo '<div class="jumbotron">
            <h1 class="display-4">You are not able to start a Session</h1>
            <p class="lead">If you want to start a Session...Login First</p>
            <hr class="my-2">
        </div>';
        }
        ?>
    </div>

    <!-- Container for displaying posts -->
    <div class="container my-4" id="footer">
        <h2 class="text-center">Posts</h2>
        <div class="container-my-3">
            <?php
            // Query to fetch threads associated with the current category
            $sql = "SELECT * FROM `threads` WHERE thread_cat_id = '$id' ORDER BY Date DESC";
            $result = mysqli_query($conn, $sql);
            $noresult = true;
            
            // Loop through fetched threads and display each one
            while ($row = mysqli_fetch_assoc($result)) {
                $noresult = false;
                $thid = $row['thread_id'];
                $thtitle = $row['thread_title'];
                $thdesc = $row['thread_desc'];
                $thuserid = $row['thread_user_id'];
                
                // Query to fetch user information for the thread creator
                $sql2 = "SELECT firstName, lastName, user_profile_image FROM `users` WHERE user_id = '$thuserid'";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                $name = $row2['firstName'];
                $surname = $row2['lastName'];
                $profileImage = empty($row2['user_profile_image']) ? 'assets/profile/defaultPic.png' : 'assets/profile/'.$row2['user_profile_image'];
                
                // Truncate thread description and add "..." if longer than 100 characters
                $displayDesc = strlen($thdesc) > 100 ? substr($thdesc, 0, 100) . "..." : $thdesc;

                // Display each thread with user information
                echo '<div class="media my-3">
                    <a class="d-flex" href="#">
                        <img src="'.$profileImage.'" class="user-pic" height="55px" alt="">
                    </a>
                    <div class="media-body ml-2">
                        <h5 class="my-0"><a class="text-dark" href="Threads.php?threadid='. $thid.'">'.$thtitle.'</a></h5>
                        '.$displayDesc.'
                    </div>
                    Posted By: <p class="font-weight-bold">'.$name.' '.$surname.'</p>
                </div>';
            }
            
            // Display message if no posts are found
            if ($noresult) {
                echo '<div class="jumbotron my-3">
                    <h1 class="display-3">No posts yet...</h1>
                    <p class="lead">Be the first person to post :D</p>
                    <hr class="my-2">
                </div>';
            }
            ?>
        </div>
    </div>

    <!-- JavaScript for toggling submenu -->
    <script>
        const subMenu = document.getElementById("subMenu");

        // Function to toggle the submenu visibility
        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>

</body>
</html>
