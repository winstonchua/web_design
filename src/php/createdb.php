<?php
$db = mysqli_connect('localhost', 'root', '', 'penta');
if (!$db) {
    die("Connection failed: " . mysqli_connect_error());
}

$tables = [
    "CREATE TABLE IF NOT EXISTS penta_account (
        id INT AUTO_INCREMENT PRIMARY KEY,
        customer_name VARCHAR(255) NOT NULL,
        customer_email VARCHAR(255) NOT NULL UNIQUE, 
        customer_password VARCHAR(255) NOT NULL,
        customer_card_number VARCHAR(19) NOT NULL, 
        customer_card_expiry DATE NOT NULL, 
        customer_card_cvv VARCHAR(4) NOT NULL
    )",
    "CREATE TABLE IF NOT EXISTS rating (
        item_id INT,
        item_rating DECIMAL(5,2)
    );",
    "CREATE TABLE IF NOT EXISTS orders (
        ID INT PRIMARY KEY AUTO_INCREMENT,
        order_date DATE,
        Order_ID INT,
        email VARCHAR(255),
        item INT,
        quantity INT,
        item_price DECIMAL(5,2)
    )",
    "CREATE TABLE IF NOT EXISTS menu (
        item_id INT PRIMARY KEY AUTO_INCREMENT,
        item_image VARCHAR(255),
        item_name VARCHAR(255),
        item_price DECIMAL(5,2),
        item_rating INT,
        item_category VARCHAR(255),
        active BOOLEAN
    )",
    "CREATE TABLE IF NOT EXISTS delivery_address (
        order_id INT PRIMARY KEY,
        delivery_address VARCHAR(255)
    )",
    "CREATE TABLE IF NOT EXISTS current_ID (
        current_ID INT
    )",
    "CREATE TABLE IF NOT EXISTS support (
        customer_name VARCHAR(255) NOT NULL,
        customer_email VARCHAR(255) NOT NULL, 
        customer_phone_number VARCHAR(19),
        customer_problem TEXT NOT NULL
    );"
];

foreach ($tables as $k => $sql) {
    if (!mysqli_query($db, $sql)) {
        echo "Error creating table $k: " . mysqli_error($db) . "\n";
    } else {
        echo "Table $k created successfully.\n";
    }
}

// Check if menu table exists and insert data if it does
$menu_check_query = "SELECT COUNT(*) AS cnt FROM menu";
$menu_check_result = mysqli_query($db, $menu_check_query);
$menu_row = mysqli_fetch_assoc($menu_check_result);

if ($menu_row['cnt'] == 0) { // If the table is empty, insert the data
    $insert_query = "INSERT INTO menu (item_image, item_name, item_price, item_category, active) VALUES
    ('../../assets/menu/burger.jpg', 'Burger', 9.99, 'Mains', 1),
    ('../../assets/menu/burger-set.jpg', 'Burger Set', 12.99, 'Deals', 1),
    ('../../assets/menu/chicken-wings.jpg', 'Chicken Wings', 8.99, 'Sides', 1),
    ('../../assets/menu/fries.jpg', 'Fries', 4.99, 'Sides', 1),
    ('../../assets/menu/hot-coffee.jpg', 'Hot Coffee', 2.99, 'Drinks', 1),
    ('../../assets/menu/hot-latte.jpg', 'Hot Latte', 3.99, 'Drinks', 1),
    ('../../assets/menu/hot-milk-tea.jpg', 'Hot Milk Tea', 3.49, 'Drinks', 1),
    ('../../assets/menu/hot-tea.jpg', 'Hot Tea', 2.49, 'Drinks', 1),
    ('../../assets/menu/iced-coffee.jpg', 'Iced Coffee', 3.49, 'Drinks', 1),
    ('../../assets/menu/iced-latte.jpg', 'Iced Latte', 4.49, 'Drinks', 1),
    ('../../assets/menu/iced-milk-tea.jpg', 'Iced Milk Tea', 3.99, 'Drinks', 1),
    ('../../assets/menu/iced-tea.jpg', 'Iced Tea', 2.99, 'Drinks', 1),
    ('../../assets/menu/juice.jpg', 'Juice', 4.99, 'Drinks', 1),
    ('../../assets/menu/pasta.jpg', 'Pasta', 11.99, 'Mains', 1);";

    if (mysqli_query($db, $insert_query)) {
        echo "Data inserted into menu table successfully.\n";
    } else {
        echo "Error inserting data into menu table: " . mysqli_error($db) . "\n";
    }
}

mysqli_close($db);
?>
