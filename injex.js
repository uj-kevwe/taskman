//THIS TO TOGGLE BETWEEN SIGN-UP AND LOGIN
let logincon = document.getElementById("logincon");
let signcon = document.getElementById("signincon");

function Signup(){
logincon.style.display="none";
signcon.style.display="block";
}


function login(){
    logincon.style.display = "block";
    signcon.style.display = "none";
}



// THE TOGGLE OF THE PASSWORD TO SHOW AND HIDE
const togglePasswordBtns = document.querySelectorAll(".togglePasswordBtn");

togglePasswordBtns.forEach(btn => {
  btn.addEventListener("click", function () {
    // Find the following input field
    const passwordField = this.parentElement.querySelector(".passwordField");
    const togglePasswordIcon = this.parentElement.querySelector(".togglePasswordIcon");

    // Toggle type
    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
    passwordField.setAttribute("type", type);

    // Switch icon
    if (type === "text") {
      togglePasswordIcon.classList.remove("fa-eye-slash");
      togglePasswordIcon.classList.add("fa-eye");
    } else {
      togglePasswordIcon.classList.remove("fa-eye");
      togglePasswordIcon.classList.add("fa-eye-slash");
    }
  });
});




//THE FORGOTTEN PASSWORD ROOT     

    const forgotPasswordForm = document.getElementById('forgot-password-form');
    const resetPasswordForm = document.getElementById('reset-password-form');

    const forgotPasswordPage = document.getElementById('forgot-password-page');
    const successPage = document.getElementById('success-page');
    const resetPasswordPage = document.getElementById('reset-password-page');
    const pwd1 = document.getElementById("pwd1");
    const pwd2 = document.getElementById("pwd2");
    const password_status =document.getElementById("password-status")

    forgotPasswordForm.addEventListener('submit', function(e) {
      e.preventDefault();
      // Simulate success response
      forgotPasswordPage.classList.add('hidden');
      successPage.classList.remove('hidden');
    });

    // Simulate reset password
    // resetPasswordForm.addEventListener('submit', function(e) {
    //   e.preventDefault();

    //   // Check if password is long enough
    //   if (pwd1.value.length < 5) {
    //     password_status.textContent = "Password is too short (minimum 5 characters)";
    //     return; // Stop here
    //   }

    //   // Check if passwords match
    //   if (pwd1.value !== pwd2.value) {
    //     password_status.textContent = "Passwords do not match";
    //     return; // Stop here
    //   }

      // If both condition are met then this will happen
      // console.log('Password successfully reset!');
      // alert('Password reset successful! You can now log in.');
      // window.location.href = './'; // Go back to login
      // e.submit();
    // });

    // Button click → go to Reset Password Page
    function goToResetPage() {
      successPage.classList.add('hidden');
      resetPasswordPage.classList.remove('hidden');
    };  
