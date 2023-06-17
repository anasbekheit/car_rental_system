<?php
/**
 * @var mysqli $db
 */
require_once 'server.php';
if (isset($_SESSION['user'])) {
    header('location: ../view/index.html');
    exit;
}
function checkFields($first_name, $last_name, $email, $password_1, $password_2, $country, $credit_card): array
{
    $errors = array();

    // First name validation
    if (empty($first_name)) {
        $errors[] = array("message" => "First name is required", "inputId" => "fname");
    } elseif (!preg_match('/^[A-Za-z]{1,16}$/', $first_name)) {
        $errors[] = array("message" => "First name should contain only English characters and be up to 16 characters long", "inputId" => "fname");
    }

    // Last name validation
    if (empty($last_name)) {
        $errors[] = array("message" => "Last name is required", "inputId" => "lname");
    } elseif (!preg_match('/^[A-Za-z]{1,16}$/', $last_name)) {
        $errors[] = array("message" => "Last name should contain only English characters and be up to 16 characters long", "inputId" => "lname");
    }

    // Email validation
    if (empty($email)) {
        $errors[] = array("message" => "Email is required", "inputId" => "email");
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = array("message" => "Invalid email format", "inputId" => "email");
    }

    // Password validation
    if (empty($password_1)) {
        $errors[] = array("message" => "Password is required", "inputId" => "password");
    } elseif (strlen($password_1) < 8 || !preg_match('/^[A-Za-z0-9]+$/', $password_1)) {
        $errors[] = array("message" => "Password should be at least 8 characters long and contain only alphanumeric characters", "inputId" => "password");
    }

    // Password confirmation
    if ($password_1 != $password_2) {
        $errors[] = array("message" => "Passwords don't match", "inputId" => "confirm_password");
    }

    // Country validation
    $validCountries = array("USA", "Canada", "Egypt", "United Kingdom"); // Replace with your list of valid countries
    if (empty($country)) {
        $errors[] = array("message" => "Country field is required", "inputId" => "country");
    } elseif (!in_array($country, $validCountries)) {
        $errors[] = array("message" => "Invalid country", "inputId" => "country");
    }

    // Credit card validation
    if (empty($credit_card)) {
        $errors[] = array("message" => "Credit card is required", "inputId" => "credit_card");
    } elseif (!validateCreditCard($credit_card)) { // Implement your credit card validation logic
        $errors[] = array("message" => "Invalid credit card", "inputId" => "credit_card");
    }

    if (alreadyExists($email)) { // if user exists
        $errors[] = array("message" => "Email already exists", "inputId" => "email");
    }

    return $errors;
}
function validateCreditCard($credit_card): bool
{
    // Remove any non-digit characters from the credit card number
    $credit_card = preg_replace('/[^0-9]/', '', $credit_card);

    // Check the length of the credit card number
    if (strlen($credit_card) < 13 || strlen($credit_card) > 16) {
        return false;
    }

    // Perform prefix-based validation for some commonly used card types
    $prefixes = array(
        "Visa" => "/^4/",
        "Mastercard" => "/^5[1-5]/",
        "American Express" => "/^3[47]/",
        "Discover" => "/^6(?:011|5)/",
    );

    foreach ($prefixes as $cardType => $prefixPattern) {
        if (preg_match($prefixPattern, $credit_card)) {
            return true;
        }
    }

    // If the credit card number doesn't match any known prefixes, it is invalid
    return false;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // receive all input values from the form
    $data = json_decode(file_get_contents("php://input"));
    $fname = trim($data->fname);
    $lname = trim($data->lname);
    $email = trim($data->email);
    $country = trim($data->country);
    $credit_card = trim($data->credit_card);
    $password_1 = $data->password;
    $password_2 = $data->confirm_password;

    // CHECK THAT THE USER HAS ALL FIELDS ENTERED CORRECTLY
    $errors = checkFields($fname, $lname, $email, $password_1, $password_2, $country, $credit_card);

    // Finally, register user if there are no errors in the form
    if (empty($errors)) {
        $user = registerUser($fname, $lname, $email, $password_1, $country, $credit_card);
        $_SESSION['user'] = $user;
        $_SESSION['success'] = "You are now registered and logged in";
        echo json_encode($errors);
        header('location: index.php');
        exit;
    }
    echo json_encode($errors);
}
