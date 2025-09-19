<?php
    // include "../db/connect.php";
    session_start();
    include "../db/setup.php";
    $user_id = $_POST["user_id"];
    $_SESSION["user_id"] = $user_id;
    $user_passwd = $_POST["user_passwd"];
    $user_role = $_POST["user_role"];
    
    print_r($_POST);;
    $c = new crudOps();
 
    $where = "userid = '$user_id'";
    $this_user = $c->readFilteredData($conn,$database,"users",$where);
    echo "User Role $user_role";
    
    if($this_user->num_rows > 0){
        while($rows = $this_user->fetch_assoc()){
        
            if(password_verify($user_passwd,$rows["password"])){
            	echo "<hr>DB role ".$rows["roleid"];
                if($user_role == $rows["roleid"]){
                    if($user_role == $rows["roleid"]){
                        switch ($user_role){
                            case "00001":
                                $where2 = "staffid = '$user_id'";
                                $this_person = $c->readFilteredData($conn,$database,"staff",$where2);
                                if($this_person->num_rows > 0){
                                    while($r = $this_person->fetch_assoc()){
                                        $_SESSION["person"] = $r["firstname"]." ".$r["middlename"]." ".$r["lastname"];
                                        $_SESSION["department"] = $r["deptid"];
                                    }
                                }
                                header("Location:../dashboards/admin/");
                                break;
                            case "00002":
                                $where2 = "clientid = '$user_id'";
                                $this_person = $c->readFilteredData($conn,$database,"clients",$where2);
                                if($this_person->num_rows > 0){
                                    while($r = $this_person->fetch_assoc()){
                                        $_SESSION["person"] = $r["firstname"]." ".$r["middlename"]." ".$r["lastname"];
                                        $_SESSION["department"] = "Client";
                                    }
                                }
                                header("Location:../dashboards/client");
                                break;
                            case "00003":
                                $where2 = "staffid = '$user_id'";
                                $this_person = $c->readFilteredData($conn,$database,"staff",$where2);
                                if($this_person->num_rows > 0){
                                    while($r = $this_person->fetch_assoc()){
                                        $_SESSION["person"] = $r["firstname"]." ".$r["middlename"]." ".$r["lastname"];
                                        $_SESSION["department"] = $r["deptid"];
                                    }
                                }
                                header("Location:../dashboards/handler");
                                break;
                            case "00004":
                                $where2 = "staffid = '$user_id'";
                                $this_person = $c->readFilteredData($conn,$database,"staff",$where2);
                                if($this_person->num_rows > 0){
                                    while($r = $this_person->fetch_assoc()){
                                        $_SESSION["person"] = $r["firstname"]." ".$r["middlename"]." ".$r["lastname"];
                                        $_SESSION["department"] = $r["deptid"];
                                    }
                                }
                                header("Location:../dashboards/supervisor/");
                                break;
                            default:
                                $_SESSION["invalid_login"] = "Please select your role";
                                header("Location:../");
                        }
                    }
                    else{
                        $_SESSION["invalid_login"] = "<p style = 'color:red'>You have no rights to this $user_role dashboard</p>";
                    }
                }
                else{
                    $_SESSION["invalid_login"] = "<p style = 'color:red'>Wrong User Profile Selected!</p>";
                    header("Location:../");
                }
            }
            else{
                $_SESSION["invalid_login"] = "<p style = 'color:red'>Wrong Password</p>";
                header("Location:../");
            }
        }
    }
    else{
        $_SESSION["invalid_login"] = "<p style = 'color:blue'>No User ID or Email matched! Please type your correct user id</p>";
        header("Location:../");
    }
    
