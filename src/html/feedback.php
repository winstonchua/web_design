<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Redirect them to login page or show an error message
        header('Location: index.php'); // Assuming your login page is named 'login.php'
        exit;
    }

    $db = mysqli_connect('localhost','root','','penta');
    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $foodItemsQuery = "SELECT item_id, item_name FROM menu ORDER BY item_name ASC";
    $foodItemsResult = mysqli_query($db, $foodItemsQuery);
    
    // Preparing the food items array
    $foodItems = [];
    if ($foodItemsResult) {
        while ($row = mysqli_fetch_assoc($foodItemsResult)) {
            $foodItems[] = $row;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Retrieve the posted data
        
        $foodItemId = $_POST['foodItem'];
        $rating = $_POST['rating'];
    
        $stmt = $db->prepare("INSERT INTO rating (item_id, item_rating) VALUES (?, ?)");
        $stmt->bind_param("ii", $foodItemId, $rating);
        $stmt->execute();
    
        //Recalculate the average rating for the food item
        $stmt2 = $db->prepare("SELECT AVG(item_rating) as average_rating FROM rating WHERE item_id = ?");
        $stmt2->bind_param("i", $foodItemId);
        $stmt2->execute();
        
        $result = $stmt2->get_result();
        $row = $result->fetch_assoc();
        
        $averageRating = $row['average_rating'];
    
        // Update the average rating in the menu table
        $stmt3 = $db->prepare("UPDATE menu SET item_rating = ? WHERE item_id = ?");
        $stmt3->bind_param("di", $averageRating, $foodItemId);
        $stmt3->execute();
    
    
    
    }
?>


<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Penta Cafe</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/styles.css">
    </head>

    <body>
    
    <?php
        include '../php/nav.php';
        include '../php/signin_modal.php';    
    ?>

    <div class="feedback-body">
        <h6>Food Item Feedback</h6>

        <form id="feedbackForm" method="post" action="">
            <label for="foodItem">Choose a food item:</label>
            <select name="foodItem" id="foodItem">
                <?php foreach($foodItems as $item): ?>
                    <option value="<?php echo htmlspecialchars($item['item_id']); ?>">
                        <?php echo htmlspecialchars($item['item_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="rating">Rating (1-5):</label>
            <input type="number" id="rating" name="rating" min="1" max="5" required>

            <input type="submit" value="Submit Feedback">
        </form>

    </div>
    <script src="../js/feedback.js"></script>

    <?php
        include '../php/footer.php';
    ?>
    </body>
</html>