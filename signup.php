<?php
include 'includes/header.php';

$crud = new CRUD;

$errors = [];

if ($_POST) {
    if (isset($_POST['signup-button'])) {

        // validimi

        if (strlen($_POST['fullname']) < 5) {
            $errors[] = "Too short! Please enter longer name!";
        }

        if (strlen($_POST['email']) < 11) {
            $errors[] = "Too short! Please enter longer email!";
        }

        if (strlen($_POST['password']) < 8) {
            $errors[] = "Too short! Please enter longer password!";
        }

        if ($_POST['confirmpassword'] !== $_POST['password']) {
            $errors[] = "Type same password for both password fields!";
        }

        if ($_POST['gender'] == "") {
            $errors[] = "Please select a gender!";
        }

        // implementimi n'db

        if (!count($errors)) {
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $gender = $_POST['gender'];

            if ($crud->create('users', [
                'fullname' => $fullname,
                'email' => $email,
                'password' => $password,
                'bio' => '',
                'gender' => $gender
            ])) {
                header('Location: login.php');
            } else {
                $errors[] = "Something went wrong!";
            }
        }
    }
}
?>

<div class="container" style="margin-top:50px">
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
        <?php if ($errors) : ?>
            <?php foreach ($errors as $error) : ?>
                <div class="mb-3">
                    <p><?= $error ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        <div class="mb-3">
            <label for="fullname" class="form-label">Fullname</label>
            <input type="text" class="form-control" name="fullname">
        </div>
        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword" name="password">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="confirmpassword">
        </div>
        <div class="mb-3">
            <label class="form-check-label" for="gender">Gender</label>
            <select class="form-select" aria-label="Default select example" id="gender" name="gender">
                <option value="">Select your gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Remind me</label>
        </div>
        <div class="mb-3 form-check" style="padding:0px;">
            Already have an account? <a href="login.php">Log In</a>
        </div>
        <button type="submit" class="btn btn-primary" name="signup-button">Sign up</button>
    </form>
</div>