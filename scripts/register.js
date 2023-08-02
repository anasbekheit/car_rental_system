import {
    isValidName,
    isValidEmail,
    isValidPassword,
    isValidCountry,
    isValidCreditCard,
    displayErrors
} from "./util.js";

function validateForm() {
    const errors = [];

    const firstName = document.getElementById('fname').value.trim();
    const lastName = document.getElementById('lname').value.trim();
    const email = document.getElementById('email').value.trim();
    const password1 = document.getElementById('password').value;
    const password2 = document.getElementById('confirm_password').value;
    const country = document.getElementById('country').value.trim();
    const creditCard = document.getElementById('credit_card').value.trim();

    if (firstName === '') {
        errors.push({ message: 'First name is required', inputId: 'fname' });
    } else if (!isValidName(firstName)) {
        errors.push({
            message: 'First name should only contain English characters and have a maximum length of 16',
            inputId: 'fname'
        });
    }

    if (lastName === '') {
        errors.push({ message: 'Last name is required', inputId: 'lname' });
    } else if (!isValidName(lastName)) {
        errors.push({
            message: 'Last name should only contain English characters and have a maximum length of 16',
            inputId: 'lname'
        });
    }

    if (email === '') {
        errors.push({ message: 'Email is required', inputId: 'email' });
    } else if (!isValidEmail(email)) {
        errors.push({ message: 'Invalid email', inputId: 'email' });
    }

    if (password1 === '') {
        errors.push({ message: 'Password is required', inputId: 'password' });
    } else if (!isValidPassword(password1)) {
        errors.push({
            message: 'Password should be at least 8 characters long and contain only alphanumeric characters',
            inputId: 'password'
        });
    } else if (password1 !== password2) {
        errors.push({ message: "Passwords don't match", inputId: 'confirm_password' });
    }

    if (country === '') {
        errors.push({ message: 'Country is required', inputId: 'country' });
    } else if (!isValidCountry(validCountries, country)) {
        errors.push({ message: 'Invalid country', inputId: 'country' });
    }

    if (creditCard === '') {
        errors.push({ message: 'Credit card is required', inputId: 'credit_card' });
    } else if (!isValidCreditCard(creditCard)) {
        errors.push({ message: 'Invalid credit card', inputId: 'credit_card' });
    }

    return errors;
}
function fillCountries() {
    // Get the select element
    const countrySelect = document.getElementById('country');

    // Generate the options for the select element
    validCountries.forEach(country => {
        const option = document.createElement('option');
        option.value = country;
        option.textContent = country;
        countrySelect.appendChild(option);
    });
}
async function registerUser(formData) {
    try {
        const response = await fetch('../logic/register.php', {
            method: 'POST',
            body: formData
        });

        if (response.redirected) {
            window.location.href = '../view/index.html';
            return Promise.reject('Redirection occurred');
        }

        const errors = await response.json();
        errors && displayErrors(errors);
    } catch (error) {
        console.error('Error:', error);
    }
}


// Array of valid countries
const validCountries = [
    'USA',
    'Canada',
    'United Kingdom',
    'Egypt'
    // Add more valid countries here
];

if(sessionStorage.getItem('loggedIn') === 'true'){
    window.location.href = '../view/index.html';
}
document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registrationForm');
    const errorMessages = document.getElementById('errorMessages');
    fillCountries();

    registerForm.addEventListener('submit', function(event) {
        event.preventDefault();
        errorMessages.innerHTML = '';
        const errors = validateForm();
        if (errors.length > 0) {
            displayErrors(errors);
            return;
        }
        let inputs = registerForm.getElementsByClassName("input");
        let formData = {};
        for(let i=0; i< inputs.length; i++){
            formData[inputs[i].name] = inputs[i].value;
        }
        let json = JSON.stringify(formData);
        registerUser(json);
    });
});