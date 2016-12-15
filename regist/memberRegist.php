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
(string)$mode = "";
(string)$modeValue = "";
(string)$teamChangeStsValue = "";
(string)$sts = "";
(string)$members = "";
$aheadPlayer = array();
$errorValue = array();
$teamDatas = array();
$memberDataArray = array();
$teamMembersNumbers = array();
$takingMemberDataArray = array();
$insertTakingPlayers = array();
$distinctDatasCheck = array();
(string)$fmButtonValue = "";
(string)$teamDataChangeModeValue = "";
(string)$memberInsertConfBackButton = "        <input type=\"button\" value=\"　やり直す　\" onclick=\"confBack();\" />\n";
(string)$memberInsertButton = "        <input type=\"submit\" value=\"　選手登録　\" onclick=\"confSubmit('playerInsert', 'comp');\" />\n";
(string)$memberInsertCompButton = "        <input type=\"button\" value=\"　チーム情報ページに戻る　\" onclick=\"pageBack('back');\" />\n";
//(string)$memberInsertConfButton = "            <input type=\"submit\" value=\"　内容確認　\" />\n";
//(string)$memberInsertBackButton = "            <input type=\"button\" value=\"　戻る　\" onclick=\"pageBack('input');\" />\n";

(int)$registNum = 10;
(int)$insertNum = 0;
(int)$confErrorNum = 0;
(int)$compErrorNum = 0;
(string)$number = "";
(string)$name_f = "";
(string)$name_s = "";
(string)$kana_f = "";
(string)$kana_s = "";
(string)$tall = "";
(string)$bd_type = "";
(string)$bd_y = "";
(string)$bd_m = "";
(string)$bd_d = "";
(string)$posision = "";
$insertDatas = array();

// 許可年齢
(int)$age_low = 15;	// 登録可能最小年齢
(int)$age_hi  = 60;	// 登録可能最大年齢
(int)$age_default  = 25;	// 誕生日未入力時の年齢

// 許可年齢計算
$ages_array = array(
					'seireki_low' 		=> date("Y") - 60,
					'seireki_hi' 		=> date("Y") - 15,
					'heisei_low' 		=> 1,
					'heisei_hi' 		=> date("Y") - 2003,
					'showa_low' 		=> date("Y") - 1985,
					'showa_hi' 			=> 64,
					'default_seireki' 	=> date("Y") - $age_default,
					'default_age' 		=> $age_default,
					);

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

// メンバー登録モード
if ($mode == "playerInsert") {

    // 今シーズン個人登録データ
    if ($memberDataClass->rarrySeasonMemberList(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId) == true) {
        // 個人データ
        $nextMemberDatas    = $memberDataClass->getMemberDataList();
        for ($j=0; $j<count($nextMemberDatas); $j++) {
            // 今シーズン登録済み選手ID
            $teamMembersNumbers[] = $nextMemberDatas[$j]["number"];
        }
//print nl2br(print_r($teamMembersNumbers,true));
    }

    for ($i=0; $i<$registNum; $i++) {

        (string)$errorValue[$i] = "";

        $number[$i] = mbHan($number[$i]);
        $kana_f[$i] = mbZen($kana_f[$i]);
        $kana_s[$i] = mbZen($kana_s[$i]);
        $tall[$i] = mbHan($tall[$i]);
        $bd_y[$i] = mbHan($bd_y[$i]);
        $bdError = 0;

        // フォームデータチェック
        if ($name_f[$i] != "" OR $name_s[$i] != "" /* OR $kana_f[$i] != "" OR $kana_s[$i] != "" */) {
            if ($number[$i] != "") {
                if ($paramCheckClass->isNumberCheck($errorMessageObj, $number[$i], 0, 2) == False) {
                    $errorValue[$i] .= "<div>背番号　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
                }
                // チーム登録済み選手の背番号重複チェック
                if ($sts != "comp" AND array_search($number[$i], $teamMembersNumbers) !== FALSE) {
                    $errorValue[$i] .= "<div>背番号　：　<span style=\"font-weight:bold;color:red;\">登録選手に使用されている背番号です。</span></div>\n";
                }
                // フォーム登録選手の背番号重複チェック
                if (isset($distinctDatasCheck["number"]) AND array_search($number[$i],$distinctDatasCheck["number"]) !== FALSE){
                    $errorValue[$i] .= "<div>背番号　：　<span style=\"font-weight:bold;color:red;\">入力のある背番号と重複しています。</span></div>\n";
                }
            }
            if ($paramCheckClass->isNameCheck($errorMessageObj, $name_f[$i], 1, 64, "utf-8") == False) {
                $errorValue[$i] .= "<div>名前(姓)　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
            }
            if ($paramCheckClass->isNameCheck($errorMessageObj, $name_s[$i], 1, 64, "utf-8") == False) {
                $errorValue[$i] .= "<div>名前(名)　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
            }
            // リーガ登録済み選手の重複チェック
            if ($sts != "comp" AND ($userCheckClass->distinctMemberCheck($name_f[$i], $name_s[$i])) == True) {
                //$disinctPlayerData = $userCheckClass->getDistinctMemberData();
                $errorValue[$i] .= "<div>名前　：　<span style=\"font-weight:bold;color:red;\">リーガ東海の登録選手に登録されています。</span></div>\n";
            }
            // フォーム登録選手のフルネーム重複チェック
            if (isset($distinctDatasCheck["names"]) AND array_search($name_f[$i].$name_s[$i],$distinctDatasCheck["names"]) !== FALSE){
                $errorValue[$i] .= "<div>名前　：　<span style=\"font-weight:bold;color:red;\">入力のある名前()と重複しています。</span></div>\n";
            }
            if ($paramCheckClass->there_katakana($errorMessageObj, $kana_f[$i], 2, 64, "utf-8") == False) {
                $errorValue[$i] .= "<div>カナ(姓)　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
            }
            if ($paramCheckClass->there_katakana($errorMessageObj, $kana_s[$i], 2, 64, "utf-8") == False) {
                $errorValue[$i] .= "<div>カナ(名)　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
            }
            if ($paramCheckClass->isNumberCheck($errorMessageObj, $tall[$i], 3, 3) == False) {
                $errorValue[$i] .= "<div>身長　　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
            }
            if ($tall[$i] > 250) {
                $errorValue[$i] .= "<div>身長　　：　<span style=\"font-weight:bold;color:red;\">250以下の範囲にしてください</span></div>\n";
            }
            if ($bd_y[$i] != "") {
                switch ($bd_type[$i]) {
                  case  1 :
                    $bithdayType = "西暦";
                    $insBdYear[$i] = $bd_y[$i];
                    if ($paramCheckClass->isNumberCheck($errorMessageObj, $bd_y[$i], 4, 4) == False) {
                        $errorValue[$i] .= "<div>誕生日　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
                        $bdError++;
                    } else {
                        $birthday_y[$i] = (int)$bd_y[$i];
                        if ($birthday_y[$i] < (date("Y") - $age_hi) OR $birthday_y[$i] > (date("Y") - $age_low)) {
                            $errorValue[$i] .= "<div>誕生日(年)　：　<span style=\"font-weight:bold;color:red;\">西暦選択時は".$ages_array['seireki_low']."&nbsp;～&nbsp;".$ages_array['seireki_hi']."年の範囲にしてください。</span></div>\n";
	                        $bdError++;
                        }
                    }
                    break;
                  case  2 :
                    $bithdayType = "平成";
                    $insBdYear[$i] = $bd_y[$i] + 1988;
                    if ($paramCheckClass->isNumberCheck($errorMessageObj, $bd_y[$i], 1, 2) == False) {
                        $errorValue[$i] .= "<div>誕生日　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
                        $bdError++;
                    } else {
                        $birthday_y[$i] = (int)$bd_y[$i] + 1988;
                        if ($birthday_y[$i] < (date("Y") - $age_hi) OR $birthday_y[$i] > (date("Y") - $age_low)) {
                            $errorValue[$i] .= "<div>誕生日(年)　：　<span style=\"font-weight:bold;color:red;\">平成選択時は".$ages_array['heisei_low']."&nbsp;～&nbsp;".$ages_array['heisei_hi']."年の範囲にしてください。</span></div>\n";
	                        $bdError++;
                        }
                    }
                    break;
                  case  3 :
                    $bithdayType = "昭和";
                    $insBdYear[$i] = $bd_y[$i] + 1926;
                    if ($paramCheckClass->isNumberCheck($errorMessageObj, $bd_y[$i], 2, 2) == False) {
                        $errorValue[$i] .= "<div>誕生日　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
                        $bdError++;
                    } else {
                        $birthday_y[$i] = (int)$bd_y[$i] + 1925;
                        if ($bd_y[$i] > 64 OR $birthday_y[$i] < (date("Y") - $age_hi) OR $birthday_y[$i] > (date("Y") - $age_low)) {
                            $errorValue[$i] .= "<div>誕生日(年)　：　<span style=\"font-weight:bold;color:red;\">昭和選択時は".$ages_array['showa_low']."&nbsp;～&nbsp;".$ages_array['showa_hi']."年の範囲にしてください。</span></div>\n";
	                        $bdError++;
                        }
                    }
                    break;
                }
            } else {
//                $bd_type[$i] = 1;
//                $bithdayType = "西暦";
//                $bd_y[$i] = date('Y') - $age_default;
//                $insBdYear[$i] = date('Y') - $age_default;
                $errorValue[$i] .= "<div>誕生日　：　<span style=\"font-weight:bold;color:red;\">登録してください。</span></div>\n";
            }
            if ($bdError > 0 AND $paramCheckClass->isDateCheck($errorMessageObj, $bd_m[$i], $bd_d[$i], $bd_y[$i]) == False) {
                $errorValue[$i] .= "<div>誕生日　：　<span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
            }

            // エラーがない時
            if ($errorValue[$i] == "") {
                $insertDatas[$i]["number"] = $number[$i];
                ($number[$i] == "") ? $insertDatas[$i]["numberValue"] = "<span style=\"color:red;font-weight:bold;font-size:10px;\">未登録</span>" : $insertDatas[$i]["numberValue"] = $number[$i];
                $insertDatas[$i]["name_f"] = $name_f[$i];
                $insertDatas[$i]["name_s"] = $name_s[$i];
                $insertDatas[$i]["kana_f"] = $kana_f[$i];
                $insertDatas[$i]["kana_s"] = $kana_s[$i];
                $insertDatas[$i]["posision"] = $posision[$i];
                switch ($posision[$i]) {
                  case  1 : $posisionValue[$i] = "ポイントガード"; break;
                  case  2 : $posisionValue[$i] = "シューティングガード"; break;
                  case  3 : $posisionValue[$i] = "スモールフォワード"; break;
                  case  4 : $posisionValue[$i] = "パワーフォワード"; break;
                  case  5 : $posisionValue[$i] = "センター"; break;
                }
                $insertDatas[$i]["posisionValue"] = $posisionValue[$i];
                $insertDatas[$i]["tall"] = $tall[$i];
                $insertDatas[$i]["bd_type"] = $bd_type[$i];
                $insertDatas[$i]["bithdayType"] = $bithdayType;
                $insertDatas[$i]["bd_y"] = $bd_y[$i];
                $insertDatas[$i]["bd_m"] = $bd_m[$i];
                $insertDatas[$i]["bd_d"] = $bd_d[$i];
                // 年齢計算
                $insertDatas[$i]["age"] = floor((date("Ymd") - ($insBdYear[$i].sprintf("%02d",$bd_m[$i]).sprintf("%02d",$bd_d[$i]))) /10000);

                // フォーム内重複チェック用
                $distinctDatasCheck["number"][$i] = $number[$i];
                $distinctDatasCheck["names"][$i] = $name_f[$i].$name_s[$i];

                // 選手登録
                if ($sts == "comp") {
                    $insertMemberData[$insertNum]["number"] = $number[$i];
                    $insertMemberData[$insertNum]["name_first"] = $name_f[$i];
                    $insertMemberData[$insertNum]["name_second"] = $name_s[$i];
                    $insertMemberData[$insertNum]["kana_first"] = $kana_f[$i];
                    $insertMemberData[$insertNum]["kana_second"] = $kana_s[$i];
                    $insertMemberData[$insertNum]["posision"] = $posision[$i];
                    $insertMemberData[$insertNum]["height"] = $tall[$i];
                    $insertMemberData[$insertNum]["birthday"] = $insBdYear[$i]."-".sprintf("%02d",$bd_m[$i])."-".sprintf("%02d",$bd_d[$i]);
                    $insertNum++;
                }
            } else {
                $mode = "new";
                $confErrorNum++;
                $insertDatas[$i]["number"] = "";
                $insertDatas[$i]["name_f"] = "";
                $insertDatas[$i]["name_s"] = "";
                $insertDatas[$i]["kana_f"] = "";
                $insertDatas[$i]["kana_s"] = "";
                $insertDatas[$i]["posision"] = "";
                $insertDatas[$i]["tall"] = "";
                $insertDatas[$i]["bd_type"] = "";
                $insertDatas[$i]["bd_y"] = "";
                $insertDatas[$i]["bd_m"] = "";
                $insertDatas[$i]["bd_d"] = "";
            }

        }
    }
    if (count($insertDatas) == 0) {
        $mode = "new";
    } else {
        if ($sts == "conf") {
            if ($confErrorNum == 0) {
                //$fmButtonValue = "          <p style=\"font-weight:bold;color:green;\">上記選手を2009シーズンの選手登録をします。</p>";
                $fmButtonValue .= $memberInsertButton."&nbsp;".$memberInsertConfBackButton;
            }
        } else if ($sts == "comp") {
//print $_SESSION['resistMember']["memberRegist"]." = SESSION<br />";
            if ($_SESSION['resistMember']["memberRegist"] == "") {
                // トランザクション開始
                $connectDbClass->Query("BEGIN");

                for ($i=0; $i<count($insertMemberData); $i++) {
                    // 選手登録
                    if ($memberDataChangeClass->memberInsert(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId, $insertMemberData[$i]) == False) {
                        $compErrorNum++;
                    }
                }
                // エラーがなければセッション登録
                if ($compErrorNum > 0) {
                    // ロールバック処理
                    $connectDbClass->Query( "ROLLBACK" );
                    $fmButtonValue = "          <p style=\"font-weight:bold;color:red;\">選手登録に失敗しました。</p>
          <p>お手数ですが、リーガ東海事務局<br /><a href=mailto:\"".LIGA_MAIL."\" style=\"font-size:120%;\">".LIGA_MAIL."</a><br />まで報告・お問い合わせ下さい。</p>";
                } else {
                    // コミット処理
                    $connectDbClass->Query( "COMMIT" );
                    // 登録完了セッション発行
                    $_SESSION['resistMember']["memberRegist"] = "insert";
                    $fmButtonValue = "          <p style=\"font-weight:bold;color:blue;\">選手登録が完了しました。</p>";
                }
            }

            $fmButtonValue .= $memberInsertCompButton;
        }
    }
}

// SMARTYにデータを送る
$smarty->assign("registNum", $registNum);
$smarty->assign("bd_type", $bd_type);
$smarty->assign("number", $number);
$smarty->assign("name_f", $name_f);
$smarty->assign("name_s", $name_s);
$smarty->assign("kana_f", $kana_f);
$smarty->assign("kana_s", $kana_s);
$smarty->assign("tall", $tall);
$smarty->assign("bd_y", $bd_y);
$smarty->assign("bd_m", $bd_m);
$smarty->assign("bd_d", $bd_d);
$smarty->assign("posision", $posision);
$smarty->assign("mode", $mode);
$smarty->assign("errorValue", $errorValue);
$smarty->assign("insertDatas", $insertDatas);
$smarty->assign("fmButtonValue", $fmButtonValue);
$smarty->assign("rarryDataArray", $rarryDataArray);
$smarty->assign("ligaMail", LIGA_MAIL);
$smarty->assign("subName", SUB_NAME);

$smarty->assign("age_low", $age_low);
$smarty->assign("age_hi", $age_hi);
$smarty->assign("ages_array", $ages_array);

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>