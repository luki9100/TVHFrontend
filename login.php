<?php
if(session_status() == PHP_SESSION_NONE){
    //session has not started
    session_start();
}

//load the config file
require_once("config.php");
//load the URLs File
require_once("urls.php");

// Required field names
$required = array('username', 'password');

function GetURLContents($basicAuth, $URL, $username , $password){
    //set basic_auth
    if($basicAuth==true){
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => 'Authorization: Basic ' . base64_encode($username . ':' . $password)
            ]
        ];
        //get the Result
        return file_get_contents($URL, false, stream_context_create($opts));
    } else {
        //soon(TM)
    }
}

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