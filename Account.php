<?php 
session_start();
include("connect.php");

$profilePicSrc = 'assets/profile/defaultPic.png';

if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'");
    if($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $profilePicSrc = empty($row['image']) ? 'assets/profile/defaultPic.png' : 'assets/profile/'.$row['image'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/profile_page.css">
    <link rel="icon" type="image/x-icon" href="assets/images/StreetSyncLogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>StreetSync</title>
</head>
<body>
    <!-- NAVBAR -->
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
                            <?php 
                            if(isset($_SESSION['email'])) {
                                $email = $_SESSION['email'];
                                $query = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'");
                                if($query && mysqli_num_rows($query) > 0) {
                                    $row = mysqli_fetch_assoc($query);
                                    echo '<img src="' . ($row['image'] ? 'assets/profile/' . $row['image'] : 'assets/profile/defaultPic.png') . '" alt="Profile Picture">';
                                } else {
                                    echo '<img src="assets/profile/defaultPic.png" alt="Profile Picture">';
                                }
                            } else {
                                echo '<img src="assets/profile/defaultPic.png" alt="Profile Picture">';
                            }
                            ?>

                            <?php 
                            if(isset($_SESSION['email'])){
                                $email = $_SESSION['email'];
                                $query = mysqli_query($conn, "SELECT users.* FROM `users` WHERE users.email='$email'");
                                while($row = mysqli_fetch_array($query)){
                                    echo $row['fullname'];
                                }
                            }
                            ?>
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
    <script>
        const subMenu = document.getElementById("subMenu");

        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>

    <!-- Profile page -->

    <div class="header__wrapper">
        <header></header>
        <div class="cols__container">
            <div class="left__col">
                <div class="img__container">
                    <?php 
                    if(isset($_SESSION['email'])) {
                        $email = $_SESSION['email'];
                        $query = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'");
                        if($query && mysqli_num_rows($query) > 0) {
                            $row = mysqli_fetch_assoc($query);
                            echo '<img src="' . ($row['image'] ? 'assets/profile/' . $row['image'] : 'assets/profile/defaultPic.png') . '" alt="Profile Picture">';
                        } else {
                            echo '<img src="assets/profile/defaultPic.png" alt="Profile Picture">';
                        }
                    } else {
                        echo '<img src="assets/profile/defaultPic.png" alt="Profile Picture">';
                    }
                    ?>
                </div>
                <h2>
                    <?php 
                    if(isset($_SESSION['email'])){
                        $email = $_SESSION['email'];
                        $query = mysqli_query($conn, "SELECT users.* FROM `users` WHERE users.email='$email'");
                        while($row = mysqli_fetch_array($query)){
                            echo $row['fullname'];
                        }
                    }
                    ?>
                </h2>
                <p>
                    <?php 
                    if(isset($_SESSION['email'])){
                        $email = $_SESSION['email'];
                        $query = mysqli_query($conn, "SELECT users.* FROM `users` WHERE users.email='$email'");
                        while($row = mysqli_fetch_array($query)){
                            echo $row['email'];
                        }
                    }
                    ?>
                </p>
                <!---->
                <button id="openModal">Edit Profile</button>
                <div class="modal" id="modal">
                    <div class="modal-inner">
                        <h2> Edit Profile</h2>
                        <p>
                            Lorem ipsum
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
</body>
</html>
