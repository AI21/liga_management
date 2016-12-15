<?php

/*/-----------------------------------------------------------
 *
 *  サーバーチェック
 */
 //-----------------------------------------------------------

if (!isset($_SERVER["HTTPS"]) AND ($_SERVER["HTTPS"] != 'on')) {
    header("Location: https://" . SERVER_HOST . "/management.liga-tokai.com/management/httpsdocs/regist/loginError.php?ServerCheck_HTTPS");
}

if (!isset($_SERVER["HTTP_REFERER"])) {
    header("Location: ./loginError.php?ServerCheck_REFERER");
} else {
    $serverHost = split ('[/]', $_SERVER["HTTP_REFERER"]);
//    ($_SERVER["HTTPS"] == 'on') ? $checkArrayNum = 4 : $checkArrayNum = 2 ;
//print $serverHost[2]."<br />";
//print SERVER_HOST."<br />";
//print nl2br(print_r($serverHost,true));
    if ($serverHost[2] != SERVER_HOST) {
//        if ($serverHost[2] != "localhost") {
        header("Location: ./loginError.php?ServerCheck_serverHost");
    }
}
#print nl2br(print_r($_SERVER,true));

?>
