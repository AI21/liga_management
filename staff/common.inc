<?php
//ini_set( 'display_errors', "1" );
//ini_set( 'safe_mode', "1" );
//error_reporting(E_ALL);

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
$strPlayerAddEndDays = "2016-07-31";   // 選手追加・放出可能期日
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
    '1' => array('loginStart' => '', 'loginEnd' => '2016-07-31', 'playerAdd' => '2016-07-31', 'opning' => FALSE),
    '25' => array('loginStart' => '', 'loginEnd' => '2016-07-31', 'playerAdd' => '2016-07-31', 'opning' => FALSE),
    '26' => array('loginStart' => '', 'loginEnd' => '2016-07-31', 'playerAdd' => '2016-07-31', 'opning' => FALSE),
    '6' => array('loginStart' => '', 'loginEnd' => '2016-07-31', 'playerAdd' => '2016-07-31', 'opning' => FALSE),
    '7' => array('loginStart' => '', 'loginEnd' => '2016-07-31', 'playerAdd' => '2016-07-31', 'opning' => FALSE),
    '8' => array('loginStart' => '', 'loginEnd' => '2016-07-31', 'playerAdd' => '2016-07-31', 'opning' => FALSE),
    '20' => array('loginStart' => '', 'loginEnd' => '2016-07-31', 'playerAdd' => '2016-07-31', 'opning' => FALSE),
);

//特別対応でログイン可能なチーム
$arySpecialTeamLimit = array(
    // 78 => array('playerAdd' => '2016-03-04'),
    // 181 => array('playerAdd' => '2016-03-04'),
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
// ログイン開始日
define ("REGIST_START_DATE", "2013-02-01");
define ("REGIST_START_TIME", "15:00:00");

// チームデータ確認期日
define ("TEAM_DATA_CONF_LIMIT", "2016-03-31");
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
// 移籍(放出)が可能か？
define ("MEMBER_DISCHARGE", TRUE);
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
define ("SMARTY_CONFIG_DIR",    $mainDir."/staff/smarty_templates/configs/");
define ("SMARTY_TEMPLATE_DIR",  $mainDir."/staff/smarty_templates/templates/");
define ("SMARTY_COMPLETE_DIR",  $mainDir."/staff/smarty_templates/templates_c/");

// メール送信用クラス
$sendMailClass = new sendMailClass(LIGA_MAIL, AUTO_SEND_MAIL, './mail');

$script_path = $_SERVER['SCRIPT_NAME'];
$fileName = basename($script_path, '.php');

if ($rarryDataObj->rarryDetails(NEXT_RARRY_ID) == true) {
    $rarryDataArray = $rarryDataObj->getRarryDetail();
}
if ($rarryDataObj->rarryDetails(PRE_RARRY_ID) == true) {
    $preRarryDataArray = $rarryDataObj->getRarryDetail();
}

// ログインページ以外
if ( $fileName != 'login' && $fileName != 'reissue' && $fileName != 'login_admin') {

    // 管理者モード判定
    if (isset($_SESSION['resistMember']["admin_login"]) && $_SESSION['resistMember']["admin_login"] != '') {
        $adminMode = true;
        $readMode = '';
    } else {
        $adminMode = false;
    }

}
?>