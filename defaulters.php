<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$selected_collection_id = 0;
$defaulters_result = null;

// Fetch collections for dropdown
$collections_result = $conn->query("SELECT id, month FROM collections ORDER BY id DESC");

// Handle form submission
if (isset($_POST['view_defaulters'])) {
    $selected_collection_id = intval($_POST['collection_id']);

    // Fetch unpaid members
    $defaulters_query = "
        SELECT m.id, m.name, m.phone
        FROM members m
        WHERE m.id NOT IN (
            SELECT p.member_id FROM payments p
            WHERE p.collection_id = $selected_collection_id
        )
        ORDER BY m.name ASC
    ";
    $defaulters_result = $conn->query($defaulters_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Unpaid Members - Club Fund</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container mt-5">
  <h2 class="mb-4 text-center">Unpaid Members Report</h2>

  <form method="POST" action="defaulters.php" class="mb-4">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="collection_id" class="form-label">Select Month</label>
        <select name="collection_id" id="collection_id" class="form-select" required>
          <option value="" disabled selected>-- Select Month --</option>
          <?php while ($collection = $collections_result->fetch_assoc()) : ?>
            <option value="<?= $collection['id'] ?>" <?= $selected_collection_id == $collection['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($collection['month']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>
      <div class="col-md-6 align-self-end">
        <button type="submit" name="view_defaulters" class="btn btn-primary">View Defaulters</button>
      </div>
    </div>
  </form>

  <?php if ($defaulters_result !== null) : ?>
    <?php if ($defaulters_result->num_rows > 0) : ?>
      <h4 class="mb-3">Members who haven't paid for <?= htmlspecialchars($_POST['collection_id']) ?>:</h4>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Member Name</th>
            <th>Phone</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $defaulters_result->fetch_assoc()) : ?>
            <tr>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else : ?>
      <div class="alert alert-success">All members have paid for this month 🎉</div>
    <?php endif; ?>
  <?php endif; ?>

  <div class="mt-4">
    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
