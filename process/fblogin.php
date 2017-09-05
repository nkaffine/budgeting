<?php
/**
 * Created by PhpStorm.
 * User: Nick
 * Date: 8/10/17
 * Time: 8:08 PM
 */
    require_once($_SERVER['DOCUMENT_ROOT'].'/db.php');
    if(!($connection = @ mysqli_connect($DB_hostname, $DB_username, $DB_password, $DB_databasename))){
        showerror($connection);
    }
    if(count($_POST)){
        $id = validNumbers($_POST['userId'], 30);
        $sessionId = validInputSizeAlpha($_POST['sessionId'], 255);
        $first = validInputSizeAlpha($_POST['first'], 255);
        $last = validInputSizeAlpha($_POST['last'], 255);
        if($_POST['email'] != 'undefined'){
            $email = validInputSizeAlpha($_POST['email'], 255);
        }
        if($_POST['gender'] != 'undefined'){
            $gender = validInputSizeAlpha($_POST['gender'], 20);
        }
        if($_POST['min_age'] != 'undefined'){
            $min_age = validNumbers($_POST['min_age'], 3);
        }
        if($_POST['max_age'] != 'undefined'){
            $max_age = validNumbers($_POST['max_age'], 3);
        }
        $lon = validNumbers($_POST['lon'], 30);
        $lat = validNumbers($_POST['lat'], 30);
    }
    if(empty($id) || empty($sessionId) || empty($first) || empty($last)){
        error("3-1-1");
    }
    // Updates the email of the user to the given email
    function updateEmail($e){
        global $id;
        global $connection;
        $query = "update users set email = '{$e}' where facebook_id = {$id}";
        if(($result = @ mysqli_query($connection, $query))==FALSE){
            showerror($connection);
        }
        if(mysqli_affected_rows($connection) == -1) {
            error("3-2-4");
        }
    }
    // Check to see if the facebook id is already affiliated with a user account
    $query = "select facebook_id from users where facebook_id = {$id}";
    if(($result = @ mysqli_query($connection, $query))==FALSE){
        showerror($connection);
    }
    if(mysqli_num_rows($result) == 0){
        // The facebook id is not affiliated with a user account so input the user's information
        // Get the next user_id that is open
        $query = "select max(user_id) from users";
        if(($result = @ mysqli_query($connection, $query))==FALSE){
            showerror($connection);
        }
        $row = mysqli_fetch_array($result);
        $newId = $row['max(user_id)'] + 1;

        // Insert the user's information into the database with the new user_id
        $query = "insert into users (user_id, facebook_id, first, last) values ({$newId}, {$id}, '{$first}', '{$last}')";
        if(($result = mysqli_query($connection,$query))==FALSE){
            showerror($connection);
        }
        if(mysqli_affected_rows($connection) == -1){
            error("3-3-5");
        }
        // If the email is not empty, add the email
        if(!empty($email)){
            updateEmail($email);
        }
    } else if(mysqli_num_rows($result) == 1){
        // Check to see if any of the facebook information has changed
        $query = "select email from users where facebook_id = {$id}";
        if(($result = @ mysqli_query($connection, $query))==FALSE){
            showerror($connection);
        }
        if($row['email'] != $email && !empty($email)){
            updateEmail($email);
        }
    } else {
        // There was more than one row with the facebook id so there is a problem
        error("3-4-2");
    }
    // Starts the session of the user and redirects them to the home page.
    session_start();
    $_SESSION['id'] = $id;
    $_SESSION['key'] = $sessionId;
    header("Location: ../home.php");
    exit;
?>