<?php

ini_set('error_reporting', E_ALL & ~E_NOTICE);
ini_set( 'display_errors', "1" );
ini_set( 'safe_mode', "1" );
//error_reporting(E_ALL);

// セッション開始
session_start();

// クラスファイル読み込み
/*
 * @class connectionDB
 * @file commons                  共通関数
 * @file paramCheckClass          パラメータチェック関数
 * @file connectionDBClass        データベース接続関数
 * @file sqlCodeClass             データベースSQLコード設定関数
 * @file userDataClass            共通関数
 */
$mainDir = dirname(dirname(__FILE__));
//$mainDir = '';
$phpShereDir = "/php_shere/liga/";
$phpCoreDir = "/php_core/";
//print nl2br(print_r($_SERVER,true));
//print nl2br(print_r($_SESSION,true));

require_once $mainDir.$phpShereDir."database/connectionDBClass.inc";
require_once $mainDir.$phpShereDir."database/sqlCodeClass.inc";

require_once $mainDir.$phpShereDir."common/errorCheck.inc";
require_once $mainDir.$phpShereDir."common/errorMessageValues.inc";
require_once $mainDir.$phpShereDir."common/paramCheckClass.inc";
require_once $mainDir.$phpShereDir."common/stringProcess.inc";
require_once $mainDir.$phpShereDir."common/page.inc";

//require_once $mainDir.$phpShereDir."common/thumbnail.inc";
require_once $mainDir.$phpShereDir."common/createThumbnailImage.inc";

require_once $mainDir.$phpShereDir."datas/rarryData.inc";
require_once $mainDir.$phpShereDir."datas/teamDataClass.inc";
require_once $mainDir.$phpShereDir."datas/teamDataChangeClass.inc";
require_once $mainDir.$phpShereDir."datas/schejuleData.inc";
require_once $mainDir.$phpShereDir."datas/schejuleDataChangeClass.inc";
require_once $mainDir.$phpShereDir."datas/mastarHallData.inc";
require_once $mainDir.$phpShereDir."datas/mastarBlockData.inc";
require_once $mainDir.$phpShereDir."datas/versusResult.inc";
require_once $mainDir.$phpShereDir."datas/versusData.inc";
require_once $mainDir.$phpShereDir."datas/indiScoreData.inc";
require_once $mainDir.$phpShereDir."datas/memberDataClass.inc";
require_once $mainDir.$phpShereDir."datas/memberDataChangeClass.inc";

//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/database/connectionDBClass.inc';
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/database/sqlCodeClass.inc';

//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/common/errorCheck.inc';
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/common/errorMessageValues.inc';
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/common/paramCheckClass.inc';
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/common/stringProcess.inc';

//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/rarryData.inc';              // 大会チームデータ
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/teamData.inc';               // 大会登録チームデータ
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/teamDataChangeClass.inc';    // チームデータ登録・更新クラス
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/schejuleData.inc';           // スケジュールデータ
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/mastarHallData.inc';         // 会場データ
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/mastarBlockData.inc';        // 大会ブロックデータ
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/versusResult.inc';           // 対戦成績データ
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/versusData.inc';
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/indiScoreData.inc';          // 個人成績データ
//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/datas/memberDataClass.inc';        // 選手データ

//require_once $_SERVER['DOCUMENT_ROOT'].'/php/pc/shere/common/page.inc';                  // ページ遷移クラス

// 文字コードを管理用に設定
mb_language("Japanese");
mb_internal_encoding('utf-8');
/* HTTP出力文字エンコーディング */
mb_http_output("utf-8");
mb_detect_order("utf-8");
#mb_regex_encoding("utf-8");

// 各種オブジェクトを生成
$connectionDBObj = new connectionDBClass;
$sqlCodeClassObj = new sqlCodeClass;
// 大会情報オブジェクト
$rarryDataObj = new rarryData;
// 会場オブジェクト
$mHallDataObj = new mastarHallData;
// 大会ブロックオブジェクト
$mBlockDataObj = new mastarBlockData;
// 対戦成績オブジェクト
$versusResultObj = new versusResult;
$versusDataObj = new versusData;
// 全チームデータオブジェクト
$teamDataObj = new teamDataClass;
// チームデータ変更オブジェクト
$teamDataChangeObj = new teamDataChangeClass;
// スケジュールデータオブジェクト
$schejuleDataObj = new schejuleData;
// スケジュールデータ変更オブジェクト
$schejuleDataChangeObj = new schejuleDataChangeClass;
// 個人成績オブジェクト生成
$indiScoreDataObj = new indiScoreData;
//選手データオブジェクト
$memberDataObj = new memberDataClass;
// 選手データ変更オブジェクト
$memberDataChangeObj = new memberDataChangeClass;

// エラーチェックオブジェクト生成
$errorCheckObj = new errorCheck;
// エラーメッセージオブジェクト生成
$errorMessageObj = new errorMessageValues;
// パrメータチェックオブジェクト生成
$paramCheckClassObj = new paramCheckClass;

// 日本語検索用にデータベース文字コードをutf-8にセットする
$sqlCodeClassObj->dbSetCode("utf-8");

// SQLエラー
if (isset($sqlCode->dbError)) {
    $dbErrors = $sqlCode->dbError;
}

// 大会IDのセッションが無ければ一旦TOPへ
if (!isset($_SESSION["rarryId"]) OR $_SESSION["rarryId"] == '') {
    header("Location: ./index.php");
}

?>