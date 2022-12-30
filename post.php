<?php
    include 'classes/Comments.php';
    $commentsDB = new Comments;
    
    $comments = $commentsDB->readComments();

?>

<style>
    .user--fullname { 
        text-decoration: none;
        color: black;
        font-weight:600;
    }
</style>

<div class="container post--div">
    <?php if ($_SESSION['is_loggedin']) : ?>
        <?php foreach ($posts as $post) : ?>
            <?php extract($post); ?>
            <div class="shadow-sm p-3 mb-5 bg-body rounded">
                <div class="form-floating info--div" style="display: flex; justify-content: space-between;">
                    <div>
                        <img src="profilepics/<?= $pfp ?>" class="shadow-sm user--pfp" alt="profile pic">
                        <a href="profile.php?id=<?= $uid ?>" style="text-decoration: none; color: black; font-weight:600;"><?= $fullname ?></a>
                    </div>
                    <div>
                        <?php if($uid === $_SESSION['user_id']) : ?>
                            <a href="deleteItems.php?post_id=<?=$id?>" style="text-decoration: none; color: black; font-weight:600;">Delete</a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (str_contains($content, "iframe=")) : ?>
                    <?php $url = explode("=",$content); $content1 = explode("iframe",$url[0])[0]?>
                    <div class="mb-3 post--content">
                        <form class="editContentForm">
                            <input type="text" class="form-control editContent" name="<?=$uid?>" value="<?= $content ?>" id="<?=$id?>" hidden>
                        </form>
                        <p class="content" id="<?=$id?>"><?= $content1 ?></p>
                    </div>
                <?php else: ?>
                    <div class="mb-3 post--content">
                        <form class="editContentForm">
                            <input type="text" class="form-control editContent" name="<?=$uid?>" value="<?= $content ?>" id="<?=$id?>" hidden>
                        </form>
                        <p class="content" id="<?=$id?>"><?= $content ?></p>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <?php if ($image) : ?>
                        <img class="post--pic" src="post-photos/<?= $post['image']; ?>" alt="">
                    <?php elseif ($video) : ?>
                        <video width="400" controls>
                            <source src="post-videos/<?= $video ?>" type="video/mp4">
                        </video>
                    <?php elseif (str_contains($content, "iframe=")) : ?>
                        <?php $url = explode("=",$content); $content = explode("iframe",$url[0])[0]?>
                        <iframe width="420" height="315" class="iframe"
                            src="<?=$url[1]?>">
                        </iframe>
                    <?php endif; ?>
                </div>

                <?php $allComments = []; ?>
                <?php foreach($comments as $comment) : ?>
                    <?php if($comment['post_id'] === $post['id']) : ?>
                        <?php $allComments[] = $comment; ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="post--stats mb-1">
                    <?php if (exists($likes, function ($e) {
                        return $e == $_SESSION['user_id'];
                    })) : ?>
                        <p><i class="ri-thumb-up-fill like" id="<?= $id ?>"></i> &nbsp; <span><?= count($likes) ?></span> </p>
                    <?php else : ?>
                        <p><i class="ri-thumb-up-line like" id="<?= $id ?>"></i> &nbsp; <span><?= count($likes) ?></span> </p>
                    <?php endif; ?>
                    <p><i class="ri-chat-1-line"></i> &nbsp; <span class="numOfComments" id="<?=$id?>"><?=count($allComments);?></span></p>
                </div>
                <form class="form-floating mb-4 comment">
                    <input type="text" class="form-control" id="<?= $id ?>" placeholder="Type comment">
                    <label for="comment">Type comment</label>
                </form>
                <div class="comments">
                <?php foreach($allComments as $comment) : ?>
                        <div class="form-floating info--div">
                            <img src="profilepics/<?= $comment['pfp'] ?>" class="shadow-sm user--pfp" alt="profile pic">
                            <div class="user--comment" style="display: flex; justify-content: space-between; width:100%;">
                                <div>
                                    <a href="profile.php?id=<?= $comment['id'] ?>" class="user--fullname"><?= $comment['fullname'] ?></a>
                                    <p><?= $comment['content'] ?></p>
                                </div>
                                <div>
                                    <!-- href="deleteItems.php?comment_id=$comment['comment_id']"  -->
                                    <a style="text-decoration: none; color: black; font-weight:600;" id="<?= $comment['comment_id']?>" class="delete--comment">Delete</a>
                                </div>
                            </div>
                        </div>
                <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <?php header('Location: login.php'); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let like = document.getElementsByClassName("like");
        let comment = document.getElementsByClassName("comment");
        let comments = document.getElementsByClassName("comments");
        let deleteComment = document.getElementsByClassName('delete--comment');
        let postContent = document.getElementsByClassName('post--content');
        let editContent = document.getElementsByClassName('editContent');
        let editForm = document.getElementsByClassName('editContentForm');
        let iframe = document.getElementsByClassName('iframe');

        for (let i = 0; i < like.length; i++) {
            like[i].addEventListener("click", (e) => {

                axios.get(`likePost.php?id=${e.target.id}`)
                    .then(data => {
                        let likes = e.target.parentNode.querySelector('span').innerText;
                        let x;
                        if (e.target.classList.contains("ri-thumb-up-line")) {
                            x = parseInt(likes) + 1;
                            e.target.classList.replace("ri-thumb-up-line", "ri-thumb-up-fill");
                        } else {
                            x = parseInt(likes) - 1;
                            e.target.classList.replace("ri-thumb-up-fill", "ri-thumb-up-line");
                        }
                        e.target.parentNode.querySelector('span').innerText = x;
                    }).catch(error => console.log(error));
            })
        }

        for(let i = 0; i < postContent.length; i++) {
            postContent[i].addEventListener("click", (e) => {
                let user_id = <?=$_SESSION['user_id']?>;

                if(user_id == editForm[i].querySelector("input").name) {
                    postContent[i].querySelector("p").style.display = "none";
                    editForm[i].querySelector("input").removeAttribute("hidden")
                }

                editForm[i].addEventListener("submit", (e) => {
                    e.preventDefault();
                    axios.post('editPost.php', {
                        post_id: e.target.querySelector("input").id,
                        new_content: e.target.querySelector("input").value,
                        uid: e.target.querySelector("input").name
                    })
                    .then(data => {
                        e.target.querySelector("input").setAttribute("hidden", true);
                        postContent[i].querySelector("p").style.display = "block";
                        let text = e.target.querySelector("input").value.split("iframe=");
                        iframe[i] ? iframe[i].src = text[1] : '';
                        postContent[i].querySelector("p").innerText = text[0];
                    }).catch(error => {
                        e.target.querySelector("input").setAttribute("hidden", true);
                        postContent[i].querySelector("p").style.display = "block";
                    });
                })
                
            })
        }

        for(let i = 0; i < deleteComment.length; i++) {
            deleteComment[i].addEventListener("click", (e) => {
                axios.post('deleteItems.php', {
                    comment_id: e.target.id
                })
                .then(data => {
                    let nr = e.target.parentNode.parentNode.parentNode.parentNode.parentNode.getElementsByClassName('post--stats')[0].getElementsByClassName('numOfComments')[0];
                    nr.innerText = parseInt(nr.innerText) - 1;
                    e.target.parentNode.parentNode.parentNode.remove();
                });
            })
        }

        for (let i = 0; i < comment.length; i++) {
            comment[i].addEventListener("submit", (e) => {
                e.preventDefault();
                let commentInput = e.target.querySelector('input');
                let value = commentInput.value;
                let nr = e.target.parentNode.getElementsByClassName('post--stats')[0].getElementsByClassName('numOfComments')[0];

                axios.post('comment.php', {
                    postId: commentInput.id,
                    content: commentInput.value
                })
                .then(data => {
                    divToAppend = document.createElement('div');
                    imageToAppend = document.createElement('img');
                    linkToAppend = document.createElement('a');
                    secondDivToAppend = document.createElement('div');
                    thirdDivToAppend = document.createElement('div');
                    contentToAppend = document.createElement('p');
                    fourthDivToAppend = document.createElement('div');
                    deleteLinkToAppend = document.createElement('a');

                    imageToAppend.classList.add("shadow-sm");
                    imageToAppend.classList.add("user--pfp");
                    imageToAppend.setAttribute("src", "profilepics/<?=$_SESSION['pfp']?>");
                    divToAppend.classList.add('form-floating');
                    // deleteLinkToAppend.setAttribute("href", `deleteItems.php?comment_id=${data.data}`);
                    deleteLinkToAppend.textContent = 'Delete';
                    deleteLinkToAppend.style = "text-decoration: none; color: black; font-weight:600;";
                    divToAppend.classList.add('info--div');
                    linkToAppend.classList.add('user--fullname');
                    secondDivToAppend.classList.add('user--comment');
                    secondDivToAppend.style = "display: flex; justify-content: space-between; width:100%;";
                    linkToAppend.setAttribute("href", "profile.php?id=<?=$_SESSION['user_id']?>");
                    linkToAppend.textContent = "<?= $_SESSION['fullname'] ?>";
                    contentToAppend.textContent = value;

                    deleteLinkToAppend.addEventListener("click", (e) => {
                        axios.post('deleteItems.php', {
                            comment_id: data.data
                        })
                        .then(data => {
                            let nr = e.target.parentNode.parentNode.parentNode.parentNode.parentNode.getElementsByClassName('post--stats')[0].getElementsByClassName('numOfComments')[0];
                            nr.innerText = parseInt(nr.innerText) - 1;
                            e.target.parentNode.parentNode.parentNode.remove();
                        });
                    })

                    fourthDivToAppend.appendChild(deleteLinkToAppend);
                    thirdDivToAppend.appendChild(linkToAppend);
                    thirdDivToAppend.appendChild(contentToAppend);
                    secondDivToAppend.appendChild(thirdDivToAppend);
                    secondDivToAppend.appendChild(fourthDivToAppend);
                    divToAppend.appendChild(imageToAppend);
                    divToAppend.appendChild(secondDivToAppend);
                    comments[i].appendChild(divToAppend);
                    nr.innerText = parseInt(nr.innerText) + 1;
                }).catch(error => console.log('error'));

                commentInput.value = '';
            });
        }
    </script>