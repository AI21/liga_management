<?php

// 設定ファイル読み込み
//require_once "connectionDB.inc";

class memberDataClass extends connectionDBClass {

	/**
	 * コンストラクター.
	 */
	public function __construct() {
	}

	// チーム登録メンバーデータ
	public function rarrySeasonMemberList($rarryId, $season, $teamId = 0) {
	//public function rarrySeasonMemberList($nextRarryId, $nextSeason, $preRarryId, $preSeason, $teamId = 0)

		$retData = False;
		$this->memberDataList = array();

		// チームIDがないときは終了
		if (!isset($rarryId) AND !isset($season) AND !isset($teamId)) {
			return  $retData;
		}

		# チーム登録メンバーデータの取得
		$sql = "SELECT " .
					" LMI.`id` AS m_id, " .
					" CASE when (LRTM.`number` IS NULL or LRTM.`number` = '') then NULL ELSE LRTM.`number` END number, " .
					" CASE when LMI.`name_first`   IS NULL then '未登録' ELSE LMI.`name_first` END name_first, " .
					" LMI.`name_second` AS name_second, " .
					" CASE when LMI.`kana_first`   IS NULL then '未登録' ELSE LMI.`kana_first` END kana_first, " .
					" LMI.`kana_second` AS kana_second, " .
					//" CASE when LMI.`age`	  IS NULL then '未登録' ELSE LMI.`age` END age, " .
					" CASE when LMI.`height`   IS NULL then '未登録' ELSE LMI.`height` END height, " .
					//" CASE when LMI.`weight`   IS NULL then '未登録' ELSE LMI.`weight` END weight, " .
					" CASE when LMI.`birthday` IS NULL then '未登録' ELSE LMI.`birthday` END birthday, " .
					" CASE when LMI.`birthday` IS NULL then '--'	 ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages, " .
					" CASE when MPI.`posision_jpn` IS NULL then '未登録' ELSE MPI.`posision_jpn` END posision, " .
					" LRTM.`captain_flg` AS captain_flg, " .
					" CASE when LMI.`m_comment` = ''   then '未回答' ELSE LMI.`m_comment` END comment, " .
					" LMP.`payment` AS registPayment, " .
					" LRTM.`created` AS created, " .
					" LRTM.`modified` AS discharge_date " .
					" FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
					" LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI " .
					"		ON LRTM.`m_id` = LMI.`id`" .
					" LEFT JOIN ".dbTableName::LT_MEMBER_PAYMENT." LMP " .
					"		ON LRTM.`m_id` = LMP.`m_id` " .
					"	   AND LMP.`r_id` = " . $rarryId . " " .
					" LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI " .
					"  ON LRTM.`p_id` = MPI.`id`" .
					" WHERE LRTM.r_id = " . $rarryId . "" .
					"  AND  LRTM.season = " . $season . "" .
					"  AND  LRTM.t_id = " . $teamId . "" .
					" ORDER BY LPAD(LRTM.`number`, 2, 0), LRTM.`number` DESC";
/*
		$sql = "SELECT " .
					   " (a.m_id), r_id, number, LMT.name_first FROM (
					   select r_id, m_id, number FROM lt_regist_team_member WHERE r_id = '2' AND define = 1 AND t_id = '47'
					   UNION ALL
					   select r_id, m_id, number FROM lt_regist_team_member WHERE r_id = '1' AND define = 3 AND t_id = '47'
					   ) as a INNER JOIN lt_member_info LMT ON a.m_id = LMT.m_id";
		$sql = "SELECT LRTM.r_id, LRTM.m_id, LRTM.number
			   CASE when LMI.`name_first`   IS NULL then '未登録' ELSE LMI.`name_first` END name_first,
			   LMI.`name_second` AS name_second,
			   CASE when LMI.`kana_first`   IS NULL then '未登録' ELSE LMI.`kana_first` END kana_first,
			   LMI.`kana_second` AS kana_second,
			   CASE when LMI.`age`	  IS NULL then '未登録' ELSE LMI.`age` END age,
			   CASE when LMI.`height`   IS NULL then '未登録' ELSE LMI.`height` END height,
			   CASE when LMI.`weight`   IS NULL then '未登録' ELSE LMI.`weight` END weight,
			   CASE when LMI.`birthday` IS NULL then '未登録' ELSE LMI.`birthday` END birthday,
			   CASE when LMI.`birthday` IS NULL then '--'	 ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages,
			   MPI.`posision_abb` AS posision,
			   LRTM.`captain_flg` AS captain_flg
			   FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM
			   LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI ON LRTM.m_id = LMI.m_id
			   LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI ON LRTM.`posision` = MPI.`id`
			   WHERE LRTM.r_id = " . $nextRarryId . "
			   AND LRTM.define = " . $nextSeason . "
			   AND LRTM.t_id = " . $teamId . "
			   UNION
			   SELECT LRTM.r_id, LRTM.m_id, LRTM.number
			   CASE when LMI.`name_first`   IS NULL then '未登録' ELSE LMI.`name_first` END name_first,
			   LMI.`name_second` AS name_second,
			   CASE when LMI.`kana_first`   IS NULL then '未登録' ELSE LMI.`kana_first` END kana_first,
			   LMI.`kana_second` AS kana_second,
			   CASE when LMI.`age`	  IS NULL then '未登録' ELSE LMI.`age` END age,
			   CASE when LMI.`height`   IS NULL then '未登録' ELSE LMI.`height` END height,
			   CASE when LMI.`weight`   IS NULL then '未登録' ELSE LMI.`weight` END weight,
			   CASE when LMI.`birthday` IS NULL then '未登録' ELSE LMI.`birthday` END birthday,
			   CASE when LMI.`birthday` IS NULL then '--'	 ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages,
			   MPI.`posision_abb` AS posision,
			   FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM
			   LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI ON LRTM.m_id = LMI.m_id
			   LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI ON LRTM.`posision` = MPI.`id`
			   WHERE LRTM.r_id = " . $preRarryId . "
			   AND LRTM.define = " . $preSeason . "
			   AND LRTM.t_id = " . $teamId . " ";
		$sql = "SELECT " .
					   " LMI.`m_id` AS m_id, " .
					   " LRTM.`number` AS number, " .
					   " CASE when LMI.`name_first`   IS NULL then '未登録' ELSE LMI.`name_first` END name_first, " .
					   " LMI.`name_second` AS name_second, " .
					   " CASE when LMI.`kana_first`   IS NULL then '未登録' ELSE LMI.`kana_first` END kana_first, " .
					   " LMI.`kana_second` AS kana_second, " .
					   " CASE when LMI.`age`	  IS NULL then '未登録' ELSE LMI.`age` END age, " .
					   " CASE when LMI.`height`   IS NULL then '未登録' ELSE LMI.`height` END height, " .
					   " CASE when LMI.`weight`   IS NULL then '未登録' ELSE LMI.`weight` END weight, " .
					   " CASE when LMI.`birthday` IS NULL then '未登録' ELSE LMI.`birthday` END birthday, " .
					   " CASE when LMI.`birthday` IS NULL then '--'	 ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages, " .
					   " MPI.`posision_abb` AS posision, " .
					   " LRTM.`captain_flg` AS captain_flg, " .
					   " CASE when LMI.`m_comment` = ''   then '未回答' ELSE LMI.`m_comment` END comment " .
					" FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
					" LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI " .
					"		ON LRTM.`m_id` = LMI.`m_id`" .
					" LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI " .
					"  ON LRTM.`posision` = MPI.`id`" .
					" WHERE LRTM.r_id = " . $nextRarryId . "" .
					"  AND  LRTM.define = " . $nextSeason . "" .
					"  AND  LRTM.t_id = " . $teamId . "" .
					//" ORDER BY LPAD(LRTM.`number`, 2, 0)" .
			   "UNION " .
			   "SELECT " .
					   " LMI.`m_id` AS m_id, " .
					   " LRTM.`number` AS number, " .
					   " CASE when LMI.`name_first`   IS NULL then '未登録' ELSE LMI.`name_first` END name_first, " .
					   " LMI.`name_second` AS name_second, " .
					   " CASE when LMI.`kana_first`   IS NULL then '未登録' ELSE LMI.`kana_first` END kana_first, " .
					   " LMI.`kana_second` AS kana_second, " .
					   " CASE when LMI.`age`	  IS NULL then '未登録' ELSE LMI.`age` END age, " .
					   " CASE when LMI.`height`   IS NULL then '未登録' ELSE LMI.`height` END height, " .
					   " CASE when LMI.`weight`   IS NULL then '未登録' ELSE LMI.`weight` END weight, " .
					   " CASE when LMI.`birthday` IS NULL then '未登録' ELSE LMI.`birthday` END birthday, " .
					   " CASE when LMI.`birthday` IS NULL then '--'	 ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages, " .
					   " MPI.`posision_abb` AS posision, " .
					   " LRTM.`captain_flg` AS captain_flg, " .
					   " CASE when LMI.`m_comment` = ''   then '未回答' ELSE LMI.`m_comment` END comment " .
					" FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
					" LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI " .
					"		ON LRTM.`m_id` = LMI.`m_id`" .
					" LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI " .
					"  ON LRTM.`posision` = MPI.`id`" .
					" WHERE LRTM.r_id = " . $preRarryId . "" .
					"  AND  LRTM.define = " . $preSeason . "" .
					"  AND  LRTM.t_id = " . $teamId . "" ;
					//" ORDER BY LPAD(LRTM.`number`, 2, 0)";
*/
//print $sql."<BR>";
		//$rs  = PostSQL(mb_convert_encoding($sql, "UTF-8"));
		$rs  = $this->Query($sql);
		if(!$rs){ print $sql."<br />[datas/memberDataClass::rarrySeasonMemberList]チーム登録メンバーデータの取得エラーです。<br />".$sql."<br />"; return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);
//print $teamId." = ".$num."<BR>";

		if($num > 0){

			for ($i = 0; $i < $num; $i++) {

				$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

				// メンバーデータ(配列名はCSSに合わせる事)
				$this->memberDataList[$i]["nemberId"] = $data["m_id"];
				$this->memberDataList[$i]["number"] = $data["number"];
				$this->memberDataList[$i]["nameFirst"] = $data["name_first"];
				$this->memberDataList[$i]["nameSecond"] = $data["name_second"];
				$this->memberDataList[$i]["kanaFirst"] = $data["kana_first"];
				$this->memberDataList[$i]["kanaSecond"] = $data["kana_second"];
				$this->memberDataList[$i]["posision"] = $data["posision"];
				$this->memberDataList[$i]["birthday"] = $data["birthday"];
				$this->memberDataList[$i]["age"] = $data["ages"];
				$this->memberDataList[$i]["height"] = $data["height"];
				$this->memberDataList[$i]["registPayment"] = $data["registPayment"];
				$this->memberDataList[$i]["created"] = $data["created"];
				$this->memberDataList[$i]["discharge_date"] = $data["discharge_date"];
				//$this->memberData[$i]["comment"] = $data["comment"];
				//$this->ret[$i]["weight"] = $data["weight"];
#print $this->ret[$i]["posision"]." = xxx<br>";
#print $data["name_first"]." = comment<br>";
			}
			$this->memberCount = $num;
//print $teamId." = ".$this->memberCount."<BR>";
			$retData = True;
		} else {
			$this->memberCount = 0;
/*
				// メンバーデータ(配列名はCSSに合わせる事)
				$this->memberData[0]["no"] = "";
				$this->memberData[0]["name"] = "";
				$this->memberData[0]["posision"] = "";
				$this->memberData[0]["birthday"] = "";
				$this->memberData[0]["age"] = "";
				$this->memberData[0]["height"] = "";
				$this->memberData[0]["discharge_date"] = "";
				//$this->memberData[0]["comment"] = "";
				//$this->memberData[0]["kana"] = "";
				//$this->memberData[0]["weight"] = "";
*/
		}

		// メモリ解放
		$this->FreeQuery($rs);
//print $teamId." = ".$this->memberCount."<BR>";

		return $retData;
	}


	/**
	 * 概要 : チーム登録メンバーデータを返す
	 *
	 * 説明 : 個人の対戦スコアが登録されているかのチェック
	 *
	 * @param $rarryId	大会ID
	 * @param $teamId	 チームID
	 * @param $gameId	 ゲームID
	 */
	public function teamRegistMemberList($rarryId, $teamId, $season) {

		$ret = False;

		$sql = "SELECT " .
					   " LISC.`m_id` AS DataCount " .
					   " FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
						  " LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LRTM " .
					   "  ON   LISC.`m_id` = LRTM.`m_id` " .
					   " WHERE LISC.`r_id` = " . $rarryId . " " .
					   "  AND  LISC.`game_id` = " . $gameId . " " .
					   "  AND  LRTM.`t_id` = " . $teamId . " " ;
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print $sql."<br />[datas/memberDataClass::teamRegistMemberList]チーム登録メンバーデータエラーです。<br />".$sql."<br />"; return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		// データが1件以上あればばTrueを返す
		if($num > 0){

			$ret = True;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $ret;
	}


	/**
	 * 概要 : 選手データを取得する
	 *
	 * 説明 : 指定IDの選手情報を取得する
	 *
	 * @param $memberId   選手ID
	 */
	public function selectMemberData($memberId) {

		$ret = false;
		$this->memberData = array();

		$sql = "SELECT " .
					   " `name_first` " .
					   " ,`name_second` " .
					   " ,`kana_first` " .
					   " ,`kana_second` " .
					   " FROM ".dbTableName::LT_MEMBER_INFO." " .
					   " WHERE `id` = " . $memberId . " " ;
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print $sql."<br />[datas/memberDataClass::selectMemberData]メンバーデータ取得エラーです。<br />".$sql."<br />"; return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		// データが1件以上あればばTrueを返す
		if($num > 0){

			$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

			// メンバーデータ(配列名はCSSに合わせる事)
			$this->memberData["name_first"]	= $data["name_first"];
			$this->memberData["name_second"]   = $data["name_second"];
			$this->memberData["kana_first"]	= $data["kana_first"];
			$this->memberData["kana_second"]   = $data["kana_second"];

			$ret = true;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $ret;
	}

	/**
	 * 概要 : 選手が最後に出場したチームデータを取得する
	 *
	 * 説明 : 指定IDの選手情報を取得する
	 *
	 * @param $memberId   選手ID
	 */
	public function selectMemberLastRarryData($memberId, $nextRarryId, $preRarryId=0, $preRarrySeason=0) {

		$ret = false;
		$this->memberData = array();
		$sqlPreRallySelect = "";

		if ($preRarryId > 0) {
			$sqlPreRallySelect = " AND LRT.`r_id` = " . $preRarryId;
		}
		if ($preRarrySeason > 0) {
			$sqlPreRallySelect .= " AND LRTM.`season` = " . $preRarrySeason;
		}

		$sql = "SELECT " .
						" LRTM.`number` " .
						" ,LRT.`team_name` " .
						" ,LRTM.`modified` " .
						" FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
						" LEFT JOIN ".dbTableName::LT_REGIST_TEAM." LRT " .
						"		ON LRTM.`t_id` = LRT.`t_id` " .
						"	   AND LRTM.`r_id` = LRT.`r_id` " .
//						"	   AND LRT.`r_id` != " . $nextRarryId . " " .
						" LEFT JOIN ".dbTableName::LT_RARRY_INFO." LRI " .
						"		ON LRTM.`r_id` = LRI.`id` " .
						"	   AND LRI.`parent_id` = 0 " .
						" WHERE LRTM.`m_id` = " . $memberId .
						$sqlPreRallySelect .
						//" AND LRTM.`r_id` NOT IN (6,8) " .
						//" ORDER BY LRT.`r_id` DESC, LRTM.`define` DESC, LRI.`define` DESC " .
						" LIMIT 1 " ;
//print $sql."<BR>";
		$rs  = $this->Query($sql);
		if(!$rs){ print $sql."<br />[datas/memberDataClass::selectMemberLastRarryData]選手が最後に出場したチームデータ取得エラーです。<br />".$sql."<br />"; return false; }

		// データ数を取得
		$num		= $this->GetRowCount($rs);

		// データが1件以上あればばTrueを返す
		if($num > 0){

			$data   = $this->FetchRow($rs);	   // １行Ｇｅｔ

			// メンバーデータ(配列名はCSSに合わせる事)
			$this->memberLastRarryData["number"]	= $data["number"];
			$this->memberLastRarryData["team_name"]   = $data["team_name"];
			$this->memberLastRarryData["modified"]   = $data["modified"];

			$ret = true;
		}

		// メモリ解放
		$this->FreeQuery($rs);

		return $ret;
	}


	//------------------------------------------------------
	// 個人データ
	//------------------------------------------------------
	/*
	 * チーム登録人数を返す
	 */
	function getPeopleCount() {
		return $this->memberCount;
	}

	/*
	 * 選手リストを返す
	 */
	function getMemberDataList() {
		return $this->memberDataList;
	}

	/*
	 * 選手情報を返す
	 */
	function getMemberData() {
		return $this->memberData;
	}

	/*
	 * 選手が最後に出場したチーム情報を返す
	 */
	function getMemberLastRarryData() {
		return $this->memberLastRarryData;
	}

	/*
	 * 名前を返す
	 */
	function getName($no) {
		return $this->ret[$no]["name"];
	}

	/*
	 * 読みカナを返す
	function getKana($no) {
		return $this->ret[$no]["kana"];
	}
	 */

	/*
	 * ポジションを返す
	 */
	function getPosision($no) {
		return $this->ret[$no]["posision"];
	}

	/*
	 * 年齢を返す
	function getAge($no) {
		return $this->ret[$no]["age"];
	}
	 */

	/*
	 * 年齢２(誕生日から計算)を返す
	 */
	function getAge($no) {
		return $this->ret[$no]["age"];
	}

	/*
	 * 身長を返す
	 */
	function getHeight($no) {
		return $this->ret[$no]["height"];
	}

	/*
	 * 体重を返す
	function getWeight($no) {
		return $this->ret[$no]["weight"];
	}
	 */

	/*
	 * コメントを返す
	 */
	function getComment($no) {
		return $this->ret[$no]["comment"];
	}

}

?>