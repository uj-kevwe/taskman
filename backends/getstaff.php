<?php
    include "../db/setup.php";
    $staffname = $_GET["staffname"];

    $staffnames = explode(" ",$staffname);

    $firstname = $staffnames[0];
    if(sizeof($staffnames) == 2){
        $middlename = "";
        $lastname = $staffnames[1];
    }
    else if(sizeof($staffnames) == 3){
        $middlename = $staffnames[1];
        $lastname = $staffnames[2];
    }

    $where = "concat(firstname,' ',middlename,' ',lastname) = '$firstname $middlename $lastname'";
    $c = new crudOps();
    $staff = $c->readFilteredData($conn,$database,"staff",$where);

    if($staff->num_rows > 0){
        while($rows = $staff->fetch_assoc()){
            echo "<p style = 'color:red'>".
            $rows["firstname"]." ".$rows["middlename"]." ".$rows["lastname"]." already exists".
            "</p>";
        }
    }
    else{
        echo "New Staff Name";
    }