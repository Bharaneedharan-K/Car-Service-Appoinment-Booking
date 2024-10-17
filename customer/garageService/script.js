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
        // Fetch the shop data from the backend
        detailsDiv.innerHTML = `<h3>Shop List</h3>`;
        shopBtn.classList.add('active'); 

        fetch('fetch_shop_list.php')
            .then(response => response.json())
            .then(shopItems => {
                shopItems.forEach(item => {
                    const card = createShopCard(item);
                    cardContainer.appendChild(card);
                });
            })
            .catch(error => console.error('Error fetching shop list:', error));
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
    const { service_name, service_price, shop_name, service_description, service_photo } = serviceItem;

    // Create card div
    const card = document.createElement('div');
    card.classList.add('card');

    // Add the service image on the left side
    const img = document.createElement('img');
    img.src = service_photo ? service_photo : 'uploads/placeholder.jpg'; // Use a placeholder if no image
    img.alt = service_name;
    img.classList.add('service-photo');

    // Create a div to hold the details
    const detailsDiv = document.createElement('div');
    detailsDiv.classList.add('card-details');
    
    // Add service details (name, price, shop name, description)
    detailsDiv.innerHTML = `
        <h4>${service_name} - $${service_price}</h4>
        <p>Shop: ${shop_name}</p>
        <p>Description: ${service_description}</p> 

    `;

    // Create the action button
    const actionButton = document.createElement('button');
    actionButton.classList.add('action-btn');
    actionButton.textContent = 'Add Cart';
    actionButton.addEventListener('click', () => {
        alert(`Booking service: ${service_name}`);
    });

    // Append the image, details, and button to the card
    card.appendChild(img);
    card.appendChild(detailsDiv);
    card.appendChild(actionButton);

    return card;
}





function createShopCard(shopItem) {
    const { shop_name, shop_id, location, shop_photo } = shopItem;

    // Create card div
    const card = document.createElement('div');
    card.classList.add('card');

    // Add the shop image on the left side
    const img = document.createElement('img');
    img.src = shop_photo ? shop_photo : 'uploads/placeholder.jpg'; // Use a placeholder if no image
    img.alt = shop_name;
    img.classList.add('shop-photo'); 

    // Create a div to hold the details
    const detailsDiv = document.createElement('div');
    detailsDiv.classList.add('card-details');

    // Add shop details (name, ID, address)
    detailsDiv.innerHTML = `
        <h4>${shop_name}</h4>
        <p>Shop ID: ${shop_id}</p>
        <p>Location: ${location}</p>
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
