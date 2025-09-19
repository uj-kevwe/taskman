<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Us - Transcript Tracker</title>
  <link rel="stylesheet" href="bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .contact-container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    h1 {
      color: #2c3e50;
    }
  </style>
</head>
<body>

  <div id="alert" 
     class="alert alert-success alert-dismissible fade show shadow-lg rounded" 
     role="alert" 
     style="
       display: none; 
       position: fixed; 
       top: 20px; 
       left: 50%; 
       transform: translateX(-50%);
       z-index: 1055; 
       width: 90%; 
       max-width: 600px;
       font-weight: 600;
       font-size: 1.1rem;
       box-shadow: 0 4px 15px rgba(0, 128, 0, 0.3);
     ">
  <strong>Success!</strong> <span id="alert-message"></span>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>


<div class="container contact-container">
  <h1 class="mb-4">Contact Us</h1>
  <p>Have questions or need support? Reach out to us!</p>

  <form>
    <div class="mb-3">
      <label for="name" class="form-label">Your Name</label>
      <input type="text" class="form-control" id="name" placeholder="Enter your name" required />
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Your Email</label>
      <input type="email" class="form-control" id="email" placeholder="Enter your email" required />
    </div>

    <div class="mb-3">
      <label for="message" class="form-label">Message</label>
      <textarea class="form-control" id="message" rows="5" placeholder="Write your message here" required></textarea>
    </div>

    <div class="d-grid mb-3">
      <button type="submit" onclick="success(event)" class="btn btn-primary" id="alert">Send Message</button>
    </div>
  </form>

  <hr class="my-4" />

  <p class="mt-5"><a href="index.php">← Back to Home</a></p>
</div>

</body>
<script src="bootstrap.js"></script>
<script>
let alertBox = document.getElementById("alert");

function success(event)  {
    event.preventDefault(); // Prevent form submission for now


    alertBox.textContent = `message sent successfully!`; // Set success message
    alertBox.style.display = "block"; // Show alert

    // Hide the alert after  seconds
    setTimeout(() => {
      alertBox.style.display = "none"; 
      // Submit the form
      const form = event.target.closest('form');
      if (form) {
      form.submit();
      }
    }, 1500);
  };
</script>
</html>
