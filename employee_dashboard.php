<?php
session_start();
if (empty($_SESSION['employee_id'])) {
    header('Location: login.php');
    exit;
}

require __DIR__ . '/db.php';
$customers = $connection->query('SELECT customer_id, first_name, middle_name, last_name, email, phone_number, username, created_at FROM customers ORDER BY created_at DESC, customer_id DESC');
?>
<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Sun Son Solar - Customer Records</title>
<style>body{margin:0;padding:32px;font-family:Arial,Helvetica,sans-serif;background:#fff7e8;color:#3a2a18}header{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px}h1{color:#b34700;margin:0}a{color:#b34700;font-weight:bold}.table-wrap{overflow-x:auto;background:#fff;border-radius:10px;box-shadow:0 4px 14px rgba(179,71,0,.12)}table{width:100%;border-collapse:collapse;min-width:850px}th,td{padding:12px;text-align:left;border-bottom:1px solid #f0dcbb}th{background:#f57c1f;color:#fff}tr:last-child td{border-bottom:0}.empty{padding:22px}</style></head>
<body><header><div><h1>Registered Customers</h1><p>Welcome, <?= htmlspecialchars($_SESSION['employee_name'], ENT_QUOTES, 'UTF-8') ?>.</p></div><a href="logout.php">Log out</a></header><div class="table-wrap"><?php if ($customers->num_rows === 0): ?><p class="empty">No customers have registered yet.</p><?php else: ?><table><thead><tr><th>Customer ID</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Email</th><th>Phone Number</th><th>Username</th><th>Created At</th></tr></thead><tbody><?php while ($customer = $customers->fetch_assoc()): ?><tr><td><?= htmlspecialchars($customer['customer_id'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($customer['first_name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($customer['middle_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($customer['last_name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($customer['email'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($customer['phone_number'] ?? '', ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($customer['username'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($customer['created_at'], ENT_QUOTES, 'UTF-8') ?></td></tr><?php endwhile; ?></tbody></table><?php endif; ?></div></body>
</html>
