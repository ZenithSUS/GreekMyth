<?php
    //Include queries
    include "queries/posts_queries.php";

    //Checks if the editform is submitted
    if(isset($_POST['editPost'])) {
        //Get data from url using GET method
        $postId = $_GET['post_id'];
        //Call editPost function
        editPost($postId);
    }
?>