<?php
    include "../db/setup.php";
    $conn->query($sql);

    $conn->query("use $database");

    $jobid = $_GET["jobid"];
    $job_title = $_GET["job_title"];
    $c = new crudOps();
    $where = "jobid = '$jobid'";
    $this_job = $c->readFilteredData($conn,$database,"jobs",$where);
    if($this_job->num_rows > 0){
        while($rows = $this_job->fetch_assoc()){
            $tasks = $rows["tasks_title"];
            $weeks = $rows["duration_weeks"];
            $days = $rows["duration_days"];
            $hours = $rows["duration_hours"];
            $mins = $rows["duration_minutes"];
            $secs = $rows["duration_seconds"];
        }
    }
    echo "<input type = 'hidden' id = 'tasks' name = 'tasks'>";
    echo "<input type = 'hidden' id = 'duration_weeks' name = 'duration_weeks'>";
    echo "<input type = 'hidden' id = 'duration_days' name = 'duration_days'>";
    echo "<input type = 'hidden' id = 'duration_hours' name = 'duration_hours'>";
    echo "<input type = 'hidden' id = 'duration_mins' name = 'duration_mins'>";
    echo "<input type = 'hidden' id = 'duration_secs' name = 'duration_secs'>";
    $tasks = explode(",",$tasks);
    $weeks = explode(",",$weeks);
    $days = explode(",",$days);
    $hours = explode(",",$hours);
    $minutes = explode(",",$mins);
    $seconds = explode(",",$secs);
?>
<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="4" style="text-align: center;">TASKS AND MAXIMUM DURATION FOR <?php echo strtoupper($job_title); ?></th>
        </tr>
        <tr>
            <td>Task</td>
            <td>Duration</td>
            <td>Handler</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                <select class="form-control" name="request-tasks" id="request-tasks" style="width: 90%;" onchange="get_duration(id);set_durations(id)">
                    <option value="">Select A Task</option>
                    <?php
                        for($i = 0; $i < sizeof($tasks); $i++){
                            $taskid = $jobid.str_pad($i+1,5,"0",STR_PAD_LEFT);
                            echo "<option value = '$taskid' weeks = '$weeks[$i]' days = '$days[$i]' hours = '$hours[$i]' mins = '$mins[$i]' secs = '$secs[$i]'>".
                                       $tasks[$i].
                                  "</option>";
                        }
                    ?>
                </select>
            </td>
            <td id = "task-duration">
                Task duration will show here.................
            </td>
            <td id = "thandler"></td>
        </tr>assignTask()
        <tr>
            <td colspan="3" style="text-align:center">
                <p style = "margin-bottom:-10px">Remarks</p>
                <textarea name="remarks" id="remarks" placeholder="Remarks" required style="width:100%"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:center">
                <input type="hidden" id="task-title" name="task-title">
                
                <button type="button" class="btn btn-success" name = "create-single" onclick="createTasks()">
                    Assign Tasks
                </button>
            </td>
        </tr>
    </tbody>
</table>