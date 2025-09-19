<?php
    include "../db/setup.php";
    // print_r($_GET);
    $job_title = $_GET["job_title"];
    $catid = $_GET["job_cat"];
    $tasks = $_GET["tasks"];
    $weeks = $_GET["weeks"];
    $days = $_GET["days"];
    $hours = $_GET["hours"];
    $mins = $_GET["mins"];
    $secs = $_GET["secs"];
    
    
    $flag = "yes";
    while($flag == "yes"){
        $jobid = rand(00000,99999);
        $sql = "select * from $database.jobs where jobid = '$jobid'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $flag = "yes";
        }
        else{
            $flag = "no";
        }
    }
    $sql = "insert into $database.jobs (jobid,catid,job_title,tasks_title,duration_weeks,duration_days,duration_hours,duration_minutes,duration_seconds) 
            values ('$jobid','$catid','$job_title','$tasks','$weeks','$days','$hours','$mins','$secs')";
    $result = $conn->query($sql);

    if(!$conn->error){
        echo "Job ".$job_title." created Successfully";
    }
    else{
        echo "Error creating Job $job_title<br>$conn->error";
    }
    
