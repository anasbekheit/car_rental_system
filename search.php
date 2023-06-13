<?php
/**
 * @var object $db
 */
require_once 'server.php';

if (!isset($_SESSION['user'])) {
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: login.php");
}

$rows = array();


/**
 * @param $car_model
 * @param $car_price
 * @param $car_manufacturer
 * @param $car_country
 * @param $car_color
 * @return string
 */
function getWhere($car_model, $car_price, $car_manufacturer, $car_country, $car_color)
{
    $where = "";
    if (!empty($car_model)) {
        $where .= "AND car_model = '$car_model' ";
    }
    if (!empty($car_price)) {
        $where .= "AND price_per_day <= '$car_price' ";
    }
    if (!empty($car_manufacturer)) {
        $where .= "AND car_manufacturer ='$car_manufacturer' ";
    }
    if (!empty($car_country)) {
        $where .= "AND country='$car_country' ";
    }
    if (!empty($car_color)) {
        $where .= "AND color= '$car_color' ";
    }
    return $where;
}


// receive all input values from the form
$car_model = isset($_POST['car_model']) ? mysqli_real_escape_string($db, $_POST['car_model']) : "";
$car_price = isset($_POST['car_price']) ? mysqli_real_escape_string($db, $_POST['car_price']) : "";
$car_manufacturer = isset($_POST['car_manufacturer']) ? mysqli_real_escape_string($db, $_POST['car_manufacturer']) : "";
$car_color = isset($_POST['car_color']) ? mysqli_real_escape_string($db, $_POST['car_color']) : "";
$car_country = isset($_POST['car_country']) ? mysqli_real_escape_string($db, $_POST['car_country']) : "";
$from_date = isset($_POST['from_date']) ? mysqli_real_escape_string($db, $_POST['from_date']) : "";
$to_date = isset($_POST['to_date']) ? mysqli_real_escape_string($db, $_POST['to_date']) : "";

$query = "SELECT plate_id,car_model,car_manufacturer,color,model_year,country,price_per_day,car_status,car_image FROM car  WHERE car_status = 'active' ";
$where = getWhere($car_model, $car_price, $car_manufacturer, $car_country, $car_color);
$query .= $where;
$query .= "EXCEPT (SELECT plate_id,car_model,car_manufacturer,color,model_year,country,price_per_day,car_status,car_image
FROM reservation NATURAL JOIN car WHERE '$from_date' BETWEEN pickup_time AND return_time OR '$to_date' BETWEEN pickup_time AND return_time)";
$results = mysqli_query($db, $query);

if ($results) {
    $rows = $results->fetch_all(MYSQLI_ASSOC);
    $_SESSION['from_date'] = $from_date;
    $_SESSION['to_date'] = $to_date;

}
// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($rows);