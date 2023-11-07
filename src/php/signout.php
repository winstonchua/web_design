<?php
// signout.php

session_start();
unset($_SESSION['loggedin']);
unset($_SESSION['customer_name']);
session_destroy();

// Redirect to the homepage or login page
header('Location: ../html/index.php');
exit;
?>
