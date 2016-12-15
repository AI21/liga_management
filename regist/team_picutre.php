<?php
////////////////////////////////////////////////////
/*
 * チーム写真表示
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array();
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array();

session_start();

require_once './common.inc';

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "GET") {
	while(list ($key, $val) = each($_GET)) {
		$$key = $val;
	}
} else if ($strRequestMethod == "POST") {
	while(list ($key, $val) = each($_POST)) {
		$$key = $val;
#print $key." = ".$val."<br />";
	}
}

if ($teamDataClass->selectTeamPicture($rarryId, $teamId, $view) == true) {
	header("content-type:image/jpeg");
	print ($teamDataClass->getSelectTeamPicture());
} else {
print "no";
}

?>
