<?php
    //Function to display comments
    function comments($conn) {
        //Get post id from url or using GET method
        $postId = $_GET['post_id'];
        //Write query and join tables to display comments
        $sql = "SELECT * FROM comments 
        JOIN users ON comments.author = users.user_id 
        WHERE comments.post_id = '$postId' AND comments.status = 1
        ORDER BY created_at DESC;";
        //Execute query
        $result = $conn->query($sql);
        //Check if comments exist
        if($result !== false && $result->num_rows > 0) {
            // Display comments
            while ($row = $result->fetch_assoc()) {
                //Clean the comments text
                //Display comments
                echo "<div class='comment'>";
                //Display username and date of comment
                echo "<p class='author'> <a href='profile.php?user_id=" . $row['author'] . "'>";
                //Check if profile pic exists
                if($row['profile_pic'] != NULL) {
                    echo "<img src='../img/u/" . $row['profile_pic'] . "' alt='user' class='profilePic'>";
                //Display default profile pic if profile pic does not exist
                } else {
                    echo "<img src='../img/default.jpg' alt='user' class='profilePic'>";
                }
                echo "</a>";
                //Check if the comment is a reply
                if($row['parent_comment'] != NULL) {
                //Display username and date
                echo "Replied by " . $row['username'] . " " . date('F j, Y, g:i a', strtotime($row['created_at'])) . "</p>";
                } else {
                //Display username and the replier
                echo "Commented by " . $row['username'] . " " . date('F j, Y, g:i a', strtotime($row['created_at'])) . "</p>";
                }
                //Display comment
                echo "<div class='content'>";
                echo "<p>" . $row['content']. "</p>";
                echo "<div class='replybtnOptions'>";
                echo "<button class='replyBtn'>Reply</button>";
                if($row['author'] == $_SESSION['user_id']) {
                    //Display delete button if comment is authored by user
                    echo "<form action='../actions/comment_act.php?post_id=" . $postId . "&comment_id=" . $row['comment_id'] . "' method='POST'>";
                    echo "<button class='deleteBtn' onclick=\"return confirm('Are you sure you want to delete this comment?')\" name='deleteComment'>Delete</button>";
                    echo "</form>";
                }
                echo "</div>";
                echo "</div>";

                //Get the user vote type
                $sql2 = "SELECT vote_type FROM likes JOIN users ON likes.liker = users.user_id 
                WHERE likes.post_id = '" . $row['post_id'] . "' 
                AND likes.liker = '" . $_SESSION['user_id'] . "'";
                //Execute query
                $result2 = $conn->query($sql2);
                //Get vote type
                $row2 = $result2->fetch_assoc();
                //Check if there is a vote
                if($result2 !== false && $result2->num_rows > 0) {
                    $voteType = $row2['vote_type'];
                } else {
                    $voteType = null;
                }

                //Display likes and dislikes
                echo "<form action='../actions/likes/commentLike_act.php?post_id=" . $row['post_id'] . "&comment_id=" . $row['comment_id'] . "&type=" . $voteType . "' method='POST' class='vote'>
                        <p><button type='submit' name='likeForm'><img class='vote-emoji' src='../img/misc/like.png' alt='like'> " . $row['likes'] . " </button></p>
                        <p><button type='submit' name='dislikeForm'><img class='vote-emoji' src='../img/misc/dislike.png' alt='dislike'> " . $row['dislikes'] . " </button></p>
                    </form>";

                //Display reply box
                echo "<div class='reply-box hidden'>";
                echo "<form action='../actions/comment_act.php?post_id=" . $postId . "&comment_id=" . $row['comment_id'] . "' method='POST'>";
                echo "<textarea name='content' placeholder='Reply to " . $row['username'] . "' class='replyInput'></textarea>";
                echo "<button type='submit' name='replyForm'>Reply</button>";
                echo "</form>";
                echo "</div>";
                echo "</div>";
            }
        //Display message if no comments
        } else {
            echo "<div class='comment'>";
            echo    "<p>No comments yet</p>";
            echo "</div>";
        }
        //Close connection
        
    }

    function createComment($conn, $userId, $postId, $content) {
        if(isset($_POST['commentForm'])) {
            //Check if content is set and not null to create comment
            if(isset($content) && !empty($content) && !is_null($content)) {
                //Write query
                $sql = "INSERT INTO comments (comment_id, author, post_id, content) VALUES (?, ?, ?, ?)";
                //Prepare query
                $stmt = $conn->prepare($sql);
                //Get unique comment id
                $comment_id = uniqid();
                //Clean the text 
                $output = nl2br($content);
            
                //Bind parameters
                $stmt->bind_param("ssss", $comment_id, $userId, $postId, $output);


                //Execute query
                $stmt->execute();

                //Check if query executed
                if($stmt == true) {
                //fetch the inserted comment by using and getting comment id
                    $sql = "SELECT comments.comment_id, posts.title FROM comments 
                    JOIN posts ON comments.post_id = posts.post_id 
                    WHERE comments.comment_id = '$comment_id'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();

                    //include queries
                    include "activity.php";
                    //Get title from posts and comment relationship
                    $title= $row['title'];
                    addActivity($conn, $userId, "commennted on a post titled " . $title . "", NULL, $row['comment_id']);
                }
            }

            //Get post id from url or using GET method
            $postId = $_GET['post_id'];
            header("Location: ../user/currentPost.php?post_id=" . $postId);
        }

    }

    //Function to create reply
    function createReply($conn, $userId, $postId, $commentId, $content) {
        if(isset($_POST['replyForm'])) {
            //Check if content is set and not null to create comment
            if(isset($content) && !empty($content) && !is_null($content)) {
                //Write query
                $sql = "INSERT INTO comments (parent_comment, comment_id, author, post_id, content) VALUES (?, ?, ?, ?, ?)";
                //Prepare query
                $stmt = $conn->prepare($sql);
                //Get unique comment id
                $comment_id = uniqid();
                //Clean the text 
                $output = nl2br($content);
            
                //Bind parameters
                $stmt->bind_param("sssss", $commentId, $comment_id, $userId, $postId, $output);
                //Execute query
                $stmt->execute();

                //Check if query executed
                if($stmt == true) {
                //fetch the inserted comment by using and getting comment id
                    $sql = "SELECT comments.comment_id, posts.title FROM comments 
                    JOIN posts ON comments.post_id = posts.post_id 
                    WHERE comments.comment_id = '$comment_id'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    //include queries
                    include "activity.php";
                    //Get title from posts and comment relationship
                    $title= $row['title'];
                    addActivity($conn, $userId, "replied to a comment titled " . $title . "", NULL, $row['comment_id']);
                }
            }
            //Get post id from url or using GET method
            $postId = $_GET['post_id'];
            header("Location: ../user/currentPost.php?post_id=" . $postId . "&comment_id=" . $commentId);
        }
    }

    //Function to delete comment
    function deleteComment($conn, $commentId, $postId) {
        if(isset($_POST['deleteComment'])) {
            //Write query
            $sql = "DELETE FROM comments WHERE comment_id = '$commentId'";
            //Execute query
            $conn->query($sql);
            header("Location: ../user/currentPost.php?post_id=" . $postId);
        }
    }

?>