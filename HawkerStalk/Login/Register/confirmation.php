<!-- confirmation.php -->
<?
session_start();
ini_set('session.cookie_domain', '.domain.com');
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
        // Get the email from the POST request securely using htmlspecialchars
        if (!empty($_SESSION["email"])) {
            $email = $_SESSION["email"];
        } else {
            echo "<p>Error: Email not provided!</p>";
            exit;
        }
    ?>
    
    <div class="message-container">
        <h1 class="message">Thank you for signing up!</h1>
        <!-- Hidden paragraph to hold the email address for use in JavaScript -->
        <p id="to" style="display:none;"><?php echo $email; ?></p>
        <button class="back-button" onclick="sendMail()">Send Confirmation Email</button>
    </div>

    <script>
        function sendMail(){
            // Initialize EmailJS
            (function(){
                emailjs.init("ecpfQCyWBlizVS5MW"); // Your EmailJS public key
            })();

            // Get the email from the PHP-generated content
            var params = {
                to: document.querySelector("#to").textContent
            };

            var serviceId = "service_df5rsb4"; // Email service ID
            var templateId = "template_ley1kxr"; // Email template ID

            // Send the email using EmailJS
            emailjs.send(serviceId, templateId, params)
            .then(function(response) {
                alert("Confirmation email sent successfully!");
                window.location.href = 'Location: ../index.html'; // Redirect back to the login page
            })
            .catch(function(error) {
                console.error("Failed to send email:", error);
            });
        }
    </script>
</body>
</html>
