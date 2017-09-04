<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/13/17
 * Time: 11:03 AM
 */
    require_once('../db.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        error("10-0-7");
    }
    $user_id = logincheck("10-1", "10-2");
    if(count($_GET)){
        $name = validInputSizeAlpha($_GET['name'], 50);
        if(isset($_GET['balance'])){
            $balance = validNumbers($_GET['balance'], 10);
        }
        if(isset($_GET['balance_type'])) {
            $balance_type = validNumbers($_GET['balance_type'], 1);
        }
        if(isset($_GET['term'])){
            $term = validNumbers($_GET['term'], 1);
        }
        if(isset($_GET['account_type'])){
            $account_type = validNumbers($_GET['account_type'], 1);
        }
    }
    if(empty($name)|| !isset($account_type)){
        error("10-3-1");
        header("Content-type: text/xml");

        $dom = new DOMDocument("1.0");
        $results = $dom->createElement("results");
        $resultsNode = $dom->appendChild($results);

        $resultsNode->setAttribute("error", "Not all required values were submitted");
        echo $dom->saveXML();
    } else {
        $query = "select max(account_id) from accounts";
        if(($result = @ mysqli_query($connection, $query))==FALSE){
            error("10-4-6");
        }
        $row = @ mysqli_fetch_array($result);
        $account_id = $row['max(account_id)'] + 1;
        $columns = "(account_name, account_type, account_id, user_id";
        $values = "('{$name}', {$account_type}, {$account_id}, {$user_id}";
        if(isset($balance)){
            $columns = $columns . ", init_balance, curr_balance";
            $values = $values . ", {$balance}, {$balance}";
        }
        if(isset($balance_type)){
            $columns = $columns . ", balance_type";
            $values = $values . ", {$balance_type}";
        }
        if(isset($term)){
            $columns = $columns . ", long_term";
            $values = $values . ", {$term}";
        }
        $columns = $columns . ")";
        $values = $values . ")";
        $query = "insert into accounts {$columns} values {$values}";
        if(($result = mysqli_query($connection, $query))==FALSE){
            error("10-5-6");
        }
    }
    header("Location: ../home.php");
    exit;

//    header("Content-type: text/xml");
//
//    $dom = new DOMDocument("1.0");
//    $results = $dom->createElement("results");
//    $resultsNode = $dom->appendChild($results);
//
//    $users = $dom->createElement("users");
//    $usersNode = $resultsNode->appendChild($users);
//
//    while($row = mysqli_fetch_array($result)){
//        $user = $dom->createElement("user");
//        $newUser = $usersNode->appendChild($user);
//        $newUser->setAttribute("name", $row['name']);
//        $newUser->setAttribute("id", $row['user_id']);
//    }
//    echo $dom->saveXML();
?>