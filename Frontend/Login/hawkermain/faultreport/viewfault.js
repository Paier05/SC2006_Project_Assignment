document.getElementById('loadReportsBtn').addEventListener('click', function() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_fault_reports.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('faultReports').innerHTML = xhr.responseText;
        } else {
            document.getElementById('faultReports').innerHTML = 'Error loading fault reports';
        }
    };

    xhr.onerror = function() {
        document.getElementById('faultReports').innerHTML = 'Request failed';
    };

    xhr.send();
});
