<?php
    include "../db/setup.php";
    include "messaging.php";
    $c = new crudOps();
    $m = new Message();
    $staffname = $_GET["staffname"];
    $role = $_GET["role"];
    $dept = $_GET["dept"];
    $email = $_GET["email"];
    $phone = $_GET["phone"];

    if(isset($_GET["allowed_jobs"])){
        $allowed_jobs = $_GET["allowed_jobs"];
    }
    if(isset($_GET["db_table"])){
    	$db_table = $_GET["db_table"];
    }
    $password = password_hash("defaultpassword",PASSWORD_DEFAULT);

    // get staff details
    $staff = explode(" ",$staffname);
    $fname = $staff[0];
    $mname = "";
    // print_r($_GET);
    if(sizeof($staff) == 2){
        $lname = $staff[1];
    }
    else if (sizeof($staff) > 2){
        $mname = $staff[1];
        $lname = $staff[2];
    }
    if($role == "00003"){
        $staffid = "handler".substr($fname,0,1).$lname;
    }
    else if($role == "00004"){
        $staffid = "supervisor".substr($fname,0,1).$lname;
    }

    // add to users table
    $fields = array("userid","roleid","email","telephone","password");
    $values = array(array("$staffid","$role","$email","$phone","$password"));
    $rep1 = $c->addRecords($conn,$database,"users",$fields,$values);

    // add to staff table
    $fields = array("staffid","firstname","middlename","lastname","deptid");
    $values = array(array("$staffid","$fname","$mname","$lname","$dept"));
    $rep2 = $c->addRecords($conn,$database,"staff",$fields,$values);

    if(isset($_GET["allowed_jobs"])){
        // add to supervisors or handlers table
        $fields = array("staffid","allowed_jobs_id");
        $values = array(array("$staffid","$allowed_jobs"));
        $rep3 = $c->addRecords($conn,$database,$db_table,$fields,$values);
    }
    
    
    // send mail to new user
    $message = "$fname, Uniben Welcome to Taskman App";
    $message = wordwrap($message,70);
    $headers = "From: info@gfclacademy.com.ng" . "\r\n" .
	"CC: somebodyelse@example.com";

    $return = "<ol>";
    if(strpos($rep1,"new record") !== false){
        if(strpos($rep2,"new record") !== false){
            $return .= "<li>$fname added sucessfully to database</li>";
            // if($m->sendMessage($email,"Welcome To Taskman",$headers,$message)){
            // 	$return .= "<hr>$fname notified at $email";
            // }
            // else{
            // 	$return .= "<hr>Could not notify $fname at $email";
            // }
        }
        else if(strpos($rep3,"new record") !== false){
            $return .= "<li>$name added as a Supervisor</li>";
        }
        else{
            $return .= "<li>Issues encountered adding record to Staff Table</li>";
        }
    }
    else{
        $return .= "<li>Issues encountered adding record to Users Table</li>";
    }
    $return .= "</ol>";
    
    
    
    echo $return;
    