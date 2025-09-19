<?php
	include "../db/setup.php";
	
	$jobid = $_GET["jobid"];
	echo "<input type = 'hidden' id = 'assigned_job_id' value = '$jobid'>";
	$hours = $_GET["hours"];
	$days = $_GET["days"];
	$weeks = $_GET["weeks"];
	$months = $_GET["months"];
	
	//calculate the duration in days and hours
	$duration_hours = intval($hours) + intval($days)*24 + intval($weeks)*7*24 + intval($months)*30*24;
	$duration_days = floatval($duration_hours/24);
	
	$conn->query("use $database");
	$sql = "update jobs set job_duration_days = $duration_days, job_duration_hours = $duration_hours where jobid = '$jobid'";
	$result = $conn->query($sql);
	
	if(!$conn->error){?>
	    <div>
	        <p>Select a Job Category or Create a New One</p>
	        <select id = "jobcat" class = "form-control" onchange = "setNewCat()" > 
	            <option value = "0">Select A Category</option>
	            <option value = "newcat">Create a New Category</option>
	            <?php
	                $sql = "select * from categories";
	                $result = $conn->query($sql);
	                if($result->num_rows > 0){
	                    while($rows = $result->fetch_assoc()){
	                        $cat = $rows["category"];
	                        $catid = $rows["catid"];
	                        echo "<option value = '$catid'>$cat</option>";
	                    }
	                } 
	            ?>
	        </select>
	        <input type = "hidden" class = "form-control" id = "new_category" placeholder = "Type A New Category Name" style = "margin: 10px;">
	        .
	        <p>Choose a Supervisor for This Job</p>
	        <select class = "form-control" id = "job_supervisor">
	            <option value = "0">Choose A Supervisor</option>
	            <?php
	                $sql = "select staff.*, users.* from staff inner join users on staff.staffid = users.userid where users.roleid = '00006'";
	                $result = $conn->query($sql);
	                
	                if($result->num_rows > 0){
	                    while($rows = $result->fetch_assoc()){
	                        $staffid = $rows["staffid"];
	                        $staffname = $rows["firstname"]." ".$rows["middlename"]." ".$rows["lastname"];
	                        echo "<option value = '$staffid'>$staffname</option>";
	                    }
	                }
	            ?>
	        </select>
	        
            	<button class = 'btn btn-success' onclick = 'assign_request();'>Assign Job</button>
	    </div>   
<?php	
	    
	}
?>