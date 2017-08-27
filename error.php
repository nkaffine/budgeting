<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/10/17
 * Time: 8:36 PM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        showerror($connection);
    }
    if(count($_GET)){
        $message = validInputSizeAlpha($_GET['message'], 1000);
    }
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Error</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!--Stuff for selectors-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="style_sheets/main.css">
</head>
<body>
<?php
if(isset($message)){
    echo"<div class='col-lg-6 col-lg-offset-3' style='margin-top: 2%;'><div class='panel panel-danger'>
                    <div class='panel-heading'>Alert:</div>
                    <div class='panel-body'>{$message}</div>
                </div></div>";
}
?>
</body>
</html>

