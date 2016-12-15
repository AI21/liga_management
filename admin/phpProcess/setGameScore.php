<?php
	// 試合結果登録・更新用ファイル

    require_once ('../common.inc');

	//-------------------------
	//POST値の受け取り
	//-------------------------
	// XSS対策
	$gameId 				= encode($_POST['game_id']);
	//$gameReport 			= encode(rawurlencode($_POST['gameReport']));
	//$bestHigherHome 			= encode(rawurlencode($_POST['bestHigherHome']));
	//$bestHigherAway 			= encode(rawurlencode($_POST['bestHigherAway']));
	$gameReport 			= encode($_POST['gameReport']);
	$bestHigherHome 			= encode($_POST['bestHigherHome']);
	$bestHigherAway 			= encode($_POST['bestHigherAway']);
//	$gameReportPicture1		= encode($_POST["gameReportPicture1"]);
//	$gameReportPicture2		= encode($_POST["gameReportPicture2"]);
//	$gameReportPicture3		= encode($_POST["gameReportPicture3"]);
//	$this->connectObj = $connectionDBObj;

//print $gameReport;
//print $bestHigherHome;
//print $bestHigherAway;


	echo json_encode(doSendGameReport($connectionDBObj, $gameId, $bestHigherHome, $bestHigherAway, $gameReport));
	exit();

	function doSendGameReport($connectObj, $gameId, $bestHigherHome, $bestHigherAway, $gameReport){

		$ret = array(
			"result" => false,
			"message" => '',
		);
		
		// ゲームレポートをURLエンコード処理
		//$gameReport = urldecode($gameReport);
		//$bestHigherHome = urldecode($bestHigherHome);
		//$bestHigherAway = urldecode($bestHigherAway);
		
		if (checkGameReport($connectObj, $gameId) == true) {
			$sql = "UPDATE ".dbTableName::RARRY_SCORE_REPORT." SET" .
				" `report` = '".$gameReport."', " .
				" `home_best` = '".$bestHigherHome."', " .
				" `away_best` = '".$bestHigherAway."', " .
				" `modified` = NOW() " .
				" WHERE " .
				" `game_id` = ".$gameId;
		} else {
			
			$sql = "INSERT INTO ".dbTableName::RARRY_SCORE_REPORT."  (" .
				" `game_id`, " .
				" `report`, " .
				" `home_best`, " .
				" `away_best`, " .
				" `created` " .
				" ) VALUES (" .
					" '".$gameId."', " .
					" '".$gameReport."', " .
					" '".$bestHigherHome."', " .
					" '".$bestHigherAway."', " .
					" NOW() " .
				")";
		}
//print $sql;
		// データ登録
		$rs  = $connectObj->Query($sql);
		if ( !$rs ) {
			$ret["message"] = "ゲームレポートの登録に失敗しました。";
		} else {
			$ret["message"] = "ゲームレポートを登録しました。";
			$ret["result"] = true;
		}
		return $ret;
	}
	
	// ゲームレポートのデータがあるか
	function checkGameReport($connectObj, $gameId) {
	
        $sql = "SELECT `id`
                     FROM ".dbTableName::RARRY_SCORE_REPORT." 
                     WHERE `game_id` = " . $gameId;
//print $sql."<BR>";
        $rs  = $connectObj->Query($sql);
        if(!$rs){ return false; }

        // データ数を取得
        $num        = $connectObj->GetRowCount($rs);

        if($num > 0){
			return true;
        }
        return false;
	}
?>