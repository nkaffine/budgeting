<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 1:14 PM
 */
    require_once('../db.php');
    if(!($connection = mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("16-1-7");
    }
    $user_id = logincheck("16-2", "16-3");
    if(count($_POST)){
        if(isset($_POST['account_id'])){
            $account_id = validNumbers($_POST['account_id'], 10);
        }
        if(isset($_POST['curr_balance'])){
            $curr_balance = validNumbers($_POST['curr_balance'], 10);
        }
        if(isset($_POST['init_balance'])){
            $init_balance = validNumbers($_POST['init_balance'], 10);
        }
        if(isset($_POST['name'])){
            $name = validInputSizeAlpha($_POST['name'], 255);
        }
        if(isset($_POST['type'])){
            $type = validNumbers($_POST['type'], 1);
        }
    }
    if(!isset($name) || !isset($account_id) || !isset($type)) {
        error("16-4-1");
    }
    if($type == 0 && !isset($curr_balance) && !isset($init_balance)){
        error("16-5-1");
    }
    $query = "update accounts set account_name = '{$name}'";
    if($type == 0 || $type == 3 || $type == 4){
        $query1 = "select curr_balance, init_balance from accounts where account_id = {$account_id} and user_id = {$user_id}";
        if(($result = @ mysqli_query($connection, $query1))==FALSE){
            error("16-6-6");
        }
        if(notUnique($result)){
            error("16-7-3");
        }
        $row = @ mysqli_fetch_array($result);
        $old_curr_balance = $row['curr_balance'];
        $old_init_balance = $row['init_balance'];
        $diff_in_init_balance = $init_balance - $old_init_balance;
        $query = $query . ", curr_balance = {$curr_balance} + {$diff_in_init_balance}, init_balance = {$init_balance}";
    }
    $query = $query. " where user_id = {$user_id} and account_id = {$account_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        debug($query);
        error("16-8-6");
    }
    if(mysqli_affected_rows($connection) == -1){
        error("16-9-4");
    }
    if($type == 0){
        header("Location: ../accounts.php");
    } else if($type == 1 || $type == 2){
        header("Location: ../categories.php");
    } else if($type == 4) {
        header("Location: ../accountsPayable.php");
    } else if($type == 3) {
        header("Location: ../accountsReceivable.php");
    } else {
        header("Location: ../home.php");
    }
    exit;
?>