<?php
    include "../db/setup.php";

    $job_title = $_GET["job_title"];
    $catid = $_GET["job_cat"];

    $sql = "select * from $database.jobs where job_title = '$job_title' and catid = '$catid'";
    $result = $conn->query($sql);
    echo $result->num_rows."<hr>";
    if($result->num_rows > 0){
        $sql1 = "select * from $database.categories where catid = '$catid'";
        $result1 = $conn->query($sql1);
        while($rows = $result1->fetch_assoc()){
            $category = $rows["category"];
        }
        echo "Job '$job_title' already exists for Category '$category'";
    }
    else{
        echo "proceed with <br> $sql";
    }
    