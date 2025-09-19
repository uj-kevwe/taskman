 <?php
    include "connect.php";
    
    $sql = "create database if not exists $database";
    $conn->query($sql);

    if(!$conn->error){
        $c = new crudOps();
        // create users table
        $users = array(
                        "userid varchar(100) not null unique,",
                        "roleid varchar(100) not null,",
                        "email varchar(100) not null unique,",
                        "telephone varchar(20),",
                        "password text not null"
                    );
        
        $c->createTable($conn,$database,"users",$users);
         
        // create staff table
        $staff = array(
                        "staffid varchar(100) not null unique,",
                        "firstname varchar(100) not null,",
                        "middlename varchar(100),",
                        "lastname varchar(100) not null,",
                        "deptid varchar(100) not null"
                    );
        
        $c->createTable($conn,$database,"staff",$staff);

        // create staff table
        $clients = array(
                        "clientid varchar(100) not null unique,",
                        "firstname varchar(100) not null,",
                        "middlename varchar(100),",
                        "lastname varchar(100) not null,",
                        "email varchar(100) not null,",
                        "gender varchar(10) not null,",
                        "address text not null,",
                        "picture text,",
                        "date_joined date"
                    );
        
        $c->createTable($conn,$database,"clients",$clients);
        
        // create jobs table
        $jobs = array(
                        "jobid varchar(100) not null unique,",
                        "catid varchar(100) not null,",
                        "job_title varchar(100) not null,",
                        "tasks_title text not null,",
                        "duration_weeks text not null,",
                        "duration_days text not null,",
                        "duration_hours text not null,",
                        "duration_minutes text not null,",
                        "duration_seconds text not null"
                    );
        $c->createTable($conn,$database,"jobs",$jobs);

        $tasks = array(
                        "taskid varchar(100) not null unique,",
                        "requestid varchar(100) not null,",
                        "task_name varchar(100) not null,",
                        "supervised_by varchar(100) not null,",
                        "time_assigned datetime not null,",
                        "prev_handler_id varchar(100) not null,",
                        "current_handler_id varchar(100) not null,",
                        "duration_weeks int not null,",
                        "duration_days int not null,",
                        "duration_hours int not null,",
                        "duration_minutes int not null,",
                        "duration_seconds int not null,",
                        "task_status_id varchar(100) not null,",
                        "time_status_changed datetime null,",
                        "job_completed_status int(1) not null"
        );
        $c->createTable($conn,$database,"tasks",$tasks);

        // create supervisors table
        $supervisors = array(
                        "staffid varchar(100) not null unique,",
                        "allowed_jobs_id text not null,",
                        "accept_flag int(1) not null default 0"
                    );
        $c->createTable($conn,$database,"supervisors",$supervisors);
        
        // create handlers table
        $handlers = array(
                        "staffid varchar(100) not null unique,",
                        "allowed_jobs_id text not null,",
                        "accept_flag int(1) not null default 0"
                    );
        $c->createTable($conn,$database,"handlers",$handlers);

        // create roles table
        $roles = array(
                        "roleid varchar(100) not null unique,",
                        "role varchar(100) not null"
                    );
        $c->createTable($conn,$database,"roles",$roles);

        // create departments table
        $depts = array(
                        "deptid varchar(100) not null unique,",
                        "department varchar(100) not null,",
                        "hodid varchar(100) not null"
                    );
        $c->createTable($conn,$database,"departments",$depts);

        // create categories table
        $cats = array(
                        "catid varchar(100) not null unique,",
                        "category varchar(100) not null"
                    );
        $c->createTable($conn,$database,"categories",$cats);

        // create requests table
        $requests = array(
                        "requestid varchar(100) not null unique,",
                        "jobid varchar(100) not null,",
                        "clientid varchar(100) not null,",
                        "job_date datetime not null,",
                        "supervised_by varchar(100) null,",
                        "job_status_id varchar(10) not null"
                    );
        $c->createTable($conn,$database,"requests",$requests);

        // create job_status table
        $status = array(
                        "statusid varchar(10) not null unique,",
                        "status varchar(100) not null,",
                        "colour_code varchar(6) not null"
                    );
        $c->createTable($conn,$database,"job_status",$status);

        // create reset_code table
        $reset_codes = array(
                        "email varchar(100) not null unique,",
                        "code varchar(6) not null,",
                        "time datetime not null,",
                        "status int(1) not null default 1"
                    );
        $c->createTable($conn,$database,"reset_codes",$reset_codes);

        // create task_status table
        $status = array(
                        "statusid varchar(10) not null unique,",
                        "status varchar(100) not null,",
                        "colour_code varchar(6) not null"
                    );
        $c->createTable($conn,$database,"task_status",$status);

        // add admin to users table
        // "userid","roleid","email","telephone","password"
        $all_users = $c->readData($conn,$database,"users");
        $default_pass = password_hash("defaultpassword",PASSWORD_DEFAULT);
        if($all_users->num_rows == 0){
            // insert admin
            $fields = array("userid","roleid","email","telephone","password");
            $values = array(array("adminKevwe","00001","uj.kevwe@gmail.com","07065956369","$default_pass"));
            $c->addRecords($conn,$database,"users",$fields,$values);
        }

        // add admin to staff table
        // "staffid","firstname","middlename","lastname","deptid"
        $all_staff = $c->readData($conn,$database,"staff");
        if($all_staff->num_rows == 0){
            // insert admin
            $fields = array("staffid","firstname","middlename","lastname","deptid");
            $values = array(array("adminKevwe","Steve","Kevwe","Omiunu","00001"));
            $c->addRecords($conn,$database,"staff",$fields,$values);
        }

        
        // add roles to roles table
        $all_roles = $c->readData($conn,$database,"roles");
        if($all_roles->num_rows == 0){
            // insert admin
            $fields = array("roleid","role");
            $values = array(
                            array("00001","Admin"),
                            array("00002","Clients"),
                            array("00003","Handler"),
                            array("00004","Supervisor")
                           
                        );
            $c->addRecords($conn,$database,"roles",$fields,$values);
        }
        
        // add job_status to job_status table
        $all_job_status = $c->readData($conn,$database,"job_status");
        if($all_job_status->num_rows == 0){
            // insert admin
            $fields = array("statusid","status","colour_code");
            $values = array(
                            array("00001","Submitted","#000000"),
                            array("00002","Assigned","#FFFF00"),
                            array("00003","In Progress","#0000FF"),
                            array("00004","Completed","#00FF00"),
                            array("00005","Overdue","#FF0000")
                           
                        );
            $c->addRecords($conn,$database,"job_status",$fields,$values);
        }
        
        // add task_status to task_status table
        $all_task_status = $c->readData($conn,$database,"task_status");
        if($all_task_status->num_rows == 0){
            // insert admin
            $fields = array("statusid","status","colour_code");
            $values = array(
                            array("00001","Assigned","#FFFF00"),
                            array("00002","Accepted","#e45d0eff"),
                            array("00003","Rejected","#000000"),
                            array("00004","In Progress","#0000FF"),
                            array("00005","Completed","#00FF00"),
                            array("00006","Overdue","#FF0000")
                           
                        );
            $c->addRecords($conn,$database,"task_status",$fields,$values);
        }

        // add Admin to department table
        $all_depts = $c->readData($conn,$database,"departments");
        if($all_depts->num_rows == 0){
            // insert admin
            $fields = array("deptid","department","hodid");
            $values = array(
                            array("00001","Administration","00001")
                        );
            $c->addRecords($conn,$database,"departments",$fields,$values);
        }
    }
    else{
        echo $conn->error;
    }

    class crudOps {
        public function createtable($cn,$db,$table,$flds){
            $sql = "create table if not exists $db.$table (";
            $sql .= "sno int not null primary key auto_increment,";
            foreach($flds as $v){
                $sql .= $v;
            }
            $sql .= ")";
            $cn->query($sql);
        }

        public function readData($cn,$db,$table){
            $sql = "select * from $db.$table";
            return $cn->query($sql);
        }

        public function readFilteredData($cn,$db,$table,$where){
            $sql = "select * from $db.$table where $where";
            // echo $sql;
            return $cn->query($sql);
        }

        public function addRecords($cn,$db,$table,$fields,$values){
            $sql = "insert into $db.$table ";
            $sql .= "(";
            $i = 0;
            foreach($fields as $value){
                $sql .= $value;
                if($i < sizeof($fields)-1){
                    $i++;
                    $sql .= ",";
                }
            }
            $sql .= ") values ";

            $i = 0;
            foreach($values as $value){
                $sql .= "(";
                $j = 0;
                foreach($value as $v){
                    $sql .= "'".$v."'";
                    if($j < sizeof($value) - 1){
                        $j++;
                        $sql .= ",";
                    }
                }
                $sql .= ")";
                if($i < sizeof($values)-1){
                    $sql .= ",";
                    $i++;
                }
            }
            $cn->query($sql);

            if($cn->error){
                return $cn->error."<br>$sql";
            }
            else{
                
                return "new record added successfully to $table table";
            }
        }
    }
?>

