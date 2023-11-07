<?php
session_start();
if (isset($_SESSION['signup_response'])) {
    unset($_SESSION['signup_response']);
}
header("Location: ../html/index.php");  // replace 'your_previous_page.php' with the name of the current page
exit();
?>
