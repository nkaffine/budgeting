<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 9/3/17
 * Time: 5:03 PM
 */
    require_once($_SERVER['DOCUMENT_ROOT'].'/db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("25-0-7");
    }
    if(count($_POST)){
        $type = validNumbers($_POST['type'], 1);
    }
    if(!isset($type)){
        error("25-1-1");
    }
    $user_id = logincheck("25-2", "25-3");
    $menu = getHeaderInfo("25-4", "25-5");
    $query = "select account_id, account_name from accounts where account_type = 0 and user_id = {$user_id}";
    if(($from_accounts = @ mysqli_query($connection, $query))==FALSE){
        error("25-6-6");
    }
    $query = "select account_id, account_name from accounts where account_type = 1 and user_id = {$user_id}";
    if(($spending_categories = @ mysqli_query($connection, $query))==FALSE){
        error("25-7-6");
    }
    $query = "select account_id, account_name from accounts where account_type = 3 and user_id = {$user_id}";
    if(($account_receivables = @ mysqli_query($connection, $query))==FALSE){
        error("25-8-6");
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>New Split Transaction</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!--Stuff for selectors-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
        <link rel="stylesheet" href="style_sheets/main.css">
        <script src="scripts/newAccounts.js"></script>
        <style>
            .box {
                background-color: white;
            }
            #select1{
                padding-left: 0;
            }
        </style>
    </head>
    <body>
        <?php placeHeader($menu) ?>
        <div class="col-lg-4 col-lg-offset-4 box col-xs-10 col-xs-offset-1">
            <?php echo"<h3>New Split Transaction</h3>"; ?>
            <form action="process/splitTransaction.php" method="post" id="transaction">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="transaction_name" value="">
                <label for="Description">Description (optional)</label>
                <textarea class="form-control" name="description" value="" maxlength="255" form="transaction"></textarea>
                <label for="date">Date</label>
                <input class="form-control" type="text" name="date" value="">
                <label for="Amount">Amount</label>
                <input class="form-control" type="number" step=".01" value="0" name="amount">
                <label for='from'>Where the Money is Coming From?</label>
                <div class="col-lg-12" style="padding-left:0;padding-right:0;">
                    <select class='selectpicker form-control' name='from'">
                    <?php
                    while($row = @ mysqli_fetch_array($from_accounts)){
                        $name = $row['account_name'];
                        $id = $row['account_id'];
                        echo"<option value='{$id}'>{$name}</option>";
                    }
                    ?>
                    </select>
                </div>
                <label class='col-lg-12 row' for='to'>Who did you split the purchase with?</label>
                <div class="col-lg-12" style="padding-left:0;padding-right:0;">
                    <select class='selectpicker form-control' name='ar'">
                    <?php
                    while($row = @ mysqli_fetch_array($account_receivables)){
                        $name = $row['account_name'];
                        $id = $row['account_id'];
                        echo"<option value='{$id}'>{$name}</option>";
                    }
                    ?>
                    </select>
                </div>
                <label class='col-lg-12 row' for='to'>Where is the money going?</label>
                <div class="col-lg-12" style="padding-left:0;padding-right:0;">
                    <select class='selectpicker form-control' name='to'">
                    <?php
                    while($row = @ mysqli_fetch_array($spending_categories)){
                        $name = $row['account_name'];
                        $id = $row['account_id'];
                        echo"<option value='{$id}'>{$name}</option>";
                    }
                    ?>
                    </select>
                </div>
                <?php echo"<input type='hidden' name='transaction_type' value='{$type}'>"; ?>
                <input style="margin-top:5%;" class="form-control btn btn-primary" type="submit" value="Submit">
            </form>
            &nbsp;
        </div>
        <div class='col-lg-12' style="height:20vh;></div>
    </body>
</html>
