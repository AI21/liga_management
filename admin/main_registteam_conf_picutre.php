<?php
////////////////////////////////////////////////////
/*
 * チームマスタデータ追加・編集・削除
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array();
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array();

(string)$pageTitle = 'チーム写真';
(string)$rarryDetail = array();
(string)$teamDatas = array();
(string)$teamMemberDatas = array();
(string)$mode = '';
(string)$paymentChangeValue = '';

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
//print $key." = ".$val."<br />";
    }
}

//if ($files) {
//	$path = $files['imagefile']['tmp_name'];//uploaded file
//	
//	if(is_uploaded_file($path)) {
//		$data = addslashes(file_get_contents($path));//一時ファイルの読み込み
//		
//		header("content-type:image/jpeg");
//		print $data;
//	}
//}
$view = addslashes(file_get_contents($path));//一時ファイルの読み込み
//$view = ($_SESSION['vinaryData']);
header("content-type:image/jpeg");
print $view;
?>
