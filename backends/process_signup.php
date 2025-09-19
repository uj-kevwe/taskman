<?php
    include "../db/setup.php";
    $c = new crudOps();
    
    //add new user to clients and users table
    $fullnames = $_POST["fullnames"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];
    $password1 = $_POST["password1"];
    $password2 = $_POST["password2"];
    $role = $_POST["client_role"];
    $roleid = "";
    $address = $_POST["address"];
  
    // get the roleid
    $where1 = "role = '$role'";
    $allroles = $c->readFilteredData($conn,$database,"roles",$where1);
    if($allroles->num_rows > 0){
        while($rows = $allroles->fetch_assoc()){
            $roleid = $rows["roleid"];
        }
    }
    //hash the password
    $pass = password_hash($password1,PASSWORD_DEFAULT);
    
    $users_fields = array("userid","roleid","email","telephone","password");
    $users_values = array(array("$username","$roleid","$email","$phone","$pass"));
    
    $c->addRecords($conn,$database,"users",$users_fields,$users_values);
    
    //separate fullnames
    $clientnames = explode(" ",$fullnames);
    $firstname = $clientnames[0];
    if(sizeof($clientnames) > 2){
        $middlename = $clientnames[1];
        $lastname = $clientnames[2];
    }
    else{
        $middlename = "";
        $lastname = $clientnames[1];
    }
    
    $picture = $_FILES["user_image"]["name"];
    $date_joined = date("Y-m-d");
    
    $clients_fields = array("clientid","firstname","middlename","lastname","email","gender","address","picture","date_joined");
    $clients_values = array(array("$username","$firstname","$middlename","$lastname","$email","$gender","$address","$picture","$date_joined"));

    $returned = $c->addRecords($conn,$database,"clients",$clients_fields,$clients_values);
    
    if(str_contains($returned,"successfully")){
    	// upload photo
    	$image_name = $_FILES["user_image"]["name"];
    	$tmp_name = $_FILES["user_image"]["tmp_name"];
    	$error = $_FILES["user_image"]["error"];
    	$size = $_FILES["user_image"]["size"];
    	$type = $_FILES["user_image"]["type"];
    	
    	$location = "../assets/images/clients";
	if(!is_dir($location)){
		mkdir($location);
	}
	$location = $location."/".$image_name;
	
    	if($error == 0){
	    	if($type == "image/jpeg" || $type == "image/jpg"){ // only .jpg or .jpeg allowed
	    		if($size <= 419430400){ // Less than or equals to 50MB
	    			//upload the photo
				if(move_uploaded_file($tmp_name,$location)){
	    				echo "<script>
			            		alert('Your Registration was successful');
			            		location.replace('../');
			          	</script>";
	    			}
	    		}
	    		else{
	    			echo "<script>
		            		alert('Your Image is too large. Upload images less than 50MB');
		            		location.replace('../');
		          	</script>";
	    		}
	    	}
	    	else{
	    		echo "<script>
	            		alert('Invalid Image Type. Upload only .jpg or .jpeg files');
	            		location.replace('../');
	          	</script>";
	    	}
	 }	
    }
    
    
        
   
	
	