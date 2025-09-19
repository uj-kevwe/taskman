<?php
    include "../db/setup.php";
    $c = new crudOps();
    $department = $_GET["department"];
    $staffname = $_GET["staffname"];
    $staffdetails = explode(" ",$staffname);


    $all_dept = $c->readData($conn,$database,"departments");
    $dept_num = $all_dept->num_rows + 1;
    $deptid = str_pad($dept_num,5,"0",STR_PAD_LEFT);

    if(isset($_GET["staffemail"])){
        $email = $_GET["staffemail"];
        $phone = $_GET["telephone"];

        $where = "email = '$email'";
        $all_users = $c->readFilteredData($conn,$database,"users",$where);
        if($all_users->num_rows > 0){
            while($rows = $all_users->fetch_assoc()){
                $hodid = $rows["userid"];
            }
        }
        else{
            $hodid = "admin".$staffdetails[1];
        }

        // get staff details
        $staff_lname = $staffdetails[0];
        if(sizeof($staffdetails) == 2){
            $staff_fname = $staffdetails[1];
            $staff_mname = "";
        }
        else if(sizeof($staffdetails) > 2){
            $staff_fname = $staffdetails[1];
            $staff_mname = $staffdetails[2];
        }
        $roleid = "00001";
        $password = password_hash("defaultpassword",PASSWORD_DEFAULT);

        // add to users table
        $fields = array("userid","roleid","email","telephone","password");
        $values = array(array("$hodid","$roleid","$email","$phone","$password"));
        $c->addRecords($conn,$database,"users",$fields,$values);

        // add to staff table
        $fields = array("staffid","firstname","middlename","lastname","deptid");
        $values = array(array("$hodid","$staff_fname","$staff_mname","$staff_lname","$deptid"));
        $c->addRecords($conn,$database,"staff",$fields,$values);
        
    }
    else{
        $where = "concat(firstname,' ',middlename,' ',lastname) = '".$staffdetails[0]." ".$staffdetails[1]." ".$staffdetails[2]."'";
        $all_staff = $c->readFilteredData($conn,$database,"staff",$where);
        if($all_staff->num_rows > 0){
            while($rows = $all_staff->fetch_assoc()){
                $hodid = $rows["staffid"];
            }
        }
    }

    $fields = array("deptid","department","hodid");
    $values = array(array("$deptid","$department","$hodid"));
    $response = $c->addRecords($conn,$database,"departments",$fields,$values);
    echo $response;