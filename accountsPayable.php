<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 3:37 PM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("18-1-7");
    }
    $user_id = logincheck("18-2", "18-3");
    $menu = getHeaderInfo("18-4");
    $query = "select account_name, account_id, curr_balance from accounts where user_id = {$user_id} and ".
        "account_type = 4";
    if(($ap = @ mysqli_query($connection, $query))==FALSE){
        error('18-5-6');
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Categories</title>
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
            <h1>Accounts Payable</h1>
            <?php
                if(mysqli_num_rows($ap) > 0){
                    echo"<table class='table table-striped col-lg-12'>
                            <thead>
                                <th>Name</th>
                                <th>Current Balance</th>
                                <th></th>
                                <th></th>
                            </thead>
                            <tbody>";
                            while($row = @ mysqli_fetch_array($ap)){
                                $account_id = $row['account_id'];
                                $account_name = $row['account_name'];
                                $curr_balance = $row['curr_balance'];
                                echo"<tr>
                                        <td>{$account_name}</td>
                                        <td>$"."{$curr_balance}</td>
                                        <td>
                                            <form action='editAccount.php' method='post'>
                                                <input type='hidden' name='id' value='{$account_id}'>
                                                <input type='hidden' name='name' value='{$account_name}'>
                                                <input type='hidden' name='type' value='4'>
                                                <input type='submit' value='Edit' class='btn btn-default'>
                                            </form>
                                        </td>
                                        <td>
                                            <form action='deleteAccount.php' method='post'>
                                                <input type='hidden' name='account_id' value='{$account_id}'>
                                                <input type='hidden' name='type' value='4'>
                                                <input type='submit' value='Delete' class='btn btn-default'>
                                            </form>
                                        </td>
                                    </tr>";
                            }
                        echo"</tbody>
                        </table>";
                } else {
                    echo"<h3>You have no Accounts Payable</h3>";
                }
            ?>
        </div>
        <div class='col-lg-12' style="height:20vh;></div>
    </body>
</html>
