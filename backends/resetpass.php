<?php
    include "../db/setup.php";
    print_r($_POST);
    $pwd1 = $_POST["pwd1"];
    $pwd2 = $_POST["pwd2"];
    $userid = $_POST["userid"];
    $reset_code = $_POST["reset_code"];
    echo "<input type = 'hidden' id = 'user_id' value = '$userid'>";

    
    if($pwd1 != $pwd2){
        echo "
            <script>
                userid = document.getElementById('user_id').value;
                alert('Both Password Fields Must Match');
                // location.replace('../forgotten_password.php?user_id='+userid);
            </script>
        ";
    }
    else{
        $sql = "select * from $database.users where userid = '$userid'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($rows = $result->fetch_assoc()){
                $email = $rows["email"];
            }
        }
        $sql = "select * from $database.reset_codes where email = '$email' and code = '$reset_code'";
        $result = $conn->query($sql);
        // check if invalid
        // if($result->num_rows > 0){
        if($result->num_rows == 0){
            while($rows = $result->fetch_assoc()){
                // check if expired
                echo "<hr>Last Reset Link Time: ".$rows["time"];
                echo "<hr>Now: ".date("Y-m-d h:i:s")."<hr>";
                $reset_time = new DateTime($rows["time"]);
                $now = new DateTime(date("Y-m-d h:i:s"));
                $diff = $now->diff($reset_time);
                
                $total_minutes = ($diff->days * 24 * 60); 
                $total_minutes += ($diff->h * 60); 
                $total_minutes += $diff->i; 
                // echo $total_minutes;
            }
            // echo "Reset Time Difference: $diff";
            if($total_minutes > 10){
            	echo "
            	     <script>
            	     	alert('This Code Has Expired');
            	     	location.replace('../');
            	     </script>
            	";
            }
            else{
	            $pass = password_hash($pwd1,PASSWORD_DEFAULT);
	            $sql1 = "update $database.users set password = '$pass'";
	            $result = $conn->query($sql1);
	            if(!$conn->error){
	                echo "
	                    <script>
	                        alert('Password Reset Successfully');
	                        location.replace('../');
	                    </script>
	                ";
	            }
	            else{
	                echo "$sql1 <hr>Error:".$conn->error;
	            }
	     }
        } 
        else{
            echo "
                <script>
                    alert('Invalid Reset Code');
                    location.replace('../forgotten_password.php?user_id='+userid);
                </script>
            ";
        } 
    }
?>