document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    const links = document.querySelectorAll('.sidebar a');

    // Function to load content
    const loadContent = (url) => {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                contentDiv.innerHTML = data;
            })
            .catch(error => console.error('Error loading content:', error));
    };

    // Load content based on URL hash or default to home
    const loadInitialContent = () => {
        const hash = window.location.hash.substring(1);
        const page = hash ? hash : 'home.html';
        loadContent(page);
    };

    // Event listener for link clicks
    links.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const url = event.target.getAttribute('href');
            loadContent(url);
            window.history.pushState(null, '', url);
        });
    });

    // Handle browser navigation
    window.addEventListener('popstate', () => {
        const page = window.location.pathname.substring(1);
        loadContent(page);
    });

    // Initial content load
    loadInitialContent();
});
