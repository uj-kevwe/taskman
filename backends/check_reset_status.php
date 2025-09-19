<?php
    session_start();
    include "../db/setup.php";
    $userid = $_GET["userid"];

    // retrieve email
    if(strpos($userid,"@") === false){
        $sql = "select * from $database.users where userid = '$userid'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($rows = $result->fetch_assoc()){
                $email = $rows["email"];
            }
        }
    }
    else{
        $email = $userid;
    }

    // check reset status
    $sql = "select * from $database.reset_codes where email = '$email'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $reset_status = 1;
    }
    else{
    	$reset_status = 0;
    }

     echo $reset_status;
    
