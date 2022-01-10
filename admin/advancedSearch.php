<?php
require 'server.php';

if(!isset($_SESSION['admin_user'])){
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_user']);
    header("location: login.php");
}

$options_array=array();
$table="";
$column="";
$value="";
$rows=array();


if(isset($_POST['advanced_search']))
{
    $table=mysqli_real_escape_string($db,$_POST['table']);
    $column=mysqli_real_escape_string($db,$_POST['column']);
    $value=mysqli_real_escape_string($db,$_POST['search_value']);
    $query="SELECT * FROM ".$table." WHERE ".$column." LIKE \"%"."$value%\"";

    echo $query;
    $results=mysqli_query($db,$query);

    if($results && $results->num_rows>0){
    $rows=$results->fetch_all(MYSQLI_ASSOC);
        if(strcmp($table,"customer")==0){
            $_SESSION['search_customer']=$rows;
        }
        if(strcmp($table,"car")==0){
            $_SESSION['search_car']=$rows;
        }
        if(strcmp($table,"reservation")==0){
            $_SESSION['search_reservation']=$rows;
        }

  }
}

?>
<html>
<head>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        // This is our actual script
        $(document).ready(function(){
            $('#table').change(function() {
                var options=[];
                var optionsValues=[];
                // window.location.href = window.location.href + '?value=' + this.value;
                var table=$("#table option:selected").text();
                if(table.localeCompare("car")==0)
                {
                    options = ["plate id","car model","car manufacturer","model year","price per day","country","color","car status"];
                    optionsValues = ["plate_id","car_model","car_manufacturer","model_year","price_per_day","country","color","car_status"];

                }
                if(table.localeCompare("customer")==0)
                {
                    options = ["customer id","customer first name","customer last name","customer email","country"];
                    optionsValues = ["customer_id","fname","lname","email","country"];

                }
                if(table.localeCompare("reservation")==0)
                {
                    options = ["reservation id","car plate id","customer id","pickup time","return time","reservation time","total amount"];
                    optionsValues = ["reservation_id","plate_id","customer_id","pickup_time","return_time","reservation_time","total_amount"];

                }
                var min = 0;

                select = document.getElementById('column');
                while (select.lastChild) {
                    select.removeChild(select.lastChild);
                }

                for (var i = min; i<options.length; i++){
                    var opt = document.createElement('option');
                    opt.value = optionsValues[i];
                    opt.innerHTML = options[i];
                    select.appendChild(opt);
                }

            });
        });

    </script>

</head>
<body>
<form name="form1" id="form1" action="advancedSearch.php"  method="post">
    Search for a: <select name="table" id="table">
        <option value="customer" selected="selected">customer</option>
        <option value="reservation" selected="selected">reservation</option>
        <option value="car" selected="selected">car</option>
    </select>
    <br><br>

    Search by: <select name="column" id="column">
        <?php foreach($options_array as $option): ?>
        <option value="option" selected="selected"><?php echo $option; ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>
    Value:
        <input type="text" id="value" name="search_value">
    <button type="submit" class="btn" name="advanced_search">Search</button>
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


