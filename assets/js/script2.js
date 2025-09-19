alert("Hi");
function toggleSections(id){
    alert(id);
    id = parseInt(id.substr(-1)) + 1; 
    // section_id = "section" + id;
    // job_sections = document.getElementsByClassName("jobs-section");
    // for(i = 0; i < job_sections.length; i++){
    //     job_sections[i].style.display = "none";
    // }
    // document.getElementById(section_id).style.display = "block";
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
        // alert(this.readyState + "--" + this.status);
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
    index = task.selectedIndex;
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
        document.getElementById("task-handler").disabled = false;
    }
    else{
        document.getElementById("task-duration").innerHTML = "Chose a task first.<br> It's duration will show here.................";
        document.getElementById("task-handler").disabled = true;
    }
}
function show_details(id){
    taskid = document.getElementById(id).innerHTML;
    location.replace('jobDetails.php?taskid='+taskid);
}