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
    <link rel="stylesheet" href="style/aboutus.css">
    <link rel="icon" type="image/x-icon" href="assets/images/StreetSyncLogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>StreetSync</title>
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

<div id="o_nama" class="o_nama">
        <h1>Welcome to StreetSync</h1>
</div>

<div class="paragraph">
            <p>At StreetSync, we believe in the power of community. 
                Our platform is designed to bring neighbors together, 
                fostering a sense of belonging and mutual support. 
                Whether you're new to the area or a longtime resident, 
                StreetSync makes it easy to connect with the people 
                who live around you.</p>
        </div>

<div class="paragraph-grid">
            <div>
                <h5>OUR MISSION</h5>
                <p>Our mission is simple: to create stronger, more 
                    connected neighborhoods. In a world where digital 
                    interactions often replace face-to-face conversations, 
                    we aim to bring back the essence of community spirit. 
                    StreetSync is a place where you can share local news, 
                    seek recommendations, organize events, and lend a 
                    helping hand to your neighbors.</p>
            </div>
            <div>
                <h5>WHAT WE DO</h5>
                <p>StreetSync offers a variety of features to enhance 
                    your neighborhood experience:
                    Announcments: Stay informed about local happenings, 
                    from garage sales to block parties. Recommendations: 
                    Find trusted local services and businesses based on your 
                    neighbors' experiences. Event Planning: Organize community 
                    events, from potlucks to cleanup drives, and invite your 
                    neighbors to join. Volunteering: Discover opportunities to 
                    give back to your community by connecting with local volunteer 
                    projects and initiatives. Marketplace: Buy, sell, and trade 
                    items with your neighbors in a trusted environment.</p>
            </div>
            <div>
                <h5>WHY CHOOSE STREETSYNC</h5>
                <p>Local Focus: Unlike other social networks, StreetSync is all 
                    about your neighborhood. Our platform is tailored to the specific 
                    needs and interests of local communities. Privacy and Security: 
                    We prioritize your privacy and the security of your information. 
                    Our platform is designed to ensure a safe and trusted environment 
                    for all users. User-Friendly: StreetSync is easy to use, with a 
                    clean interface and intuitive features that make connecting with 
                    your neighbors a breeze.</p>
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
