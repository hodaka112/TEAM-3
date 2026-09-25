<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

function registration_value(string $field): string
{
    return trim($_POST[$field] ?? '');
}

function registration_error(string $message): void
{
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => $message]);
    exit;
}

$lastnm = registration_value('lastnm');
$accountType = registration_value('accountType');
$middleName = registration_value('middlenm');
$gender = registration_value('gender');
$department = registration_value('department');
$firstnm = registration_value('firstnm');
$birthdate = registration_value('birthdate');
$email = registration_value('email');
$username = registration_value('username');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$contactnum = registration_value('contactnum');
$address = registration_value('address');

if (!in_array($accountType, ['customer', 'employee'], true)) {
    registration_error('Please select Customer or Employee.');
}
if (mb_strlen($lastnm) < 2) {
    registration_error('Please enter your last name.');
}
if (mb_strlen($firstnm) < 2) {
    registration_error('Please enter your first name.');
}
if ($gender !== '' && !in_array($gender, ['Male', 'Female', 'Other', 'Prefer not to say'], true)) {
    registration_error('Please select a valid gender.');
}
if ($gender === '') {
    $gender = null;
}
if ($accountType === 'employee' && !in_array($department, ['Management', 'IT'], true)) {
    registration_error('Please select a department.');
}
if ($birthdate === '' || !DateTime::createFromFormat('Y-m-d', $birthdate)) {
    registration_error('Please enter your birthdate.');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    registration_error('Please enter your email.');
}
if (mb_strlen($username) < 3) {
    registration_error('Username must be at least 3 characters.');
}
if (strlen($password) < 8) {
    registration_error('Password must be at least 8 characters.');
}
if ($password !== $confirmPassword) {
    registration_error('Passwords do not match.');
}
if (!preg_match('/^09[0-9]{9}$/', $contactnum)) {
    registration_error('Please enter a valid 11-digit phone number starting with 09.');
}
if (mb_strlen($address) < 5) {
    registration_error('Please enter your complete address.');
}

require __DIR__ . '/db.php';

try {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    if ($accountType === 'employee') {
        $statement = $connection->prepare(
            'INSERT INTO employees
             (first_name, middle_name, last_name, birthdate, gender,
              email, phone_number, address, username, password, department)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->bind_param(
            'sssssssssss',
            $firstnm,
            $middleName,
            $lastnm,
            $birthdate,
            $gender,
            $email,
            $contactnum,
            $address,
            $username,
            $hashedPassword,
            $department
        );
    } else {
        $statement = $connection->prepare(
            'INSERT INTO customers
             (first_name, middle_name, last_name, birthdate, gender,
              email, phone_number, address, username, password)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $statement->bind_param(
            'ssssssssss',
            $firstnm,
            $middleName,
            $lastnm,
            $birthdate,
            $gender,
            $email,
            $contactnum,
            $address,
            $username,
            $hashedPassword
        );
    }
    $statement->execute();

    echo json_encode(['success' => true, 'message' => 'Registration successful!']);
} catch (mysqli_sql_exception $exception) {
    // mysqli strict mode makes prepare(), bind_param(), and execute() throw here.
    // Keep database details out of the browser, but record them in the PHP/Apache error log.
    error_log(
        ucfirst($accountType)
            . ' registration database error [MySQL '
            . $exception->getCode()
            . ']: '
            . $exception->getMessage()
    );

    $isDuplicate = $exception->getCode() === 1062;
    $message = $isDuplicate
        ? 'That email address or username is already registered.'
        : 'Unable to complete registration. Please try again.';
    http_response_code($isDuplicate ? 409 : 500);
    echo json_encode(['success' => false, 'message' => $message]);
}
