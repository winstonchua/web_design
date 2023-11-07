<!DOCTYPE HTML>
<html lang="en">

<?php include '../php/head.php'; ?>

<body>
<?php
    include '../php/nav.php';
    include '../php/signin_modal.php';    
?>



<?php
session_start();  // Start the session

if (isset($_POST['email'])) {
    $_SESSION['email'] = $_POST['email'];  // Set the session variable
}
if (isset($_POST['login'])) {
    $_SESSION['loggedin'] = true;  // Set the session variable
}
if (isset($_POST['logout'])) {
    unset($_SESSION['customer_email']);     // Unset the email session variable
    unset($_SESSION['loggedin']);  // Unset the loggedin session variable
    unset($_SESSION['customer_name']);  // Unset the customer_name session variable
}
if (isset($_POST['set_name']) && !empty($_POST['customer_name'])) {
    $_SESSION['customer_name'] = $_POST['customer_name'];  // Set the session variable
}
?>
<form action="" method="post">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email">
    <!-- <input type="submit" value="Submit"> -->
    <input type="text" name="customer_name" placeholder="Enter your name">
    <input type="submit" name="set_name" value="Set Name">
</form>
<form action="set_session.php" method="post">
    <input type="submit" name="login" value="Log In">
</form>
<form action="set_session.php" method="post">
    <input type="submit" name="logout" value="Log Out">
</form>

</body>