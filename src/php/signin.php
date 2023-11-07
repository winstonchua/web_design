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

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Check if the POST data exists
    if(isset($_POST['email']) && isset($_POST['password'])) {
        $customer_email = $_POST['email'];
        $password = $_POST['password'];
    } else {
        $_SESSION['login_response'] = "Please fill in all the fields.";
        header("Location: ../html/index.php");
        exit();
    }

    // Check User's Credentials
    $stmt = $pdo->prepare("SELECT * FROM penta_account WHERE customer_email = ?");
    $stmt->execute([$customer_email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['customer_password'])) { // Adjusted field name
        // Register Session Variables
        $_SESSION["loggedin"] = true;
        $_SESSION["customer_name"] = $user['customer_name'];
        $_SESSION["customer_email"] = $user['customer_email'];
        $_SESSION['login_response'] = "Logged in successfully!";
    } else {
        $_SESSION['login_response'] = "Incorrect email or password.";
    }

} catch (\PDOException $e) {
    $_SESSION['login_response'] = "Database error: " . $e->getMessage();
}

header("Location: ../html/index.php");
exit();
?>