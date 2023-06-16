function logout() {
    window.location.href = '../index.php?logout=1';
}
function isValidDate(dateString)
{
    // First check for the pattern
    //Function checks against regex pattern for mm/dd/yyyy
    // In reality the default format is yyyy-mm-dd
    if(!/^\d{4}-\d{1,2}-\d{1,2}$/.test(dateString))
        return false;

    // Parse the date parts to integers
    const parts = dateString.split("-");
    const day = parseInt(parts[2], 10);
    const month = parseInt(parts[1], 10);
    const year = parseInt(parts[0], 10);

    // Check the ranges of month and year
    if(year < 1000 || year > 3000 || month < 1 || month > 12)
        return false;

    const monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

    // Adjust for leap years
    if(year % 400 === 0 || (year % 100 !== 0 && year % 4 === 0))
        monthLength[1] = 29;

    // Check the range of the day
    return day > 0 && day <= monthLength[month - 1];
}
function correctDates(fromDate, toDate) {
    if ( !isValidDate(fromDate) || !isValidDate(toDate)){
        alert("Invalid Dates.\nFormat should be mm/dd/yyyy.");
        return false;
    }
    fromDate = new Date(fromDate);
    toDate = new Date(toDate);
    if(fromDate > toDate){
        alert("From date has to be before To Date.");
        return false;
    }
    const today = new Date();
    if (fromDate < today || toDate < today){
        alert("Both From and To Date should be in the future");
        return false;
    }

    return true;
}
document.addEventListener('DOMContentLoaded', function() {
    // Get the search form element
    const searchForm = document.querySelector('.search_form');
    document.getElementById('search_btn');

    // Add event listener for form submission
    searchForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the "from" and "to" date values
        const fromDate = document.getElementById('from_date').value;
        const toDate = document.getElementById('to_date').value;

        // Validate the dates
        if(!correctDates(fromDate, toDate)){
            return;
        }

        // Create a new FormData object
        const formData = new FormData(searchForm);

        // Send an AJAX request to the search.php file
        fetch('../search.php', {
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
                    data.forEach(result => createCarCard(searchResults, result));
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

function createCarCard(searchResults, result) {
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
}
