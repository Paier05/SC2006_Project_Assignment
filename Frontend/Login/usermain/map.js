// Initialize and add the map
function initMap() {
    // Center the map at a default location (Singapore)
    var mapCenter = { lat: 1.3521, lng: 103.8198 };

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: mapCenter
    });

    const customIcon = {
        path: google.maps.SymbolPath.CIRCLE,  // Use a predefined shape, or specify a custom SVG path
        fillColor: "#03adfc",                 // Marker color (hex or RGB)
        fillOpacity: 1,
        strokeColor: "#ADD8E6",               // Outline color
        strokeWeight: 2,
        scale: 8,                            // Size of the marker
    };

    // Check if Geolocation API is supported
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                let   curInfoWindow = new google.maps.InfoWindow();
                // Place a marker on the user's location
                let curMarker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    icon: customIcon,
                    title: "You are here!",
                });
                curInfoWindow.setPosition(pos);
                curInfoWindow.setContent("You are here.");
                curInfoWindow.open(map, curMarker);
                map.setCenter(pos);
                // Center map on user's location
                map.setZoom(12);
            },
            () => {
                // Handle error (e.g., user denied access)
                handleLocationError(true, map.getCenter());
            }
        );
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, map.getCenter());
    }

    
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
                    map.setCenter({ lat: parseFloat(location.lat), lng: parseFloat(location.lng) });
                    map.setZoom(15);
                    infowindow.open(map, marker);
                    fetchStalls(location.id, location.name);
                });                  

                var infowindow = new google.maps.InfoWindow({
                    content: `<h3>${location.name}</h3><p>${location.location}</p>`
                });

                
                // Create list item and add click event
                const listItem = document.createElement("div");
                listItem.className = "hawker-item";
                listItem.innerHTML = `<strong>${location.name}</strong><br>${location.location}`;
                listItem.style.cursor = "pointer";
                listItem.style.margin = "10px 0";
                listItem.addEventListener("click", () => {
                    map.setCenter({ lat: parseFloat(location.lat), lng: parseFloat(location.lng) });
                    map.setZoom(15);
                    infowindow.open(map, marker);
                    fetchStalls(location.id, location.name);
                });
                document.getElementById("hawker-list").appendChild(listItem);
            });
        })
        .catch(error => console.error('Error:', error)); 
}


function handleLocationError(browserHasGeolocation, pos) {
    const infoWindow = new google.maps.InfoWindow({
        content: browserHasGeolocation
            ? "Error: The Geolocation service failed."
            : "Error: Your browser doesn't support geolocation.",
        position: pos,
    });
    infoWindow.open(map);
}


// Real-time filter function for the local list search
document.getElementById("search-input").addEventListener("input", function() {
    const filterText = this.value.toLowerCase();
    const hawkerItems = document.querySelectorAll(".hawker-item");

    hawkerItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(filterText) ? "" : "none"; // Toggle display based on match
    });
});


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
                document.getElementById("menuContainer").style.display = 'none';
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
            document.getElementById("menuContainer").style.display = 'none';
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
            const menuContainer = document.getElementById("menuContainer");
            const menuItemsContainer = document.getElementById("menuItems");
            
            // Clear previous menu items
            menuItemsContainer.innerHTML = '';

            if (menuItems.length > 0) {
                // Show the menu container
                menuContainer.style.display = 'block';

                // Generate menu item HTML and append it to menuItems container
                menuItems.forEach(item => {
                    const menuItemDiv = document.createElement("div");
                    menuItemDiv.className = "menuItem";
                    menuItemDiv.innerHTML = `
                        <h3>${item.ItemName}</h3>
                        <img src="../hawkerinitialize/uploads/${item.ItemImage}" alt="${item.ItemName}" style="width:100px;height:100px;">
                        <p>${item.ItemDescription}</p>
                        <p>Price: $${item.Price}</p>
                    `;
                    menuItemsContainer.appendChild(menuItemDiv);
                });
            } else {
                // Hide the menu container if no menu items are available
                menuContainer.style.display = 'none';
            }
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
