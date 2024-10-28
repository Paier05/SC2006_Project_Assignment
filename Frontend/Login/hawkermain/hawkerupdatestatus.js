document.getElementById('openShopBtn').addEventListener('click', function() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'hawkerupdatestatus.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText); // Parse JSON response
            if (response.error) {
                alert('Error updating status: ' + response.error); // Display error
            } else {
                alert(response.success); // Show success message
            }
        } else {
            alert('Error updating status.'); // Handle other HTTP errors
        }
    };

    xhr.onerror = function() {
        alert('Request failed.'); // Handle request failure
    };

    const data = `status=open`; // Specify the status to set
    xhr.send(data);
});

document.getElementById('closeShopBtn').addEventListener('click', function() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'hawkerupdatestatus.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText); // Parse JSON response
            if (response.error) {
                alert('Error updating status: ' + response.error); // Display error
            } else {
                alert(response.success); // Show success message
            }
        } else {
            alert('Error updating status.'); // Handle other HTTP errors
        }
    };

    xhr.onerror = function() {
        alert('Request failed.'); // Handle request failure
    };

    const data = `status=close`; // Specify the status to set
    xhr.send(data);
});

//get stall_name to display
document.addEventListener('DOMContentLoaded', function() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'getStallName.php', true);

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
