import {logout, updateUsername} from "./util.js";

let username = '';

async function fetchUsername() {
    try {
        const response = await fetch('../logic/index.php');

        if (!response.ok) {
            throw new Error("Something went wrong!");
        }

        if (response.redirected) {
            console.log("redirected");
            window.location.href = '../view/login.html';
            return Promise.reject('Redirection occurred');
        } else {
            const data = await response.json();
            console.log("data is : " + data);
            return data;
        }
    } catch (error) {
        console.error('Error:', error);
        throw error; // Rethrow the error for the outer catch block, if needed.
    }
}

async function init() {
    try {
        username = await fetchUsername();
        if (document.readyState === 'complete') {
            updateUsername(username);
        } else {
            window.addEventListener('load', function() {
                updateUsername(username);
            });
        }
    } catch (error) {
        // Handle the error as needed
        console.error('Error:', error);
    }
}
!sessionStorage.getItem('loggedIn')? window.location.href = '../view/login.html' : init();
document.addEventListener("DOMContentLoaded", function() {

    // Rent a Car button click event
    document.getElementById("rentCarButton")
        .addEventListener("click", function() {
            window.location.href = '../view/search.html';
        });

    // View Reservations button click event
    document.getElementById("viewReservationsButton")
        .addEventListener("click", function() {
            window.location.href = '../logic/view_reservation.php';
        });

    // Logout link click event
    document.getElementById("logoutLink")
        .addEventListener("click", function(event) {
            event.preventDefault();
            logout();
        });
});