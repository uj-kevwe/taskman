<?php
	include "../db/connect.php";
	$conn->query("use $database");
	$job_name = $_GET["request_name"];
	 $job_id = get_job_id();
	 $ownerid = $_GET["user_id"];
	 $date = date("Y-m-d");
	
	function get_job_id(){
	include "../db/setup.php";
	$conn->query("use $database");
    	$jobid = rand(00000,99999);
    	$jobid = str_pad($jobid,"0",STR_PAD_LEFT);
    	$sql = "select * from requests where jobid = '$jobid'";
    	$result = $conn->query($sql);
    	if($result->num_rows > 0){
    	    get_job_id();
    	}
    	else{
    	    return $jobid;
    	}
	 }
	 
	 
	 
	 $sql = "insert into requests (jobid,Task,ownerid,job_date,job_status_id)  values ('$job_id','$job_name','$ownerid','$date','00001')";
	 $conn->query($sql);
	 
	 if($conn->error){
	     echo "<p style = 'color:red'>Error Submitting This Request <br>".$conn->error."</p>";
	 }
	 else{
	     echo "<p style = 'color:blue'>New Request Submitted Successfully</p>";
	 }
	 
	

?>