document.getElementById('loadReviewsBtn').addEventListener('click', function() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'hawkerreview.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            // Update the reviews section with the received HTML content
            document.getElementById('review').innerHTML = xhr.responseText;
        } else {
            // Handle error in case the request was successful but returned an error
            document.getElementById('review').innerHTML = 'Error loading reviews.';
        }
    };

    xhr.onerror = function() {
        // Handle request failure
        document.getElementById('review').innerHTML = 'Request failed.';
    };

    xhr.send();
});
