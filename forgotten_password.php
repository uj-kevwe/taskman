<?php
    include "db/setup.php";
    if(isset($_GET["user_id"])){
      $userid = $_GET["user_id"];
      if(strpos($userid,"@")===false){
          // retrieve user email
          $sql = "select * from $database.users where userid = '$userid'";
          $result = $conn->query($sql);

          if($result->num_rows > 0){
              while($rows = $result->fetch_assoc()){
                  $email = $rows["email"];
              }
          }
      }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Forgot Password Flow - Transcript Tracker</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    body {
      background-color: #f1f1f6;
    }

    .main-card {
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .reset-btn, .send-btn {
      background-color: #3b82f6;
      border: none;
    }

    .reset-btn:hover, .send-btn:hover {
      background-color: #2563eb;
    }

    .footer-links a {
      font-size: 0.85rem;
      color: #666;
      text-decoration: none;
    }

    .footer-links a:hover {
      text-decoration: underline;
    }

    .success-icon {
      width: 80px;
      margin-bottom: 20px;
    }

    .hidden {
      display: none;
    }


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
  <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="w-100 main-card" style="max-width: 400px;">
      <!-- Forgot Password Page -->
      <div id="forgot-password-page">
        <div class="text-center mb-4">
          <img src="https://img.icons8.com/material-rounded/48/000000/forgot-password.png" alt="Forgot Password Icon" />
        </div>

        <h4 class="text-center mb-3">Reset Password</h4>
        <p class="text-center mb-4 text-muted">Enter your email address to reset your password.</p>

        <form id="forgot-password-form">
          <div class="mb-3">
            <?php
              if(isset($_GET["user_id"])){
                $userid = $_GET["user_id"];
                echo "<input type='email' class='form-control' id='email-input' value = '$email' readonly>";
              }
              else{
                echo '<input type="email" class="form-control" id="email-input" placeholder="Enter your email" required />';
              }
            ?>
            
          </div>

          <div class="d-grid mb-3">
            <button type="button" class="btn send-btn text-white" onclick = "send_reset_code()">Send Reset Code</button>
          </div>
        </form>

        <p class="text-center mt-3">
          <a href="index.php">Back to Login</a>
        </p>
      </div>

      <!-- Success Page -->
      <div id="success-page" class="hidden text-center">
        <img src="checkmark--v1.png" alt="Success Icon" class="success-icon" />
        <h4 class="mb-3">Check Your Email!</h4>
        <p class="mb-4 text-muted">
          We've sent a reset code to your email address to reset your password.
        </p>

        <div class="d-grid mb-3">
          <button class="btn btn-primary text-white" onclick="goToResetPage()">Reset Password</button>
        </div>
      </div>
      

      <!-- Error Page -->
       <div id="error-page" class="hidden text-center">
        <img src="checkmark--v1.png" alt="Success Icon" class="success-icon" />
        <h4 class="mb-3">Error Sending Mail!</h4>
        <p id = "error-message" class="mb-4 text-muted"></p>

        <!-- <div class="d-grid mb-3">
          <button class="btn btn-primary text-white" onclick="goToResetPage()">Reset Password</button>
        </div> -->
      </div>

      <!-- Reset Password Page -->
      <div id="reset-password-page" class="hidden">
        <div class="text-center mb-4">
          <img src="https://img.icons8.com/fluency-systems-filled/48/000000/key.png" alt="Reset Password Icon" />
        </div>

        <h4 class="text-center mb-3">Reset Your Password</h4>
        <p class="text-center mb-4 text-muted">Enter your Reset Code new password below.</p>

        <form id="reset-password-form" action="backends/resetpass.php" method= "post">
            <input type="hidden" name="userid" value = '<?=$userid; ?>'>
            <div class="password-container mb-3">
                <input type="text" class="passwordField" placeholder="6-Digit Reset Code" required id="reset_code" name = "reset_code">
                <!-- <span class="togglePasswordBtn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-70%); cursor: pointer;">
                  <i class="togglePasswordIcon far fa-eye-slash"></i> -->
                </span>
            </div>  
            <div class="password-container mb-3">
                <input type="password" class="passwordField" placeholder="Password" required id="pwd1" name = "pwd1">
                <span class="togglePasswordBtn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-70%); cursor: pointer;">
                  <i class="togglePasswordIcon far fa-eye-slash"></i>
                </span>
            </div>

           <div class="password-container mb-3">
                <input type="password" class="passwordField" placeholder="confirm Password" required id="pwd2" name = "pwd2">
                <span class="togglePasswordBtn" style="position: absolute; right: 10px; top: 50%; transform: translateY(-70%); cursor: pointer;">
                  <i class="togglePasswordIcon far fa-eye-slash"></i>
                </span>
            </div>
             
              <div>
                <span id="password-status" style="color: red;"></span>
              </div>
          <div class="d-grid mb-3">
            <button type="submit" class="btn reset-btn text-white">Reset Password</button>
          </div>
        </form>

        <p class="text-center mt-3">
          <a href="index.php">Back to Login</a>
        </p>
      </div>

      <div class="d-flex justify-content-between mt-4 footer-links">
        <a href="privacy.php">Privacy policy</a>
        <a href="contact.php">Contact info</a>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="injex.js"></script>
  <script>
      function send_reset_code(){
          email = document.getElementById("email-input").value;
          const forgotPasswordPage = document.getElementById('forgot-password-page');
          const successPage = document.getElementById('success-page');
          const errorPage = document.getElementById('error-page');
          xml = new XMLHttpRequest();
          xml.onreadystatechange = function(){
              if(this.readyState == 4 && this.status == 200){
                
                if(!this.responseText.includes("Error")){
                  forgotPasswordPage.classList.add('hidden');
                  successPage.classList.remove('hidden');
                }
                else{  
                  document.getElementById("error-message").innerHTML = this.responseText;
                  forgotPasswordPage.classList.add('hidden');
                  errorPage.classList.remove('hidden');
                }
              }
          };
          xml.open("GET","backends/send_reset_password_code.php?email="+email,true);
          xml.send();
      }
  </script>
</body>
</html>
