import {logout, init} from "./util.js";

init();
document.addEventListener("DOMContentLoaded", function() {

    // Rent a Car button click event
    document.getElementById("rentCarButton")
        .addEventListener("click", function() {
            window.location.href = '../view/search.html';
        });

    // View Reservations button click event
    document.getElementById("viewReservationsButton")
        .addEventListener("click", function() {
            window.location.href = '../view/view_reservations.html';
        });

    // Logout link click event
    document.getElementById("logoutLink")
        .addEventListener("click", function(event) {
            event.preventDefault();
            logout();
        });
});