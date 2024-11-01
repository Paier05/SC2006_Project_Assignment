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
}

// Event listener for clicking menu items
menuSidebar.addEventListener('click', (event) => {
    const menuItem = event.target.closest('.menu-item');
    if (menuItem && !menuItem.classList.contains('add-item')) {
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

    // Get the current index of menu items
    const itemIndex = document.querySelectorAll('.menu-item').length; // Total number of items

    // Create inputs for the new menu item's details
    newMenuItemButton.innerHTML += `
        <input type="text" name="menu_items[${itemIndex}][item_name]" placeholder="Enter Name" required />
        <textarea name="menu_items[${itemIndex}][item_description]" placeholder="Enter Description"></textarea>
        <input type="number" name="menu_items[${itemIndex}][item_price]" placeholder="Enter Price" required />
        <input type="file" name="image[]" accept="image/*" />
    `;

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

    // Enable edit mode immediately
    toggleEditMode(true);

    // Add event listener for the delete button
    deleteButton.addEventListener('click', function(event) {
        event.stopPropagation(); // Prevent event bubbling
        deleteMenuItem(newMenuItemButton); // Call delete function
    });

    // Programmatically trigger a click to show the new item details
    newMenuItemButton.click();
});

// Update only the selected item's button text as user types
nameField.addEventListener('input', () => {
    if (selectedMenuItem) {
        const nameInput = nameField.value.trim();
        const itemName = nameInput || "New"; // Default to "New" if input is empty
        
        // Update the button text
        selectedMenuItem.textContent = itemName;

        // Recreate the delete button to ensure it remains on the right
        const deleteButton = document.createElement('span');
        deleteButton.classList.add('cross'); // Add class for styling
        deleteButton.textContent = '✖'; // Use a cross icon
        deleteButton.style.color = 'red'; // Color for the delete button
        deleteButton.style.cursor = 'pointer'; // Change cursor to pointer

        // Append the delete button to the menu item and add delete functionality
        selectedMenuItem.appendChild(deleteButton);

        // Add event listener for the delete button
        deleteButton.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent event bubbling
            deleteMenuItem(selectedMenuItem); // Call delete function
        });

        // Update data attributes for the selected item
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

// Save menu functionality
function saveMenu() {
    const itemName = nameField.value; // Get the current item's name
    const itemDescription = descriptionField.value; // Get the current item's description
    const itemPrice = priceField.value; // Get the current item's price

    // Create a menu item object
    const menuItem = {
        item_name: itemName,
        item_description: itemDescription,
        item_price: itemPrice,
    };

    // Create a FormData object
    const formData = new FormData();

    // Append the menu item as a JSON string to the FormData object
    formData.append('menu_item', JSON.stringify(menuItem)); // Use 'menu_item' instead of 'menu_items'

    // Handle image upload for the item
    const imageFiles = imageUpload.files; // Get the selected file
    if (imageFiles.length > 0) {
        formData.append('image', imageFiles[0]); // Append the first selected file
    }

    // Send the form data using AJAX
    fetch(window.location.href, {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text()) // Get the response as text first
    .then(text => {
        console.log('Response:', text); // Log the response for debugging
        const data = JSON.parse(text); // Parse it as JSON
        return data;
    })
    .then(data => {
        if (data.status === 'success') {
            alert('Menu item saved successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the menu item.');
    });
}
    /*const menuItems = []; // Initialize an array to hold menu items
    const itemId = editButton.getAttribute('data-id');

    // Assuming these fields exist for the current item being saved
    const itemName = nameField.value;
    const itemDescription = descriptionField.value;
    const itemPrice = priceField.value;

    // Create a menu item object and push it into the array
    menuItems.push({
        item_name: itemName,
        item_description: itemDescription,
        item_price: itemPrice,
    });

    // Create a FormData object
    const formData = new FormData();

    // Append the array of menu items as a JSON string to the FormData object
    formData.append('menu_items', JSON.stringify(menuItems)); // Convert to JSON string

    // Handle image upload for each item
    const imageFiles = imageUpload.files; // Get all selected files
    for (let i = 0; i < imageFiles.length; i++) {
        formData.append('image[]', imageFiles[i]); // Append each file as an array
    }

    // Send the form data using AJAX
    fetch(window.location.href, {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text()) // Get the response as text first
    .then(text => {
        console.log('Response:', text); // Log the response for debugging
        const data = JSON.parse(text); // Parse it as JSON
        return data;
    })
    .then(data => {
        if (data.status === 'success') {
            alert('Menu item saved successfully!');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the menu item.');
    });
}*/

// Event listener for the edit button to include save functionality
editButton.addEventListener('click', () => {
    if (isEditing) {
        saveMenu(); // Call save menu function on save
    } else {
        // Existing edit functionality
        if (selectedMenuItem) {
            const nameInput = nameField.value.trim();
            const itemName = nameInput || "New"; // Default to "New" if input is empty
            
            // Update the button text and data attributes for the selected item
            selectedMenuItem.textContent = itemName;
            selectedMenuItem.setAttribute('data-name', itemName);
            selectedMenuItem.setAttribute('data-description', descriptionField.value);
            selectedMenuItem.setAttribute('data-price', priceField.value);
        }
    }
    toggleEditMode(!isEditing); // Toggle editing mode on button click
});

// Initial display
if (selectedMenuItem) {
    selectedMenuItem.click(); // Simulate a click on the first menu item to display details
}
