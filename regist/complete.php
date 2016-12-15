<?php

session_start();

// クラスファイル読み込み
/*
 * @file commons.inc          共通関数
 */
require_once "./common.inc";
//require_once "./serverCheck.inc";

// テンプレート場所の指定
$smarty->config_dir   = SMARTY_CONFIG_DIR;
$smarty->template_dir = SMARTY_TEMPLATE_DIR;
$smarty->compile_dir  = SMARTY_COMPLETE_DIR;

// スクリプト名の取得
$script_name =  basename($_SERVER['SCRIPT_NAME'],".php");

// 変数初期値
(int)$teamId = null;
(int)$rarryId = NEXT_RARRY_ID;
(int)$season = NEXT_RARRY_SEASON;
(int)$currentRarry = NEXT_RARRY_ID;
(string)$fileName = $script_name;
(string)$mode = "";
(string)$modeValue = "";
(string)$teamChangeStsValue = "";
(string)$sts = "";
(string)$members = "";
(string)$aheadPlayer = array();
(string)$errorValue = array();
(string)$teamDatas = array();
(string)$memberDataArray = array();
(string)$takingMemberDataArray = array();
(string)$insertTakingPlayers = array();
(string)$nextMemberDatas = array();
(string)$preMemberDatas = array();
(string)$preMemberDataArray = array();
(string)$nextMembersId = array();
(string)$fmButtonValue = "";
(string)$teamDataChangeModeValue = "";
(string)$teamChangeButton = "        <input type=\"submit\" value=\"　チーム情報変更　\" />\n";
(string)$teamChangeConfButton = "            <input type=\"submit\" value=\"　内容確認　\" />\n";
(string)$teamChangeCompButton = "        <input type=\"button\" value=\"　変更　\" onclick=\"changeConf('teamChange');\" />\n";
(string)$teamChangeBackButton = "            <input type=\"button\" value=\"　戻る　\" onclick=\"pageBack('input');\" />\n";
(string)$teamChangeConfBackButton = "        <input type=\"button\" value=\"　やり直す　\" onclick=\"sendPages('teamChange', 'back');\" />\n";

//$_SESSION['resistMember']["memberRegist"] = "";

// パラメータの環境変数
//$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
//if ($strRequestMethod == "POST") {
//    while(list ($key, $val) = each($_POST)) {
//        $$key = encode($val);
//print $key." = ".$val."<BR>";
//    }
//}
$teamId = encode(@$_GET["tid"]);
if (is_null($teamId)) {
    header("Location: ./loginError2.php");
    exit;
}
//$preRarryRegistTeamDatas = preRarryRegistTeam($connectDbClass, NEXT_RARRY_ID, $teamId);

// チームの登録データ
if ($teamDataClass->registTeamData(NEXT_RARRY_ID, $teamId) == False) {
    header("Location: ./loginError2.php");
    exit;
}
if ($rarryId != $currentRarry) {
    //$_SESSION['resistMember']["taking"] = "";
}

// 初期表示・モード無し
$teamDatas = $teamDataClass->getTeamDatas();
$modeValue = "teamChange";
$fmButtonValue = $teamChangeButton;
$rarryClass = $teamDatas["teamRarryClassName"];
// NULLデータがあるデータは「未登録」に変換
foreach ($teamDatas as $key => $val) {
    //print $key." = ".$val."<BR>";
    if ($val == "" OR $val == "0") {
        $teamDatas[$key] = "未登録";
    }
}

// 今シーズン個人登録データ
if ($memberDataClass->rarrySeasonMemberList(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId) == true) {
    // 個人データ
    $nextMemberDatas    = $memberDataClass->getMemberData();
    for ($i=0; $i<count($nextMemberDatas); $i++) {
        // 今シーズン登録済み選手ID
        $nextMembersId[] = $nextMemberDatas[$i]["nemberId"];
    }
    $nextMembarNum = count($nextMembersId);
//print nl2br(print_r($nextMembersId,true));
}
#print "<hr />";
/*
// 前シーズン個人登録データ
if ($memberDataClass->rarrySeasonMemberList(PRE_RARRY_ID, PRE_RARRY_SEASON, $teamId) == true) {
    // 個人データ
    $preMemberDataArray    = $memberDataClass->getMemberData();
    for ($i=0; $i<count($preMemberDataArray); $i++) {
        if (count($preMemberDataArray) > 0) {
            if (!in_array ($preMemberDataArray[$i]["nemberId"], $nextMembersId)) {
                $preMemberDatas[] = $preMemberDataArray[$i];
            }
        } else {
            $preMemberDatas[] = $preMemberDataArray;
        }
    }
}
print nl2br(print_r($preMemberDataArray,true));
print "<hr />";
print nl2br(print_r($preMemberDatas,true));
print nl2br(print_r($result,true));
*/
$smarty->assign("nextMemberDatas", $nextMemberDatas);
$smarty->assign("nextMembarNum", $nextMembarNum);
//$smarty->assign("preMemberDatas", $preMemberDatas);

// SMARTYにデータを送る
$smarty->assign("currentRarry", $currentRarry);
$smarty->assign("rarryId", $rarryId);
$smarty->assign("define", $season);
$smarty->assign("teamId", $teamId);
$smarty->assign("rarryClass", $rarryClass);
$smarty->assign("teamDatas", $teamDatas);
$smarty->assign("memberDataArray", $memberDataArray);
$smarty->assign("members", $members);
$smarty->assign("mode", $mode);
$smarty->assign("modeValue", $modeValue);
$smarty->assign("teamChangeStsValue", $teamChangeStsValue);
$smarty->assign("sts", $sts);
$smarty->assign("teamDataChangeModeValue", $teamDataChangeModeValue);
$smarty->assign("fmButtonValue", $fmButtonValue);
//$smarty->assign("preRarryRegistTeamDatas", $preRarryRegistTeamDatas);
$smarty->assign("subName", SUB_NAME);

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>