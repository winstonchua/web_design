<?php
    session_start();

    $message = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true ? 
               "Hello, " . $_SESSION["customer_name"] . "!" : "You are not logged in.";

    // Check if the signup response exists
    $shouldShowSuccessModal = isset($_SESSION['signup_response']) && $_SESSION['signup_response'] === "User registered successfully!";

?>

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Penta Cafe</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/styles.css">
        <script src="../js/homepage.js"></script>
    </head>

    <body>
    <?php
        include '../php/nav.php';
        include '../php/signin_modal.php';    
    ?>


        <!-- Modals -->
        <div id="signInModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h5 class="modal-title">Sign In</h5>
                <form action="../php/signin.php" method="post" class="modal-form">
                    <div class="signin-details">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div class="signin-details">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" required>
                    </div>
                    <button type="submit" class="submit-btn">Sign In</button>
                </form>
            </div>
        </div>
        
        <!-- Sign Up Modal Content -->
        <div id="signUpModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h5 class="modal-title">Sign Up</h5>
                <form action="../php/signup.php" method="post" class="modal-form" id="signup-form">
                    <div class="signin-details">
                        <label for="signup-name">Name:</label>
                        <input type="text" name="name" id="signup-name" required>
                    </div>
                    <div class="signin-details">
                        <label for="signup-email">Email:</label>
                        <input type="email" name="email" id="signup-email" required>
                    </div>
                    <div class="signin-details">
                        <label for="signup-password">Password:</label>
                        <input type="password" name="password" id="signup-password" required>
                    </div>
                    <br>
                    <label for="card-number">Card Number:</label>
                    <input type="text" id="card-number" name="card-number" placeholder="XXXX XXXX XXXX XXXX">
                    <div class="expiry-cvv-group">
                        <div class="expiry-group">
                            <label for="expiry-date">Expiry Date:</label>
                            <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" class="expiry-input">
                        </div>
                        <div class="cvv-group">
                            <label for="cvv">CVV:</label>
                            <input type="text" id="cvv" name="cvv" placeholder="CVV" class="cvv-input">
                        </div>
                    </div>
                    <br>
                    <button type="submit" class="submit-btn">Sign Up</button>
                </form>
             
            </div>
        </div>

        <!-- Success Modal -->
        <div id="successModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h5 class="modal-title">Success</h5>
                <div class="success-message">
                    <?php 
                        if (isset($_SESSION['signup_response'])) {
                            echo $_SESSION['signup_response'];
                            unset($_SESSION['signup_response']); // Clear the session variable
                        } 
                    ?>
                </div>
                <button onclick="closeAndRedirect()" class="submit-btn">Close</button>
            </div>
        </div>



        <div class="body-image"></div>


        <div class="about-us">
            <div class="about-us-text">
                <h2>Savor the Moment</h2>
               <p>At Penta Café, we believe in the power of five - five senses to enjoy our meticulously crafted coffee. Our café is more than just a place to grab a quick cup of coffee. It’s a haven where you can pause, savor the moment, and indulge in the rich symphony of aroma, taste, and warmth that our coffee offers. Come, let’s celebrate the joy of coffee together at Penta Café.</p>
            </div>
            <div class="about-us-image">
                <img src="../../assets/coffee_about_us.jpg" alt="about-image">
            </div>
        </div>
        <div class="chef-header">
            <h2>Meet our Chefs</h2>
        </div>
        <div class="chef-container">
            
            <div class="chef-one">
                <h3>Lucy Smith</h3><br><h4>Executive Chef</h4><br>
                <img src="../../assets/chef1-removebg-preview.png" alt="chef1-image">
            </div>
            <div class="chef-two">
                <h3>John Thomas</h3><br><h4>Sous Chef</h4><br>
                <img src="../../assets/chef2-removebg-preview.png" alt="chef2-image">
            </div>
        </div>

        <div class="orderGuide">

        </div>

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
        <script>
            window.addEventListener('DOMContentLoaded', (event) => {
                if (<?php echo json_encode($shouldShowSuccessModal); ?>) {
                    toggleModal("successModal");
                }
            });
        </script>


    </body>
</html>