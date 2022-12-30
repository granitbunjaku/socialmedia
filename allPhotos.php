<?php 
    include('readProfile.php');
    
?>
    <style>
        .all-pics-videos {
            display: flex;
            flex-wrap: wrap;
        }

        .all-pics-videos img,
        .all-pics-videos video{
            width: 230px;
            height: 230px;
            margin-left: 10px;
        }
    </style>

    <head>
        <link href="assets/css/profile.css" rel="stylesheet">
    </head>

    <div class="profile">
        <img src="bannerpics/<?= $cover ?>" class="user--banner">
        <img src="profilepics/<?= $pfp ?>" class="shadow-sm user--pfp1" alt="profile pic">
    </div>

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
        </div>

        <div class="all-pics-videos">
            <?php foreach($posts as $post) : ?>
                <?php if(strlen($post['image'])): ?>
                    <img src="post-photos/<?=$post['image']?>">
                <?php endif; ?>
                <?php if(strlen($post['video'])): ?>
                    <video controls>
                        <source src="post-videos/<?= $post['video'] ?>" type="video/mp4">
                    </video>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>

    </div>