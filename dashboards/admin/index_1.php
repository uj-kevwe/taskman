<?php
    session_start();
    include "../../db/setup.php";
    $c = new crudOps();
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
                top: 40px;
                left: 0;
                right: 0;
                z-index: 0;
            }
            .main-section{
                position: absolute;
                top: 60px;
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
                    <a href="javascript:void(0)" class="nav-link" id = "department-section" onclick="toggleSection(id)"><i class="fas fa-file-alt"></i>Add New Department</a>
                    <a href="javascript:void(0)" class="nav-link" id = "user-section" onclick="toggleSection(id)"><i class="fas fa-user"></i>Add New User</a>
                    <a href="javascript:void(0)" class="nav-link" id = "category-section" onclick="toggleSection(id)"><i class="fas fa-cog"></i>Add New Job Category</a>
                    <a href="javascript:void(0)" class="nav-link" id = "job-section" onclick="toggleSection(id)"><i class="fas fa-cog"></i>Assign Job</a>
                    <a href="javascript:void(0)" class="nav-link mt-4" id = "logout-section" onclick="toggleSection(id)"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 content">
                
                <section class="main-section" id="new-dashboard-section">
                    
                    <h2 class="mb-4 fw-bold">Super Admin Dashboard</h2>

                    <!-- Stats -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card-stats">
                                <h3>150</h3>
                                <p>Total Requests</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card-stats">
                                <h3>40</h3>
                                <p>Pending Requests</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card-stats">
                                <h3>90</h3>
                                <p>Completed Requests</p>
                            </div>
                        </div>
                    </div>

                    <!-- Requests Table -->
                    <h5 class="fw-bold mb-3">All Requests</h5>
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
                    </div>
                </section>
                <section class="main-section" id="new-department-section">
                    <button class="mb-4 fw-bold deptbtns" id = "single-dept" onclick="switchDeptForm(id)">Add A New Department</button>
                    <button class="mb-4 fw-bold deptbtns" id = "bulk-dept" onclick="switchDeptForm(id)">Upload Bulk Departments</button>
                    <form action="" method="post" class = "deptForms" id="add-single-dept">
                        <p style="margin-bottom:1px;">New Department Name</p>
                        <input type="text" class="form-control" name="new-dept" id="new-dept" onfocusout = "searchDept(id)" oninput = "resetLabel()">
                        <p id = "new-dept-search"></p>
                        <div class="new-features" id="newDept">
                            <p style="margin-bottom:1px;">Head of Department Name (Surname First)</p>
                            <input type="text" class="form-control m-2" name="dept-head" id="dept-head" oninput = "searchStaff(id)" onfocusout = "staffSearch(id)">
                            <div id = "new-staff-search"></div>
                            <p id = "dept-email-label" style="margin-bottom:1px;">Head of Department Email</p>
                            <input type="email" class="form-control m-2" name="dept-email" id="dept-email" onfocusout = "verifyEmail(id)">
                            <p id = "dept-phone-label">Head of Department Phone</p>
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
                        <input type="submit" value="Upload File">
                    </form>
                </section>
                <section class="main-section" id="new-user-section">
                    <button class="mb-4 fw-bold userbtns" id = "single-user" onclick="switchUserForm(id)">Add A New User</button>
                    <button class="mb-4 fw-bold userbtns" id = "bulk-user" onclick="switchUserForm(id)">Upload Bulk Users</button>
                    <form class="userForms" id = "add-single-user">
                        <input type="text" class="form-control" name="new-user" id="new-user" placeholder="New Staff Name" onfocusout = "newStaff(id)" oninput = "clearLabel()">
                        <p id = "new-user-search"></p>
                        <div class="new-features" id="newUser">
                            <input type="email" name="user-email" id="user-email" class="form-control" placeholder="New Staff Email">
                            <input type="tel" name="user-phone" id="user-phone" class="form-control" placeholder="New Staff Phone Number">
                            <select name="user-role" id="user-role">
                                <option value="0">Select A User Role</option>
                                <option value="00003">Handler</option>
                                <option value="00004">Supervisor</option>
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
                <section class="main-section" id="new-category-section">
                    <h2 class="mb-4 fw-bold">Add A New Job Category</h2>
                </section>
                <section class="main-section" id="new-job-section">
                    <h2 class="mb-4 fw-bold">Assign Job</h2>
                </section>
            </main>
        </div>
    </div>

    
    <!-- Bootstrap JS + FontAwesome -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        function expandMenu(id){
            if(document.getElementById(id).style.display == "block"){
                document.getElementById(id).style.display = "none";
            }
            else{
                document.getElementById(id).style.display = "block";
            }
        }

        function toggleSection(id){
            document.getElementById("menu").style.display = "none";
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
                        }
                        else{
                            document.getElementById("new-staff-search").innerHTML = this.responseText;
                         /*   document.getElementById("dept-email-label").style.display = "none";
                            document.getElementById("dept-email").style.display = "none";
                            document.getElementById("dept-phone-label").style.display = "none";
                            document.getElementById("dept-phone").style.display = "none"; */
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
           // alert(user);
        }

        function newStaff(id){
            staff = document.getElementById(id).value;
            // alert(staff);
            if(staff != ""){
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
                        }
                    }
                };
                xml.open("GET","../../backends/getstaff.php?staffname="+staff,true);
                xml.send(); 
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
            staffname = document.getElementById("new-user").value;
            staffrole = document.getElementById("user-role").value;
            staffdept = document.getElementById("user-dept").value;
            staffemail = document.getElementById("user-email").value;
            staffphone = document.getElementById("user-phone").value;

            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    document.getElementById("add-new-user-status").innerHTML = this.responseText;
                }
            };
            xml.open("GET","../../backends/add_new_staff.php?staffname="+staffname+"&role="+staffrole+"&dept="+staffdept+"&email="+staffemail+"&phone="+staffphone,true);
            xml.send();
        }

    </script>

</body>
</html>