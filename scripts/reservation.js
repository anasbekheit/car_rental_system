import {init} from "./util.js";

function createCarInfo() {
    const parentElement = document.getElementById('car_details');
    const car = JSON.parse(sessionStorage.getItem('car'));

    // Create the <p> element for totalAmount
    const totalAmountElement = createPropertyElement('totalAmount', 'Total Amount', calculateTotalAmount(car));

    for (let key in car) {
        if (car.hasOwnProperty(key)) {
            if (car.hasOwnProperty(key) && key !== 'car_image') {
                const propertyElement = createPropertyElement(key, key.toUpperCase().replace(/_/g, ' '), car[key]);
                parentElement.appendChild(propertyElement);
            }
        }
    }

    parentElement.appendChild(totalAmountElement);
    const img = document.createElement('img');
    img.src = car['car_image'];
    parentElement.appendChild(img);
}

function createPropertyElement(id, label, value) {
    const propertyElement = document.createElement('p');
    propertyElement.id = id;

    const strong = document.createElement('strong');
    strong.textContent = label;

    const child = document.createTextNode(value);

    propertyElement.appendChild(strong);
    propertyElement.appendChild(child);

    return propertyElement;
}

function calculateTotalAmount(car) {
    const from = new Date(sessionStorage.getItem('fromDate'));
    const to = new Date(sessionStorage.getItem('toDate'));
    const pricePerDay = parseFloat(car.price_per_day);
    const totalDays = Math.ceil((to - from) / (1000 * 60 * 60 * 24)); //Convert from milliseconds to days
    return '$' + (pricePerDay * totalDays).toFixed(2);
}

init();
document.addEventListener('DOMContentLoaded',function () {

    createCarInfo();
    // Back to search button click event
    document.getElementById('back_to_search').addEventListener('click', () => {
        sessionStorage.removeItem('car');
        window.location.href = 'search.html';
    });
});
