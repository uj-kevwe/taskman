<?php
    session_start();
    include "../../db/setup.php";
    $c = new crudOps();
    if(isset($_SESSION["person"])){
        $staffid = $_SESSION["user_id"];
        $sql = "select * from $database.supervisors where staffid = '$staffid'";
        $result = $conn->query($sql);
        

        $supervisors = $conn->query("select * from $database.supervisors where staffid = '$staffid'");
        $alljobs = $conn->query("select * from $database.jobs");
        $all_requests = $conn->query("select * from $database.requests where supervised_by = '$staffid'");
        $assigned_requests = $conn->query("select * from $database.requests where supervised_by = '$staffid' and job_status_id = '00001'");
        $accepted_tasks = $conn->query("select * from $database.tasks where supervised_by = '$staffid' and task_status_id = '00002'");
        $rejected_tasks = $conn->query("select * from $database.tasks where supervised_by = '$staffid' and task_status_id = '00003'");
        $in_progress_tasks = $conn->query("select * from $database.tasks where supervised_by = '$staffid' and task_status_id = '00004'");
        $completed_tasks = $conn->query("select * from $database.tasks where supervised_by = '$staffid' and task_status_id = '00005'");
        $overdue_tasks = $conn->query("select * from $database.tasks where supervised_by = '$staffid' and task_status_id = '00006'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Supervisor Dashboard - Request Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">
    <script src="../../assets/bootstrap/bootstrap.min.js"></script>

    <!-- Bootstrap 3 -->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f9fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
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
        .supervisor-action{
            color: red;
        }
        .processing{
            color: green;
        }
        .jobs-section{
            box-shadow: 5px 5px lightblue;
            border-style: solid;
            border-width: 0.5px;
            border-color: lightgrey;
            display: none;
            padding: 20px;

        }
        .jobs-section p{
            margin-left: 10px;
        }
        .jobs-section input, .jobs-section select, .jobs-section textarea{
            margin: 10px;
        }
        .jobbatch{
            margin-top: -10px;
            border-style: solid;
            border-color: lightgrey;
            padding: 20px;
            display:none;
        }
        #single-jobs{
            position: relative;
            top: 10px;
            height: 400px;
            padding: 10px;
            overflow-x: auto;
            background-color: white;

        }
        #single,#multiple{
            margin: 0 20px;
            width: 200px;
        }
        #column1{
            border-right-style:solid;
            border-right-weight:10px;
            border-right-color:black;
        }
        #job-section-table tbody tr:hover{
            background-color:lightblue;
            color:white;
            cursor:pointer;
        }
        #timer-section{
            display:none; 
        }
        #timer-section table, #timer-section table td{
            border-style:solid;
            border-collapse: collapse;
        }

        @media screen and (max-width:900px){
            .sidebar {
                min-height: 30vh;
            }
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
                        <div class="fw-bold"><?= $_SESSION["person"]; ?></div>
                        <small class="text-muted">Request Tracker</small>
                    </div>
                </div>
                <a href="#" class="nav-link active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="javascript:void(0)" class="nav-link" id = "section-0" onclick="toggleSections(id);"><i class="fas fa-file-alt"></i> All Requests</a>
                <a href="javascript:void(0)" class="nav-link" id = "section-1" onclick="toggleSections(id)"><i class="fas fa-tasks"></i> Assign Job</a>
                <a href="javascript:void(0)" class="nav-link" id = "section-2" onclick="toggleSections(id)"><i class="fas fa-edit"></i> Edit Jobs</a>
                <a href="../../backends/process_logout.php" class="nav-link mt-4"><i class="fas fa-sign-out-alt"></i> Logout</a>
                
                <hr>
                <div id = "timer-section">
                    <table class = "table">
                        <tr style = "background-color:black;color:rgb(255,255,255);">
                            <th colspan = "2" style = "text-align:center;">JOB/TASK COUNTDOWN</th>
                        </tr>
                        <tr>
                            <td>JOB</td>
                            <td id = "cur-job"></td>
                        </tr>
                        <tr>
                            <td colspan = "2" id = "job-timer" style = "text-align:center"></td>
                        </tr>
                        <tr>
                             <td>TASK</td>
                            <td id = "cur-task"></td>
                        </tr>
                        <tr>
                            <td colspan = "2" id = "task-timer" style = "text-align:center"></td>
                        </tr>
                    </table>
                </div>
                <input type = 'text' id = 'total_job_time_seconds'>
                <input type = 'text' id = 'total_task_time_seconds'>
                <input type = 'text' id = 'task-countdown'>
                <input type = 'text' id = 'request-countdown'>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 content">
                <h2 class="mb-4 fw-bold">Supervisor Dashboard</h2>

                <!-- Stats -->
                <div class="row mb-4">
                    <div class="col-md-12 text text-center"><h5>Requests Status</h5></div>
                    <div class="col-md-2 mb-3">
                        <div class="card-stats">
                            <h3><?php echo $assigned_requests->num_rows; ?></h3>
                            <p>Assigned</p>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card-stats">
                            <h3><?= $accepted_tasks->num_rows ?></h3>
                            <p>Accepted</p>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card-stats">
                            <h3><?= $in_progress_tasks->num_rows ?></h3>
                            <p>In-Progress</p>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card-stats">
                            <h3><?= $rejected_tasks->num_rows ?></h3>
                            <p>Rejected</p>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card-stats">
                            <h3><?= $completed_tasks->num_rows ?></h3>
                            <p>Completed</p>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="card-stats">
                            <h3><?php echo $overdue_tasks->num_rows; ?></h3>
                            <p>Overdue</p>
                        </div>
                    </div>
                </div>

                <!-- Requests Table -->
                <div class="jobs-section table-responsive" id = "section1">
                    <h5 class="fw-bold mb-3">All Requests</h5>
                    <table id="job-section-table" class = "table table-bordered" style = "width:98%; font-size:12px;">
        			    <thead>
        			  	    <tr>
                                <th>Task ID</th>
                                <th>Client ID</th>
                                <th>Job Name</th>
                                <th>Job Category</th>
                                <th>Date in</th>
                                <th>Job Timer (Days:Hours:Mins:Secs) </th>
                                <th>Current Task</th>
                                <th>Task Timer (Days:Hours:Mins:Secs) </th>
                                <th>Task Status</th>
                                <th>Status Remark</th>
                                <th>Current Task Handler</th>
                                <th>Client Phone</th>
                                <th>Client Email</th>
                                <th>Remarks</th>
                                <!-- <th>  <input type="checkbox" id="chk" name="chk" value="check"></th> -->
        				    </tr>
        			    </thead>
        			    <tbody>
                            <?php
                                if($all_requests->num_rows > 0){
                                    $sno = 1;
                                    while($rows = $all_requests->fetch_assoc()){ 
                                        $clientid = $rows["clientid"];
                                        $requestid = $rows["requestid"];
                                        $jobid = $rows["jobid"];
                                        $date_in = $rows["job_date"];
 
                                        $all_jobs = $conn->query("select * from $database.jobs where jobid = '$jobid'");
                                         if($all_jobs->num_rows > 0){
                                            while($rows = $all_jobs->fetch_assoc()){
                                                $job = $rows["job_title"];
                                                $catid = $rows["catid"];
                                                $weeks = explode(",",$rows["duration_weeks"]);
                                                $days = explode(",",$rows["duration_days"]);
                                                $hours = explode(",",$rows["duration_hours"]);
                                                $mins = explode(",",$rows["duration_minutes"]);
                                                $secs = explode(",",$rows["duration_seconds"]);
                                            }
                                         }
                                         $all_cat = $conn->query("select * from $database.categories where catid = '$catid'");
                                         if($all_cat->num_rows > 0){
                                            while($rows = $all_cat->fetch_assoc()){
                                                $category = $rows["category"];
                                            }
                                         } 
                                         $wk = 0;
                                         $dy = 0;
                                         $hr = 0;
                                         $min = 0;
                                         $sec = 0;
                                         for($i = 0; $i < sizeof($hours); $i++){
                                            $wk += intval($weeks[$i]);
                                            $dy += intval($days[$i]);
                                            $hr += intval($hours[$i]);
                                            $min += intval($mins[$i]);
                                            $sec += intval($secs[$i]);
                                         }
                                         $day = $wk*7 + $dy;
                                         $countdown = "$day days $hr hrs $min mins $sec secs"; 
                                         $total_job_time_in_seconds = ($day*24*3600) + ($hr*3600) + ($min*60) + $sec;
                                         

                                         $tweeks = $tdays = $thrs = $tmins = $tsecs = 0; 
                                         $each_request = $conn->query("select * from $database.tasks where requestid = '$requestid'");
                                         if($each_request->num_rows > 0){
                                            while($rows = $each_request->fetch_assoc()){
                                                $current_handler_id = $rows["current_handler_id"];
                                                $task_name = $rows["task_name"];
                                                $weeks = $rows["duration_weeks"];
                                                $days = $rows["duration_days"];
                                                $hours = $rows["duration_hours"];
                                                $minutes = $rows["duration_minutes"];
                                                $seconds = $rows["duration_seconds"];
                                                $status_id = $rows["task_status_id"];
                                            }
                                         }
                                         $days = $weeks*7 + $days;
                                         $task_timer = "$days days: $hours hrs: $minutes mins: $seconds secs";
                                         $total_task_time_in_seconds = $days*24*3600 + $hours*3600 + $minutes*60 + $seconds;
                                         

                                         $this_status = $conn->query("select * from $database.task_status where statusid = '$status_id'");
                                         if($this_status->num_rows > 0){
                                            while($rows = $this_status->fetch_assoc()) {
                                                $status = $rows["status"];
                                                $color_code = $rows["colour_code"];
                                            }
                                         }

                                         $this_staff = $conn->query("select * from $database.staff where staffid = '$current_handler_id'");
                                         if($this_staff->num_rows > 0){
                                            while($rows = $this_staff->fetch_assoc()){
                                                $current_handler = $rows["firstname"]." ".$rows["middlename"]." ".$rows["lastname"];
                                            }
                                         }
                                         else{
                                             $current_handler = "Unassigned";
                                         }

                                         $this_user = $conn->query("select * from $database.users where userid = '$current_handler_id'");
                                         if($this_user->num_rows > 0){
                                            while($rows = $this_user->fetch_assoc()){
                                                $phone = $rows["telephone"];
                                                $email = $rows["email"];
                                            }
                                         }
                                         else{
                                         	$phone = "Unassigned";
                                                $email = "unassigned";
                                         }
                            ?>
                                    
                                    <tr id = "row<?=$sno?>" job_in_secs = '<?=$total_job_time_in_seconds?>' task_in_secs = '<?=$total_task_time_in_seconds?>' onclick = "displayTimer(id);setRequestProgress(id);setTaskProgress(id)">
                                        <td> 
                                          <!--  <button id = "btn<?=$sno++; ?>" class="btn btn-sm" onclick = "show_details(id)"><?=$requestid;?></button></td> -->
                                            <?=$requestid?>
                                        <td><?=$clientid;?></td>
                                        <td><?=$job;?></td>
                                        <td><?=$category;?></td>
                                        <td><?= $date_in;?></td>
                                        <td><?= $countdown ?></td>
                                        <td><?=$task_name ?></td>
                                        <td><?=$task_timer ?></td>
                                        <td><?=$status?></td>
                                        <td></td>
                                        <td> <a href="chat.html"><?=$current_handler;?></a> </td>
                                        <td><?=$phone; ?></td>
                                        <td><?=$email; ?></td>
                                        <td></td>
                                        <!-- <td>  <input type="checkbox" id="chk" name="chk" value="check"></td> -->
                                    </tr>
                            <?php          
                            }
                        }  
                        else{
                            echo "<tr><td colspan = '15'>No Job For this supervisor</td></tr>";
                        }
                    ?>
        				</tbody>
        			</table>
                </div>
                <div class="jobs-section" id="section2">
                    <form id="tasks-form" action = "../../backends/create_new_jobs.php" method="post" enctype="multipart/form-data">
                        <h5 class="fw-bold mb-3">New Job Details</h5>
                        <button type = "button" class="btn btn-primary" id = "single" onclick = "showbatch(id)">Assign A Single Job</button>
                        <button type = "button" class="btn btn-success" id = "multiple" onclick = "showbatch(id)">Assign Multiple Jobs</button>
                        <div class="row jobbatch" id="single-jobs">
                            <div class="col-lg-4 col-sm-12" id = "column1">
                                <div>
                                    <p style = "margin-bottom:-10px">Assign a Job</p>
                                    <select class = "form-control" name="new-job-name" id="new-job-name" onchange="assignTask();get_handlers(this.value)">
                                        <option value="0">Select from Available List</option>
                                        <?php
                                            while($rows = $supervisors->fetch_assoc()){
                                                $allowed_jobs_id = $rows["allowed_jobs_id"];
                                            }
                                            $jobs_id = explode(",",$allowed_jobs_id);
                                            foreach($jobs_id as $jobid){
                                                $sql = "select * from $database.jobs where jobid = '$jobid'";
                                                $res = $conn->query($sql);
                                                while($r = $res->fetch_assoc()){
                                                    $job_title = $r["job_title"];
                                                    echo "<option value = '$jobid'>$job_title</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div>
                                    <p style = "margin-bottom:-10px">Date In</p>
                                    <input class = "form-control" type="date" name="date-in" id="date-in" required>
                                </div>
                                <h6>Client Details</h6>
                                <div>
                                    <p style = "margin-bottom:-10px">First Name</p>
                                    <input class = "form-control" type="text" name="firstname" id="firstname" placeholder="Client First Name" required>
                                </div>
                                <div>
                                    <p style = "margin-bottom:-10px">Middle Name</p>
                                    <input class = "form-control" type="text" name="middlename" id="middlename" placeholder="Client Middle Name" >
                                </div>
                                <div>
                                    <p style = "margin-bottom:-10px">Last Name</p>
                                    <input class = "form-control" type="text" name="lastname" id="lastname" placeholder="Client Last Name" required>
                                </div>
                                <div>
                                    <p style = "margin-bottom:-10px">Gender</p>
                                    <input type="radio" name="gender" id="male" value="male" ><span>Male</span>
                                    <input type="radio" name="gender" id="female" value="female" ><span>Female</span>
                                </div>
                                <div>
                                    <p style = "margin-bottom:-10px">Telephone Number</p>
                                    <input class = "form-control" type="tel" name="phone" id="phone" placeholder="Client Phone" required>
                                </div>
                                <div>
                                    <p style = "margin-bottom:-10px">Address</p>
                                    <textarea class = "form-control" name="address" id="address"></textarea>
                                </div>
                                <div>
                                    <p style = "margin-bottom:-10px">Email Address</p>
                                    <input class = "form-control" type="email" name="email" id="email" placeholder="Client Email" onfocusout = "generate_password()" required>
                                </div>
                                <div>
                                    <p style = "margin-bottom:-10px">Auto Generated Password</p>
                                    <input class = "form-control" type="text" name="auto-pass" id="auto-pass" placeholder="Auto-generated Password" readonly>
                                </div>
                                <div>
                                    <p style = "margin-bottom:-10px">Client Photo</p>
                                    <input class = "form-control" type="file" name="client-photo" id="client-photo">
                                </div>
                                <hr>
                            </div>
                            <div class="col-lg-8 col-sm-12" id = "column2">
                                <div id="tasks-assign"></div>
                            </div>
                        </div>
                        <div class="jobbatch" id="multiple-jobs">
                            <p style="font-size:10px; font-style:italic;margin-bottom:1px">
                            <a href="../../assets/csvformat/batchjobs.csv" download>
                                    Download csv format to use in editing spreadsheet file for batch upload
                                </a>
                            </p>
                            <input type="file" name="batch-new-jobs" id="batch-new-jobs" accept=".csv">
                            <hr>
                            <button type="submit" class="btn btn-warning" name = "upload-multiple">
                                Upload Batch Jobs
                            </button>
                        </div>
                        <input type="hidden" id = "submit-type" name="submit-type">
                    </form>
                </div>

                <div class="jobs-section" id="section3">
                    <h5>Edit Job</h5>
                </div>


            </main>
        </div>
    </div>

    <!-- Bootstrap JS + FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        function toggleSections(id){
            id = parseInt(id.substr(-1)) + 1;
            section_id = "section" + id;
            job_sections = document.getElementsByClassName("jobs-section");
            for(i = 0; i < job_sections.length; i++){
                job_sections[i].style.display = "none";
            }
            document.getElementById(section_id).style.display = "block";
        }
        function showbatch(id){
            jobs = document.getElementsByClassName("jobbatch");
            job_id = id + "-jobs";
            button_type = "create-"+id;
            for(i = 0; i < jobs.length; i++){
                jobs[i].style.display = "none";
            }
            document.getElementById(job_id).style.display = "block";
            document.getElementById("submit-type").value = button_type;
        }
        function createJobID(){
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("new-job-id").value = this.responseText;
                    document.getElementById("new-job-name").focus();
                }
            };
            xml.open("GET","../../backends/get_new_job_id.php",true);
            xml.send();
        }
        function generate_password(){
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("auto-pass").value = this.responseText;
                    document.getElementById("request-tasks").focus();
                }
            };
            xml.open("GET","../../backends/getpass.php",true);
            xml.send();
        }
        function assignTask(){
            job = document.getElementById("new-job-name");
            jobid = job.value;
            index = job.selectedIndex;
            job_title = job.options[index].text;
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                alert(this.readyState + "--" + this.status);
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("tasks-assign").innerHTML = this.responseText;
                }
            };
            xml.open("GET","../../backends/assign_tasks.php?jobid="+jobid+"&job_title="+job_title,true);
            xml.send();
        }
        function createTasks(){
            handler = document.getElementById("task-handler").selectedIndex;
            task = document.getElementById("request-tasks").selectedIndex;
            if(task > 0){
                if(handler > 0){
                    document.getElementById("tasks-form").submit();
                }
                else{
                    alert("Please select a Handler before Submitting");
                    document.getElementById("task-handler").focus();

                }
            }
            else{
                alert("Please select a Task before Submitting");
                document.getElementById("request-tasks").focus();
            }
            
        }
        function get_duration(id){
            task = document.getElementById(id);
            index = task.selectedIndex; alert(index);
            task_title = task.options[index].text;
            if(index > 0){
                week = task.options[index].getAttribute("weeks");
                day = task.options[index].getAttribute("days");
                hour = task.options[index].getAttribute("hours");
                mins = task.options[index].getAttribute("mins");
                secs = task.options[index].getAttribute("secs");
                duration = week + " weeks " + day + " days " + hour + " hours " + mins + " mins " + secs + " secs";
                document.getElementById("task-duration").innerHTML = duration;
                document.getElementById("task-title").value = task_title;
        //        document.getElementById("task-handler").disabled = false;
            }
            else{
                document.getElementById("task-duration").innerHTML = "Chose a task first.<br> It's duration will show here.................";
            }
        }
        function set_durations(id){
            task = document.getElementById(id);
            index = task.selectedIndex;
            document.getElementById("tasks").value = task.value;
            document.getElementById("duration_weeks").value = task.options[index].getAttribute("weeks");
            document.getElementById("duration_days").value = task.options[index].getAttribute("days");
            document.getElementById("duration_hours").value = task.options[index].getAttribute("hours");
            document.getElementById("duration_mins").value = task.options[index].getAttribute("mins");
            document.getElementById("duration_secs").value = task.options[index].getAttribute("secs");
        }
        function get_handlers(val){
           xml = new XMLHttpRequest();
           xml.onreadystatechange = function(){
           	if(this.readyState == 4 && this.status == 200){
           	    document.getElementById("thandler").innerHTML = this.responseText;
           	}
           };
           xml.open("GET","../../backends/populate_handlers.php?jobs="+val,true);
           xml.send();
        }
        function show_details(id){
            taskid = document.getElementById(id).innerHTML;
            location.replace('jobDetails.php?taskid='+taskid);
        }
        function displayTimer(id){
            rownum = id.substring(3);
            table = document.getElementById("job-section-table");
            job_timer = table.rows[rownum].cells[5].innerHTML;
            task_timer = table.rows[rownum].cells[7].innerHTML;
            jis = document.getElementById(id).getAttribute("job_in_secs");
            tis = document.getElementById(id).getAttribute("task_in_secs");
            document.getElementById("total_job_time_seconds").value = jis;
            document.getElementById("total_task_time_seconds").value = tis;
            // document.getElementById("job-timer").innerHTML = countDown(jis);
            // document.getElementById("task-timer").innerHTML = countDown(tis);
            document.getElementById("cur-job").innerHTML = table.rows[rownum].cells[2].innerHTML;
            document.getElementById("cur-task").innerHTML = table.rows[rownum].cells[6].innerHTML;
            document.getElementById("timer-section").style.display = "block";
            
        }
         function setRequestProgress(id){
            table = document.getElementById("job-section-table");// alert(id);
            rownum = id.substring(3);// alert(rownum);
            taskid = table.rows[rownum].cells[0].innerHTML;// alert(taskid);
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("request-countdown").value = this.responseText;
                    countdownTimer(this.responseText);
                }
            };
            xml.open("GET","../backends/setCountdownDate.php?taskid="+taskid,true);
            xml.send();
        }
        function countdownTimer(t){
            var countDownDate = new Date(t).getTime();

            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("job-timer").innerHTML = "<h5 style = 'color:blue;'>JOB" + days + "d " + hours + "h "
                + minutes + "m " + seconds + "s </h5>";
        
                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("job-timer").innerHTML = "<h5 style = 'color:red;'>" + "EXPIRED </h5>";
                }
            }, 1000);

        }
        function setTaskProgress(id){
            table = document.getElementById("job-section-table");
            rownum = id.substring(3);
            taskid = table.rows[rownum].cells[0].innerHTML;
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("task-countdown").value = this.responseText;
                    countdownTaskTimer(this.responseText);
                }
            };
            xml.open("GET","../backends/setTaskCountdownDate.php?taskid="+taskid,true);
            xml.send();
        }
        function countdownTaskTimer(t){
            var countDownDate = new Date(t).getTime();

            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                document.getElementById("task-timer").innerHTML = "<h5 style = 'color:blue;'>TASK" + days + "d " + hours + "h "
                + minutes + "m " + seconds + "s </h5>";
        
                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("task-timer").innerHTML = "<h5 style = 'color:red;'>" + "EXPIRED </h5>";
                }
            }, 1000);

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
