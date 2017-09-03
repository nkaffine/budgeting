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
    $menu = getHeaderInfo("14-4", "14-5");
    $query = "select account_id, account_name, init_balance, curr_balance, balance_type from accounts where ".
        "user_id = {$user_id} and active = 1 and account_type = 0";
    if(($accounts = @ mysqli_query($connection, $query))==FALSE){
        error("14-6-6");
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
        <div class="box col-lg-6 col-lg-offset-3 col-xs-10 col-xs-offset-1">
            <h1 class="col-lg-6 col-xs-12">Accounts</h1>
            <h1>
                <form action="newAccount.php" method="post">
                    <input type="hidden" name="type" value="0">
                    <input type="submit" value="New" class="visible-xs col-xs-12 btn btn-primary pull-right" style="margin-top:1%;">
                    <input type="submit" value="New" class="hidden-xs btn btn-primary pull-right" style="margin-top:1%;">
                </form>
            </h1>
            <?php
                if(mysqli_num_rows($accounts) > 0){
                    echo"<table class='table table-striped col-lg-12'>
                            <thead>
                                <th>Name</th>
                                <th class='hidden-xs'>Starting Balance</th>
                                <th>Current Balance</th>
                                <th></th>
                                <th class='hidden-xs'></th>
                            </thead>
                            <tbody>";
                            while($row = @ mysqli_fetch_array($accounts)){
                                $name = $row['account_name'];
                                $id = $row['account_id'];
                                $init_balance = $row['init_balance'];
                                $curr_balance = $row['curr_balance'];
                                $balance_type = $row['balance_type'];
                                if($balance_type == 1){
                                    $color = "red";
                                    $prefix = "(";
                                    $suffix = ")";
                                } else {
                                    $color = "green";
                                    $prefix = "";
                                    $suffix = "";
                                }
                                $link = "account.php?account_id=".urlencode($id);
                                echo"<tr>
                                        <td><a href='{$link}'>{$name}</a></td>
                                        <td class='hidden-xs' style='color:{$color}'>$".$prefix."{$init_balance}".$suffix."</td>
                                        <td style='color:{$color}'>$".$prefix."{$curr_balance}".$suffix."</td>
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
                                        <td class='hidden-xs'>
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
        <div class='col-lg-12' style="height:20vh;"></div>
    </body>
</html>
