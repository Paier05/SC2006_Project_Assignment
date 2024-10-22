// Fetch the previous opening hours when the page loads
window.onload = function() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'hawkeropeninghours.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);

            if (!data.error) {
                // Populate the existing opening hours
                document.getElementById('original_opening_hours').innerText = data.opening_hours;

                // Populate the existing opening days by selecting the corresponding day buttons
                const openingDaysArray = data.opening_days.split(''); // Split the '1100110' format into an array

                openingDaysArray.forEach((dayStatus, index) => {
                    if (dayStatus === '1') {
                        document.querySelector(`.day-btn[data-day="${index}"]`).classList.add('selected');
                    }
                });
            } else {
                alert('Error loading opening hours.');
            }
        } else {
            alert('Error fetching data.');
        }
    };

    xhr.send();
};

// Handle day button selection
document.querySelectorAll('.day-btn').forEach(button => {
    button.addEventListener('click', function() {
        if (this.classList.contains('selected')) {
            this.classList.remove('selected');
        } else {
            this.classList.add('selected');
        }
    });
});

// Handle save button click
document.getElementById('saveBtn').addEventListener('click', function() {
    let openingDaysArray = ['0', '0', '0', '0', '0', '0', '0']; // Default: all days closed

    document.querySelectorAll('.day-btn.selected').forEach(btn => {
        const dayIndex = parseInt(btn.dataset.day);
        openingDaysArray[dayIndex] = '1';
    });

    const opening_days = openingDaysArray.join('');
    const opening_hours = document.getElementById('opening_hours').value;

    if (opening_days === '0000000') {
        alert('Please select at least one open day.');
        return;
    }

    // Make an AJAX POST request
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

    const data = `opening_days=${opening_days}&opening_hours=${opening_hours}`;
    xhr.send(data);
});
