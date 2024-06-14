<?php
session_start();
include("connect.php");

$profilePicSrc = 'assets/profile/defaultPic.png';
$profileBackground = 'assets/backgrounds/defaultBackground.jpg';
$userInfo = [];

if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM `users` WHERE email='$email'");
    if ($query && mysqli_num_rows($query) > 0) {
        $userInfo = mysqli_fetch_assoc($query);
        $profilePicSrc = empty($userInfo['user_profile_image']) ? 'assets/profile/defaultPic.png' : 'assets/profile/'.$userInfo['user_profile_image'];
       /* $profileBackground = empty($userInfo['user_profile_background']) ? 'assets/backgrounds/defaultBackground.jpg' : 'assets/backgrounds/'.$userInfo['user_profile_background'];*/
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
                  <p><?php echo $userInfo['firstName'] . ' ' . $userInfo['lastName']; ?></p>
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

  <!-- Profile page -->
  <div class="header__wrapper" style="background-image: url('<?php echo $profileBackground; ?>');">
    <header></header>
    <div class="cols__container">
      <div class="left__col">
        <div class="img__container">
          <img src="<?php echo $profilePicSrc; ?>" alt="Profile Picture">
        </div>
        <h2>
          <?php echo !empty($userInfo) ? $userInfo['firstName'] . ' ' . $userInfo['lastName'] : ''; ?>
        </h2>
        <p>
          <?php echo !empty($userInfo) ? $userInfo['email'] : ''; ?>
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
                <label for="profileImage">Choose image</label>
                <button type="submit" id="changeImage">Change image</button>
              </form>
            </div>
            <!--
            <div class="changeBackground">
              <form method="POST" action="change_background.php" enctype="multipart/form-data">
                <input type="file" name="profile_background" id="profileBackground" accept="image/*" required>
                <label for="profileBackground">Change background</label>
                <button type="submit" id="changeBackground">Submit</button>
              </form>
            </div>
  -->
            </p>
            <button id="closeModal">Close</button>
          </div>
        </div>
      </div>
      <div class="right__col">
      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const fileInput = document.getElementById('profileImage');
      const fileLabel = document.querySelector('label[for="profileImage"]');

      fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
          fileLabel.textContent = fileInput.files[0].name;
        } else {
          fileLabel.textContent = 'Change image';
        }
      });
 /*
      const backgroundInput = document.getElementById('profileBackground');
      const backgroundLabel = document.querySelector('label[for="profileBackground"]');

      backgroundInput.addEventListener('change', function() {
        if (backgroundInput.files.length > 0) {
          backgroundLabel.textContent = backgroundInput.files[0].name;
        } else {
          backgroundLabel.textContent = 'Change background';
        }
      });
      */
    });
  </script>
</body>
</html>
