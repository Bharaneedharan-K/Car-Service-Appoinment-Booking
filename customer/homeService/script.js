document.addEventListener("DOMContentLoaded", function() {
    showDetails('shop'); // Call showDetails with 'shop' to set default view

    // Search functionality
    const searchBtn = document.getElementById('searchBtn');
    searchBtn.addEventListener('click', () => {
        const searchTerm = document.getElementById('searchBar').value.toLowerCase();
        const currentType = shopBtn.classList.contains('active') ? 'shop' : 
                            serviceBtn.classList.contains('active') ? 'service' : 'home_service';
        searchItems(currentType, searchTerm);
    });
});

function searchItems(type, term) {
    const detailsDiv = document.getElementById('details');
    const cardContainer = detailsDiv.querySelector('.card-container');

    if (type === 'shop') {
        fetch('fetch_shop_list.php')
            .then(response => response.json())
            .then(shopItems => {
                cardContainer.innerHTML = '';
                shopItems.forEach(item => {
                    if (item.shop_name.toLowerCase().includes(term) || item.location.toLowerCase().includes(term)) {
                        const card = createShopCard(item);
                        cardContainer.appendChild(card);
                    }
                });
            });
    } else if (type === 'service') {
        fetch('fetch_service_list.php')
            .then(response => response.json())
            .then(serviceItems => {
                cardContainer.innerHTML = '';
                serviceItems.forEach(item => {
                    if (item.service_name.toLowerCase().includes(term) || item.service_description.toLowerCase().includes(term)) {
                        const card = createServiceCard(item);
                        cardContainer.appendChild(card);
                    }
                });
            });
    } else if (type === 'home_service') {
        fetch('fetch_home_service_list.php')
            .then(response => response.json())
            .then(homeServiceItems => {
                cardContainer.innerHTML = '';
                homeServiceItems.forEach(item => {
                    if (item.service_name.toLowerCase().includes(term) || item.service_description.toLowerCase().includes(term)) {
                        const card = createHomeServiceCard(item);
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
    const homeServiceBtn = document.getElementById('homeServiceBtn');
    const cardContainer = document.createElement('div');
    cardContainer.classList.add('card-container');

    detailsDiv.innerHTML = '';
    detailsDiv.style.display = 'block';

    shopBtn.classList.remove('active');
    serviceBtn.classList.remove('active');
    homeServiceBtn.classList.remove('active');

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
    } else if (type === 'home_service') {
        detailsDiv.innerHTML = `<h3>Home Service List</h3>`;
        homeServiceBtn.classList.add('active');

        fetch('fetch_home_service_list.php')
            .then(response => response.json())
            .then(homeServiceItems => {
                homeServiceItems.forEach(item => {
                    const card = createHomeServiceCard(item);
                    cardContainer.appendChild(card);
                });
            })
            .catch(error => console.error('Error fetching home service list:', error));
    }

    detailsDiv.appendChild(cardContainer);
}

function createHomeServiceCard(serviceItem) {
    const { service_name, service_price, shop_name, service_description, service_photo } = serviceItem;

    const card = document.createElement('div');
    card.classList.add('card');

    const img = document.createElement('img');
    img.src = service_photo ? service_photo : 'uploads/placeholder.jpg';
    img.alt = service_name;
    img.classList.add('service-photo');

    const detailsDiv = document.createElement('div');
    detailsDiv.classList.add('card-details');
    
    detailsDiv.innerHTML = `
        <h4>${service_name} - $${service_price}</h4>
        <p>Shop: ${shop_name}</p>
        <p>Description: ${service_description}</p>
    `;

    const addToCartBtn = document.createElement('button');
    addToCartBtn.innerText = 'Add to Cart';
    addToCartBtn.classList.add('add-to-cart-btn');

    addToCartBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        addToCart(serviceItem);
    });

    detailsDiv.appendChild(addToCartBtn);
    card.appendChild(img);
    card.appendChild(detailsDiv);

    card.addEventListener('click', () => {
        showServicePopup(serviceItem);
    });

    return card;
}

// Function stubs for createShopCard and createServiceCard (implementation can be similar to createHomeServiceCard)
function createShopCard(shopItem) {
    // Your implementation here...
}

function createServiceCard(serviceItem) {
    // Your implementation here...
}

function addToCart(serviceItem) {
    // Your implementation for adding to cart...
}

function showServicePopup(serviceItem) {
    // Your implementation for displaying service popup...
}
