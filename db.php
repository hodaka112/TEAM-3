<?php
// XAMPP MySQL connection. Import database.sql once before using the application.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $connection = new mysqli('localhost', 'root', '', 'team3_db');
    $connection->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    exit('Database connection could not be established. Ensure XAMPP MySQL is running and database.sql has been imported.');
}
