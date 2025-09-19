function toggleMenu(){
    if(document.getElementById("small-menu").style.display == "block"){
        document.getElementById("small-menu").style.display = "none";
    }
    else{
        document.getElementById("small-menu").style.display = "block";
    }
}

function togglePwordDisplay(){
    pwd = document.getElementById("pwd");
    pwdType = document.getElementById("password").getAttribute("type");

    if(pwdType == "text"){
        document.getElementById("password").setAttribute("type","password");
        pwd.setAttribute("class","fa fa-eye");
        pwd.setAttribute("title","click to show password");
    }
    else if(pwdType == "password"){
        document.getElementById("password").setAttribute("type","text");
        document.getElementById("password").style.width = "90%";
        pwd.setAttribute("class","fa fa-eye-slash");
        pwd.setAttribute("title","click to hide password");
    }

}

function displayComplexity(){
    document.getElementById("password-complexity").style.display = "inline";

    // checking password length
    pw = document.getElementById("pword1").value;
    if(pw.length >= 8 && pw.length <=10){
        document.getElementById("passwlength").checked = true;
    }
    else{
        document.getElementById("passwlength").checked = false;
    }

    // checking for digit
    for(i = 0; i < pw.length; i++){ 
        if(!isNaN(parseInt(pw[i]))){
           document.getElementById("passwdigit").checked = true;
        } 
    }

    // check for uppercase
    for(i = 0; i < pw.length; i++){ 
        if(pw[i] === pw[i].toUpperCase()){
           document.getElementById("passwupper").checked = true;
        } 
    }
    // check for lowercase
    for(i = 0; i < pw.length; i++){ 
        if(pw[i] === pw[i].toLowerCase()){
           document.getElementById("passwlower").checked = true;
        } 
    }

}

function confirmComplexity(){
    all_checked = 1;
    complex_checks = document.getElementsByClassName("complex");
    for(i = 0; i < complex_checks.length; i++){
        if(complex_checks[i].checked == false){
            all_checked = 0;
            break;   
        }
    }
    if(all_checked == 0){
        document.getElementById("pword1").focus();
        document.getElementById("pword1").select();
        document.getElementById("pword1").style.borderColor = "red";
    }
}
