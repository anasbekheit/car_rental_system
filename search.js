function logout() {
    window.location.href = 'index.php?logout=1';
}
document.addEventListener('DOMContentLoaded', function() {
    // Get the search form element
    const searchForm = document.querySelector('.search_form');
    const searchButton = document.getElementById('search_btn');
    // Add event listener for form submission
    searchForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Create a new FormData object
        const formData = new FormData(searchForm);

        // Send an AJAX request to the search.php file
        fetch('search.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // Remove the old search results
                const oldSearchResults = document.querySelector('.search_results');
                if (oldSearchResults) {
                    oldSearchResults.remove();
                }
                // Process the returned data
                console.log(data);
                // Create a new search results section
                const searchResults = document.createElement('div');
                searchResults.classList.add('search_results');
                document.body.appendChild(searchResults);

                if (data.length > 0) {
                    data.forEach(result => {
                        // Create HTML elements for each result
                        const div = document.createElement('div');
                        const img = document.createElement('img');
                        const button = document.createElement('button');
                        const h1 = document.createElement('h1');
                        const price = document.createElement('p');
                        const info = document.createElement('p');
                        // Set attributes of elements
                        div.classList.add('card');
                        img.src = result.car_image;
                        button.type = 'submit';
                        button.name = 'button';
                        price.classList.add('price');

                        // Set content of the elements
                        button.innerText = 'View';
                        h1.textContent = result.car_manufacturer + ' : ' + result.car_model;
                        price.textContent = result.price_per_day + '$';
                        info.textContent = `Model year: ${result.model_year}.\nCountry: ${result.country}`;

                        // Append elements to the search results section
                        div.appendChild(img);
                        div.appendChild(button);
                        div.appendChild(h1);
                        div.appendChild(price);
                        div.appendChild(info);
                        searchResults.appendChild(div);
                    });
                } else {
                    // Display a message if no results found
                    searchResults.textContent = 'No results found.';
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});