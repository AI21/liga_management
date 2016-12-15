<?php

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('all', 'style');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array();

require_once './common.inc';

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "GET") {
    while(list ($key, $val) = each($_GET)) {
        $$key = $val;
        if ($key == "rarryId") {
            $_SESSION["rarryId"] = $val;
        }
    }
}

(string)$rarryDetail = array();

// 大会情報取得
if ($rarryDataObj->rarryReagueHistory() == True) {
    $rarryDataArray = $rarryDataObj->getRarryReagueHistory();
}

if (!isset($_SESSION["rarryId"])) {
    $_SESSION["rarryId"] = $rarryDataArray[0]["rarryId"];
}
// 大会情報の取得
if ($rarryDataObj->rarryDetails($_SESSION["rarryId"]) == true) {
    $rarryDetail = $rarryDataObj->getRarryDetail();
}

// 大会ブロックデータ
if ($mBlockDataObj->rarryRegistBlockData($_SESSION["rarryId"]) == True) {
		// 登録しているスケジュール
	$masterBlockData = $mBlockDataObj->getRarryRegistBlockData();

	for ($i=0; $i<count($masterBlockData); $i++) {

		(string)$blockSelected = "";

		// 戻り時のセレクト値
		if ($masterBlockData[$i]["BLOCK_ID"] == $rc) {
			$blockSelected = ' selected="selected"';
			// ブロック最大登録チーム数
			$useClassTeamSetNum = $masterBlockData[$i]['REGIST_TEAM_NUM'];
		}
		$blockId		= $masterBlockData[$i]["BLOCK_ID"];
		$blockName	= $masterBlockData[$i]["BLOCK_NAME"];

		// ブロック(クラス)フォーム生成
		//$fmBlockOption .= '				<option value="'.$scriptName.'?rc='.$blockId.'"'.$blockSelected.'>'.$blockName.'</option>'."\n";
	}
}

// ブロック別登録チーム情報
if ($teamDataObj->LeagueTeam($_SESSION["rarryId"], $rc) == true) {
	$registTeamDatas = $teamDataObj->getLeagueTeam();
}
$registTeamNum = count($registTeamDatas);

///////////////////
// データ確認用
//print nl2br(print_r($masterBlockData,true));
//print nl2br(print_r($registTeamDatas,true));
//print $registTeamNum." = 大会登録ブロック数<br />";
///////////////////

?>

<?php
// HTMLヘッダ読み込み
include_once "block/header.php";
?>
<body>
<div id="main">
<!-- メインコンテンツ -->
<?php include_once "block/menu.php"; ?>
  <div id="middle">
<!-- ページ内サブコンテンツ -->
<?php include_once "block/contents.php"; ?>
    <div id="center-column">
      <div class="top-bar">
        <a href="#" class="button">ADD NEW </a>
        <h1>Contents</h1>
        <div class="breadcrumbs"><a href="#">Homepage</a> / <a href="#">Contents</a></div>
      </div><br />
      <div class="select-bar">&nbsp;</div>
      <div class="table">
        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <table class="listing" cellpadding="0" cellspacing="0" style="width:100%;">
          <tr>
            <th class="first" width="120">Header Here</th>
            <th class="last">Head</th>
          </tr>
          <tr class="bg">
            <td class="first style2">大会名</td>
            <td class="last" style="text-indent:1em;text-align: left;"><?php echo $rarryDetail['rarry_name']; ?> <?php echo $rarryDetail['rarry_sub_name']; ?></td>
          </tr>
          <tr class="bg">
            <td class="first style2">チーム数</td>
            <td class="last" style="text-indent:1em;text-align: left;"><?php echo $rarryDataArray; ?></td>
          </tr>
          <tr class="bg">
            <td class="first style2">大会URL</td>
            <td class="last" style="text-indent:1em;text-align: left;"><a href="http://www.liga-tokai.com/schejule/index/<?php echo $_SESSION["rarryId"]; ?>" target="_blank">http://www.liga-tokai.com/schejule/index/<?php echo $_SESSION["rarryId"]; ?></a></td>
          </tr>
        </table>
      </div>
    </div><?php /* END center-column */ ?>
<!--
    <div id="right-column">
      <strong class="h">INFO</strong>
      <div class="box">Detect and eliminate viruses and Trojan horses, even new and unknown ones. Detect and eliminate viruses and Trojan horses, even new and </div>
    </div>
-->
  </div><?php /* END middle */ ?>
  <div id="footer"></div>
</div><?php echo "\n"/* END main */ ?>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>