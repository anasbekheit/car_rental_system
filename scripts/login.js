document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', handleLoginFormSubmit);
});

async function handleLoginFormSubmit(event) {
    event.preventDefault();
    const formData = getFormDataFromLoginForm();

    try {
        const response = await login(formData);
        response.ok ? handleSuccessfulLogin(response.url) : displayLoginError();
    } catch (error) {
        console.error('Error:', error);
    }
}

function getFormDataFromLoginForm() {
    const email = document.getElementById('login_email').value;
    const password = document.getElementById('login_password').value;
    return JSON.stringify({login_email: email, login_password: password});
}

async function login(formData) {
    return fetch('../logic/login.php', {
        method: 'POST',
        body: formData
    });
}

function handleSuccessfulLogin(redirectUrl) {
    window.location.href = redirectUrl;
    sessionStorage.setItem('loggedIn', 'true');
}

function displayLoginError() {
    const error = document.getElementById('error-message');
    error.innerText = "Incorrect username and/or password";
}
