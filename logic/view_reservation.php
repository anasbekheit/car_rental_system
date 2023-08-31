<?php
/**
 * @var mysqli $db
 */

require_once 'server.php';

if(!isset($_SESSION['user'])){
    header('location: ../view/login.html');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: ../view/login.html");
}

$customer_id=$_SESSION['user']['customer_id'];
$query= "SELECT reservation_id,plate_id, pickup_time, return_time, total_amount, color, model_year, car_model, car_manufacturer, country, reservation_time
        FROM reservation NATURAL JOIN car
        WHERE customer_id='$customer_id'";

$rows=array();

$results = mysqli_query($db, $query);
if($results && $results->num_rows>0){
    $rows=$results->fetch_all(MYSQLI_ASSOC);
    echo json_encode($rows);
}