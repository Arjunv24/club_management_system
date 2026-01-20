<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$selected_collection_id = null;
$payments = [];

if (isset($_POST['month'])) {
    $selected_collection_id = intval($_POST['month']);
    // Fetch payments for selected month
    $sql = "SELECT m.name, m.phone, m.email, p.amount_paid, p.date_paid 
            FROM payments p 
            JOIN members m ON p.member_id = m.id 
            WHERE p.collection_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_collection_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $payments = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

// Fetch all collections for dropdown (only month)
$collections_result = $conn->query("SELECT id, month FROM collections ORDER BY id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Payments by Month</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
  <h2>Payments by Month</h2>
  <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

  <form method="POST" class="mb-4 row g-3 align-items-center">
    <div class="col-auto">
      <label for="month" class="col-form-label">Select Month:</label>
    </div>
    <div class="col-auto">
      <select name="month" id="month" class="form-select" required>
        <option value="">-- Select Month --</option>
        <?php while ($row = $collections_result->fetch_assoc()) { 
          $selected = ($selected_collection_id == $row['id']) ? 'selected' : '';
          echo "<option value=\"{$row['id']}\" $selected>{$row['month']}</option>";
        } ?>
      </select>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-primary">Show Payments</button>
    </div>
  </form>

  <?php if ($selected_collection_id !== null) : ?>
    <?php
      // Get selected month for display
      $collections_result->data_seek(0); // reset pointer
      $selectedMonth = '';
      while ($col = $collections_result->fetch_assoc()) {
        if ($col['id'] == $selected_collection_id) {
          $selectedMonth = $col['month'];
          break;
        }
      }
    ?>
    <h4>Payments for <?= htmlspecialchars($selectedMonth) ?></h4>
    <?php if (count($payments) > 0): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Amount Paid</th>
            <th>Date Paid</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($payments as $pay) : ?>
            <tr>
              <td><?= htmlspecialchars($pay['name']) ?></td>
              <td><?= htmlspecialchars($pay['phone']) ?></td>
              <td><?= htmlspecialchars($pay['email']) ?></td>
              <td>$<?= number_format($pay['amount_paid'], 2) ?></td>
              <td><?= htmlspecialchars($pay['date_paid']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No payments found for this month.</p>
    <?php endif; ?>
  <?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
