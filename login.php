<?php
if(session_status() == PHP_SESSION_NONE){
    //session has not started
    session_start();
}

//load the config file
require_once("conf/config.php");
//load the URLs File
require_once("conf/urls.php");
//load Functions
require_once("functions/GetURLContents.php");


// Required field names
$required = array('username', 'password');

function checkPostVars($required){
    foreach($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Field is empty");
        }
    }
}

function checkTvLogin($serverInfoURL, $username , $password){
    if(GetURLContents(true, $serverInfoURL, $username , $password) == false){
        throw new Exception("Login incorrect or Server not reachable!");
    }
}


//check if Post Vars are given
try {
    checkPostVars($required);
  } catch(Exception $e) {
    error_log("No Username or Password given. Redirecting....");
    header("Location: /login.html");
    die();
}

//check if Login is correct
try {
    checkTvLogin($serverInfoURL, $_POST['username'], $_POST['password']);
    } catch(Exception $e) {
    error_log("No Username or Password given. Redirecting....");
    header("Location: /login.html");
    die();
}


$_SESSION["username"] = $_POST['username'];
$_SESSION["password"] = $_POST['password'];

header("Location: /index.php");
die();

?>