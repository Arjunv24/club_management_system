<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Fetch total members
$total_members_result = $conn->query("SELECT COUNT(*) AS total_members FROM members");
$total_members = $total_members_result->fetch_assoc()['total_members'];

// Fetch total payments
$total_payments_result = $conn->query("SELECT SUM(amount_paid) AS total_collected FROM payments");
$total_collected = $total_payments_result->fetch_assoc()['total_collected'] ?? 0;

// Fetch number of months/collections
$total_collections_result = $conn->query("SELECT COUNT(*) AS total_collections FROM collections");
$total_collections = $total_collections_result->fetch_assoc()['total_collections'];

// Fetch collections for export dropdown
$collections_result = $conn->query("SELECT id, month FROM collections");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - Club Fund</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    background: #f0f4f8;
}
.dashboard-card {
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
h2 {
    font-weight: 600;
}
.card-title {
    font-size: 1.2rem;
    font-weight: 500;
}
.icon-large {
    font-size: 2.5rem;
}
</style>
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4 text-center">📊 Club Fund Admin Dashboard</h2>

  <!-- Dashboard Summary Cards -->
  <div class="row g-4 mb-4">

    <div class="col-md-4">
      <div class="card text-white bg-primary dashboard-card p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title">Total Members</h5>
            <h3><?= $total_members ?></h3>
          </div>
          <i class="bi bi-people-fill icon-large"></i>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-white bg-success dashboard-card p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title">Total Amount Collected</h5>
            <h3>$<?= number_format($total_collected, 2) ?></h3>
          </div>
          <i class="bi bi-cash-stack icon-large"></i>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card text-white bg-warning dashboard-card p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h5 class="card-title">Total Collections</h5>
            <h3><?= $total_collections ?></h3>
          </div>
          <i class="bi bi-calendar2-week-fill icon-large"></i>
        </div>
      </div>
    </div>

  </div>

  <!-- Navigation Buttons -->
  <div class="row g-3 justify-content-center">

  <div class="col-md-2">
    <a href="members.php" class="btn btn-outline-primary w-100 py-3">
      <i class="bi bi-person-plus-fill me-2"></i>Members
    </a>
  </div>

  <div class="col-md-2">
    <a href="collections.php" class="btn btn-outline-warning w-100 py-3">
      <i class="bi bi-calendar2-week me-2"></i>Collections
    </a>
  </div>

  <div class="col-md-2">
    <a href="payments.php" class="btn btn-outline-success w-100 py-3">
      <i class="bi bi-cash-coin me-2"></i>Payments
    </a>
  </div>

  <div class="col-md-2">
    <a href="defaulters.php" class="btn btn-outline-danger w-100 py-3">
      <i class="bi bi-exclamation-octagon-fill me-2"></i>Defaulters
    </a>
  </div>

  <div class="col-md-2">
    <a href="paid_last_month.php" class="btn btn-outline-info w-100 py-3">
      <i class="bi bi-clock-history me-2"></i>Paid Last Month
    </a>
  </div>

</div>



  <!-- Export Payment Report -->
  <div class="card p-4 mb-5 shadow-sm">
    <h4 class="mb-3">📥 Export Payment Report</h4>
    <form method="GET" action="export_payments.php" class="row g-3 align-items-center">
      <div class="col-md-6">
        <select name="collection_id" class="form-select" required>
          <option value="">Select Month</option>
          <?php while ($col = $collections_result->fetch_assoc()) { ?>
            <option value="<?= $col['id'] ?>"><?= htmlspecialchars($col['month']) ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-md-4">
        <button type="submit" class="btn btn-success w-100">
          <i class="bi bi-file-earmark-excel-fill me-2"></i>Export to Excel
        </button>
      </div>
    </form>
  </div>

  <!-- Logout -->
  <div class="text-center mt-4">
    <a href="logout.php" class="btn btn-dark btn-lg px-4">
      <i class="bi bi-box-arrow-right me-2"></i>Logout
    </a>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
