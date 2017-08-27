<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/11/17
 * Time: 10:45 PM
 */
    require_once('../db.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))) {
        error("8-1-7");
    }
    $user_id = logincheck("8-2", "8-3");
    if(isset($_GET['start'])){
        $start = process_date($_GET['start'], "8-4", "8-5");
    }
    if(isset($_GET['end'])){
        $end = process_date($_GET['end'],"8-6", "8-7");
    }
    if(isset($_GET['to'])){
        $to = validNumbers($_GET['to'], 10);
    }
    if(isset($_GET['to_name'])){
        $to_name = validInputSizeAlpha($_GET['to_name'], 255);
    }
    if(isset($_GET['from'])){
        $from = validNumbers($_GET['from'], 10);
    }
    if(isset($_GET['from_name'])){
        $to_name = validInputSizeAlpha($_GET['from_name'], 255);
    }
    if(isset($_GET['query'])){
        $search = validInputSizeAlpha($_GET['query'], 255);
    }
    if(isset($_GET['type'])){
        $type = validNumbers($_GET['type'], 1);
    }
    if(isset($_GET['method'])){
        $method = validInputSizeAlpha($_GET['method'], 50);
    }
    if(empty($method)){
        error("8-8-1");
    }
    $query = "select ".$method."(if(type = 0, -amount, amount)) as total from transactions";
    if(isset($start) || isset($end) || isset($to) || isset($from) || isset($search) || isset($type)){
        $query = $query . " where user_id = {$user_id} and active = 1";
        if(isset($start)){
            $query = $query . "transaction_date >= '{$start}' and ";
        }
        if(isset($end)){
            $query = $query . "transaction_date <= '{$end}' and ";
        }
        if(isset($to)){
            $query = $query . "(if(to_id is null, category_id, to_id) = {$to} and if(to_id is null, ".
                "(select name from categories where id = category_id), (select ".
                "name from accounts where id = to_id)) = '{$to_name}') and ";
        }
        if(isset($from)){
            $query = $query . "(if(from_id is null, category_id, from_id) = {$from} and if(from_id is null, ".
                "(select name from categories where id = category_id), (select name ".
                "from accounts where id = from_id)) = '{$from_name}') and ";
        }
        if(isset($search)){
            $query = $query . "transaction_name like '%{$search}%' and ";
        }
        if(isset($type)){
            $query = $query . "type = {$type} and ";
        }
        $query = substr($query, 0, strlen($query) - 4);
    }
    $query = $query . " order by transaction_date desc, date_added desc";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        error("8-9-6");
    }
    header("Content-type: text/xml");

    $dom = new DOMDocument("1.0");
    $results = $dom->createElement("results");
    $resultsNode = $dom->appendChild($results);

    $transactions = $dom->createElement("transactions");
    $transactionsNode = $resultsNode->appendChild($transactions);

    while($row = mysqli_fetch_array($result)){
        $transaction = $dom->createElement("transaction");
        $newTransaction = $transactionsNode->appendChild($transaction);
        $newTransaction->setAttribute("amount", $row['total']);
    }
    echo $dom->saveXML();