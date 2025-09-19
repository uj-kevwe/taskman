<?php
    include "../db/setup.php";
    $conn->query("use $database");
    $taskid = $_GET["taskid"];

    $task = $conn->query("select * from tasks where taskid = '$taskid'");
    if($task->num_rows > 0){
        while($rows = $task->fetch_assoc()){
            $weeks = $rows["duration_weeks"];
            $days = $rows["duration_days"];
            $hours = $rows["duration_hours"];
            $mins = $rows["duration_minutes"];
            $secs = $rows["duration_seconds"];
            $task_date = $rows["time_assigned"];
        }
    }
    $total_hours = $weeks*7*24 + $days*24 + $hours + $mins/60 + $secs/3600;
    $total_secs = $total_hours * 3600;
    $task_date = date_create(strval($task_date));
    $count_to = date_modify($task_date,"+ $total_secs secs");
    echo date_format($count_to,"Y-m-d h:i:s");