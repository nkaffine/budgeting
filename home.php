<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/10/17
 * Time: 8:23 PM
 */
    require_once($_SERVER['DOCUMENT_ROOT'].'/db.php');
    require_once('header.php');
    require_once('queries/accounts.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("4-1-7");
    }
    $user_id = logincheck("4-2", "4-3");
    $menu = getHeaderInfo("4-4", "4-5");
    $query = "select transaction_name, amount, transaction_date, type from transactions where active = 1 and user_id = ".
        "{$user_id} order by transaction_date desc, date_added desc limit 10";
    if(($recent_transactions = @ mysqli_query($connection, $query))==FALSE){
        error("4-4-6");
    }
    $query = getAccounts($user_id, 0);
    if(($personal_accounts = @ mysqli_query($connection, $query))==FALSE){
        error("4-5-6");
    }
    $query = getAccounts($user_id, 2);
    if(($earning_categorys = @ mysqli_query($connection, $query))==FALSE){
        error("4-6-6");
    }
    $query = getAccounts($user_id, 1);
    if(($spending_categories = @ mysqli_query($connection, $query))==FALSE){
        error("4-7-6");
    }
    $query = getAccounts($user_id, 3);
    if(($ar_accounts = @ mysqli_query($connection, $query))==FALSE){
        error("4-8-6");
    }
    $query = getAccounts($user_id, 4);
    if(($ap_accounts = @ mysqli_query($connection, $query))==FALSE){
        error("4-9-6");
    }
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Kaffine Budgeting</title>
        <meta charset="utf-8">
        <!--Stuff required for bootstrap-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!--importing javascript file and style sheets from the server-->
        <script src="scripts/login.js"></script>
        <script src="scripts/timeFormatting.js"></script>
        <link rel="stylesheet" href="style_sheets/main.css">
        <script>
            $(document).ready(function(){
                var dates = document.getElementsByClassName('time');
                for(var i = 0; i < dates.length; i++){
                    dates[i].innerHTML = userTime2(dates[i].innerHTML);
                }
            });
        </script>
        <style>
            .box1{
                background-color: white;
                padding-left: 5%;
                padding-right:5%;
                padding-top: 1%;
                padding-bottom: 1%;
            }
            .box{
                background-color: transparent;
            }
        </style>
    </head>
    <body>
        <?php echo placeHeader($menu);?>
        <div class="container-fluid hidden-xs">
            <div class="col-lg-4 box">
                <div class='box1' style="padding-bottom: 4%;">
                    <h1 role="button" data-toggle="collapse" data-target="#newTransaction">New Transaction</h1>
                    <div class="collapse" id="newTransaction">
                        <form action="newTransaction.php" method="post">
                            <input class="btn btn-default form-control" type="submit" value="New Spending Transaction">
                            <input type="hidden" name="type" value="0">
                        </form>
                        &nbsp;
                        <form action="newTransaction.php" method="post">
                            <input class="btn btn-default form-control" type="submit" value="New Earning Transaction">
                            <input type="hidden" name="type" value="1">
                        </form>
                        &nbsp;
                        <form action="newTransaction.php" method="post">
                            <input class="btn btn-default form-control" type="submit" value="New Transfer Transaction">
                            <input type="hidden" name="type" value="2">
                        </form>
                        &nbsp;
                        <form action="newTransaction.php" method="post">
                            <input class="btn btn-default form-control" type="submit" value="New Accounts Receivable Transaction">
                            <input type="hidden" name="type" value="3">
                        </form>
                        &nbsp;
                        <form action="newTransaction.php" method="post">
                            <input class="btn btn-default form-control" type="submit" value="New Accounts Received Transaction">
                            <input type="hidden" name="type" value="4">
                        </form>
                        &nbsp;
                        <form action="newTransaction.php" method="post">
                            <input class="btn btn-default form-control" type="submit" value="New Accounts Payable Transaction">
                            <input type="hidden" name="type" value="5">
                        </form>
                        &nbsp;
                        <form action="newTransaction.php" method="post">
                            <input class="btn btn-default form-control" type="submit" value="New Accounts Paid Transaction">
                            <input type="hidden" name="type" value="6">
                        </form>
                        &nbsp;
                        <form action="splitTransaction.php" method="post">
                            <input class="btn btn-default form-control" type="submit" value="New Split Purchase Transaction">
                            <input type="hidden" name="type" value="7">
                        </form>
                    </div>
                </div>
                <div class='box1' style="margin-top: 5%; padding-bottom: 4%;">
                    <h1 role="button" data-toggle="collapse" data-target="#recentTransactions">Recent Transactions</h1>
                    <div id="recentTransactions" class="collapse">
                        <table class="table table-striped">
                            <thead>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Date</th>
                            </thead>
                            <tbody>
                            <?php
                            while($row = @ mysqli_fetch_array($recent_transactions)){
                                $name = $row['transaction_name'];
                                $amount = commaSeparate($row['amount']);
                                $date = $row['transaction_date'];
                                $type = $row['type'];
                                if($type == 1 || $type == 3){
                                    $color = "green";
                                    $prefix = "+";
                                } else if($type == 0 || $type == 5) {
                                    $color = "red";
                                    $prefix = "-";
                                } else {
                                    $color = "grey";
                                    $prefix = "";
                                }
                                echo"<tr>
                                                <td>{$name}</td>
                                                <td style='color:{$color};'>{$prefix}"."$"."{$amount}</td>
                                                <td class='time'>{$date}</td>
                                            </tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box col-lg-4">
                <div class='container col-lg-12' style="padding-bottom: 4%; background-color: white;">
                    <div class="col-lg-12">
                        <h1 role='button' data-toggle='collapse' data-target='#accounts' class="pull-left">Accounts</h1>
                        <h1>
                            <form action="newAccount.php" method="post">
                                <input type="hidden" name="type" value="0">
                                <input type="submit" value="New" class="btn btn-primary pull-right" style="margin-top:1%;">
                            </form>
                        </h1>
                    </div>
                    <div id="accounts" class="collapse col-lg-12">
                        <table class="table table-striped">
                            <thead>
                                <th>Account Name</th>
                                <th>Current Balance</th>
                            </thead>
                            <tbody>
                            <?php
                                while($row = @ mysqli_fetch_array($personal_accounts)){
                                    $name = $row['account_name'];
                                    $account_id = $row['account_id'];
                                    $curr_balance = commaSeparate($row['curr_balance']);
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
                                    $link = 'account.php?account_id=' . $account_id;
                                    echo"<tr>
                                            <td><a href='{$link}'>{$name}</a></td>
                                            <td style='color:{$color};'>$".$prefix.$curr_balance.$suffix."</td>
                                        </tr>";
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class='container col-lg-12' style="padding-bottom: 3%; background-color: white; margin-top: 5%;">
                    <div class="col-lg-12">
                        <h2 role="button" data-toggle="collapse" data-target="#ap_accounts" class="pull-left">Accounts Payable</h2>
                        <h2>
                            <form action="newAccount.php" method="post">
                                <input type="hidden" name="type" value="4">
                                <input type="submit" value="New" class="btn btn-primary pull-right" style="margin-top:0%;">
                            </form>
                        </h2>
                    </div>
                    <div id="ap_accounts" class="collapse col-lg-12">
                        <table class="table table-striped">
                            <thead>
                                <th>Account Name</th>
                                <th>Current Balance</th>
                            </thead>
                            <tbody>
                            <?php
                            while($row = @ mysqli_fetch_array($ap_accounts)){
                                $name = $row['account_name'];
                                $account_id = $row['account_id'];
                                $account_balance = commaSeparate($row['curr_balance']);
                                $link = 'account.php?account_id=' . $account_id;
                                echo"<tr>
                                        <td><a href='{$link}'>{$name}</a></td>
                                        <td style='color:red'>$(".$account_balance.")</td>
                                    </tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class='container col-lg-12' style="padding-bottom: 3%; background-color: white; margin-top: 5%;">
                    <div class="col-lg-12">
                        <h3 role="button" data-toggle="collapse" data-target="#araccounts" class="pull-left">Accounts Receivable</h3>
                        <h3>
                            <form action="newAccount.php" method="post">
                                <input type="hidden" name="type" value="3">
                                <input type="submit" value="New" class="btn btn-primary pull-right" style="margin-top:-.5%;">
                            </form>
                        </h3>
                    </div>

                    <div id="araccounts" class="collapse col-lg-12">
                        <table class="table table-striped">
                            <thead>
                                <th>Account Name</th>
                                <th>Current Balance</th>
                            </thead>
                            <tbody>
                            <?php
                            while($row = @ mysqli_fetch_array($ar_accounts)){
                                $name = $row['account_name'];
                                $account_id = $row['account_id'];
                                $account_balance = commaSeparate($row['curr_balance']);
                                $link = 'account.php?account_id=' . $account_id;
                                echo"<tr>
                                    <td><a href='{$link}'>{$name}</a></td>
                                    <td style='color:green;'>$".$account_balance."</td>
                                </tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box col-lg-4">
                <div class='container col-lg-12' style="padding-bottom: 4%; background-color: white;">
                    <div class="col-lg-12">
                        <h3 role="button" data-toggle="collapse" data-target="#spend_cat" class="pull-left">Spending Categories</h3>
                        <h3>
                            <form action="newAccount.php" method="post">
                                <input type="hidden" name="type" value="1">
                                <input type="submit" value="New" class="btn btn-primary pull-right" style="margin-top:-.5%;">
                            </form>
                        </h3>
                    </div>
                    <div id="spend_cat" class="collapse col-lg-12">
                        <table class="table table-striped">
                            <thead>
                                <th>Category Name</th>
                            </thead>
                            <tbody>
                            <?php
                            while($row = @ mysqli_fetch_array($spending_categories)){
                                $name = $row['account_name'];
                                $account_id = $row['account_id'];
                                $link = 'account.php?account_id=' . $account_id;
                                echo"<tr>
                                        <td><a href='{$link}'>{$name}</a></td>
                                    </tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class='container col-lg-12' style="margin-top: 5%; padding-bottom: 3%; background-color: white;">
                    <div class="col-lg-12">
                        <h3 role="button" data-toggle="collapse" data-target="#earn_cat" class="pull-left">Earning Categories</1>
                        <h3>
                            <form action="newAccount.php" method="post">
                                <input type="hidden" name="type" value="2">
                                <input type="submit" value="New" class="btn btn-primary pull-right" style="margin-top:-.5%;">
                            </form>
                        </h3>
                    </div>

                    <div id="earn_cat" class="collapse col-lg-12">
                        <table class="table table-striped">
                            <thead>
                            <th>Account Name</th>
                            </thead>
                            <tbody>
                            <?php
                            while($row = @ mysqli_fetch_array($earning_categorys)){
                                $name = $row['account_name'];
                                $account_id = $row['account_id'];
                                $link = 'account.php?account_id=' . $account_id;
                                echo"<tr>
                                        <td><a href='{$link}'>{$name}</a></td>
                                    </tr>";
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="visible-xs col-xs-10 col-xs-offset-1">
            <div class='box1' style="padding-bottom: 4%;">
                <h1>New Transaction</h1>
                <div>
                    <form action="newTransaction.php" method="post">
                        <input class="btn btn-default form-control" type="submit" value="New Spending Transaction">
                        <input type="hidden" name="type" value="0">
                    </form>
                    &nbsp;
                    <form action="newTransaction.php" method="post">
                        <input class="btn btn-default form-control" type="submit" value="New Earning Transaction">
                        <input type="hidden" name="type" value="1">
                    </form>
                    &nbsp;
                    <form action="newTransaction.php" method="post">
                        <input class="btn btn-default form-control" type="submit" value="New Transfer Transaction">
                        <input type="hidden" name="type" value="2">
                    </form>
                    &nbsp;
                    <form action="newTransaction.php" method="post">
                        <input class="btn btn-default form-control" type="submit" value="New Accounts Receivable Transaction">
                        <input type="hidden" name="type" value="3">
                    </form>
                    &nbsp;
                    <form action="newTransaction.php" method="post">
                        <input class="btn btn-default form-control" type="submit" value="New Accounts Received Transaction">
                        <input type="hidden" name="type" value="4">
                    </form>
                    &nbsp;
                    <form action="newTransaction.php" method="post">
                        <input class="btn btn-default form-control" type="submit" value="New Accounts Payable Transaction">
                        <input type="hidden" name="type" value="5">
                    </form>
                    &nbsp;
                    <form action="newTransaction.php" method="post">
                        <input class="btn btn-default form-control" type="submit" value="New Accounts Paid Transaction">
                        <input type="hidden" name="type" value="6">
                    </form>
                </div>
            </div>
        </div>
        <div class='col-lg-12' style="height:20vh;></div>
    </body>
</html>

