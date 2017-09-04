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
    $menu = getHeaderInfo("18-4", "18-5");
    $query = "select account_name, account_id, curr_balance, init_balance from accounts where user_id = {$user_id} and ".
        "account_type = 4 and active = 1";
    if(($ap = @ mysqli_query($connection, $query))==FALSE){
        error('18-6-6');
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
        <div class="box col-lg-6 col-lg-offset-3 col-xs-10 col-xs-offset-1">
            <h1 class="col-lg-6 col-xs-12">Accounts Payable</h1>
            <h1>
                <form action="newAccount.php" method="post">
                    <input type="hidden" name="type" value="4">
                    <input type="submit" value="New" class="visible-xs col-xs-12 btn btn-primary pull-right" style="margin-top:1%;">
                    <input type="submit" value="New" class="hidden-xs btn btn-primary pull-right" style="margin-top:1%;">
                </form>
            </h1>
            <?php
                if(mysqli_num_rows($ap) > 0){
                    echo"<table class='table table-striped col-lg-12'>
                            <thead>
                                <th>Name</th>
                                <th>Current Balance</th>
                                <th></th>
                                <th class='hidden-xs'></th>
                            </thead>
                            <tbody>";
                            while($row = @ mysqli_fetch_array($ap)){
                                $account_id = $row['account_id'];
                                $account_name = $row['account_name'];
                                $curr_balance = $row['curr_balance'];
                                $init_balance = $row['init_balance'];
                                $link = "account.php?account_id=".urlencode($account_id);
                                echo"<tr>
                                        <td><a href='{$link}'>{$account_name}</a></td>
                                        <td style='color:red'>$("."{$curr_balance})</td>
                                        <td>
                                            <form action='editAccount.php' method='post'>
                                                <input type='hidden' name='id' value='{$account_id}'>
                                                <input type='hidden' name='name' value='{$account_name}'>
                                                <input type='hidden' name='curr_balance' value='{$curr_balance}'>
                                                <input type='hidden' name='init_balance' value='{$init_balance}'>
                                                <input type='hidden' name='type' value='4'>
                                                <input type='submit' value='Edit' class='btn btn-default'>
                                            </form>
                                        </td>
                                        <td class='hidden-xs'>
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
