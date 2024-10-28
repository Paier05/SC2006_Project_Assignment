const urlParams = new URLSearchParams(window.location.search);
const stallId = urlParams.get('stall_id');
console.log(stallId);

// Fetch stall details and existing reviews
function loadStallInfo() {
    fetch(`fetch_review_stall.php?stall_id=${stallId}`)
        .then(response => response.json())
        .then(data => {
            // Set stall information
            console.log(data);

            document.getElementById("stallName").innerText = data.stall_name;
            document.getElementById("openingHours").innerText = `Opening Hours: ${data.opening_hours}`;
        })
        .catch(error => console.error("Error:", error));
}

function loadStallReview() {
    fetch(`fetch_review.php?stall_id=${stallId}`)
        .then(response => response.json())
        .then(reviews => {
            // Set stall information
            console.log(reviews);
            var reviewHTML = ``;

            if (reviews.length === 0) {
                document.getElementById("reviewList").innerHTML = `<p>No reviews for this stall</p>`;
                return;
            }

            reviews.forEach(review => {
                console.log(review);
                reviewHTML += `
                    <div class="review-card">
                        <p>${review.user_email}</p>
                        <p>${'⭐'.repeat(review.rating)}</p>
                        <p>"${review.review}"</p>
                    </div>
                `;
            });
            document.getElementById("reviewList").innerHTML = reviewHTML;
        })
        .catch(error => console.error("Error:", error));
}

// Handle review form submission
        document.getElementById("reviewForm").addEventListener("submit", function(event) {
            event.preventDefault();
            const reviewText = document.getElementById("reviewText").value;
            const rating = document.querySelector('input[name="rating"]:checked').value;

            fetch("submit_review.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ stall_id: stallId, review_text: reviewText, rating: rating })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    alert("Review submitted successfully!");
                    document.getElementById("reviewText").value = "";
                    document.querySelector('input[name="rating"]:checked').checked = false;
                    loadStallInfo(); // Reload reviews after submitting
                } else {
                    alert("Failed to submit review.");
                }
            })
            .catch(error => console.error("Error:", error));
        });

// Load stall info and reviews when the page loads
loadStallInfo();
loadStallReview();