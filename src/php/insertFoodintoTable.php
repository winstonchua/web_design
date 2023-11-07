<?php
    $db = mysqli_connect('localhost', 'root', '', 'penta');
    if (!$db) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Array of random food items
    $foodItems = ['Pizza', 'Burger', 'Sushi', 'Pasta', 'Salad', 'Tacos', 'Curry', 'Noodles', 'Sandwich', 'Steak'];

    foreach ($foodItems as $item) {
        // Prepare the insert statement
        $stmt = mysqli_prepare($db, "INSERT INTO food_items (item_name) VALUES (?)");
        mysqli_stmt_bind_param($stmt, 's', $item);

        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Check for errors
        if(mysqli_stmt_error($stmt)) {
            echo "Error inserting item $item: " . mysqli_stmt_error($stmt) . "<br/>";
        } else {
            echo "Successfully inserted item $item<br/>";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }

    mysqli_close($db);
?>
