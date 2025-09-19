<?php
	include "../db/setup.php";
	$conn->query("use $database");
	$jobid = $_GET["jobs"];
	
	$sql = "select  users.*, staff.*, handlers.* from users
		inner join staff on users.userid = staff.staffid 
		inner join handlers on staff.staffid = handlers.staffid";
		
	$result = $conn->query($sql);
	$handlers = "<select id = 'task-handler' name = 'task_handler'>";
	if($result->num_rows > 0){
	     $handlers .= "<option value = '0'>Select A Handler</option>";
	     while($rows = $result->fetch_assoc()){
	     	$allowed_jobs = explode(",",$rows["allowed_jobs_id"]);
	     	if(in_array($jobid,$allowed_jobs)){
	     	   $handler_name = $rows["firstname"]." ".$rows["middlename"]." ".$rows["lastname"];
	     	   $handlerid = $rows["userid"];
	     	   $handlers .= "<option value = '$handlerid'>$handler_name</option>";
	     	}
	     }
	}
	else{
	    $handlers .= "<option value = '-1'>No Handler assigned for this job</option>";
	}
	$handlers .= "</select>";
	echo $handlers;
	// echo $allowed_jobs[0]." -- ".$jobid;