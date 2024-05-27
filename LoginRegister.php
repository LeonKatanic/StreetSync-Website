<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <title>StreetSync</title>
  <link rel="stylesheet" href="style/login_register.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<body>
  <div class="container" id="signUp" style="display:none;">
    <form method="post" action="register.php">
      <div class="title">Register</div>
      <div class="input-group">
        <input type="text" placeholder="Enter Your Full Name" name="fullName" id="fullName" required />
        <div class="underline"></div>
      </div>
      <div class="input-group">
        <input type="text" placeholder="Enter Your Username" name="userName" id="userName" required />
        <div class="underline"></div>
      </div>
      <div class="input-group">
        <input type="email" placeholder="Enter Your Email" name="email" id="email" required />
        <div class="underline"></div>
      </div>
      <div class="input-group">
        <input type="password" placeholder="Enter Your Password" id="regPassword" name="password" required />
        <div class="checkbox-container">
          <input type="checkbox" id="showRegPassword" onclick="togglePasswordVisibility('regPassword', 'showRegPassword')" />
        </div>
        <div class="underline"></div>
      </div>
      <div class="input-group button">
        <input type="submit" class="btn" name="signUp" value="Continue" />
      </div>
    </form>
    <div class="option">Already have an account?</div>
    <div class="optionBtn">
      <a id="signInButton">Login</a>
    </div>
  </div>

  <div class="container" id="signIn">
    <form method="post" action="register.php">
      <div class="title">Login</div>
      <div class="input-group">
        <input type="email" placeholder="Enter Your Email" name="email" id="email" required />
        <div class="underline"></div>
      </div>
      <div class="input-group">
        <input type="password" placeholder="Enter Your Password" id="loginPassword" name="password" required />
        <div class="checkbox-container">
          <input type="checkbox" id="showLoginPassword" onclick="togglePasswordVisibility('loginPassword', 'showLoginPassword')" />
        </div>
        <div class="underline"></div>
      </div>
      <div class="input-group button">
        <input type="submit" class="btn" name="signIn" value="Continue" />
      </div>
    </form>
    <div class="option">Don't have an account?</div>
    <div class="optionBtn">
      <a id="signUpButton">Register</a>
    </div>
  </div>

  <script>
    function togglePasswordVisibility(passwordFieldId, checkboxId) {
      const passwordField = document.getElementById(passwordFieldId);
      const checkbox = document.getElementById(checkboxId);
      if (checkbox.checked) {
        passwordField.type = 'text';
      } else {
        passwordField.type = 'password';
      }
    }
  </script>
  <script src="scripts/loginRegister.js"></script>
</body>
</html>
