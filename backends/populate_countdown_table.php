<?php

    include "../db/setup.php";
    $conn->query("use $database");
    $jobid = $_GET["jobid"];
    
    $data = array();
    $sql = "select * from jobs where jobid = '$jobid'";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0){
        while($rows = $result->fetch_assoc()){
        	if($rows["assigned_date"] != NULL){
	            $datetime = strtotime($rows["assigned_date"]);
	            $fromdate = date("Y-m-d h:i:s",$datetime);
	            $date = date("Y-m-d",$datetime);
	            $time = date("h:i:s",$datetime);
	            $duration_hours = $rows["job_duration_hours"];
	            
	            $from = new DateTime($fromdate);
		    $from->add(new DateInterval('PT' . $duration_hours . 'H'));
		    $completion_day = $from->format('Y-m-d H:i:s');
		    $completion_date = substr($completion_day,0,10);
		    $completion_time = substr($completion_day,11);
	            array_push($data,$date);
	            array_push($data,$time);
	            array_push($data,$duration_hours);
	            array_push($data,$completion_date);
	            array_push($data,$completion_time);
	            array_push($data,$rows["assigned_date"]);
	       }
	       else{
	       	    array_push($data,"Pending");
	            array_push($data,"Pending");
	            array_push($data,"0");
	            array_push($data,"Pending");
	            array_push($data,"Pending");
	       }
        }
    }
    
    $data = json_encode($data);
    
    echo $data;
   // echo $fromdate;