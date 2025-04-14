<link rel="stylesheet" href="assets/css/popup_style.css">
<style>
  .footer1 {
    position: fixed;
    bottom: 0;
    width: 100%;
    color: #5c4ac7;
    text-align: center;
  }
</style>
<?php
include('./constant/layout/head.php');
include('./constant/connect.php');
session_start();

if (isset($_SESSION['userId'])) {
  // Redirect based on role
  if ($_SESSION['role'] === 'Admin') {
    header('location: dashboard.php'); // Redirect to admin dashboard
  } else {
    header('location: client_dashboard.php'); // Redirect to client dashboard
  }
  exit(); // Ensure no further code is executed after redirection
}

$errors = array();

if ($_POST) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (empty($email) || empty($password)) {
    if ($email == "") {
      $errors[] = "Email is required";
    }
    if ($password == "") {
      $errors[] = "Password is required";
    }
  } else {
    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $connect->query($sql);

    if ($result->num_rows == 1) {
      $password = md5($password); // Use a stronger hashing algorithm in production
      // Check email and password
      $mainSql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
      $mainResult = $connect->query($mainSql);

      if ($mainResult->num_rows == 1) {
        $value = $mainResult->fetch_assoc();
        $_SESSION['userId'] = $value['user_id'];
        $_SESSION['role'] = $value['Role']; // Store user role

        // Redirect based on role
        if ($_SESSION['role'] === 'Admin') {
          header('location: dashboard.php'); // Redirect to admin dashboard
        } else {
          header('location: client_dashboard.php'); // Redirect to client dashboard
        }
        exit(); // Ensure no further code is executed after redirection
      } else {
        echo showErrorPopup("Incorrect email/password combination");
      }
    } else {
      echo showErrorPopup("Email does not exist");
    }
  }
}

function showErrorPopup($message)
{
  return "
    <div class='popup popup--icon -error js_error-popup popup--visible'>
      <div class='popup__background'></div>
      <div class='popup__content'>
        <h3 class='popup__content__title'>Error</h3>
        <p>$message</p>
        <p><a href='login.php'><button class='button button--error' data-for='js_error-popup'>Close</button></a></p>
      </div>
    </div>";
}
?>

<div id="main-wrapper">
  <div class="unix-login">
    <div class="container-fluid" style="background-image: url('assets/uploadImage/Logo/banner3.jpg'); background-color: #ffffff; background-size:cover">
      <div class="row" style="justify-content:center; align-items:center;">
        <div class="col-md-4">
          <div class="login-content">
            <div class="login-form">
              <center><img src="./assets/uploadImage/Logo/logo.png" style="width: 300px;"></center><br>
              <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" autocomplete="off" id="loginForm" class="row">
                <div class="form-group col-md-12">
                  <label style="font-size:20px"><b>Email</b></label>
                  <input type="text" name="email" id="email" class="form-control" placeholder="Email" pattern="[^@\s]+@[^@\s]+\.[^@\s]+" title="Invalid email address" required="" style="border-radius: 10px;">
                </div>

                <div class="form-group col-md-12">
                  <label>Password</label>
                  <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" style="border-radius: 10px; border-top-right-radius: 0px; border-bottom-right-radius: 0px;" placeholder="Password" required="">
                    <div class="input-group-append">
                      <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <button style="background-color: #102b49; border-radius: 50px;" type="submit" name="login" class="f-w-600 text-white btn btn-flat m-b-10 m-t-20">Sign in</button>
                </div>
                <div class="col-md-12">
                  <a href='register.php' style="background-color: #102b49; border-radius: 50px;" name="register" class='f-w-600 text-white btn btn-flat m-b-10 m-t-10'>Register here</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



</div>

<script src="./assets/js/lib/jquery/jquery.min.js"></script>
<script src="./assets/js/lib/bootstrap/js/popper.min.js"></script>
<script src="./assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="./assets/js/jquery.slimscroll.js"></script>
<script src="./assets/js/sidebarmenu.js"></script>
<script src="./assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="./assets/js/custom.min.js"></script>

<script>
  // JavaScript to toggle password visibility
  const togglePassword = document.getElementById("togglePassword");
  const passwordField = document.getElementById("password");
  const eyeIcon = document.getElementById("eyeIcon");

  togglePassword.addEventListener("click", function() {
    // Toggle the type attribute using getAttribute() and setAttribute()
    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);

    // Toggle the eye icon (closed/opened)
    if (type === "password") {
      eyeIcon.classList.remove("fa-eye-slash");
      eyeIcon.classList.add("fa-eye");
    } else {
      eyeIcon.classList.remove("fa-eye");
      eyeIcon.classList.add("fa-eye-slash");
    }
  });
</script>

</body>

</html>