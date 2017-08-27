<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/27/17
 * Time: 5:18 PM
 */
    require_once('db.php');
    require_once('header.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("22-1-7");
    }
    $user_id = logincheck("22-2", "22-3");
    $menu = getHeaderInfo("22-4");
    if(count($_POST)){
        $transaction_id = validNumbers($_POST['transaction_id'], 10);
    }
    if(!isset($transaction_id)){
        error("22-5-1");
    }
    $query = "select "
?>