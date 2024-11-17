document.addEventListener('DOMContentLoaded', () => {
    const myServiceLink = document.getElementById('myServiceLink');
    const popupCard = document.getElementById('popupCard');
    const closeCardButton = document.querySelector('.close-card');

    // Show popup when "My Service" link is clicked
    myServiceLink.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default link behavior
        popupCard.style.display = 'flex'; // Show the popup
    });

    // Hide popup when close button is clicked
    closeCardButton.addEventListener('click', () => {
        popupCard.style.display = 'none'; // Hide the popup
    });
});
