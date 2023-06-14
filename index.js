document.addEventListener("DOMContentLoaded",
    function(){

        // Rent a Car button click event
        document.getElementById("rentCarButton").addEventListener("click", function() {
            window.location.href = "search.html";
        });

        // View Reservations button click event
        document.getElementById("viewReservationsButton").addEventListener("click", function() {
            window.location.href = "viewReservation.php";
        });

        document.getElementById("logoutLink").addEventListener("click", function() {
            window.location.href = "viewReservation.php";
        });

        // Logout link click event
        document.getElementById("logoutLink").addEventListener("click", function(event) {
            event.preventDefault(); // Prevent the default link behavior (navigation)
            // Redirect to logout URL
            window.location.href = "index.php?logout=1";
        });
    })
