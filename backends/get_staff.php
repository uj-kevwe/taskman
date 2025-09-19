<?php
    include "../db/setup.php";
    $staffname = $_GET["staffname"];

    $staffnames = explode(" ",$staffname);

    $lastname = $staffnames[0];

    $where = "lastname like '%$lastname%'";
    $c = new crudOps();
    $staff = $c->readFilteredData($conn,$database,"staff",$where);

    if($staff->num_rows > 0){
        while($rows = $staff->fetch_assoc()){
            echo "<button type = 'button' class = 'btn btn-link' onclick = 'setStaff(this.innerHTML)' style = 'display:block'>".
            $rows["firstname"]." ".$rows["middlename"]." ".$rows["lastname"].
            "</button>";
        }
    }
    else{
        echo "New Staff Name";
    }