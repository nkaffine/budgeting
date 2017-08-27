<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/10/17
 * Time: 8:54 PM
 */
    function getHeaderInfo($page1){
        global $connection;
        $query = "select account_name, curr_balance, account_id from accounts where active = true and ".
            "account_type = 0";
        if(($result = @ mysqli_query($connection, $query))==FALSE){
            error($page1 . "-6");
        }
        $menu = "<li class='dropdown'>
                        <a class='dropdown-toggle' data-toggle='dropdown' href='#employeeof'>Accounts<span class='caret'></span></a>
                        <ul class='dropdown-menu'>";
        while($row = @ mysqli_fetch_array($result)){
            $name = $row['account_name'];
            $balance = $row['curr_balance'];
            $link = "account.php?account_id=" . $row['account_id'];
            $menu = $menu . "<li><a href='" . $link . "'><div style='font-weight: bold;'>" . $name . "</div><div>$" . $balance . "</div></a></li>";
        }
        $menu = $menu . "</ul></li>";
        return $menu;
    }

    function placeHeader($menu){
        echo"<nav class='navbar navbar-inverse'>
                <div class='container-fluid'>
                    <div class='navbar-header'>
                        <a class='navbar-brand' href='/home.php'>Budgeting</a>
                    </div>
                    <ul class='nav navbar-nav'>
                        <li><a href='home.php'>Home</a></li>
                        <li><a href='/transactions.php'>Transactions</a></li>   
                        <li><a href='/accounts.php'>Accounts</a></li>                
                        <li><a href='/categories.php'>Categories</a></li>
                        <li><a href='accountsPayable.php'>Accounts Payable</a></li>
                        <li><a href='accountsReceivable.php'>Accounts Receivable</a></li>
                    </ul>
                    <ul class='nav navbar-nav navbar-right'>";
        echo"{$menu}";
        echo"</ul>
                </div>
            </nav>";
    }
?>