<?php

// クラスファイル読み込み
/*
 * @class connectionDB  
 * @file commons                  共通関数
 * @file paramCheckClass          パラメータチェック関数
 * @file connectionDBClass        データベース接続関数
 * @file sqlCodeClass             データベースSQLコード設定関数
 * @file userDataClass            共通関数
 */
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/database/connectionDBClass.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/database/sqlCodeClass.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/common/userCheckClass.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/common/stringProcess.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/common/paramCheckClass.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/common/errorMessageValues.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/datas/teamDataClass.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/datas/memberDataClass.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/datas/unspecificationDataClass.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/datas/memberDataChangeClass.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/pc/shere/datas/teamDataChangeClass.inc";
require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/smarty/libs/Smarty.class.php";

// 文字コードを管理用に設定
mb_language("Japanese");
mb_internal_encoding('utf-8');
/* HTTP出力文字エンコーディング */
mb_http_output("utf-8");
mb_detect_order("utf-8");
#mb_regex_encoding("utf-8");

// 各種オブジェクトを生成
$smarty = new Smarty;
$connectDbClass = new connectionDBClass;        # データベース接続オブジェクト
$paramCheckClass = new paramCheckClass();       # パラメータチェックオブジェクト
$userCheckClass = new userCheckClass;           # ユーザーデータクラス
$teamDataClass = new teamDataClass;             # チーム情報クラス
$memberDataClass = new memberDataClass;         # メンバー情報クラス
$errorMessageValues = new errorMessageValues;   # エラーメッセージオブジェクト
$unspecificationDataClass = new unspecificationDataClass;
$teamDataChangeClass = new teamDataChangeClass;
$memberDataChangeClass = new memberDataChangeClass;
$sqlCodeClass = new sqlCodeClass;

// 日本語検索用にデータベース文字コードをutf-8にセットする
$sqlCodeClass->dbSetCode("utf-8");

// SQLエラー
if (isset($sqlCode->dbError)) {
    $dbErrors = $sqlCode->dbError;
}

## ページ設定 ##
// 大会ID
define ("NEXT_RARRY_ID", "2");
// 大会シーズン
define ("NEXT_RARRY_SEASON", "1");
// 現在大会ID
define ("PRE_RARRY_ID", "1");
// 現在大会シーズン
define ("PRE_RARRY_SEASON", "3");
// ログイン可能日
define ("REGIST_LIMIT_DATE", "2008-12-24");

## 特殊設定 ##
// チームID
define ("TEAM_ID", "11");

if (mktime(0,0,0,date("Y"), date("m"), date("d")) > mktime(0,0,0,substr(REGIST_LIMIT_DATE, 5, 2),substr(REGIST_LIMIT_DATE, 8, 2),substr(REGIST_LIMIT_DATE, 0, 4))) {
    header("Location: ./loginError.php");
}


$optionArrays = array(
                     "practiceDay" =>array(
                         "1" => "月曜日",
                         "2" => "火曜日",
                         "3" => "水曜日",
                         "4" => "木曜日",
                         "5" => "金曜日",
                         "6" => "土曜日",
                         "7" => "日曜日"
                         ),
                     "teamColor" =>array(
                         "black" => "黒色",
                         "silver" => "シルバー",
                         "white" => "白色",
                         "maroon" => "マルーン",
                         "red" => "赤色",
                         "purple" => "紫色",
                         "pink" => "ピンク",
                         "green" => "濃緑色",
                         "lime" => "緑色",
                         "yellow" => "黄色",
                         "navy" => "紺色",
                         "blue" => "青色",
                         "aqua" => "水色",
                         "orange" => "オレンジ"
                         ),
                     "teamHomeColor" =>array(
                         "cwhite" => "白色",
                         "cpink" => "ピンク",
                         "cred" => "赤色",
                         "cviolet" => "紫色",
                         "corange" => "オレンジ",
                         "cyerrow" => "黄色",
                         "cmizu" => "水色",
                         "cblue" => "青色",
                         "crightgreen" => "薄緑",
                         "cgreen" => "緑色",
                         "cglay" => "グレー"
                         ),
                     "teamAwayColor" =>array(
                         "cwhite" => "白色",
                         "cpink" => "ピンク",
                         "cred" => "赤色",
                         "cviolet" => "紫色",
                         "corange" => "オレンジ",
                         "cyerrow" => "黄色",
                         "cmizu" => "水色",
                         "cblue" => "青色",
                         "crightgreen" => "薄緑",
                         "cgreen" => "緑色",
                         "cglay" => "グレー"
                         )
                    );

?>