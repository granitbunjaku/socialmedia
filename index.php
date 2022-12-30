<head>
    <link href="assets/css/index.css" rel="stylesheet">
</head>

<?php
include 'includes/header.php';
include('createPost.php');
include 'classes/Posts.php';

$_SESSION['page'] = "home";

$postDB = new Posts;
$posts = $postDB->readPosts();

?>

<?php if (isset($_SESSION['is_loggedin'])) : ?>
    <div class="container post--div">
        <form class="shadow-sm p-3 mb-5 bg-body rounded" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <?php if ($errors) : ?>
                <?php foreach ($errors as $error) : ?>
                    <div class="mb-3">
                        <p class="alert alert-danger"><?= $error ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="form-floating">
                <textarea class="form-control post--textarea" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 100px;" name="content"></textarea>
                <label for="floatingTextarea2">What's on your mind?</label>
            </div>
            <div class="mb-3 post--file">
                <input class="form-control" type="file" id="formFile" name="post-file">
            </div>
            <div class="post--button">
                <button type="submit" class="form-control btn btn-primary" name="post-button"><i class="ri-send-plane-fill"></i></button>
            </div>
        </form>

        <?php include 'post.php'; ?>
    </div>
<?php else : header('Location: login.php') ?>

<?php endif; ?>