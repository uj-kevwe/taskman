<?php
	include "../db/setup.php";
	$conn->query("use $database");
	$category_name = $_GET["category"];
	$found = "yes";
	while($found == "yes"){
	    $catid = rand(00000,99999);
	    $catid = str_pad($catid,5,"0",STR_PAD_LEFT);
	    $sql = "select * from categories where catid = '$catid'";
	    $result = $conn->query($sql);
	    if($result->num_rows > 0){
	        $found = "yes";
	    }
	    else{
	        $found = "no";
	    }
	}
	
	
	$sql = "insert into categories (catid,category) values ('$catid','$category_name')";
	$result = $conn->query($sql);
	
	if($conn->error){
	    echo "Error encountered while attempting to create categor $category_name <hr>($conn->error)";
	}
	else{
	    echo "New category created successfully";
	}