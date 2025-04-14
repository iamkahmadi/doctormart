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

$errors = array();

if ($_POST) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $mobile_no = $_POST['mobile_no'];
    $shop_tel_no = $_POST['shop_tel_no'];
    $address = $_POST['address'];

    if (empty($username) || empty($email) || empty($password) || empty($address)) {
        if ($username == "") {
            $errors[] = "Username is required";
        }
        if ($email == "") {
            $errors[] = "Email is required";
        }
        if ($password == "") {
            $errors[] = "Password is required";
        }
        if ($address == "") {
            $errors[] = "Address is required";
        }
    } else {
        // Validate at least one of mobile_no or shop_tel_no must be filled
        if (empty($mobile_no) && empty($shop_tel_no)) {
            $errors[] = "At least one of Mobile Number or Shop Telephone Number is required.";
        }

        // Check if email already exists
        $sqlEmailCheck = "SELECT * FROM users WHERE email='$email'";
        $resultEmailCheck = $connect->query($sqlEmailCheck);

        if ($resultEmailCheck->num_rows > 0) {
            echo showErrorPopup("Email already exists.");
        } else {
            // Insert user into database
            $hashedPassword = md5($password); // Use a stronger hashing algorithm in production
            $sqlInsertUser = "INSERT INTO users (username, email, password, mobile_no, shop_tel_no, address, Role) VALUES ('$username', '$email', '$hashedPassword', '$mobile_no', '$shop_tel_no', '$address', 'Client')";

            if ($connect->query($sqlInsertUser) === TRUE) {
                echo showSuccessPopup("Registration successful! You can now log in.");
            } else {
                echo showErrorPopup("Error during registration.");
            }
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
        <p><a href='register.php'><button class='button button--error' data-for='js_error-popup'>Close</button></a></p>
      </div>
    </div>";
}

function showSuccessPopup($message)
{
    return "
    <div class='popup popup--icon -success js_success-popup popup--visible'>
      <div class='popup__background'></div>
      <div class='popup__content'>
        <h3 class='popup__content__title'>Success</h3>
        <p>$message</p>
        <p><a href='login.php'><button class='button button--success' data-for='js_success-popup'>Go to Login</button></a></p>
      </div>
    </div>";
}
?>

<div id="main-wrapper">
    <div class="unix-login">
        <div class="container-fluid" style="background-image: url('assets/uploadImage/Logo/banner3.jpg'); background-color: #ffffff; background-size:cover">
            <div class="row">
                <div class="col-md-4">
                    <div class="login-content">
                        <div class="login-form">
                            <center><img src="./assets/uploadImage/Logo/logo.png" style="width: 300px;"></center><br>
                            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" id="registerForm" class="row" autocomplete="off">
                                <div class="form-group col-md-12">
                                    <label>Username</label>
                                    <input type="text" name="username" id="username" class="form-control" placeholder="Username" required="">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Invalid email address" required="">
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Password</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="">
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i class="fa fa-eye" id="eyeIcon"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Mobile Number</label>
                                    <input type="text" name="mobile_no" id="mobile_no" class="form-control" placeholder="Mobile Number">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Shop Telephone Number</label>
                                    <input type="text" name="shop_tel_no" id="shop_tel_no" class="form-control" placeholder="Shop Telephone Number">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Address</label>
                                    <textarea name="address" id="address" class="form-control" placeholder="Address" required=""></textarea>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-md-12">
                                    <button style="background-color: #102b49; border-radius: 50px;" type="submit" name="register" class='f-w-600 text-white btn btn-flat m-b-10 m-t-10'>Register</button>
                                </div>
                                <div class="col-md-12">
                                    <a href='login.php' style="background-color: #102b49; border-radius: 50px;" name="register" class='f-w-600 text-white btn btn-flat m-b-10 m-t-10'>Login here</a>
                                </div>

                            </form>

                        </div> <!-- End of login-form -->
                    </div> <!-- End of login-content -->
                </div> <!-- End of col-md-4 -->
            </div> <!-- End of row -->
        </div> <!-- End of container-fluid -->
    </div> <!-- End of unix-login -->

</div>

<script src="./assets/js/lib/jquery/jquery.min.js"></script>
<script src="./assets/js/lib/bootstrap/js/popper.min.js"></script>
<script src="./assets/js/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="./assets/js/jquery.slimscroll.js"></script>
<script src="./assets/js/sidebarmenu.js"></script>
<script src="./assets/js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="./assets/js/custom.min.js"></script>

<script>
    // JavaScript function to enforce the mobile_no or shop_tel_no validation
    document.getElementById("registerForm").onsubmit = function() {
        var mobile_no = document.getElementById("mobile_no").value;
        var shop_tel_no = document.getElementById("shop_tel_no").value;

        if (mobile_no === "" && shop_tel_no === "") {
            alert("At least one of Mobile Number or Shop Telephone Number is required.");
            return false;
        }
        return true;
    }
</script>

<script>
    // JavaScript to toggle password visibility
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    togglePassword.addEventListener("click", function () {
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