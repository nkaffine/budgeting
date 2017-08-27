<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 6:00 PM
 */
    require_once('../db.php');
    require_once('../libraries/transactions.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("23-1-7");
    }
    $user_id = logincheck("23-2", "23-3");
    if(count($_POST)){
        $name = validInputSizeAlpha($_POST['transaction_name'], 100);
        $description = validInputSizeAlpha($_POST['description'], 255);
        if($_POST['date'] != ""){
            $date = process_date($_POST['date'], "23-4", "23-5");
        }
        $amount = validNumbers($_POST['amount'], 10);
        $from_id = validNumbers($_POST['from'], 10);
        $to_id = validNumbers($_POST['to'], 10);
        if(isset($_POST['transaction_type'])){
            $type = validNumbers($_POST['transaction_type'], 1);
        }
        $transaction_id = validNumbers($_POST['transaction_id'], 10);
    }
    if(empty($name) || empty($amount) || empty($from_id) || empty($to_id) ||  !isset($type) || empty($transaction_id)){
        error("23-6-1");
    }
    $query = "select amount, from_account, to_account from ".
        "transactions where user_id = {$user_id} and transaction_id = {$transaction_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("23-7-6");
    }
    if(notUnique($result)){
        error('23-8-3');
    }
    $row = @ mysqli_fetch_array($result);
    $old_amount = $row['amount'];
    $old_from_account = $row['from_account'];
    $old_to_account = $row['to_account'];
    mysqli_begin_transaction($connection);
    $query = "update transactions set transaction_name = '{$name}', amount = {$amount}, from_account = {$from_id}, to_account = {$to_id}, ".
        "transaction_date = '{$date}'";
    if(!empty($description)){
        $query = $query.", description = '{$description}'";
    }
    $query = $query. " where user_id = {$user_id} and transaction_id = {$transaction_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        debug($query);
        error("23-9-6");
    }
    if(mysqli_affected_rows($connection) == -1){
        error("23-10-5");
    }
    switch($type){
        case 0:
            //Spend Transaction
            //Decrease from account
            increaseAccount($old_from_account, $old_amount, "23-11", "23-12", "23-13", "23-14");
            break;
        case 1:
            //Earn Transaction
            //Increase to Account
            decreaseAccount($old_to_account, $old_amount, "23-15", "23-16", "23-17", "23-18");
            break;
        case 2:
            //Transfer Transaction
            //Decrease from account
            increaseAccount($old_from_account, $old_amount, "23-19", "23-20", "23-21", "23-22");
            //Increase to account
            decreaseAccount($old_to_account, $old_amount, "23-23", "23-24","23-25", "23-26");
            break;
        case 3:
            //Account receivable transaction
            //Increase from account
            increaseAccount($old_from_account, $old_amount, "23-27", "23-28", "23-29","23-30");
            decreaseAccount($old_to_account, $old_amount, "23-31", "23-32", "23-33", "23-34");
            break;
        case 4:
            //Accounts received Transaction
            //Decrease from account
            increaseAccount($old_from_account, $old_amount, "23-35", "23-36", "23-37", "23-38");
            //Increase to account
            decreaseAccount($old_to_account, $old_amount, "23-39", "23-40", "23-41", "23-42");
            break;
        case 5:
            //Accounts Payable Transaction
            //Increase to Account
            decreaseAccount($old_to_account, $old_amount, "23-43", "23-44", '23-45', "23-46");
            break;
        case 6:
            //Accounts Paid Transaction
            //Decrease from Account
            increaseAccount($old_from_account, $old_amount, "23-47", "23-48", "23-49", "23-50");
            //Decrease Decrease To Account
            increaseAccount($old_to_account, $old_amount, "23-51", "23-52", "23-53", "23-54");
            break;
    }
    switch($type){
        case 0:
            //Spend Transaction
            //Decrease from account
            decreaseAccount($from_id, $amount, "23-55", "23-56", "23-57", "23-57");
            break;
        case 1:
            //Earn Transaction
            //Increase to Account
            increaseAccount($to_id, $amount, "23-58", "23-59", "23-60", "23-61");
            break;
        case 2:
            //Transfer Transaction
            //Decrease from account
            decreaseAccount($from_id, $amount, "23-62", "23-63", "23-64", "23-65");
            //Increase to account
            increaseAccount($to_id, $amount, "23-66", "23-67","23-68", "23-69");
            break;
        case 3:
            //Account receivable transaction
            //Increase from account
            decreaseAccount($from_id, $amount, "23-70", "23-71", "23-72","23-73");
            increaseAccount($to_id, $amount, "23-74", "23-75", "23-76", "23-77");
            break;
        case 4:
            //Accounts received Transaction
            //Decrease from account
            decreaseAccount($from_id, $amount, "23-78", "23-79", "23-80", "23-81");
            //Increase to account
            increaseAccount($to_id, $amount, "23-82", "23-83", "23-84", "23-85");
            break;
        case 5:
            //Accounts Payable Transaction
            //Increase to Account
            increaseAccount($to_id, $amount, "23-86", "23-87", '23-88', "23-89");
            break;
        case 6:
            //Accounts Paid Transaction
            //Decrease from Account
            decreaseAccount($from_id, $amount, "23-90", "23-91", "23-92", "23-93");
            //Decrease Decrease To Account
            decreaseAccount($to_id, $amount, "23-94", "23-95", "23-96", "23-97");
            break;
    }
    mysqli_commit($connection);
    mysqli_close($connection);
    header("Location: ../transactions.php");
    exit;
?>