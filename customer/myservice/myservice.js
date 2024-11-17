document.addEventListener('DOMContentLoaded', () => {
    const myServiceLink = document.getElementById('myServiceLink');
    const popupCard = document.getElementById('popupCard');
    const closeCardButton = document.querySelector('.close-card');
    const inProgressServicesContainer = document.getElementById('inProgressServices');

    // Show popup when "My Service" link is clicked
    myServiceLink.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default link behavior
        popupCard.style.display = 'flex'; // Show the popup

        // Fetch in-progress services
        fetch('fetch_my_services.php')
            .then(response => response.json())
            .then(services => {
                inProgressServicesContainer.innerHTML = ''; // Clear any existing cards
                services.forEach(service => {
                    const card = document.createElement('div');
                    card.classList.add('service-card');

                    card.innerHTML = `
                        <img src="${service.service_photo}" alt="${service.service_name}" class="service-photo" />
                        <div class="service-details">
                            <h4>${service.service_name}</h4>
                            <p>Shop: ${service.shop_name}</p>
                            <p>Price: $${service.price}</p>
                            <p>Date: ${new Date(service.service_date).toLocaleDateString()}</p>
                            <p>Phone: <a href="tel:${service.phone}">${service.phone}</a></p>
                            <p>Address: ${service.location}, ${service.district}</p>
                            <a href="${service.google_map_location_url}" target="_blank" class="map-link">View on Map</a>
                        </div>
                    `;

                    inProgressServicesContainer.appendChild(card);
                });
            })
            .catch(error => console.error('Error fetching services:', error));
    });

    // Hide popup when close button is clicked
    closeCardButton.addEventListener('click', () => {
        popupCard.style.display = 'none'; // Hide the popup
    });
});
