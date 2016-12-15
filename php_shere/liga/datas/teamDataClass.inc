<?php

// =============================================================================
// $Id: $
/**
 * @file	teamDataClass.inc
 * @brief   チーム情報取得関数
 * @author
 * @date	Last updated:
 *
 *		  Copyright(c) 2006
 */
// =============================================================================
/**
 *
 */
// =============================================================================
class teamDataClass extends connectionDBClass {

	/**
	 * コンストラクター.
	 */
	public function __construct() {
	}

	// 選択チームデータ
	public function selectTeamData($team_id = 0) {

		$retData = False;

//print $team_id." = TEAM<BR>";

		// チームIDがないときは終了
		if (!isset($team_id)) {
			return  $retData;
		}

		# チームデータの取得
		$sql = "SELECT " .
			   " CASE when LTI.`t_name`				 IS NULL then '未登録' ELSE LTI.`t_name`				 END t_name , " .
			   " CASE when LTI.`t_kana`				 IS NULL then '未登録' ELSE LTI.`t_kana`				 END t_kana , " .
			   " LTI.`abb_name` AS abb_name, " .
			   " CASE when LTI.`class`				  IS NULL then '未登録' ELSE LTI.`class`				  END class , " .
			   " CASE when LTI.`site`				   IS NULL then '未登録' ELSE LTI.`site`				   END site , " .
			   " CASE when LTI.`photo`				  IS NULL then '未登録' ELSE LTI.`photo`				  END photo , " .
			   " CASE when LTI.`home_color`			 IS NULL then '未登録' ELSE LTI.`home_color`			 END home_color , " .
			   " CASE when LTI.`away_color`			 IS NULL then '未登録' ELSE LTI.`away_color`			 END away_color , " .
			   " CASE when LTI.`activity_district`	  IS NULL then '未登録' ELSE LTI.`activity_district`	  END activity_district , " .
			   " CASE when LTI.`activity_place`		 IS NULL then '未登録' ELSE LTI.`activity_place`		 END activity_place , " .
			   " CASE when LTI.`region`				 IS NULL then '未登録' ELSE LTI.`region`				 END region , " .
			   " CASE when LTI.`week`				   IS NULL then '未登録' ELSE LTI.`week`				   END week , " .
			   " CASE when LTI.`sub_reague`			 IS NULL then '未登録' ELSE LTI.`sub_reague`			 END sub_reague , " .
			   " CASE when LTI.`t_comment`			  IS NULL then '未登録' ELSE LTI.`t_comment`			  END t_comment , " .
			   " CASE when LTI.`represent`			  IS NULL then '未登録' ELSE LTI.`represent`			  END represent , " .
			   " CASE when LTI.`represent_tel`		  IS NULL then '未登録' ELSE LTI.`represent_tel`		  END represent_tel , " .
			   " CASE when LTI.`represent_address`	  IS NULL then '未登録' ELSE LTI.`represent_address`	  END represent_address , " .
			   " CASE when LTI.`sub_represent`		  IS NULL then '未登録' ELSE LTI.`sub_represent`		  END sub_represent , " .
			   " CASE when LTI.`sub_represent_tel`	  IS NULL then '未登録' ELSE LTI.`sub_represent_tel`	  END sub_represent_tel , " .
			   " CASE when LTI.`sub_represent_address`  IS NULL then '未登録' ELSE LTI.`sub_represent_address`  END sub_represent_address , " .
			   " CASE when LTI.`umpire_1`			   IS NULL then '未登録' ELSE LTI.`umpire_1`			   END umpire_1 , " .
			   " CASE when LTI.`umpire_2`			   IS NULL then '未登録' ELSE LTI.`umpire_2`			   END umpire_2 , " .
			   " CASE when LTI.`umpire_3`			   IS NULL then '未登録' ELSE LTI.`umpire_3`			   END umpire_3 , " .
			   " LTI.`login_id` AS login_id, " .
			   " MDI.`district_name` AS district_name " .
			   " FROM ".dbTableName::LT_TEAM_INFO." LTI " .
			   " LEFT JOIN ".dbTableName::M_DISTRICT_INFO." MDI " .
			   "		ON  LTI.`activity_district` = MDI.`id` " .
			   " WHERE LTI.id = " . $team_id . "";
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[teamData]チームデータ取得エラーです。<br />".$sql."<br />"; return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){

			$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

			$this->teamName = $data["t_name"];
			$this->teamKana = $data["t_kana"];
			$this->teamClass = $data["class"];
			$this->teamSite = $data["site"];
			$this->teamPhoto = $data["photo"];
			$this->teamHomeColor = $data["home_color"];
			$this->teamAwayColor = $data["away_color"];
			$this->teamActivityDistrict = $data["activity_district"];
			$this->teamActivityPlace = $data["activity_place"];
			$this->teamRegion = $data["region"];
			$this->teamWeek = $data["week"];
			$this->teamSubLeague = $data["sub_reague"];
			$this->teamComment = $data["t_comment"];
			$this->teamRepresent = $data["represent"];
			$this->teamRepresentTel = $data["represent_tel"];
			$this->teamRepresent = $data["represent_address"];
			$this->teamSubRepresent = $data["sub_represent"];
			$this->teamSubRepresentTel = $data["sub_represent_tel"];
			$this->teamSubrepresentAddress = $data["sub_represent_address"];
			$this->teamUmpire1 = $data["umpire_1"];
			$this->teamUmpire2 = $data["umpire_2"];
			$this->teamUmpire3 = $data["umpire_3"];

			$this->selectTeamDatas["teamName"] = $data["t_name"];
			$this->selectTeamDatas["teamKana"] = $data["t_kana"];
			$this->selectTeamDatas["abb_name"] = $data["abb_name"];
			$this->selectTeamDatas["teamClass"] = $data["class"];
			$this->selectTeamDatas["teamSite"] = $data["site"];
			$this->selectTeamDatas["teamPhoto"] = $data["photo"];
			$this->selectTeamDatas["teamHomeColor"] = $data["home_color"];
			$this->selectTeamDatas["teamAwayColor"] = $data["away_color"];
			$this->selectTeamDatas["teamDistrictId"] = $data["activity_district"];
			$this->selectTeamDatas["teamPlace"] = $data["activity_place"];
			$this->selectTeamDatas["teamRegion"] = $data["region"];
			$this->selectTeamDatas["teamWeek"] = $data["week"];
			$this->selectTeamDatas["teamSubLeague"] = $data["sub_reague"];
			$this->selectTeamDatas["teamComment"] = $data["t_comment"];
			$this->selectTeamDatas["teamRep"] = $data["represent"];
			$this->selectTeamDatas["teamRepTel"] = $data["represent_tel"];
			$this->selectTeamDatas["teamRepMail"] = $data["represent_address"];
			$this->selectTeamDatas["teamSubRep"] = $data["sub_represent"];
			$this->selectTeamDatas["teamSubRepTel"] = $data["sub_represent_tel"];
			$this->selectTeamDatas["teamSubRepMail"] = $data["sub_represent_address"];
			$this->selectTeamDatas["teamUmpire1"] = $data["umpire_1"];
			$this->selectTeamDatas["teamUmpire2"] = $data["umpire_2"];
			$this->selectTeamDatas["teamUmpire3"] = $data["umpire_3"];
			$this->selectTeamDatas["login_id"] = $data["login_id"];
			$this->selectTeamDatas["district_name"] = $data["district_name"];

			$retData = True;
		} else {

			$this->teamName = "";
			$this->teamKana = "";
			$this->teamClass = 0;
			$this->teamSite = "";
			$this->teamPhoto = "";
			$this->teamHomeColor = "";
			$this->teamAwayColor = "";
			$this->teamActivityDistrict = "";
			$this->teamActivityPlace = "";
			$this->teamRegion = "";
			$this->teamWeek = "";
			$this->teamSubLeague = "";
			$this->teamComment = "";
			$this->teamRepresent = "";
			$this->teamRepresentTel = "";
			$this->teamRepresent = "";
			$this->teamSubRepresent = "";
			$this->teamSubRepresentTel = "";
			$this->teamSubrepresentAddress = "";
			$this->teamUmpire1 = "";
			$this->teamUmpire2 = "";
			$this->teamUmpire3 = "";

			$retData = True;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}

	// チームデータ
	/**
	 * 概要
	 *
	 * 説明
	 *
	 * @param integer	$team_id   チームID
	 *
	 * @return string		  説明
	 * @return void
	 */
	public function registTeamData($rarryId, $teamId = 0) {

		$retData = False;

		// チームIDがないときは終了
		if (!isset($rarryId) AND !isset($teamId)) {
			return  $retData;
		}

		# チームデータの取得
		$sql = "SELECT " .
				" LTI.`t_name` , " .
				" LRT.`team_name` , " .
			   " LTI.`t_kana` , " .
			   " LTI.`class` , " .
			   " LTI.`site` , " .
			   " LTI.`photo` , " .
			   " LTI.`home_color` , " .
			   " LTI.`away_color` , " .
			   " LTI.`activity_district` , " .
			   " MDI.`district_name` , " .
			   " LTI.`activity_place` , " .
			   " LTI.`region` , " .
			   " LTI.`week` , " .
			   " LTI.`sub_reague` , " .
			   " LTI.`t_comment` , " .
			   " LTI.`represent` , " .
			   " LTI.`represent_tel` , " .
			   " LTI.`represent_address` , " .
			   " LTI.`represent_mobile_address` , " .
			   " MMD.`id` AS mobile_id , " .
			   " MMD.`domain` , " .
			   " LTI.`sub_represent` , " .
			   " LTI.`sub_represent_tel` , " .
			   " LTI.`sub_represent_address` , " .
			   " LTI.`umpire_1` , " .
			   " LTI.`umpire_2` , " .
			   " LTI.`umpire_3` , " .
			   " CASE when LRT.`picture_home` !=  '' then true ELSE false END picture_home , " .
			   " CASE when LRT.`picture_away` !=  '' then true ELSE false END picture_away , " .
			   " LRT.`applies_pic_h`, " .
			   " LRT.`applies_pic_a`, " .
			   " LRT.`member_regist_comp`, " .
			   " LRT.`class` , " .
			   " LCI.`block_name`  " .
/*
			   " CASE when `t_name`	   IS NULL then '未登録' ELSE `t_name`	   END t_name , " .
			   " CASE when `t_kana`	   IS NULL then '未登録' ELSE `t_kana`	   END t_kana , " .
			   " CASE when `class`		IS NULL then '未登録' ELSE `class`		END class , " .
			   " CASE when `site`		 IS NULL then '未登録' ELSE `site`		 END site , " .
			   " CASE when `photo`		IS NULL then '未登録' ELSE `photo`		END photo , " .
			   " CASE when `home_color`   IS NULL then '未登録' ELSE `home_color`   END home_color , " .
			   " CASE when `away_color`   IS NULL then '未登録' ELSE `away_color`   END away_color , " .
			   " CASE when `region`	   IS NULL then '未登録' ELSE `region`	   END region , " .
			   " CASE when `week`		 IS NULL then '未登録' ELSE `week`		 END week , " .
			   " CASE when `sub_reague`   IS NULL then '未登録' ELSE `sub_reague`   END sub_reague , " .
			   " CASE when `t_comment`	IS NULL then '未登録' ELSE `t_comment`	END t_comment , " .
			   " CASE when `represent_1`  IS NULL then '未登録' ELSE `represent_1`  END represent_1 , " .
			   " CASE when `represent_2`  IS NULL then '未登録' ELSE `represent_2`  END represent_2 , " .
			   " CASE when `umpire_1`	 IS NULL then '未登録' ELSE `umpire_1`	 END umpire_1 , " .
			   " CASE when `umpire_2`	 IS NULL then '未登録' ELSE `umpire_2`	 END umpire_2 , " .
			   " CASE when `umpire_3`	 IS NULL then '未登録' ELSE `umpire_3`	 END umpire_3  " .
*/
			   " FROM ".dbTableName::LT_REGIST_TEAM." LRT " .
			   " LEFT JOIN ".dbTableName::LT_TEAM_INFO." LTI " .
			   "		ON  LRT.`t_id` = LTI.`id` " .
			   " LEFT JOIN ".dbTableName::M_DISTRICT_INFO." MDI " .
			   "		ON  LTI.`activity_district` = MDI.`id` " .
			   " LEFT JOIN ".dbTableName::RARRY_CLASS_INFO." LCI " .
			   "		ON  LRT.`class` = LCI.`id` " .
			   " LEFT JOIN ".dbTableName::M_MOBILE_DOMAIN." MMD " .
			   "		ON  LTI.`represent_mobile_domain` = MMD.`id` " .
//			   " LEFT JOIN ".dbTableName::M_MOBILE_CAREERS." MMC " .
//			   "		ON  MMD.`careers_id` = MMC.`id` " .
			   " WHERE LRT.r_id = " . $rarryId . "" .
			   "  AND  LRT.t_id = " . $teamId . "";
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[daas/teamDataClass::registTeamData]チームデータの取得エラーです。<br />".$sql."<br />"; $this->DbErrorValue = $this->GetLastError(); return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){

			$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

			$this->teamDatas["teamName"] = $data["t_name"];
			$this->teamDatas["registSeasonTeamName"] = $data["team_name"];
			$this->teamDatas["teamKana"] = $data["t_kana"];
			$this->teamDatas["teamClass"] = $data["class"];
			$this->teamDatas["teamSite"] = $data["site"];
			$this->teamDatas["teamPhoto"] = $data["photo"];
			$this->teamDatas["teamHomeColor"] = $data["home_color"];
			$this->teamDatas["teamAwayColor"] = $data["away_color"];
			$this->teamDatas["teamDistrictId"] = $data["activity_district"];
			$this->teamDatas["teamDistrict"] = $data["district_name"];
			$this->teamDatas["teamPlace"] = $data["activity_place"];
			$this->teamDatas["teamRegion"] = $data["region"];
			$this->teamDatas["teamWeek"] = $data["week"];
			$this->teamDatas["teamSubLeague"] = $data["sub_reague"];
			$this->teamDatas["teamComment"] = $data["t_comment"];
			$this->teamDatas["teamRep"] = $data["represent"];
			$this->teamDatas["teamRepTel"] = $data["represent_tel"];
			$this->teamDatas["teamRepMail"] = $data["represent_address"];
			$this->teamDatas["teamRepMobileAddress"] = $data["represent_mobile_address"];
			$this->teamDatas["teamRepMobileId"] = $data["mobile_id"];
			$this->teamDatas["teamRepMobileDomain"] = $data["domain"];
			$this->teamDatas["teamSubRep"] = $data["sub_represent"];
			$this->teamDatas["teamSubRepTel"] = $data["sub_represent_tel"];
			$this->teamDatas["teamSubRepMail"] = $data["sub_represent_address"];
			$this->teamDatas["teamUmpire1"] = $data["umpire_1"];
			$this->teamDatas["teamUmpire2"] = $data["umpire_2"];
			$this->teamDatas["teamUmpire3"] = $data["umpire_3"];
			$this->teamDatas["pictureHome"] = $data["picture_home"];
			$this->teamDatas["pictureAway"] = $data["picture_away"];
			$this->teamDatas["appliesPictureHome"] = $data["applies_pic_h"];
			$this->teamDatas["appliesPictureAway"] = $data["applies_pic_a"];
			$this->teamDatas["memberRegistComp"] = $data["member_regist_comp"];
			$this->teamDatas["teamRarryClass"] = $data["class"];
			$this->teamDatas["teamRarryClassName"] = $data["block_name"];

			$retData = True;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}

	// リーグ登録一覧データ
	/**
	 * 概要
	 *
	 * 説明
	 *
	 * @param string	$arg   説明
	 *
	 * @return string		  説明
	 * @return void
	 */
	 public function LeagueTeam($rarryId, $block=null, $mode = 'view') {

		$retData = False;
		(string)$sqlWhere = ' AND LTI.`id` > 0';

		if ($block AND is_numeric($block)) {
			$sqlWhere = 'AND LRT.`class` = '.$block;
		}

		if ($mode == 'view') {
			$sqlWhere .= " AND LTI.`login_id` != ''";
		}

		# チームデータの取得
		$sql = "SELECT
						LTI.`id`,
						LRT.`team_name` AS t_name,
						LTI.`t_kana`,
						LRT.`class`,
						MCI.`block_name`,
						LTI.`site`,
						LTI.`photo`,
						LRT.`applies_pic_h`,
						LRT.`applies_pic_a`,
						LTI.`home_color`,
						LTI.`away_color`,
						LTI.`activity_district`,
						LTI.`activity_place`,
						LTI.`region`,
						LTI.`week`,
						LTI.`sub_reague`,
						LTI.`t_comment`,
						LTI.`represent`,
						LTI.`represent_tel`,
						LTI.`represent_address`,
						LTI.`represent_mobile_address`,
						MMD.`domain`,
						LTI.`sub_represent`,
						LTI.`sub_represent_tel`,
						LTI.`sub_represent_address`,
						LTI.`umpire_1`,
						LTI.`umpire_2`,
						LTI.`umpire_3`
					 FROM ".dbTableName::LT_TEAM_INFO." LTI
					 LEFT JOIN ".dbTableName::LT_REGIST_TEAM." LRT
						   ON  LTI.`id` = LRT.`t_id`
					 LEFT JOIN ".dbTableName::RARRY_CLASS_INFO." MCI
						   ON  LRT.`class` = MCI.`id`
					 LEFT JOIN ".dbTableName::M_MOBILE_DOMAIN." MMD
						   ON  LTI.`represent_mobile_domain` = MMD.`id`
					 WHERE LRT.`r_id` = '" . $rarryId . "'
					   AND LRT.`view_flag` = 1
					 " . $sqlWhere . "
						ORDER BY LRT.`class`, MCI.`seq_no`, MCI.`view_no`, LRT.`team_name`, LTI.t_kana ";
// print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[teamData]リーグ登録一覧データ取得エラーです。<br />".$sql."<br />"; return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){

			$breakClass = 0;
			$blockNumber = 0;

			for ($i = 0; $i < $num; $i++) {

				$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

				if ($breakClass != $data["class"]) $blockNumber = 0;

				// データをカラム名の配列にする
				$this->LeagueTeamArray[$data["class"]][$blockNumber] = array(
								"t_id"				   => $data["id"],
								"t_name"				 => $data["t_name"],
								"t_kana"				 => $data["t_kana"],
								"class"				  => $data["class"],
								"block_name"			 => $data["block_name"],
								"site"				   => $data["site"],
								"photo"				  => $data["photo"],
								"applies_pic_h"		  => $data["applies_pic_h"],
								"applies_pic_a"		  => $data["applies_pic_a"],
								"home_color"			 => $data["home_color"],
								"away_color"			 => $data["away_color"],
								"activity_district"	  => $data["activity_district"],
								"activity_place"		 => $data["activity_place"],
								"region"				 => $data["region"],
								"week"				   => $data["week"],
								"sub_reague"			 => $data["sub_reague"],
								"t_comment"			  => $data["t_comment"],
								"represent"			  => $data["represent"],
								"represent_tel"		  => $data["represent_tel"],
								"represent_address"	  => $data["represent_address"],
								"represent_mobile_address" => $data["represent_mobile_address"],
								"represent_mobile_domain"  => $data["domain"],
								"sub_represent"		  => $data["sub_represent"],
								"sub_represent_tel"	  => $data["sub_represent_tel"],
								"sub_represent_address"  => $data["sub_represent_address"],
								"umpire_1"			   => $data["umpire_1"],
								"umpire_2"			   => $data["umpire_2"],
								"umpire_3"			   => $data["umpire_3"]
							  );

				$blockNumber++;

				$breakClass = $data["class"];

			}
			$retData = True;
		}
//print nl2br(print_r($this->LeagueTeam,ture));
		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}

	// リーグ登録、全チームデータ
	/**
	 * 概要
	 * 説明
	 *
	 * @param string	$arg   説明
	 * @return string		  説明
	 */
	 public function selectAllTeamData() {

		$retData = false;

		// チームデータの取得
		$sql = "SELECT
						`id`,
						`t_name`,
						`t_kana`,
						`abb_name`,
						`class`,
						`site`,
						`photo`,
						`home_color`,
						`away_color`,
						`activity_district`,
						`activity_place`,
						`region`,
						`week`,
						`sub_reague`,
						`t_comment`,
						`represent`,
						`represent_tel`,
						`represent_address`,
						`sub_represent`,
						`sub_represent_tel`,
						`sub_represent_address`,
						`umpire_1`,
						`umpire_2`,
						`umpire_3`
					   FROM `team_informations` " .
//					 LEFT JOIN `regist_teams` LRT
//						   ON  LTI.`id` = LRT.`t_id`
//					 LEFT JOIN `master_class_informations` MCI
//						   ON  LRT.`class` = MCI.`id` " .
//					 WHERE LRT.`r_id` = '" . $rarryId . "'
//					   AND LTI.`login_id` != ''
//					 " . $sqlWhere . "
					 "   ORDER BY id ";
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[teamData]リーグ登録全チームデータ取得エラーです。<br />".$sql."<br />"; return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){

			for ($i = 0; $i < $num; $i++) {

				$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

				// データをカラム名の配列にする
				$this->selectAllTeamArray[$i] = array(
								"t_id"				   => $data["id"],
								"t_name"				 => $data["t_name"],
								"t_kana"				 => $data["t_kana"],
								"class"				  => $data["class"],
//								"block_name"			 => $data["block_name"],
								"site"				   => $data["site"],
								"photo"				  => $data["photo"],
								"home_color"			 => $data["home_color"],
								"away_color"			 => $data["away_color"],
								"activity_district"	  => $data["activity_district"],
								"activity_place"		 => $data["activity_place"],
								"region"				 => $data["region"],
								"week"				   => $data["week"],
								"sub_reague"			 => $data["sub_reague"],
								"t_comment"			  => $data["t_comment"],
								"represent"			  => $data["represent"],
								"represent_tel"		  => $data["represent_tel"],
								"represent_address"	  => $data["represent_address"],
								"sub_represent"		  => $data["sub_represent"],
								"sub_represent_tel"	  => $data["sub_represent_tel"],
								"sub_represent_address"  => $data["sub_represent_address"],
								"umpire_1"			   => $data["umpire_1"],
								"umpire_2"			   => $data["umpire_2"],
								"umpire_3"			   => $data["umpire_3"]
							  );

//				$blockNumber++;

				$breakClass = $data["class"];

			}
			$retData = True;
		}
//print nl2br(print_r($this->LeagueTeam,ture));
		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}

	// 活動地区データ
	/**
	 * 概要
	 *
	 * 説明
	 *
	 * @param string	$arg   説明
	 *
	 * @return string		  説明
	 * @return void
	 */
	public function selectDistrictData($recId) {

		$retData = False;

		// チームIDがないときは終了
		if (!isset($recId)) {
			return  $retData;
		}

		# チームデータの取得
		$sql = "SELECT " .
			   " `district_name`  " .
			   " FROM ".dbTableName::M_DISTRICT_INFO." " .
			   " WHERE id = " . $recId . "";
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[dats/teamDataClass::selectDistrictData]チームデータ2の取得エラーです。<br />".$sql."<br />"; $this->DbErrorValue = $this->GetLastError(); return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){

			$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

			$this->districtDatas = $data["district_name"];

			$retData = True;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}

	/**
	 * 概要 : 全活動地区データ
	 *
	 * 説明
	 *
	 * @param string	$arg   説明
	 *
	 * @return string		  説明
	 * @return void
	 */
	public function allDistrictData() {

		$retData = False;

		# チームデータの取得
		$sql = "SELECT " .
			   " `id`  " .
			   " ,`district_name`  " .
			   " FROM ".dbTableName::M_DISTRICT_INFO." " ;
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[daas/teamDataClass::allDistrictData]全活動地区データの取得エラーです。<br />".$sql."<br />"; $this->DbErrorValue = $this->GetLastError(); return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){

			for ($i = 0; $i < $num; $i++) {

				$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

				$this->districtAllDatas[$i]['d_id'] = $data["id"];
				$this->districtAllDatas[$i]['district_name'] = $data["district_name"];

			}

			$retData = True;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}

	/**
	 * 概要 : 携帯ドメインデータ
	 *
	 * 説明
	 *
	 * @param string	$arg   説明
	 *
	 * @return string		  説明
	 * @return void
	 */
	public function mobileDomainCommonData() {

		$retData = False;

		# 携帯ドメインデータの取得
		$sql = "SELECT " .
			   " MMD.`id`  " .
			   " , MMC.`name`  " .
			   " , MMC.`kana`  " .
			   " , MMD.`careers_id`  " .
			   " , MMD.`domain`  " .
			   " FROM ".dbTableName::M_MOBILE_DOMAIN." MMD " .
			   " LEFT JOIN ".dbTableName::M_MOBILE_CAREERS." MMC
						   ON  MMD.`careers_id` = MMC.`id` " ;
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[daas/teamDataClass::mobileDomainCommonData]携帯ドメインデータの取得エラーです。<br />".$sql."<br />"; $this->DbErrorValue = $this->GetLastError(); return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){

			for ($i = 0; $i < $num; $i++) {

				$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

				$this->commonMobileDatas[$i]['id'] = $data["id"];
				$this->commonMobileDatas[$i]['name'] = $data["name"];
				$this->commonMobileDatas[$i]['kana'] = $data["kana"];
				$this->commonMobileDatas[$i]['careers_id'] = $data["careers_id"];
				$this->commonMobileDatas[$i]['domain'] = $data["domain"];

			}

			$retData = True;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}

	// 選択した携帯ドメイン情報の取得
	/**
	 * 概要
	 *
	 * 説明
	 *
	 * @param string	$arg   説明
	 *
	 * @return string		  説明
	 * @return void
	 */
	public function selectMobileDomainData($mobileId) {

		$retData = false;

		// 携帯IDがないときは終了
		if (!isset($mobileId)) {
			return  $retData;
		}

		# チームデータの取得
		$sql = "SELECT " .
			   " MMC.`name`  " .
			   " , MMC.`kana`  " .
			   " , MMD.`domain`  " .
			   " FROM ".dbTableName::M_MOBILE_DOMAIN." MMD " .
			   " LEFT JOIN ".dbTableName::M_MOBILE_CAREERS." MMC
						   ON  MMD.`careers_id` = MMC.`id` " .
			   " WHERE MMD.`id` = " . $mobileId . "";
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[dats/teamDataClass::selectMobileDomainData]選択した携帯ドメイン情報の取得エラーです。<br />".$sql."<br />"; $this->DbErrorValue = $this->GetLastError(); return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){

			$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

			$this->selectMobileData["name"] = $data["name"];
			$this->selectMobileData["kana"] = $data["kana"];
			$this->selectMobileData["domain"] = $data["domain"];

			$retData = true;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}

	// チーム写真の取得
	/**
	 * 概要
	 *
	 * 説明
	 *
	 * @param string	$arg   説明
	 *
	 * @return string		  説明
	 * @return void
	 */
	public function selectTeamPicture($rarryId, $teamId, $pictureMode) {

		$retData = false;

		// 携帯IDがないときは終了
		if (!isset($rarryId) OR !isset($teamId)) {
			return  $retData;
		}

		// 取得するカラム
		switch ($pictureMode) {
			case 'home' : $getFields = 'picture_home'; break;
			case 'away' : $getFields = 'picture_away'; break;
			case 'other1' : $getFields = 'picture_other1'; break;
			case 'other2' : $getFields = 'picture_other2'; break;
			case 'other3' : $getFields = 'picture_other3'; break;
			case 'thumb_home' : $getFields = 'thumbnail_home'; break;
			case 'thumb_away' : $getFields = 'thumbnail_away'; break;
			case 'thumb_other1' : $getFields = 'thumbnail_other1'; break;
			case 'thumb_other2' : $getFields = 'thumbnail_other2'; break;
			case 'thumb_other3' : $getFields = 'thumbnail_other3'; break;
			default : $getFields = 'picture_home';
		}

		# チームデータの取得
		$sql = "SELECT " .
			   " " . $getFields . " " .
			   " FROM ".dbTableName::LT_REGIST_TEAM." " .
			   " WHERE `r_id` = " . $rarryId . " " .
			   "  AND `t_id` = " . $teamId . " " .
			   "  AND octet_length(`" . $getFields . "`) > 0 ";
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[datas/teamDataClass::selectMobileDomainData]選択した携帯ドメイン情報の取得エラーです。<br />".$sql."<br />"; $this->DbErrorValue = $this->GetLastError(); return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){
//			$retData = true;
			$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

			// 画像表示
//			header("content-type:image/jpeg");
			$this->selectTeamPicture = $data[$getFields];
			$retData = true;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}

	/**
	 * 概要 : 過去に出場した最後の大会データの取得
	 *
	 * 説明
	 *
	 * @param $rarryId	大会ID
	 * @param $teamId	 チームID
	 */
	public function teamRegistThePastRarryData($rarryId, $teamId) {

		$retData = false;

		$sql = "SELECT " .
					 " LRT.`r_id` , " .
					 " LRI.`progress` " .
			   " FROM ".dbTableName::LT_REGIST_TEAM." LRT " .
			   " LEFT JOIN ".dbTableName::LT_RARRY_INFO." LRI " .
			   "		ON LRT.`r_id` = LRI.`id` " .
			   " WHERE LRT.`t_id` = " . $teamId . " " .
			   " AND LRT.`r_id` != " . $rarryId . " " .
			   " AND LRI.`parent_id` = 0 " .
			   " ORDER BY LRI.`define` DESC, LRI.`id` DESC " .
			   " LIMIT 1 ";

//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print "[datas/teamDataClass::teamRegistThePastRarryData]去に出場した最後の大会データの取得エラーです。<br />".$sql."<br />"; return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		if($num > 0){

			$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

			$this->teamRegistThePastRarryDataArray["r_id"] = $data["r_id"];
			$this->teamRegistThePastRarryDataArray["progress"] = $data["progress"];

			$retData = true;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $retData;
	}


	//------------------------------------------------------
	// チームデータ
	//------------------------------------------------------
	/*
	 * DBエラー内容を返す
	 */
	function getDbErrorValue() {
		return $this->DbErrorValue;
	}
	/*
	 * エラー内容を返す
	 */
	function getErrorMessageValue() {
		return $this->ErrorMessageValue;
	}
	/*
	 * 指定チーム情報を返す
	 */
	function getTeamDatas() {
		return $this->teamDatas;
	}
	/*
	 * 指定チーム情報を返す
	 */
	function getSelectTeamDatas() {
		return $this->selectTeamDatas;
	}
	/*
	 * 大会別登録チーム情報を返す
	 */
	function getLeagueTeam() {
		return $this->LeagueTeamArray;
	}
	/*
	 * 全チーム情報を返す
	 */
	function getSelectAllTeamDatas() {
		return $this->selectAllTeamArray;
	}
	/*
	 * 活動場所を返す
	 */
	function getDistrictDatas() {
		return $this->districtDatas;
	}
	/*
	 * 全活動場所を返す
	 */
	function getDistrictAllDatas() {
		return $this->districtAllDatas;
	}
	/*
	 * 携帯情報を返す
	 */
	function getCommonMobileDatas() {
		return $this->commonMobileDatas;
	}
	/*
	 * 携帯情報を返す
	 */
	function getSelectMobileData() {
		return $this->selectMobileData;
	}
	/*
	 * チーム画像を返す
	 */
	function getSelectTeamPicture() {
		return $this->selectTeamPicture;
	}
	/*
	 * 過去に出場した最後の大会データを返す
	 */
	function getTeamRegistThePastRarryData() {
		return $this->teamRegistThePastRarryDataArray;
	}



	/*
	 * チーム名を返す
	 */
	function getTeamName() {
		return $this->teamName;
	}

	/*
	 * チーム名(カナ)を返す
	 */
	function getTeamKana() {
		return $this->teamKana;
	}

	/*
	 * チームクラスを返す
	 */
	function getTeamClass() {
		return $this->teamClass;
	}

	/*
	 * チームWEBサイトアドレスを返す
	 */
	function getTeamSite() {
		return $this->teamSite;
	}

	/*
	 * チーム写真ファイル名を返す
	 */
	function getTeamPhoto() {
		return $this->teamPhoto;
	}

	/*
	 * チームホームカラーを返す
	 */
	function getTeamHomeColor() {
		return $this->teamHomeColor;
	}

	/*
	 * チームアウェイカラーを返す
	 */
	function getTeamAwayColor() {
		return $this->teamAwayColor;
	}

	/*
	 * チーム活動場所を返す
	 */
	function getTeamRegion() {
		return $this->teamRegion;
	}

	/*
	 * チーム活動日を返す
	 */
	function getTeamWeek() {
		return $this->teamWeek;
	}

	/*
	 * チームリーガ外大会を返す
	 */
	function getTeamSubLeague() {
		return $this->teamSubLeague;
	}

	/*
	 * チームコメントを返す
	 */
	function getTeamComment() {
		return $this->teamComment;
	}

	/*
	 * チームを返す
	 */
	function getTeamRepresent1() {
		return $this->teamRepresent1;
	}

	/*
	 * チームを返す
	 */
	function getTeamRepresent2() {
		return $this->teamRepresent2;
	}

	/*
	 * チーム審判１を返す
	 */
	function getTeamUmpire1() {
		return $this->teamUmpire1;
	}

	/*
	 * チーム審判２を返す
	 */
	function getTeamUmpire2() {
		return $this->teamUmpire2;
	}

	/*
	 * チーム審判３を返す
	 */
	function getTeamUmpire3() {
		return $this->teamUmpire3;
	}


}

?>
