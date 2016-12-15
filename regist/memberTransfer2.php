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
(string)$playerDataArray = array();
(string)$retrievalPlayersData = array();
(string)$retrievalPlayers = array();
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

// チームの登録データ
if ($teamDataClass->registTeamData(NEXT_RARRY_ID, $teamId) == false) {
    header("Location: ./loginError.php");
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

    $playerNum = count($retrievalPlayersData);

    if ($playerNum > 0) {
		for ($i=0; $i<$playerNum; $i++) {
			if ($retrievalData["state"] == 2){
				// 移籍可能状態のみ検出
				if ($retrievalPlayersData[$i]['discharge'] == 'NG') {
					continue;
				}
			}
			if (isset($retrievalPlayersData[$i]['memberId'])) {
				$retrievalPlayers[] = $retrievalPlayersData[$i];
			}
		}

		// 背番号ソート
//		usort($retrievalPlayers, "memberSort");
	}

//print nl2br(print_r($retrievalPlayersData,ture));
}

// 前大会登録チームデータ
function preRarryRegistTeam($connect, $rarryId, $teamId) {

    $buf = array();

    $sql = "SELECT " .
           "       `id` AS teamId, " .
           "       `t_name` AS teamName " .
           " FROM ".dbTableName::LT_TEAM_INFO." " .
           " WHERE `id` > 1 " .
           "  AND  `t_kana` != '' " .
           " ORDER BY `teamName` " ;
/*
    $sql = "SELECT " .
           "       `t_id` AS teamId, " .
           "       `team_name` AS teamName " .
           " FROM ".dbTableName::LT_REGIST_TEAM." " .
           " WHERE `r_id` = " . $rarryId . " " .
           "  AND  `t_id` != " . $teamId . " " .
           " ORDER BY  `class`, `team_name` " ;
*/
//print $sql;
    $rs  = $connect->Query($sql);
    if(!$rs){
        print $sql."<br />[preRarryRegistTeam]前大会登録チームデータエラーです。";
        return false;
    }
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
           " WHERE `id` = " . $memberId . " " ;
//print $sql;
    $rs  = $connect->Query($sql);
    if(!$rs){
        print $sql."<br />[playerData]選手データエラーです。";
        return false;
    }
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
    (string)$preSeasonRarryIdSql  = "";
    (string)$preSeasonSql  = "";
    (string)$preTeamInfoSql  = "";

/*
    (string)$sqlWhereBasic = " AND (PRELRTM.`t_id` IS NULL OR PRELRTM.`t_id` != " . $teamId . " OR NEXTLRTM.`t_id` != " . $teamId . ") ";

    // 名前(姓)検索
    if ($retrievalData["nameFirst"] != "") {
        $memberSqlWhere .= " AND LMI.`name_first` LIKE '%" . $retrievalData["nameFirst"] . "%' escape '#' ";
    }
    // 名前(名)検索
    if ($retrievalData["nameSecond"] != "") {
        $memberSqlWhere .= " AND LMI.`name_second` LIKE '%" . $retrievalData["nameSecond"] . "%' escape '#' ";
    }
    // ヨミカナ(姓)検索
    if ($retrievalData["kanaFirst"] != "") {
        $memberSqlWhere .= " AND LMI.`kana_first` LIKE '%" . $retrievalData["kanaFirst"] . "%' escape '#' ";
    }
    // ヨミカナ(名)検索
    if ($retrievalData["kanaSecond"] != "") {
        $memberSqlWhere .= " AND LMI.`kana_second` LIKE '%" . $retrievalData["kanaSecond"] . "%' escape '#' ";
    }
    // 放出状態
    if ($retrievalData["state"] == 1) {
        //$teamSqlWhere .= " AND LMI.`name_first` = " . $retrievalData["nameFirst"] . " ";
    } else if ($retrievalData["state"] == 2) {
        $teamSqlWhere .= " AND NEXTLRTM.`modified` IS NULL ";
        if (RARRY_RELATION == true) {
            if ($retrievalData["teamId"] == "none") {
                $teamSqlWhere .= " AND (PRELRTM.`t_id` IS NULL OR PRELRTM.`modified` > '0000-00-00 00:00:00') ";
            } else if ($retrievalData["teamId"] != "notRegist") {
                $teamSqlWhere .= " AND PRELRTM.`modified` > '0000-00-00 00:00:00' ";
            }
        }
    } else if ($retrievalData["state"] == 3) {
        $teamSqlWhere .= " AND NEXTLTI.`t_name` IS NULL  ";
        $teamSqlWhere .= " AND PRELRTM.`modified` = '0000-00-00 00:00:00' ";
    }

    if ($retrievalData["teamId"] == "none") {
        $sqlOrder .= " ORDER BY PRELRTM.`t_id`, LPAD(PRELRTM.`number`, 2, 0) ";
    } else if ($retrievalData["teamId"] == "notRegist") {
        $teamSqlWhere .= " AND PRELRTM.`t_id` IS NULL ";
        //$teamSqlWhere .= " AND NEXTLRTM.`t_id` IS NULL ";
        $sqlOrder .= " ORDER BY LMI.`kana_first`, LMI.`name_first` ";
    } else if ($retrievalData["teamId"] == "onRegist") {	// 進行中シーズン
        $teamSqlWhere .= " AND PRELRTM.`t_id` IS NOT NULL ";
//        $teamSqlWhere .= " AND NEXTLRTM.`t_id` IS NULL ";
        $sqlOrder .= " ORDER BY PRELRTM.`t_id`, LPAD(PRELRTM.`number`, 2, 0) ";
    } else if ($retrievalData["teamId"] == "tm2009") {	// トーナメント2009
        $teamSqlWhere .= " AND PRELRTM.`t_id` IS NOT NULL ";
//        $teamSqlWhere .= " AND NEXTLRTM.`t_id` IS NULL ";
        $sqlOrder .= " ORDER BY PRELRTM.`t_id`, LPAD(PRELRTM.`number`, 2, 0) ";
    } else if ($retrievalData["teamId"] == "tm2009_myTeam") {	// トーナメント2009(自チーム)
        $teamSqlWhere .= " AND PRELRTM.`t_id` IS NOT NULL ";
        $sqlWhereBasic = " AND (PRELRTM.`t_id` IS NULL OR PRELRTM.`t_id` = " . $teamId . " OR NEXTLRTM.`t_id` = " . $teamId . ") ";
//        $teamSqlWhere .= " AND NEXTLRTM.`t_id` IS NULL ";
        $sqlOrder .= " ORDER BY PRELRTM.`t_id`, LPAD(PRELRTM.`number`, 2, 0) ";
    } else if ($retrievalData["teamId"] == "20072008") {	// 20072008シーズン
        $teamSqlWhere .= " AND PRELRTM.`t_id` IS NOT NULL ";
        $teamSqlWhere .= " AND NEXTLRTM.`t_id` IS NULL ";
        $sqlOrder .= " ORDER BY PRELRTM.`t_id`, LPAD(PRELRTM.`number`, 2, 0) ";
    } else {
        $teamSqlWhere .= "  AND  (PRELRTM.`t_id` = " . $retrievalData["teamId"] . ") ";
//        $sqlOrder .= " ORDER BY LPAD(PRELRTM.`number`, 2, 0) ";
        $sqlOrder .= " ORDER BY memberId, define ";
    }

    if ($retrievalData["teamId"] == "tm2009") {
        $preRarryId = 4;
        $preSeasonSql  = "";
    } elseif ($retrievalData["teamId"] == "tm2009_myTeam") {
        $preRarryId = 4;
        $preSeasonSql  = "";
    } elseif ($retrievalData["teamId"] == "20072008") {
        $preRarryId = 1;
        $preSeasonSql  = "";
    } else {
//        $preSeasonSql  = "  AND  PRELRTM.`define` = " . $preSeason . " ";
        $preSeasonSql  = "";
    }

    if ($retrievalData["teamId"] == "none") {
        $teamSqlWhere .= " AND PRELRTM.`t_id` IS NULL ";
    } else {
    	$selectRarryId = teamRegistLastRarryData($connect, $retrievalData["teamId"]);
//print $selectRarryId;
//print $retrievalData["teamId"];
		if ($selectRarryId > 0) {
	    	$preSeasonRarryIdSql  = "  AND  PRELRTM.`r_id` = " . $selectRarryId . " ";
		    $preTeamInfoSql  = "  AND  LRM.`r_id` = " . $selectRarryId . " ";
		}
    }

    $sql = "SELECT " .
           "       distinct(LMI.`id`) AS memberId, " .
           "       PRELRTM.`define` AS define, " .
           "       LMI.`name_first` AS nameFirst, " .
           "       LMI.`name_second` AS nameSecond, " .
           "       LMI.`kana_first` AS kanaFirst, " .
           "       LMI.`kana_second` AS kanaSecond, " .
           "       CASE WHEN PRELRTM.`modified` = '0000-00-00 00:00:00' then 'NG' ELSE 'OK' END discharge, " .
           "       CASE WHEN PRELRTM.`number` IS NULL then '--' ELSE PRELRTM.`number` END preNumber, " .
           "       CASE WHEN NEXTLRTM.`number` IS NULL then '--' ELSE NEXTLRTM.`number` END nextNumber, " .
           //"       CASE WHEN PRELTI.`t_name` IS NULL then '未所属' ELSE PRELTI.`t_name` END preTeamName, " .
           "       CASE WHEN LRM.`team_name` IS NULL then '---' ELSE LRM.`team_name` END preTeamName, " .
           "       CASE WHEN NEXTLTI.`t_name` IS NULL then '---' ELSE NEXTLTI.`t_name` END nextTeamName " .
           " FROM ".dbTableName::LT_MEMBER_INFO." LMI " .
           " LEFT JOIN ".dbTableName::LT_REGIST_TEAM_MEMBER." PRELRTM " .
           "       ON  LMI.`id` = PRELRTM.`m_id` " .
           " " . $preSeasonRarryIdSql . " " .
           " " . $preSeasonSql . " " .
           //"  AND  PRELRTM.`r_id` = " . $preRarryId . " " .
           //"  AND  PRELRTM.`define` = " . $preSeason . " " .
           //"  AND  PRELRTM.`t_id` != " . $teamId . " " .
           " LEFT JOIN ".dbTableName::LT_REGIST_TEAM." LRM " .
           "       ON  LRM.`t_id` = PRELRTM.`t_id` " .
           " " . $preTeamInfoSql . " " .
           //"  AND  LRM.`r_id` = " . $preRarryId . " " .
           " LEFT JOIN ".dbTableName::LT_TEAM_INFO." PRELTI " .
           "       ON  PRELRTM.`t_id` = PRELTI.`id` " .
           " LEFT JOIN ".dbTableName::LT_REGIST_TEAM_MEMBER." NEXTLRTM " .
           "       ON  LMI.`id` = NEXTLRTM.`m_id` " .
           "  AND  NEXTLRTM.`r_id` = " . $nextRarryId . " " .
           "  AND  NEXTLRTM.`define` = " . $nextSeason . " " .
           //"  AND  NEXTLRTM.`t_id` != " . $teamId . " " .
           " LEFT JOIN ".dbTableName::LT_TEAM_INFO." NEXTLTI " .
           "       ON  NEXTLRTM.`t_id` = NEXTLTI.`id` " .
           " WHERE LMI.`id` > 0 " .
           " " . $sqlWhereBasic . " " .
//           "  AND (PRELRTM.`t_id` IS NULL OR PRELRTM.`t_id` != " . $teamId . " OR NEXTLRTM.`t_id` != " . $teamId . ") " .
           " " . $teamSqlWhere . " " .
           " " . $memberSqlWhere . " " .
           " " . $sqlOrder . " " ;
*/

    (boolean)$nameRetrieval = false;

    // 名前(姓)検索
    if ($retrievalData["nameFirst"] != "") {
        $memberSqlWhere .= " AND MI.`name_first` LIKE '%" . $retrievalData["nameFirst"] . "%' escape '#' ";
        $nameRetrieval = true;
    }
    // 名前(名)検索
    if ($retrievalData["nameSecond"] != "") {
        $memberSqlWhere .= " AND MI.`name_second` LIKE '%" . $retrievalData["nameSecond"] . "%' escape '#' ";
        $nameRetrieval = true;
    }

    // ヨミカナ(姓)検索
    if ($retrievalData["kanaFirst"] != "") {
        $memberSqlWhere .= " AND MI.`kana_first` LIKE '%" . $retrievalData["kanaFirst"] . "%' escape '#' ";
        $nameRetrieval = true;
    }
    // ヨミカナ(名)検索
    if ($retrievalData["kanaSecond"] != "") {
        $memberSqlWhere .= " AND MI.`kana_second` LIKE '%" . $retrievalData["kanaSecond"] . "%' escape '#' ";
        $nameRetrieval = true;
    }
    // 放出状態
    if ($retrievalData["state"] == 1) {
        //$teamSqlWhere .= " AND LMI.`name_first` = " . $retrievalData["nameFirst"] . " ";
    } else if ($retrievalData["state"] == 2) {
        $teamSqlWhere .= " AND RTM.`modified` IS NULL ";
        if (RARRY_RELATION == true) {
            if ($retrievalData["teamId"] > 0) {
                $teamSqlWhere .= " AND RTM.`modified` > '0000-00-00 00:00:00' ";
//            } else if ($retrievalData["teamId"] != "notRegist") {
//                $teamSqlWhere .= " AND (PRELRTM.`t_id` IS NULL OR PRELRTM.`modified` > '0000-00-00 00:00:00') ";
            }
        }
//    } else if ($retrievalData["state"] == 3) {
//        $teamSqlWhere .= " AND NEXTLTI.`t_name` IS NULL  ";
//        $teamSqlWhere .= " AND PRELRTM.`modified` = '0000-00-00 00:00:00' ";
    }

    // 検索チームIDなし
    if (!isset($retrievalData['teamId']) OR !is_numeric($retrievalData['teamId'])) {
    	return $buf;
    }

    if ($retrievalData["teamId"] > 0) {
    	$mainSqlWhere = "RTM.`t_id` = " . $retrievalData['teamId'];
    } else {
    	// 名前検索あり
    	if ($nameRetrieval == true) {
    		$mainSqlWhere = "RTM.`t_id` > 0";
    	} else {
	    	$mainSqlWhere = "RTM.`t_id` = 0";
    	}
    }

	$sql = "SELECT
			  MI.`id` AS memberId
			, RTM.`define` AS define
			, MI.`name_first` AS nameFirst
			, MI.`name_second` AS nameSecond
			, MI.`kana_first` AS kanaFirst
			, MI.`kana_second` AS kanaSecond
			FROM `regist_team_members` RTM
			LEFT JOIN `member_informations` MI
			 ON RTM.`m_id` = MI.`id`
			where " . $mainSqlWhere . "
			" . $memberSqlWhere . "
			group by MI.`kana_first`,MI.`kana_second`
	";
//print nl2br(print_r($retrievalData,true));


if ($_SERVER['REMOTE_ADDR'] == '210.254.40.8') {
//    print $sql."<br />";
}
    //print $sql;
    $rs  = $connect->Query($sql);
    if(!$rs){
        print $sql."<br />[retrievalPlayer]選手検索エラーです。";
        return false;
    }
    $nums = $connect->GetRowCount($rs);
    // データがあったら登録状態の取得
    if($nums > 0){
        for ($i=0; $i<$nums; $i++) {
            $data   = $connect->FetchRow($rs);       // １行Ｇｅｔ
            if ($i > 0) {
            	// 重複している選手IDは前データを削除
	            if ($data["memberId"] == $buf[$i-1]["memberId"]) {
	            	$buf[$i-1] = array();
	            }
            }
//print $data["define"];
            $buf[$i]["memberId"] = $data["memberId"];
            $buf[$i]["define"] = $data["define"];
            $buf[$i]["nameFirst"] = $data["nameFirst"];
            $buf[$i]["nameSecond"] = $data["nameSecond"];
            $buf[$i]["kanaFirst"] = $data["kanaFirst"];
            $buf[$i]["kanaSecond"] = $data["kanaSecond"];
//            $buf[$i]["discharge"] = $data["discharge"];
//            $buf[$i]["preNumber"] = $data["preNumber"];
//            $buf[$i]["nextNumber"] = $data["nextNumber"];
//            $buf[$i]["preTeamName"] = $data["preTeamName"];
//            $buf[$i]["nextTeamName"] = $data["nextTeamName"];
            // 放出チェック
            $preCheckData = checkMemberSelectSeason($connect, $data["memberId"], PRE_RARRY_ID, PRE_RARRY_SEASON);
            $nextCheckData = checkMemberSelectSeason($connect, $data["memberId"], NEXT_RARRY_ID, NEXT_RARRY_SEASON);

//print nl2br(print_r($checkData,true));
            if ($data["define"] != $preSeason) {
            	$buf[$i]["preNumber"] = '--';
            }
            // 旧大会データに選手情報があるとき
            if (count($preCheckData) > 0) {
            	// 旧大会の所属情報
            	$buf[$i]["preTeamName"] = $preCheckData['nowTeamName'];
            	$buf[$i]["preNumber"] = $preCheckData['nowTeamNumber'];
	            // 放出されているか
            	if ($preCheckData['modified'] == '0000-00-00 00:00:00') {
	            	$buf[$i]["discharge"] = 'NG';
	            } else {
	            	$buf[$i]["discharge"] = 'OK';
	            }
//print $checkData['nowTeamName'];
            } else {
            	$buf[$i]["preTeamName"] = 'なし';
            	$buf[$i]["preNumber"] = '--';
            	$buf[$i]["discharge"] = 'OK';
	        }

	        // 今期の大会データに選手情報があるとき
            if (count($nextCheckData) > 0) {
            	$buf[$i]["nextTeamName"] = $nextCheckData['nowTeamName'];
            } else {
            	$buf[$i]["nextTeamName"] = '---';
            }
        }
    }
//print nl2br(print_r($buf,true));
    return $buf;
}

function teamRegistLastRarryData($connect, $teamId) {

	$buf = 0;

	$sql = "SELECT LRT.`r_id`
			FROM ".dbTableName::LT_REGIST_TEAM." LRT
			LEFT JOIN ".dbTableName::LT_RARRY_INFO." LRI
				ON LRT.`r_id` = LRI.`id`
			WHERE LRT.`t_id` = ". $teamId . "
				AND LRI.`id` NOT IN (". NEXT_RARRY_ID . ")
				AND LRI.`parent_id` = 0
			ORDER BY LRT.`r_id` = ". PRE_RARRY_ID . " DESC, LRT.`r_id` DESC
			LIMIT 1";
//print $sql."<br />";
	$rs  = $connect->Query($sql);
    if(!$rs){
        print $sql."<br />[teamRegistLastRarryData]最終出場大会ID取得エラーです。";
        return false;
    }
	$nums = $connect->GetRowCount($rs);
    // データがあったら登録状態の取得
    if($nums > 0){
    	$data   = $connect->FetchRow($rs);       // １行Ｇｅｔ
    	$buf = $data['r_id'];
    }
    return $buf;
}

// メンバーの登録・放出チェック
function checkMemberSelectSeason($connect, $memberId, $rarryId, $season) {

	$buf = array();

	$sql = "SELECT LRM.`team_name`, LRTM.`number`, LRTM.`modified`
			FROM ".dbTableName::LT_REGIST_TEAM." LRM
			LEFT JOIN ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM
				ON LRM.`t_id` = LRTM.`t_id`
				AND LRTM.`r_id` = ". $rarryId . "
				AND LRTM.`m_id` = " . $memberId . "
				AND LRTM.`define` = " . $season . "
			WHERE LRM.`r_id` = ". $rarryId . "
			AND LRTM.`r_id` = ". $rarryId . "
			LIMIT 1";
if ($_SERVER['REMOTE_ADDR'] == '210.254.40.8') {
//print $sql."<br />";
}

    $rs  = $connect->Query($sql);
    if(!$rs){
        print $sql."<br />[checkMemberSelectSeason]選手検索エラーです。";
        return false;
    }
    $nums = $connect->GetRowCount($rs);
    // データがあったら登録状態の取得
    if($nums > 0){
        $data   = $connect->FetchRow($rs);       // １行Ｇｅｔ
        $buf['modified'] = $data['modified'];
        $buf['nowTeamName'] = $data['team_name'];
        $buf['nowTeamNumber'] = $data['number'];
    }
    return $buf;
}

// 背番号でソート
function memberSort($a, $b)
{
    if ($a['preNumber'] == $b['preNumber']) {
        return 0;
    }
    return ($a['preNumber'] < $b['preNumber']) ? -1 : 1;
}



// SMARTYにデータを送る
$smarty->assign("state", $state);
$smarty->assign("name_f_retrieval", $name_f_retrieval);
$smarty->assign("name_s_retrieval", $name_s_retrieval);
$smarty->assign("kana_f_retrieval", $kana_f_retrieval);
$smarty->assign("kana_s_retrieval", $kana_s_retrieval);
$smarty->assign("teamRetrieval", $teamRetrieval);
$smarty->assign("preRarryRegistTeamDatas", $preRarryRegistTeamDatas);
$smarty->assign("retrievalPlayersData", $retrievalPlayers);
$smarty->assign("mode", $mode);
$smarty->assign("transferComp", $transferComp);
$smarty->assign("registPlayerData", $registPlayerData);
$smarty->assign("rarryDataArray", $rarryDataArray);
$smarty->assign("preRarryDataArray", $preRarryDataArray);
$smarty->assign("RARRY_RELATION", RARRY_RELATION);
$smarty->assign("subName", SUB_NAME);

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>