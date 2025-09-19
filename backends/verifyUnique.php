<?php
	include "../db/setup.php";
	$field = $_GET["field"];
	if($field == "username"){
	    $field = "userid";
	}
	$value = $_GET["value"];
	$conn->query("use $database");
	$sql = "select * from users where $field = '$value'";
	$result = $conn->query($sql);
	
	if($result->num_rows > 0){
	    if($field == "userid"){
	        echo "<p style = 'color:red'>Username $value already exists!</p>";
	    }
	    else{
	        echo "<p style = 'color:red'>Email $value already exists!</p>";
	    }
	}
	
?>