<?php

    // ヘッダー読み込みCSSファイル設定
    $readCssFile = array('style', 'set_score', 'jquery-ui');
    // ヘッダー読み込みjavascriptファイル設定
    $readJsFile = array('util', 'validator');

    require_once './common.inc';

    // 初期値
    $pageTitle = "スコア登録";
    $dataError = True;
    $teamFmError = True;
    #$mode = "";
    (int)$rarryId = $_SESSION["rarryId"];
    #$games = 252;
    #$season = 2008;
    #$class = 1;
    $versusDataFlag = False;
    $sqlError = True;
    (int)$team_id = 0;
    (int)$serch_month = 0;
    (int)$class = 0;
    (int)$seasons = 0;
    $homeIndiScoreData = array();
    $awayIndiScoreData = array();
    $homeIndiGameCheck = False;
    $awayIndiGameCheck = False;
    $antiwarGame = False;
    if (!isset($status)) $status = ""; $fmDisabled = "";
    if (!isset($home1st)) $home1st = 0;
    if (!isset($home2nd)) $home2nd = 0;
    if (!isset($home3rd)) $home3rd = 0;
    if (!isset($home4th)) $home4th = 0;
    if (!isset($away1st)) $away1st = 0;
    if (!isset($away2nd)) $away2nd = 0;
    if (!isset($away3rd)) $away3rd = 0;
    if (!isset($away4th)) $away4th = 0;
    if (!isset($antiwar)) $antiwar = 0;
    if (!isset($antiwarScore)) $antiwarScore = 20;

    if (!isset($h_ot1)) $h_ot1 = 0;
    if (!isset($h_ot2)) $h_ot2 = 0;
    if (!isset($h_ot3)) $h_ot3 = 0;
    if (!isset($h_ot4)) $h_ot4 = 0;
    if (!isset($h_ot5)) $h_ot5 = 0;
    if (!isset($h_ot6)) $h_ot6 = 0;
    if (!isset($h_ot7)) $h_ot7 = 0;
    if (!isset($h_ot8)) $h_ot8 = 0;
    if (!isset($h_ot9)) $h_ot9 = 0;
    if (!isset($a_ot1)) $a_ot1 = 0;
    if (!isset($a_ot2)) $a_ot2 = 0;
    if (!isset($a_ot3)) $a_ot3 = 0;
    if (!isset($a_ot4)) $a_ot4 = 0;
    if (!isset($a_ot5)) $a_ot5 = 0;
    if (!isset($a_ot6)) $a_ot6 = 0;
    if (!isset($a_ot7)) $a_ot7 = 0;
    if (!isset($a_ot8)) $a_ot8 = 0;
    if (!isset($a_ot9)) $a_ot9 = 0;

    $homePartSettingForm = "";
    $awayPartSettingForm = "";
    
    $gameReport = '';
    $gameReportPicture1 = null;
    $gameReportPicture2 = null;
    $gameReportPicture3 = null;
    
    $rootArray = explode("/", $_SERVER['PHP_SELF']);
    $root = $rootArray[1];
//print nl2br(print_r($rootArray,true));

    // 個人集計でアシスト・リバウンド・スティール・ブロックショットを計上するクラス
    $indiScoreMoreDetailClass = array(1,15,16,21,23);

    // CSS設定
    $homeWD = 'text-align:center;';   // HOME総合得点結果
    $awayWD = 'text-align:center;';   // AWAY総合得点結果

    // パラメータの環境変数
    $strRequestMethod = $_SERVER["REQUEST_METHOD"];

    // クライアントからPOSTで受取ったデータを変数に落とす
    if ($strRequestMethod == "GET") {
        while(list ($key, $val) = each($_GET)) {
            $$key = $val;
//print $key ." = ". $val."<BR>";
        }
    } else if ($strRequestMethod == "POST") {
        while(list ($key, $val) = each($_POST)) {
            $$key = $val;
//print $key ." = ". $val."<BR>";
        }
    }

    if ($rarryId == 4) {
    	$seasons = 1;
    }

    if ($rarryDataObj->rarryDetails($rarryId)) {
        $rarryDetails = $rarryDataObj->getRarryDetail();
    }
//print nl2br(print_r($_SERVER,true));
//print nl2br(print_r($_SESSION,true));
//print nl2br(print_r($rarryDetails,true));
/*
    // セッションチェック
    if ($_SESSION["indiScoreInsertData"] == "ok") {
        $mode = "change";
        $status = "";
        $_SESSION["indiScoreInsertData"] = "";
    }
*/
//print $status." = status<br />";
//print $mode." = mode<br />";
//print $gameClass." = gameClass<br />";
    /*--------------------------------------------------------------------------
     * GETデータチェック
     --------------------------------------------------------------------------*/
    // チーム成績の登録確認
    if ($status == "confilm") {

        $subTitle = "確認(チーム)";

        // フォーム入力をさせない
        $fmDisabled = "disabled=\"disabled\"";

        // 不戦勝敗処理
        if (isset($antiwarGames)) {
            if (isset($antiwar) AND isset($antiwarScore)) {
                // 不戦敗チームがHOME側
                if ($antiwar == "home") {
                    $home1st = 0; $home2nd = 0; $home3rd = 0; $home4th = 0;
                    $away1st = $antiwarScore;
                    $away2nd = 0; $away3rd = 0; $away4th = 0;
                    $homeTotalScore = 0;
                    $awayTotalScore = $antiwarScore;
                    $homeShouhai = "<div style=\"color:red;font-weight:bold;\">不戦敗</div>";
                    $awayShouhai = "<div style=\"color:blue;font-weight:bold;\">不戦勝</div>";
                } else {
                    $home1st = $antiwarScore; $homeTotalScore = $antiwarScore;
                    $home2nd = 0; $home3rd = 0; $home4th = 0;
                    $away1st = 0; $away2nd = 0; $away3rd = 0; $away4th = 0;
                    $homeTotalScore = $antiwarScore;
                    $awayTotalScore = 0;
                    $homeShouhai = "<div style=\"color:blue;font-weight:bold;\">不戦勝</div>";
                    $awayShouhai = "<div style=\"color:red;font-weight:bold;\">不戦敗</div>";
                }
            } else {
                if ($antiwar == "") {
                    $homeErrorValue .= "          <li>不戦敗のチームを選択してください。</li>";
                    $teamFmError = False;
                }
            }
            if ($antiwarScore == "") {
                $homeErrorValue .= "          <li>不戦勝の得点が未入力です。</li>\n";
                $teamFmError = False;
            } else if (!preg_match("/^[0-9]+$/", $antiwarScore)) {
                $homeErrorValue .= "          <li>不戦勝の得点の値が数値以外です。</li>\n";
                $teamFmError = False;
            } else if ($antiwarScore > 99) {
                $homeErrorValue .= "          <li>不戦勝の得点の値を99点以内にしてください。</li>\n";
                $teamFmError = False;
            }
        } else {
            // HOME側　クォーターのチェック
            ## 1Q
            if ($home1st == "") {
                $homeErrorValue .= "          <li>1Qが未入力です。</li>\n";
                $teamFmError = False;
            } else if (!preg_match("/^[0-9]+$/", $home1st)) {
                $homeErrorValue .= "          <li>1Qの値が数値以外です。</li>\n";
                $teamFmError = False;
            } else if ($home1st > 99) {
                $homeErrorValue .= "          <li>1Qの値を99点以内にしてください。</li>\n";
                $teamFmError = False;
            } else {
                #$_SESSION["conf"]["home1st"] = $home1st;
            }
            ## 2Q
            if ($home2nd == "") {
                $homeErrorValue .= "          <li>2Qが未入力です。</li>\n";
                $teamFmError = False;
            } else if (!preg_match("/^[0-9]+$/", $home2nd)) {
                $homeErrorValue .= "          <li>2Qの値が数値以外です。</li>\n";
                $teamFmError = False;
            } else if ($home2nd > 99) {
                $homeErrorValue .= "          <li>2Qの値を99点以内にしてください。</li>\n";
                $teamFmError = False;
            } else {
                #$_SESSION["conf"]["home2nd"] = $home2nd;
            }
            ## 3Q
            if ($home3rd == "") {
                $homeErrorValue .= "          <li>3Qが未入力です。</li>\n";
                $teamFmError = False;
            } else if (!preg_match("/^[0-9]+$/", $home3rd)) {
                $homeErrorValue .= "          <li>3Qの値が数値以外です。</li>\n";
                $teamFmError = False;
            } else if ($home3rd > 99) {
                $homeErrorValue .= "          <li>3Qの値を99点以内にしてください。</li>\n";
                $teamFmError = False;
            } else {
                #$_SESSION["conf"]["home3rd"] = $home3rd;
            }
            ## 4Q
            if ($home4th == "") {
                $homeErrorValue .= "          <li>4Qが未入力です。</li>\n";
                $teamFmError = False;
            } else if (!preg_match("/^[0-9]+$/", $home4th)) {
                $homeErrorValue .= "          <li>4Qの値が数値以外です。</li>\n";
                $teamFmError = False;
            } else if ($home4th > 99) {
                $homeErrorValue .= "          <li>4Qの値を99点以内にしてください。</li>\n";
                $teamFmError = False;
            } else {
                #$_SESSION["conf"]["home4th"] = $home4th;
            }

            // AWAY側　クォーターのチェック
            ## 1Q
            if ($away1st == "") {
                $awayErrorValue .= "          <li>1Qが未入力です。</li>\n";
                $teamFmError = False;
            } else if (!preg_match("/^[0-9]+$/", $away1st)) {
                $awayErrorValue .= "          <li>1Qの値が数値以外です。</li>\n";
                $teamFmError = False;
            } else if ($away1st > 99) {
                $awayErrorValue .= "          <li>1Qの値を99点以内にしてください。</li>\n";
                $teamFmError = False;
            } else {
                #$_SESSION["conf"]["away1st"] = $away1st;
            }
            ## 2Q
            if ($away2nd == "") {
                $awayErrorValue .= "          <li>2Qが未入力です。</li>\n";
                $teamFmError = False;
            } else if (!preg_match("/^[0-9]+$/", $away2nd)) {
                $awayErrorValue .= "          <li>2Qの値が数値以外です。</li>\n";
                $teamFmError = False;
            } else if ($away2nd > 99) {
                $awayErrorValue .= "          <li>2Qの値を99点以内にしてください。</li>\n";
                $teamFmError = False;
            } else {
                #$_SESSION["conf"]["away2nd"] = $away2nd;
            }
            ## 3Q
            if ($away3rd == "") {
                $awayErrorValue .= "          <li>3Qが未入力です。</li>\n";
                $teamFmError = False;
            } else if (!preg_match("/^[0-9]+$/", $away3rd)) {
                $awayErrorValue .= "          <li>3Qの値が数値以外です。</li>\n";
                $teamFmError = False;
            } else if ($away3rd > 99) {
                $awayErrorValue .= "          <li>3Qの値を99点以内にしてください。</li>\n";
                $teamFmError = False;
            } else {
                #$_SESSION["conf"]["away3rd"] = $away3rd;
            }
            ## 4Q
            if ($away4th == "") {
                $awayErrorValue .= "          <li>4Qが未入力です。</li>\n";
                $teamFmError = False;
            } else if (!preg_match("/^[0-9]+$/", $away4th)) {
                $awayErrorValue .= "          <li>4Qの値が数値以外です。</li>\n";
                $teamFmError = False;
            } else if ($away4th > 99) {
                $awayErrorValue .= "          <li>4Qの値を99点以内にしてください。</li>\n";
                $teamFmError = False;
            } else {
                #$_SESSION["conf"]["away4th"] = $away4th;
            }

            if (($home1st + $home2nd + $home3rd + $home4th) == 0 AND ($away1st + $away2nd + $away3rd + $away4th) == 0) {
                $homeErrorValue .= "          <li>両チーム共に合計得点が0点は登録できません。</li>";
                $teamFmError = False;
            }
            // 勝敗
            if (($home1st + $home2nd + $home3rd + $home4th) > ($away1st + $away2nd + $away3rd + $away4th)) {
                $homeShouhai = "<div style=\"color:red;font-weight:bold;\"><img src=\"../admin/img/versus/win.jpg\" alt=\"GAME-WIN\" /></div>";
                $awayShouhai = "<div style=\"color:blue;font-weight:bold;\"><img src=\"../admin/img/versus/lose.jpg\" alt=\"GAME-LOSE\" /></div>";
            } else if (($home1st + $home2nd + $home3rd + $home4th) == ($away1st + $away2nd + $away3rd + $away4th)) {
                $homeShouhai = "<div style=\"color:green;font-weight:bold;\"><img src=\"../admin/img/versus/drow.jpg\" alt=\"GAME-DROW\" /></div>";
                $awayShouhai = "<div style=\"color:green;font-weight:bold;\"><img src=\"../admin/img/versus/drow.jpg\" alt=\"GAME-DROW\" /></div>";
            } else {
                $homeShouhai = "<div style=\"color:blue;font-weight:bold;\"><img src=\"../admin/img/versus/lose.jpg\" alt=\"GAME-LOSE\" /></div>";
                $awayShouhai = "<div style=\"color:red;font-weight:bold;\"><img src=\"../admin/img/versus/win.jpg\" alt=\"GAME-WIN\" /></div>";
            }
        }
    // 個人成績の登録確認・完了
    } else if ($status == "homeIndiComplet" OR $status == "awayIndiComplet" OR $status == "homeIndiConfilm" OR $status == "awayIndiConfilm") {

        $subTitle = "確認(個人)";

        (int)$setIndiNum = 0;

		// 個人成績の登録確認・完了：HOME側
        if ($status == "homeIndiComplet" OR $status == "homeIndiConfilm") {

            $gameSide = "home";
            $homeIndiFmError = True;

            // 出場人数がある場合にデータチェック
            if (count($homeSetMember) > 0) {

                foreach ($homeSetMember as $key => $val) {

                    if (!preg_match("/^[0-9]+$/", $homeSetIndiScoreData["ThreePoint".$val])) {
                        $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$homeSetIndiNumber["IndiNumber".$val] ."の3P数の値が数値以外です。</div>\n";
                        $homeIndiFmError = False;
                    }
                    if (!preg_match("/^[0-9]+$/", $homeSetIndiScoreData["TwoPoint".$val])) {
                        $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$homeSetIndiNumber["IndiNumber".$val]."の2P数の値が数値以外です。</div>\n";
                        $homeIndiFmError = False;
                    }
                    if (!preg_match("/^[0-9]+$/", $homeSetIndiScoreData["FreeTrrow".$val])) {
                        $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$homeSetIndiNumber["IndiNumber".$val]."のFT数の値が数値以外です。</div>\n";
                        $homeIndiFmError = False;
                    }
                    if (in_array($gameClass, $indiScoreMoreDetailClass)) {
	                    if (!preg_match("/^[0-9]+$/", $homeSetIndiScoreData["OffenceRebound".$val])) {
    	                    $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$homeSetIndiNumber["IndiNumber".$val]."のOFリバウンド数の値が数値以外です。</div>\n";
        	                $homeIndiFmError = False;
            	        	}
                    	if (!preg_match("/^[0-9]+$/", $homeSetIndiScoreData["DefenceRebound".$val])) {
                	    	    $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$homeSetIndiNumber["IndiNumber".$val]."のDFリバウンド数の値が数値以外です。</div>\n";
                    	    	$homeIndiFmError = False;
                    	}
	                    if (!preg_match("/^[0-9]+$/", $homeSetIndiScoreData["Assist".$val])) {
    	                    $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$homeSetIndiNumber["IndiNumber".$val]."のアシスト数の値が数値以外です。</div>\n";
        	                $homeIndiFmError = False;
            	        	}
                	   if (!preg_match("/^[0-9]+$/", $homeSetIndiScoreData["Steal".$val])) {
                    	    $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$homeSetIndiNumber["IndiNumber".$val]."のスティール数の値が数値以外です。</div>\n";
                        	$homeIndiFmError = False;
	                  }
    	                if (!preg_match("/^[0-9]+$/", $homeSetIndiScoreData["BlockShot".$val])) {
        	                $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$homeSetIndiNumber["IndiNumber".$val]."のブロックショット数の値が数値以外です。</div>\n";
            	            $homeIndiFmError = False;
                	    }
                	} else {
                    	$homeSetIndiScoreData["OffenceRebound".$val] = 0;
                    	$homeSetIndiScoreData["DefenceRebound".$val] = 0;
                    	$homeSetIndiScoreData["Assist".$val] = 0;
                    	$homeSetIndiScoreData["Steal".$val] = 0;
                    	$homeSetIndiScoreData["BlockShot".$val] = 0;
                    }
                    $setIndiNum++;
                    $checkTeamTotalScore += ($homeSetIndiScoreData["ThreePoint".$val] * 3) + ($homeSetIndiScoreData["TwoPoint".$val] * 2) + $homeSetIndiScoreData["FreeTrrow".$val];

                    // データ登録用に選手のIDの配列でデータを作成
                    $setIndiDatas[$homeSetIndiId["IndiId".$val]] = array(
                                                                       "3point_success" => $homeSetIndiScoreData["ThreePoint".$val],
                                                                       "2point_success" => $homeSetIndiScoreData["TwoPoint".$val],
                                                                       "ft_success" => $homeSetIndiScoreData["FreeTrrow".$val],
                                                                       "of_rebound" => $homeSetIndiScoreData["OffenceRebound".$val],
                                                                       "de_rebound" => $homeSetIndiScoreData["DefenceRebound".$val],
                                                                       "asist" => $homeSetIndiScoreData["Assist".$val],
                                                                       "steal" => $homeSetIndiScoreData["Steal".$val],
                                                                       "block_shot" => $homeSetIndiScoreData["BlockShot".$val],
                                                                       );
                }
                // 出場人数
                if ($setIndiNum < 5) {
                    $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">a出場人数を5人以上にしてください。</div>\n";
                    $homeIndiFmError = False;
                }

                if (is_numeric($checkTeamTotalScore) AND $homeIndiFmError == True) {
                    // チーム得点との比較
                    if ($homeTotalScore < $checkTeamTotalScore) {
                        $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">個人合計得点がチーム得点を超えています。</div>\n";
                        $homeIndiFmError = False;
                    } else if ($homeTotalScore > $checkTeamTotalScore){
                        $homeIndiErrorValue .= "          <div style=\"color:#589A58;font-weight:bold;\">個人合計得点がチーム得点に足りませんがよろしいですか？</div>\n";
                        //$homeIndiFmError = False;
                    }
                }
            } else {
                $homeIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">5名以上登録してください。</div>\n";
                $homeIndiFmError = False;
            }
		// 個人成績の登録確認・完了：AWAY側
        } else if ($status == "awayIndiComplet" OR $status == "awayIndiConfilm") {

            $gameSide = "away";
            $awayIndiFmError = True;

            // 出場人数がある場合にデータチェック
            if (count($awaySetMember) > 0) {

                foreach ($awaySetMember as $key => $val) {

                    if (!preg_match("/^[0-9]+$/", $awaySetIndiScoreData["ThreePoint".$val])) {
                        $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$awaySetIndiNumber["IndiNumber".$val] ."の3P数の値が数値以外です。</div>\n";
                        $awayIndiFmError = False;
                    }
                    if (!preg_match("/^[0-9]+$/", $awaySetIndiScoreData["TwoPoint".$val])) {
                        $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$awaySetIndiNumber["IndiNumber".$val]."の2P数の値が数値以外です。</div>\n";
                        $awayIndiFmError = False;
                    }
                    if (!preg_match("/^[0-9]+$/", $awaySetIndiScoreData["FreeTrrow".$val])) {
                        $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$awaySetIndiNumber["IndiNumber".$val]."のFT数の値が数値以外です。</div>\n";
                        $awayIndiFmError = False;
                    }
                    if (in_array($gameClass, $indiScoreMoreDetailClass)) {
                        if (!preg_match("/^[0-9]+$/", $awaySetIndiScoreData["OffenceRebound".$val])) {
                            $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$awaySetIndiNumber["IndiNumber".$val]."のOFリバウンド数の値が数値以外です。</div>\n";
                            $awayIndiFmError = False;
                        }
                        if (!preg_match("/^[0-9]+$/", $awaySetIndiScoreData["DefenceRebound".$val])) {
                            $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$awaySetIndiNumber["IndiNumber".$val]."のDFリバウンド数の値が数値以外です。</div>\n";
                            $awayIndiFmError = False;
                        }
                        if (!preg_match("/^[0-9]+$/", $awaySetIndiScoreData["Assist".$val])) {
                            $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$awaySetIndiNumber["IndiNumber".$val]."のアシスト数の値が数値以外です。</div>\n";
                            $awayIndiFmError = False;
                        }
                        if (!preg_match("/^[0-9]+$/", $awaySetIndiScoreData["Steal".$val])) {
                            $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$awaySetIndiNumber["IndiNumber".$val]."のスティール数の値が数値以外です。</div>\n";
                            $awayIndiFmError = False;
                        }
                        if (!preg_match("/^[0-9]+$/", $awaySetIndiScoreData["BlockShot".$val])) {
                            $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">No.".$awaySetIndiNumber["IndiNumber".$val]."のブロックショット数の値が数値以外です。</div>\n";
                            $awayIndiFmError = False;
                        }
                    } else {
                    	$awaySetIndiScoreData["OffenceRebound".$val] = 0;
                    	$awaySetIndiScoreData["DefenceRebound".$val] = 0;
                    	$awaySetIndiScoreData["Assist".$val] = 0;
                    	$awaySetIndiScoreData["Steal".$val] = 0;
                    	$awaySetIndiScoreData["BlockShot".$val] = 0;
                    }
                    $setIndiNum++;
                    $checkTeamTotalScore += ($awaySetIndiScoreData["ThreePoint".$val] * 3) + ($awaySetIndiScoreData["TwoPoint".$val] * 2) + $awaySetIndiScoreData["FreeTrrow".$val];

                    // データ登録用に選手のIDの配列でデータを作成
                    $setIndiDatas[$awaySetIndiId["IndiId".$val]] = array(
                                                                       "3point_success" => $awaySetIndiScoreData["ThreePoint".$val],
                                                                       "2point_success" => $awaySetIndiScoreData["TwoPoint".$val],
                                                                       "ft_success" => $awaySetIndiScoreData["FreeTrrow".$val],
                                                                       "of_rebound" => $awaySetIndiScoreData["OffenceRebound".$val],
                                                                       "de_rebound" => $awaySetIndiScoreData["DefenceRebound".$val],
                                                                       "asist" => $awaySetIndiScoreData["Assist".$val],
                                                                       "steal" => $awaySetIndiScoreData["Steal".$val],
                                                                       "block_shot" => $awaySetIndiScoreData["BlockShot".$val],
                                                                       );
                }
                // 出場人数
                if ($setIndiNum < 5) {
                    $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">出場人数を5人以上にしてください。</div>\n";
                    $awayIndiFmError = False;
                }

                if (is_numeric($checkTeamTotalScore) AND $awayIndiFmError == True) {
                    // チーム得点との比較
                    if ($awayTotalScore < $checkTeamTotalScore) {
                        $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">個人合計得点がチーム得点を超えています。</div>\n";
                        $awayIndiFmError = False;
                    } else if ($awayTotalScore > $checkTeamTotalScore){
                        $awayIndiErrorValue .= "          <div style=\"color:#589A58;font-weight:bold;\">個人合計得点がチーム得点に足りませんがよろしいですか？</div>\n";
                        //$awayIndiFmError = False;
                    }
                }
            } else {
                $awayIndiErrorValue .= "          <div style=\"color:red;font-weight:bold;\">5名以上登録してください。</div>\n";
                $awayIndiFmError = False;
            }
        }
//print nl2br(print_r($setIndiDatas, true));

    // チーム成績の登録完了
    } else if ($status == "complet") {

        $subTitle = "完了";

        if ($antiwarGames == "ON") {
            $setAntiwar = 1;
        } else {
            $setAntiwar = "NULL";
        }

        if ($mode == "change") {
            // ------------------------------------------------------------------------------//
            // NEW HOME更新SQL
            $UpdateSql = "UPDATE `rarry_scores` SET " .
                         " `1st` = ".$home1st.", " .
                         " `2nd` = ".$home2nd.", " .
                         " `3rd` = ".$home3rd.", " .
                         " `4th` = ".$home4th.", " .
                         " `ot1` = ".$h_ot1.", " .
                         " `ot2` = ".$h_ot2.", " .
                         " `ot3` = ".$h_ot3.", " .
                         " `ot4` = ".$h_ot4.", " .
                         " `ot5` = ".$h_ot5.", " .
                         " `ot6` = ".$h_ot6.", " .
                         " `ot7` = ".$h_ot7.", " .
                         " `ot8` = ".$h_ot8.", " .
                         " `ot9` = ".$h_ot9.", " .
                         " `antiwar` = ".$setAntiwar.", " .
                         " `modified` = NOW() " .
                         " WHERE `g_id` = ".$gameId." " .
                         "  AND  `t_id` = ".$gameHomeTeamId." ;";

            // データ更新
            $home_rs  = $connectionDBObj->Query($UpdateSql);

            if ( !$home_rs ) {
                $sqlError = False;
                //print "ROLLBACK<BR>";
                $insertComment .= "HOME側NEWデータベース更新エラーです。<br />".$UpdateSql."<br />";
            } else {
                //$sqlError = False;
                //$insertComment .= "スコアを更新しました。<br />";
            }

            // NEW AWAY更新SQL
            $UpdateSql = " UPDATE `rarry_scores` SET " .
                         " `1st` = ".$away1st.", " .
                         " `2nd` = ".$away2nd.", " .
                         " `3rd` = ".$away3rd.", " .
                         " `4th` = ".$away4th.", " .
                         " `ot1` = ".$a_ot1.", " .
                         " `ot2` = ".$a_ot2.", " .
                         " `ot3` = ".$a_ot3.", " .
                         " `ot4` = ".$a_ot4.", " .
                         " `ot5` = ".$a_ot5.", " .
                         " `ot6` = ".$a_ot6.", " .
                         " `ot7` = ".$a_ot7.", " .
                         " `ot8` = ".$a_ot8.", " .
                         " `ot9` = ".$a_ot9.", " .
                         " `antiwar` = ".$setAntiwar.", " .
                         " `modified` = NOW() " .
                         " WHERE `g_id` = ".$gameId." " .
                         "  AND  `t_id` = ".$gameAwayTeamId." ;";
            // データ登録
            $away_rs  = $connectionDBObj->Query($UpdateSql);

            if ( !$away_rs ) {
                $sqlError = False;
                //print "ROLLBACK<BR>";
                $insertComment .= "AWAY側NEWデータベース更新エラーです。<br />".$UpdateSql."<br />";
            } else {
                //$sqlError = False;
                $insertComment .= "チームスコアを更新しました。<br />";
            }
            // ------------------------------------------------------------------------------//
        } else if ($mode == "input") {

            // トランザクション開始
            $connectionDBObj->Query("BEGIN");

            // スケジュールクラスの生成
            $schejuleData = new schejuleData();

            // スケジュールデータ
            if ($schejuleDataObj->selectGameData($teamDataObj, $rarryId, $gameId) == True) {
                // 登録しているスケジュール
                $schejuleDatas = $schejuleDataObj->getSelectSchejuleData();

                // カラム部SQL
                $ins_comp_sql = "INSERT INTO `rarry_complete_games`  (" .
                           " `id`, " .
                           " `r_id`, " .
                           " `times`, " .
                           " `class`, " .
                           " `level`, " .
                           " `section`, " .
                           " `progress`, " .
                           " `court`, " .
                           " `home_team`, " .
                           " `away_team`, " .
                           " `ofisial_a`, " .
                           " `ofisial_b`, " .
                           " `importance`, " .
                           " `created`, " .
                           " `modified` " .
                           " ) VALUES (" .
                            $schejuleDatas["game_id"].", " .
                            $schejuleDatas["r_id"].", " .
                            " '".$schejuleDatas["g_time"]."', " .
                            $schejuleDatas["class"].", " .
                            $schejuleDatas["level"].", " .
                            $schejuleDatas["section"].", " .
                            $rarryDetails['progress'].", " .
                            $schejuleDatas["court"].", " .
                            $schejuleDatas["homeTeamId"].", " .
                            $schejuleDatas["awayTeamId"].", " .
                            $schejuleDatas["ofisialAid"].", " .
                            $schejuleDatas["ofisialBid"].", " .
                            $setAntiwar.", " .
                            "NOW(), " .
                            "NULL);";
//print $ins_comp_sql;
                // データ登録
                $ins_comp_rs  = $connectionDBObj->Query($ins_comp_sql);

                if ( !$ins_comp_rs ) {
                    $sqlError = False;
                    $insertComment .= "コンプリートゲームの登録エラーです。<br />".$ins_comp_sql."<br />";
                } else {
                    $insertComment .= "コンプリートゲームを登録しました。<br />";
                }
            }

            // ------------------------------------------------------------------//
            if ($sqlError == True) {
                // チームスコアが登録済みかどうかチェック2
                if ($versusResultObj->checkSetGameRarryScore($rarryId, $gameId) == True) {

                    # スケジュールのデータ数の取得
                    $sql = "SELECT " .
                                   " `id` AS lastId " .
                                   " FROM `rarry_scores` " .
                                   " ORDER BY `id` DESC " .
                                   " LIMIT 1 " ;
//print $sql."<BR>";
                    $rs  = $connectionDBObj->Query($sql);
                    if(!$rs){ print "データ取得エラーです。"; return false; }
                    // データ数を取得
                    $num        = $connectionDBObj->GetRowCount($rs);
                    if($num > 0){
                        $data   = $connectionDBObj->FetchRow($rs);       // １行Ｇｅｔ
                        // スケジュール最終ID
                        $insertNumber2 = $data["lastId"];
                    }

                    // カラム部SQL
                    $ins_sql = "INSERT INTO `rarry_scores`  (" .
                               " `id`, " .
                               " `r_id`, " .
                               " `g_id`, " .
                               " `t_id`, " .
                               " `1st`, `2nd`, `3rd`, `4th`, " .
                               " `ot1`, `ot2`, `ot3`, `ot4`, `ot5`, `ot6`, `ot7`, `ot8`, `ot9`, " .
                               " `antiwar`, " .
                               " `seizure`, " .
                               " `special`, " .
                               " `created`, " .
                               " `modified` " .
                               " ) VALUES ";

                    $insertNumber2++;

                    // データ部SQL(HOME側)
                    $ins_sql .= "(" .
                                $insertNumber2.", " .
                                $rarryId.", " .
                                $gameId.", " .
                                $gameHomeTeamId.", " .
                                $home1st.", " . $home2nd.", " . $home3rd.", " . $home4th.", " .
                                $h_ot1.", " . $h_ot2.", " . $h_ot3.", " . $h_ot4.", " . $h_ot5.", " .
                                $h_ot6.", " . $h_ot7.", " . $h_ot8.", " . $h_ot9.", " .
                                $setAntiwar.", " .
                                "NULL, " .
                                "NULL, " .
                                "NOW(), " .
                                "NULL),";

                    $insertNumber2++;

                    // データ部SQL(AWAY側)
                    $ins_sql .= "(" .
                                $insertNumber2.", " .
                                $rarryId.", " .
                                $gameId.", " .
                                $gameAwayTeamId.", " .
                                $away1st.", " . $away2nd.", " . $away3rd.", " . $away4th.", " .
                                $a_ot1.", " . $a_ot2.", " . $a_ot3.", " . $a_ot4.", " . $a_ot5.", " .
                                $a_ot6.", " . $a_ot7.", " . $a_ot8.", " . $a_ot9.", " .
                                $setAntiwar.", " .
                                "NULL, " .
                                "NULL, " .
                                "NOW(), " .
                                "NULL);";

//print $ins_sql."<BR>";
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
                        //$connectionDBObj->Query( "ROLLBACK" );
                        $connectionDBObj->Query( "COMMIT" );
                        $insertComment .= "スコアを登録しました。<br />";
                    }
                }
            }
        }
        // 登録エラーがなければモードを変更
        if ($sqlError == True) {
            $mode = "change";
        }
    }
    /* --------------------------------------------------------------------
     * 個人スコア登録
     * ------------------------------------------------------------------*/
    if ($status == "homeIndiComplet" OR $status == "awayIndiComplet") {

        // 一旦ゲームに関する個人データをすべて削除する
        $deleteIndiData = deleteIndividualData($connectionDBObj, $rarryId, $gameId, $gameSide);

        if ($deleteIndiData["sqlError"] == True) {
            // 個人データの登録
            $insertIndiData = insertIndividualData($connectionDBObj, $rarryId, $gameId, $gameSide, $setIndiDatas);
        }
        //if ($insertIndiData["sqlError"] == True) $_SESSION["indiScoreInsertData"] = "ok";
        $status = "";
    }

    // スコア更新モード
    if ($mode == "change" AND ($status == "" OR $status == "complet" OR
                               $status == "homeIndiDataChange" OR $status == "awayIndiDataChange" OR
                               $status == "homeIndiConfilm" OR $status == "awayIndiConfilm" OR
                               $status == "homeIndiComplet" OR $status == "awayIndiComplet")) {
        // チーム対戦成績データ
        if ($versusDataObj->versusDetailData($gameId, $rarryId, "2008", $class) == True) {

            // 対戦データフラグ
            $versusDataFlag = True;

            // 対戦クラス
            $gameClass = $versusDataObj->getClass();
            // 対戦節
            $gameSection = $versusDataObj->getSection();
            // 試合会場名
            $setHallName = $versusDataObj->getHallName();
            // 使用コート名
            $hallCortName = $versusDataObj->getHallCortName();
            // チームID
            $gameHomeTeamId = $versusDataObj->getTeamId();
            // チーム名
            $gameHomeTeamName = $versusDataObj->getTeamName();
            // チーム合計スコア
            $teamScore = $versusDataObj->getTeamScore();
            // 対戦チームID
            $gameAwayTeamId = $versusDataObj->getVsTeamId();
            // 対戦チーム名
            $gameAwayTeamName = $versusDataObj->getVsTeamName();
            // 対戦チーム合計スコア
            $vsTeamScore = $versusDataObj->getVsTeamScore();
            // 対戦日
            $exeDate = $versusDataObj->getExeDate();
            // 対戦日(年)
            $gameYear = substr($versusDataObj->getExeYear(), 2, 2);
            // 対戦日(月)
            $gameMonth = $versusDataObj->getExeMonth();
            // 対戦日(日)
            $gameDay = $versusDataObj->getExeDay();
            // 不戦フラグ
            $antiwar = $versusDataObj->getAntiwar();
            // 没収試合フラグ
            $seizure = $versusDataObj->getSeizure();
            // 特殊ルール
            $special = $versusDataObj->getSpecial();
            // チームのクォータースコア
            $homeQuarterData = $versusDataObj->getQuarterData();
            // チームのクォータースコア
            $awayQuarterData = $versusDataObj->getVsQuarterData();

            $setGameDays = "20".$gameYear."-".$gameMonth."-".$gameDay;

            $homeTotalScore = array_sum($homeQuarterData);
            $awayTotalScore = array_sum($awayQuarterData);

            while (list ($key, $val) = each ($homeQuarterData)) {
                $key = str_replace('team_', 'home', $key);
                $$key = $val;
            }
            while (list ($key, $val) = each ($awayQuarterData)) {
                $key = str_replace('vs_', 'away', $key);
                $$key = $val;
            }
            // 勝敗
            if ($homeTotalScore > $awayTotalScore) {
                $homeShouhai = "<div style=\"color:red;font-weight:bold;\"><img src=\"../admin/img/versus/win.jpg\" alt=\"GAME-WIN\" /></div>";
                $awayShouhai = "<div style=\"color:blue;font-weight:bold;\"><img src=\"../admin/img/versus/lose.jpg\" alt=\"GAME-LOSE\" /></div>";
            } else if ($homeTotalScore == $awayTotalScore) {
                $homeShouhai = "<div style=\"color:green;font-weight:bold;\"><img src=\"../admin/img/versus/drow.jpg\" alt=\"GAME-DROW\" /></div>";
                $awayShouhai = "<div style=\"color:green;font-weight:bold;\"><img src=\"../admin/img/versus/drow.jpg\" alt=\"GAME-DROW\" /></div>";
            } else {
                $homeShouhai = "<div style=\"color:blue;font-weight:bold;\"><img src=\"../admin/img/versus/lose.jpg\" alt=\"GAME-LOSE\" /></div>";
                $awayShouhai = "<div style=\"color:red;font-weight:bold;\"><img src=\"../admin/img/versus/win.jpg\" alt=\"GAME-WIN\" /></div>";
            }
//print $antiwar." = ANTI<br />";
            // 不戦フラグが無ければ個人得点の取得
            // ゲームレポート・写真の取得：2014/10/01 追加
            if ($antiwar == 0) {

            	$getGameReportDatas = $versusDataObj->getGameReportDatas();
            	$gameReport = ($getGameReportDatas['report'] != "") ? rawurldecode($getGameReportDatas['report']): '';
            	$gameReportView = nl2br($gameReport);
            	$bestHigherHome = ($getGameReportDatas['home_best'] != "") ? rawurldecode($getGameReportDatas['home_best']): '';
            	$bestHigherHomeView = ($bestHigherHome);
            	$bestHigherAway = ($getGameReportDatas['away_best'] != "") ? rawurldecode($getGameReportDatas['away_best']): '';
            	$bestHigherAwayView = ($bestHigherAway);
            	$gameReportPicture1 = (!is_null($getGameReportDatas['picture1']) and $getGameReportDatas['picture1'] != "") ? $getGameReportDatas['picture1']: null;
            	$gameReportPicture2 = (!is_null($getGameReportDatas['picture2']) and $getGameReportDatas['picture2'] != "") ? $getGameReportDatas['picture2']: null;
            	$gameReportPicture3 = (!is_null($getGameReportDatas['picture3']) and $getGameReportDatas['picture3'] != "") ? $getGameReportDatas['picture3']: null;
            	$gameReportPicture4 = (!is_null($getGameReportDatas['picture4']) and $getGameReportDatas['picture4'] != "") ? $getGameReportDatas['picture4']: null;
            	$gameReportPicture5 = (!is_null($getGameReportDatas['picture5']) and $getGameReportDatas['picture5'] != "") ? $getGameReportDatas['picture5']: null;
            	$gameReportPicture6 = (!is_null($getGameReportDatas['picture6']) and $getGameReportDatas['picture6'] != "") ? $getGameReportDatas['picture6']: null;
            	$gameReportPicture7 = (!is_null($getGameReportDatas['picture7']) and $getGameReportDatas['picture7'] != "") ? $getGameReportDatas['picture7']: null;
            	$gameReportPicture8 = (!is_null($getGameReportDatas['picture8']) and $getGameReportDatas['picture8'] != "") ? $getGameReportDatas['picture8']: null;
            	$gameReportPicture9 = (!is_null($getGameReportDatas['picture9']) and $getGameReportDatas['picture9'] != "") ? $getGameReportDatas['picture9']: null;
            	$gameReportPicture10 = (!is_null($getGameReportDatas['picture10']) and $getGameReportDatas['picture10'] != "") ? $getGameReportDatas['picture10']: null;
            	$gameReportPicture1t = (!is_null($getGameReportDatas['picture1_thumb']) and $getGameReportDatas['picture1_thumb'] != "") ? $getGameReportDatas['picture1_thumb']: null;
            	$gameReportPicture2t = (!is_null($getGameReportDatas['picture2_thumb']) and $getGameReportDatas['picture2_thumb'] != "") ? $getGameReportDatas['picture2_thumb']: null;
            	$gameReportPicture3t = (!is_null($getGameReportDatas['picture3_thumb']) and $getGameReportDatas['picture3_thumb'] != "") ? $getGameReportDatas['picture3_thumb']: null;
            	$gameReportPicture4t = (!is_null($getGameReportDatas['picture4_thumb']) and $getGameReportDatas['picture4_thumb'] != "") ? $getGameReportDatas['picture4_thumb']: null;
            	$gameReportPicture5t = (!is_null($getGameReportDatas['picture5_thumb']) and $getGameReportDatas['picture5_thumb'] != "") ? $getGameReportDatas['picture5_thumb']: null;
            	$gameReportPicture6t = (!is_null($getGameReportDatas['picture6_thumb']) and $getGameReportDatas['picture6_thumb'] != "") ? $getGameReportDatas['picture6_thumb']: null;
            	$gameReportPicture7t = (!is_null($getGameReportDatas['picture7_thumb']) and $getGameReportDatas['picture7_thumb'] != "") ? $getGameReportDatas['picture7_thumb']: null;
            	$gameReportPicture8t = (!is_null($getGameReportDatas['picture8_thumb']) and $getGameReportDatas['picture8_thumb'] != "") ? $getGameReportDatas['picture8_thumb']: null;
            	$gameReportPicture9t = (!is_null($getGameReportDatas['picture9_thumb']) and $getGameReportDatas['picture9_thumb'] != "") ? $getGameReportDatas['picture9_thumb']: null;
            	$gameReportPicture10t = (!is_null($getGameReportDatas['picture10_thumb']) and $getGameReportDatas['picture10_thumb'] != "") ? $getGameReportDatas['picture10_thumb']: null;
            
                $antiwarGame = False;

                // 個人成績クラスの生成
                $homeIndiScoreObj = new indiScoreData();
                $awayIndiScoreObj = new indiScoreData();

                // HOME側の個人得点が登録されているかチェック
                if ($homeIndiScoreObj->checkIndiGameScore($rarryId, $gameHomeTeamId, $gameId) == True) {
//print checkComment." = HomeIndiScoreSetData<br />";
                    if ($status == "homeIndiDataChange") $homePartSettingForm = "    <th class=\"indi_th\">出場</th>";
                    $homeIndiGameCheck = True;
                } else {
//print checkComment." = HomeIndiScoreNoData<br />";
                    if ($status != "homeIndiConfilm") $homePartSettingForm = "    <th class=\"indi_th\">出場</th>";
                    $homeIndiGameCheck = False;
                }
                // AWAY側の個人得点が登録されているかチェック
                if ($awayIndiScoreObj->checkIndiGameScore($rarryId, $gameAwayTeamId, $gameId) == True) {
//$checkComment .= "AwayIndiScoreSetData<br />";
                    if ($status == "awayIndiDataChange") $awayPartSettingForm = "    <th class=\"indi_th\">出場</th>";
                    $awayIndiGameCheck = True;
                } else {
//$checkComment .= "AwayIndiScoreNoData<br />";
                    if ($status != "awayIndiConfilm") $awayPartSettingForm = "    <th class=\"indi_th\">出場</th>";
                    $awayIndiGameCheck = False;
                }
                // ホームチーム個人成績データ
                if ($homeIndiScoreObj->adminIndividualGameScore($rarryId, $gameId, $gameHomeTeamId, $rarryDetails['progress']) == True) {
                    $homeIndiScoreData = $homeIndiScoreObj->getIndiGameData();
                }
                // アウェイチーム個人成績データ
                if ($awayIndiScoreObj->adminIndividualGameScore($rarryId, $gameId, $gameAwayTeamId, $rarryDetails['progress']) == True) {
                    $awayIndiScoreData = $awayIndiScoreObj->getIndiGameData();
                }
            } else {
                $antiwarGame = True;
            }
        }
    } else if ($mode == "input") {

        // スケジュールデータ
        if ($schejuleDataObj->SchejuleDatas($teamDataObj, $rarryId, $gameId, $team_id, $serch_month, $class, "setScore") == True) {
            // 登録しているスケジュール
            $selectSchejuleData = $schejuleDataObj->getSchejuleData();
            // 試合会場データオブジェクトの取得
            if ($mHallDataObj->selectCoatHallData($selectSchejuleData[0]["hall"]) ==True) {
                $coatData  = $mHallDataObj->getCoatHallData();
                $setHallName = $coatData["h_name"];
                $hallCortName = $coatData["cort_name"];
            }
            // 対戦年
            $gameYear = $selectSchejuleData[0]["year"];
            // 対戦月
            $gameMonth = $selectSchejuleData[0]["month"];
            // 対戦日
            $gameDay = $selectSchejuleData[0]["day"];
            // クラス
            $gameBlock = $selectSchejuleData[0]["class"];
            // 対戦節
            $gameSection = $selectSchejuleData[0]["section"];
            // 試合会場のコート
            $gameCort = $selectSchejuleData[0]["hall"];
            // HOMEチームID
            $gameHomeTeamId = $selectSchejuleData[0]["homeTeamId"];
            // HOMEチーム名
            $gameHomeTeamName = $selectSchejuleData[0]["home_team"];
            // AWAYチームID
            $gameAwayTeamId = $selectSchejuleData[0]["awayTeamId"];
            // AWAYチーム名
            $gameAwayTeamName = $selectSchejuleData[0]["away_team"];

            $setGameDays = "20".$gameYear."-".$gameMonth."-".$gameDay;
        } else {
            $dataError = False;
        }
    }

    /**
     * 概要 : 個人のゲームスコアを削除する
     *
     * 説明 : 個人のゲームスコアを削除する
     *
     * @param $rarryId    大会ID
     * @param $gameId     ゲームID
     * @param $gameSide   HOME or AWAY
    */
    function deleteIndividualData($connectionDBObj, $rarryId, $gameId, $gameSide) {

        $delData["sqlError"] = True;

        ($gameSide == "home") ? $setSide = 0 : $setSide = 1;

        // カラム部SQL
        $del_sql = "DELETE FROM `individual_scores` " .
                   " WHERE      `game_id` = " . $gameId . " " .
                   "  AND       `side` = " . $setSide . " " ;

//print $del_sql."<BR>";
        // データ登録
        $del_rs  = $connectionDBObj->Query($del_sql);

        if ( !$del_rs ) {
            $delData["sqlError"] = False;
            $delData["deleteComment"] .= "個人スコアのデータベース削除に失敗しました。<br />";
        }

        return $delData;
    }

    /**
     * 概要 : 個人のゲームスコアを登録する
     *
     * 説明 : 個人のゲームスコアを登録する
     *
     * @param $rarryId    大会ID
     * @param $teamId     チームID
     * @param $gameId     ゲームID
    */
    function insertIndividualData($connectionDBObj, $rarryId, $gameId, $gameSide, $setIndiDatas) {

        $insertData["sqlError"] = True;

        ($gameSide == "home") ? $setSide = 0 : $setSide = 1;

        // カラム部SQL
        $ins_sql = "INSERT INTO `individual_scores`  (" .
                   " `r_id`, " .
                   " `m_id`, " .
                   " `game_id`, " .
                   " `side`, " .
                   " `2point_callenge`, `2point_success`, " .
                   " `3point_callenge`, `3point_success`, " .
                   " `ft_callenge`, `ft_success`, " .
                   " `dunk`, `assist`, " .
                   " `of_rebound`, `df_rebound`, " .
                   " `steal`, `block`, `turn_over`, `faul`, " .
                   " `play_time`, " .
                   " `created`, " .
                   " `modified` " .
                   " ) VALUES ";

        $setMemberNum = count($setIndiDatas);

        foreach ($setIndiDatas AS $setMemberId => $setDatas) {

            $foaeachCount++;

            // データ部SQL(HOME側)
            $ins_sql .= "(" .
                        $rarryId.", " .
                        $setMemberId . ", " .
                        $gameId.", " .
                        $setSide.", " .
                        " 0, ".$setDatas["2point_success"].", " .
                        " 0, ".$setDatas["3point_success"].", " .
                        " 0, ".$setDatas["ft_success"].", " .
                        " 0, ".$setDatas["asist"].", " .
                        " ".$setDatas["of_rebound"].", ".$setDatas["de_rebound"].", " .
                        " ".$setDatas["steal"].", ".$setDatas["block_shot"].", 0, 0, " .
                        " '00:00:00', " .
                        " NOW(), " .
                        " NULL " .
                        ")" ;
            ($setMemberNum == $foaeachCount) ? $ins_sql .= ";" : $ins_sql .= ",";
        }
//print $ins_sql."<BR>";
            $setData["insertComment"] .= "個人スコアのデータベース登録に失敗しました。<br />";
        // トランザクション開始
        $tran = $connectionDBObj->Query("BEGIN");

        // データ登録
        $ins_rs  = $connectionDBObj->Query($ins_sql);

        if ( !$ins_rs ) {
            $insertData["sqlError"] = False;
            //print "ROLLBACK<BR>";
            $connectionDBObj->Query( "ROLLBACK" );
            $setData["insertComment"] .= "個人スコアのデータベース登録に失敗しました。<br />";
        } else {
            $setData["sqlError"] = False;
            //print "COMMIT<BR>";
            //$connectionDBObj->Query( "ROLLBACK" );
            $connectionDBObj->Query( "COMMIT" );
            $insertData["insertComment"] .= "個人スコアを登録しました。<br />";
        }
        return $insertData;
    }


/*
print nl2br(print_r($hallName,true));
print nl2br(print_r($vsQuarterData,true));
print $teamId." = チームID<BR>";
print $teamName." = チーム名<BR>";
print $teamScore." = チーム合計スコア<BR>";
print $vsTeamId." = 対戦チームID<BR>";
print $vsTeamName." = 対戦チーム名<BR>";
print $vsTeamScore." = 対戦チーム合計スコア<BR>";
print $exeDate." = 対戦日<BR>";
print $antiwar." = 不戦フラグ<BR>";
print $seizure." = 没収試合フラグ<BR>";
print $special." = 特殊ルール<BR>";
#print nl2br(print_r($selectSchejuleData,true));
print "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
*/
?>
<?php include_once "block/header.php" ?>
<script language="javascript" type="text/javascript">
//<![CDATA[
<!--

// チーム合計得点の計算
function scoreCalculation(form){

  form.homeTotalScore.value = eval(form.home1st.value)+eval(form.home2nd.value)+eval(form.home3rd.value)+eval(form.home4th.value);
  form.awayTotalScore.value = eval(form.away1st.value)+eval(form.away2nd.value)+eval(form.away3rd.value)+eval(form.away4th.value);
  return false;
}

// 個人合計得点の計算
function indiScoreCalculation(form, side, numbers){

  var threePointSum = 0;
  var twoPointSum = 0;
  var freeTrrowSum = 0;
  var totalSum = 0;
  var offenceReboundSum = 0;
  var defenceReboundSum = 0;
  var totalReboundSum = 0;
  var assistSum = 0;
  var stealSum = 0;
  var blockShotSum = 0;
  var threepoint = eval(form.elements[side+'SetIndiScoreData[ThreePoint'+numbers+']'].value) * 3;
  var twopoint = eval(form.elements[side+'SetIndiScoreData[TwoPoint'+numbers+']'].value) * 2;
  var freethrow = eval(form.elements[side+'SetIndiScoreData[FreeTrrow'+numbers+']'].value);
<?php
  	if (in_array($gameClass, $indiScoreMoreDetailClass)) {
?>
  var offencerebound = eval(form.elements[side+'SetIndiScoreData[OffenceRebound'+numbers+']'].value);
  var defencerebound = eval(form.elements[side+'SetIndiScoreData[DefenceRebound'+numbers+']'].value);
<?php
	}
?>

  form.elements[side+'SetIndiScoreData[IndiTotalScore'+numbers+']'].value = threepoint + twopoint + freethrow;
<?php
  	if (in_array($gameClass, $indiScoreMoreDetailClass)) {
?>
  //form.elements[side+'SetIndiScoreData[IndiTotalRebound'+numbers+']'].value = offencerebound + defencerebound;
<?php
	}
?>
<?php if (in_array($gameClass, $indiScoreMoreDetailClass)) { ?>
	// 合計が1点以上あれば自動的に出場にする
	if (
		(form.elements[side+'SetIndiScoreData[IndiTotalScore'+numbers+']'].value > 0)
		|| (form.elements[side+'SetIndiScoreData[OffenceRebound'+numbers+']'].value > 0)
		|| (form.elements[side+'SetIndiScoreData[DefenceRebound'+numbers+']'].value > 0)
		|| (form.elements[side+'SetIndiScoreData[Assist'+numbers+']'].value > 0)
		|| (form.elements[side+'SetIndiScoreData[Steal'+numbers+']'].value > 0)
		|| (form.elements[side+'SetIndiScoreData[BlockShot'+numbers+']'].value > 0)
		) {
		form.elements[side+'SetMember['+numbers+']'].checked = true;
	} else {
		form.elements[side+'SetMember['+numbers+']'].checked = false;
	}
<?php } else { ?>
	// 合計が1点以上あれば自動的に出場にする
	if (form.elements[side+'SetIndiScoreData[IndiTotalScore'+numbers+']'].value > 0) {
		form.elements[side+'SetMember['+numbers+']'].checked = true;
	} else {
		form.elements[side+'SetMember['+numbers+']'].checked = false;
	}
<?php } ?>

  // 3P・2P・FT合計本数・総得点
<?php
$indiNum = ($status == "homeIndiComplet") ? count($homeIndiScoreData) : count($awayIndiScoreData);
//print nl2br(print_r($homeIndiScoreData, true));
  for ($i=0; $i<$indiNum; $i++) {
?>
  threePointSum += eval(form.elements[side+'SetIndiScoreData[ThreePoint<?php print $i; ?>]'].value);
  twoPointSum += eval(form.elements[side+'SetIndiScoreData[TwoPoint<?php print $i; ?>]'].value);
  freeTrrowSum += eval(form.elements[side+'SetIndiScoreData[FreeTrrow<?php print $i; ?>]'].value);
  totalSum += eval(form.elements[side+'SetIndiScoreData[IndiTotalScore<?php print $i; ?>]'].value);
<?php
  	if (in_array($gameClass, $indiScoreMoreDetailClass)) {
?>
  offenceReboundSum += eval(form.elements[side+'SetIndiScoreData[OffenceRebound<?php print $i; ?>]'].value);
  defenceReboundSum += eval(form.elements[side+'SetIndiScoreData[DefenceRebound<?php print $i; ?>]'].value);
  assistSum += eval(form.elements[side+'SetIndiScoreData[Assist<?php print $i; ?>]'].value);
  stealSum += eval(form.elements[side+'SetIndiScoreData[Steal<?php print $i; ?>]'].value);
  blockShotSum += eval(form.elements[side+'SetIndiScoreData[BlockShot<?php print $i; ?>]'].value);
<?php
  	}
  }
?>
  if (side == 'home') {
    document.setHomeIndiScore.homeIndiThreePointSum.value = threePointSum;
    document.setHomeIndiScore.homeIndiTwoPointSum.value = twoPointSum;
    document.setHomeIndiScore.homeIndiFreeTrrowSum.value = freeTrrowSum;
    document.setHomeIndiScore.homeIndiTotalScoreSum.value = totalSum;
<?php
	if (in_array($gameClass, $indiScoreMoreDetailClass)) {
?>
    document.setHomeIndiScore.homeIndiOffenceReboundSum.value = offenceReboundSum;
    document.setHomeIndiScore.homeIndiDefenceReboundSum.value = defenceReboundSum;
    document.setHomeIndiScore.homeIndiAssistSum.value = assistSum;
    document.setHomeIndiScore.homeIndiStealSum.value = stealSum;
    document.setHomeIndiScore.homeIndiBlockShotSum.value = blockShotSum;
<?php
	}
?>
  } else {
    document.setAwayIndiScore.awayIndiThreePointSum.value = threePointSum;
    document.setAwayIndiScore.awayIndiTwoPointSum.value = twoPointSum;
    document.setAwayIndiScore.awayIndiFreeTrrowSum.value = freeTrrowSum;
    document.setAwayIndiScore.awayIndiTotalScoreSum.value = totalSum;
<?php
  	if (in_array($gameClass, $indiScoreMoreDetailClass)) {
?>
    document.setAwayIndiScore.awayIndiOffenceReboundSum.value = offenceReboundSum;
    document.setAwayIndiScore.awayIndiDefenceReboundSum.value = defenceReboundSum;
    document.setAwayIndiScore.awayIndiAssistSum.value = assistSum;
    document.setAwayIndiScore.awayIndiStealSum.value = stealSum;
    document.setAwayIndiScore.awayIndiBlockShotSum.value = blockShotSum;
<?php
  	}
?>
  }

  return false;
}

//-->
//]]>
</script>

<body>
<div id="main">
<!-- メインコンテンツ -->
<?php include_once "block/menu.php" ?>
  <div id="middle">
<!-- ページ内サブコンテンツ -->
<?php include_once "block/contents.php"; ?>
<?php
print <<<EOF
    <div id="center-column">
      <div class="top-bar">
        <h1>SCORE登録{$subTitle}画面</h1>
      </div>
      <br />
      <div class="select-bar">&nbsp;</div>\n
EOF;
if ($dataError == True) {
print <<<EOF
<form name="setTeamScore" method="post" action="{$_SERVER["PHPSELF"]}" onsubmit="return Validator.submit(this)">
<div class="RESULT">
  <div class="GAME">
    <div class="DAYS">{$rarryName}&nbsp;{$rarrySubName}&nbsp;{$className}&nbsp;[&nbsp;第&nbsp;{$gameSection}&nbsp;節&nbsp;]</div>\n
EOF;
  // スコア更新モード
  if ($mode == "change") {
print <<<EOF
    <div style="width:600px;">
      <div style="clear:left;float:left;width:400px;">
        <div>GameNo ：{$gameId}</div>
        <div>対戦日 ：20{$gameYear}年&nbsp;{$gameMonth}&nbsp;月&nbsp;{$gameDay}&nbsp;日</div>
        <div>会　場　：{$setHallName}&nbsp;{$hallCortName}</div>
      </div>\n
EOF;
    if ($status == "" OR $status == "complet" OR $status == "homeIndiComplet" OR $status == "awayIndiComplet") {
print <<<EOF
      <div style="float:left;">
        <input type="submit" value="スコア修正" />
        　<input type="button" value="一覧に戻る" onclick="location='main_schejule.php?serchMode={$serchMode}'" />
      </div>\n
EOF;
    } else if ($status == "teamDataInput" OR $status == "confilm" OR $status == "homeIndiConfilm" OR $status == "awayIndiConfilm") {
print <<<EOF
      <div style="float:left;">
        <input type="submit" value="結果へ戻る" onclick="sendTeam('');" />
      </div>\n
EOF;
    }
print <<<EOF
    </div>\n
EOF;
  // スコア新規登録モード
  } else {
//      <input type="text" size="2" maxlength="2" name="chDay" value="$gameDay" class="imenumeric" disabled="disabled" onblur="Validator.check(this, 'num1-31 count-2')" />&nbsp;日
print <<<EOF
    <div style="width:100%;">
      <span style="width:400px;">
      対戦日 ：20{$gameYear}年
      <input type="text" size="2" maxlength="2" name="chMonth" value="$gameMonth" class="imenumeric" disabled="disabled" />&nbsp;月
      <input type="text" size="2" maxlength="2" name="chDay" value="$gameDay" class="imenumeric" disabled="disabled" />&nbsp;日
      </span>
      <span style="padding-left:25px;">
        <input type="checkbox" name="chDays" value="1" onclick="changeDates(this,'chMonth','chDay');" />&nbsp;日付変更はチェック$chDays
      </span>
    <div>
    <div>会　場　：{$setHallName}&nbsp;{$hallCortName}
      <span style="padding-left:25px;">
        <input type="checkbox" name="chDays" value="1" onclick="changeDates(this,'a','b');" disabled="disabled" />&nbsp;会場変更はチェック
      </span>
    <div>\n
EOF;
  }
print <<<EOF
  </div>
</div>

<!-- 得点表示 -->
<table id="GAMEDATA">
  <thead>
  <tr style="text-align:center;">
    <td>HOME TEAM</td>
    <td style="width:65px;">SCORE</td>
    <td>QUARTER</td>
    <td style="width:65px;">SCORE</td>
    <td>AWAY TEAM</td>
  </tr>
  </thead>
  <tbody>
  <tr>
    <td class="HomeTeamName">
      <span style="font-size:120%;">{$gameHomeTeamName}</span>
      {$homeShouhai}
    </td>\n
EOF;
    // スコア更新モード
    if ($mode == "change" AND ($status == "" OR $status == "complet" OR $status == "homeIndiConfilm" OR $status == "awayIndiConfilm" OR $status == "homeIndiComplet" OR $status == "awayIndiComplet")) {
print <<<EOF
    <td style="{$homeWD}"><span style="font-size:180%;font-weight:bold;">{$homeTotalScore}</span></td>
    <td class="quarterBox">
<!-- クォーター間の得点表示 -->
      <table id="QuarterData">
        <tr>
          <td class="quarter" style="color:#C36;font-size:130%;">{$home1st}</td>
          <td class="quarterView">1st</td>
          <td class="quarter" style="color:#C36;font-size:130%;">{$away1st}</td>
        </tr>
        <tr>
          <td class="quarter" style="color:#000;font-size:130%;">{$home2nd}</td>
          <td class="quarterView">2nd</td>
          <td class="quarter" style="color:#000;font-size:130%;">{$away2nd}</td>
        </tr>
        <tr>
          <td class="quarter" style="color:#C36;font-size:130%;">{$home3rd}</td>
          <td class="quarterView">3rd</td>
          <td class="quarter" style="color:#C36;font-size:130%;">{$away3rd}</td>
        </tr>
        <tr>
          <td class="quarter" style="color:#000;font-size:130%;">{$home4th}</td>
          <td class="quarterView">4th</td>
          <td class="quarter" style="color:#000;font-size:130%;">{$away4th}</td>
        </tr>
      </table>
    </td>
    <td style="{$awayWD}"><span style="font-size:180%;font-weight:bold;">{$awayTotalScore}</span></td>\n
EOF;
    // スコア新規登録モード
    } else {
print <<<EOF
    <td class="{$homeWD}"><input type="text" name="homeTotalScore" size="3" readonly="readonly" class="totalscore" value="{$homeTotalScore}" /></td>
    <td class="quarterBox">
<!-- クォーター間の得点表示 -->
      <table id="QuarterData">
        <tr>
          <td class="quarter" style="color:#C36;"><input type="text" name="home1st" size="3" maxlength="3" class="imenumeric" onblur="scoreCalculation(this.form);Validator.check(this, 'num');" style="text-align:right" value="$home1st" onfocus="this.select()" tabindex="1" {$fmDisabled} /></td>
          <td class="quarterView">1st</td>
          <td class="quarter" style="color:#C36;"><input type="text" name="away1st" size="3" maxlength="3" class="imenumeric" onblur="scoreCalculation(this.form);Validator.check(this, 'num');" style="text-align:right" value="$away1st" onfocus="this.select()" tabindex="5" {$fmDisabled} /></td>
        </tr>
        <tr>
          <td class="quarter" style="color:#000;"><input type="text" name="home2nd" size="3" maxlength="3" class="imenumeric" onblur="scoreCalculation(this.form);Validator.check(this, 'num');" style="text-align:right" value="$home2nd" onfocus="this.select()" tabindex="2" {$fmDisabled} /></td>
          <td class="quarterView">2nd</td>
          <td class="quarter" style="color:#000;"><input type="text" name="away2nd" size="3" maxlength="3" class="imenumeric" onblur="scoreCalculation(this.form);Validator.check(this, 'num');" style="text-align:right" value="$away2nd" onfocus="this.select()" tabindex="6" {$fmDisabled} /></td>
        </tr>
        <tr>
          <td class="quarter" style="color:#C36;"><input type="text" name="home3rd" size="3" maxlength="3" class="imenumeric" onblur="scoreCalculation(this.form);Validator.check(this, 'num');" style="text-align:right" value="$home3rd" onfocus="this.select()" tabindex="3" {$fmDisabled} /></td>
          <td class="quarterView">3rd</td>
          <td class="quarter" style="color:#C36;"><input type="text" name="away3rd" size="3" maxlength="3" class="imenumeric" onblur="scoreCalculation(this.form);Validator.check(this, 'num');" style="text-align:right" value="$away3rd" onfocus="this.select()" tabindex="7" {$fmDisabled} /></td>
        </tr>
        <tr>
          <td class="quarter" style="color:#000;"><input type="text" name="home4th" size="3" maxlength="3" class="imenumeric" onblur="scoreCalculation(this.form);Validator.check(this, 'num');" style="text-align:right" value="$home4th" onfocus="this.select()" tabindex="4" {$fmDisabled} /></td>
          <td class="quarterView">4th</td>
          <td class="quarter" style="color:#000;"><input type="text" name="away4th" size="3" maxlength="3" class="imenumeric" onblur="scoreCalculation(this.form);Validator.check(this, 'num');" style="text-align:right" value="$away4th" onfocus="this.select()" tabindex="8" {$fmDisabled} /></td>
        </tr>
      </table>
    </td>
    <td class="{$awayWD}"><input type="text" name="awayTotalScore" size="3" readonly="readonly" class="totalscore" value="{$awayTotalScore}" /></td>\n
EOF;
    } // スコア新規登録モード<END>
print <<<EOF
    <td class="AwayTeamName">
      <span style="font-size:120%;">{$gameAwayTeamName}</span>
      {$awayShouhai}
    </td>
  </tr>
  <tr>
    <td colspan="5" class="antiwarLine">\n
EOF;
if ($teamFmError == False) {
print <<<EOF
      <div style="float:left;width:300px;color:red;font-weight:bold;">
        <ul>\n
EOF;
  for ($i=0; $i<count($homeErrorValue); $i++) {
    print $homeErrorValue;
  }
print <<<EOF

        </ul>
      </div>
      <div style="float:right;width:230px;color:red;font-weight:bold;">
        <ul>\n
EOF;
  for ($i=0; $i<count($awayErrorValue); $i++) {
    print $awayErrorValue;
  }
print <<<EOF
        </ul>
      </div>\n
EOF;
}
print <<<EOF
      <div id="mydiv" style="display:none">
        <div style="padding-left:210px"><input type="radio" name="antiwar" value="home" tabindex="10" />
          <span style="padding:0px 30px 0px 30px;">不戦敗チーム</span>
          <input type="radio" name="antiwar" value="away" tabindex="11" />
        </div>
        <div style="padding-left:210px">
          <input type="text" name="antiwarScore" size="2" value="{$antiwarScore}" tabindex="12" />
          <span style="padding-left:25px;">不戦勝チームの得点</span>
        </div>
      </div>
    </td>
  </tr>\n
EOF;

// スコア更新モード
if ($mode == "change" AND ($status == "" OR $status == "complet")) {
print <<<EOF
  </tbody>
</table>
<input type="hidden" name="status" value="teamDataInput" />
<input type="hidden" name="home1st" value="{$home1st}" />
<input type="hidden" name="home2nd" value="{$home2nd}" />
<input type="hidden" name="home3rd" value="{$home3rd}" />
<input type="hidden" name="home4th" value="{$home4th}" />
<input type="hidden" name="away1st" value="{$away1st}" />
<input type="hidden" name="away2nd" value="{$away2nd}" />
<input type="hidden" name="away3rd" value="{$away3rd}" />
<input type="hidden" name="away4th" value="{$away4th}" />
<input type="hidden" name="homeTotalScore" value="{$homeTotalScore}" />
<input type="hidden" name="awayTotalScore" value="{$awayTotalScore}" />
<input type="hidden" name="antiwar" value="{$antiwar}" />
<input type="hidden" name="antiwarGames" value="{$antiwarGames}" />\n
EOF;
// スコア新規登録モード
} else {
  // 登録確認モード
  if ($status == "confilm") {

print <<<EOF
  <tr>
    <td colspan="5" style="text-align:center">\n
EOF;
    // フォームエラーがなければOKボタン
    if ($teamFmError == True) {
print <<<EOF
      <input type="submit" value="ＯＫ" tabindex="13" />\n
EOF;
    }
print <<<EOF
      <span style="padding:0px 10px;">&nbsp;</span>
      <input type="button" value="やり直す" onclick="sendTeam('teamDataInput');" tabindex="14" />
    </td>
  </tr>
  </tbody>
</table>

<input type="hidden" name="status" value="complet" />
<input type="hidden" name="home1st" value="{$home1st}" />
<input type="hidden" name="home2nd" value="{$home2nd}" />
<input type="hidden" name="home3rd" value="{$home3rd}" />
<input type="hidden" name="home4th" value="{$home4th}" />
<input type="hidden" name="away1st" value="{$away1st}" />
<input type="hidden" name="away2nd" value="{$away2nd}" />
<input type="hidden" name="away3rd" value="{$away3rd}" />
<input type="hidden" name="away4th" value="{$away4th}" />
<input type="hidden" name="antiwar" value="{$antiwar}" />
<input type="hidden" name="antiwarGames" value="{$antiwarGames}" />\n
EOF;

  } else if ($status == "" OR $status == "teamDataInput") {

print <<<EOF
  <tr>
    <td style="text-align:center"><input type="checkbox" name="antiwarGames" value="ON" onclick="toggleDiv('mydiv');" tabindex="9" />不戦勝敗ゲームの時はチェック
    <td>&nbsp;</td>
    <td style="text-align:center"><input type="submit" value="スコア登録" tabindex="13" /></td>
    <td colspan="2"><span style="padding:0px 10px;"><input type="reset" value="リセット" tabindex="14" /></span></td>
  </tr>
  </tbody>
</table>
<input type="hidden" name="serchMode" value="{$serchMode}" />
<input type="hidden" name="mode" value="{$mode}" />
<input type="hidden" name="status" value="confilm" />\n
EOF;
  } else {
print <<<EOF
  </tr>
  </tbody>
</table>\n
EOF;
  } // 登録確認モード<END>
} // スコア新規登録モード<END>
if ($mode == "input") {
print <<<EOF
</div>
</div>
</div>
</div>\n
EOF;
}
print <<<EOF
<input type="hidden" name="serchMode" value="{$serchMode}" />
<input type="hidden" name="mode" value="{$mode}" />
<input type="hidden" name="rarryId" value="{$rarryId}" />
<input type="hidden" name="gameId" value="{$gameId}" />
<input type="hidden" name="gameBlock" value="{$gameBlock}" />
<input type="hidden" name="gameSection" value="{$gameSection}" />
<input type="hidden" name="gameCort" value="{$gameCort}" />
<input type="hidden" name="gameHomeTeamName" value="{$gameHomeTeamName}" />
<input type="hidden" name="gameAwayTeamName" value="{$gameAwayTeamName}" />
<input type="hidden" name="gameHomeTeamId" value="{$gameHomeTeamId}" />
<input type="hidden" name="gameAwayTeamId" value="{$gameAwayTeamId}" />
<input type="hidden" name="gameYear" value="{$gameYear}" />
<input type="hidden" name="gameMonth" value="{$gameMonth}" />
<input type="hidden" name="gameDay" value="{$gameDay}" />
</form>

<!--個人成績の登録 -->
<br />
<div class="select-bar">&nbsp;</div>\n
EOF;

//-----------------------------------------------------------
// ゲームレポート
//-----------------------------------------------------------
print <<<EOF
<div id="gamereport">
	{$gameReportView}
	<div id="pitureArea">
		<div class="pitures">
			{$gameReportPicture1_thumb}
		</div>
		<div class="pitures">
			{$gameReportpicture2_thumb}
		</div>
		<div class="pitures">
			{$gameReportpicture3_thumb}
		</div>
	</div>
	<input type="button" value="ゲームレポート登録" id="gameReportBtn" tabindex="20" />
	<div id="dialog_gameReport">
		<table>
			<tbody>
				<tr>
					<th>上位入賞者：{$gameHomeTeamName}</th><th>上位入賞者：{$gameAwayTeamName}</th>
				</tr>
				<tr>
					<td><textarea id="txt_best_higher_home" name="best_higher_home" cols="30" rows="3">{$bestHigherHomeView}</textarea></td>
					<td><textarea id="txt_best_higher_away" name="best_higher_away" cols="30" rows="3">{$bestHigherAwayView}</textarea></td>
				</tr>
				<tr>
					<th colspan="2">ゲームレポート</th>
				</tr>
				<tr>
					<td colspan="2"><textarea id="txt_report" name="report" cols="80" rows="5">{$gameReport}</textarea></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div id="dialog-complete" class="div_dialog" title="完了">
		ゲームレポートの登録が完了しました。
	</div>
	<div id="dialog-error" class="div_dialog" title="エラー">
		ゲームレポートの登録に失敗しました。
	</div>
	<div id="dialog-incomplete" class="div_dialog" title="通信エラー">
		Ajax通信に失敗しました。
	</div>
	<div class="lockOff"><div></div></div>
	<div class="select-bar">&nbsp;</div>
</div>
EOF;

if ($status == "complet") {
print "<div style=\"padding-left:30px;font-weight:bold;font-size:120%;color:#D8480B;\">".$insertComment."</div>\n";
}
print $indiComments;

//-----------------------------------------------------------
// 個人スタッツデータ
//-----------------------------------------------------------
if ($versusDataFlag == True AND $antiwarGame == False) {

if (in_array($gameClass, $indiScoreMoreDetailClass)) {
	$table_width = "700px";
	$special_style = "class-one";
	$colspan['table01'] = '4';
} else {
	$table_width = "366px";
	$special_style = "";
	$special_style = "class-one";
	$colspan['table01'] = '5';
}

print <<<EOF
<!-- 個人成績の表示欄 -->
<h2><span style="text-indent:10px;font-size:80%;font-weight:bold;">INDI個人スタッツ</span></h2>
<table style="padding:0px;width:747px;">
<tr>
<td style="margin:0px;padding:0px;border:none;vertical-align:top;width:{$table_width};">
<table class="indi_data {$special_style}">
  <thead>
  <tr>
    <th colspan="20" style="background-color:#FFF;text-align:left;">
      <div class="indi_team">{$gameHomeTeamName}</div>
    </th>
  </tr>
  </thead>
  <form name="setHomeIndiScore" method="post" action="{$_SERVER["PHPSELF"]}">
  <tbody>
  <tr>
    <th class="indi_th" colspan="2">TEAM</th>
    <th class="indi_th" colspan="{$colspan['table01']}">SCORE</th>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
	<th class="indi_th" colspan="5">REBOUND・ASSIST・STEAL・BLOCK</th>
EOF;
if ($homeIndiGameCheck == True AND ($status == "" OR $status == "complet" OR preg_match ("/away/i", $status))) {
} else {
print <<<EOF
	<th class="indi_th">&nbsp;</th>
EOF;
}
print <<<EOF
EOF;
}
print <<<EOF
  </tr>
  <tr>
    <th class="indi_th" style="width:20px;">No.</th>
    <th class="indi_th">氏名</th>
    <th class="indi_th">3P</th>
    <th class="indi_th">2P</th>
    <th class="indi_th">FT</th>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <th class="indi_th">Total</th>
    <th class="indi_th">OFR</th>
    <th class="indi_th">DFR</th>
    <th class="indi_th">AS</th>
    <th class="indi_th">ST</th>
    <th class="indi_th">BL</th>
{$homePartSettingForm}
EOF;
} else {
print <<<EOF
{$homePartSettingForm}
    <th class="indi_th">Total</th>
EOF;
}
print <<<EOF
  </tr>\n
EOF;
if ($homeIndiGameCheck == True AND ($status == "" OR $status == "complet" OR preg_match ("/away/i", $status))){
  /////////////////////////////
  // HOME側の個人得点        //
  /////////////////////////////
  foreach ($homeIndiScoreData as $key => $homeIndiData) {
    if ($homeIndiData["PARTICIPATION"] == "unpart") continue;
    $threePoint = $homeIndiData["3POINT_IN"] * 3;
    $twoPoint = $homeIndiData["2POINT_IN"] * 2;
print <<<EOF
  <tr>
    <td class="player_number" style="width:20px;">{$homeIndiData["NUMBER"]}</td>
    <td class="player_name">{$homeIndiData["FIRST_NAME"]}&nbsp;{$homeIndiData["SECOND_NAME"]}</td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;">{$threePoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$homeIndiData["3POINT_IN"]}</div>
    </td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;">{$twoPoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$homeIndiData["2POINT_IN"]}</div>
    </td>
    <td class="ft">{$homeIndiData["FREE_THROW_IN"]}</td>
    <td class="IndiTotalScore"><span style="font-weight:bold;color:#0A2FCA;">{$homeIndiData["TOTAL_SCORE"]}</span></td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="ft">{$homeIndiData["OFFENCE_REBOUND"]}</td>
    <td class="ft">{$homeIndiData["DEFENCE_REBOUND"]}</td>
	<td class="ft">{$homeIndiData["ASSIST"]}</td>
	<td class="ft">{$homeIndiData["STEAL"]}</td>
	<td class="ft">{$homeIndiData["BLOCKSHOT"]}</td>
EOF;
}
print <<<EOF
  </tr>\n
EOF;
  }
  if (!preg_match ("/away/i", $status)) {
print <<<EOF
  <tr>
    <td colspan="12" style="background-color:#FFF;text-align:center;">
      <input type="submit" value="HOME個人データ修正" />
    </td>
  </tr>\n
EOF;
  }
print <<<EOF
<input type="hidden" name="serchMode" value="{$serchMode}" />
<input type="hidden" name="mode" value="{$mode}" />
<input type="hidden" name="status" value="homeIndiDataChange" />
<input type="hidden" name="rarryId" value="{$rarryId}" />
<input type="hidden" name="gameId" value="{$gameId}" />
<input type="hidden" name="gameHomeTeamId" value="{$gameHomeTeamId}" />
<input type="hidden" name="gameClass" value="{$gameClass}" />
</form>\n
EOF;
// 個人スコア確認モード
} else if ($status == "homeIndiConfilm") {
  // HOME側の個人得点
  for ($i = 0; $i<count($homeIndiScoreData); $i++) {
    if (!isset($homeSetMember[$i])) continue; // 出場にチェックがない選手は非表示
    (int)$threePoint = $homeSetIndiScoreData["ThreePoint".$i] * 3;
    (int)$twoPoint = $homeSetIndiScoreData["TwoPoint".$i] * 2;
    (int)$homeTotalThreePoint += $threePoint;
    (int)$homeTotalThreePointNum += $homeSetIndiScoreData["ThreePoint".$i];
    (int)$homeTotalTwoPoint += $twoPoint;
    (int)$homeTotalTwoPointNum += $homeSetIndiScoreData["TwoPoint".$i];
    (int)$homeTotalFreeTrrow += $homeSetIndiScoreData["FreeTrrow".$i];
    (int)$homeTotalOffenceRebound += $homeSetIndiScoreData["OffenceRebound".$i];
    (int)$homeTotalDefenceRebound += $homeSetIndiScoreData["DefenceRebound".$i];
    (int)$homeTotalAssist += $homeSetIndiScoreData["Assist".$i];
    (int)$homeTotalSteal += $homeSetIndiScoreData["Steal".$i];
    (int)$homeTotalBlockShot += $homeSetIndiScoreData["BlockShot".$i];
print <<<EOF
  <tr>
    <td class="player_number">{$homeIndiScoreData[$i]["NUMBER"]}</td>
    <td class="player_name" style="width:100px;">{$homeIndiScoreData[$i]["FIRST_NAME"]}&nbsp;{$homeIndiScoreData[$i]["SECOND_NAME"]}</td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;font-weight:bold;">{$threePoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$homeSetIndiScoreData["ThreePoint".$i]}</div>
      <input type="hidden" name="homeSetMember[{$i}]" value="{$homeSetMember[$i]}" />
      <input type="hidden" name="homeSetIndiScoreData[ThreePoint{$i}]" value="{$homeSetIndiScoreData[ThreePoint.$i]}" />
    </td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;font-weight:bold;">{$twoPoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$homeSetIndiScoreData["TwoPoint".$i]}</div>
      <input type="hidden" name="homeSetIndiScoreData[TwoPoint{$i}]" value="{$homeSetIndiScoreData[TwoPoint.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$homeSetIndiScoreData["FreeTrrow".$i]}
      <input type="hidden" name="homeSetIndiScoreData[FreeTrrow{$i}]" value="{$homeSetIndiScoreData[FreeTrrow.$i]}" />
    </td>
    <td class="IndiTotalScore"><span style="font-weight:bold;color:#0A2FCA;">{$homeSetIndiScoreData["IndiTotalScore".$i]}</span>
      <input type="hidden" name="homeSetIndiTotalScore[IndiTotalScore{$i}]" value="{$homeSetIndiScoreData[IndiTotalScore.$i]}" />
      <input type="hidden" name="homeSetIndiId[IndiId{$i}]" value="{$homeSetIndiId["IndiId".$i]}" />
      <input type="hidden" name="homeSetIndiNumber[IndiNumber{$i}]" value="{$homeSetIndiNumber[IndiNumber.$i]}" />
    </td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="ft" style="font-weight:bold;text-align:right;">{$homeSetIndiScoreData["OffenceRebound".$i]}
      <input type="hidden" name="homeSetIndiScoreData[OffenceRebound{$i}]" value="{$homeSetIndiScoreData[OffenceRebound.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$homeSetIndiScoreData["DefenceRebound".$i]}
      <input type="hidden" name="homeSetIndiScoreData[DefenceRebound{$i}]" value="{$homeSetIndiScoreData[DefenceRebound.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$homeSetIndiScoreData["Assist".$i]}
      <input type="hidden" name="homeSetIndiScoreData[Assist{$i}]" value="{$homeSetIndiScoreData[Assist.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$homeSetIndiScoreData["Steal".$i]}
      <input type="hidden" name="homeSetIndiScoreData[Steal{$i}]" value="{$homeSetIndiScoreData[Steal.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$homeSetIndiScoreData["BlockShot".$i]}
      <input type="hidden" name="homeSetIndiScoreData[BlockShot{$i}]" value="{$homeSetIndiScoreData[BlockShot.$i]}" />
    </td>
EOF;
}
print <<<EOF
  </tr>\n
EOF;
  }
    (int)$homeTotalScoreSum = $homeTotalThreePoint + $homeTotalTwoPoint + $homeTotalFreeTrrow;
print <<<EOF
  <tr>
    <td colspan="2" style="border:none;padding:5px 10px 2px 0px;text-align:right;">TEAM TOTAL</td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;">{$homeTotalThreePoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$homeTotalThreePointNum}</div>
    </td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;">{$homeTotalTwoPoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$homeTotalTwoPointNum}</div>
    </td>
    <td class="ft">{$homeTotalFreeTrrow}</td>
    <td class="IndiTotalScore">
      <span style="font-weight:bold;color:#0A2FCA;">{$homeTotalScoreSum}</span>
    </td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="ft">{$homeTotalOffenceRebound}</td>
    <td class="ft">{$homeTotalDefenceRebound}</td>
    <td class="ft">{$homeTotalAssist}</td>
    <td class="ft">{$homeTotalSteal}</td>
    <td class="ft">{$homeTotalBlockShot}</td>
EOF;
}
print <<<EOF
  </tr>
  <tr>
    <td colspan="20" style="border:none;text-align:center;">
EOF;
  if ($homeIndiFmError == True) {
print <<<EOF
      {$homeIndiErrorValue}
      <input type="submit" value="&nbsp;確定&nbsp;" />\n
EOF;
  } else {
print <<<EOF
      {$homeIndiErrorValue}\n
EOF;
  }
print <<<EOF
      <input type="button" value="やり直す" onclick="sendIndi('homeIndiDataChange');" />
    </td>
  </tr>
  <tr>
    <td colspan="20" style="border:none;text-align:right;">
      <input type="button" value="結果へ戻る" onclick="sendIndi('');" />
    </td>
  </tr>
<input type="hidden" name="homeIndiThreePointSum" value="$homeIndiThreePointSum" />
<input type="hidden" name="homeIndiTwoPointSum" value="$homeIndiTwoPointSum" />
<input type="hidden" name="homeIndiFreeTrrowSum" value="$homeIndiFreeTrrowSum" />
<input type="hidden" name="homeIndiTotalScoreSum" value="$homeIndiTotalScoreSum" />
<input type="hidden" name="homeIndiOffenceReboundSum" value="$homeIndiOffenceReboundSum" />
<input type="hidden" name="homeIndiDefenceReboundSum" value="$homeIndiDefenceReboundSum" />
<input type="hidden" name="homeIndiAssistSum" value="$homeIndiAssistSum" />
<input type="hidden" name="homeIndiStealSum" value="$homeIndiStealSum" />
<input type="hidden" name="homeIndiBlockShotSum" value="$homeIndiBlockShotSum" />
<input type="hidden" name="serchMode" value="{$serchMode}" />
<input type="hidden" name="mode" value="{$mode}" />
<input type="hidden" name="status" value="homeIndiComplet" />
<input type="hidden" name="rarryId" value="{$rarryId}" />
<input type="hidden" name="gameId" value="{$gameId}" />
<input type="hidden" name="gameHomeTeamId" value="{$gameHomeTeamId}" />
<input type="hidden" name="gameClass" value="{$gameClass}" />
</form>\n
EOF;
// 個人スコア登録フォーム(チーム全体に個人スコアデータが無いとき)
} else {
/*
print <<<EOF
<form method="post" action="{$_SERVER["PHPSELF"]}">\n
EOF;
*/
  $homeIndiDataNum = count($homeIndiScoreData);
  if ($homeIndiDataNum > 5) {
    // HOME側の個人得点登録フォーム
    for ($i = 0; $i<$homeIndiDataNum; $i++) {

      if ($i == $homeIndiDataNum) break;

      if (preg_match ("/away/i", $status)) $fmHomeDisabled = "disabled=\"disabled\" ";

	  if ($status != "") {
          // 個人データ修正更新時の初期値
          if ($homeIndiScoreData[$i]["3POINT_IN"] > 0) $homeSetIndiScoreData["ThreePoint".$i] = $homeIndiScoreData[$i]["3POINT_IN"];
          if ($homeIndiScoreData[$i]["2POINT_IN"] > 0) $homeSetIndiScoreData["TwoPoint".$i] = $homeIndiScoreData[$i]["2POINT_IN"];
          if ($homeIndiScoreData[$i]["FREE_THROW_IN"] > 0) $homeSetIndiScoreData["FreeTrrow".$i] = $homeIndiScoreData[$i]["FREE_THROW_IN"];
          if ($homeIndiScoreData[$i]["TOTAL_SCORE"] > 0) $homeSetIndiTotalScore["IndiTotalScore".$i] = $homeIndiScoreData[$i]["TOTAL_SCORE"];
          if ($homeIndiScoreData[$i]["OFFENCE_REBOUND"] > 0) $homeSetIndiScoreData["OffenceRebound".$i] = $homeIndiScoreData[$i]["OFFENCE_REBOUND"];
          if ($homeIndiScoreData[$i]["DEFENCE_REBOUND"] > 0) $homeSetIndiScoreData["DefenceRebound".$i] = $homeIndiScoreData[$i]["DEFENCE_REBOUND"];
          if ($homeIndiScoreData[$i]["ASSIST"] > 0) $homeSetIndiScoreData["Assist".$i] = $homeIndiScoreData[$i]["ASSIST"];
          if ($homeIndiScoreData[$i]["STEAL"] > 0) $homeSetIndiScoreData["Steal".$i] = $homeIndiScoreData[$i]["STEAL"];
          if ($homeIndiScoreData[$i]["BLOCKSHOT"] > 0) $homeSetIndiScoreData["BlockShot".$i] = $homeIndiScoreData[$i]["BLOCKSHOT"];
          ($homeIndiScoreData[$i]["PARTICIPATION"] == "part" OR $homeSetMember[$i]) ? $setHomeChecked = " checked=\"checked\"" : $setHomeChecked = "";
      } else {
          $homeSetIndiScoreData["ThreePoint".$i] = $homeIndiScoreData[$i]["3POINT_IN"];
          $homeSetIndiScoreData["TwoPoint".$i] = $homeIndiScoreData[$i]["2POINT_IN"];
          $homeSetIndiScoreData["FreeTrrow".$i] = $homeIndiScoreData[$i]["FREE_THROW_IN"];
          $homeSetIndiTotalScore["IndiTotalScore".$i] = $homeIndiScoreData[$i]["TOTAL_SCORE"];
          $homeSetIndiScoreData["OffenceRebound".$i] = $homeIndiScoreData[$i]["OFFENCE_REBOUND"];
          $homeSetIndiScoreData["DefenceRebound".$i] = $homeIndiScoreData[$i]["DEFENCE_REBOUND"];
          $homeSetIndiScoreData["Assist".$i] = $homeIndiScoreData[$i]["ASSIST"];
          $homeSetIndiScoreData["Steal".$i] = $homeIndiScoreData[$i]["STEAL"];
          $homeSetIndiScoreData["BlockShot".$i] = $homeIndiScoreData[$i]["BLOCKSHOT"];
          ($homeIndiScoreData[$i]["PARTICIPATION"] == "part") ? $setHomeChecked = " checked=\"checked\"" : $setHomeChecked = "";
          $homeIndiThreePointSum = 0;
          $homeIndiTwoPointSum = 0;
          $homeIndiFreeTrrowSum = 0;
          $homeIndiTotalScoreSum = 0;
          $homeIndiOffenceReboundSum = 0;
          $homeIndiDefenceReboundSum = 0;
          $homeIndiAssistSum = 0;
          $homeIndiStealSum = 0;
          $homeIndiBlockShotSum = 0;
      }

      //(isset($homeSetMember[$i])) ? $setHomeChecked = " checked=\"checked\"" : $setHomeChecked = "";
      if (!isset($homeSetIndiScoreData["ThreePoint".$i])) $homeSetIndiScoreData["ThreePoint".$i] = 0;
      if (!isset($homeSetIndiScoreData["TwoPoint".$i])) $homeSetIndiScoreData["TwoPoint".$i] = 0;
      if (!isset($homeSetIndiScoreData["FreeTrrow".$i])) $homeSetIndiScoreData["FreeTrrow".$i] = 0;
      if (!isset($homeSetIndiTotalScore["IndiTotalScore".$i])) $homeSetIndiTotalScore["IndiTotalScore".$i] = 0;
      if (!isset($homeSetIndiScoreData["OffenceRebound".$i])) $homeSetIndiScoreData["OffenceRebound".$i] = 0;
      if (!isset($homeSetIndiScoreData["DefenceRebound".$i])) $homeSetIndiScoreData["DefenceRebound".$i] = 0;
      if (!isset($homeSetIndiScoreData["Assist".$i])) $homeSetIndiScoreData["Assist".$i] = 0;
      if (!isset($homeSetIndiScoreData["Steal".$i])) $homeSetIndiScoreData["Steal".$i] = 0;
      if (!isset($homeSetIndiScoreData["BlockShot".$i])) $homeSetIndiScoreData["BlockShot".$i] = 0;
      if (!isset($homeSetIndiId["IndiId".$i])) $homeSetIndiId["IndiId".$i] = $homeIndiScoreData[$i]["INDI_ID"];
      if (!isset($homeSetIndiNumber["IndiNumber".$i])) $homeSetIndiNumber["IndiNumber".$i] = $homeIndiScoreData[$i]["NUMBER"];
print <<<EOF
  <tr>
    <td class="player_number">{$homeIndiScoreData[$i]["NUMBER"]}</td>
    <td class="player_name">{$homeIndiScoreData[$i]["FIRST_NAME"]}&nbsp;{$homeIndiScoreData[$i]["SECOND_NAME"]}</td>
    <td style="text-align:center;">
      <input type="text" name="homeSetIndiScoreData[ThreePoint{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'home',{$i});" style="text-align:right" value="{$homeSetIndiScoreData["ThreePoint".$i]}" onfocus="this.select()" {$fmHomeDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeSetIndiScoreData[TwoPoint{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'home',{$i});" style="text-align:right" value="{$homeSetIndiScoreData["TwoPoint$i"]}" onfocus="this.select()" {$fmHomeDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeSetIndiScoreData[FreeTrrow{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'home',{$i});" style="text-align:right" value="{$homeSetIndiScoreData["FreeTrrow$i"]}" onfocus="this.select()" {$fmHomeDisabled}/>
    </td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="IndiTotalScore">
      <input type="text" name="homeSetIndiScoreData[IndiTotalScore{$i}]" size="3" readonly="readonly" class="inditotalscore" value="{$homeSetIndiTotalScore["IndiTotalScore$i"]}" />
      <input type="hidden" name="homeSetIndiId[IndiId{$i}]" value="{$homeSetIndiId["IndiId{$i}"]}" />
      <input type="hidden" name="homeSetIndiNumber[IndiNumber{$i}]" value="{$homeSetIndiNumber["IndiNumber{$i}"]}" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeSetIndiScoreData[OffenceRebound{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'home',{$i});" style="text-align:right" value="{$homeSetIndiScoreData["OffenceRebound$i"]}" onfocus="this.select()" {$fmHomeDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeSetIndiScoreData[DefenceRebound{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'home',{$i});" style="text-align:right" value="{$homeSetIndiScoreData["DefenceRebound$i"]}" onfocus="this.select()" {$fmHomeDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeSetIndiScoreData[Assist{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'home',{$i});" style="text-align:right" value="{$homeSetIndiScoreData["Assist$i"]}" onfocus="this.select()" {$fmHomeDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeSetIndiScoreData[Steal{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'home',{$i});" style="text-align:right" value="{$homeSetIndiScoreData["Steal$i"]}" onfocus="this.select()" {$fmHomeDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeSetIndiScoreData[BlockShot{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'home',{$i});" style="text-align:right" value="{$homeSetIndiScoreData["BlockShot$i"]}" onfocus="this.select()" {$fmHomeDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="checkbox" name="homeSetMember[{$i}]" value="{$i}"{$setHomeChecked} {$fmHomeDisabled}/>
    </td>
EOF;
} else {
print <<<EOF
    <td style="text-align:center;">
      <input type="checkbox" name="homeSetMember[{$i}]" value="{$i}"{$setHomeChecked} {$fmHomeDisabled}/>
    </td>
    <td class="IndiTotalScore">
      <input type="text" name="homeSetIndiScoreData[IndiTotalScore{$i}]" size="3" readonly="readonly" class="inditotalscore" value="{$homeSetIndiTotalScore["IndiTotalScore$i"]}" />
      <input type="hidden" name="homeSetIndiId[IndiId{$i}]" value="{$homeSetIndiId["IndiId{$i}"]}" />
      <input type="hidden" name="homeSetIndiNumber[IndiNumber{$i}]" value="{$homeSetIndiNumber["IndiNumber{$i}"]}" />
    </td>
EOF;
}
print <<<EOF
  </tr>\n
EOF;
    }
print <<<EOF
  <tr>
    <td colspan="2" style="text-align:right;padding-right:8px;">計</td>
    <td style="text-align:center;">
      <input type="text" name="homeIndiThreePointSum" size="2" class="inditotalscore" value="{$homeIndiThreePointSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeIndiTwoPointSum" size="2" class="inditotalscore" value="{$homeIndiTwoPointSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeIndiFreeTrrowSum" size="2" class="inditotalscore" value="{$homeIndiFreeTrrowSum}" readonly="readonly" />
    </td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="IndiTotalScore">
      <input type="text" name="homeIndiTotalScoreSum" size="3" class="inditotalscore" value="{$homeIndiTotalScoreSum}" readonly="readonly" />
    </td>
    <td class="IndiTotalScore">
      <input type="text" name="homeIndiOffenceReboundSum" size="3" class="inditotalscore" value="{$homeIndiOffenceReboundSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeIndiDefenceReboundSum" size="2" class="inditotalscore" value="{$homeIndiDefenceReboundSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeIndiAssistSum" size="2" class="inditotalscore" value="{$homeIndiAssistSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeIndiStealSum" size="2" class="inditotalscore" value="{$homeIndiStealSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="homeIndiBlockShotSum" size="2" class="inditotalscore" value="{$homeIndiBlockShotSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      &nbsp;
    </td>
EOF;
} else {
print <<<EOF
    <td style="text-align:center;">
      &nbsp;
    </td>
    <td class="IndiTotalScore">
      <input type="text" name="homeIndiTotalScoreSum" size="3" class="inditotalscore" value="{$homeIndiTotalScoreSum}" readonly="readonly" />
    </td>
EOF;
}
print <<<EOF
  </tr>\n
EOF;
    if (!preg_match ("/away/i", $status)) {
print <<<EOF
  <tr>
    <td colspan="20" style="background-color:#FFF;text-align:center;">
      <input type="submit" value="HOME個人データ登録" />
    </td>
  </tr>\n
EOF;
      if ($status == "homeIndiDataChange") {
print <<<EOF
  <tr>
    <td colspan="20" style="border:none;text-align:right;">
      <input type="button" value="結果へ戻る" onclick="sendIndi('');" />
    </td>
  </tr>\n
EOF;
      }
EOF;
    }
  } else {
print <<<EOF
  <tr>
    <td colspan="20" style="background-color:#FFF;text-align:center;">
      チーム選手が5名以上登録されていません。
    </td>
  </tr>\n
EOF;
  }
print <<<EOF
  <input type="hidden" name="serchMode" value="{$serchMode}" />
  <input type="hidden" name="mode" value="{$mode}" />
  <input type="hidden" name="status" value="homeIndiConfilm" />
  <input type="hidden" name="rarryId" value="{$rarryId}" />
  <input type="hidden" name="gameId" value="{$gameId}" />
  <input type="hidden" name="gameClass" value="{$gameClass}" />
  <input type="hidden" name="gameHomeTeamId" value="{$gameHomeTeamId}" />
  <input type="hidden" name="homeTotalScore" value="{$homeTotalScore}" />
  </form>\n
EOF;
}
print <<<EOF
  </tbody>
</table>
EOF;

if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
</tr>
<tr>
EOF;
}

print <<<EOF
<td style="float:left;width:10px;border:none;">&nbsp;</td>
EOF;

if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
</tr>
<tr>
EOF;
}

print <<<EOF
<!-- AWAYチーム個人データ -->
<td style="margin:0px;padding:0px;border:none;vertical-align:top;width:366px;">
<table class="indi_data">
  <thead>
  <tr>
    <th colspan="20" style="background-color:#FFF;text-align:left;">
      <div class="indi_team">{$gameAwayTeamName}</div>
    </th>
  </tr>
  </thead>
  <form name="setAwayIndiScore" method="post" action="{$_SERVER["PHPSELF"]}">
  <tbody>
  <tr>
    <th class="indi_th" colspan="2">TEAM</th>
    <th class="indi_th" colspan="{$colspan['table01']}">SCORE</th>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
	<th class="indi_th" colspan="5">REBOUND・ASSIST・STEAL・BLOCK</th>
EOF;
if ($awayIndiGameCheck == True AND ($status == "" OR $status == "complet" OR preg_match ("/home/i", $status))) {
} else {
print <<<EOF
	<th class="indi_th">&nbsp;</th>
EOF;
}
print <<<EOF
EOF;
}
print <<<EOF
  </tr>
  <tr>
    <th class="indi_th" style="width:20px;">No.</th>
    <th class="indi_th">氏名</th>
    <th class="indi_th">3P</th>
    <th class="indi_th">2P</th>
    <th class="indi_th">FT</th>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <th class="indi_th">Total</th>
    <th class="indi_th">OFR</th>
    <th class="indi_th">DFR</th>
    <th class="indi_th">AS</th>
    <th class="indi_th">ST</th>
    <th class="indi_th">BL</th>
{$awayPartSettingForm}
EOF;
} else {
print <<<EOF
{$awayPartSettingForm}
    <th class="indi_th">Total</th>
EOF;
}
print <<<EOF
  </tr>\n
EOF;
if ($awayIndiGameCheck == True AND ($status == "" OR  $status == "complet" OR  preg_match ("/home/i", $status))){
  /////////////////////////////
  // AWAY側の個人得点        //
  /////////////////////////////
  foreach ($awayIndiScoreData as $key => $awayIndiData) {
    if ($awayIndiData["PARTICIPATION"] == "unpart") continue;
    $threePoint = $awayIndiData["3POINT_IN"] * 3;
    $twoPoint = $awayIndiData["2POINT_IN"] * 2;
print <<<EOF
  <tr>
    <td class="player_number">{$awayIndiData["NUMBER"]}</td>
    <td class="player_name">{$awayIndiData["FIRST_NAME"]}&nbsp;{$awayIndiData["SECOND_NAME"]}</td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;">{$threePoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$awayIndiData["3POINT_IN"]}</div>
    </td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;">{$twoPoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$awayIndiData["2POINT_IN"]}</div>
    </td>
    <td class="ft">{$awayIndiData["FREE_THROW_IN"]}</td>
    <td class="IndiTotalScore"><span style="font-weight:bold;color:#0A2FCA;">{$awayIndiData["TOTAL_SCORE"]}</span></td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="ft">{$awayIndiData["OFFENCE_REBOUND"]}</td>
    <td class="ft">{$awayIndiData["DEFENCE_REBOUND"]}</td>
	<td class="ft">{$awayIndiData["ASSIST"]}</td>
	<td class="ft">{$awayIndiData["STEAL"]}</td>
	<td class="ft">{$awayIndiData["BLOCKSHOT"]}</td>
EOF;
}
print <<<EOF
  </tr>\n
EOF;
  }
  if (!preg_match ("/home/i", $status)) {
print <<<EOF
  <tr>
    <td colspan="20" style="background-color:#FFF;text-align:center;">
      <input type="submit" value="AWAY個人データ修正" />
    </td>
  </tr>
EOF;
  }
print <<<EOF
  </tbody>
<input type="hidden" name="serchMode" value="{$serchMode}" />
<input type="hidden" name="mode" value="{$mode}" />
<input type="hidden" name="status" value="awayIndiDataChange" />
<input type="hidden" name="rarryId" value="{$rarryId}" />
<input type="hidden" name="gameId" value="{$gameId}" />
<input type="hidden" name="gameAwayTeamId" value="{$gameAwayTeamId}" />
<input type="hidden" name="gameClass" value="{$gameClass}" />
</form>\n
EOF;
// 個人スコア確認モード
} else if ($status == "awayIndiConfilm") {
  // AWAY側の個人得点
  for ($i = 0; $i<count($awayIndiScoreData); $i++) {
    if (!isset($awaySetMember[$i])) continue; // 出場にチェックがない選手は非表示
    (int)$threePoint = $awaySetIndiScoreData["ThreePoint".$i] * 3;
    (int)$twoPoint = $awaySetIndiScoreData["TwoPoint".$i] * 2;
    (int)$awayTotalThreePoint += $threePoint;
    (int)$awayTotalThreePointNum += $awaySetIndiScoreData["ThreePoint".$i];
    (int)$awayTotalTwoPoint += $twoPoint;
    (int)$awayTotalTwoPointNum += $awaySetIndiScoreData["TwoPoint".$i];
    (int)$awayTotalFreeTrrow += $awaySetIndiScoreData["FreeTrrow".$i];
    (int)$awayTotalOffenceRebound += $awaySetIndiScoreData["OffenceRebound".$i];
    (int)$awayTotalDefenceRebound += $awaySetIndiScoreData["DefenceRebound".$i];
    (int)$awayTotalAssist += $awaySetIndiScoreData["Assist".$i];
    (int)$awayTotalSteal += $awaySetIndiScoreData["Steal".$i];
    (int)$awayTotalBlockShot += $awaySetIndiScoreData["BlockShot".$i];
print <<<EOF
  <tr>
    <td class="player_number">{$awayIndiScoreData[$i]["NUMBER"]}</td>
    <td class="player_name">{$awayIndiScoreData[$i]["FIRST_NAME"]}&nbsp;{$awayIndiScoreData[$i]["SECOND_NAME"]}</td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;font-weight:bold;">{$threePoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$awaySetIndiScoreData["ThreePoint".$i]}</div>
      <input type="hidden" name="awaySetMember[{$i}]" value="{$awaySetMember[$i]}" />
      <input type="hidden" name="awaySetIndiScoreData[ThreePoint{$i}]" value="{$awaySetIndiScoreData[ThreePoint.$i]}" />
    </td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;font-weight:bold;">{$twoPoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$awaySetIndiScoreData["TwoPoint".$i]}</div>
      <input type="hidden" name="awaySetIndiScoreData[TwoPoint{$i}]" value="{$awaySetIndiScoreData[TwoPoint.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$awaySetIndiScoreData["FreeTrrow".$i]}
      <input type="hidden" name="awaySetIndiScoreData[FreeTrrow{$i}]" value="{$awaySetIndiScoreData[FreeTrrow.$i]}" />
    </td>
    <td class="IndiTotalScore"><span style="font-weight:bold;color:#0A2FCA;">{$awaySetIndiScoreData["IndiTotalScore".$i]}</span>
      <input type="hidden" name="awaySetIndiTotalScore[IndiTotalScore{$i}]" value="{$awaySetIndiScoreData[IndiTotalScore.$i]}" />
      <input type="hidden" name="awaySetIndiId[IndiId{$i}]" value="{$awaySetIndiId["IndiId".$i]}" />
      <input type="hidden" name="awaySetIndiNumber[IndiNumber{$i}]" value="{$awaySetIndiNumber[IndiNumber.$i]}" />
    </td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="ft" style="font-weight:bold;text-align:right;">{$awaySetIndiScoreData["OffenceRebound".$i]}
      <input type="hidden" name="awaySetIndiScoreData[OffenceRebound{$i}]" value="{$awaySetIndiScoreData[OffenceRebound.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$awaySetIndiScoreData["DefenceRebound".$i]}
      <input type="hidden" name="awaySetIndiScoreData[DefenceRebound{$i}]" value="{$awaySetIndiScoreData[DefenceRebound.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$awaySetIndiScoreData["Assist".$i]}
      <input type="hidden" name="awaySetIndiScoreData[Assist{$i}]" value="{$awaySetIndiScoreData[Assist.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$awaySetIndiScoreData["Steal".$i]}
      <input type="hidden" name="awaySetIndiScoreData[Steal{$i}]" value="{$awaySetIndiScoreData[Steal.$i]}" />
    </td>
    <td class="ft" style="font-weight:bold;text-align:right;">{$awaySetIndiScoreData["BlockShot".$i]}
      <input type="hidden" name="awaySetIndiScoreData[BlockShot{$i}]" value="{$awaySetIndiScoreData[BlockShot.$i]}" />
    </td>
EOF;
}
print <<<EOF
  </tr>\n
EOF;
  }
    (int)$awayTotalScoreSum = $awayTotalThreePoint + $awayTotalTwoPoint + $awayTotalFreeTrrow;
print <<<EOF
  <tr>
    <td colspan="2" style="border:none;padding:5px 10px 2px 0px;text-align:right;">TEAM TOTAL</td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;">{$awayTotalThreePoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$awayTotalThreePointNum}</div>
    </td>
    <td class="3POINT">
      <div style="float:left;width:20px;text-align:right;border-right:1px dotted;padding: 0px 5px;">{$awayTotalTwoPoint}</div>
      <div style="float:left;width:15px;padding: 0px 5px;text-align:right;">{$awayTotalTwoPointNum}</div>
    </td>
    <td class="ft">{$awayTotalFreeTrrow}
    </td>
    <td class="IndiTotalScore">
      <span style="font-weight:bold;color:#0A2FCA;">{$awayTotalScoreSum}</span>
    </td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="ft">{$awayTotalOffenceRebound}</td>
    <td class="ft">{$awayTotalDefenceRebound}</td>
    <td class="ft">{$awayTotalAssist}</td>
    <td class="ft">{$awayTotalSteal}</td>
    <td class="ft">{$awayTotalBlockShot}</td>
EOF;
}
print <<<EOF
  </tr>
  <tr>
    <td colspan="20" style="border:none;text-align:center;">
EOF;
  if ($awayIndiFmError == True) {
print <<<EOF
      {$awayIndiErrorValue}
      <input type="submit" value="&nbsp;確定&nbsp;" />\n
EOF;
  } else {
print <<<EOF
      {$awayIndiErrorValue}\n
EOF;
  }
print <<<EOF
      <input type="button" value="やり直す" onclick="sendIndi('awayIndiDataChange');" />
    </td>
  </tr>
  <tr>
    <td colspan="20" style="border:none;text-align:right;">
      <input type="button" value="結果へ戻る" onclick="sendIndi('');" />
    </td>
  </tr>
  </tbody>
<input type="hidden" name="awayIndiThreePointSum" value="$awayIndiThreePointSum" />
<input type="hidden" name="awayIndiTwoPointSum" value="$awayIndiTwoPointSum" />
<input type="hidden" name="awayIndiFreeTrrowSum" value="$awayIndiFreeTrrowSum" />
<input type="hidden" name="awayIndiTotalScoreSum" value="$awayIndiTotalScoreSum" />
<input type="hidden" name="awayIndiOffenceReboundSum" value="$awayIndiOffenceReboundSum" />
<input type="hidden" name="awayIndiDefenceReboundSum" value="$awayIndiDefenceReboundSum" />
<input type="hidden" name="awayIndiAssistSum" value="$awayIndiAssistSum" />
<input type="hidden" name="awayIndiStealSum" value="$awayIndiStealSum" />
<input type="hidden" name="awayIndiBlockShotSum" value="$awayIndiBlockShotSum" />
<input type="hidden" name="serchMode" value="{$serchMode}" />
<input type="hidden" name="mode" value="{$mode}" />
<input type="hidden" name="status" value="awayIndiComplet" />
<input type="hidden" name="rarryId" value="{$rarryId}" />
<input type="hidden" name="gameId" value="{$gameId}" />
<input type="hidden" name="gameAwayTeamId" value="{$gameAwayTeamId}" />
<input type="hidden" name="gameClass" value="{$gameClass}" />
</form>\n
EOF;
// 個人スコア登録フォーム(チーム全体に個人スコアデータが無いとき)
} else {
/*
print <<<EOF
<form method="post" action="{$_SERVER["PHPSELF"]}">\n
EOF;
*/
  $awayIndiDataNum = count($awayIndiScoreData);
  if ($awayIndiDataNum > 5) {
    // AWAY側の個人得点登録フォーム
    for ($i = 0; $i<$awayIndiDataNum; $i++) {

      if ($i == $awayIndiDataNum) break;

      if (preg_match ("/home/i", $status)) $fmAwayDisabled = "disabled=\"disabled\" ";

      if ($status != "") {
          // 個人データ修正更新時の初期値
          if ($awayIndiScoreData[$i]["3POINT_IN"] > 0) $awaySetIndiScoreData["ThreePoint".$i] = $awayIndiScoreData[$i]["3POINT_IN"];
          if ($awayIndiScoreData[$i]["2POINT_IN"] > 0) $awaySetIndiScoreData["TwoPoint".$i] = $awayIndiScoreData[$i]["2POINT_IN"];
          if ($awayIndiScoreData[$i]["FREE_THROW_IN"] > 0) $awaySetIndiScoreData["FreeTrrow".$i] = $awayIndiScoreData[$i]["FREE_THROW_IN"];
          if ($awayIndiScoreData[$i]["TOTAL_SCORE"] > 0) $awaySetIndiTotalScore["IndiTotalScore".$i] = $awayIndiScoreData[$i]["TOTAL_SCORE"];
          if ($awayIndiScoreData[$i]["OFFENCE_REBOUND"] > 0) $awaySetIndiScoreData["OffenceRebound".$i] = $awayIndiScoreData[$i]["OFFENCE_REBOUND"];
          if ($awayIndiScoreData[$i]["DEFENCE_REBOUND"] > 0) $awaySetIndiScoreData["DefenceRebound".$i] = $awayIndiScoreData[$i]["DEFENCE_REBOUND"];
          //if ($awayIndiScoreData[$i]["TOTAL_REBOUND"] > 0) $awaySetIndiTotalRebound["IndiTotalRebound".$i] = $awayIndiScoreData[$i]["TOTAL_REBOUND"];
          if ($awayIndiScoreData[$i]["ASSIST"] > 0) $awaySetIndiScoreData["Assist".$i] = $awayIndiScoreData[$i]["ASSIST"];
          if ($awayIndiScoreData[$i]["STEAL"] > 0) $awaySetIndiScoreData["Steal".$i] = $awayIndiScoreData[$i]["STEAL"];
          if ($awayIndiScoreData[$i]["BLOCKSHOT"] > 0) $awaySetIndiScoreData["BlockShot".$i] = $awayIndiScoreData[$i]["BLOCKSHOT"];
          ($awayIndiScoreData[$i]["PARTICIPATION"] == "part" OR $awaySetMember[$i]) ? $setAwayChecked = " checked=\"checked\"" : $setAwayChecked = "";
      } else {
          $awaySetIndiScoreData["ThreePoint".$i] = $awayIndiScoreData[$i]["3POINT_IN"];
          $awaySetIndiScoreData["TwoPoint".$i] = $awayIndiScoreData[$i]["2POINT_IN"];
          $awaySetIndiScoreData["FreeTrrow".$i] = $awayIndiScoreData[$i]["FREE_THROW_IN"];
          $awaySetIndiScoreData["IndiTotalScore".$i] = $awayIndiScoreData[$i]["TOTAL_SCORE"];
          $awaySetIndiScoreData["OffenceRebound".$i] = $awayIndiScoreData[$i]["OFFENCE_REBOUND"];
          $awaySetIndiScoreData["DefenceRebound".$i] = $awayIndiScoreData[$i]["DEFENCE_REBOUND"];
          //$awaySetIndiTotalRebound["TotalRebound".$i] = $awayIndiScoreData[$i]["TOTAL_REBOUND"];
          $awaySetIndiScoreData["Assist".$i] = $awayIndiScoreData[$i]["ASSIST"];
          $awaySetIndiScoreData["Steal".$i] = $awayIndiScoreData[$i]["STEAL"];
          $awaySetIndiScoreData["BlockShot".$i] = $awayIndiScoreData[$i]["BLOCKSHOT"];
          ($awayIndiScoreData[$i]["PARTICIPATION"] == "part") ? $setAwayChecked = " checked=\"checked\"" : $setAwayChecked = "";
          $awayIndiThreePointSum = 0;
          $awayIndiTwoPointSum = 0;
          $awayIndiFreeTrrowSum = 0;
          $awayIndiTotalScoreSum = 0;
          $awayIndiOffenceReboundSum = 0;
          $awayIndiDefenceReboundSum = 0;
          //$awayIndiTotalReboundSum = 0;
          $awayIndiAssistSum = 0;
          $awayIndiStealSum = 0;
          $awayIndiBlockShotSum = 0;
      }

      //(isset($awaySetMember[$i])) ? $setAwayChecked = " checked=\"checked\"" : $setAwayChecked = "";
      if (!isset($awaySetIndiScoreData["ThreePoint".$i])) $awaySetIndiScoreData["ThreePoint".$i] = 0;
      if (!isset($awaySetIndiScoreData["TwoPoint".$i])) $awaySetIndiScoreData["TwoPoint".$i] = 0;
      if (!isset($awaySetIndiScoreData["FreeTrrow".$i])) $awaySetIndiScoreData["FreeTrrow".$i] = 0;
      if (!isset($awaySetIndiTotalScore["IndiTotalScore".$i])) $awaySetIndiTotalScore["IndiTotalScore".$i] = 0;
      if (!isset($awaySetIndiScoreData["OffenceRebound".$i])) $awaySetIndiScoreData["OffenceRebound".$i] = 0;
      if (!isset($awaySetIndiScoreData["DefenceRebound".$i])) $awaySetIndiScoreData["DefenceRebound".$i] = 0;
      //if (!isset($awaySetIndiTotalRebound["IndiTotalRebound".$i])) $awaySetIndiTotalRebound["IndiTotalRebound".$i] = 0;
      if (!isset($awaySetIndiScoreData["Assist".$i])) $awaySetIndiScoreData["Assist".$i] = 0;
      if (!isset($awaySetIndiScoreData["Steal".$i])) $awaySetIndiScoreData["Steal".$i] = 0;
      if (!isset($awaySetIndiScoreData["BlockShot".$i])) $awaySetIndiScoreData["BlockShot".$i] = 0;
      if (!isset($awaySetIndiId["IndiId".$i])) $awaySetIndiId["IndiId".$i] = $awayIndiScoreData[$i]["INDI_ID"];
      if (!isset($awaySetIndiNumber["IndiNumber".$i])) $awaySetIndiNumber["IndiNumber".$i] = $awayIndiScoreData[$i]["NUMBER"];
print <<<EOF
  <tr>
    <td class="player_number">{$awayIndiScoreData[$i]["NUMBER"]}</td>
    <td class="player_name">{$awayIndiScoreData[$i]["FIRST_NAME"]}&nbsp;{$awayIndiScoreData[$i]["SECOND_NAME"]}</td>
    <td style="text-align:center;">
      <input type="text" name="awaySetIndiScoreData[ThreePoint{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'away',{$i});" style="text-align:right" value="{$awaySetIndiScoreData["ThreePoint".$i]}" onfocus="this.select()" {$fmAwayDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="awaySetIndiScoreData[TwoPoint{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'away',{$i});" style="text-align:right" value="{$awaySetIndiScoreData["TwoPoint$i"]}" onfocus="this.select()" {$fmAwayDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="awaySetIndiScoreData[FreeTrrow{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'away',{$i});" style="text-align:right" value="{$awaySetIndiScoreData["FreeTrrow$i"]}" onfocus="this.select()" {$fmAwayDisabled}/>
    </td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="IndiTotalScore">
      <input type="text" name="awaySetIndiScoreData[IndiTotalScore{$i}]" size="3" readonly="readonly" class="inditotalscore" value="{$awaySetIndiTotalScore["IndiTotalScore$i"]}" />
      <input type="hidden" name="awaySetIndiId[IndiId{$i}]" value="{$awaySetIndiId["IndiId{$i}"]}" />
      <input type="hidden" name="awaySetIndiNumber[IndiNumber{$i}]" value="{$awaySetIndiNumber["IndiNumber{$i}"]}" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="awaySetIndiScoreData[OffenceRebound{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'away',{$i});" style="text-align:right" value="{$awaySetIndiScoreData["OffenceRebound$i"]}" onfocus="this.select()" {$fmAwayDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="awaySetIndiScoreData[DefenceRebound{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'away',{$i});" style="text-align:right" value="{$awaySetIndiScoreData["DefenceRebound$i"]}" onfocus="this.select()" {$fmAwayDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="awaySetIndiScoreData[Assist{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'away',{$i});" style="text-align:right" value="{$awaySetIndiScoreData["Assist$i"]}" onfocus="this.select()" {$fmAwayDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="awaySetIndiScoreData[Steal{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'away',{$i});" style="text-align:right" value="{$awaySetIndiScoreData["Steal$i"]}" onfocus="this.select()" {$fmAwayDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="text" name="awaySetIndiScoreData[BlockShot{$i}]" size="2" maxlength="2" class="imenumeric" onblur="Validator.check(this, 'num');indiScoreCalculation(this.form,'away',{$i});" style="text-align:right" value="{$awaySetIndiScoreData["BlockShot$i"]}" onfocus="this.select()" {$fmAwayDisabled}/>
    </td>
    <td style="text-align:center;">
      <input type="checkbox" name="awaySetMember[{$i}]" value="{$i}"{$setAwayChecked} {$fmAwayDisabled}/>
    </td>
EOF;
} else {
print <<<EOF
    <td style="text-align:center;">
      <input type="checkbox" name="awaySetMember[{$i}]" value="{$i}"{$setAwayChecked} {$fmAwayDisabled}/>
    </td>
    <td class="IndiTotalScore">
      <input type="text" name="awaySetIndiScoreData[IndiTotalScore{$i}]" size="3" readonly="readonly" class="inditotalscore" value="{$awaySetIndiTotalScore["IndiTotalScore$i"]}" />
      <input type="hidden" name="awaySetIndiId[IndiId{$i}]" value="{$awaySetIndiId["IndiId{$i}"]}" />
      <input type="hidden" name="awaySetIndiNumber[IndiNumber{$i}]" value="{$awaySetIndiNumber["IndiNumber{$i}"]}" />
    </td>
EOF;
}
print <<<EOF
  </tr>\n
EOF;
    }
print <<<EOF
  <tr>
    <td colspan="2" style="text-align:right;padding-right:8px;">計</td>
    <td style="text-align:center;">
      <input type="text" name="awayIndiThreePointSum" size="2" class="inditotalscore" value="{$awayIndiThreePointSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="awayIndiTwoPointSum" size="2" class="inditotalscore" value="{$awayIndiTwoPointSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="awayIndiFreeTrrowSum" size="2" class="inditotalscore" value="{$awayIndiFreeTrrowSum}" readonly="readonly" />
    </td>
EOF;
if (in_array($gameClass, $indiScoreMoreDetailClass)) {
print <<<EOF
    <td class="IndiTotalScore">
      <input type="text" name="awayIndiTotalScoreSum" size="3" class="inditotalscore" value="{$awayIndiTotalScoreSum}" readonly="readonly" />
    </td>
    <td class="IndiTotalScore">
      <input type="text" name="awayIndiOffenceReboundSum" size="3" class="inditotalscore" value="{$awayIndiOffenceReboundSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="awayIndiDefenceReboundSum" size="2" class="inditotalscore" value="{$awayIndiDefenceReboundSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="awayIndiAssistSum" size="2" class="inditotalscore" value="{$awayIndiAssistSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="awayIndiStealSum" size="2" class="inditotalscore" value="{$awayIndiStealSum}" readonly="readonly" />
    </td>
    <td style="text-align:center;">
      <input type="text" name="awayIndiBlockShotSum" size="2" class="inditotalscore" value="{$awayIndiBlockShotSum}" readonly="readonly" />
    </td>
EOF;
} else {
print <<<EOF
    <td style="text-align:center;">
      &nbsp;
    </td>
    <td class="IndiTotalScore">
      <input type="text" name="awayIndiTotalScoreSum" size="3" class="inditotalscore" value="{$awayIndiTotalScoreSum}" readonly="readonly" />
    </td>
EOF;
}
print <<<EOF
  </tr>
EOF;
    if (!preg_match ("/home/i", $status)) {
print <<<EOF
  <tr>
    <td colspan="20" style="background-color:#FFF;text-align:center;">
      <input type="submit" value="AWAY個人データ登録" />
    </td>
  </tr>\n
EOF;
    }
  } else {
print <<<EOF
  <tr>
    <td colspan="7" style="background-color:#FFF;text-align:center;">
      チーム選手が5名以上登録されていません。
    </td>
  </tr>\n
EOF;
  }
      if ($status == "awayIndiDataChange") {
print <<<EOF
  <tr>
    <td colspan="20" style="border:none;text-align:right;">
      <input type="button" value="結果へ戻る" onclick="sendIndi('');" />
    </td>
  </tr>\n
EOF;
      }
print <<<EOF
  </tbody>
  <input type="hidden" name="serchMode" value="{$serchMode}" />
  <input type="hidden" name="mode" value="{$mode}" />
  <input type="hidden" name="status" value="awayIndiConfilm" />
  <input type="hidden" name="rarryId" value="{$rarryId}" />
  <input type="hidden" name="gameId" value="{$gameId}" />
  <input type="hidden" name="gameClass" value="{$gameClass}" />
  <input type="hidden" name="gameAwayTeamId" value="{$gameAwayTeamId}" />
  <input type="hidden" name="awayTotalScore" value="{$awayTotalScore}" />
  </form>\n
EOF;
}
print <<<EOF
</table>
</td>
</tr>
</table>
<p>&nbsp;</p>\n
EOF;

} else if ($antiwarGame == True) {
// 不戦勝ゲームの時
print <<<EOF
<h2><span style="text-indent:100px;font-size:80%;font-weight:bold;">不戦ゲームなので個人得点はありません</span></h2>\n
EOF;
} else {

print <<<EOF
<!-- チームスコアが未登録の時 -->
<p>試合結果のデータが未登録です。</p>\n
EOF;

}
//print $mode."<br />";
//print $status."<br />";
//print $ins_sql."<br />";

//print $UpdateSql."<br />";
//print $checkComment."<br />";

//print $insertComment."<br />";
//print "POST-DATA = ".nl2br(print_r($_POST,true));
#print nl2br(print_r($selectSchejuleData,true));
//print nl2br(print_r($homeIndiScoreData,true));
//print nl2br(print_r($awayIndiScoreData,true));
} else {
print <<<EOF
<!-- ゲームID見つからない時 -->
<p>試合データが未登録です。</p>
EOF;
}
?>
		</div>
		<div id="footer"></div>
	</div>
	<div id="footerBlock">
	  <hr size="2" style="color: #ED8822; filter:alpha(opacity=100,finishopacity=0,style=3)" />
	  <address>Copyright &copy; 2000-<?php echo date("Y"); ?> Liga-Tokai. All rights reserved.</address>
	  <hr size="2" style="color: #ED8822; filter:alpha(opacity=100,finishopacity=0,style=3)" />
	</div>
	<script language="javascript" type="text/javascript" src="./js/jquery-1.10.2/jquery-1.10.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui-1.10.3/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/setScore.js"></script>
</body>
</html>

