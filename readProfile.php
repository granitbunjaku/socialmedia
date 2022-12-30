<?php
    include 'includes/header.php';
    include 'classes/Friends.php';
    include 'classes/Posts.php';
    $_SESSION['page'] = "profile";
    
    $crud = new CRUD;
    $postDB = new Posts;
    $friendsDB = new Friends;
    
    include('createPost.php');
    
    if (isset($_GET['id'])) {
        if ($_GET['id'] === $_SESSION['user_id']) {
            $user = $crud->read("users", ['id' => $_SESSION['user_id']]);
            $requests = $friendsDB->readRequests($_SESSION['user_id']);
        } else {
            $user = $crud->read("users", ['id' => $_GET['id']]);
        }
    }
    
    extract($user[0]);
    
    $posts = $postDB->readPosts("u.id", $id);
    $friends = $friendsDB->readFriends($id);
    
    for($i = 0; $i<count($friends); $i++) {
        if($friends[$i]['id1'] === $id) {
            unset($friends[$i]['id1']);
            unset($friends[$i]['fullname1']);
            unset($friends[$i]['pfp1']);
        } else {
            unset($friends[$i]['id2']);
            unset($friends[$i]['fullname2']);
            unset($friends[$i]['pfp2']);
        }
    }
    
    $friendStatus = $friendsDB->isFriend($id, $_SESSION['user_id']);
    
    if (count($_FILES) > 0) {
    
        if ($_FILES["newbanner"]) {
            $bannerfile = time() . $_FILES['newbanner']['name'];
    
            if ($crud->update("users", [
                'cover' => $bannerfile
            ], ['column' => 'id', 'value' => $id])) {
                move_uploaded_file($_FILES['newbanner']['tmp_name'], 'bannerpics/' . $bannerfile);
                header('Location: profile.php?id=' . $_SESSION['user_id']);
            } else {
                echo "Something went wrong!";
            }
        }
    
        if ($_FILES["newpfp"]) {
            $pfpfile = time() . $_FILES['newpfp']['name'];
    
            if ($crud->update("users", [
                'pfp' => $pfpfile
            ], ['column' => 'id', 'value' => $id])) {
                move_uploaded_file($_FILES['newpfp']['tmp_name'], 'profilepics/' . $pfpfile);
                $_SESSION['pfp'] = $pfpfile;
                header('Location: profile.php?id=' . $_SESSION['user_id']);
            } else {
                echo "Something went wrong!";
            }
        }
    }
    
    if ($_POST) {
        if (isset($_POST['addfriend'])) {
            $crud->create("friends", ['user_id' => $_SESSION['user_id'], 'user_id2' => $_GET['id']]);
            header('Location: profile.php?id='.$_GET['id']);
        }
    }
?>