<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}
include 'db.php';

$report = mysqli_query($conn, "
  SELECT m.name AS member, c.month, p.amount_paid
  FROM payments p
  JOIN members m ON p.member_id = m.id
  JOIN collections c ON p.collection_id = c.id
  ORDER BY c.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Reports</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1 class="mb-4">Payment Reports</h1>
  <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

  <div class="card p-3 shadow-sm">
    <h2>All Payment Records</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Member</th><th>Month</th><th>Amount Paid</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($r = mysqli_fetch_assoc($report)) { ?>
        <tr>
          <td><?= htmlspecialchars($r['member']) ?></td>
          <td><?= htmlspecialchars($r['month']) ?></td>
          <td><?= $r['amount_paid'] ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
