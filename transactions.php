<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/11/17
 * Time: 10:13 PM
 */
    require_once($_SERVER['DOCUMENT_ROOT'].'/db.php');
    require_once('header.php');
    if(!($connection = mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("6-1-7");
    }
    $user_id = logincheck("6-2", "6-3");
    $menu = getHeaderInfo("6-4", "6-5");
    //Account Types that can be from accounts (0,2,3,1)
    $query = "select account_name, account_id from accounts where (account_type <= 3) and user_id = {$user_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("6-5-6");
    }
    $froms = "<option selected='selected' value='0'>None</option>";
    while($row = @ mysqli_fetch_array($result)){
        $name = $row['account_name'];
        $id = $row['account_id'];
        $option = "<option value='{$id}'>{$name}</option>";
        $froms = $froms. $option;
    }
    $tos = "<option selected='selected' value='0'>None</option>";
    //Account Types that can be to accounts (0,1,2,4)
    $query = "select account_name, account_id from accounts where (account_type != 3) and user_id= $user_id";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("6-6-6");
    }
    while($row = @ mysqli_fetch_array($result)){
        $name = $row['account_name'];
        $id = $row['account_id'];
        $option = "<option value='{$id}'>{$name}</option>";
        $tos = $tos. $option;
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
        <style>
            .box {
                background-color: white;
            }
            .bbox{
                background-color: black;
            }
        </style>
        <script>
            function oneValue(method){
                method = encodeURIComponent(method);
                var start = document.getElementById("start_date").value;
                if(start == ""){
                    start = null;
                }
                var end = document.getElementById("end_date").value;
                if(end == ""){
                    end = null;
                }
                var from = document.getElementById("from").value;
                if(from == 0){
                    from = null;
                }
                var to = document.getElementById("to").value;
                if(to == 0){
                    to = null;
                }
                var query = document.getElementById("query").value;
                if(query == ""){
                    query = null;
                }
                var type = document.getElementById("type").value;
                if(type == -1){
                    type = null;
                }
                sumTransactions(start, end, from, to, query, type, method);
            }
            $(document).ready(function(){
                getTransactions();
                $("#btn").click(function(){
                    var start = document.getElementById("start_date").value;
                    if(start == ""){
                        start = null;
                    }
                    var end = document.getElementById("end_date").value;
                    if(end == ""){
                        end = null;
                    }
                    var from = document.getElementById("from").value;
                    if(from == 0){
                        from = null;
                    }
                    var to = document.getElementById("to").value;
                    if(to == 0){
                        to = null;
                    }
                    var query = document.getElementById("query").value;
                    if(query == ""){
                        query = null;
                    }
                    var type = document.getElementById("type").value;
                    if(type == -1){
                        type = null;
                    }
                    getTransactions(start, end, from, to, query, type);
                });
                $("#btn2").click(function(){
                    oneValue("SUM");
                });
                $("#btn3").click(function(){
                    oneValue("MAX");
                });
                $("#btn4").click(function(){
                    oneValue("MIN");
                });
                $("#btn5").click(function(){
                    oneValue("COUNT");
                });
            });
        </script>
    </head>
    <body>
        <?php placeHeader($menu);?>
        <div class="col-lg-8 col-lg-offset-2 box col-xs-10 col-xs-offset-1 hidden-xs">
            <div class="col-lg-2">
                <label for="start_date">Start Date</label>
                <input class="form-control" id="start_date" name="start_date" value="">
            </div>
            <div class="col-lg-2">
                <label for="end_date">End Date</label>
                <input class="form-control" id="end_date" name="end_date" value="">
            </div>
            <div class="col-lg-2">
                <label for="from">From Account</label>
                <select name='from' class="form-control selectpicker" id="from">
                    <?php echo $froms; ?>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="to">To Account</label>
                <select name='to' class="form-control selectpicker" id="to">
                    <?php echo $tos; ?>
                </select>
            </div>
            <div class="col-lg-2">
                <label for="query">Search Names</label>
                <input class='form-control' type="text" name="query" value="" id="query">
            </div>
            <div class="col-lg-2">
                <label for="type">Transaction Type</label>
                <select name='type' class="form-control selectpicker" id="type">
                    <option value="-1">All</option>
                    <option value="0">Spend</option>
                    <option value="1">Earn</option>
                    <option value="2">Transfer</option>
                    <option value="3">Accounts Receivable</option>
                    <option value="4">Accounts Received</option>
                    <option value="5">Accounts Payable</option>
                    <option value="6">Accounts Paid</option>
                </select>
            </div>
            &nbsp;
            <div class="col-lg-12">
                <button id='btn' class='btn btn-lg'">Search</button>
                <button id='btn2' class='btn btn-lg'">Sum</button>
                <button id='btn3' class='btn btn-lg'">Max</button>
                <button id='btn4' class='btn btn-lg'">Min</button>
                <button id='btn5' class='btn btn-lg'">Count</button>
            </div>
        </div>
        <?php echo"<div style='margin-top: 2%; margin-bottom: 20%;' id='results' class='box col-lg-8 col-lg-offset-2'></div>"; ?>
        <div class='col-lg-12' style="height:20vh;></div>
    </body>
</html>