document.addEventListener('DOMContentLoaded', function() {
    // Get the login form element
    const loginForm = document.getElementById('loginForm');

    // Add event listener for form submission
    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get the input field values
        const email = document.getElementById('login_email').value;
        const password = document.getElementById('login_password').value;

        // Create an object to send the form data
        const formData = {
            login_email: email,
            login_password: password
        };
        // Send an AJAX request to the login.php file
        fetch('../logic/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
            .then(response => {
                if (response.ok) {
                    // Redirect to the desired page after successful login
                    window.location.href = "index.html";
                } else {
                    const error = document.getElementById('error-message');
                    error.innerText = "Incorrect username and/or password";
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
            });
    });
});
