<?php

//session_start();

//if (!isset($_SESSION['resistMember']["loginTime"])) {
//    header("Location: ./loginError.php");
//}
#print nl2br(print_r($_SESSION['resistMember'],true));
// クラスファイル読み込み
/*
 * @file commons.inc          共通関数
 */
require_once "./common.inc";
//require_once "./serverCheck.inc";

// テンプレート場所の指定
$smarty->config_dir   = dirname(dirname(dirname(__FILE__))).'/smarty_templates/pc/regist/configs/';
$smarty->template_dir = dirname(dirname(dirname(__FILE__))).'/smarty_templates/pc/regist/templates/';
$smarty->compile_dir  = dirname(dirname(dirname(__FILE__))).'/smarty_templates/pc/regist/templates_c/';

// スクリプト名の取得
$script_name =  basename($_SERVER['SCRIPT_NAME'],".php");

// 変数初期値
(int)$errorNums = 0;
(int)$teamId = TEAM_ID;
(int)$rarryId = NEXT_RARRY_ID;
(int)$season = NEXT_RARRY_SEASON;
(int)$currentRarry = NEXT_RARRY_ID;
(string)$fileName = $script_name;
(string)$memberId = 0;
(string)$mode = "";
(string)$sts = "";
(string)$errorValue = array();
(int)$bd_type = 0;
(string)$nameFirst = "";
(string)$nameSecond = "";
(string)$kanaFirst = "";
(string)$kanaSecond = "";
(int)$posision = 0;
(int)$tall = 0;
(int)$bd_y = 0;
(int)$bd_m = 0;
(int)$bd_d = 0;
(string)$posisionView = "";
(string)$birthdayView = "";

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
//print $key." = ".$val."<BR>";
    }
}

// 選手検索モード
if ($mode == "") {
    $mode = "change";
    $playerData = playerData($connectDbClass, NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId, $memberId);
    $number = $playerData["number"];
    $nameFirst = $playerData["nameFirst"];
    $nameSecond = $playerData["nameSecond"];
    $kanaFirst = $playerData["kanaFirst"];
    $kanaSecond = $playerData["kanaSecond"];
    $posision = $playerData["posision"];
    $tall = $playerData["tall"];
    $bd_y = substr($playerData["birthday"], 0, 4);
    $bd_m = substr($playerData["birthday"], 5, 2);
    $bd_d = substr($playerData["birthday"], 8, 2);
//print nl2br(print_r($playerData,true));
} else {

    // 今シーズン個人登録データ
    if ($memberDataClass->rarrySeasonMemberList(NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId) == true) {
        // 個人データ
        $nextMemberDatas    = $memberDataClass->getMemberData();
        for ($j=0; $j<count($nextMemberDatas); $j++) {
            if ($number == $nextMemberDatas[$j]["number"]) { continue; }
            // 今シーズン登録済み選手ID
            $teamMembersNumbers[] = $nextMemberDatas[$j]["number"];
        }
//print nl2br(print_r($nextMemberDatas,true));
    }

    (string)$errorValue["number"] = "";
    (string)$errorValue["name"] = "";
    (string)$errorValue["kana"] = "";
    (string)$errorValue["tall"] = "";
    (string)$errorValue["birthday"] = "";

    $number = mbHan($number);
    $kanaFirst = mbZen($kanaFirst);
    $kanaSecond = mbZen($kanaSecond);
    $tall = mbHan($tall);
    $bd_y = mbHan($bd_y);

    // フォームデータチェック
    if ($paramCheckClass->isNumberCheck($errorMessageValues, $number, 0, 2) == False) {
        $errorValue["number"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</div>\n";
        $errorNums++;
    }
    // チーム登録済み選手の背番号重複チェック
    if (array_search($number, $teamMembersNumbers) !== FALSE) {
        $errorValue["number"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">2009シーズン登録選手に使用されている背番号です。</div>\n";
        $errorNums++;
    }
    if ($paramCheckClass->isNameCheck($errorMessageValues, $nameFirst, 1, 64, "utf-8") == False) {
        $errorValue["name"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</div>\n";
        $errorNums++;
    }
    if ($paramCheckClass->isNameCheck($errorMessageValues, $nameSecond, 1, 64, "utf-8") == False) {
        $errorValue["name"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</div>\n";
        $errorNums++;
    }
    // リーガ登録済み選手の重複チェック
    if (($userCheckClass->distinctMemberCheck($nameFirst, $nameSecond)) == True) {
        $distinctData = $userCheckClass->getDistinctMemberData();
        if ($distinctData["nameFirst"].$distinctData["nameSecond"] != $nameFirst.$nameSecond) {
            $errorValue["name"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">リーガ東海の登録選手に登録されています。</div>\n";
            $errorNums++;
        }
    }
    if ($paramCheckClass->there_katakana($errorMessageValues, $kanaFirst, 2, 64, "utf-8") == False) {
        $errorValue["kana"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</div>\n";
        $errorNums++;
    }
    if ($paramCheckClass->there_katakana($errorMessageValues, $kanaSecond, 2, 64, "utf-8") == False) {
        $errorValue["kana"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</div>\n";
        $errorNums++;
    }
    if ($paramCheckClass->isNumberCheck($errorMessageValues, $tall, 3, 3) == False) {
        $errorValue["tall"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</div>\n";
        $errorNums++;
    }
    if ($tall > 250) {
        $errorValue["tall"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">250以下の範囲にしてください</div>\n";
        $errorNums++;
    }
    if ($bd_y != "") {
        switch ($bd_type) {
          case  1 :
            $bithdayType = "西暦";
            $insBdYear = $bd_y;
            if ($paramCheckClass->isNumberCheck($errorMessageValues, $bd_y, 4, 4) == False) {
                $errorValue["birthday"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</div>\n";
                $errorNums++;
            } else {
                $birthday_y = (int)$bd_y;
                if ($birthday_y < (date("Y") - 60) OR $birthday_y > (date("Y") - 15)) {
                    $errorValue["birthday"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">西暦選択時は".(date("Y") - 60)."&nbsp;～&nbsp;".(date("Y") - 15)."年の範囲にしてください。</div>\n";
                    $errorNums++;
                }
            }
            break;
          case  2 :
            $bithdayType = "平成";
            $insBdYear = $bd_y + 1988;
            if ($paramCheckClass->isNumberCheck($errorMessageValues, $bd_y, 1, 2) == False) {
                $errorValue["birthday"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</div>\n";
                $errorNums++;
            } else {
                $birthday_y = (int)$bd_y + 1988;
                if ($birthday_y < (date("Y") - 60) OR $birthday_y > (date("Y") - 15)) {
                    $errorValue["birthday"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">平成選択時は1&nbsp;～&nbsp;".(date("Y") - 2003)."年の範囲にしてください。</div>\n";
                    $errorNums++;
                }
            }
            break;
          case  3 :
            $bithdayType = "昭和";
            $insBdYear = $bd_y + 1925;
            if ($paramCheckClass->isNumberCheck($errorMessageValues, $bd_y, 2, 2) == False) {
                $errorValue["birthday"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span></div>\n";
                $errorNums++;
            } else {
                $birthday_y = (int)$bd_y + 1926;
                if ($bd_y > 64 OR $birthday_y < (date("Y") - 60) OR $birthday_y > (date("Y") - 15)) {
                    $errorValue["birthday"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">昭和選択時は".(date("Y") - 1986)."&nbsp;～&nbsp;64年の範囲にしてください。</div>\n";
                    $errorNums++;
                }
            }
            break;
        }
    } else {
        $bd_y = 1983;
    }
    if ($paramCheckClass->isDateCheck($errorMessageValues, $bd_m, $bd_d, $bd_y) == False) {
        $errorValue["birthday"] .= "<div style=\"font-size:12px;font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</div>\n";
        $errorNums++;
    }

    if ($errorNums == 0) {
        if ($sts == "conf") {
            $posisionView = masterPosisionData($connectDbClass, $posision);
            $birthdayView = $bithdayType . "&nbsp;" . $bd_y . "年" . $bd_m . "月" . $bd_d . "日";
        } else if ($sts == "comp") {

            // トランザクション開始
            $connectDbClass->Query("BEGIN");

            # メンバーデータを更新
            $sql = "UPDATE ".dbTableName::LT_MEMBER_INFO." SET " .
                         " `name_first` = '" . $nameFirst . "', " .
                         " `name_second` = '" . $nameSecond . "', " .
                         " `kana_first` = '" . $kanaFirst . "', " .
                         " `kana_second` = '" . $kanaSecond . "', " .
                         " `height` = '" . $tall . "', " .
                         " `birthday` = '" . $insBdYear . "-" . $bd_m . "-" . $bd_d . "' " .
                   " WHERE `m_id` = " . $memberId . " " ;
//print $sql;
            # メンバーデータを更新
            $trmsql = "UPDATE ".dbTableName::LT_REGIST_TEAM_MEMBER." SET " .
                         " `number` = '" . $number . "', " .
                         " `posision` = '" . $posision . "' " .
                   " WHERE `r_id` = " . NEXT_RARRY_ID . "" .
                   "  AND  `define` = " . NEXT_RARRY_SEASON . "" .
                   "  AND  `t_id` = " . $teamId . "" .
                   "  AND  `m_id` = " . $memberId . "" ;
//print $trmsql;
            $rs  = $connectDbClass->Query($sql);
            $trmrs  = $connectDbClass->Query($trmsql);
            if ( !$rs OR !$trmrs ) {
                // エラー内容
                $connectDbClass->DbErrorValue = $connectDbClass->GetLastError();
                // ロールバック処理
                $connectDbClass->Query( "ROLLBACK" );
            } else {
                $retData = True;
                // ロールバック処理
                //$connectDbClass->Query( "ROLLBACK" );
                $connectDbClass->Query( "COMMIT" );
            }

            $playerData = playerData($connectDbClass, NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId, $memberId);
            $number = $playerData["number"];
            $nameFirst = $playerData["nameFirst"];
            $nameSecond = $playerData["nameSecond"];
            $kanaFirst = $playerData["kanaFirst"];
            $kanaSecond = $playerData["kanaSecond"];
            $posision = $playerData["posision"];
            $tall = $playerData["tall"];
            //$bd_y = substr($playerData["birthday"], 0, 4);
            $bd_m = substr($playerData["birthday"], 5, 2);
            $bd_d = substr($playerData["birthday"], 8, 2);
        }
    } else {
        $sts = "";
    }
}

// 選手データ
function playerData($connect, $rarryId, $season, $teamId, $memberId) {

    $buf = array();

    # チーム登録メンバーデータの取得
    $sql = "SELECT " .
                   " LRTM.`number` AS number, " .
                   " LMI.`name_first` AS nameFirst, " .
                   " LMI.`name_second` AS nameSecond, " .
                   " LMI.`kana_first` AS kanaFirst, " .
                   " LMI.`kana_second` AS kanaSecond, " .
                   " CASE when LMI.`height`   IS NULL then '未登録' ELSE LMI.`height` END tall, " .
                   " CASE when LMI.`birthday` IS NULL then '未登録' ELSE LMI.`birthday` END birthday, " .
                   " CASE when LMI.`birthday` IS NULL then '--'     ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END age, " .
                   " LRTM.`posision` AS posision, " .
                   " LRTM.`captain_flg` AS captainFlg " .
                " FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
                " LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI " .
                "        ON LRTM.`m_id` = LMI.`m_id`" .
                " WHERE LRTM.`r_id` = " . $rarryId . "" .
                "  AND  LRTM.`define` = " . $season . "" .
                "  AND  LRTM.`t_id` = " . $teamId . "" .
                "  AND  LRTM.`m_id` = " . $memberId . "" ;
//print $sql;
    $rs  = $connect->Query($sql);
    if(!$rs){ return false; }
    $nums = $connect->GetRowCount($rs);
    // データがあったら登録状態の取得
    if($nums > 0){
        $data   = $connect->FetchRow($rs);       // １行Ｇｅｔ
        $buf["number"] = $data["number"];
        $buf["nameFirst"] = $data["nameFirst"];
        $buf["nameSecond"] = $data["nameSecond"];
        $buf["kanaFirst"] = $data["kanaFirst"];
        $buf["nameSecond"] = $data["nameSecond"];
        $buf["kanaSecond"] = $data["kanaSecond"];
        $buf["tall"] = $data["tall"];
        $buf["birthday"] = $data["birthday"];
        $buf["age"] = $data["age"];
        $buf["posision"] = $data["posision"];
        $buf["captainFlg"] = $data["captainFlg"];
    }
    return $buf;
}

// ポジションデータ
function masterPosisionData($connect, $posision) {

    (string)$posisionView = "";

    # チーム登録メンバーデータの取得
    $sql = "SELECT " .
                   " `posision_jpn` AS posisionView " .
                " FROM ".dbTableName::M_POSISION_INFO." " .
                " WHERE `id` = " . $posision . "" ;
//print $sql;
    $rs  = $connect->Query($sql);
    if(!$rs){ return false; }
    $nums = $connect->GetRowCount($rs);
    // データがあったら登録状態の取得
    if($nums > 0){
        $data   = $connect->FetchRow($rs);       // １行Ｇｅｔ
        $posisionView = $data["posisionView"];
    }
    return $posisionView;
}


// SMARTYにデータを送る
$smarty->assign("memberId", $memberId);
$smarty->assign("bd_type", $bd_type);
$smarty->assign("number", $number);
$smarty->assign("nameFirst", $nameFirst);
$smarty->assign("nameSecond", $nameSecond);
$smarty->assign("kanaFirst", $kanaFirst);
$smarty->assign("kanaSecond", $kanaSecond);
$smarty->assign("tall", $tall);
$smarty->assign("bd_type", $bd_type);
$smarty->assign("bd_y", $bd_y);
$smarty->assign("bd_m", $bd_m);
$smarty->assign("bd_d", $bd_d);
$smarty->assign("posision", $posision);
$smarty->assign("posisionView", $posisionView);
$smarty->assign("birthdayView", $birthdayView);
$smarty->assign("mode", $mode);
$smarty->assign("sts", $sts);
$smarty->assign("errorValue", $errorValue);

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>