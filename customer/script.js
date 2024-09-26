document.addEventListener("DOMContentLoaded", function() {
    showDetails('shop'); // Call showDetails with 'shop' to set default view
});

function showDetails(type) {
    const detailsDiv = document.getElementById('details');
    const shopBtn = document.getElementById('shopBtn');
    const serviceBtn = document.getElementById('serviceBtn');
    const cardContainer = document.createElement('div');
    cardContainer.classList.add('card-container');

    // Clear previous content
    detailsDiv.innerHTML = '';
    detailsDiv.style.display = 'block';

    // Remove active class from both buttons
    shopBtn.classList.remove('active');
    serviceBtn.classList.remove('active');

    if (type === 'shop') {
        // Shop side logic remains unchanged
        detailsDiv.innerHTML = `<h3>Shop Details</h3>`;
        shopBtn.classList.add('active'); 
        const shopItems = [
            { name: "Shop Item 1", description: "Description for Shop Item 1" },
            { name: "Shop Item 2", description: "Description for Shop Item 2" },
            { name: "Shop Item 3", description: "Description for Shop Item 3" },
            { name: "Shop Item 4", description: "Description for Shop Item 4" },
        ];
        shopItems.forEach(item => {
            const card = createCard(item.name, item.description);
            cardContainer.appendChild(card);
        });
    } else if (type === 'service') {
        // Fetch the service data from the backend
        detailsDiv.innerHTML = `<h3>Service List</h3>`;
        serviceBtn.classList.add('active');

        fetch('fetch_service_list.php')
            .then(response => response.json())
            .then(serviceItems => {
                serviceItems.forEach(item => {
                    const card = createServiceCard(item);
                    cardContainer.appendChild(card);
                });
            })
            .catch(error => console.error('Error fetching service list:', error));
    }

    detailsDiv.appendChild(cardContainer);
}

function createServiceCard(serviceItem) {
    const { service_name, service_price, shop_id, service_description, service_photo } = serviceItem;

    // Create card div
    const card = document.createElement('div');
    card.classList.add('card');

    // Add the service image on the left side
    const img = document.createElement('img');
    img.src = service_photo ? service_photo : 'uploads/placeholder.jpg'; // Use a placeholder if no image
    img.alt = service_name;
    img.style.width = '150px'; // Fixed width for the image
    img.style.height = '150px'; // Fixed height for the image
    img.style.objectFit = 'cover'; // Ensure image is nicely cropped

    // Create a div to hold the details
    const detailsDiv = document.createElement('div');
    detailsDiv.classList.add('card-details');
    
    // Add service details (name, price, shop ID, description)
    detailsDiv.innerHTML = `
        <h4>${service_name} - $${service_price}</h4>
        <p>Shop ID: ${shop_id}</p>
        <p>${service_description}</p>
    `;

    // Create a container for image and details (flex)
    const cardContent = document.createElement('div');
    cardContent.style.display = 'flex';
    cardContent.style.gap = '10px'; // Space between image and details
    cardContent.appendChild(img);
    cardContent.appendChild(detailsDiv);

    card.appendChild(cardContent);
    return card;
}
