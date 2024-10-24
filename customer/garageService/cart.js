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

    let totalPrice = 0;  // Initialize total price
    let shopIdSet = new Set();  // Store shop ids to check for consistency

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
                    <p>Shop Name: ${item.shop_name}</p>
                    <p>Price: $${item.price}</p>
                    <button class="remove-btn" data-serial-no="${item.serial_no}">Remove</button>
                </div>
            `;

            // Add event listener to remove button
            card.querySelector('.remove-btn').addEventListener('click', () => {
                removeCartItem(item.serial_no);
            });

            // Append each card to the container
            cartItemsContainer.appendChild(card);

            // Accumulate total price
            totalPrice += parseFloat(item.price);

            // Add the shop ID to the set
            shopIdSet.add(item.shop_id);
        });
    }

    modalContent.appendChild(cartItemsContainer);

    // Add total price element
    const totalPriceElement = document.createElement('div');
    totalPriceElement.classList.add('total-price');
    totalPriceElement.innerHTML = `<p>Total Price: $${totalPrice.toFixed(2)}</p>`;
    modalContent.appendChild(totalPriceElement);  // Append total price to the modal

    // Add close button
    modalContent.appendChild(closeBtn);

    // Add "Book Now" button at the bottom right
    const bookNowBtn = document.createElement('button');
    bookNowBtn.innerText = 'Book Now';
    bookNowBtn.classList.add('book-now-btn'); // Add a class for styling
    bookNowBtn.addEventListener('click', () => {
        // Check if all services are from the same shop
        if (shopIdSet.size > 1) {
            alert('All services must be from the same shop.');
        } else {
            // Proceed with booking (to be implemented)
            alert('Proceeding to book the selected services.');
            document.body.removeChild(modal); // Close modal after clicking book now
        }
    });

    // Append the "Book Now" button to modal content
    modalContent.appendChild(bookNowBtn);

    modal.appendChild(modalContent);

    // Append modal to the body
    document.body.appendChild(modal);
}
    


function removeCartItem(serial_no) {
    // Send a DELETE request to remove the cart item
    fetch(`remove_cart_item.php?serial_no=${serial_no}`, {
        method: 'GET'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Item removed from cart!');
            fetchCartItems(); // Refresh the cart items after deletion
        } else {
            alert('Failed to remove the item.');
        }
    })
    .catch(error => {
        console.error('Error removing item:', error);
    });
}
