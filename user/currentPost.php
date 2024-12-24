<?php
    // Start session
    session_start();
    // Include session checker
    include_once "session.php";
    // Check if the session is set
    if(!isset($_SESSION['user_id']) || !isset($_COOKIE['user_id'])){
        // Unset sessions
        session_unset();
        //Destroy session
        session_destroy();
        echo "<script>window.location.href = '../auth/login.php'</script>";
    } else {
        checkSessionTimeout();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../global.css">
    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/currentPost.css">
    <link rel="stylesheet" href="../styles/comment.css">
    <!-- Title -->
    <title>
        <?php 
        //Check if title is set
        if(isset($_GET['title']) && !empty($_GET['title'])){
            echo '"' . htmlspecialchars($_GET['title'], ENT_QUOTES, 'UTF-8') . '"';
        } else {
            echo 'Post';
        }
        ?>
    </title>
</head>
<body>
    <?php
    //Check if the post is set
    if(isset($_GET['post_id']) && !empty($_GET['post_id'])) {
        //Get data from url using GET method
        $postId = $_GET['post_id'];
    } else {
        header("Location: ../index.php");
    }
        
    ?>
    <?php
        //Include queries
        include "../queries/post.php";
        include "../queries/friend.php";
        include "../queries/comment.php";
        include "../queries/greek.php";
        //Include db connection
        include "../db.php";
    ?>
    <!-- Header Area -->
        <nav>
        <!-- Logo -->
        <a class="logo" href="../index.php"><img src="../img/misc/logo_transparent.png" alt="logo"></a>
            <!-- Search Bar -->
            <div class="search-bar">
                <!-- Search Input -->
                 <div class="search-input">
                    <input type="text" placeholder="Search" id="searchInput" data-enter-pressed="false" class="search" oninput="searchUser()">
                    <button class="search-button" id="searchButton">Search</button>
                </div>
                <!-- Search Results -->
                <div class="search-results-container">
                    <div id="search-results" class="search-results"></div>
                </div>
            </div>
        <!-- Profile Link -->
        <div class="profile-link">
        <?php 
            //Get user id in session
            $userId = $_SESSION['user_id'];
            // Execute query
            $result = $conn->query("SELECT * FROM users WHERE user_id = '$userId'") or die($conn->error);
            //Get profile pic from database by using user id and associative array
            $row = $result->fetch_assoc(); 
            //Get profile pic
            $profile = $row['profile_pic'];
            ?>
                <a href="profile.php?user_id=<?php echo $userId ?>">
                   <?php 
                    //Check if profile pic exists
                    if(isset($row['profile_pic'])) {
                       echo "<img src='../img/u/" . $row['profile_pic'] . "' alt='user' class='user'>";
                    // Display default image if no profile pic
                   } else { 
                    echo "<img src='../img/default.jpg' alt='user' class='user'>";
                    }?>
            <?php echo $_SESSION['username']; ?></a>
        </div>

        <!-- Burger Menu Button -->
        <div class="burger-menu-btn">
            <button class="burger-menu-icon">Menu</button>
        </div>

        <!-- Enable Burger Menu on Mobile -->
        <div class="burger-menu hidden">
            <div class="profile-link-mobile">
                <a href="../user/profile.php?user_id=<?php echo $userId ?>">
                   <?php
                        //Check if profile pic exists
                        if(isset($profile) || !is_null($profile)) {
                            echo "<img src='../img/u/" . $profile . "' alt='user' class='user'>";
                        } else { 
                            echo "<img src='../img/default.jpg' alt='user' class='user'>";
                        }?>
                <?php echo $_SESSION['username']; ?></a>
                </a>
            </div>
            <!-- Search Bar -->
            <div class="search-bar-mobile">
                <!-- Search Input -->
                 <div class="search-input-mobile">
                    <input type="text" placeholder="Search" id="searchInput-mobile" data-enter-pressed="false" class="search-mobile" oninput="MobileSearchUser()">
                    <button class="search-button-mobile" id="searchButton-mobile">Search</button>
                </div>
                <!-- Search Results -->
                <div class="search-results-mobile-container">
                    <div id="search-results-mobile" class="search-results-mobile"></div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Navbar for Mobile -->
    <div class="nav-mobile">
        <div class="nav-mobile-links">
            <a href="../index.php">Home</a>
            <a href="../friends.php" class="friends">Friends
                <!-- Notify when there is friend request -->
                <?php
                //Get friend request count
                $count = getFriendRequestCount($conn, $userId);
                if($count > 0) {
                    echo "<span class='notif'>" . $count . "</span>";
                }
            ?>
            </a>    
            <a href="../heroes.php">Heroes</a>
            <a href="../actions/logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
        </div>
    </div>
    
    <!-- Main Area -->
    <main>
        <div class="current-content">
            <!-- Nav Links -->
            <div class="nav-links"> 
                <a href="../index.php">Home</a>
                <a href="../friends.php" class="friends">Friends
                    <!-- Notify when there is friend request -->
                    <?php
                    //Get friend request count
                    $sql = "SELECT * FROM friend_requests WHERE requestee_id = ? AND status = 'pending'";
                    $stmt = $conn->prepare($sql);
                    //Bind parameter to statement
                    $stmt->bind_param("s", $userId);
                    //Execute statement
                    $stmt->execute();
                    //Get result from statement using get_result
                    $result = $stmt->get_result();
                    //Get number of rows
                    $count = $stmt->num_rows;
                    if($count > 0) {
                        echo "<span class='notif'>" . $count . "</span>";
                    }
                    //Close statement
                    $stmt->close();
                    ?>
                </a>
                <a href="../heroes.php">Heroes</a>
                <a href="../actions/logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
            </div>

        <!-- Current Post Area -->
        <div class="posts current-posts">
            <!-- Display Current Post -->
            <?php
                 //Get user id from session
                 $userId = $_SESSION['user_id'];
 
                 //Call currentPost function
                 currentPost($conn, $postId, $userId);
            ?>

            <!-- Comment Form Container -->
            <div class='comment-form'>
                <!-- Heading or Title -->
                <h2 id='commentTitle'>What are your thoughts</h2>
                <!-- Comment Form -->
                <form action='../actions/comment_act.php?post_id=<?php echo $postId ?>' method='post'>
                <!-- Comment Field -->
                <textarea name='comment' id='comment' cols='30' rows='10'></textarea>
                <!-- Submit Button -->
                <button type='submit' name='commentForm' id='commentBtn' onclick="disableCommentBtn()">Comment</button>
                </form>
            </div>

            <?php
                //Call comments function  
                comments($conn);
            ?>
                
        </div>

        <!-- Others Content Area -->
        <div class="other-content">
                <div class="other-scroll" id="other-scroll">
                    <!-- Greek Heroes Page Area -->
                    <div class="others">
                        <!-- Title -->
                        <h2>Greek Pages</h2>
                            <!-- Heroes Container -->
                            <div class="heroes">
                                <?php 
                                    //Get heroes
                                    $heroes = getGreeksUser($conn, $userId);
                                    ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </main>

    <!-- Footer Area -->
    <footer>
        <div class="footer-content">
            <a href="#top">Back to Top</a>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="../scripts/disableBtns.js"></script>
    <script src="../scripts/showReply.js"></script>
    <script src="../scripts/search.js"></script>
    <script src="../scripts/burgerMenu.js"></script>

</body>
</html>