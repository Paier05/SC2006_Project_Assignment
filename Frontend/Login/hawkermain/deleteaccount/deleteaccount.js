function redirectToMainPage(){
    window.location.href = '../hawkermain.html';
}

async function handleFormSubmit(event) {
    event.preventDefault(); // Prevent form from submitting normally

    const form = document.getElementById('deleteAccountForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('deleteaccount.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.text();

        if (result === 'Account deleted successfully.') {
            alert(result);
            window.location.href = 'successfuldelete.html'; // Redirect to main page
        } else {
            alert(result); // Display error message
        }
    } catch (error) {
        console.error('Error:', error);
        alert('There was an error processing your request.');
    }
}