
<?php

//TODO: FIX NAMING CONVENTIONS
//TODO: STYLING REMAINING

require_once 'server.php';
// initializing variables
$fname = "";
$lname = "";
$email = "";
$password_1 = "";
$password_2 = "";
$country = "";
$credit_card = "";

// REGISTER USER
if (isset($_POST['reg_user'])) {
    //ZERO ERROR START
    $errors = array();
    // receive all input values from the form
    $fname = mysqli_real_escape_string($db, $_POST['fname']);
    $lname = mysqli_real_escape_string($db, $_POST['lname']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password']);
    $password_2 = mysqli_real_escape_string($db, $_POST['confirm_password']);
    $country = mysqli_real_escape_string($db, $_POST['country']);
    $credit_card = mysqli_real_escape_string($db, $_POST['credit_card']);
    //CHECK THAT THE USER HAS ALL FIELDS ENTERED CORRECTLY
    if (empty($fname)) {
        array_push($errors, "First name is required");
    }
    if (empty($lname)) {
        array_push($errors, "Last name is required");
    }
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password_1)) {
        array_push($errors, "Password is required");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "Passwords don't match");
    }
    if (empty($country)) {
        array_push($errors, "Country field is required");
    }
    if (empty($credit_card)) {
        array_push($errors, "Credit card is required");
    }


    // first check the database to make sure
    // a user does not already exist with the same email
    $user_check_query = "SELECT * FROM customer WHERE email='$email' ";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists

        array_push($errors, "email already exists");

    }

    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        $password = md5($password_1);//encrypt the password before saving in the database

        $query = "INSERT INTO customer (fname,lname, email, customer_pass, country, credit_card) 
  			  VALUES('$fname','$lname', '$email', '$password','$country','$credit_card')";
        mysqli_query($db, $query);
        $query = "SELECT * FROM customer WHERE email='$email' ";
        $result = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user'] = $user;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>Register</title>
    <script type="text/javascript" src="script.js"></script>

</head>
<body>

<div class="main">
    <div class="contentRegister">
        <div class="headerRegister">
            <h1>Register</h1>
        </div>
        <form name="registerForm" action="register.php" onsubmit="return validateRegisterForm()" method="post">
            <?php include('errors.php'); ?>
            <div class="textfields">
                <label>First Name:</label>
                <input type="text" name="fname">
            </div>
            <div class="textfields">
                <label>Last Name</label>
                <input type="text" name="lname">
            </div>
            <div class="textfields">
                <label>Email</label>
                <input type="text" name="email">
            </div>
            <div class="textfields">
                <label>Password</label>
                <input type="password" name="password">
            </div>
            <div class="textfields">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password">
            </div>
            <div class="textfields">
                <label>Country</label>
                <input type="text" name="country">
            </div>
            <div class="textfields">
                <label>Credit Card</label>
                <input type="text" name="credit_card">
            </div>
            <div class="textfields">
                <button type="submit" class="btn" name="reg_user">Register</button>
            </div>
            <div class="footer">
                Already a member? <a href="login.php">Log in</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>