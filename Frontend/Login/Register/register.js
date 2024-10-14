function toggleUploadField() {
    var domain = document.getElementById("domain").value;
    var uploadField = document.getElementById("upload-field");

    if (domain === "hawker") {
        uploadField.style.display = "block"; // Show the upload field
    } else {
        uploadField.style.display = "none";  // Hide the upload field
    }
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
