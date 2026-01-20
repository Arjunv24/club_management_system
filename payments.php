<?php
session_start();
include 'db.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['add_payment'])) {
    $member_id = intval($_POST['member_id']);
    $collection_id = intval($_POST['collection_id']);
    $amount_paid = floatval($_POST['amount_paid']);

    if ($member_id && $collection_id && $amount_paid > 0) {
        $stmt = $conn->prepare("INSERT INTO payments (member_id, collection_id, amount_paid, date_paid) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iid", $member_id, $collection_id, $amount_paid);

        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">Payment recorded successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger">Error saving payment. Please try again.</div>';
        }
        $stmt->close();
    } else {
        $message = '<div class="alert alert-warning">Please fill in all fields correctly.</div>';
    }
}

// Fetch members and collections
$members_result = $conn->query("SELECT id, name FROM members ORDER BY name ASC");
$collections_result = $conn->query("SELECT id, month FROM collections ORDER BY id DESC");

// Fetch total payments
$total_payments_result = $conn->query("SELECT SUM(amount_paid) AS total_collected FROM payments");
$total_collected = $total_payments_result->fetch_assoc()['total_collected'];
$total_collected = $total_collected ?? 0; // in case null
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Record Payment - Club Fund</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4 text-center">Record New Payment</h2>

  <?= $message ?>

  <form method="POST" action="payments.php">
    <div class="row g-3 mb-3">
      <div class="col-md-5">
        <label for="member_id" class="form-label">Select Member</label>
        <select name="member_id" id="member_id" class="form-select" required>
          <option value="" selected disabled>-- Select Member --</option>
          <?php while ($member = $members_result->fetch_assoc()) : ?>
            <option value="<?= $member['id'] ?>"><?= htmlspecialchars($member['name']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="col-md-5">
        <label for="collection_id" class="form-label">Select Month</label>
        <select name="collection_id" id="collection_id" class="form-select" required>
          <option value="" selected disabled>-- Select Month --</option>
          <?php while ($collection = $collections_result->fetch_assoc()) : ?>
            <option value="<?= $collection['id'] ?>"><?= htmlspecialchars($collection['month']) ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="col-md-2">
        <label for="amount_paid" class="form-label">Amount</label>
        <input type="number" id="amount_paid" name="amount_paid" step="0.01" min="0.01" class="form-control" placeholder="Amount" required />
      </div>
    </div>

    <div class="text-end">
      <button type="submit" name="add_payment" class="btn btn-primary">Record Payment</button>
    </div>
  </form>

 <?php
// Fetch total payments per month
$monthly_totals_result = $conn->query("
    SELECT c.month, SUM(p.amount_paid) AS total_collected
    FROM collections c
    LEFT JOIN payments p ON c.id = p.collection_id
    GROUP BY c.id, c.month
    ORDER BY c.id DESC
");
?>

<div class="mt-5">
  <h4>Payment Summary by Month</h4>
  <table class="table table-bordered mt-3">
    <thead>
      <tr>
        <th>Month</th>
        <th>Total Collected</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $monthly_totals_result->fetch_assoc()) : ?>
        <tr>
          <td><?= htmlspecialchars($row['month']) ?></td>
          <td>$<?= number_format($row['total_collected'] ?? 0, 2) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>


  <div class="mt-4">
    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
