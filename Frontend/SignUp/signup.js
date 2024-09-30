// JavaScript function to toggle the visibility of the upload field
function toggleUploadField() {
    var domain = document.getElementById("domain").value;
    var uploadField = document.getElementById("upload-field");

    if (domain === "hawker") {
        uploadField.style.display = "block"; // Show the upload field
    } else {
        uploadField.style.display = "none";  // Hide the upload field
    }
}
