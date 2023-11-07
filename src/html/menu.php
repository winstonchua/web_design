<!DOCTYPE HTML>
<html lang="en">
    <?php 
        session_start();
        $db = new mysqli('localhost', 'root', '', 'penta');

        // Check for errors
        if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
        }

        $numItems = 0;
        $totalPrice = 0.0;
        $filter_category = null;

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            foreach ($_POST as $key => $value) {
                $value = (int)$value;
                if($value > 0){
                    if(!isset($_SESSION['CART'][($key)])){
                        $_SESSION['CART'][($key)] = $value;
                    }else{
                        $_SESSION['CART'][($key)] = $_SESSION['CART'][($key)] + $value;

                    }
                }                    
            }
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['category'])) {
                $filter_category = $_GET['category'];
            } else {
                $filter_category = null; 
            }
        }
    ?>
    <?php include '../php/head.php'; ?>

    <body>
    <?php
        include '../php/nav.php';
        include '../php/signin_modal.php';    
    ?>
    

        <div class="menu-body">
            <!-- Filters -->
            <ul class="menu-filter">
                <li class="menu-filter-item">
                    <form action="" method="get">
                        <input type="hidden" name="category" value="">
                        <button type="submit" class="menu-filter-link">All</button>
                    </form>
                </li>
                <li class="menu-filter-item">
                    <form action="" method="get">
                        <input type="hidden" name="category" value="Mains">
                        <button type="submit" class="menu-filter-link">Mains</button>
                    </form>
                </li>
                <li class="menu-filter-item">
                    <form action="" method="get">
                        <input type="hidden" name="category" value="Sides">
                        <button type="submit" class="menu-filter-link">Sides</button>
                    </form>
                </li>
                <li class="menu-filter-item">
                    <form action="" method="get">
                        <input type="hidden" name="category" value="Drinks">
                        <button type="submit" class="menu-filter-link">Drinks</button>
                    </form>
                </li>
                <li class="menu-filter-item">
                    <form action="" method="get">
                        <input type="hidden" name="category" value="Deals">
                        <button type="submit" class="menu-filter-link">Deals</button>
                    </form>
                </li>
            </ul>

            <form class="menu-right" method="post" action="">  
            <?php
            // Loop through the results and display the menu items
            
            $sql = "SELECT item_id, item_image, item_name, item_rating, item_price, item_category, active FROM menu";
            $result = $db->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Get the values from the row
                    $itemId = stripslashes($row['item_id']);
                    $image = $row["item_image"];    
                    $name = $row["item_name"];
                    $rating = $row["item_rating"];
                    $price = $row["item_price"];
                    $category = $row["item_category"];
                    $active = $row["active"];

                    if ($_SERVER["REQUEST_METHOD"] == "POST"){
                        $_SESSION['PRICE'][($key)] = $price;
                    }

                
                    if(isset($_SESSION['CART'][$itemId])){  
                        $itemTotal = $price * $_SESSION['CART'][$itemId];
                        $totalPrice = $totalPrice + $itemTotal;
                        $numItems = $numItems + $_SESSION['CART'][$itemId];
                        $_SESSION['totalPrice'] = $totalPrice;
                    }
                    if($filter_category != null){
                        if($category != $filter_category){
                            continue;
                        }
                    }

                    if($active == 0){
                        continue;
                    }
                    // Echo the HTML code for the menu 

                    echo '<div class="menu-item">';
                    echo '<div class="menu-item-image">';
                    echo '<img src="' . $image . '" alt="' . $name . '" class="menu-image">';
                    echo '</div>';
                    echo '<div class="menu-item-name">';
                    echo '<span>' . $name . '</span>';
                    echo '</div>';
                    echo '<div class="menu-item-rating">';
                    echo '<p>Rating: ' . $rating . '&#9733</p>';
                    echo '</div>';
                    echo '<div class="menu-item-price">';
                    echo '$' . $price;
                    echo '</div>';
                    echo '<div class="menu-item-number">';
                    echo '<input type="number" name ='.$itemId.' min="0">';
                    echo '</div>';
                    echo '</div>';
                }
                echo '<input type = "submit" class="menu-submit" value="Add to Cart"></input>';
            } else {
            // No results found
            echo "No menu items found";
            }
            // Close the database connection
            $db->close();
            ?>
                <!-- <div class="menu-item">
                   <div class="menu-item-image">
                    
                   </div>
                   <div class="menu-item-name">
                        <span>
                            
                        </span>
                    </div>
                    <div class="menu-item-rating">
                        <p>Rating: /5</p>
                    </div>
                    <div class="menu-item-price">
                        
                    </div>
                    <div class="menu-item-number">
                        <input type="number"/>
                    </div>
                </div> -->
            </form>
        </div>

        <div class="cart-summary">
            <h2>Cart Summary</h2>
            <p>Number of items: <?php echo $numItems; ?></p>
            <p>Total price: $<?php echo $totalPrice; ?></p>
        </div>

        <?php
            include '../php/footer.php';
        ?>
    </body>
</html>