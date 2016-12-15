<?php

session_start();

if (!isset($_SESSION['resistMember']["loginTime"])) {
    header("Location: ./loginError.php");
}
#print nl2br(print_r($_SESSION['resistMember'],true));
// クラスファイル読み込み
/*
 * @file commons.inc          共通関数
 */
require_once "./common.inc";
require_once "./serverCheck.inc";

// テンプレート場所の指定
$smarty->config_dir   = SMARTY_CONFIG_DIR;
$smarty->template_dir = SMARTY_TEMPLATE_DIR;
$smarty->compile_dir  = SMARTY_COMPLETE_DIR;

// スクリプト名の取得
$script_name =  basename($_SERVER['SCRIPT_NAME'],".php");

// 変数初期値
(int)$errorNums = 0;
(int)$teamId = $_SESSION['resistMember']["teamId"];
(int)$rarryId = NEXT_RARRY_ID;
(int)$season = NEXT_RARRY_SEASON;
(int)$currentRarry = NEXT_RARRY_ID;
(string)$fileName = $script_name;
(string)$memberId = 0;
(string)$mode = "";
(string)$sts = "";
(string)$errorValue = array();
(int)$bd_type = 0;
(string)$nameFirst = "";
(string)$nameSecond = "";
(string)$kanaFirst = "";
(string)$kanaSecond = "";
(int)$posision = 0;
(int)$tall = 0;
(int)$bd_y = 0;
(int)$bd_m = 0;
(int)$bd_d = 0;
(string)$posisionView = "";
(string)$birthdayView = "";
(string)$memberDataChangeModeValue = "";
(string)$teamMembersNumbers = array();

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
//print $key." = ".$val."<BR>";
    }
}

// チームの登録データ
if ($teamDataClass->registTeamData(NEXT_RARRY_ID, $teamId) == false) {
    header("Location: ./loginError.php?NoTeamData");
}

// SMARTYにデータを送る
$smarty->assign("memberId", $memberId);
$smarty->assign("bd_type", $bd_type);
$smarty->assign("number", $number);
$smarty->assign("nameFirst", $nameFirst);
$smarty->assign("nameSecond", $nameSecond);
$smarty->assign("kanaFirst", $kanaFirst);
$smarty->assign("kanaSecond", $kanaSecond);
$smarty->assign("tall", $tall);
$smarty->assign("bd_type", $bd_type);
$smarty->assign("bd_y", $bd_y);
$smarty->assign("bd_m", $bd_m);
$smarty->assign("bd_d", $bd_d);
$smarty->assign("posision", $posision);
$smarty->assign("posisionView", $posisionView);
$smarty->assign("birthdayView", $birthdayView);
$smarty->assign("mode", $mode);
$smarty->assign("sts", $sts);
$smarty->assign("errorValue", $errorValue);
$smarty->assign("memberDataChangeModeValue", $memberDataChangeModeValue);
$smarty->assign("readMode", $readMode);
$smarty->assign("ligaMail", LIGA_MAIL);
$smarty->assign("rarryDataArray", $rarryDataArray);
$smarty->assign("subName", SUB_NAME);

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>