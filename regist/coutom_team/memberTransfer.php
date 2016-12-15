<?php

//session_start();

//if (!isset($_SESSION['resistMember']["loginTime"])) {
//    header("Location: ./loginError.php");
//}
#print nl2br(print_r($_SESSION['resistMember'],true));
// クラスファイル読み込み
/*
 * @file commons.inc          共通関数
 */
require_once "./common.inc";
//require_once "./serverCheck.inc";

// テンプレート場所の指定
$smarty->config_dir   = dirname(dirname(dirname(__FILE__))).'/smarty_templates/pc/regist/configs/';
$smarty->template_dir = dirname(dirname(dirname(__FILE__))).'/smarty_templates/pc/regist/templates/';
$smarty->compile_dir  = dirname(dirname(dirname(__FILE__))).'/smarty_templates/pc/regist/templates_c/';

// スクリプト名の取得
$script_name =  basename($_SERVER['SCRIPT_NAME'],".php");

// 変数初期値
(int)$errorNums = 0;
(int)$teamId = TEAM_ID;
(int)$rarryId = NEXT_RARRY_ID;
(int)$season = NEXT_RARRY_SEASON;
(int)$currentRarry = NEXT_RARRY_ID;
(string)$fileName = $script_name;
(string)$memberId = 0;
(string)$mode = "";
(string)$sts = "";
(string)$errorValue = array();
(string)$playerDataArray = array();
(string)$retrievalPlayersData = array();
(string)$registPlayerData = array();
(string)$state = "";
(string)$name_f_retrieval = "";
(string)$name_s_retrieval = "";
(string)$kana_f_retrieval = "";
(string)$kana_s_retrieval = "";
(string)$teamRetrieval = "";
(string)$transferComp = "";

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
//print $key." = ".$val."<BR>";
    }
}

$preRarryRegistTeamDatas = preRarryRegistTeam($connectDbClass, PRE_RARRY_ID, $teamId);
//print nl2br(print_r($preRarryRegistTeamDatas,true));

// 選手検索モード
if ($mode == "retrieval") {

    $insertTakingPlayers[0]["memberId"] = $memberId;
    $insertTakingPlayers[0]["number"] = "";
    $insertTakingPlayers[0]["posision"] = "";
    $insertTakingPlayers[0]["captainFlag"] = "";
    $insertTakingPlayers[0]["comment"] = "";

    // 選手登録
    if ($sts == "comp") {
        if ($memberDataChangeClass->aheadSeasonPlayerTaking(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId, $insertTakingPlayers) ==True) {
            $registPlayerData = playerData($connectDbClass, $memberId);
            //$registName = $registPlayerData["nameFirst"]."&nbsp;".$registPlayerData["nameSecond"];
            $transferComp = "OK";
        }
    }

    $retrievalData["state"] = $state;
    $retrievalData["nameFirst"] = $name_f_retrieval;
    $retrievalData["nameSecond"] = $name_s_retrieval;
    $retrievalData["kanaFirst"] = $kana_f_retrieval;
    $retrievalData["kanaSecond"] = $kana_s_retrieval;
    $retrievalData["teamId"] = $teamRetrieval;

    $retrievalPlayersData = retrievalPlayer($connectDbClass, NEXT_RARRY_ID, NEXT_RARRY_SEASON, PRE_RARRY_ID, PRE_RARRY_SEASON, $teamId, $retrievalData);

//print nl2br(print_r($retrievalPlayersData,ture));
}

// 前大会登録チームデータ
function preRarryRegistTeam($connect, $rarryId, $teamId) {

    $buf = array();

/*
    $sql = "SELECT " .
           "       LTI.`t_id` AS teamId, " .
           "       LTI.`t_name` AS teamName " .
           " FROM ".dbTableName::LT_REGIST_TEAM." LRT " .
           " LEFT JOIN ".dbTableName::LT_TEAM_INFO." LTI " .
           "       ON  LRT.`t_id` = LTI.`t_id` " .
           " WHERE LRT.`r_id` = " . $rarryId . " " .
           "  AND  LRT.`t_id` != " . $teamId . " " .
           " ORDER BY  LRT.`class`, LTI.`t_name` " ;
*/
    $sql = "SELECT " .
           "       `t_id` AS teamId, " .
           "       `team_name` AS teamName " .
           " FROM ".dbTableName::LT_REGIST_TEAM." " .
           " WHERE `r_id` = " . $rarryId . " " .
           "  AND  `t_id` != " . $teamId . " " .
           " ORDER BY  `class`, `team_name` " ;
//print $sql;
    $rs  = $connect->Query($sql);
    if(!$rs){ return false; }
    $nums = $connect->GetRowCount($rs);
    // データがあったら登録状態の取得
    if($nums > 0){
        for ($i=0; $i<$nums; $i++) {
            $data   = $connect->FetchRow($rs);       // １行Ｇｅｔ
            $buf[$i]["teamId"] = $data["teamId"];
            $buf[$i]["teamName"] = $data["teamName"];
        }
    }
    return $buf;
}

// 選手データ
function playerData($connect, $memberId) {

    $buf = array();

    $sql = "SELECT " .
           "       `name_first` AS nameFirst, " .
           "       `name_second` AS nameSecond " .
           " FROM ".dbTableName::LT_MEMBER_INFO." " .
           " WHERE `m_id` = " . $memberId . " " ;
//print $sql;
    $rs  = $connect->Query($sql);
    if(!$rs){ return false; }
    $nums = $connect->GetRowCount($rs);
    // データがあったら登録状態の取得
    if($nums > 0){
        $data   = $connect->FetchRow($rs);       // １行Ｇｅｔ
        $buf["nameFirst"] = $data["nameFirst"];
        $buf["nameSecond"] = $data["nameSecond"];
    }
    return $buf;
}

// 選手検索
function retrievalPlayer($connect, $nextRarryId, $nextSeason, $preRarryId, $preSeason, $teamId, $retrievalData) {

    $buf = array();

    (string)$memberSqlWhere = "";
    (string)$teamSqlWhere = "";
    (string)$sqlOrder = "";

    if ($retrievalData["nameFirst"] != "") {
        $memberSqlWhere .= " AND LMI.`name_first` LIKE '%" . $retrievalData["nameFirst"] . "%' escape '#' ";
    }
    if ($retrievalData["nameSecond"] != "") {
        $memberSqlWhere .= " AND LMI.`name_second` LIKE '%" . $retrievalData["nameSecond"] . "%' escape '#' ";
    }
    if ($retrievalData["kanaFirst"] != "") {
        $memberSqlWhere .= " AND LMI.`kana_first` LIKE '%" . $retrievalData["kanaFirst"] . "%' escape '#' ";
    }
    if ($retrievalData["kanaSecond"] != "") {
        $memberSqlWhere .= " AND LMI.`kana_second` LIKE '%" . $retrievalData["kanaSecond"] . "%' escape '#' ";
    }
    if ($retrievalData["state"] == 1) {
        //$teamSqlWhere .= " AND LMI.`name_first` = " . $retrievalData["nameFirst"] . " ";
    } else if ($retrievalData["state"] == 2) {
        $teamSqlWhere .= " AND NEXTLRTM.`discharge_date` IS NULL ";
        if ($retrievalData["teamId"] == "none") {
            $teamSqlWhere .= " AND (PRELRTM.`t_id` IS NULL OR PRELRTM.`discharge_date` > '0000-00-00 00:00:00') ";
        } else if ($retrievalData["teamId"] != "notRegist") {
            $teamSqlWhere .= " AND PRELRTM.`discharge_date` > '0000-00-00 00:00:00' ";
        }
    } else if ($retrievalData["state"] == 3) {
        $teamSqlWhere .= " AND NEXTLTI.`t_name` IS NULL  ";
        $teamSqlWhere .= " AND PRELRTM.`discharge_date` = '0000-00-00 00:00:00' ";
    }
    if ($retrievalData["teamId"] == "none") {
        $sqlOrder .= " ORDER BY PRELRTM.`t_id`, LPAD(PRELRTM.`number`, 2, 0) ";
    } else if ($retrievalData["teamId"] == "notRegist") {
        $teamSqlWhere .= " AND PRELRTM.`t_id` IS NULL ";
        //$teamSqlWhere .= " AND NEXTLRTM.`t_id` IS NULL ";
        $sqlOrder .= " ORDER BY LMI.`kana_first`, LMI.`name_first` ";
    } else if ($retrievalData["teamId"] == "onRegist") {
        $teamSqlWhere .= " AND PRELRTM.`t_id` IS NOT NULL ";
        $teamSqlWhere .= " AND NEXTLRTM.`t_id` IS NULL ";
        $sqlOrder .= " ORDER BY PRELRTM.`t_id`, LPAD(PRELRTM.`number`, 2, 0) ";
    } else {
        $teamSqlWhere .= "  AND  (PRELRTM.`t_id` = " . $retrievalData["teamId"] . ") ";
        $sqlOrder .= " ORDER BY LPAD(PRELRTM.`number`, 2, 0) ";
    }

    $sql = "SELECT " .
           "       LMI.`m_id` AS memberId, " .
           "       LMI.`name_first` AS nameFirst, " .
           "       LMI.`name_second` AS nameSecond, " .
           "       LMI.`kana_first` AS kanaFirst, " .
           "       LMI.`kana_second` AS kanaSecond, " .
           "       CASE WHEN PRELRTM.`discharge_date` = '0000-00-00 00:00:00' then 'NG' ELSE 'OK' END discharge, " .
           "       CASE WHEN PRELRTM.`number` IS NULL then '--' ELSE PRELRTM.`number` END preNumber, " .
           "       CASE WHEN NEXTLRTM.`number` IS NULL then '--' ELSE NEXTLRTM.`number` END nextNumber, " .
           //"       CASE WHEN PRELTI.`t_name` IS NULL then '未所属' ELSE PRELTI.`t_name` END preTeamName, " .
           "       CASE WHEN LRM.`team_name` IS NULL then '未所属' ELSE LRM.`team_name` END preTeamName, " .
           "       CASE WHEN NEXTLTI.`t_name` IS NULL then '未所属' ELSE NEXTLTI.`t_name` END nextTeamName " .
           " FROM ".dbTableName::LT_MEMBER_INFO." LMI " .
           " LEFT JOIN ".dbTableName::LT_REGIST_TEAM_MEMBER." PRELRTM " .
           "       ON  LMI.`m_id` = PRELRTM.`m_id` " .
           "  AND  PRELRTM.`r_id` = " . $preRarryId . " " .
           "  AND  PRELRTM.`define` = " . $preSeason . " " .
           //"  AND  PRELRTM.`t_id` != " . $teamId . " " .
           " LEFT JOIN ".dbTableName::LT_REGIST_TEAM." LRM " .
           "       ON  LRM.`t_id` = PRELRTM.`t_id` " .
           "  AND  LRM.`r_id` = " . $preRarryId . " " .
           " LEFT JOIN ".dbTableName::LT_TEAM_INFO." PRELTI " .
           "       ON  PRELRTM.`t_id` = PRELTI.`t_id` " .
           " LEFT JOIN ".dbTableName::LT_REGIST_TEAM_MEMBER." NEXTLRTM " .
           "       ON  LMI.`m_id` = NEXTLRTM.`m_id` " .
           "  AND  NEXTLRTM.`r_id` = " . $nextRarryId . " " .
           "  AND  NEXTLRTM.`define` = " . $nextSeason . " " .
           //"  AND  NEXTLRTM.`t_id` != " . $teamId . " " .
           " LEFT JOIN ".dbTableName::LT_TEAM_INFO." NEXTLTI " .
           "       ON  NEXTLRTM.`t_id` = NEXTLTI.`t_id` " .
           " WHERE LMI.`m_id` > 0 " .
           "  AND (PRELRTM.`t_id` IS NULL OR PRELRTM.`t_id` != " . $teamId . " OR NEXTLRTM.`t_id` != " . $teamId . ") " .
           " " . $teamSqlWhere . " " .
           " " . $memberSqlWhere . " " .
           " " . $sqlOrder . " " ;
//print $sql;
    $rs  = $connect->Query($sql);
    if(!$rs){ return false; }
    $nums = $connect->GetRowCount($rs);
    // データがあったら登録状態の取得
    if($nums > 0){
        for ($i=0; $i<$nums; $i++) {
            $data   = $connect->FetchRow($rs);       // １行Ｇｅｔ
            $buf[$i]["memberId"] = $data["memberId"];
            $buf[$i]["nameFirst"] = $data["nameFirst"];
            $buf[$i]["nameSecond"] = $data["nameSecond"];
            $buf[$i]["kanaFirst"] = $data["kanaFirst"];
            $buf[$i]["kanaSecond"] = $data["kanaSecond"];
            $buf[$i]["discharge"] = $data["discharge"];
            $buf[$i]["preNumber"] = $data["preNumber"];
            $buf[$i]["nextNumber"] = $data["nextNumber"];
            $buf[$i]["preTeamName"] = $data["preTeamName"];
            $buf[$i]["nextTeamName"] = $data["nextTeamName"];
        }
    }
    return $buf;
}


// SMARTYにデータを送る
$smarty->assign("state", $state);
$smarty->assign("name_f_retrieval", $name_f_retrieval);
$smarty->assign("name_s_retrieval", $name_s_retrieval);
$smarty->assign("kana_f_retrieval", $kana_f_retrieval);
$smarty->assign("kana_s_retrieval", $kana_s_retrieval);
$smarty->assign("teamRetrieval", $teamRetrieval);
$smarty->assign("preRarryRegistTeamDatas", $preRarryRegistTeamDatas);
$smarty->assign("retrievalPlayersData", $retrievalPlayersData);
$smarty->assign("mode", $mode);
$smarty->assign("transferComp", $transferComp);
$smarty->assign("registPlayerData", $registPlayerData);

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>