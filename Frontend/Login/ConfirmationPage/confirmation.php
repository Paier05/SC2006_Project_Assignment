<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation Email Sending</title>
    <link rel="stylesheet" href="confirmation.css">
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
</head>
<body>
    <?php
        // Retrieve email from session securely
        $sending_email = $_SESSION['mail'];

        // Check if email exists and is not empty
        if (!isset($sending_email) || empty($sending_email)) {
            echo "<p>Error: Email not provided!</p>";
            exit;
        }

        // Output the email in a JavaScript variable for use in the script
        echo "<script>var email = '".htmlspecialchars($sending_email)."';</script>";
    ?>
    
    <div class="message-container">
        <h1 class="message">Thank you for signing up!</h1>
        <button class="back-button" id="sendEmailButton">Send Confirmation Email</button>
    </div>

    <!-- Move script to the bottom to ensure the DOM is fully loaded before it runs -->
    <script>
        // Wait until the DOM is fully loaded
        document.addEventListener("DOMContentLoaded", function() {
            // Attach the event listener to the button after DOM is loaded
            document.getElementById("sendEmailButton").addEventListener("click", sendMail);
        });

        function sendMail() {
            // Initialize EmailJS
            emailjs.init("ecpfQCyWBlizVS5MW"); // Your EmailJS public key

            // Set parameters with the email variable from PHP
            var params = {
                to: email,  // Using the global 'email' variable set by PHP
            };

            //alert(email);

            var serviceId = "service_df5rsb4"; // Email service ID
            var templateId = "template_ley1kxr"; // Email template ID

            // Send email using EmailJS
            emailjs.send(serviceId, templateId, params)
            .then(function(response) {
                alert("Confirmation email sent successfully!");
                window.location.href = '../index.html'; // Redirect after success
            })
            .catch(function(error) {
                console.error("Failed to send email:", error);
            });
        }
    </script>
</body>
</html>
