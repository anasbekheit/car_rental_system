import {init, isValidDate} from "./util.js";

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

async function searchData(formData) {
    try {
        const url = `../logic/search.php?action=search&${new URLSearchParams(formData).toString()}`;
        console.log(url);
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Something went wrong!');
        }
        return response.json();
    } catch (error) {
        throw error;
    }
}

function displaySearchResults(data, fromDate, toDate) {
    const results = document.createElement('div');
    const cardContainerClass = 'card-container'
    results.classList.add(cardContainerClass);

    data.length > 0 ? data.forEach(car => createCarCard(results, car, fromDate, toDate))
        : results.textContent = 'No results found.';

    const oldResults = document.querySelector(`.${cardContainerClass}`);
    oldResults?.replaceWith(results) || document.body.appendChild(results);
}

function createCarCard(searchResults, result, fromDate, toDate) {
    const { car_image, car_manufacturer, car_model, price_per_day, model_year, country } = result;

    const div = document.createElement('div');
    div.classList.add('card');

    const img = document.createElement('img');
    img.src = car_image;

    const button = document.createElement('button');
    button.type = 'submit';
    button.name = 'button';
    button.innerText = 'View';
    button.addEventListener('click', function () {
        sessionStorage.setItem('car', JSON.stringify(result));
        sessionStorage.setItem('fromDate', fromDate);
        sessionStorage.setItem('toDate', toDate);
        window.location.href = '../view/reservation.html';
    });

    const h1 = document.createElement('h1');
    h1.textContent = `${car_manufacturer} : ${car_model}`;

    const price = document.createElement('p');
    price.classList.add('price');
    price.textContent = `${price_per_day}$`;

    const info = document.createElement('p');
    info.textContent = `Model year: ${model_year}.\nCountry: ${country}`;

    div.appendChild(img);
    div.appendChild(button);
    div.appendChild(h1);
    div.appendChild(price);
    div.appendChild(info);

    searchResults.appendChild(div);
}

let manufacturers, models;
// Using async/await with fetch API
async function retrieveAvailableCars() {
    try {
        const url = '../logic/search.php?action=retrieveAvailableCars';
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return await response.json(); // You can return the list of available cars if needed.
    } catch (error) {
        console.error('Error:', error);
        throw error; // Rethrow the error for the caller to handle if needed.
    }
}

function autocompleteMatch(input) {
    if (input === '') {
        return [];
    }
    const reg = new RegExp(input);
    return manufacturers.filter(function(term) {
        if (term.match(reg)) {
            return term;
        }
    });
}

function showResults(val) {
    const res = document.getElementById("auto_complete");
    res.innerHTML = '';
    let list = '';
    let terms = autocompleteMatch(val);
    for (let i=0; i<terms.length; i++) {
        list += '<li>' + terms[i] + '</li>';
    }
    res.innerHTML = '<ul>' + list + '</ul>';
}

function separate(searchData) {
    const manufacturers = [];
    const models = [];

    searchData.forEach(datum => {
        models.push(datum.car_model); // Use push instead of concat
        manufacturers.push(datum.car_manufacturer); // Use push instead of concat
    });

    return [manufacturers, models];
}

init();
document.addEventListener('DOMContentLoaded', async function() {
    const searchDataResult = await retrieveAvailableCars();
    [manufacturers, models] = separate(searchDataResult);
    console.log(manufacturers);
    console.log(models);
    document.querySelector('.search_form').addEventListener('submit', async function(event) {
        event.preventDefault();

        const fromDate = document.getElementById('from_date').value;
        const toDate = document.getElementById('to_date').value;

        if (!correctDates(fromDate, toDate)) {
            return;
        }

        const formData = new FormData(this);

        try {
            const data = await searchData(formData);
            displaySearchResults(data, fromDate, toDate);
        } catch (error) {
            console.error('Error:', error);
        }
    });
    
    document.getElementById('car_manufacturer').addEventListener('keyup', function () {
        showResults(this.value);
    })
});