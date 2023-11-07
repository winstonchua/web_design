<?php
session_start();

$host = 'localhost';
$db   = 'penta';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$response = [];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    $customer_name = $_POST['name'];
    $customer_email = $_POST['email'];
    $customer_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $customer_card_number = $_POST['card-number'];
    $customer_card_expiry = convertToDatabaseDate($_POST['expiry-date']); // Convert MM/YY to YYYY-MM-DD
    $customer_card_cvv = $_POST['cvv'];

    $stmt = $pdo->prepare("INSERT INTO penta_account (customer_name, customer_email, customer_password, customer_card_number, customer_card_expiry, customer_card_cvv) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$customer_name, $customer_email, $customer_password, $customer_card_number, $customer_card_expiry, $customer_card_cvv]);
    
    $_SESSION['signup_response'] = "User registered successfully!";

} catch (\PDOException $e) {
    $_SESSION['signup_response'] = "Error: " . $e->getMessage();
}

header("Location: ../html/index.php");
exit();

// Convert MM/YY to YYYY-MM-DD
function convertToDatabaseDate($expiry) {
    list($month, $year) = explode('/', $expiry);
    $year = '20' . $year;  // This assumes all cards are post-2000
    return "$year-$month-01";
}

// Convert YYYY-MM-DD to MM/YY (Not used in this script but may be useful later)
function convertToExpiryFormat($databaseDate) {
    $date = new DateTime($databaseDate);
    return $date->format('m/y');
}
