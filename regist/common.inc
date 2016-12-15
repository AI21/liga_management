<?php
//ini_set( 'display_errors', "1" );
//ini_set( 'safe_mode', "1" );
//error_reporting(E_ALL);
//
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', '1');

//if ($_SERVER['REMOTE_ADDR'] != '210.254.40.8') {
//    header("Location: ./loginError.php");
//    exit;
//}
//print nl2br(print_r($_SERVER,true));

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
$phpShereDir = "/php_shere/liga/";
$phpCoreDir = "/php_core/";

require_once $mainDir.$phpShereDir."database/connectionDBClass.inc";
require_once $mainDir.$phpShereDir."database/sqlCodeClass.inc";

require_once $mainDir.$phpShereDir."common/userCheckClass.inc";
require_once $mainDir.$phpShereDir."common/stringProcess.inc";
require_once $mainDir.$phpShereDir."common/paramCheckClass.inc";
require_once $mainDir.$phpShereDir."common/errorMessageValues.inc";
require_once $mainDir.$phpShereDir."common/createThumbnailImage.inc";

require_once $mainDir.$phpShereDir."common/sendMailClass.inc";

require_once $mainDir.$phpShereDir."datas/rarryData.inc";
require_once $mainDir.$phpShereDir."datas/teamDataClass.inc";
require_once $mainDir.$phpShereDir."datas/memberDataClass.inc";
require_once $mainDir.$phpShereDir."datas/unspecificationDataClass.inc";
require_once $mainDir.$phpShereDir."datas/memberDataChangeClass.inc";
require_once $mainDir.$phpShereDir."datas/teamDataChangeClass.inc";

require_once $mainDir.$phpCoreDir."smarty/libs/Smarty.class.php";

// 文字コードを管理用に設定
mb_language("Japanese");
mb_internal_encoding('utf-8');
/* HTTP出力文字エンコーディング */
mb_http_output("utf-8");
mb_detect_order("utf-8");
//mb_regex_encoding("utf-8");

// 各種オブジェクトを生成
$smarty = new Smarty;
$connectDbClass = new connectionDBClass;	// データベース接続オブジェクト
$paramCheckClass = new paramCheckClass();	// パラメータチェックオブジェクト
$userCheckClass = new userCheckClass;		// ユーザーデータクラス
$rarryDataObj = new rarryData;				// 大会情報クラス
$teamDataClass = new teamDataClass;			// チーム情報クラス
$memberDataClass = new memberDataClass;		// メンバー情報クラス
$errorMessageObj = new errorMessageValues;	// エラーメッセージオブジェクト
$unspecificationDataClass = new unspecificationDataClass;
$teamDataChangeClass = new teamDataChangeClass;
$memberDataChangeClass = new memberDataChangeClass;
//$teamDataObj = new teamDatas;
$sqlCodeClass = new sqlCodeClass;

// 日本語検索用にデータベース文字コードをutf-8にセットする
$sqlCodeClass->dbSetCode("utf-8");

// SQLエラー
if (isset($sqlCode->dbError)) {
    $dbErrors = $sqlCode->dbError;
}

$blnMaintenanceMode = FALSE; // メンテナンスモード　FALSE:通常運用	TRUE:メンテナンス状態

$noMobileTeam = array();    // 携帯アドレスを登録できないチーム
$noLimitTeam = array();     // ログイン規制を持たないチーム
$readMode = '';
$strAdminAddr = '60.130.50.23';   // 管理者IP
// $strAdminAddr = '126.143.5.84';   // 管理者IP
$strUserAddr = '';   // ログインユーザーIP
$loginEndDays = "2016-12-31";   // ログイン終了日
$loginEndTime = "23:59:59";     // ログイン終了時間
$strPlayerAddEndDays = "2017-02-28";   // 選手追加・放出可能期日
$strPlayerAddEndTime = "23:59:59";     // 選手追加・放出可能時間
$strLoginStartYear = '';
$strLoginStartMonth = '';
$strLoginStartDay = '';
$strLoginEndYear ='';
$strLoginEndMonth = '';
$strLoginEndDay = '';
$strPlayerAddEndYear ='';
$strPlayerAddEndMonth = '';
$strPlayerAddEndDay = '';

//基本情報変更不可能チーム
$basisDataNotTeamid = array();

// クラス独自のログイン期日設定
$aryLoginClassSetting = array(
    '1' => array('loginStart' => '', 'loginEnd' => '2016-12-31', 'playerAdd' => '2016-09-30', 'opning' => FALSE),
    '25' => array('loginStart' => '', 'loginEnd' => '2016-12-31', 'playerAdd' => '2016-09-30', 'opning' => FALSE),
    '26' => array('loginStart' => '', 'loginEnd' => '2016-12-31', 'playerAdd' => '2016-09-30', 'opning' => FALSE),
    '6' => array('loginStart' => '', 'loginEnd' => '2016-12-31', 'playerAdd' => '2016-09-30', 'opning' => FALSE),
    '7' => array('loginStart' => '', 'loginEnd' => '2016-12-31', 'playerAdd' => '2016-09-30', 'opning' => FALSE),
    '8' => array('loginStart' => '', 'loginEnd' => '2016-12-31', 'playerAdd' => '2016-09-30', 'opning' => FALSE),
    '20' => array('loginStart' => '', 'loginEnd' => '2016-12-31', 'playerAdd' => '2016-09-30', 'opning' => FALSE),
);

//特別対応でログイン可能なチーム
$arySpecialTeamLimit = array(
    // 78 => array('playerAdd' => '2016-03-04'),
    // 181 => array('playerAdd' => '2016-03-04'),
);

$optionArrays = array(
    "practiceDay" =>array(
        "1" => "月曜日", "2" => "火曜日", "3" => "水曜日", "4" => "木曜日",
        "5" => "金曜日", "6" => "土曜日", "7" => "日曜日"
    ),
    "teamColor" =>array(
        "black" => "黒色", "silver" => "シルバー", "white" => "白色",
        "maroon" => "マルーン", "red" => "赤色", "purple" => "紫色", "pink" => "ピンク",
        "green" => "濃緑色", "lime" => "緑色", "yellow" => "黄色",
        "navy" => "紺色", "blue" => "青色", "aqua" => "水色",
        "orange" => "オレンジ", "gold" => "ゴールド"
    ),
     "teamHomeColor" =>array(
        "white" => "白色",
        "pink" => "ピンク", "light-red" => "赤色", "light-purple" => "薄紫色",
        "orange" => "オレンジ", "light-yellow" => "黄色",
        "aqua" => "水色", "light-blue" => "青色", "light-green" => "薄緑",
        "silver" => "シルバー", "gold" => "ゴールド"
    ),
     "teamAwayColor" =>array(
        "black" => "黒色", "red" => "赤色", "purple" => "紫色",
        "orange" => "オレンジ", "yellow" => "黄色",
        "blue" => "青色", "green" => "濃緑色",
        "gold" => "ゴールド"
    )
);

/*
 * メンテナンスモード
 * false:通常運用	true:メンテナンス状態
 */
 $strUserAddr = $_SERVER['REMOTE_ADDR'];   // ログインユーザーIP
if ( $blnMaintenanceMode == TRUE && $strUserAddr != $strAdminAddr ) {
	header("Location: ./maintenance.php");
} else {
    // print nl2br(print_r($_SESSION,1));
}

## ページ設定 ##
// 現在大会と登録大会が関連しているか？
define ("RARRY_RELATION", true);
// 大会ID
define ("NEXT_RARRY_ID", "34");
// 大会シーズン
define ("NEXT_RARRY_SEASON", "1");
// 特殊処理
$tm_regist_teams = array();
if (empty($_SESSION['resistMember']) == FALSE && in_array($_SESSION['resistMember']["teamId"], $tm_regist_teams)) {
	// 現在大会ID
	define ("PRE_RARRY_ID", "30");
	// 現在大会シーズン
	define ("PRE_RARRY_SEASON", "1");
} else {
	// 前回大会ID
	define ("PRE_RARRY_ID", "30");
	// 前回大会シーズン
	define ("PRE_RARRY_SEASON", "1");
}
// ログイン開始日
define ("REGIST_START_DATE", "2013-02-01");
define ("REGIST_START_TIME", "15:00:00");

// チームデータ確認期日
define ("TEAM_DATA_CONF_LIMIT", "2017-03-31");
// リーガ東海メールアドレス
define ("LIGA_MAIL", "ligatokai@gmail.com");
// 自動送信用メールアドレス
define ("AUTO_SEND_MAIL", "ligatokai@gmail.com");
// 機能副題
define ("SUB_NAME", "選手登録");
// 設置サーバーホスト名
define ("SERVER_HOST", "liga-tokai-com.ssl-netowl.jp");
// 個人登録費用の発生
define ("INDI_PAYMENT_FLAG", TRUE);
// 新規追加が可能か？
define ("MEMBER_REGIST", TRUE);
// 移籍(放出)が可能か？
define ("MEMBER_DISCHARGE", FALSE);
// 登録完了時の自動メール配信用テンプレートファイル
define ("REGIST_COMP_MAIL_TEMP_USER_OPNING", 'indi_regist_comp_user_opening.txt');
// 登録完了時の自動メール配信用テンプレートファイル
define ("REGIST_COMP_MAIL_TEMP_USER", 'indi_regist_comp_user.txt');
// 登録完了時の自動メール配信用テンプレートファイル
//define ("REGIST_COMP_MAIL_TEMP_MASTER", 'indi_regist_comp_tm_master.txt');
define ("REGIST_COMP_MAIL_TEMP_MASTER", 'indi_regist_comp_master.txt');
// メンバー登録費用の振り込み期限（シーズン開幕時のみ）
define ("TRANSFER_LIMIT_DATE", "2016-03-21");

// テンプレート名の指定
define ("SMARTY_CONFIG_DIR",    $mainDir."/regist/smarty_templates/configs/");
define ("SMARTY_TEMPLATE_DIR",  $mainDir."/regist/smarty_templates/templates/");
define ("SMARTY_COMPLETE_DIR",  $mainDir."/regist/smarty_templates/templates_c/");

// メール送信用クラス
$sendMailClass = new sendMailClass(LIGA_MAIL, AUTO_SEND_MAIL, './mail');

$script_path = $_SERVER['SCRIPT_NAME'];
$fileName = basename($script_path, '.php');
$strLoginTeamRegistClass = ( empty($_SESSION['resistMember']["registClass"]) == FALSE ) ? $_SESSION['resistMember']["registClass"] : '';
$strLoginTeamId = ( empty($_SESSION['resistMember']["teamId"]) == FALSE ) ? $_SESSION['resistMember']["teamId"] : '';

if ($rarryDataObj->rarryDetails(NEXT_RARRY_ID) == true) {
    $rarryDataArray = $rarryDataObj->getRarryDetail();
}
if ($rarryDataObj->rarryDetails(PRE_RARRY_ID) == true) {
    $preRarryDataArray = $rarryDataObj->getRarryDetail();
}
// トーナメントの時
(int)$preRarryId = PRE_RARRY_ID;
(int)$preRarrySeason = PRE_RARRY_SEASON;
$aryPastRarryData = array();
if ($fileName != 'reissue' && $fileName != 'team_edit_picture' ) {
	if ($rarryDataArray['type'] == 2) {
		if ($teamDataClass->teamRegistThePastRarryData(NEXT_RARRY_ID, $strLoginTeamId)) {
			$aryPastRarryData = $teamDataClass->getTeamRegistThePastRarryData();
			$preRarryId = $aryPastRarryData['r_id'];
			$preRarrySeason = $aryPastRarryData['progress'];
		}
	}
}

// ログインページ以外
if ( $fileName != 'login' && $fileName != 'reissue' && $fileName != 'login_admin') {

    $strLoginStartDays = REGIST_START_DATE;

    // 設定にないクラスのログインチームはエラー
    if( $strLoginTeamRegistClass == '' ) {
        header("Location: ./loginError.php?noClass");
    }

    // クラス毎にログイン開始日付の設定がある場合
    if ( empty($aryLoginClassSetting[$strLoginTeamRegistClass]['loginStart']) == FALSE ) {
        $strLoginStartDays = $aryLoginClassSetting[$strLoginTeamRegistClass]['loginStart'];
    }
    // クラス毎にログイン終了日時の設定がある場合
    if ( empty($aryLoginClassSetting[$strLoginTeamRegistClass]['loginEnd']) == FALSE ) {
        $loginEndDays = $aryLoginClassSetting[$strLoginTeamRegistClass]['loginEnd'];
    }
    // クラス毎に選手追加・放出可能期日の設定がある場合
    if ( empty($aryLoginClassSetting[$strLoginTeamRegistClass]['playerAdd']) == FALSE ) {
        $strPlayerAddEndDays = $aryLoginClassSetting[$strLoginTeamRegistClass]['playerAdd'];
    }

    // 特別対応するチームがある場合
    if ( count($arySpecialTeamLimit) > 0 && empty($arySpecialTeamLimit[$strLoginTeamId]) == FALSE ) {
        $strLoginStartDays = ( empty($arySpecialTeamLimit[$strLoginTeamId]['loginStart']) == FALSE ) ? $arySpecialTeamLimit[$strLoginTeamId]['loginStart'] : '';
        $loginEndDays = ( empty($arySpecialTeamLimit[$strLoginTeamId]['loginEnd']) == FALSE ) ? $arySpecialTeamLimit[$strLoginTeamId]['loginEnd'] : $loginEndDays;
        $loginEndTime = ( empty($arySpecialTeamLimit[$strLoginTeamId]['times']) == FALSE ) ? $arySpecialTeamLimit[$strLoginTeamId]['times'] : $loginEndTime;
        $strPlayerAddEndDays = ( empty($arySpecialTeamLimit[$strLoginTeamId]['playerAdd']) == FALSE ) ? $arySpecialTeamLimit[$strLoginTeamId]['playerAdd'] : $strPlayerAddEndDays;
        $strPlayerAddEndTime = ( empty($arySpecialTeamLimit[$strLoginTeamId]['times']) == FALSE ) ? $arySpecialTeamLimit[$strLoginTeamId]['times'] : $strPlayerAddEndTime;
    }

    // ログイン開始日付
    $strLoginStartYear = substr($strLoginStartDays, 0, 4);
    $strLoginStartMonth = substr($strLoginStartDays , 5, 2);
    $strLoginStartDay = substr($strLoginStartDays , 8, 2);
    //ログイン終了日時
    $strLoginEndYear = substr($loginEndDays, 0, 4);
    $strLoginEndMonth = substr($loginEndDays, 5, 2);
    $strLoginEndDay = substr($loginEndDays, 8, 2);
    $strLoginEndHour = substr($loginEndTime, 0, 2);
    $strLoginEndMinutie = substr($loginEndTime, 3, 2);
    $strLoginEndTime = substr($loginEndTime, 6, 2);
    //選手追加・放出可能期日時
    $strPlayerAddEndYear = substr($strPlayerAddEndDays, 0, 4);
    $strPlayerAddEndMonth = substr($strPlayerAddEndDays, 5, 2);
    $strPlayerAddEndDay = substr($strPlayerAddEndDays, 8, 2);
    $strPlayerAddEndHour = substr($strPlayerAddEndTime, 0, 2);
    $strPlayerAddEndMinutie = substr($strPlayerAddEndTime, 3, 2);
    $strPlayerAddEndTime = substr($strPlayerAddEndTime, 6, 2);


    // ログイン終了日の定数を設定
    define ("REGIST_LIMIT_DATE", $loginEndDays);

    // print $strLoginStartDays." = START<br />";
    // print $strLoginStartYear." = YY<br />";
    // print $strLoginStartMonth." = MM<br />";
    // print $strLoginStartDay." = DD<br />";
    // print $loginEndDays." = END<br />";
    // print $strLoginEndHour." = HH<br />";
    // print $strLoginEndMinutie." = II<br />";
    // print $strLoginEndTime." = JJ<br />";
    // print $strPlayerAddEndDays." = AddEND<br />";
    // print $strPlayerAddEndYear." = YY<br />";
    // print $strPlayerAddEndMonth." = MM<br />";
    // print $strPlayerAddEndDay." = DD<br />";
    // print $strPlayerAddEndHour." = HH<br />";
    // print $strPlayerAddEndMinutie." = II<br />";
    // print $strPlayerAddEndTime." = JJ<br />";

    $strNowTimestamp = time();
    $strLoginStartTimestamp = mktime( 0, 0, 0, $strLoginStartMonth, $strLoginStartDay, $strLoginStartYear );
    $strLoginEndTimestamp = mktime( $strLoginEndHour, $strLoginEndMinutie, $strLoginEndTime, $strLoginEndMonth, $strLoginEndDay, $strLoginEndYear );
    $strPlayerAddEndTimestamp = mktime( $strPlayerAddEndHour, $strPlayerAddEndMinutie, $strPlayerAddEndTime, $strPlayerAddEndMonth, $strPlayerAddEndDay, $strPlayerAddEndYear );

    if ( $strUserAddr != $strAdminAddr ) {
        // ログイン開始日をまではログインさせない。
        if ( $strNowTimestamp < $strLoginStartTimestamp ) {
            header("Location: ./loginError.php");
            // print $strLoginStartTimestamp." = StartTime<br />";
        }
        if ( $strNowTimestamp > $strLoginEndTimestamp ) {
            header("Location: ./loginError.php");
            // print $strLoginEndTimestamp." = EndTime<br />";
        }

        // データ更新可能日を過ぎていたら閲覧モードに設定
        if ( $strNowTimestamp > $strPlayerAddEndTimestamp ) {
        // print $strPlayerAddEndTimestamp." = AddEndTime<br />";
            $readMode = 'readOnly';
        }
    }

    // IDパスワード再発行ページ以外
    if ( $fileName != 'reissue' ) {
    	if ($strUserAddr != $strAdminAddr ) {
    		// ログイン開始日を過ぎているか？
    		if (time() < mktime(substr(REGIST_START_TIME, 0, 2),substr(REGIST_START_TIME, 3, 2),substr(REGIST_START_TIME, 6, 2),substr(REGIST_START_DATE, 5, 2),substr(REGIST_START_DATE, 8, 2),substr(REGIST_START_DATE, 0, 4))) {
    		// header("Location: ./loginError.php");
            }
    	}
    	if ( in_array($strLoginTeamId, $basisDataNotTeamid) == TRUE ) {
    		$readMode = 'readOnly';
    	}
    }

    // 管理者モード判定
    if (isset($_SESSION['resistMember']["admin_login"]) && $_SESSION['resistMember']["admin_login"] != '') {
        $adminMode = true;
        $readMode = '';
    } else {
        $adminMode = false;
    }

}
?>