document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("submitNewPassword").addEventListener("click", checkPassword);
});

function checkPassword(){
    const password = document.getElementById('password').value;
    const repassword = document.getElementById('repassword').value;

    if(password.length < 8){
        alert("Password must be at least 8 characters long.");
        event.preventDefault();
        return;
    }

    if(!(/\d/.test(password))){
        alert("Password must contains number.");
        event.preventDefault();
        return;
    }

    if(!((/[a-z]/.test(password) && /[A-Z]/.test(password)))){
        alert("Password must contains upeer case and lower case.");
        event.preventDefault();
        return;
    }

    if(password !== repassword){
        alert("Passwords do not match!");
        event.preventDefault();
        return;
    }
}