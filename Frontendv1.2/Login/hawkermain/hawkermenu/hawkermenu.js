let menuItems = [
    {
        menuItemID: 1,
        itemName: 'Chicken Rice',
        itemDescription: 'Delicious steamed chicken with rice',
        price: 3.50,
        itemImage: 'chicken_rice.png'
    },
    {
        menuItemID: 2,
        itemName: 'Laksa',
        itemDescription: 'Spicy noodle soup',
        price: 4.00,
        itemImage: 'laksa.png'
    }
];

function loadMenuItems() {
    const tableBody = document.querySelector('#menu-table tbody');
    tableBody.innerHTML = ''; // Clear table

    menuItems.forEach((item, index) => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${index + 1}</td>
            <td><img src="uploads/${item.itemImage}" alt="${item.itemName}" style="width: 50px; height: 50px;"></td>
            <td><input type="text" value="${item.itemName}" class="item-name"></td>
            <td><input type="text" value="${item.price.toFixed(2)}" class="item-price"></td>
            <td><input type="text" value="${item.itemDescription}" class="item-description"></td>
            <td><span class="trash-icon" onclick="removeMenuItem(${index})">&#128465;</span></td>
        `;
        tableBody.appendChild(row);
    });
}

// Function to remove a menu item
function removeMenuItem(index) {
    menuItems.splice(index, 1); // Remove from array
    loadMenuItems(); // Reload table
}

// Function to add a new row for a new menu item
document.getElementById('add-new-item').addEventListener('click', () => {
    const newItem = {
        menuItemID: null, // Null for new item
        itemName: '',
        itemDescription: '',
        price: 0.00,
        itemImage: '' // Image will be uploaded
    };
    menuItems.push(newItem);
    loadMenuItems();
});

// Function to validate price input
document.addEventListener('input', (event) => {
    if (event.target.classList.contains('item-price')) {
        let value = event.target.value;

        // Ensure only numbers and one decimal point are allowed
        if (!/^\d*\.?\d{0,2}$/.test(value)) {
            event.target.value = value.slice(0, -1);
        }
    }
});

// Load the menu items when the page loads
window.onload = loadMenuItems;

// Save changes to the server
document.getElementById('save-changes').addEventListener('click', () => {
    fetch('hawkermenu.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(menuItems) // Send menuItems array
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Menu updated successfully!');
        }
    });
});