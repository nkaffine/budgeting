<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 9/5/17
 * Time: 2:09 PM
 */
function getAccounts($user_id, $account_type){
    return "select account_name, account_id, curr_balance, init_balance, balance_type, account_type from accounts left join (select account_id, count(account_id) ".
        "as freq from (select from_account as account_id from transactions where user_id = {$user_id} union all select to_account ".
        "as account_id from transactions where user_id = {$user_id}) as r1 group by account_id) as r2 using (account_id) where account_type = ".
        "{$account_type} and user_id = {$user_id} and active = 1 order by freq desc";
}