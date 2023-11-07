<?php
    session_start();
    $db = new mysqli('localhost', 'root', '', 'penta');

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Redirect them to login page or show an error message
        header('Location: index.php'); // Assuming your login page is named 'login.php'
        exit;
    }
    
    $credit_card_info = [];
    if (isset($_POST['checkout'])) {
        $_SESSION['delivery_address'] = $_POST['delivery-address'] . ', ' . $_POST['delivery-address2'];;
    }
    if (isset($_SESSION['customer_email'])) {
        // Use mysqli prepared statement to fetch credit card info
        $stmt = $db->prepare("SELECT customer_card_number, customer_card_expiry, customer_card_cvv FROM penta_account WHERE customer_email = ?");
        $stmt->bind_param("s", $_SESSION['customer_email']);
        $stmt->execute();
        $result = $stmt->get_result();
        $credit_card_info = $result->fetch_assoc();
    
        // Convert the date format if needed
        if ($credit_card_info && isset($credit_card_info['customer_card_expiry'])) {
                $credit_card_info['customer_card_expiry'] = convertToExpiryFormat($credit_card_info['customer_card_expiry']);
        }
    }

        // Convert YYYY-MM-DD to MM/YY
        function convertToExpiryFormat($databaseDate) {
            $date = new DateTime($databaseDate);
            return $date->format('m/y');
        }

        $customer_email = $_SESSION['customer_email'];


    if(isset($_POST['proceed'])) { // Check if the proceed button was clicked
        $customer_email = $_SESSION['customer_email'];

        // Initialize order details
        $orderDetails = "Your order details:\n";

        // Check if the CART session is set and not empty
        if(isset($_SESSION["CART"]) && !empty($_SESSION["CART"])) {
            // Loop through each item in the cart
            foreach($_SESSION["CART"] as $item => $quantity) {
                // Get the name of the item from the database or another source
                $itemName = getItemNameById($item); // Placeholder function

                // Append each item's details to the order details string
                $orderDetails .= $itemName . " x " . $quantity . " - $" . number_format($_SESSION['PRICE'][$item]*$quantity, 2) . "\n";
                
                // Assuming you have a function to get the item name by its ID (you would need to create this function)
            }
        }
        $orderDetails .= "Total Price: " . $_SESSION['totalPrice'] . "\n" . "Delivery Address: " . $_SESSION['delivery_address'];

        // Sending an email to the local server
        $subject = "Order Confirmation";
        $message = "Hello, \n\n" . $orderDetails . "\n\nThank you for shopping with us!";
        $headers = 'From: f31ee@localhost' . "\r\n" .
        'Reply-To: f31ee@localhost' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
        mail($customer_email, $subject, $message, $headers, '-ff31ee@localhost');
    }
    function getItemNameById($itemId) {
        global $db; // Use the database connection from the global scope
        $stmt = $db->prepare("SELECT item_name FROM menu WHERE item_id = ?");
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['item_name'] : "Unknown Item"; // Return the item name or "Unknown Item" if not found
    }
    
    if (isset($_POST['proceed'])) {


        // ========================================All these below are for payment========================================
        if(isset($_SESSION["CART"])) {
            if (isset($_POST['delivery-address'])) {
                $_SESSION['delivery_address'] = $_POST['delivery-address'] . ', ' . $_POST['delivery-address2'];;
            }

            //Retrieve latest order ID
            $sql = "SELECT current_ID FROM current_id";
            $result = $db->query($sql);
                // Fetch the first row as an associative array
                $row = $result->fetch_assoc();
                // Get the value of the first column
                $Order_ID = $row['current_ID'];

            $stmt = $db->prepare("INSERT INTO orders (Order_ID, order_date, email, item, quantity, item_price) VALUES (?, ?, ?, ?, ?, ?)");
            $date = date("Y-m-d");
            // Loop through each item in the cart
            foreach($_SESSION["CART"] as $item => $quantity) {
                // Bind the parameters to the SQL query
                $stmt->bind_param('isssid',$Order_ID, $date, $_SESSION["customer_email"], $item, $quantity,$_SESSION['PRICE'][($item)]);
                // Execute the SQL query
                $stmt->execute();if ($stmt->error) {
                    echo "Error: " . $stmt->error;
                }

            }
            
            $stmt->close();
            //Send delivery address to DB
            $stmt = $db->prepare("INSERT INTO delivery_address (Order_ID, delivery_address) VALUES (?, ?)");
            $stmt->bind_param('is',$Order_ID, $_SESSION["delivery_address"]);
            $stmt->execute();
            $_SESSION['order_success'] = "Order placed successfully!";
            $stmt->close();

            // Update the latest order ID
            $Order_ID = $Order_ID + 1;
            $stmt = $db->prepare("UPDATE current_ID SET current_ID = ?");
            $stmt->bind_param('i',$Order_ID);
            $stmt->execute();
            $stmt->close();

            // Clear the CART session variable
            unset($_SESSION["CART"]);
            unset($_SESSION["delivery_address"]);

        } else {
            // No items in cart
            echo "No items in cart";
        }// ========================================All these above are for payment========================================
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
    <div class="navbar">
        <a href="index.php" class="logo">
            <img src="../../assets/penta_cafe_logo_new2.png" alt="Logo">
        </a>
        <div class="tabs">
            <a href="menu.php">Menu</a> 
            <a href="cart.php">Cart</a>  
            <a href="order.php">Order</a> 
            <a href="support.php">Support</a> 
            <a href="feedback.php">Feedback</a> 
            <div id="authLinks">
                    Welcome: <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
                    <a href="../php/signout.php">Sign Out</a>
            </div>
        </div>
    </div>

    <div class=payment-body>
        <form action="" method="post" class="checkout">

            <div class="payment-information">
                <h5>Payment Information</h5>
                Total billable: $<span id="billableAmount"><?php echo($_SESSION['totalPrice'])?>
                </span> 
            </div>
            <div class="payment-choice">
                <h5>Select Payment Method</h5>
                
                <div class="btn-group">
                    <button type="button" id="creditCardBtn" class="payment-btn">
                        <img src="../../assets/credit_card_icon.png" alt="Credit Card Icon">
                        <span>Credit Card</span>
                    </button>

                    <button type="button" id="cashBtn" class="payment-btn">
                        <img src="../../assets/cash_icon.png" alt="Cash Icon">
                        <span>Cash</span>
                    </button>

                </div>
                <div id="creditCardDetails" class="card-details-container">
                    Card Number: <span id="cardNumber"><?= htmlspecialchars($credit_card_info['customer_card_number'] ?? '') ?></span><br>
                    Expiry Date: <span id="cardExpiry"><?= htmlspecialchars($credit_card_info['customer_card_expiry'] ?? '') ?></span><br>
                    CVV: <span id="cardCVV"><?= htmlspecialchars($credit_card_info['customer_card_cvv'] ?? '') ?></span><br>
                    <br>
                    <button id="proceedButton" name="proceed" type="submit">Proceed</button>

                </div>


                <div id="cashMessage" class="cash-message-container" style="display: none;">
                    ✓ Cash on delivery
                    <br>
                    <br>
                    <button id="proceedButton" name="proceed" type="submit">Proceed</button>

                </div>
            </div>
            
        </form>
    </div>

    <?php if (isset($_SESSION['order_success'])): ?>
        <script>
            alert('<?php echo $_SESSION['order_success']; ?>');
            window.location = 'order.php'; // Redirect to order.php
        </script>
        <?php unset($_SESSION['order_success']); // Clear the message after displaying it ?>
    <?php endif; ?>


    <script src="../js/payment.js"></script>

    <footer>
        <div class="footer">
            <div class="copyright">
                <small><i>Copyright &copy; Penta Cafe 2023 
                </i></small>
            </div>
            <div class="home-detail">
                <small>
                    Address<br>
                    Nanyang Technological University <br>
                    Singapore 123456
                </small>
            </div>
        </div>
    </footer>
    </body>
</html>