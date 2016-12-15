<?php

    // ヘッダー読み込みCSSファイル設定
    $readCssFile = array('all', 'style');
    // ヘッダー読み込みjavascriptファイル設定
    $readJsFile = array();

    if ($_SERVER["SERVER_NAME"] == "localhost") {
        $_SERVER["SERVER_NAME"] = $_SERVER["SERVER_NAME"]."/management.liga-tokai.com";
    }
//print nl2br(print_r($_SERVER,true));

    // 登録完了セッションがあればリダイレクト
    if ($_SESSION["schejuleSet"]["ok"] != "") {
        header("Location: https://".$_SERVER["SERVER_NAME"]."/management.liga-tokai.com/admin/main_schejule.php");
    }

    require_once './common.inc';

//    require_once '../pc/shere/common/errorCheck.inc';   // エラーチェッククラス

    // エラーチェックオブジェクト
//    $errorCheckObj = new errorCheck();

    // パラメータの環境変数
    $strRequestMethod = $_SERVER["REQUEST_METHOD"];

    // クライアントからPOSTで受取ったデータを変数に落とす
    if ($strRequestMethod == "POST") {
        while(list ($key, $val) = each($_POST)) {
            $$key = $val;
//print $key ." = ". $val."<BR>";
//print nl2br(print_r($val,true));
        }
    }
// リファラーに登録ページのURLがなければTOPへ（仮処理）
    if (!preg_match ("/schejule/i", $_SERVER["HTTP_REFERER"])) {
//        header("Location: https://".$_SERVER["SERVER_NAME"]."/admin/");
        header("Location: https://".$_SERVER["SERVER_NAME"]."/management.liga-tokai.com/admin/");
    }

    // 初期値
    (int)$rarryId = $_SESSION["rarryId"];
    (int)$level = 0;
    (int)$distinctGame = 3; // 同日の1チームの試合数上限
    $fmError = True;
    //$errorValue = "";
    $sqlError = True;
    $scriptName = "main_schejule_set.php";
    $_SESSION["schejuleInsertData"]["error"] = array();
    (int)$setNum = 0;
    (int)$setOfNum = 0;
    (int)$insNumber = 0;
//$errorValue .= "<li>".$mode." MODE</li>";

    if (!isset($_SESSION["rarryId"])) {
//        header("Location: https://".$_SERVER["SERVER_NAME"]."/admin/main_schejule.php");
        header("Location: https://".$_SERVER["SERVER_NAME"]."/management.liga-tokai.com/admin/main_schejule.php");
    }

    // セッションクリア
    unset($_SESSION["schejuleInsertData"]);

    /*--------------------------------------------------------------------------
     * POSTデータチェック
     --------------------------------------------------------------------------*/
    if ($mode == "confirm") {
//print $hall." = hall<BR>";
//print print_r($home)." = HOUR<BR>";

        $_SESSION["schejuleInsertData"]["mainData"] = array(
                                      "mode"  => "fmError",
                                      "setYear"  => $setYear,
                                      "setMonth"  => $setMonth,
                                      "setDays"  => $setDays,
                                      "rarryId"  => $rarryId,
                                      "hall"  => $hall,
                                      "block"  => $block
                                      );
        // チェック用に指定日のスケジュール登録データの取得
        if ($schejuleDataObj->adminSchejuleDatesData($rarryId, $setYear, $setMonth, $setDays) == True) {
            $checkDatesSchejuleData = $schejuleDataObj->getSchejuleDatesData();
//print nl2br(print_r($checkDatesSchejuleData,true));
            foreach ($checkDatesSchejuleData AS $blocks => $blockData) {
                foreach ($blockData AS $datas) {
//print nl2br(print_r($datas,true));
                    //print $blocks.$datas["hall"]."<br />";
                    $checkCortDate[$datas["hall"]][] = ($datas["datetimes"]);
                }
            }
        }
//print nl2br(print_r($checkCortDate,true));

        for ($i=0; $i<10; $i++) {//hour$j" size="2" maxlength="4" />：<input type="text" name="minutes

            // エラーチェック用にデータをセッションへ代入
            $_SESSION["schejuleInsertData"]["hour".$i.""] = $hour[$i];
            $_SESSION["schejuleInsertData"]["minutes".$i.""] = $minutes[$i];
            $_SESSION["schejuleInsertData"]["homeTeam".$i.""] = $home[$i];
            $_SESSION["schejuleInsertData"]["awayTeam".$i.""] = $away[$i];
            $_SESSION["schejuleInsertData"]["cort".$i.""] = $cort[$i];
            $_SESSION["schejuleInsertData"]["officials".$i.""] = $officials[$i];
            $_SESSION["schejuleInsertData"]["noOfficial".$i.""] = $noOfficial[$i];
            $_SESSION["schejuleInsertData"]["officialA".$i.""] = $officialA[$i];
            $_SESSION["schejuleInsertData"]["officialB".$i.""] = $officialB[$i];

            // 試合会場データオブジェクトの取得
            if ($mHallDataObj->selectCoatHallData($cort[$i]) ==True) {
                $selectHallCortDatas = $mHallDataObj->getCoatHallData();
                $coatName[$setNum] = $selectHallCortDatas["cort_name"];
            }

            // HOMEもしくはAWAYの登録がある物だけ処理
            if (is_numeric($away[$i]) OR is_numeric($home[$i])) {
//print print_r($home)." = HOUR<BR>";
//print $hour[$i]." = HOUR<BR>";
//print $minutes[$i]." = minutes<BR>";
//print $home[$i]." = home<BR>";
//print $away[$i]." = away<BR>";
//print $fmError." = away<BR>";
//print $cort[$i]." = COAT<BR>";


                $dbCheckDate = $setYear."-".$setMonth."-".$setDays;

                //---------------------------------------------------------
                // エラー処理
                //---------------------------------------------------------
                if (is_numeric($home[$i])) {
                    // HOMEチーム名
                    if ($teamDataObj->selectTeamData($home[$i])) {
                        $homeTeamName[$setNum] = $teamDataObj->getTeamName();
                        //$$homeTeamName[$i]++;
                        $$homeTeamName++;
#print $$homeTeamName." HOME<BR>";
/*
                        $dbHomeDistinctNum = $errorCheckObj->checkDistinctTeamGames($rarryId, $home[$i], $dbCheckDate);

#$errorValue .= $dbHomeDistinctNum." NUM<BR>";
                        // 同チームが一定試合数以上あればエラー
                        if ($$homeTeamName[$i] >= $distinctGame) {
                            $_SESSION["schejuleInsertData"]["error"][11] .= "<li style=\"font-weight:bold;color:#E14F25;\">".$homeTeamName[$i]."&nbsp;チームの同日の試合数が".$distinctGame."を超えています。</li>\n";
                            $errorNum++;
                        }
*/
                    }
//$errorValue .= $$homeTeamName[$i]." OFFi<BR>";
                    // AWAYチーム未選択エラー
                    if (!is_numeric($away[$i])) {
                        $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;AWAYチームを選択してください。</li>\n";
                        $fmError = False;
                        $errorNum++;
                    }
                }
                if (is_numeric($away[$i])) {
                    // AWAYチーム名
                    if ($teamDataObj->selectTeamData($away[$i])) {
                        $awayTeamName[$setNum] = $teamDataObj->getTeamName();
                        $$awayTeamName[$i]++;
                        // 同チームが一定試合数以上あればエラー
                        if ($$awayTeamName[$i] >= $distinctGame) {
                            $_SESSION["schejuleInsertData"]["error"][11] .= "<li style=\"font-weight:bold;color:#E14F25;\">".$awayTeamName[$i]."&nbsp;チームの同日の試合数が".$distinctGame."を超えています。</li>\n";
                            $errorNum++;
                        }
                    }
                    // HOMEチーム未選択エラー
                    if (!is_numeric($home[$i])) {
                        $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;HOMEチームを選択してください。</li>\n";
                        $fmError = False;
                        $errorNum++;
                    }
                }

                // 両チーム共に選択しているとき
                if (is_numeric($away[$i]) AND is_numeric($home[$i])) {
                    // 同チーム対戦対戦はNG
                    if ($home[$i] == $away[$i]) {
                        $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;同チーム対戦は選択できません</li>\n";
                        $fmError = False;
                        $errorNum++;
                    }
                    // 対戦済み(スコア登録済み)試合はNG
                    if ($versusResultObj->checkResultScoreGames($rarryId, $home[$i], $away[$i]) == False) {
                        $registScore = $versusResultObj->getCheckResultScoreGameData();
                        $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;".$homeTeamName[$i]."&nbsp;と".$awayTeamName[$i]."は
                                                                         「第&nbsp;".$registScore['section']."&nbsp;節　".$registScore['month']."月".$registScore['day']."日」に対戦済みです</li>\n";
                        $fmError = False;
                        $errorNum++;
                    } else {
                        // スケジュール登録済み試合はNG
                        if ($versusResultObj->checkSchejuleGames($rarryId, $home[$i], $away[$i]) == False) {
                            $registSchejule = $versusResultObj->getCheckShejuleGameData();
                            $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;".$homeTeamName[$i]."&nbsp;と".$awayTeamName[$i]."は
                                                                             「第&nbsp;".$registSchejule['section']."&nbsp;節　".$registSchejule['month']."月".$registSchejule['day']."日&nbsp;".$registSchejule['times']."」でスケジュール登録済みです</li>\n";
                            $fmError = False;
                            $errorNum++;
                        }
                    }

                    if (isset($checkCortDate[$cort[$i]])){

                        (int)$checkSetDateTimes = $setYear.$setMonth.$setDays.$hour[$i].$minutes[$i]."00";

                        foreach ($checkCortDate[$cort[$i]] AS $checkTimesArray) {

//print $checkSetDateTimes." = ".$checkTimesArray."<BR>";
//print ($checkTimesArray - 10000)." = ".($checkTimesArray + 10000)."<BR>";
                            if ($checkSetDateTimes > ($checkTimesArray - 10000) AND $checkSetDateTimes < ($checkTimesArray + 10000)) {

                                // 試合会場データオブジェクトの取得
                                if ($mHallDataObj->selectCoatHallData($cort[$i]) ==True) {
                                    $errorCortData = $mHallDataObj->getCoatHallData();
                                }
                                $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;".$errorCortData["cort_name"]."は".substr($checkTimesArray,8,2).":".substr($checkTimesArray,10,2)."にスケジュール登録済みです。(登録済み時間の前後1時間は設定できません)</li>\n";
                                $fmError = False;
                                $errorNum++;
                            }
                        }
                    }




                }

                // 時間
                if (!preg_match("/^[0-9]+$/", $hour[$i]) OR !preg_match("/^[0-9]+$/", $minutes[$i])) {
                    $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;時間の値が数値以外です。</li>\n";
                    $fmError = False;
                    $errorNum++;
                }

                // 時間のチェック用
                (int)$checkTimes[] = mktime($hour[$i], $minutes[$i], 0, $setMonth, $setDays, $setYear);

/*
                //---------------------------------------------------------
                // 簡易エラー処理(警告のみ)
                //---------------------------------------------------------
                // オフィシャル別途指定ボタンにチェックがあるとき
                if (isset($noOfficial[$i])) {

                    $errorValue .= "ON<BR>";

                    //
                    if ($officialA[$i] > 0) {
#print $noOfficial[$i]." XXX<BR>";
                        // HOMEチーム名
                        if ($teamDataObj->selectTeamData($officialA[$i])) {
                            $officialTeamNameA[$i] = $teamDataObj->getTeamName();
                            $insertOfficialA[$setNum] = $officialA[$i];
                        } else {
                            $officialTeamNameA[$i] = "<div style=\"font-weight:bold;color:#F8BA3C;\">前半のｵﾌｨｼｬﾙが未選択ですがよろしいですか？</div>";
                        }
                    }
                    if ($officialB[$i] > 0) {
                        // HOMEチーム名
                        if ($teamDataObj->selectTeamData($officialB[$i])) {
                            $officialTeamNameB[$i] = $teamDataObj->getTeamName();
                            $insertOfficialB[$setNum] = $officialB[$i];
                        } else {
                            $officialTeamNameB[$i] .= "<div style=\"font-weight:bold;color:#F8BA3C;\">後半のｵﾌｨｼｬﾙが未選択ですがよろしいですか？</div>";
                        }
                    }
                } else {
                    // 別カードのオフィシャル指定があるとき
#$errorValue .= "OFF<BR>";
                    $officialTeamNameA[$i] = $officials[$i];
                    $setOfficialNum = $officials[$i];

$errorValue .= $setOfficialNum." OFFi<BR>";

                    if (is_numeric($home[$setOfficialNum]) AND is_numeric($away[$setOfficialNum])) {
                        $errorValue .= $homeTeamName[$setOfficialNum]."　　　　　　　　NO_SET_GAMES<BR>";
                    }
                }
*/
                // 最終データ登録用
                $insertHour[$setNum] = $hour[$i];
                $insertMinutes[$setNum] = $minutes[$i];
                $insertHome[$setNum] = $home[$i];
                $insertAway[$setNum] = $away[$i];
                $insertCort[$setNum] = $cort[$i];
                $insertOffisials[$setNum] = $officials[$i];

                $setNum++;
            } else if ($events[$i] != '') {
	            	if ($event1_[$i] == '' AND $event2_[$i] == '') {
	                    $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;イベント設定が空白です。</li>\n";
	                    $fmError = False;
	                    $errorNum++;
	            	} else {
	            		$insertHour[$i] = $hour[$i];
		                $insertMinutes[$i] = $minutes[$i];
		                $insertCort[$i] = $cort[$i];
                		$insertHome[$i] = 0;
	            		$insertAway[$i] = 0;
	            		$insertOfficialA[$i] = 0;
	            		$insertOfficialB[$i] = 0;
	            		$insertEvent1[$i] = $event1_[$i];
	            		$insertEvent2[$i] = $event2_[$i];
	            		$setNum++;
	            	}
	            }
        }
#print nl2br(print_r($insertHour,true));
#print nl2br(print_r($insertHome,true));
#print nl2br(print_r($insertAway,true));
#$errorValue .= $setNum." SSETNUM<BR>";
#$errorValue .= print_r($homeTeamName)." <BR><BR><BR>";
#$errorValue .= print_r($awayTeamName)." <BR>";
        if ($setNum > 0) {

#$errorValue .= print_r($homeTeamName)."asdfg";
			if (count($homeTeamName) > 0) {
	            $checkTeams = array_unique($homeTeamName);
#print nl2br(print_r($checkTeams,true));
	            foreach ($checkTeams as $key => $val) {
//print $key." = ".$val."<BR>";
		        	// イベント設定時はスルー
		        	if ($events[$key] != '') {
		        		continue;
		        	}
		        	// 同チーム試合数チェック
					$dbHomeDistinctNum = $errorCheckObj->checkDistinctTeamGames($rarryId, $home[$key], $dbCheckDate);
	                if ($dbHomeDistinctNum > 0) {
#print $dbHomeDistinctNum." = DIST<br />";
#print $$homeTeamName." = aaa<br />";
#print $distinctGame." = X<br />";
	                    $aaa = $$homeTeamName + $dbHomeDistinctNum;
	                }
	                if ($aaa >= $distinctGame) {
                    	$_SESSION["schejuleInsertData"]["error"][11] .= "<li style=\"font-weight:bold;color:#E14F25;\">チーム&nbsp;".$aaa.$val.$dbHomeDistinctNum."&nbsp;の同日の試合数が".$distinctGame."を超えています。</li>\n";
                    	$errorNum++;
                	}
#print $homeTeamName.$$homeTeamName." = MUM#<br />";
            	}
			}

            // 時間のチェック(フォーム)
            $checkTimeNum = count($checkTimes);
            for ($i=0; $i < 10; $i++) {

	        	// イベント設定時はスルー
	        	if ($events[$i] != '') {
	        		continue;
	        	}

                if (!isset($checkTimes[$i])) continue;
                for ($j=0; $j < 10; $j++) {

                    if ($i == $j OR !isset($checkTimes[$j])) continue;

#$errorValue .= $home[$i]." = ".$away[$i]."<BR>";
                    // フォーム内で同組のカード指定があればエラー
                    if ((($home[$i] == $home[$j]) AND ($away[$i] == $away[$j])) OR ($home[$i] == $away[$j]) AND ($away[$i] == $home[$j])) {
                        $fmError = False;
#$errorValue .= $home[$i]."WNGNG".$i."<BR>";
                        $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;".($j+1)."と対戦の組み合わせが同じです。</li>\n";
                        $errorNum++;
                    }
                    // 同一コートで選択時間の前後1時間以内は消化できないためエラーとする
                    if ($cort[$i] == $cort[$j]) {
                        if ((($checkTimes[$i] - 3600) < $checkTimes[$j]) AND (($checkTimes[$i] + 3600) > $checkTimes[$j])) {
                            $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;時間が「No.".($j+1)."」と1時間以内です</li>\n";
                            $errorNum++;
                        }
                    }
                    // 登録済みスケジュールに同コートで選択時間の前後1時間以内はエラーとする



/*

                    if ($cort[$i] == isset($checkDatesSchejuleData[$block][$j])) {
                        if ((($checkTimes[$i] - 3600) < $checkTimes[$j]) AND (($checkTimes[$i] + 3600) > $checkTimes[$j])) {
                            $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;時間が「No.".($j+1)."」と1時間以内です</li>\n";
                            $errorNum++;
                        }
                    }

*/



                }
                // オフィシャル別途指定ボタンにチェックがあるとき
                if (isset($noOfficial[$i])) {

//$errorValue .= $i."ON<BR>";
                    // 前半オフィシャル
                    if ($officialA[$i] > 0) {

                        if (($officialA[$i] == $home[$i]) OR ($officialA[$i] == $away[$i])) {
                            $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;試合・オフィシャルの同チームは選択できません。</li>\n";
                            $errorNum++;
                        } else {
                            // 前半オフィシャルチーム名
                            if ($teamDataObj->selectTeamData($officialA[$i]) == True) {
                                $teamNameView = $teamDataObj->getTeamName();
                                $setOfficialA[$i] = $home[$setOfficialNum];
                                $insertOfficialA[$i] = $officialA[$i];
                                // オフィシャルチームが同日に試合がないときは警告
                                $dbOfficialATeamDistinctNum = $errorCheckObj->checkDistinctTeamGames($rarryId, $officialA[$i], $dbCheckDate);
                                if (!array_search($officialA[$i], $home) AND ($dbOfficialATeamDistinctNum == 0)) {
                                    $setOfficials[$i] = "<span style=\"font-weight:bold;color:#C25904;\">".$teamNameView."</span>は同日に試合がありませんがよろしいですか？";
                                } else {
                                    $setOfficials[$i] .= "前半：".$teamNameView;
                                }
                            } else {
                                #$setOfficials[$i] = "<span style=\"font-weight:bold;color:#F8BA3C;\">前半のｵﾌｨｼｬﾙが未選択ですがよろしいですか？</span>";
                            }
                        }
                    } else {
                        $setOfficials[$i] = "<span>前半のｵﾌｨｼｬﾙが未選択ですがよろしいですか？</span>";
                        $insertOfficialA[$i] = 0;
                    }
                    // 後半オフィシャル
                    if ($officialB[$i] > 0) {
                        if (($officialB[$i] == $home[$i]) OR ($officialB[$i] == $away[$i])) {
                            $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;試合・オフィシャルの同チームは選択できません。</li>\n";
                            $errorNum++;
                        } else {
                            // 後半オフィシャルチーム名
                            if ($teamDataObj->selectTeamData($officialB[$i])) {
                                $teamNameView = $teamDataObj->getTeamName();
                                $insertOfficialB[$i] = $officialB[$i];
                                // オフィシャルチームが同日に試合がないときは警告
                                $dbOfficialATeamDistinctNum = $errorCheckObj->checkDistinctTeamGames($rarryId, $officialB[$i], $dbCheckDate);
                                if (!array_search($officialB[$i], $away) AND ($dbOfficialATeamDistinctNum == 0)) {
                                    $setOfficials[$i] .= "<br /><span style=\"font-weight:bold;color:#C25904;\">".$teamNameView."</span>は同日に試合がありませんがよろしいですか？";
                                } else {
                                    $setOfficials[$i] .= "<br />後半：".$teamNameView;
                                }
                            } else {
                                #$setOfficials[$i] .= "<div style=\"font-weight:bold;color:#F8BA3C;\">後半のｵﾌｨｼｬﾙが未選択ですがよろしいですか？</div>";
                            }
                        }
                    } else {
                        $setOfficials[$i] .= "<br /><span>後半のｵﾌｨｼｬﾙが未選択ですがよろしいですか？</span>";
                        $insertOfficialB[$i] = 0;
                    }
                } else {
                	// オフィシャル・審判のチームの取得および当日ゲームのないチームがあれば警告
                    $setOfficialNum = $insertOffisials[$i] - 1;
                    if (is_numeric($home[$setOfficialNum]) AND is_numeric($away[$setOfficialNum])) {
                        $insertOfficialA[$setOfNum] = $home[$setOfficialNum];
                        $insertOfficialB[$setOfNum] = $away[$setOfficialNum];
                        $insertOfficialA[$i] = $insertOfficialA[$setOfNum];
                        $insertOfficialB[$i] = $insertOfficialB[$setOfNum];
                        if ($teamDataObj->selectTeamData($insertOfficialA[$setOfNum])) {
                            $offisialBeforTeamName[$setOfNum] = $teamDataObj->getTeamName();
                        }
                        if ($teamDataObj->selectTeamData($insertOfficialB[$setOfNum])) {
                            $offisialAfterTeamName[$setOfNum] = $teamDataObj->getTeamName();
                        }
                        $setOfficials[$i] = "前半：".$offisialBeforTeamName[$setOfNum]."<br />後半：".$offisialAfterTeamName[$setOfNum];
                        $setOfNum++;
                    } else {
                        $insertOfficialA[$i] = 0;
                        $insertOfficialB[$i] = 0;
                        $setOfficials[$i] = "オフィシャルが未選択ですがよろしいですか？";
                    }
                }
            }
        // 1試合以上対戦を組んでいないとき
        } else {

//        	(boolean)$eventFlag = false;

            // イベント設定があるとき
//        	for ($i=0; $i < 10; $i++) {
//	            if ($events[$i] != '') {
//	            	if ($event1_[$i] == '' AND $event2_[$i] == '') {
//	                    $_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">No.".($i+1)."&nbsp;：&nbsp;イベント設定が空白です。</li>\n";
//	                    $fmError = False;
//	                    $errorNum++;
//	            	} else {
//	            		print $event1_[$i].' = イベント１<br />';
//	            		print $event2_[$i].' = イベント２';
//	            		$insertHour[$i] = $hour[$i];
//		                $insertMinutes[$i] = $minutes[$i];
//		                $insertCort[$i] = $cort[$i];
//                		$insertHome[$i] = 0;
//	            		$insertAway[$i] = 0;
//	            		$insertOfficialA[$i] = 0;
//	            		$insertOfficialB[$i] = 0;
//	            		$insertEvent1[$i] = $event1_[$i];
//	            		$insertEvent2[$i] = $event2_[$i];
//	            		$eventFlag = true;
//	            		$setNum++;
//	            	}
//	            }
//        	}
//        	if ($eventFlag == false) {
	        	$_SESSION["schejuleInsertData"]["error"][$i] .= "<li style=\"font-weight:bold;color:#E14F25;\">最低1試合は選択して下さい。</li>\n";
	            $errorNum++;
//        	}
        }
#print nl2br(print_r($home,true));
#print nl2br(print_r($insertOfficialA,true));
#print nl2br(print_r($away,true));
#print nl2br(print_r($insertOfficialB,true));
#print nl2br(print_r($insertOfficials,true));
#print nl2br(print_r($setOfficials,true));

//print $errorNum." = ERRORCOUNT<BR>";
        // エラーカウントがあるときは登録画面に戻す
        if ($errorNum > 0) {
        	$paramStartNum = strpos($_SERVER["HTTP_REFERER"], '?');
        	if ($paramStartNum > 0) {
        		$returns = substr($_SERVER["HTTP_REFERER"], 0, $paramStartNum);
        	} else {
        		$returns = $_SERVER["HTTP_REFERER"];
        	}
            header("Location: ".$returns."?mode=fmErr");
        } else {
            // 試合会場データオブジェクトの取得
            if ($mHallDataObj->selectHallData($hall) ==True) {
                $hallName  = $mHallDataObj->getHallData();
                $setHallName = $hallName["h_name"]."&nbsp;&nbsp;".$selectHallData["coat"];
            }
            switch (date("w", mktime(0,0,0,$setMonth,$setDays,$setYear))) {
                case 0 : $youbi = "日"; break;
                case 1 : $youbi = "月";
                case 2 : $youbi = "火";
                case 3 : $youbi = "水";
                case 4 : $youbi = "木";
                case 5 : $youbi = "金"; $fmError = False;
                         $errorValue .= "<li>※&nbsp;土曜もしくは日曜のみ指定可能です。</li>\n";
                         break;
                case 6 : $youbi = "土"; break;
            }
            // 大会ブロックデータオブジェクトの取得
            if ($mBlockDataObj->selectRrarryRegistBlockData($rarryId, $block) ==True) {
                $selectBlockData = $mBlockDataObj->getSelectBlockData();
                $setBlockName = $selectBlockData["BLOCK_NAME"];
                $setBlockId = $selectBlockData["BLOCK_ID"];
            }
            //$mode = "confirm"
        }

/*
        if ( !$sqlError ) {
            $sqlError = False;
            $connectionDBObj->Query( "ROLLBACK" );
        } else {
            //$connectionDBObj->Query( "COMMIT" );
        }

        $connectionDBObj->Query( "ROLLBACK" );
        // メモリ解放
        if ( $insertQuery > 0 ) $connectionDBObj->FreeQuery($rs);
*/
    /*----------------------------------------------------
	 * データベース登録
	 *--------------------------------------------------*/
    } else if ($mode == "insert") {

        # スケジュールのデータ数の取得
        $sql = "SELECT " .
                         " `id` AS lastId " .
                      " FROM `rarry_schejules` " .
                      " ORDER BY `id` DESC " .
                      " LIMIT 1 " ;
#print $sql."<BR>";
        $rs  = $connectionDBObj->Query($sql);
        if(!$rs){ print "スケジュールのデータ数の取得エラーです。<br />".$sql."<br />"; return false; }
        // データ数を取得
        $num        = $connectionDBObj->GetRowCount($rs);
        if($num > 0){
            $data   = $connectionDBObj->FetchRow($rs);       // １行Ｇｅｔ
            // スケジュール最終ID
            $insertNumber = $data["lastId"];
        }

        # ブロックのチーム登録数
        $sql = "SELECT " .
                       " count(`class`) AS blockNum " .
                    " FROM `regist_teams` " .
                    " WHERE `r_id` = " . $rarryId . " " .
                    "  AND  `class` = " . $block . " " ;
##print $sql."<BR>";
        $rs  = $connectionDBObj->Query($sql);
        if(!$rs){ print "ブロックのチーム登録数の取得エラーです。<br />".$sql."<br />"; return false; }
        // データ数を取得
        $num        = $connectionDBObj->GetRowCount($rs);
        if($num > 0){
            $data   = $connectionDBObj->FetchRow($rs);       // １行Ｇｅｔ
            // ブロックのチーム登録数
            $blockNum = $data["blockNum"];
        }

/*
*/
        // カラム部SQL
        $ins_sql = "INSERT INTO `rarry_schejules`  (" .
                   " `id`, " .
                   " `r_id`, " .
                   " `times`, " .
                   " `class`, " .
                   " `level`, " .
                   " `section`, " .
                   " `court`, " .
                   " `home_team`, " .
                   " `away_team`, " .
                   " `ofisial_a`, " .
                   " `ofisial_b`, " .
                   " `importance`, " .
                   " `event`, " .
                   " `event2`, " .
                   " `created`, " .
                   " `modified` " .
                   " ) VALUES ";
        for ($i=0; $i<$dataNum; $i++) {

            // 節の取得
            if ($schejuleDataObj->versusSectionNumber($rarryId, $home[$i], $away[$i], $blockNum) == True) {

                // 対戦節
                $paragraphNumber = $schejuleDataObj->getBothNotTeamSection();
            }
            if (!isset($paragraphNumber)) {
                $paragraphNumber = 1;
            }
//print $paragraphNumber."　＝ 対戦節<BR>";

            //$databaseData->versusParagraphNumber("lt_schejule", $rarryId);

            $insertNumber++;

            // 試合日・時間
            $insert_datetime = $setYear."-".$setMonth."-".$setDays." ".$hour[$i].":".$minutes[$i].":00";

            // データ部SQL
            $ins_sql .= "(" .
                        $insertNumber.", " .
                        $rarryId.", " .
                        "'".$insert_datetime."', " .
                        $block.", " .
                        $level.", " .
                        $paragraphNumber.", " .
                        $cort[$i].", " .
                        $home[$i].", " .
                        $away[$i].", " .
                        $officialA[$i].", " .
                        $officialB[$i].", " .
                        "0, " .
                        "'".$event1[$i]."', " .
                        "'".$event2[$i]."', " .
                        "NOW(), " .
                        "NULL)";

            if (($dataNum - 1) != $i) $ins_sql .= ",";
        }

//print $ins_sql."<BR>";
        // トランザクション開始
        $connectionDBObj->Query("BEGIN");

        // データ登録
        $ins_rs  = $connectionDBObj->Query($ins_sql);

        if ( !$ins_rs ) {
            $sqlError = False;
            //print "ROLLBACK<BR>";
            $connectionDBObj->Query( "ROLLBACK" );
            $insertComment .= "データベース登録エラーです。<br />".$ins_sql."<br />";
        } else {
            //$sqlError = False;
            //print "COMMIT<BR>";
//            $connectionDBObj->Query( "ROLLBACK" );
            $connectionDBObj->Query( "COMMIT" );
            $insertComment .= "登録しました。<br />".$ins_sql."<br />";
            $_SESSION["schejuleSet"]["ok"] = "commit";
//            header("Location: https://".$_SERVER["SERVER_NAME"]."/admin/main_schejule.php");
            header("Location: https://liga-tokai-com.ssl-netowl.jp/management.liga-tokai.com/admin/main_schejule.php");
        }

        //$insertQuery++;

        // メモリ解放
        //$connectionDBObj->FreeQuery($ins_rs);
    } /* SQL終了 */ else {
        $errorNum++;
    }
    if ( !$sqlError ) {
        //$sqlError = False;
        //$connectionDBObj->Query( "ROLLBACK" );
    } else {
        //$connectionDBObj->Query( "COMMIT" );
    }

    #$connectionDBObj->Query( "ROLLBACK" );
    // メモリ解放
    #if ( $insertQuery > 0 ) $connectionDBObj->FreeQuery($rs);


?>
<?php include_once "block/header.php" ?>
<body>
<div id="main">
<!-- メインコンテンツ -->
<?php include_once "block/menu.php" ?>
  <div id="middle">
<!-- ページ内サブコンテンツ -->
<?php include_once "block/contents.php" ?>
    <div id="center-column">
      <div class="top-bar">
        <h1>スケジュール登録確認画面</h1>
      </div>
      <br />
      <div class="select-bar">&nbsp;</div>
<!-- 登録済みスケジュール -->
<?php
//print $mode;
//print $ins_sql."<br />";
//print $insertComment;
// 登録データ確認画面表示
if ($mode == "confirm") {
?>
<!-- 新規登録モード -->
      <div class="table">
        <form name="frm" action="./<?php echo $scriptName; ?>" method="post">
        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <table class="listing form" cellpadding="0" cellspacing="0">
          <tr>
            <th class="full" colspan="14">新規スケジュール登録確認</th>
          </tr>
          <tr>
            <td colspan="2">日付・会場</td>
            <td colspan="10" class="first style2">
<?php
print <<<EOF
              $setYear 年
              $setMonth 月
              $setDays 日
              &nbsp;($youbi)
              <span style="padding:0px 20px;">
                $setBlockName
              </span>
              <span style="padding:0px 20px;">
                $setHallName
              </span>
            </td>
          </tr>
          <tr>
            <th>No.</th>
            <th>時間</th>
            <th>コート</th>
            <th>HOME</th>
            <th>AWAY</th>
            <th>OFICIAL</th>
          </tr>
EOF;
        // 詳細登録フォーム
        //for ($j=0; $j<10; $j++)
        for ($j=0; $j<$setNum; $j++) {
            //if (!is_numeric($home[$j]) OR !is_numeric($away[$j])) continue;
            $data_num++;
            if ($events[$j] == '') {
print <<<EOF
          <tr>
            <td>$data_num</td>
            <td>$hour[$j]：$minutes[$j]～</td>
            <td>$coatName[$j]</td>
            <td>$homeTeamName[$j]</td>
            <td>$awayTeamName[$j]</td>
            <td style="padding-left:10px;text-align:left;">$setOfficials[$j]</td>
          </tr>
EOF;
            } else {
print <<<EOF
          <tr>
            <td>&nbsp;</td>
            <td>$hour[$j]：$minutes[$j]～</td>
            <td>$coatName[$j]</td>
            <td colspan="3" style="text-align:left;">
              イベント1：$insertEvent1[$j]
EOF;
				if ($insertEvent2[$j] != '') {
print <<<EOF
              <br />イベント2：$insertEvent2[$j]
EOF;
				}
print <<<EOF
            </td>
          </tr>
EOF;
            }
print <<<EOF
          <input type="hidden" name="hour[$insNumber]" value="$insertHour[$j]" />
          <input type="hidden" name="minutes[$insNumber]" value="$insertMinutes[$j]" />
          <input type="hidden" name="cort[$insNumber]" value="$insertCort[$j]" />
          <input type="hidden" name="home[$insNumber]" value="$insertHome[$j]" />
          <input type="hidden" name="away[$insNumber]" value="$insertAway[$j]" />
          <input type="hidden" name="officialA[$insNumber]" value="$insertOfficialA[$j]" />
          <input type="hidden" name="officialB[$insNumber]" value="$insertOfficialB[$j]" />
          <input type="hidden" name="event1[$insNumber]" value="$insertEvent1[$j]" />
          <input type="hidden" name="event2[$insNumber]" value="$insertEvent2[$j]" />
EOF;
            $insNumber++;
        }
print <<<EOF
        </table>
        <div style="margin:20px 0px 10px 30px;">上記内容でよろしければ「登録」を選択してください</div>
        <input type="hidden" name="mode" value="insert" />
        <input type="hidden" name="setYear" value="$setYear" />
        <input type="hidden" name="setMonth" value="$setMonth" />
        <input type="hidden" name="setDays" value="$setDays" />
        <input type="hidden" name="hall" value="$setHallId" />
        <input type="hidden" name="block" value="$block" />
        <input type="hidden" name="dataNum" value="$data_num" />
        <div style="margin:0px 0px 0px 30px;">
          <input type="submit" value="登録する" style="padding:2px 0px"/>
          <input type="button" value="戻る" style="margin-left:20px;padding:1px 10px" onclick="location.href='./main_schejule.php?mode=fmErr'" />
        </div>
        </form>
EOF;
    if ($fmError == False AND $mode != "new") {
print <<<EOF
        </table>
        <p>下記エラーが発生しまいた。</p>
        <ol style="color:#F00;">
          $errorValue
        </ol>
EOF;
    }
} else {
print <<<EOF
      <div style="font-size:140%;">
        $insertComment
EOF;
}
  if ($errorValue != "") {
print <<<EOF
        <ol style="color:#F00;">
          $errorValue
        </ol>
EOF;
  }
?>
        <p>&nbsp;</p>
      </div>
    </div>
    <div id="footer"></div>
  </div>
<?php include_once "block/footer.php" ?>
