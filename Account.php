<?php
session_start();
include("connect.php");

$profilePicSrc = 'assets/profile/defaultPic.png';
$userInfo = [];

if (isset($_SESSION['user_email'])) {
    $user_email = $_SESSION['user_email'];
    $query = mysqli_query($conn, "SELECT * FROM `users` WHERE user_email='$user_email'");
    if ($query && mysqli_num_rows($query) > 0) {
        $userInfo = mysqli_fetch_assoc($query);
        $profilePicSrc = empty($userInfo['user_profile_image']) ? 'assets/profile/defaultPic.png' : 'assets/profile/' . $userInfo['user_profile_image'];
    }
}
if (!isset($_SESSION['user_id'])) {
    header("Location: register.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/profile_page.css">
    <script defer src="scripts/editProfile.js"></script>
    <link rel="icon" type="image/x-icon" href="assets/images/StreetSyncLogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>StreetSync</title>
</head>

<body>
    <!-- NAVBAR -->
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
                                    <p><?php echo $userInfo['user_name'] . ' ' . $userInfo['user_surname']; ?></p>
                                <?php endif; ?>
                            </div>
                            <hr>
                            <a href="HomePage.php" class="sub-menu-link">
                                <i class="bi bi-house"></i>
                                <p>Home page</p>
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
    <script>
        const subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>

    <!--Sidebar-->

    <div class="wrapper">
        <div class="sidebar">
            <ul>
                <li><a href="#"><i class="bi bi-house"></i> Home</a></li>
                <li><a href="#"><i class="bi bi-megaphone"></i> Announcements</a></li>
                <li><a href="#"><i class="bi bi-calendar-event"></i> Events</a></li>
                <li><a href="#"><i class="bi bi-shop-window"></i> Marketplace</a></li>
                <li><a href="#"><i class="bi bi-pencil"></i> Reviews</a></li>
                <li><a href="#"><i class="bi bi-hammer"></i> Jobs</a></li>
            </ul>
        </div>
    </div>

    <!-- Profile page -->

    <div class="header__wrapper">
        <header></header>
        <div class="cols__container">
            <div class="left__col">
                <div class="img__container">
                    <img src="<?php echo $profilePicSrc; ?>" alt="Profile Picture">
                </div>
                <h2>
                    <?php echo !empty($userInfo) ? $userInfo['user_name'] . ' ' . $userInfo['user_surname'] : ''; ?>
                </h2>
                <p>
                    <?php echo !empty($userInfo) ? $userInfo['user_email'] : ''; ?>
                </p>
                <button id="openModal">Edit Profile</button>
                <div class="modal" id="modal">
                    <div class="modal-inner">
                        <h2>Edit Profile</h2>
                        <p>
                        <div class="changeName">
                            <form method="POST" action="change_name.php">
                                <input type="text" name="first_name" id="chngFirstName" placeholder="First name" required>
                                <input type="text" name="last_name" id="chngLastName" placeholder="Last name" required>
                                <button type="submit" id="changeName">Change name</button>
                            </form>
                        </div>
                        <div class="changeImage">
                            <form method="POST" action="change_image.php" enctype="multipart/form-data">
                                <input type="file" name="profile_image" id="profileImage" accept="image/*" required>
                                <button type="submit" id="changeImage">Change image</button>
                            </form>
                        </div>
                        <div class="deleteAccount">
                            <form id="deleteAccountForm" action="delete_account.php" method="post">
                                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                                <button type="button" onclick="confirmDelete()">Delete Account</button>
                            </form>
                        </div>
                        </p>
                        <button id="closeModal">Submit</button>
                    </div>
                </div>
            </div>
            <div class="right__col">
            </div>
        </div>
    </div>
    <script src="editProfile.js"></script>
    <script>
        function confirmDelete() {
            if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
                document.getElementById('deleteAccountForm').submit();
            }
        }
    </script>
</body>

</html>