<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Request New Transcript</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <script src="../assets/bootstrap/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 700px;
            margin: 60px auto;
            background-color: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        }
        h2 {
            font-weight: bold;
            color: #2a63e7;
            margin-bottom: 30px;
        }
        label {
            font-weight: 600;
            margin-bottom: 6px;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #ccc;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #2a63e7;
            box-shadow: none;
        }
        .btn-submit {
            background-color: #2a63e7;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            padding: 12px 20px;
            border: none;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background-color: #1c4fcb;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Initiate A New Request</h2>
        <form id="transcriptForm">
            <div class="mb-3">
                <label for="fullName">Full Name</label>
                <input type="text" class="form-control" id="fullName" required />
            </div>
            <div class="mb-3">
                <label for="studentId">Student ID</label>
                <input type="text" class="form-control" id="studentId" required />
            </div>
            <div class="mb-3">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" required />
            </div>
            <div class="mb-3">
                <label for="deliveryMethod">Delivery Method</label>
                <select class="form-control" id="deliveryMethod" required>
                    <option value="">Select Delivery Method</option>
                    <option value="Pickup">Pickup</option>
                    <option value="Email">Email</option>
                    <option value="Courier">Courier</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="numCopies">Number of Copies</label>
                <input type="number" class="form-control" id="numCopies" min="1" value="1" required />
            </div>
            <div class="mb-3">
                <label for="address">Destination Address (if applicable)</label>
                <textarea class="form-control" id="address" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="notes">Special Notes / Instructions</label>
                <textarea class="form-control" id="notes" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-submit">Submit Request</button>
        </form>
        <p class="text-center mt-3">
          <a href="index.php">Back to Dashboard</a>
        </p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Simple JS for form handling -->
    <script>
        document.getElementById('transcriptForm').addEventListener('submit', function(event) {
            event.preventDefault();

            // Collect form data
            const fullName = document.getElementById('fullName').value;
            const studentId = document.getElementById('studentId').value;
            const email = document.getElementById('email').value;
            const deliveryMethod = document.getElementById('deliveryMethod').value;
            const numCopies = document.getElementById('numCopies').value;
            const address = document.getElementById('address').value;
            const notes = document.getElementById('notes').value;

            // Simulate submit (you can replace with actual backend call)
            alert(`🎓 Transcript Request Submitted!
                    Full Name: ${fullName}
                    Student ID: ${studentId}
                    Email: ${email}
                    Delivery Method: ${deliveryMethod}
                    Number of Copies: ${numCopies}
                    Address: ${address}
                    Notes: ${notes}`);

            // Optionally reset the form
            this.reset();
        });
    </script>

</body>
</html>
