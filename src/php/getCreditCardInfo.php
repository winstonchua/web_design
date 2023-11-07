<?php
session_start();

// Your database connection code
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

// Convert YYYY-MM-DD to MM/YY
function convertToExpiryFormat($databaseDate) {
    $date = new DateTime($databaseDate);
    return $date->format('m/y');
}

// Assuming the user's email is stored in the session after they log in
$customer_email = $_SESSION['customer_email'];

// Fetch credit card details
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->prepare("SELECT customer_card_number, customer_card_expiry, customer_card_cvv FROM penta_account WHERE customer_email = ?");
    $stmt->execute([$customer_email]);
    $credit_card_info = $stmt->fetch();
    
    // Convert the expiry date to MM/YY format
    $credit_card_info['customer_card_expiry'] = convertToExpiryFormat($credit_card_info['customer_card_expiry']);
    
    echo json_encode($credit_card_info);
} catch (\PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
