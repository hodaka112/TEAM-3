CREATE DATABASE IF NOT EXISTS team3_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE team3_db;

CREATE TABLE IF NOT EXISTS customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    birthdate DATE,
    gender ENUM('Male','Female','Other','Prefer not to say'),
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15),
    address TEXT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    birthdate DATE,
    gender ENUM('Male','Female','Other','Prefer not to say'),
    email VARCHAR(100) NOT NULL UNIQUE,
    phone_number VARCHAR(15),
    address TEXT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE customers
    MODIFY gender ENUM('Male', 'Female', 'Other', 'Prefer not to say') NULL;

ALTER TABLE employees
    MODIFY gender ENUM('Male', 'Female', 'Other', 'Prefer not to say') NULL;

-- Kat's password is stored only as a password_hash() value.
INSERT INTO employees (
    first_name,
    last_name,
    username,
    password,
    department
)
VALUES (
    'Katherine',
    'Sinagaraw',
    'KittyKat16',
    '$2y$10$fOaTOB74JfPzWdPw3Hiy..7pspk1fSR9d2mhqa63lbGGeDbWV8l1e',
    'Management'
)
ON DUPLICATE KEY UPDATE username = username;

-- Sol's INSERT is intentionally deferred until she supplies her chosen username.
