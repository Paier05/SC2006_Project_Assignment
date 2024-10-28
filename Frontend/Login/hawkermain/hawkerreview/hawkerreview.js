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

//get stall_name to display
document.addEventListener('DOMContentLoaded', function() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', '../getStallName.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.stall_name) {
                document.getElementById('stall_name').innerText = response.stall_name;
            } else {
                document.getElementById('stall_name').innerText = 'Stall name not found';
            }
        } else {
            document.getElementById('stall_name').innerText = 'Error loading stall name';
        }
    };

    xhr.onerror = function() {
        document.getElementById('stall_name').innerText = 'Request failed';
    };

    xhr.send();
});
