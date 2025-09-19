<?php

    include "../db/setup.php";
    $conn->query("use $database");
    $taskid = $_GET["taskid"];
    $next_handler = $_GET["next_handler"];
    $next_task = $_GET["next_task"];
    $time_assigned = date("Y-m-d h:i:s");
    $sql = "select * from tasks where taskid = '$taskid'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while($rows = $result->fetch_assoc()){
            $requestid = $rows["requestid"];
            $task_name = $rows["task_name"];
            $supervised_by = $rows["supervised_by"];
            $prev_handler = $rows["prev_handler_id"];
        }
    }
    $requests = $conn->query("select * from requests where requestid = '$requestid'");
    if($requests->num_rows > 0){
        while($rows = $requests->fetch_assoc()){
            $jobid = $rows["jobid"];
            $time_submitted = $rows["job_date"];
        }
    }
    $sql = "select * from jobs where jobid = '$jobid'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while($rows = $result->fetch_assoc()){
            $tasks = $rows["tasks_title"];
            $dw = $rows["duration_weeks"];
            $dd = $rows["duration_days"];
            $dh = $rows["duration_hours"];
            $dm = $rows["duration_minutes"];
            $ds = $rows["duration_seconds"];
        }
    }
    // echo $tasks;
    $tasks_titles = explode(",",$tasks);
    $remaining_weeks = explode(",",$dw);
    $remaining_days = explode(",",$dd);
    $remaining_hours = explode(",",$dh);
    $remaining_minutes = explode(",",$dm);
    $remaining_seconds = explode(",",$ds);
    $rt = "";
    $rw = "";
    $rd = "";
    $rh = "";
    $rm = "";
    $rs = "";
    for($i = 0; $i < sizeof($tasks_titles);$i++){
        if($tasks_titles[$i] <> $task_name){
            $rt .= $tasks_titles[$i];
            $rw .= $remaining_weeks[$i];
            $rd .= $remaining_days[$i];
            $rh .= $remaining_hours[$i];
            $rm .= $remaining_minutes[$i];
            $rs .= $remaining_seconds[$i];
            if($i < sizeof($tasks_titles)-1){
                $rt .= ",";
                $rw .= ",";
                $rd .= ",";
                $rh .= ",";
                $rm .= ",";
                $rs .= ",";
            }
            
        }
    }

    //set new taskid
    $result = $conn->query("select * from tasks where taskid = '$taskid'");
    $newtaskid = $requestid.":".str_pad($result->num_rows+1,4,"0",STR_PAD_LEFT);

    $sql = "insert into tasks 
            (taskid,requestid,task_name,supervised_by,time_assigned,prev_handler_id,current_handler_id,
            duration_weeks,duration_days,duration_hours,duration_minutes,
            duration_seconds,task_status_id)
            values
            ('$newtaskid','$requestid','$next_task','$supervised_by','$time_assigned',
            '$prev_handler','$next_handler','$rw','$rd','$rh','$rm','$rs','00001');
            ";
    $result = $conn->query($sql);
    if(!$conn->error){
        //update the previous task status to completed
        $sql1 = "update tasks set task_status_id = '00005', time_status_changed = '$time_assigned',
                job_completed_status = 1 
                where taskid = '$taskid'";
        $result1 = $conn->query($sql1);

        if(!$conn->error){
            echo "Task Completed And Moved To Next Handler!";
        }
        else{
            echo $conn->error;
        }
    }
    else{
        echo $conn->error;
    }