<?php
session_start();
include("connect.php");

// Handle POST request and insert comment into the database
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $comment = $_POST['comment'];
    $user_id = $_POST['user_id'];
    $thread_id = $_POST['thread_id'];

    $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $comment, $thread_id, $user_id);
    $result = $stmt->execute();
    if ($result) {
        // Redirect to the same page to prevent resubmission
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Get thread information
$id = $_GET['threadid'];
$sql = "SELECT * FROM `threads` WHERE thread_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['thread_id'];
        $title = $row['thread_title'];
        $desc = $row['thread_desc'];
        $thuser_id = $row['thread_user_id'];

        $sql2 = "SELECT firstName, lastName FROM `users` WHERE user_id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $thuser_id);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        if ($result2) {
            $row2 = mysqli_fetch_assoc($result2);
            $postedby = $row2['firstName'] . ' ' . $row2['lastName'];
        } else {
            // Handle query error
            echo "Error: " . $stmt2->error;
        }
    }
} else {
    // Handle query error
    echo "Error: " . $stmt->error;
}

// Get user information if logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'");
    if ($query && mysqli_num_rows($query) > 0) {
        $userInfo = mysqli_fetch_assoc($query);
        $profilePicSrc = empty($userInfo['user_profile_image']) ? 'assets/profile/defaultPic.png' : 'assets/profile/'.$userInfo['user_profile_image'];
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<title>StreetSync</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
    #footer {
        min-height: 500px;
    }
    </style>    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/threads.css">
    <link rel="icon" type="image/x-icon" href="assets/images/StreetSyncLogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>
<div class="navbar">
    <ul>
        <div class="navlinks">
            <li style="float:left">
                <a href="HomePage.php">
                    <img src="assets/images/StreetSyncName.png" alt="StreetSyncName">
                </a>
            </li>
            <li>
                <a href="AboutUs.php">About Us</a>
            </li>
            <li>
                <a href="PrivacyPolicy.php">Privacy Policy</a>
            </li>
        </div>
        <div class="hero">
            <nav>
                <img src="<?php echo $profilePicSrc; ?>" class="user-pic" onclick="toggleMenu()">
                <div class="sub-menu-wrap" id="subMenu">
                    <div class="sub-menu">
                        <div class="user-info">
                            <img src="<?php echo $profilePicSrc; ?>" alt="Profile Picture">
                            <?php if (!empty($userInfo)) : ?>
                                <p><?php echo $userInfo['firstName'] . ' ' . $userInfo['lastName']; ?></p>
                            <?php endif; ?>
                        </div>
                        <hr>
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

    <div class="container my-3">
        <div class="jumbotron">
            <h1 class="display-3"><?php echo htmlspecialchars($title); ?></h1>
            <p class="lead"><?php echo htmlspecialchars($desc); ?></p>
            <hr class="my-2">
            <p>Posted by: <b><?php echo htmlspecialchars($postedby); ?></b></p>
        </div>
    </div>

    <div class="container my-3">
        <h2 class="my-3">Post a comment</h2>
        <?php
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
            echo ' <div class="jumbotron">
            <h1 class="display-4">You are not able to Post a Comment</h1>
            <p class="lead">If you want to Post a Comment...Login First</p>
            <hr class="my-2">
        </div>';
        }
        ?>
    </div>

    <div class="container my-4" id="footer">
        <h2 class="text-center">Your Comments</h2>
        <div class="container my-3">
            <?php
            $sql = "SELECT * FROM `comments` WHERE thread_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result) {
                $noresult = true;
                while ($row = mysqli_fetch_assoc($result)) {
                    $noresult = false;
                    $comment = $row['comment_content'];
                    $postedby = $row['comment_by'];

                    // Fetch user's information (first name, last name, profile picture)
                    $sql2 = "SELECT firstName, lastName, user_profile_image FROM `users` WHERE user_id = ?";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bind_param("i", $postedby);
                    $stmt2->execute();
                    $result2 = $stmt2->get_result();
                    if ($result2) {
                        $row2 = mysqli_fetch_assoc($result2);
                        $firstName = $row2['firstName'];
                        $lastName = $row2['lastName'];
                        $profilePic = empty($row2['user_profile_image']) ? 'assets/profile/defaultPic.png' : 'assets/profile/'.$row2['user_profile_image'];

                        // Display the comment with profile picture
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
                        // Handle query error
                        echo "Error: " . $stmt2->error;
                    }
                }
                if ($noresult) {
                    echo ' <div class="jumbotron my-3">
                    <h1 class="display-3">No Comments Found</h1>
                    <p class="lead">Be the first person to post a comment</p>
                    <hr class="my-2">
                    </div>';
                }
            } else {
                // Handle query error
                echo "Error: " . $stmt->error;
            }
            ?>
        </div>
    </div>

    <script>
        const subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>
</body>
</html>
