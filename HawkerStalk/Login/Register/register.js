// Toggle upload field based on domain selection
function toggleUploadField() {
    var domain = document.getElementById("domain").value;
    var uploadField = document.getElementById("upload-field");

    if (domain === "hawker") {
        uploadField.style.display = "block"; // Show the upload field
    } else {
        uploadField.style.display = "none";  // Hide the upload field
    }
}

// Validate form submission and password requirements
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('domain').addEventListener('change', toggleUploadField);

    document.getElementById('customerForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const domain = document.getElementById('domain').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const repassword = document.getElementById('repassword').value;

        // Check if password length is long enough
        if (password.length < 8) {
            alert("Password must be at least 8 characters long.");
            return;
        }

        // Check if password contains a number
        if (!(/\d/.test(password))) {
            alert("Password must contain at least one number.");
            return;
        }

        // Check if password contains both uppercase and lowercase characters
        if (!(/[a-z]/.test(password) && /[A-Z]/.test(password))) {
            alert("Password must contain both uppercase and lowercase characters.");
            return;
        }

        // Check if passwords match
        if (password !== repassword) {
            alert("Password mismatch!");
            return;
        }
        
        // Submit form data to the server here
        console.log({
            domain: domain,
            email: email,
            password: password,
        });

        // Proceed with form submission
        this.submit();
    });
});
