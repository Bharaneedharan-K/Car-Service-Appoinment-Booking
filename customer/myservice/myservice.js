// myservice.js

// Get the "My Service" link and the popup card elements
const myServiceLink = document.getElementById("myServiceLink");
const popupCard = document.getElementById("popupCard");
const closePopup = document.querySelector(".close-popup");

// Show the popup card when "My Service" is clicked
myServiceLink.addEventListener("click", function(event) {
    event.preventDefault(); // Prevent the default link behavior
    popupCard.style.display = "flex"; // Show the popup card
});

// Hide the popup card when the close button is clicked
closePopup.addEventListener("click", function() {
    popupCard.style.display = "none"; // Hide the popup card
});
