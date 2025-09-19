<?php

    include "../db/setup.php";
    $email = $_GET["email"];
    $reset_code = rand(000000,999999);
    $email_body = "<html><body>
                        <p>Use This 6-digit Code below to reset your Password</p>
                        <h1>$reset_code</h1>
                   </body></html>";  
    $subject = "Reset Code";  

    mail($email,$subject,$email_body);
    if(mail($email,$subject,$email_body)){
        // add reset code to reset_codes table
        $reset_time = date("Y-m-d h:i:s");
        $sql = "select * from $database.reset_codes where email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $sql1 = "update $database.reset_codes set code = '$reset_code', time = '$reset_time' where email = '$email'";
        }
        else{
            $sql1 = "insert into $database.reset_codes 
                    (email,code,time) values 
                    ('$email','$reset_code','$reset_time')";
        }
        $result1 = $conn->query($sql1);
        if(!$conn->error){
            echo "Reset Code Sent to $email";
        }
        else{
            echo "Error sending email: <br>".$conn->error;
        }
    }
    else{
        echo "Error Sending Mail. Retry again. <br>If Error persists, contact the Administrator";
    }