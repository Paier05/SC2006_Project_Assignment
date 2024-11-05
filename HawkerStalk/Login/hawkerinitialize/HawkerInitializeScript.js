// Function to handle price input validation
function validatePriceInput(input) {
    input.addEventListener('input', function() {
        // Remove non-numeric characters except for "."
        this.value = this.value.replace(/[^0-9.]/g, '');

        // Ensure only one decimal point
        if ((this.value.match(/\./g) || []).length > 1) {
            this.value = this.value.slice(0, -1);
        }

        // If first character is ".", prepend "0"
        if (this.value.charAt(0) === '.') {
            this.value = '0' + this.value;
        }

        // Restrict input to two decimal places
        const decimalIndex = this.value.indexOf('.');
        if (decimalIndex !== -1 && this.value.length - decimalIndex > 3) {
            this.value = this.value.slice(0, decimalIndex + 3);
        }
    });
}

// Apply validation to all existing price inputs on page load
document.querySelectorAll('.price-input').forEach(validatePriceInput);

// Function to add a new menu item row
function addMenuItem() {
    const container = document.getElementById('menu-items-container');
    const newRow = document.createElement('div');
    newRow.classList.add('menu-item-row');

    // HTML for the new menu item row
    newRow.innerHTML = `
        <input type="text" name="item_name[]" placeholder="Food Name" required>
        <input type="number" name="item_price[]" class="price-input" placeholder="Price (SGD)" required>
        <input type="file" name="item_image[]" accept="image/*" required>
        <textarea name="item_description[]" placeholder="Description" rows="2"></textarea>
    `;

    // Append the new row to the container
    container.appendChild(newRow);

    // Validate the new price input
    validatePriceInput(newRow.querySelector('.price-input'));
}

// Add event listener for the "Add Another Item" button
document.getElementById('add-menu-item-btn').addEventListener('click', addMenuItem);
