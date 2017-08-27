<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 12:12 PM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("14-1-7");
    }
    $user_id = logincheck("14-2", "14-3");
    $menu = getHeaderInfo("14-4");
    $query = "select account_id, account_name, init_balance, curr_balance from accounts where ".
        "user_id = {$user_id} and active = 1 and account_type = 0";
    if(($accounts = @ mysqli_query($connection, $query))==FALSE){
        error("14-5-6");
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
        <div class="box col-lg-6 col-lg-offset-3">
            <h1>Accounts</h1>
            <?php
                if(mysqli_num_rows($accounts) > 0){
                    echo"<table class='table table-striped col-lg-12'>
                            <thead>
                                <th>Name</th>
                                <th>Starting Balance</th>
                                <th>Current Balance</th>
                                <th></th>
                                <th></th>
                            </thead>
                            <tbody>";
                            while($row = @ mysqli_fetch_array($accounts)){
                                $name = $row['account_name'];
                                $id = $row['account_id'];
                                $init_balance = $row['init_balance'];
                                $curr_balance = $row['curr_balance'];
                                echo"<tr>
                                        <td>{$name}</td>
                                        <td>$"."{$init_balance}</td>
                                        <td>$"."{$curr_balance}</td>
                                        <td>
                                            <form action='editAccount.php' method='post'>
                                                <input type='hidden' name='id' value='{$id}'>
                                                <input type='hidden' name='curr_balance' value='{$curr_balance}'>
                                                <input type='hidden' name='init_balance' value='{$init_balance}'>
                                                <input type='hidden' name='name' value='{$name}'>
                                                <input type='hidden' name='type' value='0'>
                                                <input type='submit' value='Edit' class='btn btn-default'>
                                            </form>
                                        </td>
                                        <td>
                                            <form action='deleteAccount.php' method='post'>
                                                <input type='hidden' name='account_id' value='{$id}'>
                                                <input type='hidden' name='type' value='0'>
                                                <input type='submit' value='Delete' class='btn btn-default'>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        echo"</tbody>
                        </table>";
                } else {
                    echo"You have no accounts";
                }
            ?>
        </div>
    </body>
</html>
