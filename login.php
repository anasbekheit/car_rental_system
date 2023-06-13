<?php
include('server.php'); // Include the server-side code

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    global $db;
    $data = file_get_contents("php://input");
    $form_data = json_decode($data, true);
    $email = mysqli_real_escape_string($db, $form_data['login_email']);
    $password = mysqli_real_escape_string($db, $form_data['login_password']);

    // Validate the user's credentials
    if (validateCredentials($email, $password)) {
        $_SESSION['user'] = getUser($email);
        $_SESSION['success'] = "You are now logged in";
        header("HTTP/1.1 200 OK");
    } else {
        header('Content-Type: application/json');
        header("HTTP/1.1 401 Unauthorized");
    }
    exit();
}

// Function to validate the user's credentials
function validateCredentials($email, $password) {
    global $db;
    if (empty($email) || empty($password)) {
        return false;
    }
    $password = md5($password);
    $query = "SELECT customer_id, email, fname, lname, country, credit_card FROM customer WHERE email='$email' AND customer_pass='$password'";
    $results = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($results);

    return mysqli_num_rows($results) == 1;
}

// Function to fetch user details based on email
function getUser($email) {
    global $db;
    $query = "SELECT customer_id, email, fname, lname, country, credit_card FROM customer WHERE email='$email'";
    $result = mysqli_query($db, $query);
    return mysqli_fetch_assoc($result);
}
