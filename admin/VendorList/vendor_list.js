document.addEventListener("DOMContentLoaded", function() {
    fetchVendors();
});

function fetchVendors() {
    fetch('fetch_vendors.php')
        .then(response => response.json())
        .then(data => {
            const vendorCards = document.getElementById('vendor-cards');
            vendorCards.innerHTML = '';
            data.forEach(vendor => {
                const card = document.createElement('div');
                card.classList.add('card');
                card.innerHTML = `
                    <img src="${vendor.shop_photo}" alt="${vendor.shop_name}">
                    <h3>${vendor.shop_name}</h3>
                    <p><strong>Vendor Name:</strong> ${vendor.name}</p>
                    <p><strong>Shop ID:</strong> ${vendor.shop_id}</p>
                    <p><strong>Phone:</strong> ${vendor.phone}</p>
                    <p><strong>Email:</strong> ${vendor.email}</p>
                    <p><strong>Location:</strong> ${vendor.location}</p>
                `;
                card.addEventListener('click', () => showVendorDetails(vendor));
                vendorCards.appendChild(card);
            });
        });
}

function showVendorDetails(vendor) {
    const vendorDetails = document.getElementById('vendor-details');
    vendorDetails.innerHTML = `
        <div class="details-container">
            <div class="details-text">
                <h2>${vendor.shop_name}</h2>
                <p><strong>Vendor Name:</strong> ${vendor.name}</p>
                <p><strong>Shop ID:</strong> ${vendor.shop_id}</p>
                <p><strong>Phone:</strong> ${vendor.phone}</p>
                <p><strong>Email:</strong> ${vendor.email}</p>
                <p><strong>Location:</strong> ${vendor.location}</p>
                <button onclick="hideVendorDetails()">Close</button>
            </div>
            <img src="${vendor.shop_photo}" alt="${vendor.shop_name}" class="details-image">
        </div>
    `;
}

function hideVendorDetails() {
    const vendorDetails = document.getElementById('vendor-details');
    vendorDetails.innerHTML = '';
}

function filterVendors() {
    const searchTerm = document.querySelector('.search-bar').value.toLowerCase();
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        const shopName = card.querySelector('h3').textContent.toLowerCase();
        if (shopName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
