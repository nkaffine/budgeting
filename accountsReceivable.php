<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 3:37 PM
 */
    require_once($_SERVER['DOCUMENT_ROOT'].'/db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("19-1-7");
    }
    $user_id = logincheck("19-2", "19-3");
    $menu = getHeaderInfo("19-4", "19-5");
    $query = "select account_name, account_id, curr_balance, init_balance, balance_type from accounts left join (select account_id, count(account_id) ".
        "as freq from (select from_account as account_id from transactions where user_id = {$user_id} union all select to_account ".
        "as account_id from transactions where user_id = {$user_id}) as r1 group by account_id) as r2 using (account_id) where account_type = ".
        "3 and user_id = {$user_id} and active = 1 order by freq desc";
    if(($ar = @ mysqli_query($connection, $query))==FALSE){
        error("19-6-6");
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
            <h1 class="col-lg-6 col-xs-12">Accounts Receivable</h1>
            <h1>
                <form action="newAccount.php" method="post">
                    <input type="hidden" name="type" value="3">
                    <input type="submit" value="New" class="visible-xs col-xs-12 btn btn-primary pull-right" style="margin-top:1%;">
                    <input type="submit" value="New" class="hidden-xs btn btn-primary pull-right" style="margin-top:1%;">
                </form>
            </h1>
            <?php
            if(mysqli_num_rows($ar) > 0){
                echo"<table class='table table-striped col-lg-12'>
                                    <thead>
                                        <th>Name</th>
                                        <th>Current Balance</th>
                                        <th></th>
                                        <th class='hidden-xs'></th>
                                    </thead> 
                                    <tbody>";
                while($row = @ mysqli_fetch_array($ar)){
                    $account_id = $row['account_id'];
                    $account_name = $row['account_name'];
                    $curr_balance = commaSeparate($row['curr_balance']);
                    $link = "account.php?account_id=".urlencode($account_id);
                    echo"<tr>
                                                <td><a href='{$link}'>{$account_name}</a></td>
                                                <td style='color:green'>$"."{$curr_balance}</td>
                                                <td>
                                                    <form action='editAccount.php' method='post'>
                                                        <input type='hidden' name='id' value='{$account_id}'>
                                                        <input type='hidden' name='name' value='{$account_name}'>
                                                        <input type='hidden' name='type' value='3'>
                                                        <input type='submit' value='Edit' class='btn btn-default'>
                                                    </form>
                                                </td>
                                                <td class='hidden-xs'>
                                                    <form action='deleteAccount.php' method='post'>
                                                        <input type='hidden' name='account_id' value='{$account_id}'>
                                                        <input type='hidden' name='type' value='3'>
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
