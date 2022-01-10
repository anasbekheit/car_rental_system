<?php

session_start();
$errors=array();
require 'errors.php';
// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'crs');

?>