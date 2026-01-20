<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: login.php");
  exit();
}

include('db.php');

// Add Member
if (isset($_POST['add_member'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);

  // Duplicate check by phone only
  $check_query = "SELECT * FROM members WHERE phone = '$phone'";
  $check_result = mysqli_query($conn, $check_query);

  if (mysqli_num_rows($check_result) > 0) {
    // Duplicate found
    echo "<script>alert('A member with this phone number already exists!'); window.location.href='members.php';</script>";
    exit();
  } else {
    // No duplicate, insert new member
    $sql = "INSERT INTO members (name, phone, email) VALUES ('$name', '$phone', '$email')";
    mysqli_query($conn, $sql);
    header("Location: members.php");
    exit();
  }
}


// Delete Member
if (isset($_GET['delete'])) {
  $id = $_GET['delete'];
  $sql = "DELETE FROM members WHERE id=$id";
  mysqli_query($conn, $sql);
  header("Location: members.php");
  exit();
}

// Fetch members
$result = mysqli_query($conn, "SELECT * FROM members");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Members Management</title>
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h1 class="mb-4">Members</h1>
  <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

  <div class="card mb-4 p-4 shadow-sm">
    <h2>Add New Member</h2>
    <form method="POST" class="row g-3">
      <div class="col-md-4">
        <input type="text" name="name" class="form-control" placeholder="Name" required>
      </div>
      <div class="col-md-4">
        <input type="text" name="phone" class="form-control" placeholder="Phone" required>
      </div>
      <div class="col-md-4">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="col-12">
        <button type="submit" name="add_member" class="btn btn-primary">Add Member</button>
      </div>
    </form>
  </div>

  <div class="card p-3 shadow-sm">
    <h2>All Members</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Status</th><th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['phone']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo htmlspecialchars($row['status']); ?></td>
          <td>
            <a href="members.php?delete=<?php echo $row['id']; ?>" 
               onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
