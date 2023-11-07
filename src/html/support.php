<?php
session_start();

// Check if the user is not logged in, then redirect to the login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

// Define variables for the form data
$customer_name = $customer_email = $customer_phone = $customer_problem = "";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect post variables
    $customer_name = $_POST['name'];
    $customer_email = $_POST['email'];
    $customer_phone = $_POST['phone'];
    $customer_problem = $_POST['problem'];

    // Initialize a new database connection
    $db = new mysqli('localhost', 'root', '', 'penta');
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO support (customer_name, customer_email, customer_phone_number, customer_problem) VALUES (?, ?, ?, ?)");
    // Bind parameters to the prepared statement
    $stmt->bind_param("ssss", $customer_name, $customer_email, $customer_phone, $customer_problem);
    // Execute the statement and check for errors
    if ($stmt->execute()) {
        echo "<script>alert('Support request submitted successfully.');</script>";
    } else {
        echo "<script>alert('Error submitting support request: " . $stmt->error . "');</script>";
    }
    // Close the statement and the database connection
    $stmt->close();
    $db->close();
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
        
    <?php
        include '../php/nav.php';
        include '../php/signin_modal.php';    
    ?>

        <div class="supportbody">
            <div class="supportWrapper">

                <div class="supportForm">
                    <form action="" method="post" id="supportForm"> 
                        <h1 style="text-align: center;">Support</h1>
                        <table>
                            <tr>
                                <td class="formLabel">*Name:</td>
                                <td class="supportTable">
                                    <input type="text" id="jobName" name="name" required placeholder="Enter your name here" style="width: 50%;"> 
                                    <div id="nameError" style="color: red;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="formLabel">*Email:</td>
                                <td class="supportTable">
                                    <input type="email" id="jobEmail" name="email" required placeholder="Enter your Email-ID here " style="width: 50%;">
                                    <div id="emailError" style="color: red;"></div>
                                </td>

                            </tr>
                            <tr>
                                <td class="formLabel">Phone Number:</td>
                                <td class="supportTable">
                                    <input type="phone" id="jobPhone" name="phone" required placeholder="Enter your phone number here " style="width: 50%;">
                                <div id="phoneError" style="color: red;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="formLabel">*Description of Problem:</td>
                                <td class="supportTable">
                                    <textarea name="problem" id="jobProblem" cols="50" rows="10" required placeholder="Describe your problem here" style="resize: none;"></textarea>
                                    <div id="problemError" style="color: red;"></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    
                                </td>
                                <td style="padding-left: 300px;">
                                    <input type="reset" value="Clear">
                                    <input type="submit" value="Send">  
                                </td>
                            </tr>
                        
                        </table>
                    </form>
                </div>
                <div class="contact-us">
                    <table>
                        <tr>
                            <td class="contact-us-table">
                                <div> 
                                    <img src="../../assets/phone_icon_no_bg.png" alt="phone-icon" class="phone-icon">
                                    <!--https://www.pinterest.com/pin/252272016613840132/-->
                                </div>
                                <div class="contact-us-words"> +65 8888 8888</div>
                            </td>
                            <!--email-->
                            <td class="contact-us-table">
                                <div> 
                                    <img src="../../assets/mail_icon_no_bg.png" alt="email-icon" class="email-icon">
                                    <!--https://www.pngwing.com/en/free-png-zgobv/-->
                                </div>
                                <div class="contact-us-words"> support@pentacafe.com</div>
                            </td>
                            <!--address-->
                            <td class="contact-us-table"> 
                                <div> 
                                    <img src="../../assets/address_icon_no_bg.png" alt="address-icon" class="address-icon">
                                    <!--https://www.hiclipart.com/free-transparent-background-png-clipart-czcub/-->
                                </div>
                                <div class="contact-us-words"> Nanyang Technological University <br> Singapore 123456</div>
                            </td>
                        </tr>
                    </table>
                
                </div>
            </div>
        </div>
        <script src="../js/support.js"></script>

        <?php
            include '../php/footer.php';
        ?>
    </body>
</html>