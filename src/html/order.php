<!DOCTYPE HTML>
<html lang="en">
<?php 
    session_start();
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
    
    <div class="body-wrapper">
    <form class="cart-table-wrapper" method="post" action="">
        <input class="input-large" type="text" name="order_ID" placeholder="Enter Order ID">
        <input type="submit" value="Submit">
    </form>
    <?php
        
        //initialise arrays
        $name = array(); 
        $user_order_ID = array();

        // Loop through the results and display the menu items
        $sql = "SELECT item_id, item_name FROM menu";
        $result = $db->query($sql);
        while($row = $result->fetch_assoc()) {
            $name[$row['item_id']] = $row['item_name'];
        }
        $order_date = date("Y-m-d");
        $user_order_ID = array();
        $sql = "SELECT order_ID, email, item, quantity, item_price FROM orders";
        $result = $db->query($sql);
        if (isset($_SESSION['customer_email']) && $_SERVER['REQUEST_METHOD'] != 'POST') {
            if ($result->num_rows > 0) {
                $input_ID = $_SESSION['customer_email'];
                $totalPrice = 0;
                //Create a list of order IDs belongoing to user's email
                while($row = $result->fetch_assoc()) {
                    $email = $row["email"]; 
                    $order_ID = $row["order_ID"];
                    
                    if ($_SESSION['customer_email'] == $email) {
                        if (in_array($order_ID, $user_order_ID)) {
                            continue;
                        } else {
                            $user_order_ID[] = $order_ID;
                        }
                        
                    }

                }
                //Loop through the list of order IDs and display the order details
                foreach ($user_order_ID as $id) {
                    echo '<table class="cart-table">';
                    // echo '<tr>';
                    // echo '<th>Order ID:'.$id.'</th>';
                    // echo '<th>Item Name</th>';
                    // echo '</tr>';
                    
                    echo '<thead>';
                    echo '<th>Item Name</th>';
                    echo '<th>Item Quantity</th>';                
                    echo '<th>Item Unit Price</th>';
                    echo '<th>Sub Total</th>';
                    echo '</thead>';
                    $sql = "SELECT order_ID, email, item, quantity, item_price FROM orders";
                    $result = $db->query($sql);


                    $sql2 = "SELECT order_id, delivery_address FROM delivery_address";
                    $result2 = $db->query($sql2);
                    while($row = $result2->fetch_assoc()) {
                        if ($row['order_id'] == $id) {
                            $delivery_address = $row['delivery_address'];
                        }
                    }

                    while($row = $result->fetch_assoc()) {
                        // Get the values from the row
                        $item = $row['item'];
                        $email = $row["email"];
                        $quantity = $row["quantity"];
                        $order_ID = $row["order_ID"];
                        // $order_ID_prev = $order_ID;
                        $price = $row["item_price"];
                        if (isset($name[$item])){
                            $item_name = $name[$item];
                        }
                        
    
                        if ($order_ID == $id) {
                            $itemTotal = $price * $quantity;
                            $totalPrice = $totalPrice + $itemTotal;
    
                            echo '<tr>';
                            echo '<td>';
                            echo $item_name;
                            echo '</td>';
                            echo '<td>';
                            echo $quantity;
                            echo '</td>';
                            echo '<td>';
                            echo '$'.$price.'';
                            echo '</td>';
                            echo '<td>';
                            echo '$'.$itemTotal.'';
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    echo '<tfoot>'; 
                    echo '<td>Order ID: '.$id.'</td>';
                    echo '<td>Delivery Address: '.$delivery_address.' </td>';
                    echo '<td>Order Date: '.$order_date.' </td>';                    
                    echo '<td>Total Price: $'. number_format($totalPrice, 2) .'</td>';
                    echo '</tfoot>';
                    echo '</table>';
                    // echo '<input type = "submit" class="menu-submit"></input>';
                }


                



                

            }
        } 

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_ID'])) {
            if ($result->num_rows > 0) {
                $input_ID = $_POST['order_ID'];
                $totalPrice = 0;
                $orderFound = false;
                echo '<table class="cart-table">';
                echo '<thead>';
                echo '<th>Item Name</th>';
                echo '<th>Item Quantity</th>';                
                echo '<th>Item Unit Price</th>';
                echo '<th>Sub Total</th>';
                echo '</thead>';

                
                $sql2 = "SELECT order_id, delivery_address FROM delivery_address";
                $result2 = $db->query($sql2);
                while($row = $result2->fetch_assoc()) {
                    if ($row['order_id'] == $input_ID) {
                        $delivery_address = $row['delivery_address'];
                    }
                }

                while($row = $result->fetch_assoc()) {
                    // Get the values from the row
                    $item = $row['item'];
                    $email = $row["email"];
                    $quantity = $row["quantity"];    
                    $order_ID = $row["order_ID"];
                    $price = $row["item_price"];
                    if (isset($name[$item])){
                        $item_name = $name[$item];
                    }

                    if ($order_ID == $input_ID && $input_ID != "") {
                        $orderFound = true;
                        $itemTotal = $price * $quantity;
                        $totalPrice = $totalPrice + $itemTotal;

                        echo '<tr>';
                        echo '<td>';
                        echo $item_name;
                        echo '</td>';
                        echo '<td>';
                        echo $quantity;
                        echo '</td>';
                        echo '<td>';
                        echo '$'.$price.'';
                        echo '</td>';
                        echo '<td>';
                        echo '$'.$itemTotal.'';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
                echo '<tfoot>'; 
                echo '<td>Order ID: '.$input_ID.'</td>';
                echo '<td>Delivery Address: '.$delivery_address.' </td>';
                echo '<td>Order Date: '.$order_date.' </td>';
                echo '<td>Total Price: $'. number_format($totalPrice, 2) .'</td>';
                echo '</tfoot>';
                echo '</table>';
                // echo '<input type = "submit" class="menu-submit"></input>';
                if ($orderFound == false) {
                    echo '<div class="centered-text">';
                    echo 'Order not found';
                    echo '</div>';
                }

            }
            else{
                echo "No orders found";
            } 
        }
        else {
            echo '<div class="centered-text">';
            echo 'Please enter an order ID';
            echo '</div>';        }

    ?>
    </div>
    <?php
        include '../php/footer.php';
    ?>
</body>
</html>