<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 6:48 PM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("24-1-7");
    }
    $user_id = logincheck("24-2", "24-3");
    $menu = getHeaderInfo("24-4");
    if(count($_GET)){
        $account_id = validNumbers($_GET['account_id'], 10);
    }
    if(empty($account_id)){
        error("24-5-1");
    }
    $query = "select account_name, init_balance, curr_balance from accounts where user_id = {$user_id} and account_id = {$account_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("24-6-6");
    }
    if(notUnique($result)){
        error("24-7-3");
    }
    $row = @ mysqli_fetch_array($result);
    $account_name = $row['account_name'];
    $init_balance = $row['init_balance'];
    $curr_balance = $row['curr_balance'];
    $query = "select transaction_name, description, amount, transaction_date, from_account, (select account_name from ".
        "accounts where account_id = from_account and user_id = {$user_id}) as from_name, to_account, (select account_name ".
        "from accounts where account_id = to_account and user_id = {$user_id}) as to_name from transactions where ".
        "(to_account = {$account_id} or from_account = {$account_id}) and user_id = {$user_id} order by ".
        "transaction_date desc, date_added desc limit 20";
    if(($result = @ mysqli_query($connection,$query))==FALSE){
        debug($query);
        error("24-8-6");
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
        <script>
            $(document).ready(function(){
                var times = document.getElementsByClassName('time');
                for(var i = 0; i < times.length; i++){
                    times[i].innerHTML = userTime2(times[i].innerHTML);
                }
            })
        </script>
    </head>
    <body>
        <?php placeHeader($menu);?>
        <div class="col-lg-6 col-lg-offset-3">
            <div class="box col-lg-12">
                <?php echo"
                    <h1>{$account_name}</h1>
                    <h2>Starting Balance: $"."{$init_balance}</h2>
                    <h2>Current Balance: $"."{$curr_balance}</h2>
                    ";
                ?>
            </div>
            <div class="box col-lg-12" style="margin-top:2%;">
                <h1>Transactions</h1>
                <?php
                    if(mysqli_num_rows($result) > 0){
                        echo"<table class='table table-striped'>
                                <thead>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </thead>
                                <tbody>";
                                    while($row = @ mysqli_fetch_array($result)){
                                        $name = $row['transaction_name'];
                                        $amount = $row['amount'];
                                        $description = $row['description'];
                                        $date = $row['transaction_date'];
                                        echo"<tr>
                                                <td>{$name}</td>
                                                <td>$"."{$amount}</td>
                                                <td>{$description}</td>
                                                <td class='time'>{$date}</td>
                                            </tr>";
                                    }
                            echo"</tbody>
                            </table>
                            ";
                    } else {
                        echo"<h2>There are no transactions with this account</h2>";
                    }
                ?>
            </div>
        </div>
    </body>
</html>
