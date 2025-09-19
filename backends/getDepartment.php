<?php
    include "../db/setup.php";
    $department = $_GET["department"];

    $c = new crudOps();
    $where = " department = '$department'";
    $all_depts = $c->readFilteredData($conn,$database,"departments",$where);
    if($all_depts->num_rows > 0){
        while($rows = $all_depts->fetch_assoc()){
            echo "<p style = 'color:red'>".$rows["department"]." department exists already</p>";
        }
    }