<?php
session_start();
include("connect.php");

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
<!DOCTYPE html>
<html lang="en">
<head>
    
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/home_page.css">
    <link rel="icon" type="image/x-icon" href="assets/images/StreetSyncLogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>StreetSync</title>
</head>
<body>
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
    
    <!-- Threads -->

    <div class="container my-3">
        <h2 class="text-center">Browse Categories</h2>
        <div class="row my-4">
            <?php
            $sql = "SELECT * FROM `categories`";
            $result = mysqli_query($conn,$sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['category_id'];
                $catname = $row['category_name'];
                $catdesc = $row['category_description'];

                echo '<div class="col-md-4 my-3">
                <div class="card text-left">
                  <img class="card-img-top" src="assets/images/card-'.$id.'.jpg" alt="">
                  <div class="card-body">
                    <h4 class="card-title"><a href="threadlist.php?catid='.$id.'">'.$catname.'</a></h4>
                    <p class="card-text">'.substr($catdesc, 0 , 110).'</p>
                    <a href="threadlist.php?catid='.$id.'" class="btn btn-primary btn-lg">View Threads</a>
                  </div>
                </div>
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
