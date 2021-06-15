<?php
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
        //get the Result
        return file_get_contents($URL);
    }
}
?>