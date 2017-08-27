<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 12:43 PM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("15-1-7");
    }
    $user_id = logincheck("15-2", "15-3");
    $menu = getHeaderInfo("15-4");
    if(count($_POST)){
        if(isset($_POST['id'])){
            $account_id = validNumbers($_POST['id'], 10);
        }
        if(isset($_POST['curr_balance'])){
            $curr_balance = validNumbers($_POST['curr_balance'], 10);
        }
        if(isset($_POST['init_balance'])){
            $init_balance = validNumbers($_POST['init_balance'], 10);
        }
        if(isset($_POST['name'])){
            $name = validInputSizeAlpha($_POST['name'], 255);
        }
        if(isset($_POST['type'])){
            $type = validNumbers($_POST['type'], 1);
        }
    }
    if(!isset($type)|| !isset($account_id) || !isset($name)){
        error("15-5-1");
    }
    switch($type){
        case 0:
            if(!isset($curr_balance) || !isset($init_balance)){
                error("15-6-1");
            }
            break;
        case 1:
            break;
        case 2:
            break;
        case 3:
            break;
        case 4:
            break;
        default:
            error("15-7-2");
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Transactions</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!--Stuff for selectors-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
        <script src="scripts/main.js"></script>
        <link rel="stylesheet" href="style_sheets/main.css">
        <script src="scripts/transactions.js"></script>
        <script src="scripts/timeFormatting.js"></script>
    </head>
    <body>
        <?php placeHeader($menu)?>
        <div class="box col-lg-4 col-lg-offset-4">
            <form action="process/editAccount.php" method="post">
                <h1>New Account</h1>
                <label for="name">Account Name</label>
                <?php echo"
                        <input class='form-control' type='text' name='name' value='{$name}'>
                        <input type='hidden' name='type' value='{$type}'>
                        <input type='hidden' name='account_id' value='{$account_id}'>";
                if($type == 0){
                    echo"<label for='init_balance'>Starting Balance</label>
                        <input class='form-control' type='number' name='init_balance' step='.01' value='{$init_balance}'>
                        <label for='curr_balance'>Current Balance</label>
                        <input class='form-control' type='number' name='curr_balance' step='.01' value='{$curr_balance}'>";
                }
                ?>
                &nbsp;
                <input class="form-control btn btn-primary" type="submit" value="Submit">
                &nbsp;
            </form>
        </div>
    </body>
</html>
