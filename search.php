<?php
/**
 * @var mysqli $db
 */
require_once 'server.php';

if (!isset($_SESSION['user'])) {
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: login.php");
}

/**
 * @param $car_model
 * @param $car_price
 * @param $car_manufacturer
 * @param $car_country
 * @param $car_color
 * @return array
 */
function getWhere($car_model, $car_price, $car_manufacturer, $car_country, $car_color): array
{
    $where = "";
    $params = array();

    if (!empty($car_model)) {
        $where .= "AND car_model = ? ";
        $params[] = $car_model;
    }
    if (!empty($car_price)) {
        $where .= "AND price_per_day <= ? ";
        $params[] = $car_price;
    }
    if (!empty($car_manufacturer)) {
        $where .= "AND car_manufacturer = ? ";
        $params[] = $car_manufacturer;
    }
    if (!empty($car_country)) {
        $where .= "AND country = ? ";
        $params[] = $car_country;
    }
    if (!empty($car_color)) {
        $where .= "AND color = ? ";
        $params[] = $car_color;
    }

    // Prepare the WHERE clause
    $where = ltrim($where, "AND ");
    $where = $where ? "WHERE $where" : "";

    return array(
        'where' => $where,
        'params' => $params
    );
}

// receive all input values from the form
$car_model = $_POST['car_model'] ?? "";
$car_price = $_POST['car_price'] ?? "";
$car_manufacturer = $_POST['car_manufacturer'] ?? "";
$car_color = $_POST['car_color'] ?? "";
$car_country = $_POST['car_country'] ?? "";
$from_date = $_POST['from_date'] ?? "";
$to_date = $_POST['to_date'] ?? "";

$query = "SELECT plate_id,car_model,car_manufacturer,color,model_year,country,price_per_day,car_status,car_image FROM car  WHERE car_status = 'active' ";
$whereData = getWhere($car_model, $car_price, $car_manufacturer, $car_country, $car_color);
$query .= $whereData['where'];
$query .= "EXCEPT (SELECT plate_id,car_model,car_manufacturer,color,model_year,country,price_per_day,car_status,car_image
FROM reservation NATURAL JOIN car WHERE ? BETWEEN pickup_time AND return_time OR ? BETWEEN pickup_time AND return_time)";

$statement = mysqli_prepare($db, $query);

if ($statement) {
    // Bind the parameters
    $bindParams = array_merge(array(str_repeat("s", count($whereData['params']))), $whereData['params'], array($from_date, $to_date));
    mysqli_stmt_bind_param($statement, ...$bindParams);

    // Execute the statement
    mysqli_stmt_execute($statement);

    // Get the results
    $results = mysqli_stmt_get_result($statement);

    if ($results) {
        $rows = $results->fetch_all(MYSQLI_ASSOC);
        $_SESSION['from_date'] = $from_date;
        $_SESSION['to_date'] = $to_date;

    }
}
// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($rows ?? []);
include 'view/search.html';