<?php
////////////////////////////////////////////////////
/*
 * チーム写真表示
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array();
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array();

(string)$pageTitle = 'チーム写真';
(string)$rarryDetail = array();
(string)$teamDatas = array();
(string)$teamMemberDatas = array();
(string)$mode = '';
(string)$paymentChangeValue = '';

require_once './common.inc';

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "GET") {
    while(list ($key, $val) = each($_GET)) {
        $$key = $val;
    }
} else if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = $val;
#print $key." = ".$val."<br />";
    }
}

// 大会情報の取得
//if ($rarryDataObj->rarryDetails($_SESSION["rarryId"]) == true) {
//    $rarryDetail = $rarryDataObj->getRarryDetail();
//}
//print nl2br(print_r($rarryDetail,true));

// 登録チーム情報
//if ($teamDaTAOBJ->REGISTTEAMDATA($_SESSION["RARRYID"], $TID) == TRUE) {
//    $TEAMDATAS = $TEAMDATAOBJ->GETTEAMDATAS();
//}
//print nl2br(print_r($teamDatas,true));

header("content-type:image/jpeg");
if ($teamDataObj->selectTeamPicture($_SESSION["rarryId"], $tid, $view) == true) {
    print $teamDataObj->getSelectTeamPicture();
} else {
	readfile('./img/no_photo_available.jpg');
}

?>
