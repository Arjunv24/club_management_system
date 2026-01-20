<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}
include 'db.php';

// Add collection
if (isset($_POST['add_collection'])) {
  $month = $_POST['month'];
  $amount = $_POST['amount'];
  mysqli_query($conn, "INSERT INTO collections (month, amount) VALUES ('$month', '$amount')");
  header("Location: collections.php");
  exit();
}

// List collections
$result = mysqli_query($conn, "SELECT * FROM collections ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Collections</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1 class="mb-4">Collections</h1>
  <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

  <div class="card p-4 shadow-sm mb-4">
    <h2>Add New Collection</h2>
    <form method="POST" class="row g-3">
      <div class="col-md-6">
        <input type="text" name="month" class="form-control" placeholder="Month (e.g. May 2025)" required>
      </div>
      <div class="col-md-4">
        <input type="number" step="0.01" name="amount" class="form-control" placeholder="Amount" required>
      </div>
      <div class="col-md-2">
        <button type="submit" name="add_collection" class="btn btn-primary w-100">Add</button>
      </div>
    </form>
  </div>

  <div class="card p-3 shadow-sm">
    <h2>All Collections</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th><th>Month</th><th>Amount</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['month']) ?></td>
          <td><?= $row['amount'] ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
