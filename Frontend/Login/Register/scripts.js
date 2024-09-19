document.getElementById('customerForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const repassword = document.getElementById('repassword').value;

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
