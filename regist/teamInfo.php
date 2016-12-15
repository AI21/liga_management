<?php

session_start();

if (!isset($_SESSION['resistMember']["loginTime"])) {
	header("Location: ./loginError.php?loginTime");
}

// クラスファイル読み込み
/*
 * @file commons.inc		  共通関数
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
(string)$mode = "";
(string)$modeValue = "";
(string)$teamChangeStsValue = "";
(string)$sts = "";
(string)$members = "";
$aheadPlayer = array();
$errorValue = array();
$teamDatas = array();
$memberDataArray = array();
$takingMemberDataArray = array();
$insertTakingPlayers = array();
$nextMemberDatas = array();
$middleRegistMembers = array();
$preMemberDatas = array();
$preMemberDataArray = array();
$nextMembersId = array();
$mobileCommonDomains = array();
(string)$fmButtonValue = "";
(string)$teamDataChangeModeValue = "";
(string)$teamChangeButton = "		<input type=\"submit\" value=\"　チーム情報変更　\" />\n";
(string)$teamChangeConfButton = "			<input type=\"submit\" value=\"　内容確認　\" />\n";
(string)$teamChangeCompButton = "		<input type=\"button\" value=\"　変更　\" onclick=\"changeConf('teamChange');\" />\n";
(string)$teamChangeBackButton = "			<input type=\"button\" value=\"　戻る　\" onclick=\"pageBack('input');\" />\n";
(string)$teamChangeConfBackButton = "		<input type=\"button\" value=\"　やり直す　\" onclick=\"sendPages('teamChange', 'back');\" />\n";
(string)$sendMailError = "";
(boolean)$appliesPictureHome = false;
(boolean)$appliesPictureAway = false;
(boolean)$appliesPictureOther1 = false;
(boolean)$appliesPictureOther2 = false;
(boolean)$appliesPictureOther3 = false;

$_SESSION['resistMember']["memberRegist"] = "";

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
	while(list ($key, $val) = each($_POST)) {
		$$key = encode($val);
// print $key." = ".$val."<BR>";
	}
}

// チームの登録データ
if ($teamDataClass->registTeamData(NEXT_RARRY_ID, $teamId) == false) {
	// header("Location: ./loginError.php?NoTeamData");
}
if ($rarryId != $currentRarry) {
	$_SESSION['resistMember']["taking"] = "";
}
//print $mode." = $sts";
// 初期表示・モード無し
if ($mode == "") {
	$teamDatas = $teamDataClass->getTeamDatas();
	$modeValue = "teamChange";
	$fmButtonValue = $teamChangeButton;
	$_SESSION['resistMember']["rarryClass"] = $teamDatas["teamRarryClassName"];
	// NULLデータがあるデータは「未登録」に変換
	foreach ($teamDatas as $key => $val) {
		if ($_SERVER['REMOTE_ADDR'] == '220.30.174.79' ) {
			// print $key." = ".$val."<BR>";
		}
		if ($val == "" OR $val == "0") {
			$teamDatas[$key] = "未登録";
		}
	}
	// チームカラー表示
	if (count($optionArrays["teamColor"]) > 0) {
		foreach ($optionArrays["teamColor"] as $key => $val) {
			if ($key == $teamDatas["teamHomeColor"]) {
				$teamDatas["teamHomeColorView"] = $val;
			}
			if ($key == $teamDatas["teamAwayColor"]) {
				$teamDatas["teamAwayColorView"] = $val;
			}
		}
	}

	if (in_array($teamId, $noMobileTeam)) {
		$teamDatas["noMobile"] = true;
	}

// チーム情報変更モード
} else if ($mode == "teamChange") {
	if ($teamDataClass->mobileDomainCommonData() == true) {
		$commonMobileDatas = $teamDataClass->getCommonMobileDatas();
		for ($i=0; $i<count($commonMobileDatas); $i++) {
			$mobileDomains[$commonMobileDatas[$i]['id']] = $commonMobileDatas[$i]['domain'];
			$mobileCommonDomains[] = $commonMobileDatas[$i]['domain'];
		}
	}
	if ($sts == "") {
		$teamDatas = $teamDataClass->getTeamDatas();
		$modeValue = $mode;
		$teamChangeStsValue = "情報入力";
		$sts = "conf";
		$fmButtonValue = $teamChangeConfButton."&nbsp;".$teamChangeBackButton;
		$distrinctDatas = teamDistrict($connectDbClass);
		$teamDatas["teamRepMobileDomain"] = makeSelectBox("teamRepMobileDomain", $mobileDomains, $teamDatas["teamRepMobileId"]);
		$teamDatas["teamHomeColor"] = makeSelectBox("teamHomeColor", $optionArrays["teamHomeColor"], $teamDatas["teamHomeColor"]);
		$teamDatas["teamAwayColor"] = makeSelectBox("teamAwayColor", $optionArrays["teamAwayColor"], $teamDatas["teamAwayColor"]);
		$teamDatas["teamDistrict"] = makeSelectBox("teamDistrict", $distrinctDatas, $teamDatas["teamDistrictId"]);
	} else {

		$teamDatas["teamName"] = mbZen($teamName);
		$teamDatas["teamKana"] = mbZen($teamKana);
		$teamDatas["teamRep"] = mbZen($teamRep);
		$teamDatas["teamRepTel"] = mbHan($teamRepTel);
		$teamDatas["teamRepMail"] = mbHan($teamRepMail);
		$teamDatas["teamRepMobileAddress"] = mbHan($teamRepMobileAddress);
		$teamDatas["teamSubRep"] = mbZen($teamSubRep);
		$teamDatas["teamSubRepTel"] = mbHan($teamSubRepTel);
		$teamDatas["teamSubRepMail"] = mbHan($teamSubRepMail);
		$teamDatas["teamPlace"] = $teamPlace;

		// 代表者PCメールをアドレスとドメインに分割
		if ($teamDatas["teamRepMail"] != '') {
			list($teamRepAddress, $teamRepDomain) = explode('@', $teamDatas["teamRepMail"]);
		}

		/*
		 * フォームデータチェック
		 */
		// チーム名(漢字)
		if ($paramCheckClass->isNullCheck($errorMessageObj, $teamDatas["teamName"], 1, 64) == false) {
			$errorValue["teamName"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
			//$errorNums++;
		}
		// チーム名(カナ)
		if ($paramCheckClass->there_katakana($errorMessageObj, $teamDatas["teamKana"], 2, 64, "utf-8") == false) {
			$errorValue["teamKana"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
		}
		// 代表者(氏名)
		if ($paramCheckClass->there_zenkaku($errorMessageObj, $teamDatas["teamRep"], 2, 16, "utf-8") == false) {
			$errorValue["teamRep"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
		}
		// 代表者(TEL)
		if ($paramCheckClass->isMobileCheck($errorMessageObj, $teamDatas["teamRepTel"]) == false) {
			$errorValue["teamRepTel"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
		}
		// 代表者(PCメール)
		if ($teamDatas["teamRepMail"] != '' OR in_array($teamId, $noMobileTeam)) {
			if ($paramCheckClass->isNullCheck($errorMessageObj, $teamDatas["teamRepMail"], 2, 120) == false) {
				$errorValue["teamRepMail"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
				//$errorNums++;
			} elseif (in_array($teamRepDomain, $mobileCommonDomains)) {
				$errorValue["teamRepMail"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageObj->getErrorMessage("MOBILEDOMAIN_SAME")."</span>\n";
			} elseif ($paramCheckClass->isMailCheck($errorMessageObj, $teamDatas["teamRepMail"]) == false) {
				$errorValue["teamRepMail"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
			}
		}
		// 代表者(携帯メール)
		if (!in_array($teamId, $noMobileTeam)) {
			if ($paramCheckClass->isNullCheck($errorMessageObj, $teamDatas["teamRepMobileAddress"], 2, 100) == false) {
				$errorValue["teamRepMobileAddress"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
			} elseif ($teamRepMobileDomain == "") {
				$errorValue["teamRepMobileDomain"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageObj->getErrorMessage("MOBILEDOMAIN_NO_SELECT")."</span>\n";
			} else {
				(string)$selectMobileData = array();
				if ($teamDataClass->selectMobileDomainData($teamRepMobileDomain) == true) {
					$selectMobileData = $teamDataClass->getSelectMobileData();
				}
				if ($paramCheckClass->isMailCheck($errorMessageObj, $teamDatas["teamRepMobileAddress"].'@'.$selectMobileData['domain']) == false) {
					$errorValue["teamRepMobileAddress"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
				}
			}
		} else {
			$selectMobileData['domain'] = '';
		}
		// 副代表者(氏名)
		if ($teamDatas["teamSubRep"] != "" AND $paramCheckClass->there_zenkaku($errorMessageObj, $teamDatas["teamSubRep"], 2, 16, "utf-8") == false) {
			$errorValue["teamSubRep"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
		}
		// 副代表者(TEL)
		if ($teamDatas["teamSubRepTel"] != "" AND $paramCheckClass->isMobileCheck($errorMessageObj, $teamDatas["teamSubRepTel"]) == false) {
			$errorValue["teamSubRepTel"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
		}
		// 副代表者(メール)
		if ($teamDatas["teamSubRepMail"] != "" AND $paramCheckClass->isNullCheck($errorMessageObj, $teamDatas["teamSubRepMail"], 2, 120) == false) {
			$errorValue["teamSubRepMail"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
			//$errorNums++;
		} elseif ($teamDatas["teamSubRepMail"] != "" AND $paramCheckClass->isMailCheck($errorMessageObj, $teamDatas["teamSubRepMail"]) == false) {
			$errorValue["teamSubRepMail"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
		}
		// チーム活動地区
		if ($teamDistrict == "") {
//			$errorValue["teamDistrict"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageObj->getErrorMessage("DISTRICT_NO_SELECT")."</span>\n";
		}
		// チーム活動場所
		if ($teamDatas["teamPlace"] != "" AND $paramCheckClass->there_zenkaku($errorMessageObj, $teamDatas["teamPlace"], 2, 16, "utf-8") == false) {
			$teamDatas["teamPlace"]["teamPlace"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
		}
		// ホームカラー
		if ($teamHomeColor == "") {
			$errorValue["teamHomeColor"] = "<br /><span style=\"font-weight:bold;color:red;\">ホーム".$errorMessageObj->getErrorMessage("TEAMCOLOR_NO_SELECT")."</span>\n";
		}
		// アウェイカラー
		if ($teamAwayColor == "") {
			$errorValue["teamAwayColor"] = "<br /><span style=\"font-weight:bold;color:red;\">アウェイ".$errorMessageObj->getErrorMessage("TEAMCOLOR_NO_SELECT")."</span>\n";
		}
		// 代表者と副代表者の重複チェック
		if (!isset($errorValue["teamSubRep"]) AND $teamDatas["teamRep"] == $teamDatas["teamSubRep"]) {
			$errorValue["teamSubRep"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageObj->getErrorMessage("REPRESENT_SAME")."</span>\n";
		}
		if (!isset($errorValue["teamRepTel"]) AND $teamDatas["teamRepTel"] == $teamDatas["teamSubRepTel"]) {
			$errorValue["teamSubRepTel"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageObj->getErrorMessage("REPRESENT_TEL_SAME")."</span>\n";
		}
		if (!isset($errorValue["teamRepMail"]) AND $teamDatas["teamSubRepMail"] != "" AND $teamDatas["teamRepMail"] == $teamDatas["teamSubRepMail"]) {
			$errorValue["teamSubRepMail"] = "<br /><span style=\"font-weight:bold;color:red;\">".$errorMessageObj->getErrorMessage("REPRESENT_MAIL_SAME")."</span>\n";
		}

		if (count($errorValue) > 0 OR $sts == "back") {
			$modeValue = "teamChange";
			$sts = "conf";
			$teamChangeStsValue = "情報入力";
			$fmButtonValue = $teamChangeConfButton."&nbsp;".$teamChangeBackButton;
			$distrinctDatas = teamDistrict($connectDbClass);
			$teamDatas["teamRepMobileDomain"] = makeSelectBox("teamRepMobileDomain", $mobileDomains, $teamRepMobileDomain);
			$teamDatas["teamHomeColor"] = makeSelectBox("teamHomeColor", $optionArrays["teamHomeColor"], $teamHomeColor);
			$teamDatas["teamAwayColor"] = makeSelectBox("teamAwayColor", $optionArrays["teamAwayColor"], $teamAwayColor);
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
				$formDatas["teamRepMobileDomain"] = $teamRepMobileDomain;
				$formDatas["teamHomeColor"] = $teamHomeColor;
				$formDatas["teamAwayColor"] = $teamAwayColor;
				$teamDatas["teamRepMobileDomain"] = $selectMobileData['domain'];
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
				if ($teamDataChangeClass->teamDataChange(NEXT_RARRY_ID, $teamId, $changeDatas/*, $beforUserDatas*/) == true) {
					$teamDataChangeModeValue = "<div style=\"padding:7px 0;color:blue;font-size:18px;font-weight:bold;\">チーム情報を変更しました。</div>";
				} else {
					$teamDataChangeModeValue = "<div style=\"color:red;font-weight:bold;\">チーム情報の変更が失敗しました。</div>";
				}
				// チームの登録データ
				if ($teamDataClass->registTeamData(NEXT_RARRY_ID, $teamId) == true) {
					$teamDatas		   = $teamDataClass->getTeamDatas();
				} else {
					//$teamDataChangeModeValue = "<div>チーム情報の変更が失敗しました。</div>";
				}
			}
			if ($teamDistrict != '' AND $teamDataClass->selectDistrictData($teamDistrict) == true) {
				$teamDatas["teamDistrict"] = $teamDataClass->getDistrictDatas();
			}
			$teamDatas["teamHomeColor"] = $optionArrays["teamColor"]["$teamHomeColor"];
			$teamDatas["teamAwayColor"] = $optionArrays["teamColor"]["$teamAwayColor"];
		}
	}
} else if ($mode == "memberAhead") {

	$fmButtonValue = $teamChangeButton;
	$teamDatas = $teamDataClass->getTeamDatas();

	#if ($_SESSION['resistMember']["taking"] == "") {

		$registPlayers = array();
		$registSameTeamPlayers = array();
		$registdiffTeamPlayers = array();

//print print_r($aheadPlayer);

		(int)$insertPlayerNum = 0;
		(int)$aheadPlayerNum = count($aheadPlayer);

		for ($i=0; $i<$aheadPlayerNum; $i++) {
			if ($userCheckClass->memberRarryRegistCheck($errorMessageObj, NEXT_RARRY_ID, $preRarryId, NEXT_RARRY_SEASON, $preRarrySeason, $teamId, $aheadPlayer[$i]) == True) {
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
//print nl2br(print_r($insertTakingPlayers,true));
//print nl2br(print_r($registSameTeamPlayers,true));
//print nl2br(print_r($registdiffTeamPlayers,true));

		if (count($insertTakingPlayers) > 0) {
			// 選手登録
			if ($memberDataChangeClass->aheadSeasonPlayerTaking(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId, $insertTakingPlayers) == true) {
			}
		}
		$registPlayers = $insertTakingPlayers;
		// SMARTYにデータを送る
		$smarty->assign("registPlayers", $registPlayers);
		$smarty->assign("registSameTeamPlayers", $registSameTeamPlayers);
		$smarty->assign("registdiffTeamPlayers", $registdiffTeamPlayers);
	#}
	$_SESSION['resistMember']["taking"] = "insert";
} else if ($mode == "removeMember") {
	$teamDatas = $teamDataClass->getTeamDatas();
	$modeValue = "teamChange";
	$fmButtonValue = $teamChangeButton;
	// 今シーズンの登録選手から外す
	if ($teamDataChangeClass->removeTeamPlayer(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $memberId) == true) {
		// SMARTYにデータを送る
		$smarty->assign("removeComp", "ok");
	}
} else if ($mode == "dischargeMember") {
	$teamDatas = $teamDataClass->getTeamDatas();
	$modeValue = "teamChange";
	$fmButtonValue = $teamChangeButton;
	// 削除対象の選手情報を取得
	if ($memberDataClass->selectMemberData($memberId) == true) {
		$deleteMemberData	= $memberDataClass->getMemberData();
	}
	// 今シーズンの登録選手から外す
	if ($teamDataChangeClass->removeTeamPlayer(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $memberId) == true) {
		// 現在大会が関連していた場合
		if (RARRY_RELATION == true) {
			// 登録選手から放出する
			if ($teamDataChangeClass->dischargeTeamPlayer($preRarryId, $preRarrySeason, $memberId) == true) {
				$removeComp = "ok";
				if ($preRarryId != PRE_RARRY_ID) {
					// 特殊処理
					$teamDataChangeClass->dischargeTeamPlayer(PRE_RARRY_ID, PRE_RARRY_SEASON, $memberId);
				}
			}
		} else {
			$removeComp = "ok";
		}
		// SMARTYにデータを送る
		$smarty->assign("removeComp", $removeComp);
		$smarty->assign("deleteMemberData", $deleteMemberData);
	}
	/* シーズン登録用 */
	// 登録選手から放出する
//	if ($teamDataChangeClass->dischargeTeamPlayer(PRE_RARRY_ID, PRE_RARRY_SEASON, $memberId) == True) {
//		// SMARTYにデータを送る
//		$smarty->assign("removeComp", "ok");
//	}
} else if ($mode == "memberRegistComplete") {
	$teamDatas = $teamDataClass->getTeamDatas();

	// 申請フラグ更新
	//if ($teamDataChangeClass->registTeamPictureChange(NEXT_RARRY_ID, $teamId, 'member_regist_comp', true) == true) {
		//$readMode = 'readOnly';
		// 登録選手から放出する
		if ($teamDataChangeClass->dischargeTeamAllPlayer($preRarryId, $preRarrySeason, $teamId) == true) {
		}
	//}
}

// 今シーズン個人登録データ
if ($memberDataClass->rarrySeasonMemberList(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId) == true) {
	// 個人データ
	$nextMemberDatas	= $memberDataClass->getMemberDataList();
	$nextMemberNums = count($nextMemberDatas);
	for ($i=0; $i<$nextMemberNums; $i++) {
		// 今シーズン登録済み選手ID
		$nextMembersId[] = $nextMemberDatas[$i]["nemberId"];
//		$a[$nextMemberDatas[$i]["nemberId"]] = $nextMemberDatas[$i];

		// 中間登録の時
		if (NEXT_RARRY_SEASON > 1) {
			// 中間登録者を抽出
			if ($nextMemberDatas[$i]["created"] > REGIST_START_DATE) {
				$middleRegistMembers[] = $nextMemberDatas[$i]["nemberId"];
			}
		}
	}
	$nonPaymentMembers = 0;
	$nonPaymentYen = '0';
	for ($i=0; $i<$nextMemberNums; $i++) {
		if ($nextMemberDatas[$i]['registPayment'] == 0) {
			$nonPaymentMembers++;
			$nonPaymentYen = $nonPaymentMembers * 500;
		}
	}
	if ($mode == "memberRegistComplete") {
//print nl2br(print_r($_POST,true));
//		$upsTeamid = array( 1,2,4,7,8,10,12,13,14,16,30,41,42,47,48,61,
//							11,17,19,23,27,39,52,53,59,62,74,83,88,90);
//print nl2br(print_r($teamDatas,true));
		$year = (int)substr(TRANSFER_LIMIT_DATE, 0, 4);
		$month = (int)substr(TRANSFER_LIMIT_DATE, 5, 2);
		$days = (int)substr(TRANSFER_LIMIT_DATE, 8, 2);
/*
		if ($teamDatas['teamClass'] > 2) {
			$days = (int)substr(TRANSFER_LIMIT_DATE, 8, 2);
		} else {
			$days = (int)substr(TRANSFER_LIMIT_DATE, 8, 2) - 7;
		}
*/
		$weeks = date("w", mktime(0, 0, 0, $month, $days, $year));
		switch ($weeks) {
			case 1 : $week = '月'; break;
			case 2 : $week = '火'; break;
			case 3 : $week = '水'; break;
			case 4 : $week = '木'; break;
			case 5 : $week = '金'; break;
			case 6 : $week = '土'; break;
			default : $week = '日';
		}
		$mailTransferDate = date("n月j日", mktime(0, 0, 0, $month, $days, $year))."(" . $week . ")";
//print nl2br(print_r($teamDatas,true));
		//$sendMails = array('to' => array($teamDatas['teamRepMail'], $teamDatas['teamRepMobileAddress'].'@'.$teamDatas['teamRepMobileDomain']));
		$mailSendNum = count($mailSends);
		if ($mailSendNum > 0) {
			$mailSend = explode(',',$mailSends);
			$mailToArray = array();
			for ($i=0; $i<count($mailSend); $i++)	{
				switch ($mailSend[$i]) {
					case 'represent' :
						$mailToArray[] = $teamDatas['teamRepMail'];
						break;
					case 'represent_mobile' :
						if (($teamDatas['teamRepMobileAddress'] != '') AND ($teamDatas['teamRepMobileDomain'] != '')) {
							$mailToArray[] = $teamDatas['teamRepMobileAddress'].'@'.$teamDatas['teamRepMobileDomain'];
						}
						break;
					case 'sub_represent' :
						if ($teamDatas['teamSubRepMail'] != '') {
							$mailToArray[] = $teamDatas['teamSubRepMail'];
						}
						break;
					default : true;
				}
			}
			if ($mailSendOther != '') {
				$mailToArray[] = $mailSendOther;
			}
			$sendMails = array(
											'to' => $mailToArray,
										);
			// テンプレート内容の変換文字設定
			$mailParams = array(
								'TEAM_ID' => $teamId,
								'TEAM_NAME' => $teamDatas['teamName'],
								'TEAM_REPRESENT' => $teamDatas['teamRep'],
								'RARRY_SUB_NAME' => $rarryDataArray['rarry_sub_name'],
								'NON_PAYMENT_MEMBERS' => $nonPaymentMembers,
								'NON_PAYMENT_YEN' => number_format($nonPaymentYen),
								'TRANSFER_DATE' => $mailTransferDate,
								'RARRY_ID' => NEXT_RARRY_ID,
								'LIGA_MAIL' => LIGA_MAIL,
								'mailSubject' => '【リーガ東海】個人登録を受け付けました。',
			);
			// if ($_SERVER['REMOTE_ADDR'] == '220.30.174.79') {
			// 	print nl2br(print_r($rarryDataArray,true));
			// }
//print $mailSends. " = sends<br>";
//print $mailSendOther. " = mailSendOther<br>";;
//print nl2br(print_r($mailParams,true));
//print nl2br(print_r($sendMails,true));
			// クラス毎に選手追加・放出可能期日の設定がある場合
			if ( $aryLoginClassSetting[$strLoginTeamRegistClass]['opning'] == TRUE ) {
				$registCompMailTempUser = REGIST_COMP_MAIL_TEMP_USER_OPNING;
			} else {
				$registCompMailTempUser = REGIST_COMP_MAIL_TEMP_USER;
			}

			// 特殊対応
			if ($teamId == 13 OR $teamId == 132 OR $teamId == 136) {
				$registCompMailTempUser = REGIST_COMP_MAIL_TEMP_USER_OPNING;
				$mailParams['TRANSFER_DATE'] = "2016年03月28日(月)";
			}
			// $registCompMailTempUser = ($teamId == 167) ? 'indi_regist_comp_user_opening.txt' : REGIST_COMP_MAIL_TEMP_USER;
			//  $registCompMailTempUser = ($teamId != 0) ? 'indi_regist_comp_user_opening.txt' : REGIST_COMP_MAIL_TEMP_USER;

			// 登録完了メール送信：登録チーム宛
			if ($sendMailClass->autoSendMail($sendMails, $mailParams, $registCompMailTempUser) == false) {
				$sendMailError = $sendMailClass->getMailErrorValue();
				//print REGIST_COMP_MAIL_TEMP_USER.'1NG';
			}
			// 登録完了メール送信：管理者宛
			$sendMailMaster = array('to' => array(LIGA_MAIL));
//print nl2br(print_r($sendMailMaster,true));
			if ($sendMailClass->autoSendMail($sendMailMaster, $mailParams, REGIST_COMP_MAIL_TEMP_MASTER) == false) {
				$sendMailError = $sendMailClass->getMailErrorValue();
				//print REGIST_COMP_MAIL_TEMP_MASTER.'2NG';
			} else {
				//print 'OK';
			}
//print $script_name. " = script_name<br>";
			header("Location: ./".$script_name.".php");
		}
	}
}
//print $sendMailError."<hr />";

// 過去出場大会が違った場合
if ($preRarryId != PRE_RARRY_ID) {
	if ($rarryDataObj->rarryDetails($preRarryId) == true) {
		$preRarryDataArray = $rarryDataObj->getRarryDetail();
	}
}

// 前シーズン個人登録データ
if ($memberDataClass->rarrySeasonMemberList($preRarryId, $preRarrySeason, $teamId) == true) {
	// 個人データ
	$preMemberDataArray	= $memberDataClass->getMemberDataList();
	for ($i=0; $i<count($preMemberDataArray); $i++) {
		if (count($preMemberDataArray) > 0) {
//			$b[$preMemberDataArray[$i]["nemberId"]] = $preMemberDataArray[$i];
			if (!in_array ($preMemberDataArray[$i]["nemberId"], $nextMembersId)) {
				$preMemberDatas[] = $preMemberDataArray[$i];
			}
		} else {
			$preMemberDatas[] = $preMemberDataArray;
		}
	}
}
//print nl2br(print_r($teamDatas,true));
if ($mode == "") {
	// 投稿中の画像チェック
	if(file_exists('./tmp_contribute/'.NEXT_RARRY_ID.'/'.$teamDatas["teamPhoto"].'_home.jpg')){
		$appliesPictureHome = true;
	}
	if(file_exists('./tmp_contribute/'.NEXT_RARRY_ID.'/'.$teamDatas["teamPhoto"].'_away.jpg')){
		$appliesPictureAway = true;
	}
	if(file_exists('./tmp_contribute/'.NEXT_RARRY_ID.'/'.$teamDatas["teamPhoto"].'_other1.jpg')){
		$appliesPictureOther1 = true;
	}
	if(file_exists('./tmp_contribute/'.NEXT_RARRY_ID.'/'.$teamDatas["teamPhoto"].'_other2.jpg')){
		$appliesPictureOther2 = true;
	}
	if(file_exists('./tmp_contribute/'.NEXT_RARRY_ID.'/'.$teamDatas["teamPhoto"].'_other3.jpg')){
		$appliesPictureOther3 = true;
	}
}
//print nl2br(print_r($teamDatas,true));
//print nl2br(print_r($nextMemberDatas,true));
//print nl2br(print_r($nextMembersId,true))."<hr />";
//print nl2br(print_r($preMemberDataArray,true))."<hr />";
//print nl2br(print_r($preMemberDatas,true));
//print "<hr />";
//print nl2br(print_r($result,true));

$smarty->assign("nextMemberDatas", $nextMemberDatas);
$smarty->assign("preMemberDatas", $preMemberDatas);

$middleMemberNum = count($middleRegistMembers);
$nextMemberNum = count($nextMemberDatas);
if (NEXT_RARRY_SEASON > 1) {
	$smarty->assign("nextMemberNumMoney", number_format($middleMemberNum * 500));
} else {
	$smarty->assign("nextMemberNumMoney", number_format($nextMemberNum * 500));
}

//print nl2br(print_r($a,true))."<hr />";
//print nl2br(print_r($b,true))."<hr />";
//print_r(array_intersect_assoc($a, $b));


//number_format($nonPaymentYen);
//print $nonPaymentMembers." 人<br />";
//print $nonPaymentYen." 円<br />";
//print nl2br(print_r($teamDatas,true))."<hr />";

// メンバー登録完了モードの時
if ($teamDatas["memberRegistComp"] == 1) {
//	$readMode = 'readOnly';
}

function makeSelectBox($fmName, $optionArray, $datas = "") {

	$buf = "			<select name=\"" . $fmName . "\">\n";
	$buf .= "			  <option value=\"\">下記より選択</option>\n";
	if (count($optionArray) > 0) {
		foreach ($optionArray as $key => $val) {
			($key == $datas) ? $selected = " selected=\"selected\"" : $selected = "";
			$buf .= "			  <option class=\"" . $key . "\" value=\"" . $key . "\"" . $selected . ">" . $val . "</option>\n";
		}
	}
	$buf .= "			</select>\n";
	return $buf;
}

function teamDistrict($connect) {
	$sql = "SELECT * FROM ".dbTableName::M_DISTRICT_INFO." " ;
		$rs  = $connect->Query($sql);
		if(!$rs){
			print $sql."<br />[teamDistrict]都道府県情報取得エラーです。<br />".$sql."<br />";
			return false;
		}
		$nums = $connect->GetRowCount($rs);
		// データがあったら登録状態の取得
		if($nums > 0){
			for ($i=0; $i<$nums; $i++) {
				$data   = $connect->FetchRow($rs);	   // １行Ｇｅｔ
				$buf[$data["id"]] = $data["district_name"];
			}
			return $buf;
		}
}

// SMARTYにデータを送る
$smarty->assign("currentRarry", $currentRarry);
$smarty->assign("rarryId", $rarryId);
$smarty->assign("teamId", $teamId);
$smarty->assign("season", $season);
$smarty->assign("rarryClass", $_SESSION['resistMember']["rarryClass"]);
$smarty->assign("teamDatas", $teamDatas);
$smarty->assign("memberDataArray", $memberDataArray);
$smarty->assign("nonPaymentMembers", $nonPaymentMembers);
$smarty->assign("nonPaymentYen", $nonPaymentYen);
$smarty->assign("members", $members);
$smarty->assign("mode", $mode);
$smarty->assign("modeValue", $modeValue);
$smarty->assign("teamChangeStsValue", $teamChangeStsValue);
$smarty->assign("sts", $sts);
$smarty->assign("teamDataChangeModeValue", $teamDataChangeModeValue);
$smarty->assign("fmButtonValue", $fmButtonValue);
$smarty->assign("registStartDate", REGIST_START_DATE);
$smarty->assign("rarryDataArray", $rarryDataArray);
$smarty->assign("preRarryDataArray", $preRarryDataArray);
$smarty->assign("readMode", $readMode);
$smarty->assign("subName", SUB_NAME);
$smarty->assign("ligaMail", LIGA_MAIL);
$smarty->assign("appliesPictureHome", $appliesPictureHome);
$smarty->assign("appliesPictureAway", $appliesPictureAway);
$smarty->assign("appliesPictureOther1", $appliesPictureOther1);
$smarty->assign("appliesPictureOther2", $appliesPictureOther2);
$smarty->assign("appliesPictureOther3", $appliesPictureOther3);
$smarty->assign("member_discharge", MEMBER_DISCHARGE);
$smarty->assign("sendMailError", $sendMailError);

$smarty->assign("adminMode", $adminMode);

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>