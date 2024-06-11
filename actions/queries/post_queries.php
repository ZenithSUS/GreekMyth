<?php
    //Function to display posts in random order
    function posts() {  
        //Include db connection  
        include "db.php";
        //Write query and join tables
        $sql = "SELECT * FROM posts JOIN users ON posts.author = users.user_id
        ORDER BY RAND()";
        //Execute query
        $result = $conn->query($sql);
        //Display posts in random order
        if($result !== false && $result->num_rows > 0) {
            //Loop through results
            while ($row = $result->fetch_assoc()) {
                //Get post date and time
                $created_date = $row['created_at'];
                //Posts Containers
                echo "<div class='post'>";
                echo "<p class='author'><a href='user/profile.php?user_id=" . $row['author'] . "'>";
                //Check if profile picture exists
                if ($row['profile_pic'] != NULL) {
                    echo "<img src='img/u/" . $row['profile_pic'] . "' alt='user' class='profilePic'>";
                // Provide a default picture if no profile picture exists
                } else {
                    echo "<img src='img/default.jpg' alt='user' class='profilePic'>";
                }
                echo "</a>";
                //Display username and date
                echo "Posted by " . $row['username'] . " on " . date('F j, Y, g:i a', strtotime($created_date)) . "</p>";
                //Display title and content
                echo "<a class='title' href='user/currentPost.php?post_id=" . $row['post_id'] . "&title=" . $row['title'] . "'><h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['content'] . "</p></a>";
                //Display likes and dislikes
                echo "<div class='vote'>
                        <p><img src='img/like.png' alt='like'> " . $row['likes'] . "</p>
                        <p><img src='img/dislike.png' alt='dislike'> " . $row['dislikes'] . "</p>
                    </div>";

                //Get number of comments
                $sql2 = "SELECT COALESCE(COUNT(*), 0) AS total_comments FROM comments WHERE post_id = '" . $row['post_id'] . "'";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();

                //Display comments
                echo "<hr>";
                echo "<a href='user/currentPost.php?post_id=" . $row['post_id'] . "'>comments " . $row2['total_comments'] . "</a>";
                echo "</div>";
            }
        //Else display no posts yet
        } else {
            echo "<div class='post'>";
            echo "<p>No posts yet</p>";
            echo "</div>";
        }
        //Close connection
        $conn->close();
    }

    //Function to sort posts by date
    function postsByDate() {
        //Include db connection
        include "db.php";
        //Write query and join tables
        $sql = "SELECT * FROM posts JOIN users ON posts.author = users.user_id 
        ORDER BY posts.created_at DESC";
        //Execute query
        $result = $conn->query($sql);
        //Display posts in date order
        while ($row = $result->fetch_assoc()) {
            //Get post date and time
            $created_date = $row['created_at'];

            //Posts Containers
            echo "<div class='post'>";
            echo "<p class='author'><a href='user/profile.php?user_id=" . $row['author'] . "'>";
            //Check if profile picture exists
            if ($row['profile_pic'] != NULL) {
                echo "<img src='img/u/" . $row['profile_pic'] . "' alt='user' class='profilePic'>";
            // Provide a default picture if no profile picture exists
            } else {
                echo "<img src='img/default.jpg' alt='user' class='profilePic'>";
            }
            echo "</a>";
            //Display username and date
            echo "Posted by " . $row['username'] . " on " . date('F j, Y, g:i a', strtotime($created_date)) . "</p>";
            //Display title and content
            echo "<a class='title' href='user/currentPost.php?post_id=" . $row['post_id'] . "&title=" . $row['title'] . "'><h3>" . $row['title'] . "</h3>";
            echo "<p>" . $row['content'] . "</p></a>";
            //Display likes and dislikes
            echo "<div class='vote'>
                <p><img src='img/like.png' alt='like'> " . $row['likes'] . "</p>
                <p><img src='img/dislike.png' alt='dislike'> " . $row['dislikes'] . "</p>
            </div>";

            //Get number of comments
            $sql2 = "SELECT count(*) AS total_comments FROM comments WHERE post_id = '" . $row['post_id'] . "'";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            //Display comments
            echo "<hr>";
            echo "<a href='user/currentPost.php?post_id=" . $row['post_id'] . "'>comments " . $row2['total_comments'] . "</a>";
            echo "</div>";
        }
    }

    //Function to sort post by likes
    function postsByLikes() {
        //Include db connection
        include "db.php";
        //Write query and join tables
        $sql = "SELECT * FROM posts 
        JOIN users ON posts.author = users.user_id
        ORDER BY likes DESC";
        //Execute query
        $result = $conn->query($sql);
        //Check if posts exist
        if($result !== false && $result->num_rows > 0) {
            //Display posts in likes order
            while ($row = $result->fetch_assoc()) {
                //Get post date and time
                $created_date = $row['created_at'];
                //Posts Containers
                echo "<div class='post'>";
                //Display username and date
                echo "<p class='author'><a href='user/profile.php?user_id=" . $row['author'] . "'>";
                //Check if profile picture exists
                if ($row['profile_pic'] != NULL) {
                    echo "<img src='img/u/" . $row['profile_pic'] . "' alt='user' class='profilePic'>";
                // Provide a default picture if no profile picture exists
                } else {
                    echo "<img src='img/default.jpg' alt='user' class='profilePic'>";
                }
                echo "</a>";
                //Display username and date
                echo "Posted by " . $row['username'] . " on " . date('F j, Y, g:i a', strtotime($created_date)) . "</p>";
                //Display title and content
                echo "<a class='title' href='user/currentPost.php?post_id=" . $row['post_id'] . "&title=" . $row['title'] . "'><h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['content'] . "</p></a>";
                //Display likes and dislikes
                echo "<div class='vote'>
                        <p><img src='img/like.png' alt='like'> " . $row['likes'] . "</p>
                        <p><img src='img/dislike.png' alt='dislike'> " . $row['dislikes'] . "</p>
                    </div>";

                //Get total comments
                $postId = $row['post_id'];
                $sql2 = "SELECT COUNT(comments.comment_id) AS 
                total_comments, posts.post_id FROM comments 
                JOIN posts ON comments.post_id = posts.post_id
                WHERE posts.post_id = '$postId'";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();    
                //Display comments
                echo "<hr>";
                echo "<a href='user/currentPost.php?post_id=" . $row['post_id'] . "'>comments " . $row2['total_comments'] . "</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='post'><p>No posts Found</p></div>";
        }
    }

    //Function to sort post by comments
    function postsByComments() {
        //Include db connection
        include "db.php";
        //Write query and join tables including total comments
        //Left join to get total comments even if there are no comments
        //Group by post_id to get total comments
        //Order by total comments
        $sql = "SELECT users.*, posts.*, COALESCE(COUNT(comments.comment_id), 0) AS total_comments
        FROM posts
        JOIN users ON posts.author = users.user_id
        LEFT JOIN comments ON posts.post_id = comments.post_id
        GROUP BY posts.post_id
        ORDER BY total_comments DESC;";
        //Execute query
        $result = $conn->query($sql);
        if($result !== false && $result->num_rows > 0) {
            //Display posts in comments order
            while ($row = $result->fetch_assoc()) {
                //Get post date and time
                $created_date = $row['created_at'];
                //Posts Containers
                echo "<div class='post'>";
                //Display username and date
                echo "<p class='author'><a href='user/profile.php?user_id=" . $row['author'] . "'>";
                //Check if profile picture exists
                if ($row['profile_pic'] != NULL) {
                    echo "<img src='img/u/" . $row['profile_pic'] . "' alt='user' class='profilePic'>";
                // Provide a default picture if no profile picture exists
                } else {
                    echo "<img src='img/default.jpg' alt='user' class='profilePic'>";
                }
                echo "</a>";
                //Display username and date
                echo "Posted by " . $row['username'] . " on " . date('F j, Y, g:i a', strtotime($created_date)) . "</p>";
                //Display title and content
                echo "<a class='title' href='user/currentPost.php?post_id=" . $row['post_id'] . "&title=" . $row['title'] . "'><h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['content'] . "</p></a>";
                //Display likes and dislikes
                echo "<div class='vote'>
                        <p><img src='img/like.png' alt='like'> " . $row['likes'] . "</p>
                        <p><img src='img/dislike.png' alt='dislike'> " . $row['dislikes'] . "</p>
                    </div>";
                //Display comments
                echo "<hr>";
                echo "<a href='user/currentPost.php?post_id=" . $row['post_id'] . "'>comments " . $row['total_comments'] . "</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='post'><p>No posts Found</p></div>";
        }
        //Close connection
        $conn->close();
    }


    //Function to create post
    function createPost(){
        //Include queries
        include "../db.php";
        //Initialize session
        session_start();
        //Check if form is submitted
        if(isset($_POST['createPost'])) {
            //Write query
            $sql = "INSERT INTO posts (post_id, title, content, author) VALUES (?, ?, ?, ?)";
            //Prepare statement
            $stmt = $conn->prepare($sql);

            //Bind parameters
            $stmt->bind_param("ssss", $post_id, $title, $output, $author);
            //Set parameters
            $post_id = uniqid();
            $title = $_POST['title'];
            $content = $_POST['content'];
            $output = nl2br($content);
            $author = $_SESSION['user_id'];

            //Execute query
            $stmt->execute();

            //Check if title is empty
            if($title == "" || empty($title)) {
                header("Location: ../user/createPost.php?error=The title cannot be empty");
                exit();
            }

            //Get post id related to the user
            $sql = "SELECT post_id FROM posts WHERE author = '$author' AND title = '$title'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $post_id = $row['post_id'];
            addActivity($author, "created a post a with title " . $title, $post_id, null);
            
            //Close statement
            $stmt->execute();
            $stmt->close();
            header("Location: ../index.php");
        }
    }

    //Function to edit post
    function editPost($postId) {
        include "../db.php";
        session_start();
        //Get post id
        if(isset($_POST['editPost'])) {
            $sql = "UPDATE posts SET title = ?, content = ?, updated_at = NOW() WHERE post_id = ?";
            //Prepare statement
            $stmt = $conn->prepare($sql);
            
            // Get data from form
            $title = $_POST['title'];
            $content = $_POST['content'];
            $author = $_SESSION['user_id'];
            $output = nl2br($content);

            //Bind parameters
            $stmt->bind_param("sss", $title, $output, $postId);

            //Check if title is empty
            if($title == "" || empty($title)) {
                header("Location: ../user/editPost.php?post_id=" . $postId . "&error=The title cannot be empty");
                exit();
            } 
            //Execute statement
            $stmt->execute();

            //Get post id related to the user
            $sql = "SELECT post_id FROM posts WHERE author = '$author' AND title = '$title'";
            //Execute query
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            //Get post id related to the user
            $postId = $row['post_id'];

            //Add activity or call function addActivity
            addActivity($author, "edited a post with title " . $title, $postId, null);
            header("Location: ../index.php?post_id=" . $postId);
        }
    }

    //Function to display current post
    function currentPost($postId, $userId) {
        //Include queries
        include "../db.php";
        //Writequery and join tables to display current posts
        $sql = "SELECT * FROM posts 
        JOIN users ON posts.author = users.user_id 
        WHERE posts.post_id = '$postId'";
        //Execute query
        $result = $conn->query($sql);
        //Get post data from query using fetch_assoc
        $row = $result->fetch_assoc();
        //Get post date and time from created_at
        $created_at = $row['created_at'];
        //Display post
        echo "<div class='post'>";
        //Display username and date of post
        echo "<p class='author'> <a href='profile.php?user_id=" . $row['author'] . "'>";
        //Check if profile pic exists
        if($row['profile_pic'] != NULL) {
            echo "<img src='../img/u/" . $row['profile_pic'] . "' alt='user' class='profilePic'>";
        //Display default profile pic if profile pic does not exist
        } else {
            echo "<img src='../img/default.jpg' alt='user' class='profilePic'>";
        }
        echo "</a>";
        //Display username and date
        echo "Posted by " .$row['username'] . " on " . date('F j, Y, g:i a', strtotime($created_at)) . "</p>";
        
        //Display post title and content
        echo "<a class='title' href='user/currentPost.php?post_id=" . $row['post_id'] . "'><h3>" . $row['title'] . "</h3></a>";
        echo "<p>" . $row['content'] . "</p>";
        //Display likes and dislikes
        echo "<div class='vote'>
                    <img src='img/like.png' alt='like'><p> " . $row['likes'] . "</p>
                    <img src='img/dislike.png' alt='dislike'><p> " . $row['dislikes'] . "</p>
            </div>";
            //Display options to edit or delete post
            if($userId == $row['author']){
                echo "<div class='post-options'>";
                echo "<a href='deletePost.php?post_id=" . $row['post_id'] . "' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this post?')\">Delete Post</a>";
                echo "<a href='editPost.php?post_id=" . $row['post_id'] . "'>Edit Post</a>";
                echo "</div>";
            }
        echo "<hr>";
        echo "</div>";
        //Close connection
        $conn->close();
    }

    //Function to delete post
    function deletePost($postId) {
        //Include queries
        include "../db.php";
        //Write sql query to delete table
        $sql = "DELETE FROM posts WHERE post_id = '$postId'";
        //Execute query
        $conn->query($sql);
        //Redirect to index
        header("Location: ../index.php");

    }
?>