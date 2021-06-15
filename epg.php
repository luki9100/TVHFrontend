<?php
if(session_status() == PHP_SESSION_NONE){
    //session has not started
    session_start();
}
?>


<html>
<head>
    <link rel="stylesheet" href="style/epg.css">
    <title>TVHFrontend EPG</title>
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

$epgResult = GetURLContents($tvBasicAuth, $epgURL, $tvUsername, $tvPassword);

$epgDecoded = json_decode($epgResult, true);

$channels = $epgDecoded['entries'];

$uuids = array();

$epgs = array();

//get all UUIDs from EPG
foreach($channels as $channel){
    array_push($uuids, $channel["channelUuid"]);
}

//Make UUIDs Unique
$uuids = array_unique($uuids);



//Display Table
echo("<div class=\"table-wrapper\"> <table class=\"fl-table\">");
echo("<thead><tr><th>Channel</th><th>Now</th><th>Next</th></tr></thead><tbody>");
//Go through every unique UUID
foreach($uuids as $uuid){
    //Search for the Chanel with the UUID
    foreach($channels as $channel){    
        if(in_array($uuid, $channel)){
            //Push channel information into array
            if(!in_array($channel["channelName"], $epgs)){
                array_push($epgs, $channel["channelName"]);
            }            
            array_push($epgs, $channel["title"]);
        }
    }
    //fill array to fit table
    if(count($epgs) < 3){
        array_push($epgs, " ");
    }
    //display array as row in table
    echo("<tr>");
    $i = 0;
    foreach($epgs as $epg){
        if($i < 3){
            echo("<td>". $epg ."</td>");
            $i++;
        }
    }
    echo("</tr>");

    //reset array
    $epgs = array();
}
echo("</tbody></table>");

if($tvBasicAuth==true){
echo("Logged in as " . $tvUsername);
}
?>