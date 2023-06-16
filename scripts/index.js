let username = '';
function updateUsername() {
    document.getElementById("username").innerText = username;
}
// Fetch The username from server
fetch('../index.php')
    .then(response => {
        if(!response.ok){
            console.log("not response");
            throw new Error("Something went wrong!");
        }
        if(response.redirected){
            console.log("redirected");
            window.location.href = '../view/login.html';
            return Promise.reject('Redirection occurred');
        }else{
            return response.json();
        }
    })
    .then(data => {
        console.log("data is : " + data);
        username = data;
        if (document.readyState === 'complete') {
            updateUsername();
        } else {
            window.addEventListener('load', updateUsername);
        }
    })
    .catch(error => console.error('Error:', error));

document.addEventListener("DOMContentLoaded",
    function(){

        // Rent a Car button click event
        document.getElementById("rentCarButton").addEventListener("click", function() {
            window.location.href = '../view/search.html';
        });

        // View Reservations button click event
        document.getElementById("viewReservationsButton").addEventListener("click", function() {
            window.location.href = '../view-reservation.php';
        });

        // Logout link click event
        document.getElementById("logoutLink").addEventListener("click", function(event) {
            event.preventDefault(); // Prevent the default link behavior (navigation)
            // Redirect to logout URL
            window.location.href = '../index.php?logout=1';
        });

        // Put the username
        document.getElementById("username").innerText = username;
    })
