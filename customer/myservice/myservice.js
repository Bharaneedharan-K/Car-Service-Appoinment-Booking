document.addEventListener('DOMContentLoaded', () => {
    const myServiceLink = document.getElementById('myServiceLink');
    const popupCard = document.getElementById('popupCard');
    const closeCardButton = document.querySelector('.close-card');
    const inProgressServicesContainer = document.getElementById('inProgressServices');

    // Check if required elements exist
    if (!myServiceLink || !popupCard || !closeCardButton || !inProgressServicesContainer) {
        console.error('One or more required elements are missing. Check your HTML structure.');
        return;
    }

    // Show popup when "My Service" link is clicked
    myServiceLink.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default link behavior
        popupCard.style.display = 'flex'; // Show the popup

        // Fetch in-progress services
        fetch('my_services.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(services => {
                console.log('Fetched services:', services); // Log the fetched services

                // Check if services is an array and not empty
                if (!Array.isArray(services) || services.length === 0) {
                    console.warn('No services found or invalid data format');
                    inProgressServicesContainer.innerHTML = '<p>No services in progress.</p>';
                    return;
                }

                // Clear previous content before appending new cards
                inProgressServicesContainer.innerHTML = '';

                // Loop through services and create cards
                services.forEach(service => {
                    console.log('Processing service:', service); // Log each service

                    const card = document.createElement('div');
                    card.classList.add('service-card');

                    // Dynamically create the service card content
                    card.innerHTML = `
                        <img 
                            src="${service.photo}" 
                            alt="${service.service_name}" 
                            class="service-photo" 
                            onerror="this.onerror=null; this.src='https://via.placeholder.com/150?text=No+Image';" />
                        <div class="service-details">
                            <h4>${service.service_name}</h4>
                            <p><strong>Shop:</strong> ${service.shop_name}</p>
                            <p><strong>Price:</strong> ₹${service.price}</p>
                            <p><strong>Date:</strong> ${new Date(service.service_date).toLocaleDateString()}</p>
                            <p><strong>Phone:</strong> <a href="tel:${service.phone}">${service.phone}</a></p>
                            <p><strong>Address:</strong> ${service.location}</p>
                            <a href="${service.google_map_location_url}" target="_blank" class="map-link">View on Map</a>
                        </div>
                    `;

                    // Append the card to the container
                    inProgressServicesContainer.appendChild(card);
                });
            })
            .catch(error => {
                console.error('Error fetching services:', error);
                inProgressServicesContainer.innerHTML = '<p>Failed to load services. Please try again later. ' + error.message + '</p>';
            });
    });

    // Hide popup when the close button is clicked
    closeCardButton.addEventListener('click', () => {
        popupCard.style.display = 'none'; // Hide the popup
    });
});
