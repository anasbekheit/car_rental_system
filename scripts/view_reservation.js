import {init} from "./util.js";

async function retrieveReservations() {
    const url = `../logic/view_reservation.php`;
    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return await response.json();
    } catch (error) {
        console.error('Error:', error);
        throw error;
    }
}

async function loadTableData() {
    const table = document.getElementById("table_body");

    try {
        const items = await retrieveReservations();

        items.forEach(item => {
            const row = table.insertRow();
            Object.values(item).forEach(value => {
                const cell = row.insertCell();
                cell.innerText = value;
            });
        });

        console.log(items);
    } catch (error) {
        console.error('Error loading table data:', error);
    }
}

init();

// Define column names
const columnNames = [
    "Reservation ID",
    "Plate ID",
    "Pickup time",
    "Return time",
    "Total Amount Paid",
    "Color",
    "Year",
    "Model",
    "Manufacturer",
    "Country",
    "Reservation Time"
];

document.addEventListener('DOMContentLoaded',  function (){

// Generate table header
    const tableHeader = document.getElementById("table_header");
    const headerRow = document.createElement("tr");
    columnNames.forEach(columnName => {
        const th = document.createElement("th");
        th.textContent = columnName;
        headerRow.appendChild(th);
    });
    tableHeader.appendChild(headerRow);
    loadTableData().then();
});