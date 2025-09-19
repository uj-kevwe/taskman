<?php
    session_start();
    include "../../db/setup.php";
    $user_id = $_SESSION["user_id"];
    $c = new crudOps();
    if(isset($_SESSION["person"])){
        $where = "current_handler_id = '$user_id'";
        $all_tasks = $c->readFilteredData($conn,$database,"tasks",$where);
        $where_pending = "$where and task_status_id = '00001'";
        $pending_tasks = $c->readFilteredData($conn,$database,"tasks",$where_pending); 
        $where_accepted = "$where and task_status_id = '00002'";
        $accepted_tasks = $c->readFilteredData($conn,$database,"tasks",$where_accepted);
        $where = "userid != '$user_id' and roleid = '00003'";
        $handlers = $c->readFilteredData($conn,$database,"users",$where);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>University Request Tracker - Processor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap/bootstrap.min.css">
    <script src="../../assets/bootstrap/bootstrap.min.js"></script>
    <style>
        /* Same CSS as before */
        body {
            background-color: #f5f6fa;
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
        .sidebar .nav-link i {
            font-size: 18px;
        }
        .content {
            padding: 40px 30px;
        }
        #pending-requests{
            position: fixed;
            top: 25%;
            left: 20%;
            width: 70%;
            padding: 25px;
            background-color: #333;
            border-style: solid;
            border-color: black;
            color: white;
        }
        #prh{
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            padding: 10px;
            margin-bottom: 20px;
            background-color:white;
            color: black;
            text-align: center;
        }
        #prh-title{
            
            color: black;
        }
        #prh-close{
            float: right;
            margin-right: 10px;
            color: red;
            cursor: pointer;
        }
        #pendings{
            position: absolute;
            top: 50px;
            left: 0;
            right: 0;
        }
        #pendings table{
            color: black;
        } 
        .table th {
            background-color: #fafafa;
            color: #333;
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
        .btn-view {
            border: 1px solid #f68c1f;
            color: #f68c1f;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 14px;
            background-color: transparent;
            transition: background 0.3s, color 0.3s;
        }
        .btn-view:hover {
            background-color: #f68c1f;
            color: white;
        }
        .progress-tracker {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .progress-step {
            text-align: center;
            position: relative;
            flex: 1;
        }
        .progress-step::after {
            content: "";
            position: absolute;
            top: 14px;
            right: -50%;
            width: 100%;
            height: 4px;
            background-color: #d0d0d0;
            z-index: 0;
        }
        .progress-step:last-child::after {
            display: none;
        }
        .progress-step .circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: #d0d0d0;
            margin: 0 auto 8px;
            border: 3px solid #d0d0d0;
            z-index: 1;
            position: relative;
        }
        .progress-step.active .circle {
            background-color: white;
            border-color: #2a63e7;
        }
        .progress-step.active ~ .progress-step .circle {
            background-color: white;
            border-color: #d0d0d0;
        }
        .progress-step.active::after {
            background-color: #2a63e7;
        }
        .progress-step span {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }
        .progress-step.active span {
            color: #2a63e7;
        }
        .countdown{
            position: fixed;
            left: 60%;
            top: 10%;
        }
        #tasks-table tbody tr:hover{
            background-color: #eaf1ff;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-file-alt fs-3 text-primary me-2"></i>
                    <div>
                        <div class="fw-bold">University</div>
                        <small class="text-muted">Request Tracking System</small>
                    </div>
                </div>
                <a href="#" class="nav-link active"><i class="fas fa-home"></i> Home</a>
                <a href="#" class="nav-link"><i class="fas fa-network-wired"></i> Requests</a>
                <a href="#" class="nav-link"><i class="fas fa-file-signature"></i> Requests</a>
                <a href="#" class="nav-link"><i class="fas fa-chart-bar"></i> Analytics</a>
                <a href="../../backends/process_logout.php" class="nav-link mt-4"><i class="fas fa-clipboard-list"></i> Log out</a>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 content">
                <h2 class="mb-4 fw-bold">Handlers Dashboard</h2>
                <p>Welcome <?=$_SESSION["person"]; ?></p>
                <div class="countdown">
                    <input type="hidden" name="request-countdown" id="request-countdown">
                    <input type="hidden" name="task-countdown" id="task-countdown">
                    <span id="countdown-timer"></span>
                    <span id="countdown-task-timer"></span>
                </div>
                <?php
                    if($pending_tasks->num_rows > 0) {  ?>
                        <div id="pending-requests">
                            <input type="hidden" id="pending-status" value = "<?=$pending_tasks->num_rows; ?>">
                            <div id="prh">
                                <span id="prh-title">You Have Pending Requests</span`>
                                <span id = "prh-close" class="fa fa-times" onclick="closepending()"></span>
                            </div>
                            <div id="pendings">
                                <form id = "pending-tasks-form">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>SNO</th>
                                            <th>JOB NAME</th>
                                            <th>JOB DURATION</th>
                                            <th>JOB DATE</th>
                                            <th>TASK NAME</th>
                                            <th>TASK DURATION</th>
                                            <th>TASK DATE</th>
                                            <th>ACCEPT/REJECT</th>
                                        </tr>
                                        <?php
                                            // if($pending_tasks->num_rows > 0){
                                                $rownum = 1;
                                                while($rows = $pending_tasks->fetch_assoc()){
                                                    $requestid = $rows["requestid"];
                                                    $taskid = $rows["taskid"];
                                                    $task_name = $rows["task_name"];
                                                    $wks = $rows["duration_weeks"];
                                                    $days = $rows["duration_days"];
                                                    $hrs = $rows["duration_hours"];
                                                    $mins = $rows["duration_minutess"];
                                                    $secs = $rows["duration_seconds"];
                                                    $task_date = $rows["time_assigned"];
                                                    
                                                    $ds = $wks*7 + $days;
                                                    $task_duration = "$ds days: $hrs hrs: $mins mins: $secs secs";
                                                    $where1 = "requestid = '$requestid'";
                                                    $this_request = $c->readFilteredData($conn,$database,"requests",$where1);
                                                    if($this_request->num_rows > 0){
                                                        while($rows = $this_request->fetch_assoc()){
                                                            $jobid = $rows["jobid"];
                                                            $job_date = $rows["job_date"];
                                                        }
                                                    }
                                                    $where1 = "jobid = '$jobid'";
                                                    $jobs = $c->readFilteredData($conn,$database,"jobs",$where1);
                                                    if($jobs->num_rows > 0){
                                                        while($rows1 = $jobs->fetch_assoc()){
                                                            // $rownum = 1;
                                                            $job = $rows1["job_title"];
                                                            $job_weeks = explode(",",$rows1["duration_weeks"]);
                                                            $job_days = explode(",",$rows1["duration_days"]);
                                                            $job_hours = explode(",",$rows1["duration_hours"]);
                                                            $job_mins = explode(",",$rows1["duration_minutes"]);
                                                            $job_secs = explode(",",$rows1["duration_seconds"]);
                                                        }
                                                    }
                                                    $weeks = $days = $hours = $mins = $secs = 0;
                                                    for($i = 0; $i < sizeof($job_weeks); $i++){
                                                        $weeks += $job_weeks[$i]*7*24;
                                                        $days += $job_days[$i]*24;
                                                        $hours += $job_hours[$i];
                                                        $mins += $job_mins[$i]/60;
                                                        $secs += $job_secs[$i]/3600;
                                                    }
                                                    $job_duration = $weeks + $days + $hours + $mins + $secs;
                                                    // while($rows = $pending_tasks->fetch_assoc()){
                                                    $weeks = $days = $hours = $mins = $secs = 0;
                                                    // for($i = 0; $i < sizeof($job_weeks); $i++){
                                                    $weeks += $rows["duration_weeks"]*7*24;
                                                    $days += $rows["duration_days"]*24;
                                                    $hours += $rows["duration_hours"];
                                                    $mins += $rows["duration_minutes"]/60;
                                                    $secs += $rows["duration_seconds"]/3600;
                                                    // }
                                                    // $task_duration = $weeks + $days + $hours + $mins + $secs;
                                                    echo "<tr>";
                                                    echo "<td>".$rownum++."</td>";
                                                    echo "<td>".$job."</td>";
                                                    echo "<td>".number_format($job_duration,2)." hours</td>";
                                                    echo "<td>".$job_date."</td>";
                                                    echo "<td>".$task_name."</td>";
                                                    echo "<td>".$task_duration." hours</td>";
                                                    echo "<td>".$task_date."</td>";
                                                    echo "<td>
                                                            <button type = 'button' id = 'accept-$taskid' class = 'btn btn-sm btn-success' onclick = 'acceptJob(id)'>
                                                                Accept
                                                            </button>
                                                            <button type = 'button' id = 'reject-$taskid' class = 'btn btn-sm btn-danger' onclick = 'rejectJob(id)'>
                                                                Reject
                                                            </button>
                                                        </td>";
                                                    echo "</tr>";
                                                }
                                            // }
                                        ?>
                                    </table>
                                    <input type="hidden" name="taskid" id="taskid">
                                </form>
                            </div>
                        <!-- <p><?php // if(isset($_SESSION["task_status"])){echo $_SESSION["task_status"];} ?></p> -->
                        </div>
                <?php }else { ?>
                    <div id = "accepted-requests">
                        <!-- Request Requests Table -->
                        <h5 class="fw-bold mb-3">
                            Accepted Tasks 
                            <span style="font-size: 10px;font-style:italic;">Click on any request row to view it's progress Timer</span>
                        </h5>
                        <div class="table-responsive mb-4">
                            <table id = "tasks-table" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SNO</th>
                                        <th>TASK</th>
                                        <th>Duration (Hrs)</th>
                                        <th>Last Handler</th> 
                                        <th>Status</th>
                                        <th>Overdue Reasons</th>
                                        <th>Next Task</th>
                                        <th>Next Handler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        if($accepted_tasks->num_rows > 0){
                                            $sno = 1;
                                            while($rows = $accepted_tasks->fetch_assoc()){
                                                $requestid = $rows["requestid"];
                                                $taskid = $rows["taskid"] ;
                                                $task_name = $rows["task_name"];
                                                $last_handler = $rows["prev_handler_id"];
                                                $weeks = $rows["duration_weeks"]*7*24;
                                                $days = $rows["duration_days"]*24;
                                                $hours = $rows["duration_hours"];
                                                $mins = $rows["duration_minutes"]/60;
                                                $secs = $rows["duration_seconds"]/3600;
                                                $duration = $weeks + $days + $hours + $mins + $secs;

                                                $where = "requestid = '$requestid'";
                                                $this_request = $c->readFilteredData($conn,$database,"requests",$where);
                                                if($this_request->num_rows > 0){
                                                    while($rows = $this_request->fetch_assoc()){
                                                        $jobid = $rows["jobid"];
                                                    }
                                                }

                                                $where = "jobid = '$jobid'";
                                                $this_job = $c->readFilteredData($conn,$database,"jobs",$where);
                                                if($this_job->num_rows > 0){
                                                    while($rows = $this_job->fetch_assoc()){
                                                        $job_title = $rows["job_title"];
                                                    }
                                                }
                                                echo "<tr id = '$taskid' onclick = 'setRequestProgress(id);setTaskProgress(id)'>";
                                                echo "<td>$sno</td>";
                                                echo "<td>".$task_name."</td>";
                                                echo "<td>$duration </td>";
                                                echo "<td>".$last_handler."</td>"; 
                                                echo "<td id = 'task-status'>";
                                                // calculate status
                                                $now = date_create(date("Y-m-d h:i:s"));
                                                $from = date_create($rows["time_assigned"]);
                                                $diff = date_diff($from,$now);
                                                $time_elapsed = intval($diff->format("%R%a"))*24;
                                                echo "<input type = 'hidden' id = 'time_elapsed' value = '$time_elapsed'>";
                                                if($time_elapsed > $duration){
                                                    echo "Overdue";
                                                }
                                                else{
                                                    echo "In Progress";
                                                    echo "<br><span>Tick if Completed: <input type = 'checkbox' id = 'tick$sno' onclick = 'changeStatus(id)'>";
                                                }
                                                echo "</td>";
                                                echo "<td>
                                                        <input type = 'text' class = 'form-control reason' 
                                                        id = 'reason$sno' placeholder = 'Reason For Overdue' 
                                                        disabled style = 'font-size:12px;'>
                                                    </td>";
                                                echo "<td>"; ?>
                                                <select name="next-tasks" id="next-tasks">
                                                    <option value="0">Select Next Task</option>
                                                    <?php
                                                        $esql = "select * from $database.jobs where jobid = '$jobid'";
                                                        $risot = $conn->query($esql);
                                                        if($risot->num_rows > 0){
                                                            while($roows = $risot->fetch_assoc()){
                                                                $all_tasks = $roows["tasks_title"];
                                                            }
                                                        }
                                                        else{
                                                            echo "<option value = '-1'>".$conn->error."</option>";
                                                        }
                                                        // echo "<option>".$all_tasks."</option>";
                                                        $allTasks = explode(",",$all_tasks);
                                                        for($i = 0; $i < sizeof($allTasks);$i++){
                                                            $j = $i + 1;
                                                            if($allTasks[$i] != $task_name){
                                                                echo "<option value = '$j'>".$allTasks[$i]."</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
                                        <?php   echo "</td>";
                                                echo "<td>"; ?>
                                                <select name="task-handlers" id="task-handlers">
                                                    <option value="0">Select Next Handler</option>
                                                    <?php
                                                        if($handlers->num_rows > 0){
                                                            while($rows = $handlers->fetch_assoc()){
                                                                $user_id = $rows["userid"];
                                                                $where = "staffid = '$user_id'";
                                                                $res = $c->readFilteredData($conn,$database,"staff",$where);
                                                                // $sql = "select * from staff where staffid = '$user_id'";
                                                                // $res = $conn->query($sql);
                                                                if($res->num_rows > 0){
                                                                    while($rws = $res->fetch_assoc()){
                                                                        $handler_name = $rws["firstname"]." ".$rws["middlename"]." ".$rws["lastname"];
                                                                        echo "<option value = '$user_id'>$handler_name</option>";
                                                                    }
                                                                }
                                                                else{
                                                                    echo "<option>No Other Handler in the System</option>";
                                                                }
                                                            }
                                                        }
                                                                
                                                    ?>
                                                </select>
                                                <button id = "next<?=$sno?>" class="btn btn-sm btn-primary p-1 mt-3"
                                                style = "position:relative;top:-10px;height:20px; width:20px;font-size:10px;"
                                                onclick="nextHandler(id)">
                                                    <span class="fa fa-chevron-right"></span>
                                                </button>
                                    <?php
                                                echo "</td>";
                                                echo "</tr>";
                                                $sno++;
                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                <?php } ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JS for Modal -->
    <script> 
        table = document.getElementById("tasks-table"); 
            
        for(i = 0; i < table.rows.length; i++){
            if(table.rows[i].cells[4].innerHTML.includes("Overdue")){
                reason = "reason"+i;
                document.getElementById(reason).disabled = false;
            }
        }
        function closepending(){
            if(document.getElementById("pending-status").value > 0){
                alert("Please Accept or Reject Any Pending Requests");
            }
            else{
                document.getElementById("pending-requests").style.display = "none";
                document.getElementById("accepted-requests").style.display = "block";
            }
        }
        function acceptJob(id){
            taskid = id.substring(7);
            document.getElementById("taskid").value = taskid;
            pending_status = document.getElementById("pending-status").value;
            pending_status = parseInt(pending_status) - 1;
            document.getElementById("pending-status").value = pending_status;
            if(id.includes("accept")){
                document.getElementById("pending-tasks-form").setAttribute("action","../../backends/acceptTask.php?jobi");
            }
            document.getElementById("pending-tasks-form").submit();
        }
        function rejectJob(id){
            taskid = id.substring(7);
            document.getElementById("taskid").value = taskid;
            pending_status = document.getElementById("pending-status").value;
            pending_status = parseInt(pending_status) - 1;
            document.getElementById("pending-status").value = pending_status;
            if(id.includes("reject")){
                document.getElementById("pending-tasks-form").setAttribute("action","../../backends/rejectTask.php");
            }
            document.getElementById("pending-tasks-form").submit();
        }
        function changeStatus(id){
            row = id.substring(4);
            next_btn = "next"+row;
            reason = "reason"+row;
            if(document.getElementById(id).checked == true){
                document.getElementById("task-status").innerHTML = "Completed";
                document.getElementById(next_btn).disabled = false;
                document.getElementById(reason).focus();
            }
            else{
                document.getElementById(next_btn).disabled = true;
                // document.getElementById(reason).disabled = true;
            }
        }
        function nextHandler(id){
            sno = id.substring(4);
            taskid = table.rows[sno].getAttribute("id");
            next_handler = document.getElementById("task-handlers").value;
            task = document.getElementById("next-tasks");
            index = task.selectedIndex;
            next_task = task.options[index].text;
            next_task_val = task.value;

            if(next_handler != 0){
                if(next_task_val != 0){
                    xml = new XMLHttpRequest();
                    xml.onreadystatechange = function(){
                        // alert(this.readyState + "--" + this.status);
                        if(this.readyState == 4 && this.status == 200){
                            alert(this.responseText);
                        }
                    };
                    xml.open("GET","../../backends/nextHandler.php?taskid="+taskid+"&next_handler="+next_handler+"&next_task="+next_task,true);
                    xml.send();
                }
                else{
                    alert("Please select a valid Task");
                }
            }
            else{
                alert("Please select a valid Handler");
            }
        }
        function setRequestProgress(id){
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("request-countdown").value = this.responseText;
                    countdownTimer(this.responseText);
                }
            };
            xml.open("GET","../../backends/setCountdownDate.php?taskid="+id,true);
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

                
        
                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("countdown-timer").innerHTML = "<h5 style = 'color:red;'>JOB SCOPE: " + "EXPIRED </h5>";
                }
                else{
                    document.getElementById("countdown-timer").innerHTML = "<h5 style = 'color:blue;'>JOB SCOPE: " + days + "d " + hours + "h "
                + minutes + "m " + seconds + "s </h5>";
                }
            }, 1000);

        }
        function setTaskProgress(id){
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("task-countdown").value = this.responseText;
                    countdownTaskTimer(this.responseText);
                }
            };
            xml.open("GET","../../backends/setTaskCountdownDate.php?taskid="+id,true);
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

                document.getElementById("countdown-task-timer").innerHTML = "<h5 style = 'color:blue;'>TASK SCOPE: " + days + "d " + hours + "h "
                + minutes + "m " + seconds + "s </h5>";
        
                // If the count down is over, write some text 
                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("countdown-task-timer").innerHTML = "<h5 style = 'color:red;'>TASK SCOPE: " + "EXPIRED </h5>";
                }
            }, 1000);

        }
    </script>

</body>
</html>
<?php
    }
    else{
        echo "
            <script>
                alert('No User Logged In');
                location.replace('../../');
            </script>
        ";
    }
?>