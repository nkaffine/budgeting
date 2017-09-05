<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 9/3/17
 * Time: 9:00 PM
 */
    require_once($_SERVER['DOCUMENT_ROOT'].'/db.php');
    require_once('../libraries/transactions.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("26-0-7");
    }
    $user_id = logincheck("26-1", "26-2");
    if(count($_POST)){
        $transaction_name = validInputSizeAlpha($_POST['transaction_name'], 50);
        $description = validInputSizeAlpha($_POST['description'], 255);
        if($_POST['date'] != ""){
            $date = process_date($_POST['date'], "26-3", "26-4");
        }
        $amount = validNumbers($_POST['amount'], 10);
        $from_account = validNumbers($_POST['from'], 10);
        $ar = validNumbers($_POST['ar'], 10);
        $spending_category = validNumbers($_POST['to'], 10);
        if(isset($_POST['transaction_type'])){
            $type = validNumbers($_POST['transaction_type'], 1);
        }
    }
    if(empty($transaction_name) || empty($amount) || empty($from_account) || empty($ar) || empty($spending_category) || !isset($type)){
        error("26-5-1");
    }
    $personAmount = ceil((($amount / 2) * 100))/100;
    $ownerAmount = $amount - $personAmount;
    $query = "select max(transaction_id) from transactions where user_id = {$user_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        debug($query);
        error("26-6-6");
    }
    $row = @ mysqli_fetch_array($result);
    $transaction_id = $row['max(transaction_id)'] + 1;
    $columns = "transaction_id, user_id, transaction_name, amount, from_account, to_account, type, transaction_date";
    $values = "{$transaction_id}, {$user_id}, '{$transaction_name}', {$ownerAmount}, {$from_account}, {$spending_category}, 0";
    if(empty($date)){
        $values = $values . ", current_date";
    } else {
        $values = $values . ", '{$date}'";
    }
    if(!empty($description)) {
        $columns = $columns . ", description";
        $values = $values . ", '{$description}'";
    }
    $transaction_id = $transaction_id + 1;
    $columns2 = "transaction_id, user_id, transaction_name, amount, from_account, to_account, type, transaction_date";
    $values2 = "{$transaction_id}, {$user_id}, '{$transaction_name}', {$personAmount}, {$from_account}, {$ar}, 3";
    if(empty($date)){
        $values2 = $values2 . ", current_date";
    } else {
        $values2 = $values2 . ", '{$date}'";
    }
    if(!empty($description)) {
        $columns2 = $columns2 . ", description";
        $values2 = $values2 . ", '{$description}'";
    }
    mysqli_begin_transaction($connection);
    $query = "insert into transactions ({$columns}) values ({$values})";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("26-7-6");
    }
    if(mysqli_affected_rows($connection) == -1){
        error("26-8-5");
    }
    $query = "insert into transactions ({$columns2}) values ({$values2})";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        debug($query);
        error("26-9-6");
    }
    if(mysqli_affected_rows($connection) == -1){
        error("26-10-5");
    }
    decreaseAccount($from_account, $amount, "26-11", "26-12", "26-13", "26-14");
    increaseAccount($ar, $personAmount, "26-15", "26-16", "26-17", "26-18");
    mysqli_commit($connection);
    mysqli_close($connection);
    header("Location: ../home.php");
    exit;
?>