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

                /*
                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                    fetchStalls(location.id, location.name);
                });
                

                var infowindow = new google.maps.InfoWindow({
                    content: `<h3>${location.name}</h3>`
                });
                */ 

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
            });
        })
        .catch(error => console.error('Error:', error)); 
}

// Fetch stalls for a hawker center and display below the map
function fetchStalls(hawkerCenterId, hawkerCenterName) {
    fetch(`fetch_stalls.php?id=${hawkerCenterId}`)
        .then(response => response.json())
        .then(stalls => {
            var stallList = `<h2>Stalls at ${hawkerCenterName}</h2>`;
            stalls.forEach(stall => {
                stallList += `
                    <div class="stall" onclick="fetchMenu(${stall.StallID})">
                        <h3>${stall.stall_name}</h3>
                        <p>Hours: ${stall.opening_hours}</p>
                    </div>
                `;
            });

            // Inject the stall list below the map
            document.getElementById('stallList').innerHTML = stallList;

            // Clear previous menu (if any)
            // document.getElementById('menuItems').innerHTML = '';
        })
        .catch(error => console.error('Error:', error));
}

/*
// Fetch menu for a specific stall and display below the stall list
function fetchMenu(stallId) {
    fetch(`fetch_menu.php?stall_id=${stallId}`)
        .then(response => response.json())
        .then(menuItems => {
            var menuContent = '<h2>Menu</h2>';
            menuItems.forEach(item => {
                menuContent += `
                    <div class="menuItem">
                        <h3>${item.ItemName}</h3>
                        <img src="${item.ItemImage}" alt="${item.ItemName}" style="width:100px;height:100px;">
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
*/

// Load the map
window.onload = initMap;