<?php
if(session_status() == PHP_SESSION_NONE){
    //session has not started
    session_start();
}
?>


<html>
<head>
    <link rel="stylesheet" href="style/style.css">
    <title>TVHFrontend Main Site</title>
<head>


<?php
//load the config file
require_once("conf/config.php");
//load the URLs File
require_once("conf/urls.php");
//load Functions
require_once("functions/GetURLContents.php");

//set empty Username and Password, real Values weill be set later, if BasicAuth is enabled
$tvUsername = "";
$tvPassword = "";


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

$channelResult = GetURLContents($tvBasicAuth, $channelURL, $tvUsername, $tvPassword);

$channelDecoded = json_decode($channelResult, true);

$channels = $channelDecoded['entries'];


echo("<div class=\"table-wrapper\"> <table class=\"fl-table\">");
echo("<thead><tr><th>Channel</th><th>Play</th></tr></thead><tbody>");
foreach($channels as $channel){
    echo("<tr>");
    echo("<td>".$channel["name"]."</td>");
    echo("<td><a href=" . $streamURL . $channel["uuid"].">Play</a></td>");
    echo("</tr>");
}
echo("</tbody></table>");

if($tvBasicAuth==true){
echo("Logged in as " . $tvUsername);
}
?>