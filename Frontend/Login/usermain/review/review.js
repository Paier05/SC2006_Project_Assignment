const urlParams = new URLSearchParams(window.location.search);
const stallId = urlParams.get('stall_id');
const stars = document.querySelectorAll(".stars i"); // Select all elements with the "i" tag and store them in a NodeList called "stars"
let selectedRating = 0; // Used to store the selected rating
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

// Loop through the "stars" NodeList
stars.forEach((star, index1) => {
    // Add an event listener that runs a function when "click" event is triggered
    star.addEventListener("click", () => {
        selectedRating = index1 + 1;
        stars.forEach((star, index2) => {
            // Use active status to update the status of the stars
            index1 >= index2 ? star.classList.add("active") : star.classList.remove("active"); 
        });
    });
});

function loadStallReview() {
    fetch(`fetch_review.php?stall_id=${stallId}`)
        .then(response => response.json())
        .then(reviews => {
            console.log(reviews);
            let reviewHTML = ``;

            if (reviews.length === 0) {
                document.getElementById("reviewList").innerHTML = `<p>No reviews for this stall</p>`;
                return;
            }

            reviews.forEach(review => {
                reviewHTML += `
                    <div class="review-card">
                        <p>${review.user_email}</p>
                        <p>${'⭐'.repeat(review.rating)}</p>
                        <p>"${review.review}"</p>
                    </div>
                `;
            });
            document.getElementById("reviewList").innerHTML = reviewHTML; // Ensure this matches the HTML ID
        })
        .catch(error => console.error("Error:", error));
}

// Handle review form submission
document.getElementById("reviewForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const reviewText = document.getElementById("reviewText").value;

    fetch("submit_review.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ stall_id: stallId, review_text: reviewText, rating: selectedRating })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            stars.forEach((star) => {
                // Use active status to update the status of the stars
                star.classList.remove("active"); 
            });

            alert("Review submitted successfully!");
            document.getElementById("reviewText").value = "";
            loadStallReview(); // Reload reviews after submitting
        } else {
            alert("Failed to submit review.");
        }
    })
    .catch(error=>console.error("Error:",error));
});

// Load stall info and reviews when the page loads
loadStallInfo();
loadStallReview();
