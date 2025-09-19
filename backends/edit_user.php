<?php
    include "../db/setup.php";
    $c = new crudOps();

    $name = $_POST["name"];
    $olduserid = $_POST["userid"];
    $newuserid = $_POST["newuserid"];
    $email = $_POST["email"];
    $department = $_POST["department"];
    $role = $_POST["role"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    if($_POST["password"] != ""){
        $password = password_hash($_POST["password"],PASSWORD_DEFAULT);
    }
    else{
        $password = "";
    }

    $where = "role = '$role'";
    $role_details = $c->readFilteredData($conn,$database,"roles",$where);

    if($role_details->num_rows > 0){
        while($rows = $role_details->fetch_assoc()){
            $roleid = $rows["roleid"];
        }
    }
    
    $where = "department = '$department'";
    $dept_details = $c->readFilteredData($conn,$database,"departments",$where);

    if($dept_details->num_rows > 0){
        while($rows = $dept_details->fetch_assoc()){
            $deptid = $rows["deptid"];
        }
    }
    
    $fields = array("userid","roleid","email","telephone","password");
    $values = array($newuserid,$roleid,$email,$phone,$password);

    // $newusers = $c->addRecords($conn,$database,"users",$fields,$values);

    $sql1 = "update $database.users set ";
    for($i = 0; $i < sizeof($fields)-1; $i++){
        $sql1 .= $fields[$i]." = '".$values[$i]."'";
        if($i < sizeof($fields)-2){
            $sql1 .= ",";
        }
    }
    if($values[sizeof($values)-1] != ""){
        $sql1 .= ",".$fields[sizeof($fields)-1]." = '".$values[sizeof($values)-1]."'";
    }
        
    $sql1 .= " where userid = '$olduserid'";
    $conn->query($sql1);

    // edit staff table
    $staffnames = explode(" ",$name);
    $firstname = $staffnames[0];

    if(sizeof($staffnames) == 2){
        $middlename = "";
        $lastname = $staffnames[1];
    }
    else if(sizeof($staffnames) == 3){
        $middlename = $staffnames[1];
        $lastname = $staffnames[2];
    }

    $fields = array("staffid","firstname","middlename","lastname","deptid");
    $values = array($newuserid,$firstname,$middlename,$lastname,$deptid);

    // $newusers = $c->addRecords($conn,$database,"staff",$fields,$values);

    $sql = "update $database.staff set ";
    for($i = 0; $i < sizeof($fields); $i++){
        $sql .= $fields[$i]." = '".$values[$i]."'";
        if($i < sizeof($fields)-1){
            $sql .= ",";
        }
    }
    $sql .= " where staffid = '$olduserid'";
    $conn->query($sql);

    echo " user edited successfully";
    // echo $sql1."<hr>".$sql;