<?php
// mysqli_report(MYSQLI_REPORT_ALL);
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

if (session_id() === '') {
    session_start(); // Only start the session if it is not already active
}
$errors = array();
// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'car_rental_system');
// Function to check if a user with the given email already exists
function alreadyExists($email): bool
{
    global $db;
    $query = "SELECT * FROM customer WHERE email=?";
    $statement = $db->prepare($query);
    $statement->bind_param("s", $email);
    $statement->execute();
    $statement->store_result();
    return $statement->num_rows() > 0;
}

// Function to insert a new user into the database
function registerUser($fname, $lname, $email, $password, $country, $credit_card): array
{
    global $db;

    $hashedPassword = md5($password);

    $query = "INSERT INTO customer (fname, lname, email, customer_pass, country, credit_card) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $statement = $db->prepare($query);
    $statement->bind_param("ssssss", $fname, $lname, $email, $hashedPassword, $country, $credit_card);
    $statement->execute();

    return [
        'email' => $email,
        'fname' => $fname,
        'lname' => $lname,
        'country' => $country,
        'credit_card' => $credit_card
    ];
}

// Function to validate the user's credentials
function validateCredentials($email, $password)
{
    global $db;

    if (empty($email) || empty($password)) {
        return null;
    }

    $hashedPassword = md5($password);

    $query = "SELECT customer_id, email, fname, lname, country, credit_card FROM customer WHERE email=? AND customer_pass=?";
    $statement = $db->prepare($query);
    $statement->bind_param("ss", $email, $hashedPassword);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows() == 1) {
        $statement->bind_result($customer_id, $email, $fname, $lname, $country, $credit_card);
        $statement->fetch();
        $user = [
            'email' => $email,
            'fname' => $fname,
            'lname' => $lname,
            'country' => $country,
            'credit_card' => $credit_card
        ];

        return $user;
    }
    return null;
}
