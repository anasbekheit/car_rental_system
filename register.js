// Array of valid countries
const validCountries = [
    'USA',
    'Canada',
    'United Kingdom',
    'Egypt'
    // Add more valid countries here
];
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
    } else if (!isValidCountry(country)) {
        errors.push({ message: 'Invalid country', inputId: 'country' });
    }

    if (creditCard === '') {
        errors.push({ message: 'Credit card is required', inputId: 'credit_card' });
    } else if (!isValidCreditCard(creditCard)) {
        errors.push({ message: 'Invalid credit card', inputId: 'credit_card' });
    }

    return errors;
}

function displayErrors(errors) {
    // Remove any existing errors message for the input field
    const existingErrors = document.querySelectorAll('.error');
    existingErrors.forEach(error=>error.remove());
    errors.forEach(function (error) {
        const errorElement = document.createElement('p');
        errorElement.classList.add('error');
        errorElement.textContent = error['message'];

        // Get the input field and its parent element
        const inputField = document.getElementById(error['inputId']);
        const parentElement = inputField.parentElement;
        // Append the error message after the input field
        parentElement.appendChild(errorElement);
    });
}


function registerUser(formData) {

    fetch('register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: formData
    },
        )
        .then(response => {
            if(!response.redirected){
              return response.json();
            }else{
                window.location.href = 'index.php';
                return Promise.reject('Redirection occurred');
            }
        })
        .then(errors => errors && displayErrors(errors))
        .catch(error => console.error('Error:', error));
}

function isValidName(name) {
    const regex = /^[a-zA-Z]{1,16}$/;
    return regex.test(name);
}

function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

function isValidPassword(password) {
    const regex = /^[a-zA-Z0-9]{8,}$/;
    return regex.test(password);
}

function isValidCountry(country) {
    return validCountries.includes(country);
}

function isValidCreditCard(creditCard) {
    // Remove any non-digit characters from the credit card number
    creditCard = creditCard.replace(/[^0-9]/g, '');

    // Check the length of the credit card number
    if (creditCard.length < 13 || creditCard.length > 16) {
        return false;
    }

    // Perform prefix-based validation for some commonly used card types
    const prefixes = {
        Visa: /^4/,
        Mastercard: /^5[1-5]/,
        'American Express': /^3[47]/,
        Discover: /^6(?:011|5)/
    };

    for (const [cardType, prefixPattern] of Object.entries(prefixes)) {
        if (prefixPattern.test(creditCard)) {
            return true;
        }
    }

    // If the credit card number doesn't match any known prefixes, it is invalid
    return false;
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