// Handle the modal display
document.getElementById('addButton').onclick = function() {
    document.getElementById('modalOverlay').style.display = 'flex';
};

document.getElementById('closeModal').onclick = function() {
    document.getElementById('modalOverlay').style.display = 'none';
};

window.onclick = function(event) {
    if (event.target === document.getElementById('modalOverlay')) {
        document.getElementById('modalOverlay').style.display = 'none';
    }
};

// Fetch and display service data
document.addEventListener('DOMContentLoaded', () => {
    fetch('fetch_services.php')
        .then(response => response.json())
        .then(data => {
            console.log('Fetched data:', data); // Debugging line to check data format
            const cardContainer = document.getElementById('cardContainer');

            // Ensure cardContainer exists
            if (!cardContainer) {
                console.error('Card container not found.');
                return;
            }

            // Check if data is an array and contains items
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(service => {
                    // Check each service object
                    console.log('Service item:', service);

                    const card = document.createElement('div');
                    card.className = 'service-card';

                    const cardLeft = document.createElement('div');
                    cardLeft.className = 'card-left';
                    const img = document.createElement('img');
                    img.src = `uploads/${service.service_photo}`; // Adjust path if needed
                    img.className = 'service-photo';
                    img.alt = service.service_name; // Adding alt attribute
                    cardLeft.appendChild(img);

                    const cardRight = document.createElement('div');
                    cardRight.className = 'card-right';
                    const title = document.createElement('h3');
                    title.textContent = service.service_name;
                    const price = document.createElement('p');
                    price.textContent = `Price: $${service.service_price}`;
                    const numServices = document.createElement('p');
                    numServices.textContent = `Services per day: ${service.number_days}`;
                    const description = document.createElement('p');
                    description.textContent = `Description: ${service.service_description}`;
                    const editBtn = document.createElement('button');
                    editBtn.textContent = 'Edit';
                    editBtn.className = 'edit-btn';

                    cardRight.appendChild(title);
                    cardRight.appendChild(price);
                    cardRight.appendChild(numServices);
                    cardRight.appendChild(description);
                    cardRight.appendChild(editBtn);

                    card.appendChild(cardLeft);
                    card.appendChild(cardRight);
                    cardContainer.appendChild(card);
                });
            } else {
                console.log('No service data available.');
            }
        })
        .catch(error => console.error('Error fetching data:', error));
});
