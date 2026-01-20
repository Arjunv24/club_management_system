<?php
session_start();
if (isset($_SESSION['admin'])) {
  header("Location: dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Club Fund Collection App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .hero-section {
      background: linear-gradient(to right, #0d6efd, #6610f2);
      color: white;
      padding: 100px 20px;
      text-align: center;
    }
    .hero-section h1 {
      font-size: 3rem;
      margin-bottom: 20px;
    }
    .hero-section p {
      font-size: 1.2rem;
      margin-bottom: 30px;
    }
    .footer {
      padding: 20px;
      background: #343a40;
      color: #ccc;
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="hero-section">
    <div class="container">
      <h1>LEGAZY Arts and Sports Club Fund Collection App</h1>
      <p>Manage your club members, collections, payments, and reports efficiently.</p>
      <a href="login.php" class="btn btn-light btn-lg">Admin Login</a>
    </div>
  </div>

  <div class="container my-5">
    <div class="row text-center">
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm p-3 h-100">
          <h4>Easy Member Management</h4>
          <p>Add, update, and manage your club members seamlessly with just a few clicks.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm p-3 h-100">
          <h4>Track Monthly Collections</h4>
          <p>Record monthly fund collections and payment records for every club member.</p>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm p-3 h-100">
          <h4>Detailed Reports</h4>
          <p>View and analyze collection reports for better financial tracking and management.</p>
        </div>
      </div>
    </div>
  </div>

  <div class="footer">
    &copy; <?= date('Y') ?> Club Fund Collection App. All rights reserved.
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
