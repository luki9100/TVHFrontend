<?php
if(session_status() == PHP_SESSION_NONE){
    //session has not started
    session_start();
}
?>


<html>
<head>
    <link rel="stylesheet" href="stlye/style.css">
    <title>TVHFrontend</title>
<head>


<?php
//load the config file
require_once("config.php");
//load the URLs File
require_once("urls.php");

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

if($tvBasicAuth==true){
    function checkSessionVars($required){
        foreach($required as $field) {
            if (empty($_SESSION[$field])) {
                throw new Exception("Field is empty");
            }
        }
    }

    // Required field names
    $required = array('username', 'password');

    //Check if Session vars are given
    try {
        checkSessionVars($required);
    } catch(Exception $e) {
        error_log("No Username or Password given. Redirecting....");
        header("Location: /login.html");
        die();
    }

    $tvUsername = $_SESSION["username"];
    $tvPassword = $_SESSION["password"];
}

$epgResult = GetURLContents($tvBasicAuth, $epgURL, $tvUsername, $tvPassword);

$epgDecoded = json_decode($epgResult, true);

$channels = $epgDecoded['entries'];

echo("<div class=\"table-wrapper\"> <table class=\"fl-table\">");
echo("<thead><tr><th>Channel</th><th>Programm</th><th>Play</th></tr></thead><tbody>");
foreach($channels as $channel){
    echo("<tr>");
    echo("<td>".$channel["channelName"]."</td>");
    echo("<td>".$channel["title"]."</td>");
    echo("<td><a href=" . $streamURL . $channel["channelUuid"].">Play</a></td>");
    echo("</tr>");
}
echo("</tbody></table>");

echo("Logged in as " . $tvUsername);
?>