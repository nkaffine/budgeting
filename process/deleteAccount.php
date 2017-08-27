<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 4:40 PM
 */
    require_once('../db.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("21-1-7");
    }
    $user_id = logincheck("21-2", "21-3");
    if(count($_POST)){
        if(isset($_POST['account_id'])){
            $account_id = validNumbers($_POST['account_id'], 10);
        }
        if(isset($_POST['type'])){
            $type = validNumbers($_POST['type'], 1);
        }
    }
    if(!isset($account_id) || !isset($type)){
        error("21-4-1");
    }
    $query = "update accounts set active = 0 where account_id = {$account_id} and user_id = {$user_id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("21-5-6");
    }
    if(mysqli_affected_rows($connection)==-1){
        error("21-6-4");
    }
    if($type == 0){
        header("Location: ../accounts.php");
    } else if($type == 1 || $type == 2){
        header("Location: ../categories.php");
    } else if($type == 3){
        header("Location: ../accountsReceivable.php");
    } else if($type == 4){
        header("Location: ../accountsPayable.php");
    } else {
        header("Locations: ../home.php");
    }
    exit;
?>