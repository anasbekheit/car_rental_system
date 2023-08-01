export function updateUsername(username) {
    document.getElementById("username").innerText = username;
}
export function logout() {
    sessionStorage.removeItem('loggedIn');
    window.location.href = '../logic/index.php?logout=1';
}
export function displayErrors(errors) {
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
export function isValidName(name) {
    const regex = /^[a-zA-Z]{1,16}$/;
    return regex.test(name);
}
export function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}
export function isValidPassword(password) {
    const regex = /^[a-zA-Z0-9]{8,}$/;
    return regex.test(password);
}
export function isValidCountry(validCountries, country) {
    return validCountries.includes(country);
}
export function isValidCreditCard(creditCard) {
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

    for (const [_, prefixPattern] of Object.entries(prefixes)) {
        if (prefixPattern.test(creditCard)) {
            return true;
        }
    }

    // If the credit card number doesn't match any known prefixes, it is invalid
    return false;
}
export function isValidDate(dateString) {
    // First check for the pattern
    //Function checks against regex pattern for mm/dd/yyyy
    // In reality the default format is yyyy-mm-dd
    if(!/^\d{4}-\d{1,2}-\d{1,2}$/.test(dateString))
        return false;

    // Parse the date parts to integers
    const parts = dateString.split("-");
    const day = parseInt(parts[2], 10);
    const month = parseInt(parts[1], 10);
    const year = parseInt(parts[0], 10);

    // Check the ranges of month and year
    if(year < 1000 || year > 3000 || month < 1 || month > 12)
        return false;

    const monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

    // Adjust for leap years
    if(year % 400 === 0 || (year % 100 !== 0 && year % 4 === 0))
        monthLength[1] = 29;

    // Check the range of the day
    return day > 0 && day <= monthLength[month - 1];
}
