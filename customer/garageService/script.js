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

    // Append the image and details to the card
    card.appendChild(img);
    card.appendChild(detailsDiv);

    // Add event listener to show service popup on click
    card.addEventListener('click', () => {
        showServicePopup(serviceItem);
    });

    return card;
}

function createShopCard(shopItem) {
    const { shop_name, shop_id, location, shop_photo } = shopItem;

    // Create card div
    const card = document.createElement('div');
    card.classList.add('card');
    card.classList.add('shop-card'); // Add specific class for shop card

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

    // Add event listener to show popup on click
    card.addEventListener('click', () => {
        // Remove active class from other cards
        const allCards = document.querySelectorAll('.shop-card');
        allCards.forEach(c => c.classList.remove('active-card'));

        // Add active class to the clicked card
        card.classList.add('active-card');

        showPopup(shopItem);

        // Remove the active class after a short duration (optional)
        setTimeout(() => {
            card.classList.remove('active-card');
        }, 300); // Adjust duration as needed
    });

    return card;
}

function showPopup(shopItem) {
    const { shop_id } = shopItem; // Only use shop_id to fetch services

    // Create the modal element
    const modal = document.createElement('div');
    modal.classList.add('modal', 'shop-popup'); // Add 'shop-popup' class

    // Create modal content
    const modalContent = document.createElement('div');
    modalContent.classList.add('modal-content');

    // Add a close button
    const closeBtn = document.createElement('span');
    closeBtn.classList.add('close-btn');
    closeBtn.innerHTML = '&times;';

    // Add event listener to close the modal when the close button is clicked
    closeBtn.addEventListener('click', () => {
        document.body.removeChild(modal); // Remove the modal from the DOM
    });

    // Add a header for the services
    const modalHeader = `
        <div class="modal-header">
            <h4>Services List</h4>
        </div>
        <div id="service-list-${shop_id}">Loading services...</div>
    `;

    modalContent.innerHTML = modalHeader;
    modalContent.appendChild(closeBtn); // Append the close button after modal content

    modal.appendChild(modalContent);

    // Append modal to body
    document.body.appendChild(modal);

    // Fetch services for the shop and display them in the modal
    fetch(`fetch_services_by_shop.php?shop_id=${shop_id}`)
        .then(response => response.json())
        .then(serviceItems => {
            const serviceListDiv = document.getElementById(`service-list-${shop_id}`);
            serviceListDiv.innerHTML = ''; // Clear loading text

            serviceItems.forEach(service => {
                const serviceDiv = document.createElement('div');
                serviceDiv.classList.add('service-item');

                serviceDiv.innerHTML = `
                    <img src="${service.service_photo ? service.service_photo : 'uploads/placeholder.jpg'}" alt="${service.service_name}" class="service-photo-small">
                    <h6>${service.service_name} - $${service.service_price}</h6>
                    <p>${service.service_description}</p>
                `;

                serviceListDiv.appendChild(serviceDiv);
            });
        })
        .catch(error => {
            const serviceListDiv = document.getElementById(`service-list-${shop_id}`);
            serviceListDiv.innerHTML = 'Error loading services';
            console.error('Error fetching services:', error);
        });
}



function showServicePopup(serviceItem) {
    const { service_name, service_price, shop_name, service_description, service_photo } = serviceItem;

    // Create the modal element
    const modal = document.createElement('div');
    modal.classList.add('modal');

    // Create modal content
    const modalContent = document.createElement('div');
    modalContent.classList.add('modal-content');

    // Add close button
    const closeBtn = document.createElement('span');
    closeBtn.classList.add('close-btn');
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => {
        document.body.removeChild(modal);
    });

    // Add service details without the action button
    const modalDetails = `
        <div class="modal-header">
            <img src="${service_photo ? service_photo : 'uploads/placeholder.jpg'}" alt="${service_name}" class="modal-img">
            ${closeBtn.outerHTML} <!-- Close button outside the image -->
        </div>
        <h4>${service_name} - $${service_price}</h4>
        <p>Shop: ${shop_name}</p>
        <p>Description: ${service_description}</p>
    `;

    modalContent.innerHTML = modalDetails;
    modalContent.appendChild(closeBtn); // Append close button to modal content
    modal.appendChild(modalContent);

    // Append modal to body
    document.body.appendChild(modal);
}
