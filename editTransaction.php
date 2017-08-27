<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 5:18 PM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("22-1-7");
    }
    $user_id = logincheck("22-2", "22-3");
    $menu = getHeaderInfo("22-4");
    if(count($_POST)){
        $transaction_id = validNumbers($_POST['transaction_id'], 10);
    }
    if(!isset($transaction_id)){
        error("22-5-1");
    }
    $query = "select transaction_name, description, amount, transaction_date, from_account, (select account_name from ".
        "accounts where user_id = {$user_id} and account_id = from_account) as from_name, to_account, (select account_name ".
        "from accounts where user_id = {$user_id} and account_id = to_account) as to_name, type from ".
        "transactions where user_id = {$user_id} and transaction_id = {$transaction_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("22-6-6");
    }
    if(notUnique($result)){
        error('22-7-3');
    }
    $row = @ mysqli_fetch_array($result);
    $transaction_name = $row['transaction_name'];
    $description = $row['description'];
    $amount = $row['amount'];
    $transaction_date = $row['transaction_date'];
    $from_account = $row['from_account'];
    $to_account = $row['to_account'];
    $type = $row['type'];
    $transaction_to_name = $row['to_name'];
    $transaction_from_name = $row['from_name'];
    switch ($type) {
        case 0 :
            //The transaction is a spending transaction
            //From accounts can only be personal accounts
            $from_account_type = 0;
            $from_name = "Personal Account";
            //To accounts can only be spending categories
            $to_account_type = 1;
            $to_name = "Spending Category";
            $name = "Spending";
            break;
        case 1 :
            //The transaction is an earning transaction
            //From accounts can only be earning categories
            $from_account_type = 2;
            $from_name = "Earning Category";
            //To accounts can only be personal accounts
            $to_account_type = 0;
            $to_name = "Personal Account";
            $name = "Earning";
            break;
        case 2 :
            //The transaction is a transfer transaction
            //From accounts can only be personal accounts
            $from_account_type = 0;
            $from_name = "Personal Account";
            //To accounts can only be personal accounts
            $to_account_type = 0;
            $to_name = "Personal Account";
            $name = "Transfer";
            break;
        case 3 :
            //The transaction is an accounts receivable transaction
            //From accounts can only be accounts receivable accounts
            $from_account_type = 0;
            $from_name = "Accounts Receivable Account";
            //To accounts can only be earning categories
            $to_account_type = 3;
            $to_name = "Earning Category";
            $name = "Accounts Receivable";
            break;
        case 4 :
            //The transaction is an accounts received transaction
            //From accounts can only be accounts receivable accounts
            $from_account_type = 3;
            $from_name = "Accounts Receivable Account";
            //To accounts can only be personal accounts
            $to_account_type = 0;
            $to_name = "Personal Account";
            $name = "Accounts Received";
            break;
        case 5 :
            //The transaction is an accounts payable transaction
            //From accounts can only be spending categories
            $from_account_type = 1;
            $from_name = "Spending Category";
            //To accounts can only be accounts payable accounts
            $to_account_type = 4;
            $to_name = "Accounts Payable Account";
            $name = "Accounts Payable";
            break;
        case 6:
            //The transaction is an accounts paid transaction
            //From accounts can only be personal accounts
            $from_account_type = 0;
            $from_name = "Personal Account";
            //To accounts can only be accounts payable accounts
            $to_account_type = 4;
            $to_name = "Accounts Payable Account";
            $name = "Accounts Paid";
            break;
        default :
            //The transaction is one that has not been recognized
            error("22-8-2");
    }
    $query = "select account_name, account_id from accounts where account_type = {$from_account_type} and ".
        "user_id = {$user_id} and active = 1";
    if(($from_accounts = @ mysqli_query($connection, $query))==FALSE){
        error("22-9-6");
    }
    $query = "select account_name, account_id from accounts where account_type = {$to_account_type} and ".
        "user_id = {$user_id} and active = 1";
    if(($to_accounts = @ mysqli_query($connection, $query))==FALSE){
        error("22-10-6");
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Edit Transaction</title>
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
        <script src="scripts/timeFormatting.js"></script>
        <style>
            .box {
                background-color: white;
            }
            #select1{
                padding-left: 0;
            }
        </style>
        <script>
            $(document).ready(function(){
                var time = document.getElementsByClassName('time');
                for(var i = 0; i < time.length; i++){
                    time[i].value = userTime2(time[i].value);
                }
            })
        </script>
    </head>
    <body>
        <?php placeHeader($menu) ?>
        <div class="col-lg-4 col-lg-offset-4 box">
            <?php echo"<h3>New {$name} Transaction</h3>"; ?>
            <form action="process/editTransaction.php" method="post" id="transaction">
                <label for="name">Name</label>
                <?php echo"<input class='form-control' type='text' name='transaction_name' value='{$transaction_name}'>"?>
                <label for="Description">Description (optional)</label>
                <?php echo"<textarea class='form-control' name='description' value='{$description}' maxlength='255' form='transaction'></textarea>";?>
                <label for="date">Date</label>
                <?php echo"<input class='form-control time' type='text' name='date' value='{$transaction_date}'>";?>
                <label for="Amount">Amount</label>
                <?php echo"<input class='form-control' type='number' step='.01' value='{$amount}' name='amount'>";?>
                <label for='from'>Where the Money is Coming From</label>
                <div class="col-lg-10 row">
                    <select class='selectpicker form-control' name='from'">
                    <?php
                    echo"<option value='{$from_account}' selected='selected'>{$transaction_from_name}</option>";
                    while($row = @ mysqli_fetch_array($from_accounts)){
                        $name = $row['account_name'];
                        $id = $row['account_id'];
                        echo"<option value='{$id}'>{$name}</option>";
                    }
                    ?>
                    </select>
                </div>
                <button type='button' class="col-lg-2 btn btn-primary pull-right" style="margin-left: 0;" data-toggle="collapse" data-target="#newFrom">New</button>
                <div id="newFrom" class="collapse col-lg-12">
                    <?php
                    echo"<input id='newFromAccountType' type='hidden' name='account_type' value='{$from_account_type}'>";
                    echo"
                                        <h3>New {$from_name}</h3>
                                        <label for='name'>Name</label>
                                        <input id='newFromName' class='form-control' type='text' name='name' value=''>";
                    if($from_account_type == 0 || $from_account_type == 3 || $from_account_type == 4){
                        echo"
                                        <label for='balance'>Balance</label>
                                        <input id='newFromBalance' class='form-control' type='number' name='balance' step='.01' value='0'>";
                        if($from_account_type == 0){
                            echo"
                                                <label for='type'>Type</label>
                                                <select id='newFromBalanceType' class='form-control selectpicker' name='type'>
                                                    <option value='0' selected='selected'>Debit</option>
                                                    <option value='1'>Credit</option>
                                                </select>";
                        }

                    }
                    echo
                    "&nbsp;
                                    <button id='newFromBtn' type='button' class='form-control btn btn-primary' data-toggle='collapse' data-target='#newFrom'>Create</button>
                                    &nbsp;";
                    ?>
                </div>
                <label class='col-lg-12 row' for='to'>Where the Money is Going</label>
                <div class="col-lg-10 row">
                    <select class='selectpicker form-control' name='to'">
                    <?php
                    echo"<option value='{$to_account}' selected='selected'>{$transaction_to_name}</option>";
                    while($row = @ mysqli_fetch_array($to_accounts)){
                        $name = $row['account_name'];
                        $id = $row['account_id'];
                        echo"<option value='{$id}'>{$name}</option>";
                    }
                    ?>
                    </select>
                </div>
                <button type="button" class="col-lg-2 btn btn-primary pull-right" data-toggle="collapse" data-target="#newTo" style="margin-left: 0;">New</button>
                <div id="newTo" class="collapse col-lg-12">
                    <?php
                    echo"<input id='newToAccountType' type='hidden' name='account_type' value='{$from_account_type}'>";
                    echo"
                                        <h3>New {$to_name}</h3>
                                        <label for='name'>Name</label>
                                        <input id='newToAccountName' class='form-control' type='text' name='name' value=''>";
                    if($to_account_type == 0 || $to_account_type == 3 || $to_account_type == 4){
                        echo"
                                        <label for='balance'>Balance</label>
                                        <input id='newToAccountBalance' class='form-control' type='number' name='balance' step='.01' value='0'>";
                        if($to_account_type == 0){
                            echo"
                                                <label for='type'>Type</label>
                                                <select id='newToAccountBalanceType' class='form-control selectpicker' name='type'>
                                                    <option value='0' selected='selected'>Debit</option>
                                                    <option value='1'>Credit</option>
                                                </select>";
                        }

                    }
                    echo
                    "&nbsp;
                                    <button id='newToBtn' type='button' class='form-control btn btn-primary' data-toggle='collapse' data-target='#newTo'>Create</button>
                                    &nbsp;";
                    ?>
                </div>
                <?php echo"<input type='hidden' name='transaction_id' value='{$transaction_id}'>
                            <input type='hidden' name='transaction_type' value='{$type}'>"; ?>
                <input style="margin-top:5%;" class="form-control btn btn-primary" type="submit" value="Submit">
            </form>
            &nbsp;
        </div>
    </body>
</html>

