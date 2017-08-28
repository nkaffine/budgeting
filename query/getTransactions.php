<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/11/17
 * Time: 10:45 PM
 */
    require_once('../db.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))) {
        showerror($connection);
    }
    $user_id = logincheck("7-1", "7-2");
    if(isset($_GET['start'])){
        $start = process_date($_GET['start'], "7-3", "7-4");
    }
    if(isset($_GET['end'])){
        $end = process_date($_GET['end'], "7-5", "7-6");
    }
    if(isset($_GET['to'])){
        $to = validNumbers($_GET['to'], 10);
    }
    if(isset($_GET['from'])){
        $from = validNumbers($_GET['from'], 10);
    }
    if(isset($_GET['from_name'])){
        $from_name = validInputSizeAlpha($_GET['from_name'], 255);
    }
    if(isset($_GET['to_name'])){
        $to_name = validInputSizeAlpha($_GET['to_name'], 255);
    }
    if(isset($_GET['query'])){
        $search = validInputSizeAlpha($_GET['query'], 255);
    }
    if(isset($_GET['type'])){
        $type = validNumbers($_GET['type'], 1);
    }
    $query = "select transaction_id, transaction_name, type, description, transaction_date, transaction_id, amount, ".
        "froms.account_name as from_name, tos.account_name as to_name, (select balance_type from accounts where user_id ".
        "= {$user_id} and account_id = to_account) as to_type, (select balance_type from accounts where user_id = {$user_id} ".
        "and account_id = from_account) as from_type from transactions left join ".
        "(select account_id as from_account, account_name from accounts where user_id = {$user_id}) as froms ".
        "using (from_account) left join (select account_id as to_account, account_name from accounts where ".
        "user_id = {$user_id}) as tos using(to_account) where user_id = {$user_id} and active = 1 ";
    if(isset($start) || isset($end) || isset($to) || isset($from) || isset($search) || isset($type)){
        $query = $query . "and ";
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
    $query = $query . "order by transaction_date desc, date_added desc";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        debug($query);
        showerror($connection);
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
        $newTransaction->setAttribute("name", $row['transaction_name']);
        $newTransaction->setAttribute("type", $row['type']);
        $newTransaction->setAttribute("description", $row['description']);
        $newTransaction->setAttribute("date", date("D M d Y H:i:s", strtotime($row['transaction_date'])) . " GMT-0700");
        $newTransaction->setAttribute("from_name", $row['from_name']);
        $newTransaction->setAttribute("to_name", $row['to_name']);
        $newTransaction->setAttribute("id", $row['transaction_id']);
        $newTransaction->setAttribute("amount", $row['amount']);
        $newTransaction->setAttribute("to_type", $row['to_type']);
        $newTransaction->setAttribute("from_type", $row['from_type']);
    }
    echo $dom->saveXML();
