// Initialize and add the map
function initMap() {
    // Center the map at a default location (e.g., Singapore)
    var mapCenter = { lat: 1.3521, lng: 103.8198 };

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: mapCenter
    });

    // Fetch locations from the PHP script
    fetch('get_hawker_addresses.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(location => {
                // Place a marker for each location
                var marker = new google.maps.Marker({
                    position: { lat: parseFloat(location.lat), lng: parseFloat(location.lng) },
                    map: map,
                    title: location.name
                });

                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                    fetchStalls(location.id, location.name);
                });                

                var infowindow = new google.maps.InfoWindow({
                    content: `<h3>${location.name}</h3>`
                });

                /*
                marker.addListener('click', function() {
                    // Show a loading message while fetching stalls
                    var infowindow = new google.maps.InfoWindow({
                        content: `<h3>${location.name}</h3><p>Loading stalls...</p>`
                    });
                    infowindow.open(map, marker);

                    // Fetch stalls for this hawker center
                    fetch(`fetch_stalls.php?id=${location.id}`)
                        .then(response => response.json())
                        .then(stalls => {
                            var stallsContent = '<ul>';
                            if (stalls.length > 0) {
                                stalls.forEach(stall => {
                                    stallsContent += `<li>${stall}</li>`;
                                });
                            } else {
                                stallsContent += '<li>No stalls available</li>';
                            }
                            stallsContent += '</ul>';

                            // Update the info window with the stalls list
                            infowindow.setContent(`
                                <h3>${location.name}</h3>
                                <p>Stalls:</p>
                                ${stallsContent}
                            `);
                        })
                        .catch(error => {
                            // Handle errors (e.g., no stalls or server error)
                            infowindow.setContent(`
                                <h3>${location.name}</h3>
                                <p>Failed to load stalls.</p>
                            `);
                        });                            
                });
                */
            });
        })
        .catch(error => console.error('Error:', error)); 
}

function getOpeningDaysMessage(opening_days) {
    // Convert the string to an array of 1s and 0s
    const daysOpen = opening_days.split('').map(Number);
    var openResult = "";

    if (daysOpen[0]==1) {
        openResult += "Mon ";
    }
    if (daysOpen[1]==1) {
        openResult += "Tue ";
    }
    if (daysOpen[2]==1) {
        openResult += "Wed ";
    }
    if (daysOpen[3]==1) {
        openResult += "Thu ";
    }
    if (daysOpen[4]==1) {
        openResult += "Fri ";
    }
    if (daysOpen[5]==1) {
        openResult += "Sat ";
    }
    if (daysOpen[6]==1) {
        openResult += "Sun ";
    }

    return openResult;
}

// this function is edited
function fetchStalls(hawkerCenterId, hawkerCenterName) {
    fetch(`fetch_stalls.php?id=${hawkerCenterId}`)
        .then(response => response.json())
        .then(stalls => {
            console.log(stalls);

            // Set the heading
            document.getElementById('stallHeading').innerText = `Stalls at ${hawkerCenterName}`;

            var stallList = '';

            if (stalls.length === 0) {
                document.getElementById('stallList').innerHTML = `<p>No stalls found for ${hawkerCenterName}</p>`;
                // Clear previous menu (if any)
                document.getElementById('menuItems').innerHTML = '';
                return;
            }

            stalls.forEach(stall => {
                console.log(stall);
                const openingDaysMessage = getOpeningDaysMessage(stall.opening_days);
                stallList += `
                    <div class="stall" onclick="fetchMenu(${stall.id})">
                        <h3>${stall.stall_name}</h3>
                        <p>Opening hours: ${stall.opening_hours}</p>
                        <p>Open on: ${openingDaysMessage}</p>
                        <p>Rating: ${'⭐'.repeat(stall.sum_rating/stall.total_number_of_rating)}</p>
                        <button onclick="event.stopPropagation(); redirectToReviewPage(${stall.id})">Review</button>
                        <button onclick="event.stopPropagation(); redirectToFaultReportPage(${stall.id})">Fault Report</button>
                    </div>
                `;
            });

            // Inject the stall list below the heading
            document.getElementById('stallList').innerHTML = stallList;

            // Clear previous menu (if any)
            document.getElementById('menuItems').innerHTML = '';
        })
        .catch(error => console.error('Error:', error));
}
// end of function editing

// Fetch menu for a specific stall and display below the stall list
function fetchMenu(stallId) {
    fetch(`fetch_menu.php?stall_id=${stallId}`)
        .then(response => response.json())
        .then(menuItems => {
            // console.log(menuItems);

            var menuContent = '<h2>Menu</h2>';
            menuItems.forEach(item => {
                menuContent += `
                    <div class="menuItem">
                        <h3>${item.ItemName}</h3>
                        <img src="../hawkerinitialize/uploads/${item.ItemImage}" alt="${item.ItemName}" style="width:100px;height:100px;">
                        <p>${item.ItemDescription}</p>
                        <p>Price: $${item.Price}</p>
                    </div>
                `;
            });

            // Inject the menu items below the stall list
            document.getElementById('menuItems').innerHTML = menuContent;
        })
        .catch(error => console.error('Error:', error));
}

/*// Function to display the "Menu" heading
function displayMenuHeading(stallId) {
    fetch(`fetch_menu.php?stall_id=${stallId}`)
        .then(response => response.json())
        .then(menuHeadings => {
            var menuContent = '<h2>Menu</h2>';
        })
        .catch(error => console.error('Error:', error));
}

// Function to fetch and display menu items
function fetchMenu(stallId) {
    fetch(`fetch_menu.php?stall_id=${stallId}`)
        .then(response => response.json())
        .then(menuItems => {
            const menuContainer = document.getElementById("menuItems");
            menuContainer.innerHTML = ''; // Clear previous items

            // Generate menu item HTML
            menuItems.forEach(item => {
                const menuItemDiv = document.createElement("div");
                menuItemDiv.className = "menuItem"; // Add class for styling
                menuItemDiv.innerHTML = `
                    <h3>${item.ItemName}</h3>
                    <img src="../hawkerinitialize/uploads/${item.ItemImage}" alt="${item.ItemName}" style="width:100px;height:100px;">
                    <p>${item.ItemDescription}</p>
                    <p>Price: $${item.Price}</p>
                `;
                menuContainer.appendChild(menuItemDiv); // Append each menu item
            });
        })
        .catch(error => console.error('Error:', error));
}*/


// Redirect function
function redirectToReviewPage(stallId) {
    window.location.href = `./review/review.html?stall_id=${stallId}`;
}

//Redirect function (Fault Report Page)
function redirectToFaultReportPage(stallId){
    window.location.href = `./userfaultreport/userfaultreport.html?stall_id=${stallId}`;
}

// Load the map
window.onload = initMap;
