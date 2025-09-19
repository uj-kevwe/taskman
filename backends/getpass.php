<?php
    $length = 10;
    function generateRandomString(){
        $length = 12;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz!#$%^&*()_+';
        $randomString = '';
        $charactersLength = strlen($characters);
        for($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0,47)];
        }
        return $randomString;
    }
    try{
        $pword = generateRandomString();
    }
    catch(Exception $e){
        $pword = "12lm#*yht5%";
    }
    echo $pword;