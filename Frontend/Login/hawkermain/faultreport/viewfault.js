document.getElementById('loadReportsBtn').addEventListener('click', function() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_fault_reports.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('fault_reports').innerHTML = xhr.responseText;
        } else {
            document.getElementById('fault_reports').innerHTML = 'Error loading fault reports';
        }
    };

    xhr.onerror = function() {
        document.getElementById('fault_reports').innerHTML = 'Request failed';
    };

    xhr.send();
});

// Function to delete a report by fault report content
function deleteReport(faultReport) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_fault_report.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200 && xhr.responseText === 'success') {
            // Find and remove the matching report item from the DOM
            document.querySelectorAll('.report-item').forEach(item => {
                if (item.textContent.includes(faultReport)) {
                    item.remove();
                    alert('Deleted');
                }
            });
        } else {
            alert('Failed to delete the report.');
        }
    };

    xhr.onerror = function() {
        alert('Request failed.');
    };

    xhr.send('fault_report=' + encodeURIComponent(faultReport));
}


// Get stall_name to display
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

