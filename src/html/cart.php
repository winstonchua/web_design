<!DOCTYPE HTML>
<html lang="en">
    <?php 
        session_start();
        $db = new mysqli('localhost', 'root', '', 'penta');

        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        // Check if the checkout POST variable is set
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['checkout'])) {
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                // User is logged in, redirect to payment.php
                $_SESSION['delivery_address'] = $_POST['delivery-address'] . ', ' . $_POST['delivery-address2'];;

                header('Location: payment.php');
                exit;
            } else {
                // User is not logged in, show sign in modal
                $_SESSION["showSignInModal"] = true;
            }
        }

        // Handle add/remove/delete actions
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $key = array_key_first($_POST);
            if (!isset($_POST['checkout'])) { // Skip if checkout action
                switch($_POST[$key]){
                    case '1':
                        // Add logic...
                        $_SESSION['CART'][$key] = $_SESSION['CART'][$key] + 1;
                        break;
                    case '2':
                        // Remove logic...
                        $_SESSION['CART'][$key] = $_SESSION['CART'][$key] - 1;
                        if($_SESSION['CART'][$key] < 1){
                            unset($_SESSION['CART'][$key]);
                        }
                        break;
                    case '3':
                        // Delete logic...
                        unset($_SESSION['CART'][$key]);
                        break;
                }
            }
        }
    ?>
    <?php include '../php/head.php'; ?>

    <body>
    <?php
        include '../php/nav.php';
        include '../php/signin_modal.php';    
    ?>
        <div class="body-wrapper">
        <form class="cart-table-wrapper" method="post" action="">
        <?php
            // Loop through the results and display the menu items
            
            $sql = "SELECT item_id, item_image, item_name, item_rating, item_price FROM menu";
            $result = $db->query($sql);
            
            if ($result->num_rows > 0) {
                if ($_SERVER["REQUEST_METHOD"] != "POST"){
                if(!empty($_SESSION['CART'])){
                    $totalPrice = 0;
                    echo '<table class="cart-table">';
                    echo '<thead>';
                    echo '<th>Item Image</th>';
                    echo '<th>Item Name</th>';                
                    echo '<th>Item Quantity</th>';
                    echo '<th>Price</th>';
                    echo '<th> Add/Remove </th>';
                    echo '</thead>';
                    while($row = $result->fetch_assoc()) {
                        // Get the values from the row
                        $itemId = $row['item_id'];
                        $image = $row["item_image"];    
                        $name = $row["item_name"];
                        $rating = $row["item_rating"];
                        $price[$itemId] = $row["item_price"];
                        $_SESSION['PRICE'][($itemId)] = $price[$itemId];
                        // echo "Test ".$_SESSION['CART'][($itemId)]." Test ".($itemId)."";
                        if(isset($_SESSION['CART'][$itemId])){  
                            
                            $itemTotal = $price[$itemId] * $_SESSION['CART'][$itemId];
                            $totalPrice = $totalPrice + $itemTotal;

                            echo '<tr>';
                            echo '<td><img src='.$image.' alt='.$name.' width="100px"></td>';
                            echo '<td>'.$name.'</td>';
                            echo '<td>'.$_SESSION['CART'][$itemId].'</td>';
                            echo '<td>$'.$itemTotal.'</td>';
                            echo '<td>';
                            echo '<button type="submit" name="'.$itemId.'" value="1" class="cart-button">
                                <img src="../../assets/add.png" alt="Add" class="cart-edit" >
                                </button>';
                            echo '<button type="submit" name="'.$itemId.'" value="2" class="cart-button">
                                <img src="../../assets/remove.png" alt="Remove" class="cart-edit" >
                                </button>';
                            echo '<button type="submit" name="'.$itemId.'" value="3" class="cart-button">
                                <img src="../../assets/delete.png" alt="Delete" class="cart-edit" >
                                </button>';
                                
                            // echo '<input type="image" src="../../assets/remove.png" alt="Remove" class="cart-edit" name="Remove'.$itemId.'" value=2>';
                            // echo '<input type="image" src="../../assets/delete.png" alt="Delete" class="cart-edit" name="Delete'.$itemId.'" value=3>';
                            echo '</td>';
                            echo '</tr>';
                            
                            
                        }

                    }
                    echo '<tfoot>'; 
                    echo '<td></td>';
                    echo '<td></td>';
                    echo '<td>Total Price</td>';
                    echo '<td>$'.$totalPrice.'</td>';
                    echo '<td></td>';
                    echo '</table>';
                    // echo '<input type = "submit" class="menu-submit"></input>';
                }
                else{
                echo "No items in cart";
                }
            } 
            }
            else {
            // No results found
            echo "No items in cart";
            }
        ?>
        </form>
        

        <!-- Check if user is logged in, if not replace checkout button with sign in button -->
        <?php
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                // User is logged in, show checkout button
                echo '<form action="" method="post" class="checkout-form">';
                echo 'Delivery Address:<br>';
                echo 'Address line 1: <input type="text" class="delivery-address" name="delivery-address" required>';
                echo '<br>';
                echo 'Address line 2: <input type="text" class="delivery-address" name="delivery-address2">';
                echo '<input type="submit" class="checkout-button" name="checkout" value="Proceed to Checkout"></input>';
                echo '</form>';
            } else {
                // User is not logged in, show sign in button
                echo '<div class="checkout-form">';
                echo '<button type="button" class="checkout-button" onclick="toggleModal(\'signInModal\')">Sign In to Checkout</button>';
                echo '</div>';
            }
        ?>
        <!-- <form action="" method="post" class="checkout-form">
            Delivery Address:<br>
            <input type="text" id="delivery-address" name="delivery-address"><br>
            <input type="submit" name="checkout" value="Proceed to Checkout" class="checkout-button">
        </form> -->
        <?php

        if (isset($_POST['checkout'])) {
            if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                // User is logged in, redirect to payment.php`

                header('Location: payment.php');
                exit;
            } else {
                // User is not logged in
                $_SESSION["showSignInModal"] = true;
                echo "<script type='text/javascript'>
                        toggleModal('signInModal');
                        </script>";
                exit;
            }

        }
        ?>
        </div>
        <?php
            include '../php/footer.php';
        ?>
    </body>
</html>