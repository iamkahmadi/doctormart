<?php
session_start();
include('./constant/connect.php');
include('./constant/layout/head.php');
include('./constant/layout/header.php');

if ($_SESSION['role'] === 'Admin') {
    include('./constant/layout/sidebar.php');
} else {
    include('./constant/layout/client_sidebar.php');
}

$userId = $_SESSION['userId'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the user is updating their profile information
    if (isset($_POST['update_profile'])) {
        // Update user details
        $username = $_POST['username'];
        $email = $_POST['email'];
        $mobile_no = $_POST['mobile_no'];
        $shop_tel_no = $_POST['shop_tel_no'];
        $address = $_POST['address'];

        $updateSql = "UPDATE users SET username='$username', email='$email', mobile_no='$mobile_no', shop_tel_no='$shop_tel_no', address='$address' WHERE user_id = $userId";
        if ($connect->query($updateSql)) {
            $successMsg = "Profile updated successfully!";
        } else {
            $errorMsg = "Error updating profile!";
        }
    }

    // Check if the user is updating their password
    if (isset($_POST['update_password'])) {
        // Update user password
        $old_password = md5($_POST['old_password']);
        $new_password = md5($_POST['new_password']);
        $confirm_password = md5($_POST['confirm_password']);

        // Check if the old password is correct
        $checkPasswordSql = "SELECT password FROM users WHERE user_id = $userId";
        $checkPasswordResult = $connect->query($checkPasswordSql);
        $checkPasswordData = $checkPasswordResult->fetch_assoc();

        if ($checkPasswordData['password'] == $old_password) {
            // Check if the new password and confirm password match
            if ($new_password == $confirm_password) {
                $updatePasswordSql = "UPDATE users SET password='$new_password' WHERE user_id = $userId";
                if ($connect->query($updatePasswordSql)) {
                    $passwordSuccessMsg = "Password updated successfully!";
                } else {
                    $passwordErrorMsg = "Error updating password!";
                }
            } else {
                $passwordErrorMsg = "New password and confirm password do not match!";
            }
        } else {
            $passwordErrorMsg = "Old password is incorrect!";
        }
    }
}

// Fetch user details
$userSql = "SELECT * FROM users WHERE user_id = $userId";
$userResult = $connect->query($userSql);
$userData = $userResult->fetch_assoc();

?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Profile Information</strong>
                    </div>
                    <div class="card-body">
                        <?php if (isset($successMsg)) echo "<div class='alert alert-success'>$successMsg</div>"; ?>
                        <?php if (isset($errorMsg)) echo "<div class='alert alert-danger'>$errorMsg</div>"; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo $userData['username']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $userData['email']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Mobile No</label>
                                <input type="text" name="mobile_no" class="form-control" value="<?php echo $userData['mobile_no']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Shop Tel No</label>
                                <input type="text" name="shop_tel_no" class="form-control" value="<?php echo $userData['shop_tel_no']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" class="form-control" required><?php echo $userData['address']; ?></textarea>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                        </form>
                        <hr>
                        <h4>Change Password</h4>
                        <?php if (isset($passwordSuccessMsg)) echo "<div class='alert alert-success'>$passwordSuccessMsg</div>"; ?>
                        <?php if (isset($passwordErrorMsg)) echo "<div class='alert alert-danger'>$passwordErrorMsg</div>"; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label>Old Password</label>
                                <input type="password" name="old_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="new_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                            <button type="submit" name="update_password" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('./constant/layout/footer.php'); ?>