<?php
// Start the session
require_once 'server.php';
// Get the variable name and value from the AJAX request
$variableName = $_POST['variableName'];
$variableValue = $_POST['variableValue'];
// Set the session variable
$_SESSION[$variableName] = $variableValue;
// Return a response
http_response_code(200);