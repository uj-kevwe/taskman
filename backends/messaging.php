<?php
    class Message {
    	
    	public function sendMessage($to,$subject,$headers,$message){
             include "../db/connect.php";
             
             mail($to,$subject,$headers,$message);
    	}
    }
?>