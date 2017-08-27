<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/13/17
 * Time: 1:22 PM
 */
function decreaseAccount($account_id, $amount, $page1, $page2, $page3, $page4){
    global $connection;
    global $user_id;
    $query = "select balance_type from accounts where account_id = {$account_id} and user_id = {$user_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error($page1."-6");
    }
    if(notUnique($result)){
        error($page2."-3");
    }
    $row = @ mysqli_fetch_array($result);
    $type = $row['balance_type'];
    if($type == 1){
        //Its credit so add
        $operation = "+";
    } else {
        //Its debit so subtract
        $operation = "-";
    }
    $query = "update accounts set curr_balance = curr_balance {$operation} {$amount} where account_id = {$account_id} and ".
        "user_id = {$user_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        debug($query);
        error($page3."-6");
    }
    if(mysqli_affected_rows($connection)==-1){
        error($page4."-7");
    }
}
function increaseAccount($account_id, $amount, $page1, $page2, $page3, $page4){
    global $connection;
    global $user_id;
    $query = "select balance_type from accounts where account_id = {$account_id} and user_id = {$user_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error($page1."-6");
    }
    if(notUnique($result)){
        error($page2."-3");
    }
    $row = @ mysqli_fetch_array($result);
    $type = $row['balance_type'];
    if($type == 1){
        //Its credit so add
        $operation = "-";
    } else {
        //Its debit so subtract
        $operation = "+";
    }
    $query = "update accounts set curr_balance = curr_balance {$operation} {$amount} where account_id = {$account_id} and ".
        "user_id = {$user_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        debug($query);
        error($page3."-6");
    }
    if(mysqli_affected_rows($connection) == -1){
        error($page4 . "-7");
    }
}
