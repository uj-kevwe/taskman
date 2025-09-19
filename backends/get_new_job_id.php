<?php
    include "../db/setup.php";
$conn->query("use $database");

$jobid = rand(000000,999999);
$found = true;

while($found){
    $sql = "select * from requests where jobid = '$jobid'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $jobid = rand(000000,999999);
        $found = true;
    }
    else{
        $found = false;
    }
}
echo $jobid;
