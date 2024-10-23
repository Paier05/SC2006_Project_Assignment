const editButton = document.getElementById('editButton');
const nameField = document.getElementById('name');
const descriptionField = document.getElementById('description');
const priceField = document.getElementById('price');
const foodImage = document.getElementById('foodImage');
const uploadIconWrapper = document.getElementById('uploadIconWrapper');
const imageUploadInput = document.getElementById('imageUpload');

const addItem = document.getElementById('addItem');
const menuItems = document.querySelectorAll('.menu-item');
let isEditing = false;

window.onload = () => {
    displayMenuDetails('Spaghetti', 'Tomato sauce with creamy spaghetti noodles', '5.50', 'spaghetti.jpg');
};

function displayMenuDetails(name, description, price, imageSrc) {
    nameField.value = name;
    descriptionField.value = description;
    priceField.value = price;
    foodImage.src = imageSrc;
    uploadIconWrapper.style.display = 'none';  
}


editButton.addEventListener('click', () => {
    if (isEditing) {
        nameField.disabled = true;
        descriptionField.disabled = true;
        priceField.disabled = true;
        editButton.textContent = 'Edit';
    } else {
        nameField.disabled = false;
        descriptionField.disabled = false;
        priceField.disabled = false;
        editButton.textContent = 'Save';
    }
    isEditing = !isEditing;
});

menuItems.forEach(item => {
    item.addEventListener('click', (event) => {
        if (!item.classList.contains('add-item')) {
            const name = item.getAttribute('data-name');
            const description = item.getAttribute('data-description');
            const price = item.getAttribute('data-price');
            const imageSrc = item.getAttribute('data-image');
            displayMenuDetails(name, description, price, imageSrc);

            // Remove 'selected' class from all items
            menuItems.forEach(i => i.classList.remove('selected'));

            // Add 'selected' class to the clicked item
            item.classList.add('selected');
        }
    });
});


addItem.addEventListener('click', () => {
    nameField.value = '';
    descriptionField.value = '';
    priceField.value = '';
    foodImage.src = '';  // Clear the image source
    foodImage.alt = '';  // Clear the alt text to avoid "Spaghetti" being shown
    uploadIconWrapper.style.display = 'flex';  // Show the upload icon
    nameField.disabled = false;
    descriptionField.disabled = false;
    priceField.disabled = false;
    editButton.textContent = 'Save';
    isEditing = true;

    // Remove 'selected' class from all items
    menuItems.forEach(item => item.classList.remove('selected'));
});


uploadIconWrapper.addEventListener('click', () => {
    imageUploadInput.click();  
});

imageUploadInput.addEventListener('change', (event) => {
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            foodImage.src = e.target.result;
            foodImage.alt = "Uploaded Image";

            // Hide the upload icon once an image is uploaded
            uploadIconWrapper.style.display = 'none';
        };

        reader.readAsDataURL(file);  
    }
});
