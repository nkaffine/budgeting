<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/13/17
 * Time: 1:02 PM
 */
    require_once('../db.php');
    require_once('../libraries/transactions.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        showerror($connection);
    }
    if(count($_POST)){
        $name = validInputSizeAlpha($_POST['transaction_name'], 100);
        $description = validInputSizeAlpha($_POST['description'], 255);
        if($_POST['date'] != ""){
            $date = process_date($_POST['date'], "11-1", "11-2");
        }
        $amount = validNumbers($_POST['amount'], 10);
        $from_id = validNumbers($_POST['from'], 10);
        $to_id = validNumbers($_POST['to'], 10);
        if(isset($_POST['transaction_type'])){
            $type = validNumbers($_POST['transaction_type'], 1);
        }
    }
    $user_id = logincheck("11-3", "11-4");
    if(empty($name) || empty($amount) || empty($from_id) || empty($to_id) ||  !isset($type)){
        error("11-5-1");
    }
    $query = "select max(transaction_id) from transactions where user_id = {$user_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("11-6-6");
    }
    $row = @ mysqli_fetch_array($result);
    $transaction_id = $row['max(transaction_id)'] + 1;
    $columns = "transaction_id, user_id, transaction_name, amount, from_account, to_account, type, transaction_date";
    $values = "{$transaction_id}, {$user_id}, '{$name}', {$amount}, {$from_id}, {$to_id}, {$type}";
    if(empty($date)){
        $values = $values . ", current_date";
    } else {
        $values = $values . ", '{$date}'";
    }
    if(!empty($description)){
        $columns = $columns.", description";
        $values = $values . ", '{$description}'";
    }
    mysqli_begin_transaction($connection);
    $query = "insert into transactions ({$columns}) values ({$values})";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        debug($query);
        error("11-7-6");
    }
    if(mysqli_affected_rows($connection) == -1){
        error("11-8-5");
    }
    switch($type){
        case 0:
            //Spend Transaction
            //Decrease from account
            decreaseAccount($from_id, $amount, "11-9", "11-10", "11-11", "11-12");
            break;
        case 1:
            //Earn Transaction
            //Increase to Account
            increaseAccount($to_id, $amount, "11-13", "11-14", "11-15", "11-16");
            break;
        case 2:
            //Transfer Transaction
            //Decrease from account
            decreaseAccount($from_id, $amount, "11-17", "11-18", "11-19", "11-20");
            //Increase to account
            increaseAccount($to_id, $amount, "11-21", "11-22","11-23", "11-24");
            break;
        case 3:
            //Account receivable transaction
            //Increase from account
            decreaseAccount($from_id, $amount, "11-25", "11-26", "11-27","11-28");
            increaseAccount($to_id, $amount, "11-29", "11-30", "11-31", "11-32");
            break;
        case 4:
            //Accounts received Transaction
            //Decrease from account
            decreaseAccount($from_id, $amount, "11-33", "11-34", "11-35", "11-36");
            //Increase to account
            increaseAccount($to_id, $amount, "11-37", "11-38", "11-39", "11-40");
            break;
        case 5:
            //Accounts Payable Transaction
            //Increase to Account
            increaseAccount($to_id, $amount, "11-41", "11-42", '11-43', "11-44");
            break;
        case 6:
            //Accounts Paid Transaction
            //Decrease from Account
            decreaseAccount($from_id, $amount, "11-45", "11-46", "11-47", "11-48");
            //Decrease Decrease To Account
            decreaseAccount($to_id, $amount, "11-49", "11-50", "11-51", "11-52");
            break;
    }
    mysqli_commit($connection);
    mysqli_close($connection);
    header("Location: ../home.php");
    exit;
?>