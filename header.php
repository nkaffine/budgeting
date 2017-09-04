<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/10/17
 * Time: 8:54 PM
 */
    function getHeaderInfo($page1, $page2){
        global $connection;
        global $user_id;
        $query = "select account_name, curr_balance, account_id from accounts where active = true and ".
            "account_type = 0 and user_id = {$user_id}";
        if(($result = @ mysqli_query($connection, $query))==FALSE){
            error($page1 . "-6");
        }
        $menu = "<li class='dropdown'>
                        <a class='dropdown-toggle' data-toggle='dropdown'>Accounts<span class='caret'></span></a>
                        <ul class='dropdown-menu'>";
        while($row = @ mysqli_fetch_array($result)){
            $name = $row['account_name'];
            $balance = $row['curr_balance'];
            $link = "account.php?account_id=" . $row['account_id'];
            $menu = $menu . "<li><a href='" . $link . "'><div style='font-weight: bold;'>" . $name . "</div><div>$" . $balance . "</div></a></li>";
        }
        $menu = $menu . "</ul></li>";
        $menu = array($menu);
        $query = "select sum(if(balance_type = 1, curr_balance * -1, curr_balance)) as equity from accounts where user_id = {$user_id} and active = 1 and long_term = 0";
        if(($result = @ mysqli_query($connection, $query))==FALSE){
            debug($query);
            error($page2 . "-30");
        }
        $row = @ mysqli_fetch_array($result);
        $equity = $row['equity'];
        if($equity >= 0){
            $equity = "$".$equity;
        } else {
            $equity = "$(".$equity.")";
        }
        array_push($menu, $equity);
        $query = "select sum(if(balance_type = 1, curr_balance * -1, curr_balance)) as equity from accounts where user_id = {$user_id} and active = 1";
        if(($result = @ mysqli_query($connection, $query))==FALSE){
            debug($query);
            error($page2 . "-30");
        }
        $row = @ mysqli_fetch_array($result);
        $equity = $row['equity'];
        if($equity >= 0){
            $equity = "$".$equity;
        } else {
            $equity = $equity * -1;
            $equity = "$(".$equity.")";
        }
        array_push($menu, $equity);
        return $menu;
    }

    function placeHeader($menu){
        echo"<nav class='navbar navbar-inverse'>
                <div class='container-fluid'>
                    <div class='navbar-header'>
                        <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='#myNavbar'>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                            <span class='icon-bar'></span>
                        </button>
                        <a class='navbar-brand' href='/home.php'>Budgeting</a>
                    </div>
                    <div class='collapse navbar-collapse' id='myNavbar'>
                        <ul class='nav navbar-nav'>
                            <li><a href='home.php'>Home</a></li>
                            <li><a href='/transactions.php'>Transactions</a></li>   
                            <li><a href='/accounts.php'>Accounts</a></li>                
                            <li><a href='/categories.php'>Categories</a></li>
                            <li><a href='accountsPayable.php'>Accounts Payable</a></li>
                            <li><a href='accountsReceivable.php'>Accounts Receivable</a></li>
                        </ul>
                        <ul class='nav navbar-nav navbar-right'>
                            <li class='dropdown'><a class='dropdown-toggle' data-toggle='dropdown'>Equity: {$menu[1]}<span class='caret'></span></a>
                                <ul class='dropdown-menu'>
                                    <li><a class='nonClickA'>Short Term: {$menu[1]}</a></li>
                                    <li><a class='nonClickA'>Long Term: {$menu[2]}</a></li>
                                </ul>
                            </li>";
                        echo"{$menu[0]}";
                        echo"</ul>
                    </div>
                </div>
            </nav>";
    }
?>