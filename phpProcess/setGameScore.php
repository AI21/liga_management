<?php
	// 試合結果登録・更新用ファイル

    require_once ('../admin/common.inc');

	//-------------------------
	//POST値の受け取り
	//-------------------------
	
	// XSS対策
	$gameId 				= encode($_POST['game_id']);
	$gameReport 			= encode(urldecode($_POST['gameReport']));
	$bestHigherHome 			= encode(urldecode($_POST['bestHigherHome']));
	$bestHigherAway 			= encode(urldecode($_POST['bestHigherAway']));
//	$gameReportPicture1		= encode($_POST["gameReportPicture1"]);
//	$gameReportPicture2		= encode($_POST["gameReportPicture2"]);
//	$gameReportPicture3		= encode($_POST["gameReportPicture3"]);
	$this->connectObj = $connectionDBObj;

	echo json_encode(doSendGameReport($gameId, $bestHigherHome, $bestHigherAway, $gameReport));
	exit();

	function doSendGameReport($gameId, $bestHigherHome, $bestHigherAway, $gameReport){

		$ret = array(
			"result" => false,
			"message" => '',
		);
		
		// ゲームレポートをURLエンコード処理
		//$gameReport = urldecode($gameReport);
		//$bestHigherHome = urldecode($bestHigherHome);
		//$bestHigherAway = urldecode($bestHigherAway);
		
		if ($this->checkGameReport($gameId) == true) {
			$sql = "UPDATE ".dbTableName::RARRY_SCORE_REPORT." SET" .
				" `report` = '".$gameReport."', " .
				" `home_best` = '".$bestHigherHome."', " .
				" `away_best` = '".$bestHigherAway."', " .
				" `modified` = NOW() " .
				" WHERE " .
				" `game_id` = '".$gameId."'";
		} else {
			
			$sql = "INSERT INTO ".dbTableName::RARRY_SCORE_REPORT."  (" .
				" `game_id`, " .
				" `report`, " .
				" `home_best`, " .
				" `away_best`, " .
				" `created` " .
				" ) VALUES (";
					" '".$gameId."', " .
					" '".$gameReport."', " .
					" '".$bestHigherHome."', " .
					" '".$bestHigherAway."', " .
					" NOW(), " .
				")" .
		}
		// データ登録
		$ins_rs  = $this->connectObj->Query($sql);
		if ( !$ins_rs ) {
			$ret["message"] = "ゲームレポートの登録に失敗しました。<br />";
		} else {
			$ret["message"] = "ゲームレポートを登録しました。<br />";
			$ret["result"] = true;
		}
		
		return $ret;
	}
	
	// ゲームレポートのデータがあるか
	private function checkGameReport($connectObj, $gameId) {
	
        $sql = "SELECT `id`
                     FROM ".dbTableName::RARRY_SCORE_REPORT." 
                     WHERE `game_id` = '" . $gameId;
//print $sql."<BR>";
        $rs  = $this->connectObj->Query($sql);
        if(!$rs){ return false; }

        // データ数を取得
        $num        = $this->connectObj->GetRowCount($rs);

        if($num > 0){
        	return true;
        }
        return false;
	}

?>