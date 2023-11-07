<?php
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