<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 11:19 AM
 */
    require_once($_SERVER['DOCUMENT_ROOT'].'/db.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/libraries/transactions.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("12-1-7");
    }
    $user_id = logincheck("12-2", "12-3");
    if(count($_POST)){
        $transaction_id = validNumbers($_POST['id'], 10);
    }
    if(empty($transaction_id)){
        error("12-4-1");
    }
    $query = "select amount, from_account, to_account, type from transactions where user_id = {$user_id} and ".
        "transaction_id = {$transaction_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("12-5-6");
    }
    if(notUnique($result)){
        error("12-6-3");
    }
    $row = @ mysqli_fetch_array($result);
    $amount = $row['amount'];
    $from_id = $row['from_account'];
    $to_id = $row['to_account'];
    $type = $row['type'];

    mysqli_begin_transaction($connection);
    $query = "update transactions set active = 0 where user_id = {$user_id} and transaction_id = {$transaction_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("12-7-6");
    }
    if(mysqli_affected_rows($connection) == -1){
        error("12-8-4");
    }
    switch($type){
        case 0:
            //Spend Transaction
            //Decrease from account
            increaseAccount($from_id, $amount, "12-9", "12-10", "12-11", "12-12");
            break;
        case 1:
            //Earn Transaction
            //Increase to Account
            decreaseAccount($to_id, $amount, "12-13", "12-14", "12-15", "12-16");
            break;
        case 2:
            //Transfer Transaction
            //Decrease from account
            increaseAccount($from_id, $amount, "12-17", "12-18", "12-19", "12-20");
            //Increase to account
            decreaseAccount($to_id, $amount, "12-21", "12-22","12-23", "12-24");
            break;
        case 3:
            //Account receivable transaction
            //Increase from account
            increaseAccount($from_id, $amount, "12-25", "12-26", "12-27","12-28");
            decreaseAccount($to_id, $amount, "12-29", "12-30", "12-31", "12-32");
            break;
        case 4:
            //Accounts received Transaction
            //Decrease from account
            increaseAccount($from_id, $amount, "12-33", "12-34", "12-35", "12-36");
            //Increase to account
            decreaseAccount($to_id, $amount, "12-37", "12-38", "12-39", "12-40");
            break;
        case 5:
            //Accounts Payable Transaction
            //Increase to Account
            decreaseAccount($to_id, $amount, "12-41", "12-42", '12-43', "12-44");
            break;
        case 6:
            //Accounts Paid Transaction
            //Decrease from Account
            increaseAccount($from_id, $amount, "12-45", "12-46", "12-47", "12-48");
            //Decrease Decrease To Account
            increaseAccount($to_id, $amount, "12-49", "12-50", "12-51", "12-52");
            break;
    }
    mysqli_commit($connection);
    mysqli_close($connection);
    header("Location: ../transactions.php");
    exit;
?>