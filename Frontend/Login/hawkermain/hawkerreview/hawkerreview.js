document.getElementById('loadReviewsBtn').addEventListener('click', function() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'hawkerreview.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('reviews').innerHTML = xhr.responseText;
        } else {
            document.getElementById('reviews').innerHTML = 'Error loading reviews.';
        }
    };

    xhr.onerror = function() {
        document.getElementById('reviews').innerHTML = 'Request failed.';
    };

    xhr.send();
});
