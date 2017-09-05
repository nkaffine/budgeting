<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/6/17
 * Time: 1:29 PM
 */
	//Initializing all of the global variables needed
	$DB_hostname = "budgetingdb.kaffine.tech";
	$DB_username = "budgeting_user";
	$DB_password = fgets(fopen($_SERVER['DOCUMENT_ROOT'] .'/license', 'r'));
	$DB_databasename = "kaftechbudgeting";

	// Show an error and stop the script
	function showerror($connection){
        // If there was an error in the connection
        if(mysqli_connect_errno()){
            die("Error " . mysqli_connect_errno($connection) . " : " .
                mysqli_connect_error($connection));
        }
    }

	//Cleaning up the input from the user
	function clean($input, $maxLength) {
        // Acces the mysqli connection from outside of the function
        global $connection;

        // Limit the length of the string
        $input = substr($input, 0, $maxLength);

        // Escapes the semicolons and single quotes if maginc quotes are off.
        if(get_magic_quotes_gpc()){
            $input = stripslashes($input);
        }
        $input = mysqli_real_escape_string($connection, $input);
        return $input;
    }

	// Checks if the user is logged in, if they are not, send them to the home page
	function logincheck($page1, $page2) {
        global $connection;
        session_start();
        if(empty($_SESSION['id'])){
            // redirect them to the login page
            $message = "Please login";
            header("Location: /index.php?message=" . urlencode($message));
            exit;
        }
        if($_SESSION['key'] != $_COOKIE['sessionId']){
            $message = "Please login";
            setcookie('sessionId', 'blah', time() - (86400 * 30), "/");
            session_destroy();
            header("Location: /index.php?message=" . urlencode($message));
        }
        $id = $_SESSION['id'];
        $query = "select user_id from users where facebook_id = {$id}";
        if(($result = mysqli_query($connection, $query))==FALSE){
            error($page1 . "-6");
        }
        if(notUnique($result)){
            error($page2."-3");
        }
        $row = @ mysqli_fetch_array($result);
        return $row['user_id'];
    }

	// Checks if there is only one row in the result
    function notUnique($result){
        return (mysqli_num_rows($result) != 1);
    }

    //Checks to see if the user_type of the user matches the ones in the given array
    function hasAccess($levels, $id, $page){
        global $connection;

        $query = "select user_type from users where facebook_id = {$id}";
        if(($result = mysqli_query($connection, $query))==FALSE){
            showerror($connection);
        }
        if(notUnique($result)){
            error($page . "-2");
        }
        $row = mysqli_fetch_array($result);
        $user_type = $row['user_type'];
        foreach($levels as &$value){
            if($value = $user_type) {
                return true;
            };
        }
        $message = "You do not have permission to access that page";
        header("Location: home.php?message=" . urlencode($message));
        exit;
    }

    function canSee($levels, $page) {
        global $connection;

        $query = "select level from users where facebook_id = {$_SESSION['id']}";
        if(($result = mysqli_query($connection, $query))==FALSE){
            showerror($connection);
        }
        if(notUnique($result)){
            error($page."-2");
        }
        $row = mysqli_fetch_array($result);
        $level = $row['level'];
        foreach($levels as &$value){
            if($value = $level) {
                return true;
            };
        }
        return false;
    }

    function validInputSizeAlpha($uncleanString, $maxLength) {
        $sizedString=substr($uncleanString, 0, $maxLength);
        $cleanString= preg_replace("[^\.a-zA-Z' ]",'',$sizedString);
        return($cleanString);
    }
    function validInputSize($uncleanString, $maxLength) {
        $cleanString=substr($uncleanString, 0, $maxLength);
        return($cleanString);
    }
    function validNumbers($uncleanString, $maxLength) {
        $cleanString=substr($uncleanString, 0, $maxLength);
        $cleanString = preg_replace("[^\.0-9]",'',$cleanString);
        return($cleanString);
    }
    function validInputDate($uncleanString, $maxLength) {
        $cleanString=substr($uncleanString, 0, $maxLength);
        //This should be in the form MM/DD/YYYY
        if(preg_replace("[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}", $cleanString)){
            //Check to see if it is a date before today

            $cleanDate = $cleanString;
        }
        //But is could be in the form MM/DD/YY
        elseif(ereg("[0-9]{1,2}/[0-9]{1,2}/[0-9]{2}", $cleanString)) {
            echo "Hey, I am a part year!";
            $monthNumber = substr($cleanString,0,2);
            $day = substr($cleanString,3,2);
            $partYear = substr($cleanString,6,2);
            $year = "20" . $partYear;
            echo "DAy: $day, Month $monthNumber, year $partYear";
            $cleanDate = $monthNumber . "/" . $day. "/" .$year ;
        }
        else{
            # This is not a good date
            $cleanDate = 'BAD';
        }
        return($cleanDate);

    }
    function validDate($uncleanString, $maxLength) {

        $cleanString=substr($uncleanString,0,$maxLength);
        if (preg-match("[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}", $cleanString)) {
            $dateString=split('/',$cleanString);
            $cleanDate=$dateString[2] .'-' .$dateString[0] .'-' . $dateString[1];
        }
        return($cleanDate);
    }
    function validInputDateIsPast($uncleanString, $maxLength) {
        $cleanString=substr($uncleanString, 0, $maxLength);
        $today=time();
        //This should be in the form MM/DD/YYYY
        if(ereg("[0-9]{2}/[0-9]{2}/[0-9]{4}", $cleanString)){
            //Check to see if it is a date before today
            $monthNumber = substr($cleanString,0,2);
            $day = substr($cleanString,3,2);
            $year = substr($cleanString,6,4);
            $thisTime=mktime(0,0,0,$monthNumber, $day, $year);
            if ($thisTime >= $today) {
                $isPast='0';
            }
            else {
                //This date is in the past
                $isPast='1';
            }
            $cleanDate = $cleanString;
        }
        //But it could be in the form MM/DD/YY
        elseif(ereg("[0-9]{2}/[0-9]{2}/[0-9]{2}", $cleanString)) {
            $monthNumber = substr($cleanString,0,2);
            $day = substr($cleanString,3,2);
            $partYear = substr($cleanString,6,2);
            $year = "20" . $partYear;
            $cleanDate = $monthNumber . "/" . $day. "/" .$year ;
            $thisTime=mktime(0,0,0,$monthNumber, $day, $year);
            if ($thisTime >= $today) {
                $isPast='0';
            }
            else {
                //This date is in the past
                $isPast='1';
            }
        }
        else{
            # This is not a good date
            $cleanDate = 'BAD';
        }
        return array($cleanDate, $isPast);

    }
    function isDefined ($value){
        $exists = FALSE;
        if(isset($value) && ($value != ''  && $value != ' ')){
            $exists =TRUE;
        }
        return $exists;
    }
    function convertDBDate ($dbDate){

        $year = substr($dbDate,0,4);
        $monthNumber = substr($dbDate,5,2);
        if (substr($monthNumber,0,1) == '0'){
            $monthNumber  = substr($dbDate,6,1);
        }
        $day = substr($dbDate,8,2);
        $monthName = getMonthName($monthNumber);
        $readableDate = $monthName . " " . $day . ", " . $year;
        return $readableDate;


    }
    function flipDBDate ($dbDate){

        $year = substr($dbDate,0,4);
        $monthNumber = substr($dbDate,5,2);

        $day = substr($dbDate,8,2);

        $readableDate = $monthNumber. "/" . $day . "/" . $year;
        return $readableDate;


    }
    function flipDateBacktoDB ($formDate){
        $year = substr($formDate,6,4);
        $monthNumber = substr($formDate,0,2);
        $day = substr($formDate,3,2);
        $dbDate = $year. "-" . $monthNumber . "-" . $day;
        return $dbDate;
    }
    function addDays ($dbDate, $numDays){
        $year = substr($dbDate,0,4);
        $monthNumber = substr($dbDate,5,2);
        $day = substr($dbDate,8,2);
        $day=$day+$numDays;
        $newDate = date("Y-m-d", mktime(0, 0, 0, $monthNumber, $day, $year));
        return $newDate;
    }
    function getMonthName ($monthNumber){
        $months = array (' ','January','February','March','April','May','June','July','August','September','October','November','December');
        $monthName=$months[$monthNumber];
        return ($monthName);

    }
    function getTimeAndUnit ($classLengthIn){

        if (($classLengthIn < 1) && ($classLengthIn !=0)) {
            $classLengthOut = $classLengthIn*8;
            $timeUnit = "hour";
        }
        else {
            $classLengthOut = $classLengthIn;
            $timeUnit = "day";
        }

        if ($classLengthOut > 1) {
            $timeUnit = $timeUnit .'s' ;
        }



        return array($classLengthOut, $timeUnit);

    }
    function convertHoursToDays ($classLengthIn, $classTimeUnit){
        /* One day is 8 hours */
        if ($classTimeUnit == 'hours' )  {
            $classLengthOut = $classLengthIn/8;
        }
        else {
            $classLengthOut = $classLengthIn;
        }


        return $classLengthOut;

    }
    function getOptions($option, $page){
        if ($option == null) {
            return "Unsure";
        } else if ($option == 0) {
            return "No";
        } else if ($option == 1) {
            return "Yes";
        } else {
            error($page . "-4");
        }
    }

    function error($error_code){
        $message = "Something went wrong, please contact the administrator. Error code: {$error_code}";
        header("Location: /error.php?message=" . urlencode($message));
        exit;
    }

    function debug($error){
        header("Location: /error.php?message=" . urlencode($error));
        exit;
    }

    function update_recent_lonlat($lon, $lat, $page){
        global $connection;
        $query = "update users set recent_lon = $lon, recent_lat = $lat where facebook_id = {$_SESSION['id']}";
        if(($result = mysqli_query($connection, $query))==FALSE){
            showerror($connection);
        }
        if(mysqli_affected_rows($connection) == -1) {
            error($page . "-7");
        }
    }

    function comma_list_to_array($string){
        $array = array();
        $array2 = array($string);
        while(strlen($string) > 0) {
            if(strpos($string, ",") !== false){
                $pos = strpos($string, ",");
                $item = substr($string, 0, $pos);
                array_push($array, $item);
                $string = substr($string, $pos + 2, strlen($string) - ($pos + 2));
                array_push($array2, $string);
            } else {
                if(strpos($string, " ") !== false){
                    $pos = strpos($string, " ");
                } else {
                    $pos = strlen($string);
                }
                $item = substr($string, 0, $pos);
                array_push($array, $item);
                $string = null;
            }
        }
        return $array;
    }

    function badAlert($message, $class){
        return"<div class='".$class."' style='margin-top:2%;'>
                <div class='panel panel-danger'>
                    <div class='panel-heading'>Alert:</div>
                    <div class='panel-body'>{$message}</div>
                </div>
            </div>";
    }
    function getGMT($date){
        return gmdate('Y-m-d H:i:s', strtotime($date));
    }
    function process_date($date, $page1, $page2){
        $date = preg_replace("[/0-9]",'',$date);
        if(strpos($date, "/") !== false){
            $pos = strpos($date, "/");
        } else {
            error($page1."-4");
        }
        $month = substr($date, 0 , $pos);
        if(strlen($month) == 1){
            $month = "0" . $month;
        }
        $date = substr($date, $pos + 1, strlen($date));
        if(strpos($date, "/") !== false){
            $pos = strpos($date, "/");
        } else {
            error($page2."-4");
        }
        $day = substr($date, 0, $pos);
        $date = substr($date, $pos + 1, strlen($date));
        if(strpos($date, " ")){
            $pos = strpos($date, " ");
        } else {
            $pos = strlen($date);
        }
        $year = substr($date, 0, $pos);
        if(strlen($year) == 2){
            $year = "20" . $year;
        }

        return $year.'-'.$month.'-'.$day." 00:00:00";
    }
    function commaSeparate($balance){
        $balance = strrev($balance);
        $point = strpos($balance, ".");
        $balance2 = substr($balance, $point+1, strlen($balance));
        $decimals = substr($balance, 0 ,$point + 1);
        $balance = $balance2;
        $balance1 = "";
        while ((strlen($balance) / 3) >= 1) {
            $balance1 = $balance1. substr($balance, 0, 3) . ",";
            $balance = substr($balance, 3, strlen($balance) - 3);
        }
        if ($balance == ""){
            return strrev($decimals . substr($balance1, 0, strlen($balance1)-1));
        } else {
            return strrev($decimals . $balance1 . $balance);
        }

    }
?>