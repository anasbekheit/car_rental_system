<?php
/**
 * @var mysqli $db
 */
$sampleCars = null;
$allowed = ['retrieveAvailableCars', 'search'];
require_once 'server.php';
if (!isset($_SESSION['user'])) {
    header('location: login.php');
}
if(in_array($_GET['action'], $allowed)){
    echo json_encode(call_user_func($_GET['action']) ?? "");
}

function retrieveAvailableCars(){
    global $db, $sampleCars;
    $query = "SELECT car_model, car_manufacturer FROM car WHERE car_status = 'active'";
    $statement = $db->prepare($query);
    if(!$sampleCars){
        if ($statement) {
            $statement->execute();
            $result = $statement->get_result();
            $sampleCars = $result->fetch_all(MYSQLI_ASSOC);
            $statement->close();
            $db->close();
        }else{
            $error = $db->error;
            // Log or display the error message
            error_log("Error preparing the statement: " . $error);
        }
    }
    return $sampleCars;
}

function search(){
    global $db;
    // receive all input values from the form
    $car_model = $_GET['car_model'] ?? "";
    $car_price = $_GET['car_price'] ?? "";
    $car_manufacturer = $_GET['car_manufacturer'] ?? "";
    $car_color = $_GET['car_color'] ?? "";
    $car_country = $_GET['car_country'] ?? "";
    $from_date = $_GET['from_date'] ?? "";
    $to_date = $_GET['to_date'] ?? "";

    $query = "SELECT plate_id, car_model, car_manufacturer, color, model_year, country, price_per_day, car_status, car_image FROM car WHERE car_status = 'active' ";
    $whereData = get_where($car_model, $car_price, $car_manufacturer, $car_country, $car_color);
    $query .= $whereData['where'];
    $query .= " EXCEPT (SELECT plate_id, car_model, car_manufacturer, color, model_year, country, price_per_day, car_status, car_image FROM reservation NATURAL JOIN car " . get_data_time($from_date, $to_date) . ")";
// Bind the parameters
    $dataTypes = str_repeat("s", count($whereData['params']) + 2);
    $var_args = array_merge($whereData['params'], [$from_date, $to_date]);

    $statement = $db->prepare($query);

    if ($statement) {
        $statement->bind_param($dataTypes, ...$var_args);
        $statement->execute();
        $result = $statement->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $statement->close();
        $db->close();

        // Set the from & to dates so you can charge the customer.
        $_SESSION['from_date'] =$from_date;
        $_SESSION['to_date'] = $to_date;
    }else{
        $error = $db->error;
        // Log or display the error message
        error_log("Error preparing the statement: " . $error);
    }
    return $rows?? null;

}
/**
 * @param $car_model
 * @param $car_price
 * @param $car_manufacturer
 * @param $car_country
 * @param $car_color
 * @return array
 */
function get_where($car_model, $car_price, $car_manufacturer, $car_country, $car_color): array
{
    $where = "";
    $params = array();

    if (!empty($car_model)) {
        $where .= " AND car_model = ? ";
        $params[] = $car_model;
    }
    if (!empty($car_price)) {
        $where .= " AND price_per_day <= ? ";
        $params[] = $car_price;
    }
    if (!empty($car_manufacturer)) {
        $where .= " AND car_manufacturer = ? ";
        $params[] = $car_manufacturer;
    }
    if (!empty($car_country)) {
        $where .= " AND country = ? ";
        $params[] = $car_country;
    }
    if (!empty($car_color)) {
        $where .= " AND color = ? ";
        $params[] = $car_color;
    }
    return array(
        'where' => $where,
        'params' => $params
    );
}

function get_data_time($from_date, $to_date): string
{
    $query = "";

    if (!empty($from_date) && !empty($to_date)) {
        $query = "WHERE ? BETWEEN pickup_time AND return_time OR ? BETWEEN pickup_time AND return_time";
    }
    return $query;
}

