<?php
if (session_id() === '') {
    session_start(); // Only start the session if it is not already active
}
$errors = array();
require 'errors.php';

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'car_rental_system');

// Function to check if a user with the given email already exists
function alreadyExists($email): bool
{
    global $db;

    $query = "SELECT * FROM customer WHERE email=?";
    $statement = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($statement, "s", $email);
    mysqli_stmt_execute($statement);
    mysqli_stmt_store_result($statement);

    return mysqli_stmt_num_rows($statement) > 0;
}

// Function to insert a new user into the database
function registerUser($fname, $lname, $email, $password, $country, $credit_card): array
{
    global $db;

    $hashedPassword = md5($password);

    $query = "INSERT INTO customer (fname, lname, email, customer_pass, country, credit_card) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $statement = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($statement, "ssssss", $fname, $lname, $email, $hashedPassword, $country, $credit_card);
    mysqli_stmt_execute($statement);

    $user = [
        'email' => $email,
        'fname' => $fname,
        'lname' => $lname,
        'country' => $country,
        'credit_card' => $credit_card
    ];

    return $user;
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
    $statement = mysqli_prepare($db, $query);
    mysqli_stmt_bind_param($statement, "ss", $email, $hashedPassword);
    mysqli_stmt_execute($statement);
    mysqli_stmt_store_result($statement);

    if (mysqli_stmt_num_rows($statement) == 1) {
        mysqli_stmt_bind_result($statement, $customer_id, $email, $fname, $lname, $country, $credit_card);
        mysqli_stmt_fetch($statement);

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
