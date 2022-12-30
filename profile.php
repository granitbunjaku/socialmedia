<?php
    include('readProfile.php');
?>



<head>
    <link href="assets/css/profile.css" rel="stylesheet">
</head>

<div class="profile">
    <img src="bannerpics/<?= $cover ?>" class="user--banner">
    <img src="profilepics/<?= $pfp ?>" class="shadow-sm user--pfp1" alt="profile pic">
</div>


<?php if ($_GET['id'] === $_SESSION['user_id']) : ?>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <label class="pfp--change shadow-sm">
            <i class="ri-camera-fill"></i>
            <input type="file" id="formFile" name="newpfp" onchange="this.form.submit()">
        </label>
    </form>

    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <label class="cover--change shadow-sm">
            <p>Update cover photo</p>
            <i class="ri-camera-fill"></i>
            <input type="file" id="formFile" name="newbanner" onchange="this.form.submit()">
        </label>
    </form>
<?php endif; ?>


<div class="content">
    <div class="informations">
        <div class="shadow-sm personal--information">
            <h4><?= $fullname ?></h4>
            <form action="profile.php?id=<?= $id ?>" method="post">
                <?php if ($_GET['id'] !== $_SESSION['user_id']) : ?>
                    <?php if(!count($friendStatus)): ?>
                        <button type="submit" class="btn btn-primary mb-2" name="addfriend">Add Friend</button>
                    <?php elseif(!$friendStatus[0]['accepted'] && $friendStatus[0]['user_id'] === $_SESSION['user_id']): ?>
                        <button class="btn btn-warning mb-2">Pending</button>
                    <?php elseif(!$friendStatus[0]['accepted'] && $friendStatus[0]['user_id'] !== $_SESSION['user_id']): ?>
                        <div class="buttons">
                            <a class="btn btn-success" href="acceptRequest.php?id=<?=$friendStatus[0]['user_id']?>">Accept</a>
                            <a class="btn btn-danger" href="rejectRequest.php?id=<?=$friendStatus[0]['user_id']?>">Reject</a>
                        </div>
                    <?php else: ?>
                        <a class="btn btn-danger mb-2" name="unfriend" href="rejectRequest.php?id=<?= $id ?>">Unfriend</a>
                    <?php endif;?>
                <?php endif; ?>
            </form>
            <h6><?=count($friends)?> friends</h6>
            <p>Gender : <?= $gender ?></p>
            <hr>
            <label for="bio" class="bio">Bio</label>
            <p id="bio"> <?= $bio ?></p>
        </div>
        <div class="shadow-sm photos--informations">
            <div class="photos--text">
                <h4>Photos</h4>
                <a href="allPhotos.php?id=<?=$_GET['id']?>">See all photos</a>
            </div>
            <?php $counter = 1; ?>
            <?php foreach($posts as $post) : ?>
                <?php if(strlen($post['image']) && $counter <= 6): ?>
                    <?php $counter++ ?>
                    <img src="post-photos/<?=$post['image']?>" alt="">
                <?php endif; ?>
                <?php if($counter > 6) break; ?>
            <?php endforeach; ?>
        </div>
        <div class="shadow-sm friends--informations">
            <div class="friends--text">
                <h4>Friends</h4>
            </div>
            <div class="profile--friends">
                <?php foreach($friends as $friend): ?>
                    <div class="friends">
                        <img src="profilepics/<?php echo isset($friend['pfp1']) ? $friend['pfp1'] : $friend['pfp2'] ?>" alt="">
                        <a href="profile.php?id=<?php echo isset($friend['id1']) ? $friend['id1'] : $friend['id2'] ?>"><?php echo isset($friend['fullname1']) ? $friend['fullname1'] : $friend['fullname2'] ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if($_GET['id'] === $_SESSION['user_id']) : ?>
            <div class="shadow-sm friend--requests">
                <div class="friends--text">
                    <h4>Friend Requests</h4>
                </div>
                <?php foreach($requests as $request): ?>
                    <div class="profile--friendsreq">
                        <div class="infos">
                            <img src="profilepics/<?=$request['pfp']?>" class="shadow-sm" alt="">
                            <p><?=$request['fullname'] ?></p>
                        </div>
                        <div class="buttons">
                            <a class="btn btn-success" href="acceptRequest.php?id=<?=$request['id']?>">Accept</a>
                            <a class="btn btn-danger" href="rejectRequest.php?id=<?=$request['id']?>">Reject</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="posts">
        <?php if ($_GET['id'] === $_SESSION['user_id']) : ?>
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
        <?php endif; ?>

        <?php include('post.php') ?>



    </div>
</div>