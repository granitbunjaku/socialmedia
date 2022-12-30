<?php
include('includes/header.php');

$user = $crud->read("users", ['id' => $_SESSION['user_id']]);

extract($user[0]);

$errors = [];

$success = [];

if ($_POST) {
    if (isset($_POST['update'])) {
        if (strlen($_POST['fullname'] > 0)) {
            if (strlen($_POST['fullname']) > 5) {
                if($_POST['fullname'] !== $fullname){
                    if ($crud->update("users", ['fullname' => $_POST['fullname']], ['column' => 'id', 'value' => $_SESSION['user_id']])) {
                        $_SESSION['fullname'] = $_POST['fullname'];
                        $success[] = "You updated your username successfully";
                    }
                } else {
                    $errors[] = "Your fullname shouldn't be the same as the recent!";
                }
            } else {
                $errors[] = "Fullname should be longer than 5 characters!";
            }
        }

        if (strlen($_POST['email'] > 0)) {
            if($_POST['email'] !== $email){
                if ($crud->update("users", ['email' => $_POST['email']], ['column' => 'id', 'value' => $_SESSION['user_id']])) {
                    $success[] = "You updated your username successfully";
                }
            } else {
                $errors[] = "Your email shouldn't be the same as the recent!";
            }
        }

        if ($_POST['bio'] !== $bio) {
            if ($crud->update("users", ['bio' => $_POST['bio']], ['column' => 'id', 'value' => $_SESSION['user_id']])) {
                $success[] = "You updated your bio successfully";
            }
        } else {
            $errors[] = "Your bio shouldn't be the same as the recent!";
        }
    }

    $user = $crud->read("users", ['id' => $_SESSION['user_id']]);

    extract($user[0]);

    if (isset($_POST['changepassword'])) {

        if (strlen($_POST['opassword']) === 0) {
            $errors[] = "Old password field shouldn't be empty!";
        }

        if (strlen($_POST['npassword']) === 0 || strlen($_POST['npassword']) < 8) {
            $errors[] = "New password field should be 8+ characters long!";
        }

        if ($_POST['rpassword'] !== $_POST['npassword']) {
            $errors[] = "Repeat password and new password field should be the same!";
        }

        $res = password_verify($_POST['opassword'], $password);

        if (count($errors) === 0) {
            if ($res) {
                $npassword = password_hash($_POST['npassword'], PASSWORD_BCRYPT);
                if ($crud->update("users", ['password' => $npassword], ['column' => 'id', 'value' => $_SESSION['user_id']])) {
                    $success[] = "You changed your password successfully";
                }
            } else {
                $errors[] = "Your old password is incorrect!";
            }
        }
    }
}
?>

<form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
    <?php if ($errors) : ?>
        <?php foreach ($errors as $error) : ?>
            <div class="mb-3">
                <p class="alert alert-danger"><?= $error ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($success) : ?>
        <?php foreach ($success as $s) : ?>
            <div class="mb-3">
                <p class="alert alert-success"><?= $s ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['is_loggedin'])) : ?>

        <div class="container" style="margin-top: 30px;">
            <label for="inputFullname" class="form-label">Full Name</label>
            <input type="text" id="inputFullname" class="form-control" name="fullname" value="<?=$fullname?>">
        </div>
        <div class="container" style="margin-top: 20px;">
            <label for="inputEmail" class="form-label">Email Address</label>
            <input type="email" id="inputEmail" class="form-control" name="email" value="<?=$email?>">
        </div>
        <div class="container" style="margin-top: 20px;">
            <label for="inputBio" class="form-label">Bio</label>
            <input type="text" id="inputBio" class="form-control" name="bio" value="<?=$bio?>">
        </div>
        <div class="container" style="margin-top: 20px;">
            <button type="submit" class="form-control btn btn-primary" name="update">Update</button>
        </div>

        <div class="container" style="margin-top: 30px;">
            <label for="inputPassword" class="form-label">Old Password</label>
            <input type="text" id="inputPassword" class="form-control" name="opassword">
        </div>
        <div class="container" style="margin-top: 20px;">
            <label for="inputPassword1" class="form-label">New Password</label>
            <input type="text" id="inputPassword1" class="form-control" name="npassword">
        </div>
        <div class="container" style="margin-top: 20px;">
            <label for="inputPassword2" class="form-label">Repeat Password</label>
            <input type="text" id="inputPassword2" class="form-control" name="rpassword">
        </div>
        <div class="container" style="margin-top: 20px;">
            <button type="submit" class="form-control btn btn-primary" name="changepassword">Change Password</button>
        </div>
</form>

<?php else : header('Location: login.php') ?>

<?php endif; ?>