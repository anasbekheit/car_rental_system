<?php
//TODO: FIX NAMING CONVENTIONS HTML
//TODO: STYLING IN CSS
require 'server.php';

if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}
if(isset($_POST['add_car']))
{
    header("location: addCar.php");
}
if(isset($_POST['edit_car']))
{
    header("location: searchCar.php");
}
if(isset($_POST['advanced_search']))
{
    header("location: advancedSearch.php");
}
if(isset($_POST['report']))
{
    header("location: reports.php");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="css\index-styling.css">
</head>
<body>
<div class="header">
    <div class="topbar">
        <a href="index.php"><img class="logo" src="../css/logo/colored/logo-white.svg"></a>
        <!-- logged in user information -->
        <?php  if (isset($_SESSION['admin_user'])) : ?>
            <div class="welcome">
                <h3><strong>Basha</strong></h3>
                <h3 style="color:blue;"> <a class="logoutText" href="index.php?logout='1'" >Log out</a> </h3>
            </div>
        <?php endif ?>
        <!-- notification message -->
        <?php if (isset($_SESSION['success'])) : ?>
            <div class="error_success" >
                <h3>
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </h3>
            </div>
        <?php endif ?>
    </div>

</div>

<div class="content">
    <div class="title">
        <h2>Options</h2>
    </div>
    <form method="post">
        <div class="user_button"> <button type="submit" class="btn" name="add_car">Add car</button> </div>
        <div class="user_button"> <button type="submit" class="btn" name="edit_car">Edit car</button> </div>
        <div class="user_button"> <button type="submit" class="btn" name="advanced_search">Advanced Search</button> </div>
        <div class="user_button"> <button type="submit" class="btn" name="report">Reports</button> </div>
    </form>
</div>
</body>
</html>