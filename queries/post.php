<?php
    //Function to display posts in random order
    function posts($conn) {  
        //Write query and join tables
        $sql = "SELECT *, greeks.name AS greek FROM posts JOIN users ON posts.author = users.user_id
         LEFT JOIN greeks ON posts.greek_group = greeks.greek_id WHERE posts.status = 1
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
                echo "Posted by <a class='username' href='user/profile.php?user_id=" . $row['author'] . "'>" . $row['username'] . "</a> on " . date('F j, Y, g:i a', strtotime($created_date));
                if($row['greek_group'] != NULL) {
                    echo " in " . "<a class='greek' href='heroes.php?greek_id=" . $row['greek_group'] . "'><strong>" .$row['greek'] . "</strong></a>";
                } 
                
                echo "</p>";
                //Display title and content
                echo "<a class='title' href='user/currentPost.php?post_id=" . $row['post_id'] . "&title=" . $row['title'] . "'><h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['content'] . "</p></a>";

                //Get the user vote type
                $sql2 = "SELECT vote_type FROM likes JOIN users ON likes.liker = users.user_id 
                WHERE likes.post_id = '" . $row['post_id'] . "' 
                AND likes.liker = '" . $_SESSION['user_id'] . "'";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();
                if($result2 !== false && $result2->num_rows > 0) {
                    $voteType = $row2['vote_type'];
                } else {
                    $voteType = null;
                }

                //Display likes and dislikes
                echo "<form action='actions/likes/like_act.php?post_id=" . $row['post_id'] . "&type=" . $voteType . "' method='POST' class='vote'>
                        <p><button type='submit' name='likeForm'><img class='vote-emoji' src='img/misc/like.png' alt='like'> " . $row['likes'] . " </button></p>
                        <p><button type='submit' name='dislikeForm'><img class='vote-emoji' src='img/misc/dislike.png' alt='dislike'> " . $row['dislikes'] . " </button></p>
                    </form>";

                //Get number of comments
                $sql2 = "SELECT COALESCE(COUNT(*), 0) AS total_comments FROM comments WHERE post_id = '" . $row['post_id'] . "' AND status = 1";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();

                //Display comments
                echo "<hr>";
                echo "<a class='commentLink' href='user/currentPost.php?post_id=" . $row['post_id'] . "'>comments " . $row2['total_comments'] . "</a>";
                echo "</div>";
            }
        //Else display no posts yet
        } else {
            echo "<div class='post'>";
            echo "<p>No posts yet</p>";
            echo "</div>";
        }
        
    }

    //Function to sort posts by date
    function postsByDate($conn) {
        //Write query and join tables
        $sql = "SELECT *, greeks.name AS greek FROM posts JOIN users ON posts.author = users.user_id
        LEFT JOIN greeks ON posts.greek_group = greeks.greek_id
        WHERE posts.status = 1
        ORDER BY posts.created_at DESC";
        //Execute query
        $result = $conn->query($sql);
        //Check if posts exist
        if ($result !== false && $result->num_rows > 0) {
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
                echo "Posted by <a class='username' href='user/profile.php?user_id=" . $row['author'] . "'>" . $row['username'] . "</a> " . " on " . date('F j, Y, g:i a', strtotime($created_date)); 
                if($row['greek_group'] != NULL) {
                    echo " in " . "<a class='greek' href='heroes.php?greek_id=" . $row['greek_group'] . "'><strong>" . $row['greek'] . "</strong></a>";
                }
                echo "</p>";
                //Display title and content
                echo "<a class='title' href='user/currentPost.php?post_id=" . $row['post_id'] . "&title=" . $row['title'] . "'><h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['content'] . "</p></a>";

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
                echo "<form action='actions/likes/like_act.php?post_id=" . $row['post_id'] . "&type=" . $voteType . "' method='POST' class='vote'>
                        <p><button type='submit' name='likeForm'><img class='vote-emoji' src='img/misc/like.png' alt='like'> " . $row['likes'] . " </button></p>
                        <p><button type='submit' name='dislikeForm'><img class='vote-emoji' src='img/misc/dislike.png' alt='dislike'> " . $row['dislikes'] . " </button></p>
                    </form>";

                //Get number of comments
                $sql2 = "SELECT count(*) AS total_comments FROM comments WHERE post_id = '" . $row['post_id'] . "' AND status = 1";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();

                //Display comments
                echo "<hr>";
                echo "<a class='commentLink' href='user/currentPost.php?post_id=" . $row['post_id'] . "'>comments " . $row2['total_comments'] . "</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='post'>";
            echo "<p>No posts found</p>";
            echo "</div>";
        }
    }

    //Function to sort post by likes
    function postsByLikes($conn) {
        //Write query and join tables
        $sql = "SELECT *, greeks.name AS greek FROM posts 
        JOIN users ON posts.author = users.user_id
        LEFT JOIN greeks ON greeks.greek_id = posts.greek_group
        WHERE posts.status = 1
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
                echo "Posted by <a class='username' href='user/profile.php?user_id=" . $row['author'] . "'>" .  $row['username'] . "</a> on " . date('F j, Y, g:i a', strtotime($created_date));
                //Check if greek group exists
                if ($row['greek_group'] != NULL) {
                    echo " in " . "<a class='greek' href='heroes.php?greek_id=" . $row['greek_group'] . "'><strong>" . $row['greek'] . "</strong></a>"  ;
                }
                echo "</p>";
                //Display title and content
                echo "<a class='title' href='user/currentPost.php?post_id=" . $row['post_id'] . "&title=" . $row['title'] . "'><h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['content'] . "</p></a>";

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
                echo "<form action='actions/likes/like_act.php?post_id=" . $row['post_id'] . "&type=" . $voteType . "' method='POST' class='vote'>
                        <p><button type='submit' name='likeForm'><img class='vote-emoji' src='img/misc/like.png' alt='like'> " . $row['likes'] . " </button></p>
                        <p><button type='submit' name='dislikeForm'><img class='vote-emoji' src='img/misc/dislike.png' alt='dislike'> " . $row['dislikes'] . " </button></p>
                    </form>";

                //Get total comments
                $postId = $row['post_id'];
                $sql2 = "SELECT COUNT(comments.comment_id) AS 
                total_comments, posts.post_id FROM comments 
                JOIN posts ON comments.post_id = posts.post_id
                WHERE posts.post_id = '$postId' AND comments.status = 1";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();    
                //Display comments
                echo "<hr>";
                echo "<a class='commentLink' href='user/currentPost.php?post_id=" . $row['post_id'] . "'>comments " . $row2['total_comments'] . "</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='post'><p>No posts found</p></div>";
        }
    }

    //Function to sort post by comments
    function postsByComments($conn) {
        //Write query and join tables including total comments
        //Left join to get total comments even if there are no comments
        //Group by post_id to get total comments
        //Order by total comments
        $sql = "SELECT users.*, posts.*, greeks.name AS greek, 
        (SELECT COUNT(*) FROM comments 
        WHERE comments.post_id = posts.post_id AND comments.status = 1) AS total_comments
        FROM posts
        JOIN users ON posts.author = users.user_id
        LEFT JOIN greeks ON posts.greek_group = greeks.greek_id
        WHERE posts.status = 1 
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
                echo "Posted by<a class='username' href='user/profile.php?user_id=" . $row['author'] . "'>" . $row['username'] . "</a>". "on " . date('F j, Y, g:i a', strtotime($created_date)); 
                //Display greek group if it exists
                if ($row['greek_group'] != NULL) {
                    echo " in" . "<a class='greek' href='heroes.php?greek_id=" . $row['greek_group'] . "'><strong>" . $row['greek'] . "</strong></a>";
                }
                echo "</p>";
                //Display title and content
                echo "<a class='title' href='user/currentPost.php?post_id=" . $row['post_id'] . "&title=" . $row['title'] . "'><h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['content'] . "</p></a>";

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
                echo "<form action='actions/likes/like_act.php?post_id=" . $row['post_id'] . "&type=" . $voteType . "' method='POST' class='vote'>
                        <p><button type='submit' name='likeForm'><img class='vote-emoji' src='img/misc/like.png' alt='like'> " . $row['likes'] . " </button></p>
                        <p><button type='submit' name='dislikeForm'><img class='vote-emoji' src='img/misc/dislike.png' alt='dislike'> " . $row['dislikes'] . " </button></p>
                    </form>";

                //Display comments
                echo "<hr>";
                echo "<a class='commentLink' href='user/currentPost.php?post_id=" . $row['post_id'] . "'>comments " . $row['total_comments'] . "</a>";
                echo "</div>";
            }
        } else {
            echo "<div class='post'><p>No posts found</p></div>";
        }
        //Close connection
        
    }


    //Function to create post
    function createPost($conn, $title, $content, $author, $greek_id) {
        //Check if form is submitted
        if(isset($_POST['createPost'])) {
            
            //Write query
            $sql = "INSERT INTO posts (post_id, title, content, author, greek_group) VALUES (UUID(), ?, ?, ?, ?)";
            //Prepare statement
            $stmt = $conn->prepare($sql);

            //Set parameters
            $output = nl2br($content);
            $author = $_SESSION['user_id'];

            //If no greek group is selected
            if($greek_id == "" || empty($greek_id)) {
                $greek_id = null;
            }

            //Check if title is empty
            if($title == "" || empty($title)) {
                header("Location: ../user/createPost.php?error=The title cannot be empty");
                exit();
            }

            //Bind parameters
            $stmt->bind_param("ssss", $title, $output, $author, $greek_id);
            
            //Execute query
            $stmt->execute();

            //Get post id related to the user
            $sql = "SELECT post_id FROM posts WHERE author = '$author' AND title = '$title'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $post_id = $row['post_id'];

            //Fetch the greek group name
            $sql = "SELECT name FROM greeks WHERE greek_id = '$greek_id'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $greek = $row['name'];

            //Include activity query
            include "activity.php";
            
            //Add activity if there is a greek group
            if($greek_id != null) {
                addActivity($conn, $author, "created a post with title " . $title . " in " . $greek . " discussion", $post_id, null);
            }
            else {
                addActivity($conn, $author, "created a post with title " . $title, $post_id, null);
            }
            
            //Close statement
            $stmt->close();
            header("Location: ../index.php");
        }
    }

    //Function to edit post
    function editPost($conn, $postId) {
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

            //Include activity
            include "activity.php";
            //Add activity or call function addActivity
            addActivity($conn, $author, "edited a post with title " . $title, $postId, null);
            header("Location: ../index.php?post_id=" . $postId);
        }
    }

    //Function to display current post
    function currentPost($conn, $postId, $userId) {
        //Writequery and join tables to display current posts
        $sql = "SELECT *, greeks.name AS greek FROM posts 
        JOIN users ON posts.author = users.user_id 
        LEFT JOIN greeks ON posts.greek_group = greeks.greek_id
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
        echo "Posted by <a class='username' href='profile.php?user_id=" . $row['author'] . "'>" .$row['username'] . "</a> on " . date('F j, Y, g:i a', strtotime($created_at)); 
        //Check if greek group exists
        if($row['greek'] != NULL) {
            echo " in " . "<a class='greek' href='../heroes.php?greek_id=" . $row['greek_group'] . "'><strong>" . $row['greek'] . "</strong></a>";
        }
        echo "</p>";
        
        //Display post title and content
        echo "<a class='title' href='currentPost.php?post_id=" . $row['post_id'] . "'><h3>" . $row['title'] . "</h3></a>";
        echo "<p>" . $row['content'] . "</p>";

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
        echo "<form action='../actions/likes/like_act.php?post_id=" . $row['post_id'] . "&type=" . $voteType . "' method='POST' class='vote'>
                <p><button type='submit' name='likeForm'><img class='vote-emoji' src='../img/misc/like.png' alt='like'> " . $row['likes'] . " </button></p>
                <p><button type='submit' name='dislikeForm'><img class='vote-emoji' src='../img/misc/dislike.png' alt='dislike'> " . $row['dislikes'] . " </button></p>
            </form>";

        echo "<hr>";
        //Display options to edit or delete post if user is author
        if($userId == $row['author']){
            echo "<div class='post-options'>";
            echo "<a href='deletePost.php?post_id=" . $row['post_id'] . "' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this post?')\">Delete Post</a>";
            echo "<a href='editPost.php?post_id=" . $row['post_id'] . "'>Edit Post</a>";
            echo "</div>";
        }
        echo "</div>";
        //Close connection
        
    }

    //Function to display all posts related to the user
    function userPosts($conn, $userId) {
        //Write query
        $sql = "SELECT * FROM posts
        JOIN users ON posts.author = users.user_id
        LEFT JOIN greeks ON posts.greek_group = greeks.greek_id 
        WHERE posts.author = ? AND posts.status = 1
        ORDER BY created_at DESC";
        //Prepare query
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("s", $userId);
        //Execute query
        $stmt->execute();
        //Get result
        $result = $stmt->get_result();  
        //Display posts
        if($result !== false && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                //Get user id
                $userId = $row['author'];
                //Get post date and time
                $created_date = $row['created_at'];
                //Posts Containers
                echo "<div class='post'>";
                echo "<p class='author'><a href='profile.php?user_id=" . $row['author'] . "'>";
                //Check if profile picture exists
                if ($row['profile_pic'] != NULL) {
                    echo "<img src='../img/u/" . $row['profile_pic'] . "' alt='user' class='profilePic'>";
                // Provide a default picture if no profile picture exists
                } else {
                    echo "<img src='../img/default.jpg' alt='user' class='profilePic'>";
                }
                echo "</a>";
                //Display username and date
                echo "Posted by <a class='username' href='profile.php?user_id=" . $row['author'] . "'>" . $row['username'] . "</a> on " . date('F j, Y, g:i a', strtotime($created_date));
                if($row['greek_group'] != NULL) {
                    echo " in " . "<a class='greek' href='../heroes.php?greek_id=" . $row['greek_group'] . "'>" .$row['name'] . "</a>";
                }
                echo "</p>";
                //Display title and content
                echo "<a class='title' href='currentPost.php?post_id=" . $row['post_id'] . "&title=" . $row['title'] . "'><h3>" . $row['title'] . "</h3>";
                echo "<p>" . $row['content'] . "</p></a>";

                //Get the user vote type
                $sql2 = "SELECT vote_type FROM likes JOIN users ON likes.liker = users.user_id 
                WHERE likes.post_id = '" . $row['post_id'] . "' 
                AND likes.liker = '" . $_SESSION['user_id'] . "'";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();
                if($result2 !== false && $result2->num_rows > 0) {
                    $voteType = $row2['vote_type'];
                } else {
                    $voteType = null;
                }

                //Display likes and dislikes
                echo "<form action='../actions/likes/like_act.php?post_id=" . $row['post_id'] . "&type=" . $voteType . "' method='POST' class='vote'>
                        <p><button type='submit' name='likeForm'><img class='vote-emoji' src='../img/misc/like.png' alt='like'> " . $row['likes'] . " </button></p>
                        <p><button type='submit' name='dislikeForm'><img class='vote-emoji' src='../img/misc/dislike.png' alt='dislike'> " . $row['dislikes'] . " </button></p>
                    </form>";

                //Get number of comments
                $sql2 = "SELECT COALESCE(COUNT(*), 0) AS total_comments FROM comments WHERE post_id = '" . $row['post_id'] . "' AND status = 1";
                $result2 = $conn->query($sql2);
                $row2 = $result2->fetch_assoc();

                //Display comments
                echo "<hr>";
                echo "<a class='commentLink' href='../user/currentPost.php?post_id=" . $row['post_id'] . "'>comments " . $row2['total_comments'] . "</a>";
                echo "</div>";
            }
        //Else display no posts yet
        } else {
            echo "<div class='post'>";
            echo "<p>No posts yet</p>";
            echo "</div>";
        }
    }
                

    //Function to delete post
    function deletePost($conn, $postId) {
        //Write sql query to delete table
        $sql = "DELETE FROM posts WHERE post_id = '$postId'";
        //Execute query
        $conn->query($sql);
        //Redirect to index
        header("Location: ../index.php");

    }
?>