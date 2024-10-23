document.querySelectorAll('.day-btn').forEach(button => {
    button.addEventListener('click', function() {
        this.classList.toggle('selected');
    });
});

document.getElementById('saveBtn').addEventListener('click', function() {
    const selectedDays = Array.from(document.querySelectorAll('.day-btn.selected')).map(btn => btn.dataset.day);
    const startTime = document.getElementById('startTime').value;
    const endTime = document.getElementById('endTime').value;

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

    const data = `days=${selectedDays.join(',')}&startTime=${startTime}&endTime=${endTime}`;
    xhr.send(data);
});
