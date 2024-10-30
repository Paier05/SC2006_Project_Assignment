const urlParams = new URLSearchParams(window.location.search);
const stallId = urlParams.get('stall_id');
console.log(stallId);

function loadStallInfo() {
    fetch(`userfaultreport.php?stall_id=${stallId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
            } else {
                document.getElementById("stallName").innerText = data.stall_name;
                document.getElementById("openingHours").innerText = `Opening Hours: ${data.opening_hours}`;
            }
        })
        .catch(error => console.error("Error:", error));
}

// Handle fault report form submission
document.getElementById("faultForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const faultText = document.getElementById("faultText").value;

    fetch("userfaultreport.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ stall_id: stallId, fault_text: faultText })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Fault Report submitted");
            document.getElementById("faultText").value = "";
            loadStallInfo(); // Optionally refresh stall info
        } else {
            alert("Failed to submit fault report.");
            console.error("Error:", data.error);
        }
    })
    .catch(error => console.error("Error:", error));
});

loadStallInfo();
