<?php
    //Start session
    session_start();
    // Include session checker
    include_once "session.php";
    // Check if the session or cookie is set
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
    <link rel="stylesheet" href="../styles/createPost.css">
    <!-- Title -->
    <title>Create Post</title>
</head>
<body>
    <?php
        //Include queries
        include "../queries/post.php";
        include "../queries/friend.php";
        include "../queries/greek.php";
        //Include db connection
        include "../db.php";
    ?>
    <nav> 
        <!-- Logo -->
        <a class="logo" href="../index.php"><img src="../img/misc/logo_transparent.png" alt="logo"></a>
            <!-- Search Bar -->
            <div class="search-bar">
                <!-- Search Input -->
                 <div class="search-input">
                    <input type="text" placeholder="Search Users" id="searchInput" data-enter-pressed="false" class="search" oninput="searchUser()">
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
            $userId = $_SESSION['user_id'];
            $result = $conn->query("SELECT * FROM users WHERE user_id = '$userId'") or die($conn->error);
            $row = $result->fetch_assoc(); 
            $profile = $row['profile_pic'];
            ?>
                <a href="profile.php?user_id=<?php echo $userId ?>">
                   <?php 
                    //Check if profile pic exists
                    if(isset($row['profile_pic'])) {
                       echo "<img src='../img/u/" . $row['profile_pic'] . "' alt='user' class='user'>";
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
                <a href="profile.php?user_id=<?php echo $userId ?>">
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
                    <input type="text" placeholder="Search User" id="searchInput-mobile" data-enter-pressed="false" class="search-mobile" oninput="MobileSearchUser()">
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
        <div class="create-content">
            <!-- Nav Links -->
            <div class="nav-links"> 
                <a href="../index.php">Home</a>
                <a href="../friends.php" class="friends">Friends
                    <!-- Notify when there is friend request -->
                    <?php
                    //Get friend request count
                    $count = getFriendRequestCountUser($conn, $userId);
                    if($count > 0) {
                        echo "<span class='notif'>" . $count . "</span>";
                    }
                    ?>
                </a>
                <a href="../heroes.php">Heroes</a>
                <a href="../actions/logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
            </div>

            <!-- Create Post Area -->
            <div class="createPost">
                <!-- Create Post Container -->
                <div class="createForm-container">
                    <!-- Heading or Title -->
                    <h2 id="postTitle">Create Post</h2>
                    <!-- Create Post Form -->
                    <form action="../actions/createPost_act.php" method="POST">
                        <!-- Title Field -->
                        <div class="form-group"> 
                            <label for="title">Title</label>
                                <input type="text" name="title" placeholder="Title">
                            <!-- Error Message -->
                            <?php if(isset($_GET['error'])) { echo "<p class='error'>" . $_GET['error'] . "</p>"; } ?>
                        </div>
                        <!-- Group Page Field -->
                        <div class="form-group">
                            <label for="groupPage">Select Group Page</label>
                                <?php
                                    //Get group pages
                                    $groupPages = getUserGroupPages($conn);
                                ?>
                        </div>
                        <!-- Content Field -->
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" cols="30" rows="10" placeholder="Write something..."></textarea>
                        </div>
                        <!-- Submit Button -->
                        <div class="createPost-btn">
                            <button type="submit" name="createPost" id="postBtn" onclick="disableButton()">Post</button>
                            <a href="../index.php" id="backBtn">Go back</a>
                        </div>
                    </form>
                </div>
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

    <!--Scripts-->
    <script src="../scripts/search.js"></script>
    <script src="../scripts/burgerMenu.js"></script>
    <script>
        function disableButton() {
            document.getElementById("postBtn").disabled = true;
        }
    </script>
</body>
</html>