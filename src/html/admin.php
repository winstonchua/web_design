<!DOCTYPE HTML>
<html lang="en">
    
<?php 
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['customer_email'] !== 'f31ee@localhost') {
        // Redirect them to login page or show an error message
        header('Location: index.php'); // Assuming your login page is named 'login.php'
        exit;
    }
    $db = new mysqli('localhost', 'root', '', 'penta');

    // Check for errors
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>

<?php include '../php/head.php'; ?>

<body>
    <?php
        include '../php/nav.php';
        include '../php/signin_modal.php';    
    ?>
    
    <?php
// Connect to the database
$db = new mysqli('localhost', 'root', '', 'penta');

// Check for errors
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch all items
$result = $db->query("SELECT * FROM menu");
$items = $result->fetch_all(MYSQLI_ASSOC);

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['edit_price'])) {
        // Edit the price of an item
        $itemId = $_POST['item_id'];
        $newPrice = $_POST['new_price'];
        $db->query("UPDATE menu SET item_price = $newPrice WHERE item_id = $itemId");
    } elseif (isset($_POST['deactivate'])) {
        // Deactivate an item
        $itemId = $_POST['item_id'];
        $db->query("UPDATE menu SET active = 0 WHERE item_id = $itemId");
    } elseif (isset($_POST['activate'])) {
        // Activate an item
        $itemId = $_POST['item_id'];
        $db->query("UPDATE menu SET active = 1 WHERE item_id = $itemId");
    } elseif (isset($_POST['add_item'])) {
        // Add a new item
        $itemName = $_POST['item_name'];
        $itemPrice = $_POST['item_price'];
        $itemCategory = $_POST['item_category'];
        // Handle the image upload
        $targetDir = "../../assets/menu/";
        $targetFile = $targetDir . basename($_FILES["item_image"]["name"]);
        move_uploaded_file($_FILES["item_image"]["tmp_name"], $targetFile);

        // Save the path to the database
        $itemImage = $targetFile;
        $db->query("INSERT INTO menu (item_name, item_price, item_image, item_category, active) VALUES ('$itemName', $itemPrice, '$itemImage', '$itemCategory', 1)");
    }
}


?>

<!-- Edit Price Form -->
<form class="admin-wrapper" method="post">
    <p>Edit Price <br></p>
    <select name="item_id">
        <?php foreach ($items as $item): ?>
            <option value="<?= $item['item_id'] ?>"><?= $item['item_name'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" name="new_price" step="0.01" min="0">
    <input type="submit" name="edit_price" value="Edit Price">
</form>

<!-- Deactivate Item Form -->
<form class="admin-wrapper" method="post">
    <p>Deactivate Item <br></p>
    <select name="item_id">
        <?php foreach ($items as $item): ?>
            <?php if ($item['active']): ?>
                <option value="<?= $item['item_id'] ?>"><?= $item['item_name'] ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="deactivate" value="Deactivate Item">
</form>

<!-- Activate Item Form -->
<form class="admin-wrapper" method="post">
    <p>Activate Item <br></p>
    <select name="item_id">
        <?php foreach ($items as $item): ?>
            <?php if (!$item['active']): ?>
                <option value="<?= $item['item_id'] ?>"><?= $item['item_name'] ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="activate" value="Activate Item">
</form>

<!-- Add Item Form -->
<form class="admin-wrapper" method="post" enctype="multipart/form-data">
    <p>Add Item <br></p>
    <input type="text" name="item_name" placeholder="Item Name">
    <!-- <input type="text" name="item_image" placeholder="Item Image"> -->
    <input type="file" name="item_image">
    <select name="item_category">
        <option value="Mains">Mains</option>
        <option value="Sides">Sides</option>
        <option value="Deals">Deals</option>
        <option value="Drinks">Drinks</option>
    </select>
    <input type="number" name="item_price" step="0.01" min="0" placeholder="Item Price">
    <input type="submit" name="add_item" value="Add Item">
</form>

    <?php
        include '../php/footer.php';
    ?>
</body>
</html>