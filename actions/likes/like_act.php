<?php
    //Include db connection
    include "../../db.php";

    //Initialize session
    session_start();

    //Include queries
    include "../../queries/like.php";

    //Get post id and user id
    $postId = $_GET['post_id'] ?? null;
    $userId = $_SESSION['user_id'];

    //Get type from url or using GET method
    $type = $_GET['type'] ?? null;

    //Check if the user is logged in
    if(!isset($_SESSION['user_id'])) {
        header("Location: ../../auth/login.php");
    }

    //Check if the get methods are set
    if(!isset($postId) || !isset($userId) || !isset($type)) {
        header("Location: ../../index.php");
    }
    
    //Check of the user pressed like
    if(isset($_POST['likeForm'])) {
        //Call addLike function
        like($conn, $postId, $userId, $type);
    }

    //Check of the user pressed dislike
    if(isset($_POST['dislikeForm'])) {
        //Call removeLike function
        dislike($conn, $postId, $userId, $type);
    }

?>