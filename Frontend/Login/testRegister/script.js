document.getElementById("registerForm").addEventListener("submit", function(event) {
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;
    let message = document.getElementById("message");

    // Basic validation (expand as needed)
    if (password.length < 6) {
        event.preventDefault();
        message.textContent = "Password must be at least 6 characters long.";
    } else {
        message.textContent = "";
    }
});
