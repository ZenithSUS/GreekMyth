<?php
    //Include db
    include "../../db.php";

    //Initialize session
    session_start();

    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }

    //Get search input
    $searchQuery = $conn->real_escape_string($_GET['q']);

    //Write query
    $sql = "SELECT greek_id, name, image_url FROM greeks WHERE name LIKE CONCAT('%', ?, '%') AND status = 1";
    //Prepare and Bind
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchQuery);
    //Execute Query
    $stmt->execute();
    //Get Result
    $result = $stmt->get_result();

    //Check if there are any results
    //Get number of rows
    if($result !== false && $result->num_rows > 0 && $searchQuery !== "") {
        while($rows = $result->fetch_assoc()) {
            $greek_id = $rows['greek_id'];
            $name = $rows['name'];
            $image_url = $rows['image_url'];
            if($image_url == null) {
                echo "<a class='item' href='heroes.php?greek_id=$greek_id' data-greek-id='$greek_id'> 
                <img src='img/hero.png' alt='user'> $name</a>";
            } else {
                echo "<a class='item' href='heroes.php?greek_id=$greek_id' data-greek-id='$greek_id'> 
                <img src='img/gods/$image_url' alt='user'> $name</a>";
            }
           
        }
    } else {
        echo "<p class='item'>No results found</p>";
    }
    //Close connection
    
    //Return result
    return $result;

?>