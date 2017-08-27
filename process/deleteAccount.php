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
    $query = "select"
?>