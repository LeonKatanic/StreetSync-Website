<?php 
session_start();
include("connect.php");

$profilePicSrc = 'assets/profile/defaultPic.png';
$user_name = '';
$user_surname = '';

if (isset($_SESSION['user_email'])) {
    $user_email = mysqli_real_escape_string($conn, $_SESSION['user_email']);
    $query = mysqli_query($conn, "SELECT * FROM `users` WHERE user_email='$user_email'");
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $profilePicSrc = empty($row['image']) ? 'assets/profile/defaultPic.png' : 'assets/profile/' . $row['image'];
        $user_name = $row['user_name'];
        $user_surname = $row['user_surname'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
                    <img src="<?php echo htmlspecialchars($profilePicSrc); ?>" class="user-pic" onclick="toggleMenu()">
                    <div class="sub-menu-wrap" id="subMenu">
                        <div class="sub-menu">
                            <div class="user-info">
                                <img src="<?php echo htmlspecialchars($profilePicSrc); ?>" alt="Profile Picture">
                                <?php 
                                if (isset($_SESSION['user_email'])) {
                                    echo htmlspecialchars($user_name . ' ' . $user_surname);
                                }
                                ?>
                            </div>
                            <hr>
                            <a href="Account.php" class="sub-menu-link">
                                <i class="bi bi-person-fill"></i>
                                <p>Profile</p>
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
    <!--SideBar-->
    <div class="wrapper">
        <div class="sidebar">
            <ul>
                <li><a href="#"><i class="bi bi-house"></i>Home</a></li>
                <li><a href="#"><i class="bi bi-megaphone"></i>Announcments</a></li>
                <li><a href="#"><i class="bi bi-calendar-event"></i>Events</a></li>
                <li><a href="#"><i class="bi bi-shop-window"></i>Markteplace</a></li>
                <li><a href="#"><i class="bi bi-pencil"></i>Reviews</a></li>
                <li><a href="#"><i class="bi bi-hammer"></i>Jobs</a></li>
            </ul>
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
