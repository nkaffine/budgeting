<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/13/17
 * Time: 11:00 AM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("9-1-7");
    }
    if(count($_POST)){
        $type = validNumbers($_POST['type'],1);
    }
    $user_id = logincheck("9-2", "9-3");
    $menu = getHeaderInfo("9-4", "9-5");
    switch($type){
        case 0:
            $account_type = "Account";
            break;
        case 1:
            $account_type = "Spending Category";
            break;
        case 2:
            $account_type = "Earning Category";
            break;
        case 3:
            $account_type = "Accounts Receivable Account";
            break;
        case 4:
            $account_type = "Accounts Payable Account";
            break;
        default:
            break;
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>New Account</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!--Stuff for selectors-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="style_sheets/main.css">
        <style>
            .valign{
                transform: translateY(-100%);
            }
        </style>
    </head>
    <body>
        <?php placeHeader($menu) ?>
        <form class="box col-lg-4 col-lg-offset-4 col-xs-10 col-xs-offset-1" action="process/newAccount.php" method="get">
            <?php echo"<h1>New {$account_type}</h1>";?>
            <?php echo"<label for='name'>{$account_type} Name</label>"; ?>
            <input class="form-control" type="text" name="name" value="">
            <?php
                echo"<input type='hidden' name='account_type' value='{$type}'>";
                if($type == 0) {
                    echo "<label for='balance'>Account Balance</label>
                    <input class='form-control' type='number' name='balance' step='.01' value='0'>
                    <label for='type'>Account Type</label>
                    <select class='form-control selectpicker' name='balance_type'>
                        <option value='0' selected='selected'>Debit</option>
                        <option value='1'>Credit</option>
                    </select>";
                } else if($type == 3 || $type == 4){
                    echo"<label for='balance'>Account Balance</label>
                        <input class='form-control' type='number' step='.01' name='balance' value='0'>";
                }
                if($type == 3){
                    echo"<input type='hidden' name='balance_type' value='0'>";
                }
                if($type == 4){
                    echo"<input type='hidden' name='balance_type' value='1'>";
                }
            ?>
            &nbsp;
            <input class="form-control btn btn-primary" type="submit" value="Submit">
            &nbsp;
        </form>
        <div class='col-lg-12' style="height:20vh;></div>
    </body>
</html>
