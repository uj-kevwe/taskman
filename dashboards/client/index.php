<?php
    session_start();
    include "../../db/setup.php";
    $user_id = $_SESSION["user_id"];
    echo "<input type = 'hidden' id = 'user_id' value = '$user_id'>";
    if(isset($_SESSION["person"])){
        $conn->query("use $database");
        $sql = "select * from jobs where ownerid = '$user_id'";
        $result = $conn->query($sql);
        $assigned = 0;
        $in_progress = 0;
        $completed = 0;
        $overdue = 0;
        $registered_jobs = 0;
        if($result->num_rows > 0){
            $registered_jobs = $result->num_rows;
        	while($rows = $result->fetch_assoc()){
        	if($rows["job_status_id"] == "00002"){
        	   $assigned++;
        	}
                else if($rows["job_status_id"] == "00003"){
                    $in_progress++;
                }
                else if($rows["job_status_id"] == "00004"){
                    $completed++;
                }
                else if($rows["job_status_id"] == "00005"){
                    $overdue++;
                }
            }
        }
        else{
            $table_content = "You Haven't Submitted Any Request Yet";
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Clients Dashboard - Request Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <script src="../assets/bootstrap/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f9fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background-color: white;
            border-right: 1px solid #e0e0e0;
            padding: 20px 15px;
        }
        .sidebar .nav-link {
            font-weight: 500;
            font-size: 16px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #eaf1ff;
            color: #2a63e7;
        }
        .content {
            padding: 40px 30px;
        }
        .card-stats {
            padding: 20px;
            border-radius: 12px;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: center;
        }
        .card-stats h3 {
            font-size: 28px;
            font-weight: bold;
            color: #2a63e7;
        }
        table,th,td{
            font-size: 14px;
        }
        .card-stats p {
            font-size: 14px;
            color: #555;
        }
        .badge-completed {
            background-color: #2a63e7;
            color: white;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 14px;
        }
        .badge-pending {
            background-color: #f68c1f;
            color: white;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 14px;
        }
        .badge-processing {
            background-color: #17a2b8;
            color: white;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 14px;
        }
        .badge-message{
            font-family: monospace;
            font-size: 12px;
            font-weight: bold;
        }
        .completed{
            color: blue;
        }
        .client-action{
            color: red;
        }
        .processing{
            color: green;
        }
        #job_countdown{
            position:fixed;
            top:10px;
            left:10%;
            right:10%;
            padding: 50px;
            background-color:white;
            box-shadow: 5px 5px blue;
            z-index:5000;
            border-style:solid;
            border-color:black;
            border-width:0.5px;
            display:none;
        }
        #new-request{
            background-color:white;
            position:fixed;
            top: 10%;
            left:25%;
            right:25%;
            height:500px;
            padding: 20px;
            z-index:5000;
            box-shadow:5px 5px blue;
            border-style:solid;
            border-color:black;
            border-width:0.5px;
            display:none;
        }
        #new-request input{
            margin: 20px 0;
        }
        .page-section span{
            postion: absolute;
            top: 5px;
            left:90%;
            cursor:pointer;
            color:red;
        }
        #timer{
            display:none;
        }
        #countdowntimer{
            margin:1px;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-user-cog fs-3 text-primary me-2"></i>
                    <div>
                        <div class="fw-bold">Client</div>
                        <small class="text-muted">Request Tracker</small>
                    </div>
                </div>
                <a href="#" class="nav-link active"><i class="fas fa-home"></i> Dashboard</a>
                <button class="btn btn-link mt-4" onclick = "initiateRequest();"> Initiate New  Request</button>
                <a href="../../backends/process_logout.php" class="nav-link mt-4"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 content">
                <h2 class="mb-4 fw-bold">Client Dashboard</h2>
                <h6>Welcome <?php echo $user_id; ?></h6>

                <!-- Stats -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card-stats">
                            <h6><?php echo "$assigned Requests Assigned"; ?></h6>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card-stats">
                            <h6><?php echo "$in_progress Requests In Progress"; ?></h6>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card-stats">
                            <h6><?php echo "$completed Requests Completed"; ?></h6>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card-stats">
                            <h6><?php echo "$overdue Overdue Requests"; ?></h6>
                        </div>
                    </div>
                </div>
                <!-- New Request -->
                <div class = "page-section" id = "new-request">
                    <span class = "fa fa-power-off" onclick = "document.getElementById('new-request').style.display = 'none';document.getElementsByTagName('body')[0].style.backgroundColor = 'white';"></span>
                    <h1 style = "text-align:center;">New Request/Job</h1>
                    <input type = "text" class = "form-control" id = "new_job" placeholder = "Type a name for your Submited Request" oninput = "begin_job_request()">
                    <button class = "btn btn-success" onclick = "submitRequest()">Submit</button>
                    
                    <div id = "new_request_status"></div>
                </div>
                <!-- Requests Table -->
                <h5 class="fw-bold mb-3">All Requests</h5>
                <p>Click on Any row to view countdown</p>
                <div class="table-responsive">
                    <table id = "tasks-table" class = "table table-bordered" style = "width:98%;">
        			  <thead>
        			  	 <tr>
        			  	     <th>Job ID</th>
        				    <th>Job Name</th>
        				    <th>Date Logged</th>
        				    <th>Job Status</th>
        				</tr>
        			</thead>
        			<tbody>
        			    <?php 
        			        $sql = "select * from jobs where ownerid = '$user_id'";
    				        $result = $conn->query($sql);
        			        if($result->num_rows > 0) {
        			            $i = 1;
        			            while($rows = $result->fetch_assoc()){
        			                $job_status = $rows["job_status_id"];
        			                $jobid = $rows["jobid"];
        			                $sql1 = "select * from job_status where statusid = '$job_status'";
        			                $result1 = $conn->query($sql1);
        			                if($result1->num_rows > 0){
        			            	    while($r = $result1->fetch_assoc()){
        			            		    $bg = $r["colour_code"];
        			            		    $status = $r["status"];
        			            	    }
        			                } ?>
        			                <tr id = "<?php echo $i++; ?>" onclick = "showCountdown(id);" style = "cursor:pointer">
        			                    <th><?php echo $jobid; ?></th>
                				        <th><?php echo $rows["Task"]; ?></th>
                				        <th><?php echo $rows["job_date"]; ?></th>
                				        <td><?php echo $status."<br><div style = 'width: 80%; height: 10px; background-color:$bg'></div>"; ?></td>
                			        </tr>
        		<?php           }
        			        }else{ ?>
        				        <tr>
        				            <th colspan = "3"><?php echo $table_content; ?></th>
        				        </tr>
        		<?php       } ?>
        				</tbody>
        			</table>
                </div>
                <div class = "page-section" id = "job_countdown">
                	<span class = "fa fa-times" onclick = "document.getElementById('job_countdown').style.display = 'none';"></span>
                	<h5 class = "text-center">JOB/REQUEST PROGRESS AND COUNTDOWN</h5>
                	<table id = "countdown-table" class = "table">
                	    <tr>
                	        <td>Request Name</td>
                	        <td>Date Submitted</td>
                	        <td>Time Submitted</td>
                	        <td>Date Assigned</td>
                	        <td>Time Assigned</td>
                	        <td>Request Duration</td>
                	        <td>Expected Completion Date</td>
                	        <td>Expected Completion Time</td>
                	    </tr>
                	    <tr>
                	        <td></td>
                	        <td></td>
                	        <td></td>
                	        <td></td>
                	        <td></td>
                	        <td></td>
                	        <td></td>
                	        <td></td>
                	    </tr>
                	</table>
                	<div id = "timer" class = "text-center">
                	    <p class = "text mt-3">Count Down Timer</p>
                	    <h1 id = "countdowntimer" class = "text"></h1>
                	</div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS + FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <script>
        function showCountdown(id){
            document.getElementById("job_countdown").style.display = "block";
            task_table = document.getElementById("tasks-table");
            countdown_table = document.getElementById("countdown-table");
            
            jobid = task_table.rows[id].cells[0].innerHTML;
            datetime = task_table.rows[id].cells[2].innerHTML;
            date = datetime.substring(0,10);
            time = datetime.substring(11);
            countdown_table.rows[1].cells[0].innerHTML = task_table.rows[id].cells[1].innerHTML;
            countdown_table.rows[1].cells[1].innerHTML = date;
            countdown_table.rows[1].cells[2].innerHTML = time;
            
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    data = JSON.parse(this.responseText);
                    countdown_table.rows[1].cells[3].innerHTML = data[0];
                    countdown_table.rows[1].cells[4].innerHTML = data[1];
                    countdown_table.rows[1].cells[5].innerHTML = data[2] + "hours";
                    countdown_table.rows[1].cells[6].innerHTML = data[3];
                    countdown_table.rows[1].cells[7].innerHTML = data[4];
                    if(data[0] == "Pending"){
                        document.getElementById("timer").style.display = "none";
                    }
                    else{
                        document.getElementById("timer").style.display = "block";
                        setInterval(countdown(data[5],data[2]),3600000);
                    }
                }
            };
            xml.open("GET","../../backends/populate_countdown_table.php?jobid="+jobid,true);
            xml.send();
        }
        
        function countdown(from,hrs_left){
            from_date = new Date(from);
            now_date = new Date();
            total_secs = Math.round((now_date.getTime() - from_date.getTime())/1000);
            hrs_spent = Math.round(total_secs/3600);
            hrs_left = hrs_left - hrs_spent;
            
            days = Math.round(hrs_left/24);
            hrs = days*24 - hrs_left;
            
            countd = days + " days " + hrs + " hrs ";
            document.getElementById("countdowntimer").innerHTML = countd; 
        }
        
        function initiateRequest(){
            document.getElementsByTagName("body")[0].style.backgroundColor = "lightgray";
            document.getElementById("new-request").style.display = "block";
        }
        
        function begin_job_request(){
            document.getElementById("new_request_status").innerHTML = "";
        }
        
          function submitRequest() {
            new_job = document.getElementById("new_job").value;
            user_id = document.getElementById("user_id").value; //alert(user_id);
            
            xml = new XMLHttpRequest(); 
             xml.onreadystatechange = function(){ 
                 if(this.readyState == 4 && this.status == 200){ 
                    document.getElementById("new_request_status").innerHTML = this.responseText;
                    document.getElementById("new_job").value = "";
                    document.getElementById("new_job").focus();
                } 
            };
            xml.open("GET","../../backends/addRequest.php?request_name="+new_job+"&user_id="+user_id, true);
            xml.send();  
        } 
    </script>

</body>
</html>
<?php
    }
    else{
        $_SESSION["invalid_login"] = "You Must Login First";
        header("Location:../../");
        
    }
?>
