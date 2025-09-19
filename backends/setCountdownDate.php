<?php
    include "../db/setup.php";
    $conn->query("use $database");
    $taskid = $_GET["taskid"];

    // $sql = ;
    $task = $conn->query("select * from tasks where taskid = '$taskid'");
    if($task->num_rows > 0){
        while($rows = $task->fetch_assoc()){
            $requestid = $rows["requestid"];
        }
    }

    $request = $conn->query("select * from requests where requestid = '$requestid'");
    if($request->num_rows > 0){
        while($rows = $request->fetch_assoc()){
            $job_date = $rows["job_date"];
            $jobid = $rows["jobid"];
        }
    }

    //  = array();
    $sql = "select * from jobs where jobid = '$jobid'";
    $job = $conn->query($sql);
    if($job->num_rows > 0){
        while($rows = $job->fetch_assoc()){
            $weeks = explode(",",$rows["duration_weeks"]);
            $days = explode(",",$rows["duration_days"]);
            $hours = explode(",",$rows["duration_hours"]);
            $minutes = explode(",",$rows["duration_minutes"]);
            $seconds = explode(",",$rows["duration_seconds"]);
        }
    }
    $wk = $dy = $hr = $min = $sec = 0;
    for($i = 0; $i < sizeof($weeks); $i++){
        $wk += intval($weeks[$i]);
        $dy += intval($days[$i]);
        $hr += intval($hours[$i]);
        $min += intval($minutes[$i]);
        $sec += intval($seconds[$i]);
    }
    $total_hours = $wk*7*24 + $dy*24 + $hr + $min/(60) + $sec/(3600);
    $total_secs = $total_hours*3600;
    $job_date = date_create(strval($job_date));
    $count_to = date_modify($job_date,"+ $total_secs secs");
    echo date_format($count_to,"Y-m-d h:i:s");  