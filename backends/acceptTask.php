<?php
    session_start();
    $userid = $_SESSION["user_id"];
    include "../db/setup.php";

    $conn->query("use $database");
    $taskid = $_GET["taskid"];
    $change_time = date("Y-m-d h:i:s");
    $sql = "update tasks set task_status_id = '00002', time_status_changed = '$change_time'
            where taskid = '$taskid'";
    $result = $conn->query($sql);

    if(!$conn->error){
        $sql1 = "select * from tasks where taskid = '$taskid'";
        $result1 = $conn->query($sql1);
        if($result1->num_rows > 0){
            while($rows1 = $result1->fetch_assoc()){
                $task_title = $rows1["task_title"];
            }
        }
        header("Location:../dashboards/handler");
        $_SESSION["task_status"] = "Task $task_title Accepted";
    }
    else{
        echo $conn->error;
    }
?>