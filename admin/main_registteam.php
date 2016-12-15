<?php
////////////////////////////////////////////////////
/*
 * 大会参加チーム一覧
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('style');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array('prototype', 'tablekit/fastinit', 'tablekit/tablekit');

// 初期値
(string)$pageTitle = "参加チーム一覧";
(string)$scriptName = "main_registteam.php";
(string)$changeScriptName = "main_registteam_detail.php";
(int)$rc = null;
(int)$useClassTeamSetNum = 0;
(string)$registTeamDatas = array();
(string)$fmBlockOption = '';

require_once './common.inc';

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "GET") {
	while(list ($key, $val) = each($_GET)) {
		$$key = $val;
//print $key." = ".$val."<br />";
	}
} else if ($strRequestMethod == "POST") {
	while(list ($key, $val) = each($_POST)) {
		$$key = $val;
//print $key." = ".$val."<br />";
	}
}

// 大会情報の取得
if ($rarryDataObj->rarryDetails($_SESSION["rarryId"]) == true) {
    $rarryDetail = $rarryDataObj->getRarryDetail();
}
//print nl2br(print_r($rarryDetail,true));

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
		$fmBlockOption .= '				<option value="'.$scriptName.'?rc='.$blockId.'"'.$blockSelected.'>'.$blockName.'</option>'."\n";
	}
}

// ブロック別登録チーム情報
if ($teamDataObj->LeagueTeam($_SESSION["rarryId"], $rc) == true) {
	$registTeamDatas = $teamDataObj->getLeagueTeam();
}
$registTeamNum = count($registTeamDatas);

if ($rc == null) {
	$rc = $masterBlockData[0]["BLOCK_ID"];
}

// データ確認用
//print $rc."<br />";
//print nl2br(print_r($masterBlockData,true));
//print nl2br(print_r($registTeamDatas,true));
//print $useClassTeamSetNum.' = ブロック最大登録チーム数<br />';
//print count($registTeamDatas[$rc]).' = ブロック登録チーム数<br />';
//print nl2br(print_r($array,true));

?>
<?php
// HTMLヘッダ読み込み
include_once "block/header.php"; ?>
<body>
<div id="main">
<!-- メインコンテンツ -->
<?php include_once "block/menu.php"; ?>
	<div id="middle">
<!-- ページ内サブコンテンツ -->
<?php include_once "block/contents.php"; ?>
		<div id="center-column">
			<div class="top-bar">
				<h1><?php echo $pageTitle; ?></h1>
				<?php echo $pageTitle; ?>
			</div>
			<div class="select-bar">&nbsp;</div>
			<div>
				<!-- クラス別大会登録チーム一覧 -->
				<form name="fmviewchange" method="post" action="#">
				<div class="select-up">
					<strong>選択: </strong>
					<select name="selectBlock" onchange="location.href=fmviewchange.selectBlock.options[document.fmviewchange.selectBlock.selectedIndex].value">
						<?php if ($registTeamNum > 0) : ?>
						<option value="<?php echo $scriptName; ?>">全チーム</option>
						<?php endif; ?>
						<?php echo $fmBlockOption; ?>
					</select>
				</div>
				</form>
				<div class="table">
					<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
					<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
					<form name="fmchangedel" method="post" action="main_registteam_change.php">
					<table class="sortable resizable" cellpadding="0" cellspacing="0" style="width:100%;" summary="参加チームデータ">
						<thead>
						<tr>
							<th style="width:35px;" id="block">クラス</th>
							<th style="width:100px;" id="teams">登録チーム名</th>
							<th style="width:5px;">人数</th>
							<th style="width:18px;">H/A</th>
							<th style="width:90px;" id="katudou">活動場所</th>
							<th style="width:60px;" id="daihyo">代表者</th>
							<th style="width:70px;" id="tel">TEL</th>
							<th style="width:150px;" id="mail">E-Mail</th>
						</tr>
						</thead>
						<tbody>
						<?php if ($registTeamNum > 0) : ?>
						<?php foreach ($registTeamDatas as $blockDatas) :?>
						<?php for ($i=0; $i<count($blockDatas); $i++) :?>
						<?php
						// チーム写真が投稿されているかチェックする
						$picture_contribute = false;
						if ($blockDatas[$i]["applies_pic_h"] OR $blockDatas[$i]["applies_pic_a"]) {
							$serch_files = glob("../regist/tmp_contribute/".$_SESSION["rarryId"]."/".$blockDatas[$i]["photo"]."_*.jpg");
							if (is_array($serch_files)) {
								foreach ($serch_files as $filename) {
									$picture_contribute = true;
								}
							}
						}
						// 登録メンバー数チェック
						(string)$members_contribute_style = ' style="color:#ff0000"';
						(int)$memberPeopleCount = 0;
						if ($memberDataObj->rarrySeasonMemberList($_SESSION["rarryId"], $rarryDetail['progress'], $blockDatas[$i]['t_id'])) {
							// 5人以上の登録があればtrue
							$memberPeopleCount = $memberDataObj->getPeopleCount();
							if ($memberPeopleCount >= 5) {
								$members_contribute_style = '';
							} else if ($memberPeopleCount > 0) {
								$members_contribute_style = ' style="color:#2C7C2D"';
							}
							$registPayment = 0;
							if ($rarryDetail['type'] == 1) {
								// 登録費の未払い選手があれば背景色変更
								$memberDatas = $memberDataObj->getMemberDataList();
								foreach ($memberDatas as $datas) {
									if ($datas['registPayment'] == 0) {
										$registPayment++;
									}
								}
								if ($registPayment > 0) {
									$members_contribute_style = ' style="font-weight:bold;color:#CC3300"';
								}
							}
						}

						// チーム名のエスケープ処理
						$teamName = preg_replace("'&(amp|#38);'i", "&", $blockDatas[$i]['t_name']);
						// ハイフン無しの携帯番号にハイフンを入れる
						if (strlen($blockDatas[$i]['represent_tel']) == 11) {
							$represent_tel = substr($blockDatas[$i]['represent_tel'], 0, 3).'-'.substr($blockDatas[$i]['represent_tel'], 3, 4).'-'.substr($blockDatas[$i]['represent_tel'], 7, 4);
						} else {
							$represent_tel = $blockDatas[$i]['represent_tel'];
						}
						?>
						<tr<?php /*if (($i % 2) == 0) { echo ' class="bg"'; }*/ ?>>
							<td><?php echo $blockDatas[$i]['block_name']; ?></td>
							<td<?php echo ($picture_contribute) ? ' style="background-color:#1F7FF0;"' : ''; ?>>
								<a href="<?php echo $changeScriptName; ?>?tid=<?php echo $blockDatas[$i]['t_id']; ?>"<?php echo $members_contribute_style; ?>><?php echo $teamName; ?></a><?php echo ($registPayment > 0) ? ' ('.$registPayment.')' : ''; ?>
							</td>
							<td><?php echo $memberPeopleCount; ?></td>
							<td>
								<span style="text-align:center;background-color:<?php echo $blockDatas[$i]['home_color']; ?>;border:1px solid #000;">　</span> /
								<span style="text-align:center;background-color:<?php echo $blockDatas[$i]['away_color']; ?>;border:1px solid <?php echo $blockDatas[$i]['home_color']; ?>;">　</span>
							</td>
							<td><?php echo $blockDatas[$i]['activity_place']; ?></td>
							<td><?php echo $blockDatas[$i]['represent']; ?></td>
							<td><?php echo $represent_tel; ?></td>
							<td><?php echo ($blockDatas[$i]['represent_mobile_address']) ? $blockDatas[$i]['represent_mobile_address'].'@'.$blockDatas[$i]['represent_mobile_domain'] : $blockDatas[$i]['represent_address'] ; ?></td>
						</tr>
						<?php endfor; ?>
						<?php endforeach; ?>
						<?php else : ?>
						<tr>
							<td colspan="8">登録チームはありません</td>
						</tr>
						<?php endif; ?>
						</tbody>
						<?php if (isset($_GET['rc']) AND $rc > 0) : ?>
						<tfoot>
						<tr>
							<td colspan="8" style="text-align:center;">
								<input type="submit" value="チーム追加・削除" />
								<input type="hidden" name="rc" value="<?php echo $rc; ?>" />
<!--								<input type="hidden" name="mode" value="addTeam" />-->
<!--								<input type="hidden" name="sts" value="" />-->
							</td>
						</tr>
						</tfoot>
						<?php endif; ?>
					</table>
					</form>
				</div>
			</div>
		</div><?php echo "\n"/* END center-column */ ?>
	</div><?php echo "\n"/* END middle */ ?>
	<div id="footer"></div>
</div><?php echo "\n"/* END main */ ?>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>