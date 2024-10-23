document.addEventListener("DOMContentLoaded", function () {
    const cartBtn = document.getElementById('cartBtn');

    // Event listener for "My Cart" button to show the cart popup
    cartBtn.addEventListener('click', () => {
        showCartPopup();
    });
});

function showCartPopup() {
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

    // Add cart header and sample cart content
    const modalHeader = `
        <div class="modal-header">
            <h4>My Cart</h4>
        </div>
        <div id="cart-items">
            <p>No items in your cart yet.</p> <!-- This could be dynamic if you add actual cart logic -->
        </div>
    `;
    
    // Set modal content
    modalContent.innerHTML = modalHeader;
    modalContent.appendChild(closeBtn);
    modal.appendChild(modalContent);

    // Append modal to the body
    document.body.appendChild(modal);
}
