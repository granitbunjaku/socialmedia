<?php 
    
    include 'classes/CRUD.php';
    $crud = new CRUD;
    
    $request_body = file_get_contents('php://input');
    $data = json_decode($request_body, true);

    $comment_id = $data['comment_id'];

    if(isset($_GET['post_id'])) {
        if($crud->delete("posts", ['id' => $_GET['post_id']])) {
            header('Location: index.php');
        }
    } else {
        if($crud->delete("comments", ['id' => $comment_id])) {
            header('Location: index.php');
        }
    }

?>