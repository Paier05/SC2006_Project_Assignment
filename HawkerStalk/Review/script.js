// Show the leave review form when the "Leave a Review" button is clicked
document.getElementById('leave-review-btn').addEventListener('click', function() {
    document.getElementById('leave-review').style.display = 'block';
    document.getElementById('reviews').style.display = 'none';
});

// Client-side validation before submitting the form
document.getElementById('review-form').addEventListener('submit', function(event) {
    let reviewText = document.getElementById('review').value;
    if (!reviewText.trim()) {
        event.preventDefault();
        alert("Please enter a review before submitting.");
    }
});
