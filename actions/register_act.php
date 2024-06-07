<?php
    include "../db.php";
    session_start();

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(isset($_POST['submit'])) {
        //Check if fields are empty
        if(empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
            header("Location: ../auth/register.php?error=Fill all the fields&username=".$username."&email=".$email);
            exit();
        }

        //Check if email and username are valid
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            header("Location: ../auth/register.php?emailError=invalidmail&username=".$username);
            exit();
        }
        else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header("Location: ../auth/register.php?emailError=invalidmail&username=".$username);
            exit();
        }
        else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            header("Location: ../auth/register.php?userError=invalidusername&email=".$email);
            exit();
        }
        //Check if password less than 8
        else if(strlen($password) < 8) {
            header("Location: ../auth/register.php?passError=Password must be at least 8 characters&username=".$username."&email=".$email);
            exit();
        }
        //Check if passwords are equal
        else if($password !== $confirm_password) {
            header("Location: ../auth/register.php?passError=Password did not match&username=".$username."&email=".$email);
            exit();
        }
        else {
            $sql = "SELECT username FROM users WHERE username=?";
            $sql2 = "SELECT email FROM users WHERE email=?";

            $stmt = $conn->prepare($sql);
            $stmt2 = $conn->prepare($sql2);

            if(!$stmt->prepare($sql) || !$stmt2->prepare($sql2)) {
                header("Location: ../register.php?error=sqlerror");
                exit();
            }
            else {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                $stmt2->bind_param("s", $email);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                
                if($result->num_rows > 0 || $result2->num_rows > 0) {
                    if($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        if($row['username'] === $username) {
                            header("Location: ../auth/register.php?userError=User aleady exists&username=".$username."&email=".$email);
                        }
                    } 
                    if($result2->num_rows > 0) {
                        $row = $result2->fetch_assoc();
                        if($row['email'] === $email) {
                            header("Location: ../auth/register.php?emailError=Email aleady exists&username=".$username."&email=".$email);
                        }
                    }
                    exit();
                } else {
                    include "queries.php";
                    register($username, $email, $password);
                    exit();
                }
            }
            $stmt->close();
            $conn->close();
        }
    }
?>