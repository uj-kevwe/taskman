<?php
    include "../db/setup.php";
    $email = $_GET["email"];

    $c = new crudOps();
    $where = "email = '$email'";

    $all_users = $c->readFilteredData($conn,$database,"users",$where);

    if($all_users->num_rows > 0){
        echo "<p style = 'color:red'>Email already Exists!</p>";
    }
    else{
        echo "<p style = 'color:blue'>Email Available For Use!</p>";
    }