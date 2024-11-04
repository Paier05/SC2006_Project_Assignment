const editButton = document.getElementById('editButton');
const nameField = document.getElementById('name');
const descriptionField = document.getElementById('description');
const priceField = document.getElementById('price');
const foodImage = document.getElementById('foodImage');
const uploadIconWrapper = document.getElementById('uploadIconWrapper');
const imageUploadInput = document.getElementById('imageUpload');
const menuSidebar = document.querySelector('.sidebar');
const addItemButton = document.getElementById('addItem');

let isEditing = false;
let selectedMenuItem = null;

// Function to display selected menu item details
function displayMenuDetails(item) {
    nameField.value = item.ItemName;
    descriptionField.value = item.ItemDescription;
    priceField.value = item.Price;

    if (item.ItemImage) {
        foodImage.src = `http://localhost/Frontend/Login/hawkerinitialize/uploads/${item.ItemImage}`;
        foodImage.style.display = 'block';
    } else {
        foodImage.style.display = 'none'; // Hide image for new items without an uploaded image
    }

    uploadIconWrapper.style.display = isEditing ? 'block' : 'none';
}

// Toggle edit mode
function toggleEditMode(enable) {
    isEditing = enable;
    nameField.disabled = !enable;
    descriptionField.disabled = !enable;
    priceField.disabled = !enable;
    uploadIconWrapper.style.display = enable ? 'block' : 'none';
    editButton.textContent = enable ? 'Save' : 'Edit';

    // Disable/enable the add item button
    if (enable) {
        addItemButton.classList.add('disabled'); // Add disabled class
    } else {
        addItemButton.classList.remove('disabled'); // Remove disabled class
    }

    // Disable/enable other menu items
    const menuItems = document.querySelectorAll('.menu-item:not(.add-item)');
    menuItems.forEach(item => {
        if (enable) {
            item.classList.add('disabled'); // Add class to visually indicate disabled
            item.style.pointerEvents = 'none'; // Prevent click events
        } else {
            item.classList.remove('disabled'); // Remove class when not editing
            item.style.pointerEvents = 'auto'; // Enable click events
        }
    });
}

// Edit button functionality
editButton.addEventListener('click', () => {
    if (isEditing) {
        // Save the current item (implement your save logic here)
        if (selectedMenuItem) {
            const nameInput = nameField.value.trim();
            const itemName = nameInput || "New"; // Default to "New" if input is empty
            
            // Update the button text and data attributes for the selected item
            selectedMenuItem.textContent = itemName;
            selectedMenuItem.setAttribute('data-name', itemName);
            selectedMenuItem.setAttribute('data-description', descriptionField.value);
            selectedMenuItem.setAttribute('data-price', priceField.value);
        }

        const menuData = gatherMenuData();
        // Send data to PHP via AJAX
        fetch('updatemenuitems.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ menuItems: menuData })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Menu updated successfully!");
                location.reload();
            } else {
                alert("Failed to update menu.");
            }
        })
        .catch(error => console.error('Error:', error));
    }
    toggleEditMode(!isEditing); // Toggle editing mode on button click
});

// Event listener for clicking menu items
menuSidebar.addEventListener('click', (event) => {
    const menuItem = event.target.closest('.menu-item');
    if (menuItem && !menuItem.classList.contains('add-item') && !menuItem.classList.contains('disabled')) {
        document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('selected'));

        selectedMenuItem = menuItem;
        selectedMenuItem.classList.add('selected');

        const item = {
            ItemName: menuItem.getAttribute('data-name'),
            ItemDescription: menuItem.getAttribute('data-description'),
            Price: menuItem.getAttribute('data-price'),
            ItemImage: menuItem.getAttribute('data-image').split('/').pop(),
        };

        displayMenuDetails(item);
    }
});

// Function to delete the menu item from the UI and hide details
function deleteMenuItem(menuItem) {
    menuItem.remove(); // Remove the menu item from the display
    if (selectedMenuItem === menuItem) {
        // Hide details if the deleted item was the selected one
        nameField.value = '';
        descriptionField.value = '';
        priceField.value = '';
        foodImage.src = ''; // Clear image
        foodImage.style.display = 'none'; // Hide the image
        toggleEditMode(false); // Exit edit mode if an item is deleted
    }
    document.querySelector('.details-section').style.display = 'none'; // Hide the details section
}

// Add new menu item button functionality
addItemButton.addEventListener('click', () => {
    if (isEditing) {
        return; // Exit the function if in editing mode
    }

    const newMenuItemButton = document.createElement('div'); // Create new item button
    newMenuItemButton.classList.add('menu-item');
    newMenuItemButton.textContent = "New"; // Default text for new items

    // Create a delete button for the new item
    const deleteButton = document.createElement('span');
    deleteButton.classList.add('cross'); // Add class for styling
    deleteButton.textContent = '✖'; // Use a cross icon
    deleteButton.style.color = 'red'; // Color for the delete button
    deleteButton.style.cursor = 'pointer'; // Change cursor to pointer

    newMenuItemButton.appendChild(deleteButton); // Append the delete button

    newMenuItemButton.setAttribute('data-name', "New");
    newMenuItemButton.setAttribute('data-description', "");
    newMenuItemButton.setAttribute('data-price', "");
    newMenuItemButton.setAttribute('data-image', ""); // No image initially

    document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('selected'));

    // Append new menu item to the sidebar
    menuSidebar.insertBefore(newMenuItemButton, addItemButton);
    newMenuItemButton.classList.add('selected');

    // Display details for the new menu item
    displayMenuDetails({
        ItemName: "",
        ItemDescription: "",
        Price: "",
        ItemImage: ""
    });

    // Immediately trigger click on the new menu item to display its details
    newMenuItemButton.click();

    // Enable edit mode immediately
    toggleEditMode(true);

    // Add event listener for the delete button
    deleteButton.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling
        deleteMenuItem(newMenuItemButton); // Call delete function
    });
});

// Update only the selected item's button text as user types
nameField.addEventListener('input', () => {
    if (selectedMenuItem) {
        const nameInput = nameField.value.trim();
        const itemName = nameInput || "New"; // Default to "New" if input is empty
        
        // Update the button text and data attributes for the selected item
        selectedMenuItem.textContent = itemName;
        selectedMenuItem.setAttribute('data-name', itemName);
    }
});

// Delete menu item functionality for existing items
menuSidebar.addEventListener('click', (event) => {
    if (event.target.classList.contains('cross')) {
        const menuItem = event.target.parentElement; // Get the parent menu item
        deleteMenuItem(menuItem); // Call delete function
    }
});

// Image upload functionality
uploadIconWrapper.addEventListener('click', () => {
    imageUploadInput.click();
});

imageUploadInput.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            foodImage.src = e.target.result;
            foodImage.style.display = 'block';
            if (selectedMenuItem) {
                selectedMenuItem.setAttribute('data-image', file.name);
            }
        };
        reader.readAsDataURL(file);
    }
});

function gatherMenuData() {
    const menuData = [];
    document.querySelectorAll('.menu-item').forEach(item => {
        if (!item.classList.contains('add-item')) {
            menuData.push({
                MenuItemID: item.getAttribute('data-id'),
                ItemName: item.getAttribute('data-name'),
                ItemDescription: item.getAttribute('data-description'),
                Price: item.getAttribute('data-price'),
                ItemImage: item.getAttribute('data-image').split('/').pop() // Only the file name
            });
        }
    });
    return menuData;
}

// Initial display
if (selectedMenuItem) {
    selectedMenuItem.click(); // Simulate a click on the first menu item to display details
}