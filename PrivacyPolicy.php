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
        <h1>Privacy Policy</h1>
</div>

<div class="paragraph-grid">
            <div>
                <h5>INTRODUCTION</h5>
                <p>
                Welcome to StreetSync. Your privacy is important to us, and we are committed to protecting your personal information. This Privacy Policy outlines how we collect, use, and share information about you when you use our services.
                </p>
            </div>
            <div>
                <h5>INFORMATION WE COLLECT</h5>
                <p>
                We collect various types of information to provide and improve our services, including personal information such as your name, email address, phone number, address, and profile information; neighborhood information detailing your neighborhood and interactions within the community; non-personal information such as browser type, device information, IP address, pages visited, and time spent on the app; and information collected through cookies, pixels, and similar technologies.
                </p>
            </div>
            <div>
                <h5>HOW WE COLLECT INFORMATION</h5>
                <p>
                We use the information we collect for purposes such as providing and improving our services and personalizing your experience, facilitating communication and interaction within your neighborhood, enhancing the safety and security of our platform, sending you updates, offers, and promotions relevant to your interests, and conducting research and analysis to understand user behavior and trends.                    
                </p>
            </div>
            <div>
                <h5>SHARING YOUR INFORMATION</h5>
                <p>
                We may share your information with third-party service providers who perform services on our behalf, such as payment processing, data analysis, and customer support. Information shared within the community, such as posts and comments, may be visible to other members. We may also share your information to comply with legal obligations, such as responding to subpoenas or other legal processes, and in the event of a merger, acquisition, or sale of assets, your information may be transferred as part of the transaction.                    
                </p>
            </div>
            <div>
                <h5>YOUR CHOICES AND RIGHTS</h5>
                <p>
                You have certain rights regarding your personal information, including accessing and updating your profile information, adjusting your privacy settings to control the visibility of your information, opting out of marketing communications, and managing cookies and tracking preferences through your device settings.                    
                </p>
            </div>
            <div>
                <h5>DATA SECURITY</h5>
                <p>
                We implement reasonable security measures to protect your personal information from unauthorized access, use, or disclosure. However, no security system is completely secure, and we cannot guarantee the absolute security of your data.                    
                </p>
            </div>
            <div>
                <h5>INTERNATIONAL DATA TRANSFER</h5>
                <p>
                Your information may be transferred to and processed in countries other than your own. We ensure that such transfers comply with applicable data protection laws.                    
                </p>
            </div>
            <div>
                <h5>CHILDREN'S PRIVACY</h5>
                <p>
                StreetSync is not intended for children under the age of 13, and we do not knowingly collect personal information from children.                    
                </p>
            </div>
            <div>
                <h5>CHANGES TO THIS PRIVACY POLICY</h5>
                <p>
                We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on our app and website with the updated date.                    
                </p>
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
