<?php
    session_start();
    include "../../db/setup.php";
    $c = new crudOps();
    if(isset($_SESSION["person"])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Super Admin Dashboard - Request Tracker</title>
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
            font-size: 12px;
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
            padding: 5px;
            border-radius: 5px;
            width: 18%;
            margin: 0 0.75%;
            /* background-color: rgba(154, 152, 152, 1);
            color:white; */
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: center;
        }
        .card-stats:hover{
            background-color:lightblue;
            color: white;
        }
        table,th,td{
            font-size: 14px;
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
        .admin-action{
            color: red;
        }
        .processing{
            color: green;
        }
        .main-section{
            display:none;
        }
        .main-section:first-child{
            display: block;
        }
        /* .main-section input,  */
        .new-features{
            display:none;
        }
        #add-dept-btn{
            display: none;
        }
        #single-dept,#bulk-dept,#single-user,#bulk-user{
            width: 48%;
            border-color:lightgrey;
            border-width: 1px;
        }
        #single-dept,#single-user{
            border-style: groove;
            border-color: darkblue;
            border-width: 3px;
        }
        form{
            border-style:none;
            width: 100%;
        }
        .deptForms:last-child,.userForms:last-child{
            display: none;
        }
        #user-role,#user-dept{
            margin:5px 0;
        }
        #menubar{
            display: none;
        }

        .fa-power-off{
            color: red;
            position: absolute;
            top: 20px;
            right: 50px;
            cursor: pointer;
        }

        #edit-user input{
            margin: 5px 0;
            width:50%;
        }
        #select-user-edit{
            margin: 10px 0;
            width: 50%;
        }

        .ruler{
            margin: 10px;
            border-style: groove;
            border-color:blue;
            border-width:3px;
        }
        .accounts-section{
            display: none;
        }
        .accounts-section:first-child{
            display: block;
        }
        #add-category-btn{
            display:none;
        }
        
        #jobdetails{
            display:none;
        }
        
        #jobdetails input[type=range], #jobdetails select, #jobdetails input[type = text]{
            width: 40%;
            margin: 5px;
        }
        
        .durations{
            width:15%;
            display:inline;
        }

        #create-job-section{
            display:none;
        }
        #allowed-jobs-label{
            margin-top:20px;
            font-size:12px;
            font-weight: bold;
        }
        #allowed-jobs{
            margin-bottom:20px;
        }
        #allowed-jobs,#allowed-jobs-label{
            display:none;
        }
        #tasks-section{
            margin: 20px 0;
            display: none;
        }
        .duration_weeks,.duration_days,.duration_hours,.duration_mins,.duration_secs{
            width:40px;
        }

        @media only screen and (max-width:900px){
            #menubar{
                display:block;
                position: absolute;
                top: 20px;
                right: 20px;
                cursor: pointer;
            }
            #menu{
                display: none;
                background-color: whitesmoke;
                position: absolute;
                top: 70px;
                left: 0;
                right: 0;
                z-index: 100;

            }
            .sidebar{
                min-height: 10px;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 100;
            }
            main{
                position: absolute;
                top: 10px;
                left: 0;
                right: 0;
                z-index: 0;
            }
            .main-section{
                position: absolute;
                top: 40px;
                left: 0;
                right: 0;
                padding: 20px;
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
                        <div class="fw-bold" style="margin-bottom: -30px;">
                            <p style="font-size:10px;">
                                <?php 
                                    if(isset($_SESSION["person"])){
                                        echo $_SESSION["person"];
                                    }
                                ?>
                            </p>
                        </div>
                        <!-- <small class="text-muted">Request Tracker</small> -->
                    </div>
                    <span id = "menubar" class="fa fa-bars" onclick="expandMenu('menu')"></span>
                </div>
                <div id="menu">
                    <a href="javascript:void(0)" class="nav-link active" id = "dashboard-section" onclick="toggleSection(id)"><i class="fas fa-home"></i>Dashboard</a>
                    <a href="javascript:void(0)" class="nav-link" id = "user-section" onclick="toggleSection(id)"><i class="fas fa-user"></i>Add New User</a>
                    <a href="javascript:void(0)" class="nav-link" id = "department-section" onclick="toggleSection(id)"><i class="fas fa-file-alt"></i>Add Department</a>
                    <a href="javascript:void(0)" class="nav-link" id = "job-category-section" onclick="toggleSection(id)"><i class="fas fa-cog"></i>Create A Job Category</a>
                    <a href="javascript:void(0)" class="nav-link" id = "job-section" onclick = "create_new_job()"><i class="fas fa-cog"></i>Create Job</a>
                    <a href="javascript:void(0)" class="nav-link mt-4" id = "logout-section" onclick="toggleSection(id)"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 content">
                
                <section class="main-section" id="new-dashboard-section">
                    
                    <h2 class="mb-4 fw-bold">Super Admin Dashboard</h2>

                    <!-- Stats -->
                    <div class="row mb-4">
                        <button class="card-stats" id = "accounts-section" onclick="toggleSection(id)">
                            Accounts
                        </button>
                        <button class="card-stats" id = "jobs-section" onclick="toggleSection(id)">
                            Jobs
                        </button>
                        <button class="card-stats" id = "holidays-section" onclick="toggleSection(id)">
                            Holidays
                        </button>
                        <button class="card-stats" id = "utility-section" onclick="toggleSection(id)">
                            Utility
                        </button>
                        <button class="card-stats" id = "profile-section" onclick="toggleSection(id)">
                            My Profile
                        </button>
                        <hr class = "ruler">
                    </div>

                    <!-- Requests Table -->
                    <!-- <h5 class="fw-bold mb-3">All Requests</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Requester Name</th>
                                    <th>Requester Type</th>
                                    <th>ID Type</th>
                                    <th>ID Number</th>
                                    <th>Request Type</th>
                                    <th>Email</th>
                                    <th>Date Requested</th>
                                    <th>Status/Current Processor</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1001</td>
                                    <td>Osasu Iyekekpolor</td>
                                    <td>Student</td>
                                    <td>Student ID Card</td>
                                    <td>STU1234</td>
                                    <td>Transcript</td>
                                    <td>osasu@uniben.edu</td>
                                    <td>April 24, 2024</td>
                                    <td>
                                        <span class="badge-completed">Completed</span><br>
                                        <span class="badge-message completed">Transcript dispatched</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">View</button>  
                                    </td>
                                </tr>
                                <tr>
                                    <td>1002</td>
                                    <td>Kingsley Ehiaghe</td>
                                    <td>Contractor</td>
                                    <td>Driver's Licence</td>
                                    <td>BEN1214AC</td>
                                    <td>Submission of Proforma Invoice</td>
                                    <td>kingsley@uniben.edu</td>
                                    <td>April 26, 2024</td>
                                    <td>
                                        <span class="badge-pending">Pending</span><br>
                                        <span class="badge-message admin-action">Super Admin Action Required</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">View</button>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>1003</td>
                                    <td>Jennifer Ogbemudia</td>
                                    <td>Staff</td>
                                    <td>National ID</td>
                                    <td>11025414781</td>
                                    <td>Request for Annual Leave</td>
                                    <td>jennifer@uniben.edu</td>
                                    <td>April 28, 2024</td>
                                    <td>
                                        <span class="badge-processing">Processing</span><br>
                                        <span class="badge-message processing">Accounts & Payrol Desk</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">View</button>
                                    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div> -->
                </section>
                <section class="main-section" id="new-department-section">
                    <span class="fa fa-power-off" onclick = "dismiss_section('new-department-section')"></span>
                    <button class="mb-4 fw-bold deptbtns" id = "single-dept" onclick="switchDeptForm(id)">Add A New Department</button>
                    <button class="mb-4 fw-bold deptbtns" id = "bulk-dept" onclick="switchDeptForm(id)">Upload Bulk Job Categories</button>
                    <form action="" method="post" class = "deptForms" id="add-single-dept">
                        <p style="margin-bottom:1px;">New Department Name</p>
                        <input type="text" class="form-control" name="new-dept" id="new-dept" onfocusout = "searchDept(id)" oninput = "resetLabel()">
                        <p id = "new-dept-search"></p>
                        <div class="new-features" id="newDept">
                            <p style="margin-bottom:1px;">Supervisor Name (Surname First)</p>
                            <input type="text" class="form-control m-2" name="dept-head" id="dept-head" oninput = "searchStaff(id)" onfocusout = "staffSearch(id)">
                            <div id = "new-staff-search"></div>
                            <p id = "dept-email-label" style="margin-bottom:1px;">Supervisor Email</p>
                            <input type="email" class="form-control m-2" name="dept-email" id="dept-email" onfocusout = "verifyEmail(id)">
                            <p id = "dept-phone-label">Supervisor Phone</p>
                            <input type="tel" name="dept-phone" id="dept-phone" class="form-control m-t">
                            <div id = "new-email-search"></div>
                            <button type="button" id = "add-dept-btn" class="btn btn-info" id="new-dept-btn" disabled onclick="addDept()">Add Department</button>
                            <p id = "new-dept-search-status"></p>
                        </div>
                    </form>
                    <form action="../../backends/uploadBulkDept.php" method = "post" class = "deptForms" id = "add-bulk-dept" enctype = "multipart/form-data">
                        <p>Choose bulk csv file to upload</p>
                        <input type="file" name="bulk-depts" id="bulk-depts" class="form-control">
                        <a href="../../assets/csvformat/bulkdept.csv" style="display:block;margin: 5px 0">
                            Download a Bulk CSV format
                        </a>
                        <input type="submit"  value="Upload File">
                    </form>
                </section>
                <section class="main-section" id="new-user-section">
                    <span class="fa fa-power-off" onclick = "dismiss_section('new-user-section')"></span>
                    <button class="mb-4 fw-bold userbtns" id = "single-user" onclick="switchUserForm(id)">Add A New User</button>
                    <button class="mb-4 fw-bold userbtns" id = "bulk-user" onclick="switchUserForm(id)">Upload Bulk Users</button>
                    <form class="userForms" id = "add-single-user">
                        <input type="text" class="form-control" name="new-user" id="new-user" placeholder="Type New User First Name, Middle Name (optional) and Last Name. (Press Tab Key when done)" onfocusout = "newStaff(id)" oninput = "clearLabel()">
                        <p id = "new-user-search"></p>
                        <div class="new-features" id="newUser">
                            <input type="email" name="user-email" id="user-email" class="form-control" placeholder="New Staff Email" onfocusout = "verify_email(id)">
                            <p id = "new-email-status"></p>
                            <input type="tel" name="user-phone" id="user-phone" class="form-control" placeholder="New Staff Phone Number">
                            <select class = "form-control" name="user-role" id="user-role" onchange="showJobs(id)">
                                <option value="0">Select A User Role</option>
                                <option value="00003">Handler</option>
                                <option value="00004">Supervisor</option>
                            </select>
                            <label id = "allowed-jobs-label" for="allowed-jobs">Select Jobs This Supervisor Can Handle (Hold down control key or shift key to select multiple jobs) </label>
                            <select class = "form-control" name="allowed-jobs" id="allowed-jobs" multiple> 
                                <?php
                                    $sql = "select * from $database.jobs";
                                    $result = $conn->query($sql);

                                    if($result->num_rows > 0){
                                        while($rows = $result->fetch_assoc()){
                                            $job_title = $rows["job_title"];
                                            $jobid = $rows["jobid"];
                                            echo "<option value ='$jobid'>$job_title</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <select class="form-control" name="user-dept" id="user-dept">
                                <option value="0">Select A Department</option>
                                <?php
                                    $all_depts = $c->readData($conn,$database,"departments");
                                    if($all_depts->num_rows > 0){
                                        while($rows = $all_depts->fetch_assoc()){
                                            $value = $rows["deptid"];
                                            echo "<option value = '$value'>".$rows["department"]."</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <button type="button" class = "btn btn-primary" id="new-user-btn" onclick = "add_a_user()">Add User</button>
                            <p id = "add-new-user-status"></p>
                        </div>
                    </form>
                    <form action="../../backends/uploadBulkStaff.php" post = "method" class="userForms" id = "add-bulk-user" enctype="multipart/form-data">
                        <p>Choose bulk csv file to upload</p>
                        <input type="file" name="bulk-users" id="bulk-users" class="form-control">
                        <a href="../../assets/csvformat/bulkusers.csv" style="display:block;margin: 5px 0">
                            Download a Bulk CSV format
                        </a>
                        <input type="submit" value="Upload File">
                    </form>
                </section>
                <section class="main-section" id="new-job-category-section">
                    <span class="fa fa-power-off" onclick = "dismiss_section('new-job-category-section')"></span>
                    <h2 class="mb-4 fw-bold">Create A New Job category</h2>
                    <input type = "text" class = "form-control" id = "category-name" name = "category-name" placeholder = "Type the Category Name" oninput = "searchcategories(id)">
                    <p id = "categories-exists"></p>
                    <button type = "button" class = "btn btn-success" id = "add-category-btn" onclick = "addCategory()">
                    	Add Category
                    </button>
                </section>
                <section class="main-section" id="new-job-section">
                    <span class="fa fa-power-off" onclick = "dismiss_section('new-job-section')"></span>
                    <h2 class="mb-4 fw-bold">Assign Job</h2>
                    <p>Submitted Jobs</p>
                    <select class = "form-control" id = "submitted_jobs" onchange= "get_job_owner(id)">
                        <option value = "0">Select A Job To Assign</option>
                        <?php
                            $where = "job_status_id = '00001'";
                            $all_jobs = $c->readFilteredData($conn,$database,"requests",$where);
                            
                            if($all_jobs->num_rows > 0){
                                while($rows = $all_jobs->fetch_assoc()){
                                    $client_name = "";
                                    $jobid = $rows["jobid"];
                                    $task = $rows["Task"];
                                    $ownerid = $rows["ownerid"];
                                    $job_date = $rows["job_date"];
                                    $where2 = "clientid = '$ownerid'";
                                    $all_clients = $c->readFilteredData($conn,$database,"clients",$where2);
                                    if($all_clients->num_rows > 0){
                                        while($r = $all_clients->fetch_assoc()){
                                            $client_name = $r["firstname"]." ".$r["middlename"]." ".$r["lastname"];
                                        }
                                    }
                                    echo "<option value = '$jobid' client = '$client_name' job_date = '$job_date'>$task</option>";
                                }
                            }
                        ?>
                    </select>
                    <div id = "jobdetails">
                        <p style = "margin: 20px 0 1px 0">Job Request Details</p>
                        <table class = "table table-bordered">
                            <tr>
                                <th>JOB REQUEST NAME</th>
                                <td id = "jrn"></td>
                            </tr>
                            <tr>
                                <th>JOB REQUEST OWNER (CLIENT)</th>
                                <td id = "jrc"></td>
                            </tr>
                            <tr>
                                <th>DATE SUBMITTED</th>
                                <td id = "jrd"></td>
                            </tr>
                            <tr>
                            </tr>
                        </table>
                        <div id = "jobduration">
	                        <p style = "margin: 20px 0 1px 0">Set Job Duration</p>
	                        <div>
	                            <label for = "hours">
	                                Hours (1 - 23): 
	                                <input type = "text" id = "set_hours" class = "form-control durations" value = "0">
	                            </label>
	                            <input type = "range" class = "form-control" id = "hours" min = "0" max = "23" value = "0" oninput = "setvalue(id)">
	                            
	                            <label for = "days">
	                                Days (1 - 6): 
	                                <input type = "text" id = "set_days" class = "form-control durations" value = "0">
	                            </label>
	                            <input type = "range" class = "form-control" id = "days" min = "0" max = "6" value = "0" oninput = "setvalue(id)">
	                            
	                            <label for = "weeks">
	                                Weeks (1 - 3):
	                                <input type = "text" id = "set_weeks" class = "form-control durations" value = "0">
	                            </label>
	                            <input type = "range" class = "form-control" id = "weeks" min = "0" max = "3" value = "0" oninput = "setvalue(id)">
	                            
	                            <label for = "months">
	                                Months (1 - 60 [5 Years]):
	                                <input type = "text" id = "set_months" class = "form-control durations" value = "0">
	                            </label>
	                            <input type = "range" class = "form-control" id = "months" min = "0" max = "60" value = "0" oninput = "setvalue(id)">
	                            
	                            
	                            <button onclick = "setDuration()" style = "margin: 20px">Set Duration</button>
	                        </div>
	                    </div>
                    </div>
                    
                </section>
                <section class="main-section" id="new-accounts-section">
                    <span class="fa fa-power-off" onclick = "dismiss_section('new-accounts-section')"></span>
                    <button class="card-stats" onclick = "addAccts()">Add User</button>
                    <button class="card-stats" id = "accts-2" onclick = "displayAccts(id)">Edit User</button>
                    <button class="card-stats">Delete User</button>
                    <button class="card-stats">Search User</button>
                    <hr class = "ruler">
                    <div id = "accts1">
                        <table class="table table-bordered" style="margin-top: 10px;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <!-- <th>Password</th> -->
                                    <th>Roles</th>
                                    <th>Job Categories</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <!-- <th>Company</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $all_users = $c->readData($conn,$database,"users");
                                    if($all_users->num_rows > 0){
                                        while($rows = $all_users->fetch_assoc()){
                                            $userid = $rows["userid"];
                                            $roleid = $rows["roleid"];
                                            $email = $rows["email"];
                                            $phone = $rows["telephone"];
                                            $department = "";
                                            
                                            $where = "staffid = '$userid'";
                                            $user_details = $c->readFilteredData($conn,$database,"staff",$where);
                                            if($user_details->num_rows > 0){
                                                while($user_row = $user_details->fetch_assoc()){
                                                    $name = $user_row["firstname"]." ".$user_row["middlename"]." ".$user_row["lastname"];
                                                    $deptid = $user_row["deptid"];

                                                    $where2 = "deptid = '$deptid'";
                                                    $dept_details = $c->readFilteredData($conn,$database,"departments",$where2);
                                                    if($dept_details->num_rows > 0){
                                                        while($dept_row = $dept_details->fetch_assoc()){
                                                            $department = $dept_row["department"];
                                                        }
                                                    }
                                                }
                                            }
                                            $where3 = "roleid = '$roleid'";
                                            $role_details = $c->readFilteredData($conn,$database,"roles",$where3);
                                            if($role_details->num_rows > 0){
                                                while($role_row = $role_details->fetch_assoc()){
                                                    $role = $role_row["role"];
                                                }
                                            }
                                            echo "<tr>";
                                            echo "<td>".$name."</td>";
                                            echo "<td>".$userid."</td>";
                                            echo "<td>".$role."</td>";
                                            echo "<td>".$department."</td>";
                                            echo "<td>".$phone."</td>";
                                            echo "<td>".$email."</td>";
                                            echo "<td>".$department."</td>";
                                            echo "</tr>";
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="accounts-section" id = "accts2">
                    	<form>
	                        <select class="form-control" name="select-user-edit" id="select-user-edit" onchange = "editUser()">
	                            <option value="0">Select a User profile to be edited</option>
	                            <?php
	                                $conn->query("use $database");
	                                $sql = "select users.*, staff.*, departments.*,roles.* 
	                                        from users inner join staff on users.userid = staff.staffid 
	                                        inner join departments on staff.deptid = departments.deptid 
	                                        inner join roles on users.roleid = roles.roleid";
	
	                                $result = $conn->query($sql);
	
	                                if($result->num_rows > 0){
	                                    while($rows = $result->fetch_assoc()){
	                                        $name = $rows["firstname"]." ".$rows["middlename"]." ".$rows["lastname"];
	                                        $userid = $rows["userid"];
	                                        $department = $rows["department"];
	                                        $role = $rows["role"];
	                                        $email = $rows["email"];
	                                        $phone = $rows["telephone"];
	
	                                        echo "<option value = '$userid' user_name = '$name' department = '$department' role = '$role' 
	                                                email = '$email' phone = '$phone', userid = '$userid'>".
	                                                    $name.
	                                                "</option>";
	                                    }
	                                }
	                            ?>
                                
	                        </select>
	                        <div id = "edit-user">
	                            <p>
	                                Edit The appropraite fields below
	                            </p>
	                            <input type="text" name="acct-user-name" id="acct-user-name" class="form-control">
	                            <input type="text" name="acct-user-userid" id="acct-user-userid" class="form-control">
	                            <input type="text" name="acct-user-dept" id="acct-user-dept" class="form-control">
	                            <input type="text" name="acct-user-role" id="acct-user-role" class="form-control">
	                            <input type="text" name="acct-user-email" id="acct-user-email" class="form-control">
	                            <input type="text" name="acct-user-phone" id="acct-user-phone" class="form-control">
	                            <button type = "button" class=" btn btn-link" id = "pword-btn" onclick="show_password(id)">Change Password</button>
	                            <hr>
	                            <input type="hidden" name="acct-user-pword" id="acct-user-pword" class="form-control" placeholder = "New Password">
	                            <input type="hidden" name="acct-user-pword2" id="acct-user-pword2" class="form-control" placeholder = "Repeat Password" oninput = "checkpword()">
	                            <p id = "password-status"></p>
	                            <input type="button" id = "edit-this-user" value="Edit User">
	                         </form>
                        </div>
                        </form>
                    </div>
                </section>
                <section class="main-section" id="new-jobs-section">
                    <span class="fa fa-power-off" onclick = "dismiss_section('new-jobs-section')"></span>
                    <button class="card-stats" 
                    onclick = "document.getElementById('create-job-section').style.display = 'block';document.getElementById('job-title').focus();">
                        Create Jobs
                    </button>
                    <div id = "create-job-section">
                        <input type="text" class="form-control" name="job-title" id="job-title" placeholder="Type Job Name Here"
                        style = "width:50%; margin:10px 0;">
                        <select name="job-cat" id="job-cat" style = "width:50%; margin:10px 0;"  onchange = "checkJobAvail()">
                            <option value="0">Choose A Category for this Job</option>
                            <?php
                                $sql = "select * from $database.categories";
                                $result = $conn->query($sql);

                                if($result->num_rows > 0){
                                    while($rows = $result->fetch_assoc()){
                                        $catid = $rows["catid"];
                                        $cat = $rows["category"];
                                        echo "<option value = '$catid'>$cat</option>";
                                    }
                                }
                            ?>
                        </select>
                        <hr>
                        <div id="tasks-section">
                            <p style = "margin:0">Add Tasks To This Job</p>
                            <div class="tasks">
                                <table id = "new-task-table">
                                    <tr>
                                        <td>Task</td>
                                        <td>Weeks</td>
                                        <td>Days</td>
                                        <td>Hours</td>
                                        <td>Mins</td>
                                        <td>Secs</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="task_title" placeholder="Add A Task"></td>
                                        <td><input type="text" class="duration_weeks" placeholder="Weeks" value="0"></td>
                                        <td><input type="text" class="duration_days" placeholder="Days" value="0"></td>
                                        <td><input type="text" class="duration_hours" placeholder="Hours" value="0"></td>
                                        <td><input type="text" class="duration_mins" placeholder="Minutes" value="0"></td>
                                        <td><input type="text" class="duration_secs" placeholder="Seconds" value="0"></td>
                                        <td><button title="Add another task" onclick="add_new_task()"><span class="fa fa-add"></span></button></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <button id = "can-create-job" class="btn btn-success" onclick = "createJob()" disabled>Create</button>
                        <p id = "new-job-status"></p>
                    </div>
                </section>
                <section class="main-section" id="new-holidays-section"">
                    <span class="fa fa-power-off" onclick = "dismiss_section('new-holidays-section')"></span>
                    <input type="date" class="form-control">
                </section>
                <section class="main-section" id="new-utility-section">
                    <span class="fa fa-power-off" onclick = "dismiss_section('new-utility-section')"></span>
                    <h5 class="text">Performance Table</h5>
                </section>
                <section class="main-section" id="new-profile-section">
                    <span class="fa fa-power-off" onclick = "dismiss_section('new-profile-section')"></span>
                    <h5 class="text">My Profile</h5>
                </section>
            </main>
        </div>
    </div>

    
    <!-- Bootstrap JS + FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function dismiss_section(id){
            document.getElementById(id).style.display = 'none';
            document.getElementById('new-dashboard-section').style.display = 'block';
        }
        function expandMenu(id){
            if(document.getElementById(id).style.display == "block"){
                document.getElementById(id).style.display = "none";
            }
            else{
                document.getElementById(id).style.display = "block";
            }
        }

        function toggleSection(id){ 
            // document.getElementById("menu").style.display = "none";
            if(id != "logout-section"){
                id = "new-"+id;
                sections = document.getElementsByClassName("main-section");

                for(i = 0; i < sections.length; i++){
                    sections[i].style.display = "none";
                }

                document.getElementById(id).style.display = "block";
            }
            else{
                location.replace("../../backends/process_logout.php")
            }
        }

        function switchDeptForm(id){
            buttons = document.getElementsByClassName("deptbtns");
            for(i = 0; i < buttons.length; i++){
                buttons[i].style.borderStyle = "solid";
                buttons[i].style.borderColor = "lightgrey";
                buttons[i].style.borderWidth = "1px";
            }
            document.getElementById(id).style.borderStyle = "groove";
            document.getElementById(id).style.borderColor = "darkblue";
            document.getElementById(id).style.borderWidth = "3px";

            forms = document.getElementsByClassName("deptForms");
            for(i = 0; i < buttons.length; i++){
                forms[i].style.display = "none";
            }
            formid = "add-"+id;
            document.getElementById(formid).style.display = "block";
        }

        function switchUserForm(id){
            buttons = document.getElementsByClassName("userbtns");
            for(i = 0; i < buttons.length; i++){
                buttons[i].style.borderStyle = "solid";
                buttons[i].style.borderColor = "lightgrey";
                buttons[i].style.borderWidth = "1px";
            }
            document.getElementById(id).style.borderStyle = "groove";
            document.getElementById(id).style.borderColor = "darkblue";
            document.getElementById(id).style.borderWidth = "3px";

            forms = document.getElementsByClassName("userForms");
            for(i = 0; i < buttons.length; i++){
                forms[i].style.display = "none";
            }
            formid = "add-"+id;
            document.getElementById(formid).style.display = "block";
        }

        function resetLabel(){
            document.getElementById("new-dept-search").innerHTML = "";
        }

        function searchDept(id){
            dept = document.getElementById(id).value;
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                // alert(this.readyState + " -- " + this.status);
                if(this.readyState == 4 && this.status == 200){
                    if(dept != ""){
                        if(this.responseText.includes("exists") == false){
                            // extract the number of p tags in the response text
                            count = this.responseText.split("<p>").length - 1;
                            //alert(count);
                            if(count == 0){
                                document.getElementById("new-dept-search-status").innerHTML = "";
                                document.getElementById("newDept").style.display = "block";
                            }
                            else if(count == 1){
                                document.getElementById("new-dept-search-status").innerHTML = this.responseText + " already exists";
                                document.getElementById("newDept").style.display = "none";
                            }
                        }
                        else{
                            document.getElementById("new-dept-search").innerHTML = this.responseText;
                            document.getElementById("new-dept").select();
                            document.getElementById("new-dept").focus();
                        }
                    }
                    else{
                        document.getElementById("new-dept-search").innerHTML = "";
                    }
                    
                }
            };
            xml.open("GET","../../backends/getDepartment.php?department="+dept,true);
            xml.send();
        }

        function addDept(){
            dept = document.getElementById("new-dept").value;
            staff = document.getElementById("dept-head").value;
            email = document.getElementById("dept-email").value;
            phone = document.getElementById("dept-phone").value;

            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("new-dept").value = "";
                    document.getElementById("dept-head").value = "";
                    document.getElementById("dept-email").value = "";
                    document.getElementById("dept-phone").value = "";
                    document.getElementById("add-new-dept").disabled = true;
                    document.getElementById("newDept").style.display = "none";
                    document.getElementById("new-dept").focus();
                    document.getElementById("new-dept-search-status").innerHTML = this.responseText;
                }
            };
            if(email == ""){
                xml.open("GET","../../backends/add_new_dept.php?department="+dept+"&staffname="+staff,true);
            }
            else{
                xml.open("GET","../../backends/add_new_dept.php?department="+dept+"&staffname="+staff+"&staffemail="+email+"&telephone="+phone,true);
            }
            xml.send();
        }

        function searchStaff(id){
            staff = document.getElementById(id).value;

            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                // alert(this.readyState + " - " + this.status)
                if(staff != ""){
                    if(this.readyState == 4 && this.status == 200){
                        if(this.responseText.includes("New Staff Name")){
                            document.getElementById("new-staff-search").innerHTML = "";
                            document.getElementById("add-dept-btn").style.display = "block";
                            document.getElementById("dept-email-label").style.display = "block";
                            document.getElementById("dept-email").style.display = "block";
                            document.getElementById("dept-phone-label").style.display = "block";
                            document.getElementById("dept-phone").style.display = "block";
                        }
                        else{
                            document.getElementById("new-staff-search").innerHTML = this.responseText;
                            document.getElementById("dept-email-label").style.display = "none";
                            document.getElementById("dept-email").style.display = "none";
                            document.getElementById("dept-phone-label").style.display = "none";
                            document.getElementById("dept-phone").style.display = "none";
                        }
                    }
                }
                else{
                    document.getElementById("new-staff-search").innerHTML = "";
                    document.getElementById("add-dept-btn").style.display = "none";
                }
            };
            xml.open("GET","../../backends/get_staff.php?staffname="+staff,true);
            xml.send();  

        }

        function staffSearch(id){
            staff = document.getElementById(id).value;

            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                // alert(this.readyState + " - " + this.status)
                if(this.readyState == 4 && this.status == 200){
                    if(this.responseText == "0"){
                        document.getElementById("add-dept-btn").style.display = "block";
                    }
                }
            };
            xml.open("GET","../../backends/get_staff.php?staffname="+staff,true);
            xml.send(); 
        }

        function verifyEmail(id){
            email = document.getElementById(id).value;

            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("new-email-search").innerHTML = this.responseText;
                    if(this.responseText.includes("Exists!")){
                        document.getElementById("add-dept-btn").disabled = true;
                        document.getElementById(id).focus();
                        document.getElementById(id).select();
                    }
                    else{
                        document.getElementById("add-dept-btn").disabled = false;
                    }
                }
            };
            xml.open("GET","../../backends/verify_staff_email.php?email="+email,true);
            xml.send(); 
        }

        function setStaff(staff){
            document.getElementById("dept-head").value = staff;
            document.getElementById("add-dept-btn").style.display = "block";
            document.getElementById("add-dept-btn").disabled = false;
        }

        function searchUsers(id){
            user = document.getElementById(id).value;
            alert(user);
        }

        function newStaff(id){
            staff = document.getElementById(id).value;
            // alert(staff);
            if(staff != ""){
                names = staff.split(" ");
                if(names.length >= 2){
                    xml = new XMLHttpRequest();
                    xml.onreadystatechange = function(){
                        if(this.readyState == 4 && this.status == 200){
                            document.getElementById("new-user-search").innerHTML = this.responseText;
                            if(this.responseText.includes("exists")){
                                document.getElementById("new-user").focus();
                                document.getElementById("new-user").select();
                            }
                            else{
                                document.getElementById("newUser").style.display = "block";
                                document.getElementById("user-email").focus();
                            }
                        }
                    };
                    xml.open("GET","../../backends/getstaff.php?staffname="+staff,true);
                    xml.send();
                }
                 else{
                    alert("Users Must have at least 2 names");
                    document.getElementById(id).select();
                 }    
            }
            else{
                document.getElementById("new-user-search").innerHTML = "";
            }
        }

        function clearLabel(){
            label = document.getElementById("new-user-search");
            label.innerHTML = "";
        }

        function add_a_user(){
            alert("Adding A User");
            staffname = document.getElementById("new-user").value;
            staffrole = document.getElementById("user-role").value;
            staffdept = document.getElementById("user-dept").value;
            staffemail = document.getElementById("user-email").value;
            staffphone = document.getElementById("user-phone").value;

            if(staffrole == "00004" || staffrole == "00003"){
                jobs = document.getElementById("allowed-jobs");
                allowed_jobs = [];
                for(job of jobs.options){
                    if(job.selected){
                        allowed_jobs.push(job.value);
                    }
                }
                alert(allowed_jobs.length);
            }

            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("add-new-user-status").innerHTML = this.responseText;
                    document.getElementById("add-single-user").reset();
                    document.getElementById("new-user").focus();
                    document.getElementById("new-email-status").innerHTML = "";
                }
            };
            if(staffrole == "00004" || staffrole == "00003"){
                if(staffrole == "00003"){
                    dbtable = "handlers";
                }
                else{
                    dbtable = "supervisors";
                }
                xml.open("GET","../../backends/add_new_staff.php?staffname="+staffname+"&role="+staffrole+"&dept="+staffdept+"&email="+
                staffemail+"&phone="+staffphone+"&allowed_jobs="+allowed_jobs+"&db_table="+dbtable,true);
            }
            else{
                xml.open("GET","../../backends/add_new_staff.php?staffname="+staffname+"&role="+staffrole+"&dept="+staffdept+"&email="+staffemail+"&phone="+staffphone,true);
            }

            xml.send();
        }

        function addAccts(){
            document.getElementById('new-accounts-section').style.display = 'none';
            document.getElementById('accts2').style.display = 'none';
            document.getElementById('accts1').style.display = 'block';
            document.getElementById('new-user-section').style.display = 'block';
        }

        function displayAccts(id){
            document.getElementById("accts1").style.display = "none";
            accts = document.getElementsByClassName("accounts-section");
            for(i = 0; i < accts.length; i++){
                accts[i].style.display = "none";
            }
            sectionid = id.replace("-","");
            document.getElementById(sectionid).style.display = "block";
        }

        function editUser(){
            user = document.getElementById("select-user-edit");
            index = user.selectedIndex;
            document.getElementById("acct-user-name").value = user[index].getAttribute("user_name");
            document.getElementById("acct-user-dept").value = user[index].getAttribute("department");
            document.getElementById("acct-user-role").value = user[index].getAttribute("role");
            document.getElementById("acct-user-email").value = user[index].getAttribute("email");
            document.getElementById("acct-user-phone").value = user[index].getAttribute("phone");
            document.getElementById("acct-user-userid").value = user[index].getAttribute("userid");

            document.getElementById("acct-user-name").select();
            // document.getElementById("").value = user[index].getAttribute("");
        }

        function show_password(id){
            document.getElementById(id).style.display = "none";
            document.getElementById("acct-user-pword").setAttribute("type","password");
            document.getElementById("acct-user-pword2").setAttribute("type","password");
        }

        function checkpword(){
            if(document.getElementById("acct-user-pword").value != document.getElementById("acct-user-pword2").value){
                document.getElementById("password-status").innerHTML = "Passwords Fields Must Match";
                document.getElementById("password-status").style.color = "red";
            }
            else{
                document.getElementById("password-status").innerHTML = "";
            }
        }
        
        function verify_email(id){
            email = document.getElementById(id).value;
            
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("new-email-status").innerHTML = this.responseText;
                    if(this.responseText == "Email already Exists!"){
                        document.getElementById(id).focus();
                    }
                }
            };
            xml.open("GET","../../backends/verify_staff_email.php?email="+email,true);
            xml.send();
        }
        function searchcategories(id){
            category = document.getElementById(id).value;
            if(category != ""){
                xml = new XMLHttpRequest();
                xml.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        if(this.responseText.includes("<p")){
                        	document.getElementById("categories-exists").innerHTML = this.responseText;
                        	document.getElementById("add-category-btn").style.display = "none";
                        }
                        else{
                        	document.getElementById("add-category-btn").style.display = "block";
                        }
                    }
                }
                xml.open("GET","../../backends/getCategories.php?category="+category,true);
                xml.send();
            }
            else{
                document.getElementById("categories-exists").innerHTML = "";
            }
        }
        
        function addCategory(){
            category = document.getElementById("category-name").value;
            
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    if(this.responseText.includes("successfully")){
                    	alert("Job category " + category + " created successfuly");
                    	document.getElementById("category-name").value = "";
                    }
                    else{
                    	alert("Job category " + category + " was not created");
                    	document.getElementById("category-name").focus();
                    }
                }
            }
            xml.open("GET","../../backends/createCategory.php?category="+category,true);
            xml.send();	
        }
        
        function get_job_owner(id){
            document.getElementById("jobdetails").style.display = "block";
            job = document.getElementById(id);
            index = job.selectedIndex;
            job_name = job.options[index].text;
            document.getElementById("jrn").innerHTML = job_name;
            document.getElementById("jrc").innerHTML = job.options[index].getAttribute("client");
            document.getElementById("jrd").innerHTML = job.options[index].getAttribute("job_date");
        }
        
        function setvalue(id){
            value_id = "set_" + id;
            v = document.getElementById(id).value;
            document.getElementById(value_id).value = v;
        }
        
        function setDuration(){
            jobid = document.getElementById("submitted_jobs").value;
            hours = document.getElementById("set_hours").value;
            days = document.getElementById("set_days").value;
            weeks = document.getElementById("set_weeks").value;
            months = document.getElementById("months").value;
            
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
               // alert(this.readyState + " - " + this.status);
                if(this.readyState == 4 && this.status == 200){
                   // alert(this.responseText);
                    document.getElementById("jobduration").innerHTML = document.getElementById("jobduration").innerHTML + this.responseText;
                } 
            };
            xml.open("GET","../../backends/set_job_duration.php?hours="+hours+"&days="+days+"&weeks="+weeks+"&months="+months+"&jobid="+jobid,true)
            xml.send();
        
        } 
        
        function setNewCat(){
            cats = document.getElementById("jobcat");
            if(cats.selectedIndex == 1){
                document.getElementById("new_category").setAttribute("type","text");
                document.getElementById("new_category").focus();
            }
            else{
                document.getElementById("new_category").setAttribute("type","hidden");
            }
        } 
        
        function assign_request(){
            jobcat = document.getElementById("jobcat").value;
            if(jobcat == "newcat"){
            	new_category = document.getElementById("new_category").value;
            }
            jobid = document.getElementById("assigned_job_id").value;
            supervisorid = document.getElementById("job_supervisor").value;
            
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
            	if(this.readyState == 4 && this.status == 200){
            		alert(this.responseText);
            		document.getElementById("set_hours").value = "0";
            		document.getElementById("set_days").value = "0"; 
            		document.getElementById("set_weeks").value = "0";
            		document.getElementById("months").value = "0";
            		document.getElementById("jobcat").selectedIndex = 0;
            		document.getElementById("job_supervisor").selectedIndex = 0;
            		
            	}
            };
            if(jobcat != "newcat"){
                xml.open("GET","../../backends/complete_assign_job.php?catid="+jobcat+"&assigned_to="+supervisorid + "&jobid=" + jobid,true);
            }
            else{
                xml.open("GET","../../backends/complete_assign_job.php?new_category="+new_category+"&assigned_to="+supervisorid + "&jobid=" + jobid,true);
            }
            xml.send();
        }

        function create_new_job(){
            sections = document.getElementsByClassName("main-section");
            for(i = 0; i < sections.length; i ++){
                sections[i].style.display = "none";
            }
          //  document.getElementById("new-dashboard-section").style.display = "none";
            document.getElementById("new-jobs-section").style.display = "block";
        }

        function createJob(){
            job = document.getElementById('job-title').value;
            category = document.getElementById('job-cat').value;
            tasks = document.getElementsByClassName("task_title");
            weeks = document.getElementsByClassName("duration_weeks");
            days = document.getElementsByClassName("duration_days");
            hours = document.getElementsByClassName("duration_hours");
            mins = document.getElementsByClassName("duration_mins");
            secs = document.getElementsByClassName("duration_secs");

            job_tasks = "";
            job_weeks = "";
            job_hours = "";
            job_days = "";
            job_mins = "";
            job_secs = "";

            for(i = 0; i < tasks.length; i++){
                job_tasks += tasks[i].value;
                job_weeks += weeks[i].value;
                job_days += days[i].value;
                job_hours += hours[i].value;
                job_mins += mins[i].value;
                job_secs += secs[i].value;

                if(i < tasks.length-1){
                    job_tasks += ",";
                    job_weeks += ",";
                    job_days += ",";
                    job_hours += ",";
                    job_mins += ",";
                    job_secs += ",";
                }
            }
            // alert(job_tasks);

            if(tasks[tasks.length-1].value == ""){ 
                alert("You can't Create an Empty Task");
                tasks[tasks.length-1].focus();
            }
            else{ 
                xml = new XMLHttpRequest();
                xml.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        document.getElementById("new-job-status").innerHTML = this.responseText;
                        document.getElementById('job-title').value = "";
                        document.getElementById('job-cat').selectedIndex = 0;
                        task_table = document.getElementById("new-task-table");
                        for(i = 2; i < task_table.rows.length; i ++){
                            task_table.deleteRow[i];
                        }
                    }
                };
                xml.open("GET","../../backends/adminNewJobs.php?job_title="+job + "&job_cat=" + category +
                "&tasks=" + job_tasks + "&weeks=" + job_weeks + "&days=" + job_days + "&hours=" + job_hours +
                "&mins=" + job_mins + "&secs=" + job_secs,true);
                xml.send();
            } 
        }

        function checkJobAvail(){
            job = document.getElementById('job-title').value;
            category = document.getElementById('job-cat').value;
            if(job == ""){
                alert("Can't create an empty job");
                document.getElementById("job-title").value = "Type a Job Title";
                document.getElementById("job-title").select();
            }
            else{
                xml = new XMLHttpRequest();
                xml.onreadystatechange = function(){ 
                    if(this.readyState == 4 && this.status == 200){
                        if(this.responeText != "proceed"){
                            document.getElementById("tasks-section").style.display = "block";
                            document.getElementById("can-create-job").disabled = false;
                        }
                        else{
                            document.getElementById("new-job-status").innerHTML = this.responseText;
                        }
                        
                    }
                };

                xml.open("GET","../../backends/checkJobAvail.php?job_title="+job+"&job_cat="+category,true);
                xml.send();
            }
        }

        function showJobs(id){
            role = document.getElementById(id).value
            if(role == "00004" || role == "00003"){
               document.getElementById("allowed-jobs").style.display = "block";
               document.getElementById("allowed-jobs-label").style.display = "block";
            }
            else{
                document.getElementById("allowed-jobs").style.display = "none";
               document.getElementById("allowed-jobs-label").style.display = "none";
            }

        }

        function add_new_task(){
            tasks = document.getElementById("new-task-table");
            rn = tasks.rows.length; 
            row = tasks.insertRow(rn);
            task_col = row.insertCell(0); task_col.innerHTML = "<input type = 'text' class = 'task_title' placeholder = 'Add A Task'>";
            week_col = row.insertCell(1); week_col.innerHTML = "<input type = 'text' class = 'duration_weeks' placeholder = 'Weeks' value = '0'>";
            days_col = row.insertCell(2); days_col.innerHTML = "<input type = 'text' class = 'duration_days' placeholder = 'Days' value = '0'>";
            hours_col = row.insertCell(3); hours_col.innerHTML = "<input type = 'text' class = 'duration_hours' placeholder = 'Hours' value = '0'>";
            mins_col = row.insertCell(4);  mins_col.innerHTML = "<input type = 'text' class = 'duration_mins' placeholder = 'Minutes' value = '0'>";
            secs_col = row.insertCell(5);  secs_col.innerHTML = "<input type = 'text' class = 'duration_secs' placeholder = 'Seconds' value = '0'>";
            add_col = row.insertCell(6); add_col.innerHTML = '<button title="Add another task" onclick="add_new_task()"><span class="fa fa-add"></span></button>';
            this.disabled = true;
        }

        $("#edit-this-user").click(function(){
            select = document.getElementById("select-user-edit");
            user = select.options[select.selectedIndex].text;
            userid = document.getElementById("select-user-edit").value;
            newname = document.getElementById("acct-user-name").value;
            newdept = document.getElementById("acct-user-dept").value;
            newrole = document.getElementById("acct-user-role").value;
            newemail = document.getElementById("acct-user-email").value;
            newphone = document.getElementById("acct-user-phone").value;
            newuserid = document.getElementById("acct-user-userid").value; 
            password = document.getElementById("acct-user-pword").value;

            $.post("../../backends/edit_user.php",
            {
                userid:userid,
                name: newname,
                department:newdept,
                role:newrole,
                email:newemail,
                phone:newphone,
                newuserid:newuserid,
                password:password
            },
            function(data,status){
                alert(user + data + "\nStatus: " + status);
                select = document.getElementById("select-user-edit");
                select.selectedIndex = 0;
                document.getElementById("acct-user-name").value = "";
                document.getElementById("acct-user-dept").value = "";
                document.getElementById("acct-user-role").value = "";
                document.getElementById("acct-user-email").value = "";
                document.getElementById("acct-user-phone").value = "";
                document.getElementById("acct-user-userid").value = ""; 
                document.getElementById("acct-user-pword").value = "";
            });
        });
        

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