<?php session_start(); include "db/setup.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Requests Tracker</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="bootstrap.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  .password-container {
      position: relative;
      max-width: 315px;
    }

    .password-container input[type="password"],
    .password-container input[type="text"] {
      width: 100%;
      padding: 10px 40px 10px 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    .password-container .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-70%);
      cursor: pointer;
      font-size: 18px;
      color: #666;
    }

    .password-container .toggle-password:hover {
      color: #000;
    }
</style>
</head>
<body>
<div class="login-container row">
        <div class="left-panel col-lg-6 col-md-6 col-sm-12">
          <img src="undraw_certificate_71gt.svg" alt="Academic Progress Illustration" />
          <h2>Track All Your University Requests Easily</h2>
        </div>

        <div class="right-panel col-lg-6 col-md-6 col-sm-12">


          <div class="form-container" id="logincon" >
            <div class="text-center mb-4 icons8">
                <img src="icons8-document-48.png" alt="Doc Icon">
              </div>
            <div class="tabs">
              <button class="active" onclick="login()">Login</button>
              <button onclick="Signup()">Sign Up</button>
            </div>
            <form id = "valid_login" action="backends/process_login.php" method="post">
              <?php if(isset($_SESSION["invalid_login"])){echo $_SESSION["invalid_login"];} ?>
              <input type="text" id="user_id" name = "user_id" placeholder="Email or User ID" required />
              <div class="password-container">
                <input type="password" class="passwordField" id="user_passwd" name = "user_passwd" placeholder="Password" required>
                <span class="togglePasswordBtn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-70%); cursor: pointer;">
                  <i class="togglePasswordIcon far fa-eye-slash"></i>
                </span>
              </div>
              <select name = "user_role" required>
                 <option value="0">Select A User Role</option>
                 <option value="00001">Admin</option>
                 <option value="00002">Client</option>
                 <option value="00003">Handler</option>
                 <option value="00004">Supervisor</option>
              </select>
              <button type="button" class="login-btn" onclick = "process_login()">Login</button>
              <a href="forgotten_password.php" class="fg-pass">Reset Password?</a>
            </form>
            <div class="footer-links">
              <a href="privacy.php">Privacy policy</a>
              <a href="contact.php">Contact info</a>
            </div>
          </div>




          <div class="form-container" id="signincon" style="display:none;">
            <div class="text-center mb-4 icons8">
              <img src="icons8-document-48.png" alt="Doc Icon" />
            </div>

            <div class="tabs">
              <button onclick="login()">Login</button>
              <button class="active" onclick="Signup()">Sign Up</button>
            </div>

            <form id = "signup-form" action = "backends/process_signup.php" method = "post" enctype = "multipart/form-data">
                <div id = "signup-status"></div>
              <input type="text" name = "fullnames" placeholder="Full Names (Firstname Middle Name Surname)" required />
              <input type = "text" id = "user_username" name = "username" placeholder = "Choose a unique Username" required / onfocusout = "verifyUnique(id)">
              <input type="email" id = "user_email" name = "email" placeholder="Enter a Unique Email" required / onfocusout = "verifyUnique(id)">
              <input type="tel" id = "user_phone" name = "phone" placeholder="Enter a Phone Number" required />
              <p>
                  <span>Male <input type = "radio" id = "male_gender" name = "gender" value = "Male" checked></span>
                  <span>Female <input type = "radio"  id = "female_gender" name = "gender" value = "Female"></span>
              </p>
              <p>
                  Upload your Image (Only .jpg or /jpeg files. maximum size: 50 MB)<br>
                  <input type = "file" id = "user_image" name = "user_image" accept = "image/jpg, image/jpeg" required>
              </p>
              <p>Home Address</p>
              <textarea id = "user_address" name = "address" style = "margin: -10px 0 15px 0"></textarea>
              <div class="password-container">
                <input type="password" class="passwordField" id = "password1" name = "password1" placeholder="Password" required>
                <span class="togglePasswordBtn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-70%); cursor: pointer;">
                  <i class="togglePasswordIcon far fa-eye-slash"></i>
                </span>
              </div>
              <div class="password-container">
                <input type="password" class="passwordField" id = "password2" name = "password2" placeholder="confirm Password" required>
                <span class="togglePasswordBtn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-70%); cursor: pointer;">
                  <i class="togglePasswordIcon far fa-eye-slash"></i>
                </span>
              </div>
              
              <select id = "client_role" name = "client_role" required>
                <option value="0">Select Your User Type</option>
                <option value="student">Student</option>
                <option value="contractor">Contractor</option>
              </select>

              <button type="button" class="login-btn" id = "process_signup" onclick = "processSignup()">Sign Up</button>
            </form>

            <div class="footer-links">
              <a href="privacy.php">Privacy policy</a>
              <a href="contact.php">Contact info</a>
            </div>
          </div>

        
        </div>
</div>
</body>

<script src="injex.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>

    function processSignup(){
    	username = document.getElementById("user_username").value;
      email = document.getElementById("user_email").value;
      
      xml = new XMLHttpRequest();
      xml.onreadystatechange = function(){
          if(this.readyState == 4 && this.status == 200){
            document.getElementById("signup-status").innerHTML = this.responseText;
            if(this.responseText.includes('success')){
                document.getElementById("signup-form").submit();
            }
          }
      }
      xml.open("GET","backends/pre_signup.php?username="+username+"&email="+email,true);
      xml.send();
    }
    function process_login(){
        if(document.getElementById("user_passwd").value == "defaultpassword"){
            userid = document.getElementById("user_id").value;
            xml = new XMLHttpRequest();
            xml.onreadystatechange = function(){
              if(this.readyState == 4 && this.status == 200){
                if(this.responseText == 1){
                  location.replace("forgotten_password.php?user_id="+userid);
                }
                else{
                  alert("Wrong Password");
                  document.getElementById("user_passwd").select();
                }
              }
            };
            xml.open("GET","backends/check_reset_status.php?userid="+userid,true);
            xml.send();
        
        }
        else{
            document.getElementById("valid_login").submit();
        }
    }
    
    
    function verifyUnique(id){
        value = document.getElementById(id).value;
        field = id.substring(5);
        xml = new XMLHttpRequest();
        xml.onreadystatechange = function(){
          if(this.readyState == 4 && this.status == 200)  {
              if(this.responseText.includes("already exists!")){
                  document.getElementById(id).focus();
                  document.getElementById(id).select();
                  document.getElementById("signup-status").innerHTML = this.responseText;
              }
          }
        };
        xml.open("GET","backends/verifyUnique.php?field="+field+"&value="+value,true);
        xml.send();
        
    }
    
    
	
</script>

</html>