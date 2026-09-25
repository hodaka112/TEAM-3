<?php
session_start();
require __DIR__ . '/db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $statement = $connection->prepare('SELECT employee_id, first_name, password FROM employees WHERE username = ? LIMIT 1');
    $statement->bind_param('s', $username);
    $statement->execute();
    $employee = $statement->get_result()->fetch_assoc();

    if ($employee && password_verify($password, $employee['password'])) {
        session_regenerate_id(true);
        $_SESSION['employee_id'] = $employee['employee_id'];
        $_SESSION['employee_name'] = $employee['first_name'];
        header('Location: employee_dashboard.php');
        exit;
    }

    $error = 'Invalid employee username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sun Son Solar - Employee Login</title>
<style>body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#ffe9bf;color:#3a2a18}main{max-width:420px;margin:80px auto;padding:32px;background:#fff;border-top:6px solid #f57c1f;border-radius:16px;box-shadow:0 10px 30px rgba(179,71,0,.18)}h1{margin-top:0;color:#b34700;text-align:center}label{display:block;margin-top:18px;font-weight:bold;color:#b34700}input{box-sizing:border-box;width:100%;padding:12px;margin-top:7px;border:1px solid #f0dcbb;border-radius:8px;font-size:16px}button{width:100%;margin-top:24px;padding:14px;border:0;border-radius:8px;background:#f57c1f;color:#fff;font-size:17px;font-weight:bold;cursor:pointer}.error{color:#b00020;text-align:center;margin-top:16px}.back{display:block;text-align:center;margin-top:18px;color:#b34700}

    
</style>


</head>
<body><main><h1>Employee Login</h1><form method="post">  
<input id="username" name="username" required autocomplete="username">
<label for="password">Password</label>
<input id="password" name="password" type="password" required autocomplete="current-password">
<button type="submit">Login</button>
</form><?php if ($error !== ''): ?><p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
</p><?php endif; ?>
<a class="back" href="registration.html">Back to registration</a>
</main>
</body>
</html>
