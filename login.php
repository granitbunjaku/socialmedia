<?php
include 'includes/header.php';

$crud = new CRUD;

$errors = [];

if ($_POST) {
    if (isset($_POST['login-button'])) {

        if (strlen($_POST['email']) < 11) {
            $errors[] = "Too short! Please enter a valid email!";
        }

        if (strlen($_POST['password']) < 8) {
            $errors[] = "Too short! Please enter a valid password!";
        }

        $user = $crud->read("users", ['email' => $_POST['email']], 1);

        if($user) extract($user[0]);

        if (!count($errors) && is_array($user) && count($user) > 0) {
            if (password_verify($_POST['password'], $password)) {
                $_SESSION['fullname'] = $fullname;
                $_SESSION['is_loggedin'] = true;
                $_SESSION['user_id'] = $id;
                $_SESSION['pfp'] = $pfp;
                header('Location: index.php');
            } else {
                $errors[] = "Wrong password!";
            }
        } else {
            $errors[] = "Invalid user!";
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
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="password">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Remind me</label>
        </div>
        <div class="mb-3 form-check" style="padding:0px;">
            Don't have an account yet? <a href="signup.php">Sign up</a>
        </div>
        <button type="submit" class="btn btn-primary" name="login-button">Log in</button>
    </form>
</div>