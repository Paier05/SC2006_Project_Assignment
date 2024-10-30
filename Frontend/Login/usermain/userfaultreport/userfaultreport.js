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

// Handle fault report form submission
        document.getElementById("faultForm").addEventListener("submit", function(event) {
            event.preventDefault();
            const reviewText = document.getElementById("faultText").value;

            fetch("userfaultreport.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ stall_id: stallId, fault_text: faultText, rating: rating })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    alert("Fault Report submitted successfully!");
                    document.getElementById("faultText").value = "";
                    loadStallInfo(); // Reload reviews after submitting
                } else {
                    alert("Failed to submit fault report.");
                }
            })
            .catch(error => console.error("Error:", error));
        });

// Load stall info and reviews when the page loads
loadStallInfo();