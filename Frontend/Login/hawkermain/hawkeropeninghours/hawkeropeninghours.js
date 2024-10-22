document.querySelectorAll('.day-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Check if the button is already selected
        if (this.classList.contains('selected')) {
            this.classList.remove('selected');
        } else {
            this.classList.add('selected');
        }
    });
});

document.getElementById('saveBtn').addEventListener('click', function() {
    // Collect all selected days
    const selectedDays = Array.from(document.querySelectorAll('.day-btn.selected')).map(btn => btn.dataset.day);
    const opening_time = document.getElementById('opening_time').value;
    const closing_time = document.getElementById('closing_time').value;

    if (selectedDays.length === 0) {
        alert('Please select at least one day.');
        return;
    }

    // Make an AJAX POST request to send the data to the PHP server
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'hawkeropeninghours.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('Opening hours saved successfully.');
        } else {
            alert('Error saving opening hours.');
        }
    };

    // Prepare the data string for the POST request
    const data = `days=${selectedDays.join(',')}&opening_time=${opening_time}&closing_time=${closing_time}`;
    xhr.send(data);
});
