<?php
    //Function to get greeks limit by 8
    function getGreeks($conn, $userId) {
        //Write query
        $sql = "SELECT * FROM greeks 
        JOIN user_groups ON greeks.greek_id = user_groups.greek_id 
        WHERE user_groups.user_id = ? AND greeks.status = 1 
        ORDER BY name LIMIT 8;";
        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("s", $userId);
        //Execute query
        $stmt->execute();
        //Get result
        $result = $stmt->get_result();
        //Check if heroes exist
        if($result !== false && $result->num_rows > 0) {
            // Display heroes
            while ($row = $result->fetch_assoc()) {
                //Clean the heroes text
                //Display heroes
                echo "<div class='hero-box'>";
                echo "<a class='greek-link' href='heroes.php?greek_id=" . $row['greek_id'] . "'>";
                //If image url is null display default image
                if($row['image_url'] == null) {
                    echo "<img src='img/hero.png' alt='hero' class='hero-img'>";
                } else {
                    echo "<img src='img/gods/" . $row['image_url'] . "' alt='hero' class='hero-img'>";
                }
                echo "<h2>" . $row['name'] . "</h2></a>";
                echo "</div>";
            } 
            echo "<a href='heroes.php' class='view-more-btn'>View More</a>";
        } else {
            echo "<p>No greeks found</p>";
        }
    }

    //Function to get all greeks
    function getAllGreeks($conn, $userId) {
        //Write query
        $sql = "SELECT * FROM greeks 
        JOIN user_groups ON greeks.greek_id = user_groups.greek_id 
        WHERE user_groups.user_id = ? AND greeks.status = 1 ORDER BY name;";
        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("s", $userId);
        //Execute query
        $stmt->execute();
        //Get result
        $result = $stmt->get_result();
        //Check if heroes exist
        if($result !== false && $result->num_rows > 0) {
            // Display heroes
            while ($row = $result->fetch_assoc()) {
                //Clean the heroes text
                //Display heroes
                echo "<div class='hero-box'>";
                echo "<a class='greek-link' href='heroes.php?greek_id=" . $row['greek_id'] . "'>";
                    //If image url is null display default image
                    if($row['image_url'] == null) {
                        echo "<img src='img/hero.png' alt='" . $row['name'] . "'>";
                    } else {
                        echo "<img src='img/gods/" . $row['image_url'] . "' alt='" . $row['name'] . "'>";
                    }
                echo "<h2>" . $row['name'] . "</h2></a>";
                echo "</div>";
            } 
        } else {
            echo "<p>No greeks found</p>";
        }
    }
 

    //Function to get heroes inside in user folder limit by 8
    function getGreeksUser($conn, $userId) {
        //Write query
        $sql = "SELECT greeks.greek_id, greeks.name, greeks.image_url FROM greeks
        JOIN user_groups ON greeks.greek_id = user_groups.greek_id
        WHERE user_groups.user_id = ? ORDER BY name LIMIT 8;";
        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("s", $userId);
        //Execute query
        $stmt->execute();
        //Get result
        $result = $stmt->get_result();
        //Check if heroes exist
        if($result !== false && $result->num_rows > 0) {
            // Display heroes
            while ($row = $result->fetch_assoc()) {
                //Clean the heroes text
                //Display heroes
                echo "<div class='hero-box'>";
                echo "<a class='greek-link' href='../heroes.php?greek_id=" . $row['greek_id'] . "'>";
                //If image url is null display default image
                if($row['image_url'] == null) {
                    echo "<img src='../img/hero.png' alt='" . $row['name'] . "'>";
                } else {
                    echo "<img src='../img/gods/" . $row['image_url'] . "' alt='" . $row['name'] . "'>";
                }
                echo "<h2>" . $row['name'] . "</h2></a>";        
                echo "</div>";
            }
            echo "<a href='../heroes.php' class='view-more-btn'>View More</a>";
        } else {
            echo "<p>No greeks found</p>";
        }
    }


    //Function to display greek groups 
    function getGreeksInfos($conn, $userId) {
        //Write query
        $sql = "SELECT * FROM greeks 
        JOIN user_groups ON greeks.greek_id = user_groups.greek_id 
        WHERE user_groups.user_id = ? AND greeks.status = 1 
        ORDER BY name";
        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("s", $userId);
        //Execute query
        $stmt->execute();
        //Get result
        $result = $stmt->get_result();

        //Check if myth exists
        if($result !== false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='myth-container'>";
                echo "<div class='myth-box'>";
                echo "<div class='greek-name'>";
                    //If image url is null display default image
                    if($row['image_url'] == null) {
                        echo "<img src='img/hero.png' alt='" . $row['name'] . "'>";
                    } else {
                        echo "<img src='img/gods/" . $row['image_url'] . "' alt='" . $row['name'] . "'>";
                    }
                echo "<h2>" . $row['name'] . "</h2>   
                    </div>";

                echo "<div class='myth-info'>
                        <h3>Description</h3>
                        <div class='myth-text'>" . $row['description'] . "</div>";
                echo "<a href='heroes.php?greek_id=" . $row['greek_id'] . "' class='myth-btn'>Read More</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
    }

    //Function to display specific myth
    function getSpecificGreekInfo($conn, $greek_id) {
        //Get user id
        $userId = $_SESSION['user_id'];
        //Write querie for myth
        $sql = "SELECT * FROM greeks WHERE greek_id = '$greek_id'";
        //Write query for number of people in myth
        $sql2 = "SELECT COUNT(*) AS total_people FROM user_groups 
        JOIN greeks ON greeks.greek_id = user_groups.greek_id 
        JOIN users ON user_groups.user_id = users.user_id
        WHERE greeks.greek_id = '$greek_id'";
        //Write query for checking if the user is the creator of the myth
        $sql3 = "SELECT users.username, greeks.creator FROM greeks JOIN users ON greeks.creator = users.user_id 
        WHERE greek_id = '$greek_id'";
        //Execute queries
        $result = $conn->query($sql);
        $result2 = $conn->query($sql2);
        $result3 = $conn->query($sql3);
        //Fetch results that counts the number of people in the myth
        $row2 = $result2->fetch_assoc();
        $row3 = $result3->fetch_assoc();
        //Fetch results that checks if the user is the creator of the myth
        if($result !== false && $result->num_rows > 0 && $result2 !== false && $result2->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                //If the Creator is Default, don't display the join button
                if($row['creator'] == "Default") {
                    $row['creator'] = "Greeks of the Gods";
                    echo "<div class='myth-container'>";
                    echo "<div class='myth-box'>";
                    echo "<div class='greek-name'>";
                    //If the myth has an image, display it
                    if(isset($row['image_url'])) {
                        echo "<a href='heroes.php?greek_id=" . $row['greek_id'] . "'><img src='img/gods/" . $row['image_url'] . "' alt='" . $row['name'] . "'></a>";
                    } else {
                        echo "<a href='heroes.php?greek_id=" . $row['greek_id'] . "'><img src='img/hero.png' alt='" . $row['name'] . "'></a>";
                    }
                    //Display myth info
                    echo "<h2>" . $row['name'] . "</h2>
                         <p>Creator: <strong>" . $row['creator'] . "</strong></p>
                         <p>Total people joined: " . $row2['total_people'] . "</p>";
                    echo "</div>";
                    echo "<div class='myth-info'>
                            <h3>Description</h3>
                            <div>" . $row['description'] . "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                //If the user is the creator of the myth, display the join button
                } else if($_SESSION['user_id'] == $row3['creator']) {
                    echo "<div class='myth-container'>";
                    echo "<div class='myth-box'>";
                    echo "<div class='greek-name'>";
                    //If the myth has an image, display it
                    if(isset($row['image_url'])) {
                        echo "<a href='heroes.php?greek_id=" . $row['greek_id'] . "'><img src='img/gods/" . $row['image_url'] . "' alt='" . $row['name'] . "'></a>";
                    } else {
                        echo "<a href='heroes.php?greek_id=" . $row['greek_id'] . "'><img src='img/hero.png' alt='" . $row['name'] . "'></a>";
                    }
                    echo "<h2>" . $row['name'] . "</h2>
                        <p>Creator: <strong>" . $row3['username'] . "</strong></p>
                        <p>Total people joined: " . $row2['total_people'] . "</p>";
                    echo "<a href='actions/page/deletepage.php?greek_id=" . $row['greek_id'] . "' class='myth-btn' onclick='return confirm(\"Are you sure you want to delete this myth?\")'>Delete Page</a>";
                    echo "</div>";
                    echo "<div class='myth-info'>
                            <h3>Description</h3>
                            <div>" . $row['description'] . "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                //If the Creator is not Default or the user is not the creator, display the join button
                } else {
                    //Display myth info
                    echo "<div class='myth-container'>";
                    echo "<div class='myth-box'>";
                    echo "<div class='greek-name'>";
                    //If the myth has an image, display it
                    if(isset($row['image_url'])) {
                        echo "<a href='heroes.php?greek_id=" . $row['greek_id'] . "'><img src='img/gods/" . $row['image_url'] . "' alt='" . $row['name'] . "'></a>";
                    } else {
                        echo "<a href='heroes.php?greek_id=" . $row['greek_id'] . "'><img src='img/hero.png' alt='" . $row['name'] . "'></a>";
                    }
                    echo "<h2>" . $row['name'] . "</h2>
                        <p>Creator: " . $row3['username'] . "</p>
                        <p>Total people joined: " . $row2['total_people'] . "</p>"; 
                    //Check if the user has joined the myth
                    $sql_check_joined = "SELECT * FROM user_groups WHERE user_id = '$userId' AND greek_id = '$greek_id'";
                    $result_check_joined = $conn->query($sql_check_joined);
                    if($result_check_joined !== false && $result_check_joined->num_rows > 0) {
                        echo "<a class='myth-btn' href='actions/page/leavePage_act.php?greek_id=" . $row['greek_id'] . "' class='myth-btn' onclick='return confirm(\"Are you sure you want to leave this myth?\")'>Leave Group</a>";
                    } else {
                        echo "<a class='myth-btn' href='actions/page/joinPage_act.php?greek_id=" . $row['greek_id'] . "' class='myth-btn'>Join Group</a>";
                    }    
                    echo  "</div>";
                    echo "<div class='myth-info'>
                            <h3>Description</h3>
                            <div>" . $row['description'] . "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                } 
            } 
        }

        //Show posts related to myth
        $sql = "SELECT posts.*, users.profile_pic, 
        users.username, greeks.name AS greek FROM posts 
        JOIN users ON posts.author = users.user_id 
        JOIN greeks ON posts.greek_group = greeks.greek_id WHERE posts.greek_group = ?";
        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("s", $greek_id);
        //Execute statement
        $stmt->execute();
        //Get result
        $result = $stmt->get_result();

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
                $sql2 = "SELECT COALESCE(COUNT(*), 0) AS total_comments FROM comments WHERE post_id = '" . $row['post_id'] . "'";
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

        //Close statement
        $stmt->close();

    }

    //function to get users greek group pages joined
    function getUserGroupPages($conn){
        //Write query
        $sql = "SELECT greeks.greek_id, greeks.name FROM greeks 
        JOIN user_groups ON greeks.greek_id = user_groups.greek_id
        AND user_groups.user_id = ? ORDER BY name;";

        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("s", $_SESSION['user_id']);
        //Execute statement
        $stmt->execute();
        //Get result
        $result = $stmt->get_result();


        //Check if heroes exist
        if($result !== false && $result->num_rows > 0) {
            // Display heroes
            echo "<select id='greek-select' name='greek' class='group-select'>";
                echo "<option value=''>Select Greek</option>";
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['greek_id'] . "'>" . $row['name'] . "</option>";
            }
            echo "</select>";
        } else {
            echo "No greeks found";
        }

        //Close statement
        $stmt->close();
    }

    //Function to create a page
    function createPage($conn, $title, $description, $image_url, $creator) {
        //Write queries
        $sql = "INSERT INTO greeks (greek_id, name, description, image_url, creator) VALUES (UUID(), ?, ?, ?, ?)";
        $sql2 = "INSERT INTO user_groups (id, user_id, greek_id) VALUES (UUID(), ?, ?)";
        //Prepare statements
        $stmt = $conn->prepare($sql);
        $stmt2 = $conn->prepare($sql2);

        //If no image is selected
        if($image_url == "" || empty($image_url)) {
            $image_url = null;
        }

        //Clean the description text
        $description = nl2br($description);
      
        //Check for errors
        //Check if title is empty
        if($title == "" || empty($title)) {
            header("Location: ../../user/createPage.php?titleError=The title cannot be empty");
            exit();
        }

        //Check if description is empty
        if($description == "" || empty($description)) {
            header("Location: ../../user/createPage.php?descriptionError=The description cannot be empty&title=" . $title);
            exit();
        }

        //Check if image is empty
        if(isset($_FILES['greek_image']) && !empty($_FILES['greek_image']['name'])) {

            //Insert Picture information
            $pagePictureName = $_FILES['greek_image']['name'];
            $pagePictureTmpName = $_FILES['greek_image']['tmp_name'];
            $pagePictureSize = $_FILES['greek_image']['size'];
            $pagePictureError = $_FILES['greek_image']['error'];
            //Get image extension
            $pagePictureExt = explode('.', $pagePictureName);
            $pagePictureActualExt = strtolower(end($pagePictureExt));
            //Allowed extensions
            $allowed = array('jpg', 'jpeg', 'png');

            //Check if file is an image
            
            if(in_array($pagePictureActualExt, $allowed)) {
                //Check if there is an error
                if($pagePictureError === UPLOAD_ERR_OK && $pagePictureSize > 0) {
                    //Check if file is too big
                    if($pagePictureSize < 1000000) {
                        //Set destination
                        $pagePictureDestination = '../../img/gods/' . $pagePictureName;
                        //Move file
                        move_uploaded_file($pagePictureTmpName, $pagePictureDestination);
                    //If the file is over 1mb 
                    } else {
                        header("Location: ../../user/createPage.php?imageError=File is too big!");
                        exit();
                    }
                } else {
                    header("Location: ../../user/createPage.php?imageError=There was an error uploading your file!");
                    exit();
                }
            //Bind parameters
            $stmt->bind_param("ssss", $title, $description, $pagePictureName, $creator);
            //If file is not an image
            } else {
                header("Location: ../../user/createPage.php?imageError=You cannot upload files of this type!");
                exit();
            }

        } else {
            //Set image url to null
            $image_url = null;
            //Bind parameters
            $stmt->bind_param("ssss", $title, $description, $image_url, $creator);
        }

         //Execute statement
         $stmt->execute();

         //Get greek id from greeks table to insert into user_groups
         $sql = "SELECT greek_id FROM greeks WHERE name = '$title'";
         $result = $conn->query($sql);
         $row = $result->fetch_assoc();
         $greek_id = $row['greek_id'];
         $user_id = $_SESSION['user_id'];

         //Bind parameters
         $stmt2->bind_param("ss", $user_id, $greek_id);
         //Execute statement
         $stmt2->execute();

         //Close connections
         $stmt->close();
         $stmt2->close();
         echo "<script>alert('Page created!');
                    window.location.href = '../../index.php';
                </script>";
    }

    //Function to join a page
    function joinPage($conn, $userId, $greekId) {
        //Write query
        $sql = "INSERT INTO user_groups (id, user_id, greek_id) VALUES (UUID(), ?, ?)";
        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("ss", $userId, $greekId);
        //Execute statement
        $stmt->execute();
        //Close connections
        $stmt->close();
        echo "<script>alert('Page joined successfully!');
                window.location.href = '../../heroes.php?greek_id=" . $greekId . "';
            </script>";
    }

    //Function to leave a page
    function leavePage($conn, $userId, $greekId) {
        //Write query
        $sql = "DELETE FROM user_groups WHERE user_id = ? AND greek_id = ?";
        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("ss", $userId, $greekId);
        //Execute statement
        $stmt->execute();

        //Delete all posts from the page
        $sql = "DELETE FROM posts WHERE greek_group = ? AND author = ?";
        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("ss", $greekId, $userId);
        //Execute statement
        $stmt->execute();

        //Close connections
        $stmt->close();
        echo "<script>alert('Page left successfully!');
                window.location.href = '../../heroes.php?greek_id=" . $greekId . "';
            </script>";
    }

    //Function to delete a page
    function deletePage($conn, $greekId) {
        //Get the creator of the page
        $sql = "SELECT creator FROM greeks WHERE greek_id = ?";
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("s", $greekId);
        //Execute statement
        $stmt->execute();
        //Get result
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $userId = $row['creator'];

        //If there is an image to delete
        if(isset($row['image_url'])) {
            //Delete image from img folder
            $sql = "SELECT image_url FROM greeks WHERE greek_id = ?";
            //Prepare statement
            $stmt = $conn->prepare($sql);
            //Bind parameters
            $stmt->bind_param("s", $greekId);
 
            //Execute statement
            $stmt->execute();
            //Get result
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $image_url = $row['image_url'];
            //Delete image from img folder

            //Check if there is an image to delete
            if($image_url != null) {
                unlink("../../img/gods/" . $image_url);
            }
        }

        //Write query to delete page
        $sql = "DELETE FROM greeks WHERE creator = ? AND greek_id = ?";
        //Prepare statement
        $stmt = $conn->prepare($sql);
        //Bind parameters
        $stmt->bind_param("ss", $userId, $greekId);
        //Execute statement
        $stmt->execute();

       
        //Close connection
        $stmt->close();
        echo "<script>alert('Page deleted successfully!');
                window.location.href = '../../index.php';
            </script>";
    }

?>