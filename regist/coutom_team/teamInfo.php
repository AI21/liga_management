<?php

session_start();


// クラスファイル読み込み
/*
 * @file commons.inc          共通関数
 */
require_once "./common.inc";
//require_once "./serverCheck.inc";

// テンプレート場所の指定
$smarty->config_dir   = dirname(dirname(dirname(dirname(__FILE__)))).'/smarty_templates/pc/regist/configs/';
$smarty->template_dir = dirname(dirname(dirname(dirname(__FILE__)))).'/smarty_templates/pc/regist/templates/';
$smarty->compile_dir  = dirname(dirname(dirname(dirname(__FILE__)))).'/smarty_templates/pc/regist/templates_c/';

// スクリプト名の取得
$script_name =  basename($_SERVER['SCRIPT_NAME'],".php");

// 変数初期値
(int)$errorNums = 0;
(int)$teamId = TEAM_ID;
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
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
//print $key." = ".$val."<BR>";
    }
}

// チームの登録データ
if ($teamDataClass->registTeamData(NEXT_RARRY_ID, $teamId) == False) {
    header("Location: ./loginError.php");
}
if ($rarryId != $currentRarry) {
    //$_SESSION['resistMember']["taking"] = "";
}

// 初期表示・モード無し
if ($mode == "") {
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
// チーム情報変更モード
} else if ($mode == "teamChange") {
    if ($sts == "") {
        $teamDatas           = $teamDataClass->getTeamDatas();
        $modeValue = $mode;
        $teamChangeStsValue = "情報入力";
        $sts = "conf";
        $fmButtonValue = $teamChangeConfButton."&nbsp;".$teamChangeBackButton;
        $distrinctDatas = teamDistrict($connectDbClass);
        $teamDatas["teamHomeColor"] = makeSelectBox("teamHomeColor", $optionArrays["teamColor"], $teamDatas["teamHomeColor"]);
        $teamDatas["teamAwayColor"] = makeSelectBox("teamAwayColor", $optionArrays["teamColor"], $teamDatas["teamAwayColor"]);
        $teamDatas["teamDistrict"] = makeSelectBox("teamDistrict", $distrinctDatas, $teamDatas["teamDistrictId"]);
    } else {

        $teamDatas["teamName"] = mbZen($teamName);
        $teamDatas["teamKana"] = mbZen($teamKana);
        $teamDatas["teamRep"] = mbZen($teamRep);
        $teamDatas["teamRepTel"] = mbHan($teamRepTel);
        $teamDatas["teamRepMail"] = mbHan($teamRepMail);
        $teamDatas["teamSubRep"] = mbZen($teamSubRep);
        $teamDatas["teamSubRepTel"] = mbHan($teamSubRepTel);
        $teamDatas["teamSubRepMail"] = mbHan($teamSubRepMail);
        $teamDatas["teamPlace"] = $teamPlace;

        // フォームデータチェック
        if ($paramCheckClass->isNullCheck($errorMessageValues, $teamDatas["teamName"], 2, 64) == False) {
            $errorValue["teamName"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
            //$errorNums++;
        }
        if ($paramCheckClass->there_katakana($errorMessageValues, $teamDatas["teamKana"], 2, 64, "utf-8") == False) {
            $errorValue["teamKana"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        }
        if ($paramCheckClass->there_zenkaku($errorMessageValues, $teamDatas["teamRep"], 2, 16, "utf-8") == False) {
            $errorValue["teamRep"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        }
        if ($paramCheckClass->isMobileCheck($errorMessageValues, $teamDatas["teamRepTel"]) == False) {
            $errorValue["teamRepTel"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        }
        if ($paramCheckClass->isMailCheck($errorMessageValues, $teamDatas["teamRepMail"]) == False) {
            $errorValue["teamRepMail"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        }
        if ($paramCheckClass->there_zenkaku($errorMessageValues, $teamDatas["teamSubRep"], 2, 16, "utf-8") == False) {
            $errorValue["teamSubRep"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        }
        if ($paramCheckClass->isMobileCheck($errorMessageValues, $teamDatas["teamSubRepTel"]) == False) {
            $errorValue["teamSubRepTel"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        }
        if ($paramCheckClass->isMailCheck($errorMessageValues, $teamDatas["teamSubRepMail"]) == False) {
            $errorValue["teamSubRepMail"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        }

        if ($teamDistrict == "") {
            $errorValue["teamDistrict"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageValues->getErrorMessage("DISTRICT_NO_SELECT")."</span>\n";
        }
        if ($paramCheckClass->there_zenkaku($errorMessageValues, $teamDatas["teamPlace"], 2, 16, "utf-8") == False) {
            $errorValue["teamPlace"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        }
        if ($teamHomeColor == "") {
            $errorValue["teamHomeColor"] = "<br /><span style=\"font-weight:bold;color:red;\">ホーム".$errorMessageValues->getErrorMessage("TEAMCOLOR_NO_SELECT")."</span>\n";
        }
        if ($teamAwayColor == "") {
            $errorValue["teamAwayColor"] = "<br /><span style=\"font-weight:bold;color:red;\">アウェイ".$errorMessageValues->getErrorMessage("TEAMCOLOR_NO_SELECT")."</span>\n";
        }
        // 代表者と副代表者の重複チェック
        if (!isset($errorValue["teamSubRep"]) AND $teamDatas["teamRep"] == $teamDatas["teamSubRep"]) {
            $errorValue["teamSubRep"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageValues->getErrorMessage("REPRESENT_SAME")."</span>\n";
        }
        if (!isset($errorValue["teamRepTel"]) AND $teamDatas["teamRepTel"] == $teamDatas["teamSubRepTel"]) {
            $errorValue["teamSubRepTel"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageValues->getErrorMessage("REPRESENT_TEL_SAME")."</span>\n";
        }
        if (!isset($errorValue["teamRepMail"]) AND $teamDatas["teamRepMail"] == $teamDatas["teamSubRepMail"]) {
            $errorValue["teamSubRepMail"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageValues->getErrorMessage("REPRESENT_MAIL_SAME")."</span>\n";
        }

        if (count($errorValue) > 0 OR $sts == "back") {
            $modeValue = "teamChange";
            $sts = "conf";
            $teamChangeStsValue = "情報入力";
            $fmButtonValue = $teamChangeConfButton."&nbsp;".$teamChangeBackButton;
            $distrinctDatas = teamDistrict($connectDbClass);
            $teamDatas["teamHomeColor"] = makeSelectBox("teamHomeColor", $optionArrays["teamColor"], $teamHomeColor);
            $teamDatas["teamAwayColor"] = makeSelectBox("teamAwayColor", $optionArrays["teamColor"], $teamAwayColor);
            $teamDatas["teamDistrict"] = makeSelectBox("teamDistrict", $distrinctDatas, $teamDistrict);
            $smarty->assign("errorValue", $errorValue);
        } else {
            if ($sts == "conf") {
                $mode = "teamChangeConf";
                #$modeValue = "teamChangeConf";
                $teamChangeStsValue = "内容確認";
                $sts = "conf";
                $fmButtonValue = $teamChangeCompButton."&nbsp;".$teamChangeConfBackButton;
                // 隠しフォームデータへのコピー
                $formDatas = $teamDatas;
                $formDatas["teamDistrict"] = $teamDistrict;
                $formDatas["teamHomeColor"] = $teamHomeColor;
                $formDatas["teamAwayColor"] = $teamAwayColor;
                $smarty->assign("formDatas", $formDatas);
            } else if ($sts == "comp") {
                $modeValue = "teamChange";
                $mode = "teamChangeComp";
                $sts = "";
                $fmButtonValue = $teamChangeButton;
                // 送信データを配列に変換
                foreach ($_POST AS $key => $val) {
                    //if ($key == "mode") continue;
                    $changeDatas[$key] = encode(mbZen($val));
                }
                // チーム情報更新
                if ($teamDataChangeClass->teamDataChange(NEXT_RARRY_ID, $teamId, $changeDatas/*, $beforUserDatas*/) == True) {
                    $teamDataChangeModeValue = "<div style=\"padding:7px 0;color:blue;font-size:18px;font-weight:bold;\">チーム情報を変更しました。</div>";
                } else {
                    $teamDataChangeModeValue = "<div style=\"color:red;font-weight:bold;\">チーム情報の変更が失敗しました。</div>";
                }
                // チームの登録データ
                if ($teamDataClass->registTeamData(NEXT_RARRY_ID, $teamId) == True) {
                    $teamDatas           = $teamDataClass->getTeamDatas();
                } else {
                    //$teamDataChangeModeValue = "<div>チーム情報の変更が失敗しました。</div>";
                }
            }
            if ($teamDataClass->selectDistrictData($teamDistrict) ==True) {
                $teamDatas["teamDistrict"] = $teamDataClass->getDistrictDatas();
            }
            $teamDatas["teamHomeColor"] = $optionArrays["teamColor"]["$teamHomeColor"];
            $teamDatas["teamAwayColor"] = $optionArrays["teamColor"]["$teamAwayColor"];
        }
    }
} else if ($mode == "memberAhead") {

    $fmButtonValue = $teamChangeButton;
    $teamDatas = $teamDataClass->getTeamDatas();

    // if ($_SESSION['resistMember']["taking"] == "") {

        $registPlayers = array();
        $registSameTeamPlayers = array();
        $registdiffTeamPlayers = array();

#print print_r($aheadPlayer);

        (int)$insertPlayerNum = 0;
        (int)$aheadPlayerNum = count($aheadPlayer);

        for ($i=0; $i<$aheadPlayerNum; $i++) {
            if ($userCheckClass->memberRarryRegistCheck($errorMessageValues, NEXT_RARRY_ID, PRE_RARRY_ID, NEXT_RARRY_SEASON, PRE_RARRY_SEASON, $teamId, $aheadPlayer[$i]) == True) {
                $insertPlayerData = $userCheckClass->getMemberRarryRegistDatas();
                $insertTakingPlayers[$insertPlayerNum]["memberId"] = $aheadPlayer[$i];
                $insertTakingPlayers[$insertPlayerNum]["number"] = $insertPlayerData["number"];
                $insertTakingPlayers[$insertPlayerNum]["posision"] = $insertPlayerData["posision"];
                $insertTakingPlayers[$insertPlayerNum]["captainFlag"] = 0;
                $insertTakingPlayers[$insertPlayerNum]["comment"] = "";
                $insertTakingPlayers[$insertPlayerNum]["firstName"] = $insertPlayerData["firstName"];
                $insertTakingPlayers[$insertPlayerNum]["secondName"] = $insertPlayerData["secondName"];
                $insertPlayerNum++;
            } else {
                // 登録済み選手
                $distinctPlayerData = $userCheckClass->getMemberRarryRegistDatas();
                if ($distinctPlayerData["sameFlag"] == "sameTeam") {
                    $registSameTeamPlayers[] = $distinctPlayerData;
                } else {
                    $registdiffTeamPlayers[] = $distinctPlayerData;
                }
            }
        }
// print nl2br(print_r($insertTakingPlayers,true));
// print nl2br(print_r($registSameTeamPlayers,true));
// print nl2br(print_r($registdiffTeamPlayers,true));

        if (count($insertTakingPlayers) > 0) {
            // 選手登録
            if ($memberDataChangeClass->aheadSeasonPlayerTaking(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId, $insertTakingPlayers) ==True) {
            }
        }
        $registPlayers = $insertTakingPlayers;
        // SMARTYにデータを送る
        $smarty->assign("registPlayers", $registPlayers);
        $smarty->assign("registSameTeamPlayers", $registSameTeamPlayers);
        $smarty->assign("registdiffTeamPlayers", $registdiffTeamPlayers);
    // }
    //$_SESSION['resistMember']["taking"] = "insert";
} else if ($mode == "removeMember") {
    $teamDatas = $teamDataClass->getTeamDatas();
    $modeValue = "teamChange";
    $fmButtonValue = $teamChangeButton;
    // 今シーズンの登録選手から外す
    if ($teamDataChangeClass->removeTeamPlayer(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $memberId) == True) {
        // SMARTYにデータを送る
        $smarty->assign("removeComp", "ok");
    }
} else if ($mode == "dischargeMember") {
    $teamDatas = $teamDataClass->getTeamDatas();
    $modeValue = "teamChange";
    $fmButtonValue = $teamChangeButton;
    // 登録選手から放出する
    if ($teamDataChangeClass->dischargeTeamPlayer(PRE_RARRY_ID, PRE_RARRY_SEASON, $memberId) == True) {
        // SMARTYにデータを送る
        $smarty->assign("removeComp", "ok");
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
#print nl2br(print_r($nextMembersId,true));
}
#print "<hr />";
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
/*
print nl2br(print_r($preMemberDataArray,true));
print "<hr />";
print nl2br(print_r($preMemberDatas,true));
print nl2br(print_r($result,true));
*/
$smarty->assign("nextMemberDatas", $nextMemberDatas);
$smarty->assign("preMemberDatas", $preMemberDatas);


function makeSelectBox($fmName, $optionArray, $datas = "") {

    $buf = "            <select name=\"" . $fmName . "\">\n";
    $buf .= "              <option value=\"\">下記より選択</option>\n";
    if (count($optionArray) > 0) {
        foreach ($optionArray as $key => $val) {
            ($key == $datas) ? $selected = " selected=\"selected\"" : $selected = "";
            $buf .= "              <option class=\"" . $key . "\" value=\"" . $key . "\"" . $selected . ">" . $val . "</option>\n";
        }
    }
    $buf .= "            </select>\n";
    return $buf;
}

function teamDistrict($connect) {
    $sql = "SELECT * FROM m_district_info " ;
        $rs  = $connect->Query($sql);
        if(!$rs){ return false; }
        $nums = $connect->GetRowCount($rs);
        // データがあったら登録状態の取得
        if($nums > 0){
            for ($i=0; $i<$nums; $i++) {
                $data   = $connect->FetchRow($rs);       // １行Ｇｅｔ
                $buf[$data["rec_id"]] = $data["district_name"];
            }
            return $buf;
        }
}

// SMARTYにデータを送る
$smarty->assign("currentRarry", $currentRarry);
$smarty->assign("rarryId", $rarryId);
$smarty->assign("define", $season);
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

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>