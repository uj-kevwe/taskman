<?php
    include "../db/setup.php";
    $conn->query("use $database");
    $assigned_to = $_GET["assigned_to"];
    $assigned_date = date("Y-m-d H:i:s");
    $jobid = $_GET["jobid"];
	if(isset($_GET["catid"])){
	    $catid = $_GET["catid"];
	}
	else if(isset($_GET["new_category"])){
	    $new_category = $_GET["new_category"];
	    $repeat_process = "yes";
	    while($repeat_process == "yes"){
	        $catid = rand(00000,99999);
  	        $sql = "select * from categories where catid = '$catid'";
	        $result = $conn->query($sql);
	        
	        if($result->num_rows == 0){
	            $repeat_process = "no";
	        }
	        else{
	            $repeat_process = "yes";
	        }
	    }
	    
	    // add new category to categories table
	    $conn->query("insert into categories (catid,category) values ('$catid','$new_category')");   
	}
	
	//update requests table
	$sql = "update requests set catid = '$catid', assigned_to = '$assigned_to',assigned_date = '$assigned_date', job_status_id = '00002' where jobid = '$jobid'";
	
	$conn->query($sql);
	
	if(!$conn->error){
	    echo "Request Details:\nJOB ID: $jobid\nSUPERVISOR ID: $assigned_to\nDATE ASSIGNED AND TIME: $assigned_date";
	}
	else{
	    echo "Error Encountered while Assigning Request\n$conn->error\nContact Product Developer";
	}
?>