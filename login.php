<?php
//TODO: FIX NAMING CONVENTIONS HTML
//TODO: STYLING IN CSS
require'server.php';

//initializing variables
$email="";
$password="";
$errors=array();

// LOGIN USER
if(isset($_SESSION['user'])){
    header('location: index.php');

}


if (isset($_POST['login_user'])) {
      // receive all input values from the form
    $email = mysqli_real_escape_string($db, $_POST['login_email']);
    $password = mysqli_real_escape_string($db, $_POST['login_password']);
  
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT customer_id, email, fname, lname, country, credit_card FROM customer WHERE email='$email' AND customer_pass='$password' ";
        $results = mysqli_query($db, $query);
        $user = mysqli_fetch_assoc($results);
  
        if (mysqli_num_rows($results) == 1) {
          $_SESSION['user']=$user;
          $_SESSION['success'] = "You are now logged in";
          header('location: index.php');
        }else {
            array_push($errors, "*wrong email/password combination*");
        }
    }
  }

?>



<html>
<head>
    <title>Login</title>
    <script type="text/javascript" src="script.js"></script>
    <link rel="stylesheet" type="text/css" href="css/styling.css">
</head>

<body>
   
    
<div class="main">
        <div class="content">
            <div class="header">
        	    <img src="css/logo/colored/logo-white.svg">
            </div>
            <form class="loginForm" name="loginForm" action="login.php" onsubmit="" method="post">
                <div class="textfields">
                    <label>Email</label>
                    <input type="text" name="login_email">
                </div>

                <div class="textfields">
                    <label>Password</label>
                    <input type="password" name="login_password">
                </div>

                <div class="errors">
                <p><?php include('errors.php'); $errors=array();?></p>
                </div>

                <div class="textfields">
                    <div class="login_button"><button type="submit" class="btn" name="login_user">Login</button></div>
                </div>
                
                <div class="footer">
                    Not a member? <a href="register.php">Register</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>