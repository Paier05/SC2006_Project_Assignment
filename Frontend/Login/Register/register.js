function toggleUploadField() {
    var domain = document.getElementById("domain").value;
    var uploadField = document.getElementById("upload-field");

    if (domain === "hawker") {
        uploadField.style.display = "block"; // Show the upload field
    } else {
        uploadField.style.display = "none";  // Hide the upload field
    }
}

function sendMail(callback){
    (function(){
        emailjs.init("ecpfQCyWBlizVS5MW"); // the emailjs key
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

document.getElementById('domain').addEventListener('change', toggleUploadField);

document.getElementById('customerForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const domain = document.getElementById('domain').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const repassword = document.getElementById('repassword').value;

    
    // Check if password length long enough
    if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        return;
    }

    // Check if password contains number
    if (!(/\d/.test(password))) {
        alert("Password must contains number.");
    }

    // Check if password contains both upper case and lower case character
    if (!((/[a-z]/.test(password)) && (/[A-Z]/.test(password)))) {
        alert("Password must contains upper case and lower case.");
        return;
    }

    // Check if passwords match
    if (password !== repassword) {
        alert("Passwords do not match!");
        return;
    }
    
    // You can send form data to the server here
    console.log({
        domain: 'Customer',
        email: email,
        password: password,
    });

    // Optionally, you can submit the form data to the server here
    this.submit();

    
});
