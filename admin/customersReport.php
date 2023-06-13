<?php
require_once 'server.php';

if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}

$column="";
$value="";
$rows=array();

if(isset($_GET['customer_report']))
{
    $column=mysqli_real_escape_string($db,$_GET['column']);
    $value=mysqli_real_escape_string($db,$_GET['value']);
    $query="SELECT * from (reservation as R JOIN customer as C ON R.customer_id = C.customer_id)  JOIN car as C2 ON R.plate_id = C2.plate_id WHERE ".$column." = '$value'";
    $results=mysqli_query($db,$query);
    if($results && $results->num_rows>0){
        $rows=$results->fetch_all(MYSQLI_ASSOC);
    }

}
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href=".\css\style.css">
</head>
<body>
    
<div class="header">
    <div class="topbar">
        <a href="index.php"><img class="logo" src="../css/logo/colored/logo-white.svg"></a>
        <!-- logged in user information -->
        <?php if (isset($_SESSION['admin_user'])) : ?>
            <div class="welcome">
                <h3><strong>Basha</strong></h3>
                <h3 style="color:blue;"><a class="logoutText" href="index.php?logout='1'">Log out</a></h3>
            </div>
        <?php endif ?>
    </div>

</div>
<form name="form1" id="form1" action="customersReport.php"  method="get">

    Search by: <select name="column" id="column">
        <option value="customer_id">customer id</option>
        <option value="fname">customer first name</option>
        <option value="lname">customer last name</option>
        <option value="email">customer email</option>
        <option value="country">country</option>
    </select>
    <br><br>
    Value:
    <input type="text" id="value" name="value">
    <button type="submit" class="btn" name="customer_report">Search</button>
    <?php if(!empty($rows)):?>
        <div class="results">
            <?php foreach ($rows as $row):?>
                <p><?php echo json_encode($row)?></p>
            <?php endforeach;?>
        </div>
    <?php else:?>
        <p>    <?php echo "no results"?></p>
    <?php endif ?>
</form>
</body>
</html>
