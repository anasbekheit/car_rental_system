<?php
if (session_id() === '') {
    session_start(); // Only start the session if it is not already active
}
$errors=array();
require 'errors.php';
// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'car_rental_system');

