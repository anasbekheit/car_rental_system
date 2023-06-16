<?php
/**
 * @var mysqli $db
 */
include('server.php'); // Include the server-side code

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents("php://input");
    $form_data = json_decode($data, true);
    $email = mysqli_real_escape_string($db, $form_data['login_email']);
    $password = mysqli_real_escape_string($db, $form_data['login_password']);

    // Validate the user's credentials
    $user = validateCredentials($email, $password);
    if ($user !== null) {
        $_SESSION['user'] = $user;
        header("HTTP/1.1 200 OK");
    } else {
        header('Content-Type: application/json');
        header("HTTP/1.1 401 Unauthorized");
    }
    exit;
}
