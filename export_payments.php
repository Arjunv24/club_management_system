<?php
include 'db.php';

if (isset($_GET['collection_id'])) {
    $collection_id = $_GET['collection_id'];

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=payment_report_collection_$collection_id.xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Fetch the collection's month name
    $collection_res = mysqli_query($conn, "SELECT month FROM collections WHERE id = $collection_id");
    $collection_data = mysqli_fetch_assoc($collection_res);
    $month = $collection_data ? $collection_data['month'] : 'Unknown';

    echo "<h3>LEGAZY monthly Payment Report for $month</h3>";

    // Members who paid
    $paid_query = "SELECT m.name, m.phone, m.email, p.amount_paid, p.date_paid
                   FROM members m
                   JOIN payments p ON m.id = p.member_id
                   WHERE p.collection_id = $collection_id";
    $paid_result = mysqli_query($conn, $paid_query);

    echo "<h4>✔ Members who Paid</h4>";
    echo "<table border='1'>
            <tr><th>Name</th><th>Phone</th><th>Email</th><th>Amount Paid</th><th>Date Paid</th></tr>";
    while ($row = mysqli_fetch_assoc($paid_result)) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['email']}</td>
                <td>{$row['amount_paid']}</td>
                <td>{$row['date_paid']}</td>
              </tr>";
    }
    echo "</table><br><br>";

    // Members who didn't pay
    $not_paid_query = "SELECT m.name, m.phone, m.email
                       FROM members m
                       WHERE m.id NOT IN (
                         SELECT member_id FROM payments WHERE collection_id = $collection_id
                       )";
    $not_paid_result = mysqli_query($conn, $not_paid_query);

    echo "<h4>✖ Members who Haven't Paid</h4>";
    echo "<table border='1'>
            <tr><th>Name</th><th>Phone</th><th>Email</th></tr>";
    while ($row = mysqli_fetch_assoc($not_paid_result)) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['phone']}</td>
                <td>{$row['email']}</td>
              </tr>";
    }
    echo "</table>";
}
?>
