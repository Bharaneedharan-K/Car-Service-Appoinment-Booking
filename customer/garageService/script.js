document.addEventListener("DOMContentLoaded", function() {
    showDetails('shop'); // Call showDetails with 'shop' to set default view

    // Search functionality
    const searchBtn = document.getElementById('searchBtn');
    searchBtn.addEventListener('click', () => {
        const searchTerm = document.getElementById('searchBar').value.toLowerCase();
        const currentType = shopBtn.classList.contains('active') ? 'shop' : 'service';
        searchItems(currentType, searchTerm);
    });
});

function searchItems(type, term) {
    const detailsDiv = document.getElementById('details');
    const cardContainer = detailsDiv.querySelector('.card-container');

    if (type === 'shop') {
        fetch('fetch_shop_list.php') // Fetch shop items again to get updated list
            .then(response => response.json())
            .then(shopItems => {
                cardContainer.innerHTML = ''; // Clear previous cards
                shopItems.forEach(item => {
                    if (item.shop_name.toLowerCase().includes(term) || item.location.toLowerCase().includes(term)) {
                        const card = createShopCard(item);
                        cardContainer.appendChild(card);
                    }
                });
            });
    } else if (type === 'service') {
        fetch('fetch_service_list.php') // Fetch service items again
            .then(response => response.json())
            .then(serviceItems => {
                cardContainer.innerHTML = ''; // Clear previous cards
                serviceItems.forEach(item => {
                    if (item.service_name.toLowerCase().includes(term) || item.service_description.toLowerCase().includes(term)) {
                        const card = createServiceCard(item);
                        cardContainer.appendChild(card);
                    }
                });
            });
    }
}

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
    const { shop_id } = shopItem;

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

    // Add service list header and container
    const modalHeader = `
        <div class="modal-header">
            <h4>Services List</h4>
        </div>
        <div id="service-list-${shop_id}">Loading services...</div>
    `;
    
    modalContent.innerHTML = modalHeader;
    modalContent.appendChild(closeBtn);
    modal.appendChild(modalContent);

    // Append modal to body
    document.body.appendChild(modal);

    // Fetch services for the shop and display them in the modal
    fetch(`fetch_services_by_shop.php?shop_id=${shop_id}`)
        .then(response => response.json())
        .then(serviceItems => {
            const serviceListDiv = document.getElementById(`service-list-${shop_id}`);
            serviceListDiv.innerHTML = ''; // Clear loading text

            // Create a card for each service
            serviceItems.forEach(service => {
                const serviceDiv = document.createElement('div');
                serviceDiv.classList.add('service-card');

                serviceDiv.innerHTML = `
                    <div class="service-item">
                        <img src="${service.service_photo ? service.service_photo : 'uploads/placeholder.jpg'}" alt="${service.service_name}" class="service-photo-small">
                        <div class="service-details">
                            <h6>${service.service_name} - $${service.service_price}</h6>
                            <p>${service.service_description}</p>
                            <button class="add-to-cart-btn">Add to Cart</button>
                        </div>
                    </div>
                `;

                serviceListDiv.appendChild(serviceDiv);

                // Add hover animation effect
                serviceDiv.addEventListener('mouseenter', () => {
                    serviceDiv.classList.add('active-card');
                });

                serviceDiv.addEventListener('mouseleave', () => {
                    serviceDiv.classList.remove('active-card');
                });

                // Add event listener for the "Add to Cart" button
                const addToCartBtn = serviceDiv.querySelector('.add-to-cart-btn');
                addToCartBtn.addEventListener('click', () => {
                    addToCart(service.service_id, service.service_name, service.service_price);
                });
            });
        })
        .catch(error => {
            const serviceListDiv = document.getElementById(`service-list-${shop_id}`);
            serviceListDiv.innerHTML = 'Error loading services';
            console.error('Error fetching services:', error);
        });
}

// Example of a function that adds the service to the cart
function addToCart(serviceId, serviceName, servicePrice) {
    // Logic to add the selected service to the cart (e.g., updating the cart UI, storing data, etc.)
    console.log(`Service added to cart: ${serviceName} (ID: ${serviceId}, Price: $${servicePrice})`);

    // Example: Show a success message or update cart count
    alert(`${serviceName} has been added to your cart!`);
}

function showServicePopup(serviceItem) {
    const { service_name, service_price, shop_id, shop_name, service_description, service_photo } = serviceItem;

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

    // Add service details with the 'Add to Cart' button
    const modalDetails = `
        <div class="modal-header">
            <img src="${service_photo ? service_photo : 'uploads/placeholder.jpg'}" alt="${service_name}" class="modal-img">
            ${closeBtn.outerHTML} <!-- Close button outside the image -->
        </div>
        <h4>${service_name} - $${service_price}</h4>
        <p>Shop: ${shop_name}</p>
        <p>Description: ${service_description}</p>
        <button class="add-to-cart-btn">Add to Cart</button> <!-- Add to Cart button -->
    `;

    modalContent.innerHTML = modalDetails;
    modalContent.appendChild(closeBtn);
    modal.appendChild(modalContent);

    // Append modal to body
    document.body.appendChild(modal);

    // Add functionality to 'Add to Cart' button
    const addToCartBtn = modalContent.querySelector('.add-to-cart-btn');
    addToCartBtn.addEventListener('click', () => {
        // Send data to add_to_cart.php using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "add_to_cart.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Prepare the data
        const params = `service_name=${encodeURIComponent(service_name)}&service_price=${service_price}&shop_id=${shop_id}&service_photo=${encodeURIComponent(service_photo)}`;

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(`${service_name} has been added to your cart!`);
            } else {
                alert('Error adding service to cart.');
            }
        };

        xhr.send(params);
    });
}

