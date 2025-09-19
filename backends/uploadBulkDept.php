<?php
    include "../db/setup.php";
    $c = new crudOps();
    $name = $_FILES["bulk-depts"]["name"];
    $tmp_name = $_FILES["bulk-depts"]["tmp_name"];
    $error = $_FILES["bulk-depts"]["error"];
    $size = $_FILES["bulk-depts"]["size"];
    $type = $_FILES["bulk-depts"]["type"];

    $location = "uploads/".$name;

    if($error == 0){
        if($type == "text/csv"){
            if($size < 200000){
                move_uploaded_file($tmp_name,$location);

                // read into csv file
                $file = fopen($location,"r");
                    
                
                while($line = fgetcsv($file)){
                    $all_depts = $c->readData($conn,$database,"departments");
                    $all_users = $c->readData($conn,$database,"users");
                    $all_staff = $c->readData($conn,$database,"staff");
                    $deptid = $all_depts->num_rows + 1;
                    $deptid = str_pad($deptid,5,"0",STR_PAD_LEFT);
                    if($line[0] != "Department"){
                        $dept = $line[0];
                        $fname = $line[1];
                        $mname = $line[2];
                        $lname = $line[3];
                        $email = $line[4];
                        $phone = $line[5];

                    
                        if($mname == ""){
                            $userid = "admin".$fname;
                        }
                        else{
                            $userid = "admin".$mname;
                        }
                        $roleid = "00001";
                        $password = password_hash("defaultpassword",PASSWORD_DEFAULT);

                        // add record to department
                        $fields = array("deptid","department","hodid");
                        $values = array(array("$deptid","$dept","$userid"));
                        $c->addRecords($conn,$database,"departments",$fields,$values);

                        // add record to users
                        $fields = array("userid","roleid","email","telephone","password");
                        $values = array(array("$userid","$roleid","$email","$phone","$password"));
                        $c->addRecords($conn,$database,"users",$fields,$values);

                        // add record to staff
                        $fields = array("staffid","firstname","middlename","lastname","deptid");
                        $values = array(array("$userid","$fname","$mname","$lname","$deptid"));
                        $c->addRecords($conn,$database,"staff",$fields,$values);
                    }
                }
                fclose($file);
                header("Location:../dashboards/admin/");
            }
        }
    }
