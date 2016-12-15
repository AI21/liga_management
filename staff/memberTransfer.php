<?php

// クラスファイル読み込み
/*
 * @file commons.inc		  共通関数
 */
require_once "./common.inc";
// require_once "./serverCheck.inc";

// テンプレート場所の指定
$smarty->config_dir   = SMARTY_CONFIG_DIR;
$smarty->template_dir = SMARTY_TEMPLATE_DIR;
$smarty->compile_dir  = SMARTY_COMPLETE_DIR;

// スクリプト名の取得
$script_name =  basename($_SERVER['SCRIPT_NAME'],".php");

// 変数初期値
(int)$errorNums = 0;
(int)$teamId = 0;
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
(int)$page = 1;
(int)$maxPage = 1;
(int)$limit = 20;

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
	while(list ($key, $val) = each($_POST)) {
		$$key = encode($val);
		if ($_SERVER['REMOTE_ADDR'] == '220.110.52.161') {
			// print $key." = ".$val."<BR>";
		}
	}
}

if ( isset($_GET['page']) == TRUE && is_numeric($_GET['page']) == TRUE ) {
	$page = $_GET['page'];
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

	// 検索選手データ一覧
	$retrievalPlayersCnt = retrievalPlayer($connectDbClass, NEXT_RARRY_ID, NEXT_RARRY_SEASON, PRE_RARRY_ID, PRE_RARRY_SEASON, $teamId, $retrievalData, 'cnt', $page, $limit);
	// 検索選手総数
	$retrievalPlayersData = retrievalPlayer($connectDbClass, NEXT_RARRY_ID, NEXT_RARRY_SEASON, PRE_RARRY_ID, PRE_RARRY_SEASON, $teamId, $retrievalData, 'data', $page, $limit);

	$playerNum = count($retrievalPlayersData);

	if ($playerNum > 0) {
		for ($i=0; $i<$playerNum; $i++) {
			if (isset($retrievalPlayersData[$i]['memberId'])) {
				$retrievalPlayers[] = $retrievalPlayersData[$i];
			}
		}
		// 背番号ソート
		usort($retrievalPlayers, "memberSort");
	}

//print nl2br(print_r($retrievalPlayersData,ture));
}

// 前大会登録チームデータ
function preRarryRegistTeam($connect, $rarryId, $teamId) {

	$buf = array();

	$sql = "SELECT " .
		   "`t_id` AS teamId, " .
		   "`team_name` AS teamName " .
		   " FROM ".dbTableName::LT_REGIST_TEAM." " .
		   " WHERE `t_id` > 1 " .
		   " AND `view_flag` = 1 " .
		   " AND `spcial_flag` = 0 " .
		   " GROUP BY `team_name` " .
		   " ORDER BY `teamName`, `teamId`, `r_id` asc" ;
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
			$data   = $connect->FetchRow($rs);	   // １行Ｇｅｔ
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
		   "	   `name_first` AS nameFirst, " .
		   "	   `name_second` AS nameSecond " .
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
		$data   = $connect->FetchRow($rs);	   // １行Ｇｅｔ
		$buf["nameFirst"] = $data["nameFirst"];
		$buf["nameSecond"] = $data["nameSecond"];
	}
	return $buf;
}

// 選手検索
function retrievalPlayer($connect, $nextRarryId, $nextSeason, $preRarryId, $preSeason, $teamId, $retrievalData, $mode, $page = 1, $limit = 20) {

	global $memberDataClass;

	$buf = array();

	(string)$memberSqlWhere = "";
	(string)$teamSqlWhere = "";
	(string)$sqlOrder = "";
	(string)$preSeasonRarryIdSql  = "";
	(string)$preSeasonSql  = "";
	(string)$preTeamInfoSql  = "";

	(string)$sqlWhereBasic = " AND (PRELRTM.`t_id` IS NULL OR PRELRTM.`t_id` != " . $teamId . " OR NEXTLRTM.`t_id` != " . $teamId . ") ";

	if (isset($retrievalData["teamId"]) AND $retrievalData["teamId"] > 0) {
		$teamSqlWhere = "RTM.`t_id` = " . $retrievalData["teamId"];
	} else {
		$teamSqlWhere = "RTM.`t_id` > 0 ";
	}

	// 前半角の空白除去
	// (string)$selectNameFirst  = mb_ereg_replace("　","",mb_ereg_replace(" ","",trim($retrievalData["nameFirst"], "　")));
	// (string)$selectNameSecond = mb_ereg_replace("　","",mb_ereg_replace(" ","",trim($retrievalData["nameSecond"], "　")));
	// (string)$selectKanaFirst  = mb_ereg_replace("　","",mb_ereg_replace(" ","",trim($retrievalData["kanaFirst"], "　")));
	// (string)$selectKanaSecond = mb_ereg_replace("　","",mb_ereg_replace(" ","",trim($retrievalData["kanaSecond"], "　")));
	(string)$selectNameFirst  = preg_replace('/(\s|　)/','',$retrievalData["nameFirst"]);
	(string)$selectNameSecond  = preg_replace('/(\s|　)/','',$retrievalData["nameSecond"]);
	(string)$selectKanaFirst  = preg_replace('/(\s|　)/','',$retrievalData["kanaFirst"]);
	(string)$selectKanaSecond  = preg_replace('/(\s|　)/','',$retrievalData["kanaSecond"]);
	if ($_SERVER['REMOTE_ADDR'] == '220.110.52.161') {
		// print $selectNameFirst." = KANA<br />";
		// print $selectNameSecond." = KANA<br />";
		// print $selectKanaFirst." = KANA<br />";
		// print $selectKanaSecond." = KANA<br />";
	}

	// 名前(姓)検索
	if ($selectNameFirst != "") {
		$memberSqlWhere .= " AND MI.`name_first` LIKE '%" . $selectNameFirst . "%' escape '#' ";
	}
	// 名前(名)検索
	if ($selectNameSecond != "") {
		$memberSqlWhere .= " AND MI.`name_second` LIKE '%" . $selectNameSecond . "%' escape '#' ";
	}
	// ヨミカナ(姓)検索
	if ($selectKanaFirst != "") {
		$memberSqlWhere .= " AND MI.`kana_first` LIKE '%" . $selectKanaFirst . "%' escape '#' ";
	}
	// ヨミカナ(名)検索
	if ($selectKanaSecond != "") {
		$memberSqlWhere .= " AND MI.`kana_second` LIKE '%" . $selectKanaSecond . "%' escape '#' ";
	}

	if ($memberSqlWhere != '') {
		// $teamSqlWhere = "RTM.`t_id` > 0 AND RTM.`modified` > '0000-00-00 00:00:00'";
		$teamSqlWhere = " 1 = 1 ";
	}

	// 取得件数
	$intStart = $limit * ($page - 1);
	$intGetNum = $intStart + $limit;

	if ($mode == 'cnt') {

		$sql = "SELECT
					COUNT(T.memberId)
				FROM
					(
						SELECT
							MI.`id` AS memberId
						FROM
							`member_informations` MI
						LEFT JOIN `regist_team_members` RTM
				 			ON RTM.`m_id` = MI.`id`
						LEFT JOIN `regist_teams` RT
							ON RTM.`t_id` = RT.`t_id`
						where
						" . $teamSqlWhere . "
						" . $memberSqlWhere . "
						group by MI.`name_first`,MI.`name_second`
					) T
		";

	} else {

		$sql = "SELECT
				MI.id AS memberId,
				RTM.define AS define,
				MI.`name_first` AS nameFirst,
				MI.`name_second` AS nameSecond,
				MI.`kana_first` AS kanaFirst,
				MI.`kana_second` AS kanaSecond,
				MI.`height` AS height,
				MI.`birthday` AS birthday
				FROM `member_informations` MI
				LEFT JOIN `regist_team_members` RTM
				 ON RTM.`m_id` = MI.`id`
				LEFT JOIN `regist_teams` RT
				 ON RTM.`t_id` = RT.`t_id`
				where
				" . $teamSqlWhere . "
				" . $memberSqlWhere . "
				group by MI.`name_first`,MI.`name_second`
		";
	}

if ($_SERVER['REMOTE_ADDR'] == '220.30.174.79') {
	// print $sql."<br />";
}
	$rs  = $connect->Query($sql);
	if(!$rs){
		print $sql."<br />[retrievalPlayer]選手検索エラーです。";
		return false;
	}
	$nums = $connect->GetRowCount($rs);
	// データがあったら登録状態の取得
	if($nums > 0){
		if ($mode == 'cnt') {
			$data   = $connect->FetchRow($rs);	   // １行Ｇｅｔ
		// print nl2br(print_r($data, 1));
			$buf = $data[0];
		// print $buf;
		} else {
			for ($i=0; $i<$nums; $i++) {
				$data   = $connect->FetchRow($rs);	   // １行Ｇｅｔ
				$buf[$i]["memberId"] = $data["memberId"];
				$buf[$i]["season"] = $data["season"];
				$buf[$i]["nameFirst"] = $data["nameFirst"];
				$buf[$i]["nameSecond"] = $data["nameSecond"];
				$buf[$i]["kanaFirst"] = $data["kanaFirst"];
				$buf[$i]["kanaSecond"] = $data["kanaSecond"];
				$buf[$i]["height"] = $data["height"];
				$buf[$i]["birthday"] = date('Y年m月d日', strtotime($data["birthday"]));
				$buf[$i]["discharge"] = 'OK';
				$buf[$i]["preTeamName"] = '---';
				$buf[$i]["preNumber"] = '---';
				$buf[$i]["nextTeamName"] = '---';
				$buf[$i]["nextNumber"] = '---';
				if ($memberDataClass->selectMemberLastRarryData($data["memberId"], NEXT_RARRY_ID, PRE_RARRY_ID, PRE_RARRY_SEASON) == true) {
					$lastTaemData = $memberDataClass->getMemberLastRarryData();
					$buf[$i]["preTeamName"] = $lastTaemData['team_name'];
					$buf[$i]["preNumber"] = '---';
					$buf[$i]["nextTeamName"] = '---';
					$buf[$i]["nextNumber"] = '---';
					if ($lastTaemData['modified'] == '0000-00-00 00:00:00') {
						$buf[$i]["discharge"] = 'NG';
						$buf[$i]["preTeamName"] = $lastTaemData['team_name'];
						$buf[$i]["preNumber"] = $lastTaemData['number'];
						$buf[$i]["nextTeamName"] = $lastTaemData['team_name'];
						$buf[$i]["nextNumber"] = $lastTaemData['number'];
					}
				}
				if ($memberDataClass->selectMemberLastRarryData($data["memberId"], NEXT_RARRY_ID, NEXT_RARRY_ID, NEXT_RARRY_SEASON) == true) {
					$lastTaemData = $memberDataClass->getMemberLastRarryData();
					$buf[$i]["preTeamName"] = $lastTaemData['team_name'];
					$buf[$i]["preNumber"] = '---';
					$buf[$i]["nextTeamName"] = '---';
					$buf[$i]["nextNumber"] = '---';
					if ($lastTaemData['modified'] == '0000-00-00 00:00:00') {
						$buf[$i]["discharge"] = 'NG';
						$buf[$i]["preTeamName"] = $lastTaemData['team_name'];
						$buf[$i]["preNumber"] = $lastTaemData['number'];
						$buf[$i]["nextTeamName"] = $lastTaemData['team_name'];
						$buf[$i]["nextNumber"] = $lastTaemData['number'];
					}
				}
			}
		}
	}
// print nl2br(print_r($buf,true));
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
		$data   = $connect->FetchRow($rs);	   // １行Ｇｅｔ
		$buf = $data['r_id'];
	}
	return $buf;
}

function checkMemberSelectSeason($connect, $memberId, $rarryId, $season) {

	$buf = array();

	$sql = "SELECT LRM.`team_name`, LRTM.`number`, LRTM.`modified`
			FROM ".dbTableName::LT_REGIST_TEAM." LRM
			LEFT JOIN ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM
				ON LRM.`t_id` = LRTM.`t_id`
				AND LRTM.`r_id` = ". $rarryId . "
				AND LRTM.`m_id` = " . $memberId . "
				AND LRTM.`season` = " . $season . "
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
		$data   = $connect->FetchRow($rs);	   // １行Ｇｅｔ
		$buf['modified'] = $data['modified'];
		$buf['nowTeamName'] = $data['team_name'];
		$buf['nowTeamNumber'] = $data['number'];
	}
	return $buf;
}

// 背番号でソート
function memberSort($a, $b) {
	if ($a['preNumber'] == $b['preNumber']) {
		return 0;
	}
	return ($a['preNumber'] < $b['preNumber']) ? -1 : 1;
}

// 最大ページ数
$maxPage = ceil($retrievalPlayersCnt / $limit);

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
$smarty->assign("retrievalPlayersCnt", $retrievalPlayersCnt);
$smarty->assign("page", $page);
$smarty->assign("maxPage", $maxPage);
$smarty->assign("limit", $limit);

// テンプレート指定
$smarty->display($fileName.'.tpl');
//print ($fileName.'.tpl');
?>