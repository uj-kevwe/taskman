<?php
	session_start();
	if(isset($_SESSION["person"])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard - Transcript Tracker</title>
    <link rel="stylesheet" href="../bootstrap.min.css">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/bootstrap/bootstrap.min.css">
    <script src="../assets/bootstrap/bootstrap.min.js"></script>
    <style>
        body {
            background-color: #f9fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background-color: white;
            border-right: 1px solid #e0e0e0;
            padding: 20px 15px;
        }
        .sidebar .nav-link {
            font-weight: 500;
            font-size: 16px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            border-radius: 8px;
            transition: background 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #eaf1ff;
            color: #2a63e7;
        }
        .sidebar .nav-link i {
            font-size: 18px;
        }
        .content {
            padding: 40px 30px;
        }
        .badge-completed {
            background-color: #2a63e7;
            color: white;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 14px;
        }
        .badge-pending {
            background-color: #f68c1f;
            color: white;
            border-radius: 8px;
            padding: 5px 12px;
            font-size: 14px;
        }
        .btn-request {
            background-color: #2a63e7;
            color: white;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            transition: background 0.3s;
        }
        .btn-request:hover {
            background-color: #1c4fcb;
        }
        .progress-tracker {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .progress-step {
            text-align: center;
            position: relative;
            flex: 1;
        }
        .progress-step::after {
            content: "";
            position: absolute;
            top: 14px;
            right: -50%;
            width: 100%;
            height: 4px;
            background-color: #d0d0d0;
            z-index: 0;
        }
        .progress-step:last-child::after {
            display: none;
        }
        .progress-step .circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: #d0d0d0;
            margin: 0 auto 8px;
            border: 3px solid #d0d0d0;
            z-index: 1;
            position: relative;
        }
        .progress-step.active .circle {
            background-color: white;
            border-color: #2a63e7;
        }
        .progress-step.active ~ .progress-step .circle {
            background-color: white;
            border-color: #d0d0d0;
        }
        .progress-step.active::after {
            background-color: #2a63e7;
        }
        .progress-step span {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #555;
        }
        .progress-step.active span {
            color: #2a63e7;
        }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 mb-md-5 mt-md-5">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-user-graduate fs-3 text-primary me-2"></i>
                    <div>
                        <!-- <div class="fw-bold"></div> -->
                        <small class="text-muted">Request Tracker Portal</small>
                    </div>
                </div>
                <a href="#" class="nav-link active"><i class="fas fa-home"></i> Dashboard</a>
                <a href="#" class="nav-link mt-4"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 mt-md-5 mt-sm-5">
                <h2 class="mb-4 fw-bold">Welcome, <?php echo $_SESSION["person"] ?></h2>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold">My Requests Trackings</h5>
                    <a href="request_new_transcript.php"  class="btn btn-request">Initiate New Tracking Request</a>
                </div>

                <!-- Transcript Requests Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date Requested</th>
                                <th>Status</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1001</td>
                                <td>April 24, 2024</td>
                                <td><span class="badge-completed">Completed</span></td>
                                <td>
                                    <div class="progress-tracker">
                                        <div class="progress-step active">
                                            <div class="circle"></div>
                                            <span>Requested</span>
                                        </div>
                                        <div class="progress-step active">
                                            <div class="circle"></div>
                                            <span>Processing</span>
                                        </div>
                                        <div class="progress-step active">
                                            <div class="circle"></div>
                                            <span>Printed</span>
                                        </div>
                                        <div class="progress-step active">
                                            <div class="circle"></div>
                                            <span>Dispatched</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>1002</td>
                                <td>April 22, 2024</td>
                                <td><span class="badge-pending">Pending</span></td>
                                <td>
                                    <div class="progress-tracker">
                                        <div class="progress-step active">
                                            <div class="circle"></div>
                                            <span>Requested</span>
                                        </div>
                                        <div class="progress-step active">
                                            <div class="circle"></div>
                                            <span>Processing</span>
                                        </div>
                                        <div class="progress-step">
                                            <div class="circle"></div>
                                            <span>Printed</span>
                                        </div>
                                        <div class="progress-step">
                                            <div class="circle"></div>
                                            <span>Dispatched</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </main>
        </div>
    </div>

    <!-- Bootstrap + Icons -->
    <script src="../bootstrap.js"></script>
    <script>
       
    </script>

</body>
</html>
<?php
	}
	else{
		echo "
			<script>
				alert('No User Logged in');
				location.replace('../../');
			</script>
		";
	}
?>
