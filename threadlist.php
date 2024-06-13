<?php 
session_start();
include("connect.php");

$id = $_GET['catid'];
$sql = "SELECT * FROM `categories` WHERE category_id = '$id'";
$result = mysqli_query($conn,$sql);
while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['category_id'];
    $catname = $row['category_name'];
    $catdesc = $row['category_description'];
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $user_id = $_POST['user_id'];

    $sql = "INSERT INTO `threads` (`thread_title`, `thread_desc`, `thread_cat_id`, `thread_user_id`) VALUES ('$title', '$desc', '$id', '$user_id')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
    }
    header("Location: ".$_SERVER['REQUEST_URI']);
    exit();
}

$profilePicSrc = 'assets/profile/defaultPic.png';
$userInfo = [];

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
    <link rel="stylesheet" href="style/home_page.css">
    <link rel="icon" type="image/x-icon" href="assets/images/StreetSyncLogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>
    <?php
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    ?>

<div class="navbar">
    <ul>
        <li style="float:left">
            <a href="HomePage.php">
                <img src="assets/images/StreetSyncName.png" alt="StreetSyncName">
            </a>
        </li>
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
            <h1 class="display-3"><?php echo $catname; ?></h1>
            <p class="lead"><?php echo $catdesc; ?></p>
            <hr class="my-2">
            <p>If you want to know more about this thread, click on this button</p>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="index.php" role="button">More Info</a>
            </p>
        </div>
    </div>
    <div class="container my-3">
        <h2 class="my-3">Start a Discussion</h2>
        <?php
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            echo '<form action="'.$_SERVER["REQUEST_URI"].'" method="post">
            <div class="form-group">
                <label for="">Problem Subject</label>
                <input type="text" class="form-control" name="title" id="" aria-describedby="helpId" placeholder="">
                <small>Write a short and crisp subject</small>
                </div>
                <input type="hidden" name="user_id" value="'.$_SESSION["user_id"].'">
                <div class="form-group">
                <label for="">Your Concern</label>
                <textarea name="desc" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Post</button>
        </form>';
        } else {
            echo '<div class="jumbotron">
            <h1 class="display-4">You are not able to start a Session</h1>
            <p class="lead">If you want to start a Session...Login First</p>
            <hr class="my-2">
        </div>';
        }
        ?>
    </div>

    <div class="container my-4" id="footer">
    <h2 class="text-center">Posts</h2>
    <div class="container-my-3">
        <?php
        $sql = "SELECT * FROM `threads` WHERE thread_cat_id = '$id' ORDER BY Date DESC";
        $result = mysqli_query($conn, $sql);
        $noresult = true;
        while ($row = mysqli_fetch_assoc($result)) {
            $noresult = false;
            $thid = $row['thread_id'];
            $thtitle = $row['thread_title'];
            $thdesc = $row['thread_desc'];
            $thuserid = $row['thread_user_id'];

            $sql2 = "SELECT firstName FROM `users` WHERE user_id = '$thuserid'";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $name = $row2['firstName'];

            echo '<div class="media my-3">
                <a class="d-flex" href="#">
                    <img src="profile/defaultPic.png" height="55px" alt="">
                </a>
                <div class="media-body ml-2 ">
                    <h5 class="my-0"><a class="text-dark" href="threads.php?threadid='. $thid.'">'.$thtitle.'</a></h5>
                    '.$thdesc.'
                </div>
                Asked By: <p class="font-weight-bold"> '.$name.'</p>
            </div>';
        }
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

<script>
        const subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
</script>

</body>
</html>
