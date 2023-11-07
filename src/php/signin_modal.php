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