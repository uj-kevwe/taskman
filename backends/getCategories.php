<?php
	include "../db/setup.php";
	$category_name = $_GET["category"];
	
	$c = new crudOps();
	$where = " category like '%$category_name%'";
	$all_categories = $c->readFilteredData($conn,$database,"categories",$where);
	
	if($all_categories->num_rows > 0){
	    echo "<p style = 'text-decoration:underline;font-weight:bolder;margin:10px 0px 3px 0'>Existing Categories</p>";
	    echo "<ol>";
	    while($rows = $all_categories->fetch_assoc()){
	        $catid = $rows["catid"];
	        $category = $rows["category"];
	        echo "<li>$category</li>";
	    }
	    echo "</ol>";
	}