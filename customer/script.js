document.addEventListener("DOMContentLoaded", function() {
    fetchServices();
});

function fetchServices() {
    fetch('fetch_services.php')
        .then(response => response.json())
        .then(data => {
            const serviceCards = document.getElementById('service-cards');
            serviceCards.innerHTML = '';
            data.forEach(service => {
                const card = document.createElement('div');
                card.classList.add('card');
                card.innerHTML = `
                    <img src="${service.service_photo}" alt="${service.service_name}">
                    <div class="card-details">
                        <h3>${service.service_name}</h3>
                        <p><strong>Service ID:</strong> ${service.service_id}</p>
                        <p><strong>Price:</strong> $${service.service_price}</p>
                        <p><strong>Description:</strong> ${service.service_description}</p>
                        <p><strong>Shop ID:</strong> ${service.shop_id}</p>
                        <button class="book-now" onclick="bookService(${service.service_id})">Book Now</button>
                    </div>
                `;
                serviceCards.appendChild(card);
            });
        });
}

function filterServices() {
    const searchTerm = document.querySelector('.search-bar').value.toLowerCase();
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const serviceName = card.querySelector('h3').textContent.toLowerCase();
        if (serviceName.includes(searchTerm)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function bookService(serviceId) {
    alert(`Service ID ${serviceId} has been booked!`);
}
