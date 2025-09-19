<?php
    include "../db/setup.php";
    
    $username = $_GET["username"];
    $email = $_GET["email"];
    
    // check for unique username
    $where = "clientid = 'username'";
    $allclients = $c->readFilteredData($conn,$database,"clients",$where);
    if($allclients->num_rows == 0){
        // check for unique emails
        $where2 = "email = '$email'";
        $allclients2 = $c->readFilteredData($conn,$database,"clients",$where2);
        if($allclients2->num_rows == 0){
            echo "success!";
        }
        else{
            echo "<p style = 'color:red'>Email $email already exists. Please use another email</p>";
        }
    }
    else{
        echo "<p style = 'color:red'>Username $username already exists! Please choose another Username</p>";
    }
	
	