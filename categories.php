<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 2:52 PM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("17-1-7");
    }
    $user_id = logincheck("17-2", "17-3");
    $menu = getHeaderInfo("17-4", "17-5");
    $query = "select account_id, account_name from accounts where user_id = {$user_id} and account_type = 1";
    if(($spending = @ mysqli_query($connection, $query))==FALSE){
        error("17-6-6");
    }
    $query = "select account_id, account_name from accounts where user_id = {$user_id} and account_type = 2";
    if(($earning = @ mysqli_query($connection, $query))==FALSE){
        error("17-7-6");
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
        <div class="col-lg-8 col-lg-offset-2 col-xs-10 col-xs-offset-1">
            <div class="box col-lg-5 col-lg-offset-1">
                <h1>Spending Categories</h1>
                <?php
                    if(mysqli_num_rows($spending) > 0){
                        echo"<table class='table table-striped col-lg-12'>
                                <thead>
                                    <th>Name</th>
                                    <th></th>
                                    <th></th>
                                </thead>
                                <tbody>";
                                    while($row = @ mysqli_fetch_array($spending)){
                                        $name = $row['account_name'];
                                        $account_id = $row['account_id'];
                                        $link = "account.php?account_id=".urlencode($account_id);
                                        echo"<tr>
                                                <td><a href='{$link}'>{$name}</a></td>
                                                <td>
                                                    <form action='editAccount.php' method='post'>
                                                        <input type='hidden' name='id' value='{$account_id}'>
                                                        <input type='hidden' name='name' value='{$name}'>
                                                        <input type='hidden' name='type' value='1'>
                                                        <input type='submit' value='Edit' class='btn btn-default'>
                                                    </form>    
                                                </td>
                                                <td>
                                                    <form action='deleteAccount.php' method='post'>
                                                        <input type='hidden' name='account_id' value='{$account_id}'>
                                                        <input type='hidden' name='type' value='1'>
                                                        <input type='submit' value='Delete' class='btn btn-default'>
                                                    </form>
                                                </td>
                                            </tr>";
                                    }
                            echo"</tbody>
                            </table>
                            ";
                    } else {
                        echo"<h2>You have no spending categories</h2>";
                    }
                ?>
            </div>
            <div class="box col-lg-5 col-lg-offset-1">
                <h1>Earning Categories</h1>
                <?php
                if(mysqli_num_rows($earning) > 0){
                    echo"<table class='table table-striped col-lg-12'>
                                <thead>
                                    <th>Name</th>
                                    <th></th>
                                    <th></th>
                                </thead>
                                <tbody>";
                    while($row = @ mysqli_fetch_array($earning)){
                        $name = $row['account_name'];
                        $account_id = $row['account_id'];
                        $link = "account.php?account_id=" . urlencode($account_id);
                        echo"<tr>
                                <td><a href='{$link}'>{$name}</a></td>
                                <td>
                                    <form action='editAccount.php' method='post'>
                                        <input type='hidden' name='id' value='{$account_id}'>
                                        <input type='hidden' name='name' value='{$name}'>
                                        <input type='hidden' name='type' value='2'>
                                        <input type='submit' value='Edit' class='btn btn-default'>
                                    </form>    
                                </td>
                                <td>
                                    <form action='deleteAccount.php' method='post'>
                                        <input type='hidden' name='account_id' value='{$account_id}'>
                                        <input type='hidden' name='type' value='2'>
                                        <input type='submit' value='Delete' class='btn btn-default'>
                                    </form>
                                </td>
                            </tr>";
                    }
                    echo"</tbody>
                            </table>
                            ";
                } else {
                    echo"<h2>You have no earning categories</h2>";
                }
                ?>
            </div>
        </div>
        <div class='col-lg-12' style="height:20vh;></div>
    </body>
</html>
