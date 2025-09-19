<?php
    session_start();
    include "../db/setup.php"; 
    $supervisor = $_SESSION["user_id"];
    $conn->query("use $database"); 

    if($_POST["submit-type"] == "create-single"){
        $client = array();
        $jobid = $_POST["new-job-name"];
        $task = $_POST["task-title"];
        $handler = $_POST["task_handler"];
        $date = $_POST["date-in"];
        $time = date("h:i:s");
        $job_date = date_format(date_create("$date $time"),"Y-m-d h:i:s");
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $requestid = $jobid.substr($email,0,strpos($email,"@"));
        $client_pass = password_hash($_POST["auto-pass"],PASSWORD_DEFAULT);
        $firstname = $_POST["firstname"];
        $middlename = $_POST["middlename"];
        $lastname = $_POST["lastname"];
        $address = $_POST["address"];
        $gender = $_POST["gender"];
        $duration_weeks = $_POST["duration_weeks"];
        $duration_days = $_POST["duration_days"];
        $duration_hours = $_POST["duration_hours"];
        $duration_mins = $_POST["duration_mins"];
        $duration_secs = $_POST["duration_secs"];
        $remarks = $_POST["remarks"];
        $time_assigned = date("Y-m-d h:i:s");
        $clientid = substr($firstname,0,1).$lastname;

        array_push(
            $client,array(
                    "email"=>$email,
                    "phone"=>$phone,
                    "firstname"=>$firstname,
                    "middlename"=>$middlename,
                    "lastname"=>$lastname,
                    "password"=>$client_pass,
                    "address"=>$address,
                    "gender"=>$gender,
                    "password"=>$client_pass,
                    "clientid"=>$clientid
                )
        );
        
        $sql = "insert into requests
                    (requestid, jobid,clientid,job_date,supervised_by,job_status_id)
                    values
                    ('$requestid','$jobid','$clientid','$job_date','$supervisor','00001')
                ";
        $result = $conn->query($sql);
        if($conn->error){
            echo $conn->error;
        }
        $sql = "select * from tasks where requestid = '$requestid'";
        $result = $conn->query($sql);
     //   echo "<hr>";
     //   echo $result->num_rows."<hr>";
        $taskid = strval($requestid).":".str_pad($result->num_rows + 1,4,"0",STR_PAD_LEFT);
    //    echo "Task ID is: ". $taskid;
        $sql = "insert into tasks 
                    (taskid,requestid,task_name,supervised_by,time_assigned,prev_handler_id,current_handler_id,
                    duration_weeks,duration_days,duration_hours,duration_minutes,duration_seconds,task_status_id)
                    values 
                    ('$taskid','$requestid','$task','$supervisor','$time_assigned','$supervisor','$handler',
                    '$duration_weeks','$duration_days','$duration_hours','$duration_mins','$duration_secs','00001')";
        $result = $conn->query($sql);
        if($conn->error){
            echo "<hr>$conn->error";
        }
        else{ 
            createClient($conn,$database,$client,$_FILES["client-photo"] );
            echo "
                <script>
                    alert('Task Assigned to Handler');
                    location.replace('../dashboards/supervisor/');
                </script>
            ";
        }
    }
    else if($_POST["submit-type"]== "upload-multiple"){

    }

    function createClient($conn,$database,$client,$picture){
        $fname = $client[0]["firstname"];
        $mname = $client[0]["middlename"];
        $lname = $client[0]["lastname"];
        $gender = $client[0]["gender"];
        $email = $client[0]["email"];
        $phone = $client[0]["phone"];
        $address = $client[0]["address"];
        $password = $client[0]["password"];
        $newclientid = $client[0]["clientid"];
        $sql = "select * from $database.users where userid = '$newclientid'";
        $result = $conn->query($sql);

        if($result->num_rows == 0){
            // upload photoimage
            $image_name = $picture["name"];
            $tmp_name = $picture["tmp_name"];
            $size = $picture["size"];
            $type = $picture["type"];
            $error = $picture["error"];
            $folder = "../assets/images/clients/";
            if(!is_dir($folder)){
                mkdir($folder);
            }
            $destination_file = $folder.$image_name;
            $types = array("image/jpg","image/jpeg","image/png","image/gif");
            if($error == 0){
                if($size < 10800000){
                    if(in_array($type,$types)){
                        move_uploaded_file($tmp_name,$destination_file);
                    }
                }
            }
            $sql = "insert into $database.clients
                    (clientid,firstname,middlename,lastname,gender,address,picture)
                    values
                    ('$newclientid','$fname','$mname','$lname','$gender','$address','$destination_file')
                    ";
            $conn->query($sql);

            // add to users table
            $sql = "insert into $database.users
                    (userid,roleid,email,telephone,password)
                    values
                    ('$newclientid','00002','$email','$phone','$password')
                    ";
            $conn->query($sql);

            return $newclientid;
        }  
    }
?>