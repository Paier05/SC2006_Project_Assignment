document.addEventListener("DOMContentLoaded", function(){
    document.getElementById("sendEmailButton").addEventListener("click" ,emailSending);
});

function emailSending(){
    emailjs.init("ecpfQCyWBlizVS5MW"); // Your EmailJS public key

    var params = {
        to: document.getElementById("email").value,  
    };

    //alert();

    var serviceId = "service_df5rsb4"; // Email service ID
    var templateId = "template_fc316zf"; // Email template ID

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