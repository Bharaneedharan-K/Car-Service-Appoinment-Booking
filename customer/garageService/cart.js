document.addEventListener("DOMContentLoaded", function () {
    const cartBtn = document.getElementById('cartBtn');

    // Event listener for "My Cart" button to show the cart popup
    cartBtn.addEventListener('click', () => {
        fetchCartItems();  // Fetch cart items when the button is clicked
    });
});

function fetchCartItems() {
    // Fetch data from the PHP file that returns cart items as JSON
    fetch('get_cart_data.php')
        .then(response => response.json())
        .then(data => {
            showCartPopup(data);  // Pass the fetched data to the showCartPopup function
        })
        .catch(error => console.error('Error fetching cart data:', error));
}
function showCartPopup(cartItems) {
    // Create the modal element
    const modal = document.createElement('div');
    modal.classList.add('modal');

    // Create modal content
    const modalContent = document.createElement('div');
    modalContent.classList.add('modal-content');

    // Add close button (top right)
    const closeBtn = document.createElement('span');
    closeBtn.classList.add('close-btn');
    closeBtn.innerHTML = '&times;';
    closeBtn.addEventListener('click', () => {
        document.body.removeChild(modal); // Close the modal on click
    });

    // Add cart header
    const modalHeader = `
        <div class="modal-header">
            <h4>My Cart</h4>
        </div>
    `;
    modalContent.innerHTML = modalHeader;

    // Add cart items dynamically
    const cartItemsContainer = document.createElement('div');
    cartItemsContainer.id = "cart-items";

    if (cartItems.length === 0) {
        cartItemsContainer.innerHTML = "<p>No items in your cart yet.</p>";
    } else {
        cartItems.forEach(item => {
            // Create card for each item
            const card = document.createElement('div');
            card.classList.add('service-card');

            // Card content (left side: photo, right side: service details and price)
            card.innerHTML = `
                <div class="card-left">
                    <img src="${item.service_photo}" alt="${item.service_name}" />
                </div>
                <div class="card-right">
                    <h5>${item.service_name}</h5>
                    <p>Shop ID: ${item.shop_id}</p>
                    <p>Price: $${item.price}</p>
                    <button class="remove-btn" data-serial-no="${item.serial_no}">Remove</button>
                </div>
            `;

            // Add event listener to remove button
            card.querySelector('.remove-btn').addEventListener('click', () => {
                removeCartItem(item.serial_no);
            });

            cartItemsContainer.appendChild(card);
        });
    }

    modalContent.appendChild(cartItemsContainer);
    modalContent.appendChild(closeBtn);

    // Add "Book Now" button at the bottom right
    const bookNowBtn = document.createElement('button');
    bookNowBtn.innerText = 'Book Now';
    bookNowBtn.classList.add('book-now-btn'); // Add a class for styling
    bookNowBtn.addEventListener('click', () => {
        // Functionality to proceed with booking (to be implemented)
        alert('Proceeding to book the selected services.');
        document.body.removeChild(modal); // Close modal after clicking book now
    });

    // Append the "Book Now" button to modal content
    modalContent.appendChild(bookNowBtn);

    modal.appendChild(modalContent);

    // Append modal to the body
    document.body.appendChild(modal);
}
