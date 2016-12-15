<?php
////////////////////////////////////////////////////
/*
 * 大会ブロック別参加チーム追加・削除
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('style');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array('prototype', 'tablekit/fastinit', 'tablekit/tablekit');

// 初期値
(string)$pageTitle = "参加チーム一覧";
(string)$scriptName = "main_registteam.php";
(string)$changeScriptName = "main_registteam_change.php";
(int)$rc = null;
(int)$useClassTeamSetNum = 0;
(int)$registTeamNum = 0;
(int)$allTeamNum = 0;
(int)$err = 0;
(string)$registTeamDatas = array();
//(string)$mode = 'add';

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

// 大会情報の取得
if ($rarryDataObj->rarryDetails($_SESSION['rarryId']) == true) {
	$rarryDetails = $rarryDataObj->getRarryDetail();
} else {
	header("Location: ./");
}
// 全チーム情報
if ($teamDataObj->selectAllTeamData() == true) {
	$allTeamDatas = $teamDataObj->getSelectAllTeamDatas();
}
// 選択した大会ブロックデータ
if ($mBlockDataObj->selectRrarryRegistBlockData($_SESSION["rarryId"], $rc) == True) {
	// ブロックデータ
	$selectBlockData = $mBlockDataObj->getSelectBlockData();
}
// ブロック別登録チーム情報
if ($teamDataObj->LeagueTeam($_SESSION["rarryId"], $rc) == true) {
	$registTeamDatas = $teamDataObj->getLeagueTeam();
}
// 大会全体の登録チーム情報
if ($teamDataObj->LeagueTeam($_SESSION["rarryId"]) == true) {
	$registRarryTeamDatas = $teamDataObj->getLeagueTeam();
}

$allTeamNum = count($allTeamDatas);
$registTeamNum = count($registRarryTeamDatas[$rc]);
//print $allTeamNum.' = allTeamNum<br />';
//print $registTeamNum.' = registTeamNum<br />';

if ($mode != '') {
	// 選択されたチーム情報の取得
	if (isset($teamId) AND ($teamDataObj->selectTeamData($teamId) == true)) {
		$selectTeamDatas = $teamDataObj->getSelectTeamDatas();
	} else {
		$err++;
		$errMessage['add'] = $errorMessageObj->getErrorMessage("NOT_TEAMDATA");
	}
	switch ($mode) {
		// チーム追加モード
		case 'add' :
			$modeView = '追加登録';
			// 大会に選択したチームが登録されているかチェックする
			if ($teamDataChangeObj->checkRarryRegistTeam($_SESSION["rarryId"], $teamId) == false) {
				$err++;
				$errMessage['add'] = 'すでに大会にエントリーされています。';
				break;
			}
			// 登録しているチーム数がブロック登録最大チーム数以上の時は登録させない
			if ($registTeamNum >= $selectBlockData['REGIST_TEAM_NUM']) {
				$err++;
				$errMessage['add'] = 'これ以上登録できません。';
				$sts = '';
				break;
			}
			if (isset($sts) AND $err == 0) {
				// 入力確認
				if ($sts == 'conf') {
//print nl2br(print_r($selectTeamDatas,true));
				// チーム追加登録
				} elseif ($sts == 'comp') {
					$insertDatas['r_id'] = $_SESSION["rarryId"];
					$insertDatas['class'] = $rc;
					$insertDatas['t_id'] = $teamId;
					$insertDatas['team_name'] = $team_name;
					$insertDatas['regist_flag'] = $regist_flag;
					$insertDatas['view_flag'] = $view_flag;
					if (isset($insertDatas)) {
						if ($teamDataChangeObj->rarryRegistTeamInsert($insertDatas) == true) {
							$compView = 'チーム追加登録が完了しました。';
//							$_SESSION['registTeam'] = 'insert';
						}
					}
				}
			}
			break;
		// チーム追加モード
		case 'change' :
			$modeView = '変更';
			if (isset($sts)) {
				// 入力確認
				if ($sts == 'conf') {
					$a;
				}
			}
			break;
		// チーム追加モード
		case 'dell' :
			// 大会が開始していたら登録チームの削除は不可
			if ($rarryDetails['progress'] > 0) {
				$errMessage['add'] = '大会が開始しているので登録チーム削除は出来ません。';
				$sts = '';
				break;
			}
			$modeView = '削除';
			if (isset($sts)) {
				// 入力確認
				if ($sts == 'conf') {
				}
				if ($sts == 'comp') {
					// 登録チーム削除
					if ($teamDataChangeObj->removeRegistTeam($_SESSION["rarryId"], $rc, $teamId) == true) {
						$compView = '登録チーム削除が完了しました。';
					} else {
						$errMessage['add'] = '登録チーム削除が出来ませんでした。';
						$sts = '';
						break;
					}
				}
			}
			break;
	}
}

if ($registTeamNum > 0) {
	foreach ($registRarryTeamDatas as $blockId => $datas) {
		// ブロック毎のチームデータ
		foreach ($datas as $val) {
			// 大会登録している全チームID取得
			$registTeamIds[] = $val['t_id'];
		}
	}
}

if ($allTeamNum > 0/* AND $registTeamNum > 0*/) {
	foreach ($allTeamDatas as $allTeamDatas) {
//		if (!in_array ($allTeamDatas['t_id'], $registTeamIds)) {
			$nums++;
			$nonRegistBlockTeams[$nums]['tid'] = $allTeamDatas['t_id'];
			$nonRegistBlockTeams[$nums]['tname'] = $allTeamDatas['t_name'];

			// 登録可能チームフォーム生成
			$fmNonRegistBlockOption .= '		<option value="'.$allTeamDatas['t_id'].'"'.$blockSelected.'>'.$allTeamDatas['t_name'].'</option>'."\n";
//		}
	}
}

// データ確認用
//print nl2br(print_r($rarryDetails,true));
//print nl2br(print_r($selectBlockData,true));
//print nl2br(print_r($registRarryTeamDatas,true));
//print nl2br(print_r($allTeamDatas,true));
//print nl2br(print_r($registTeamIds,true));
//print nl2br(print_r($nonRegistBlockTeams,true));
//print $selectBlockData['REGIST_TEAM_NUM'].' = ブロック最大登録チーム数<br />';
//print count($registTeamDatas[$rc]).' = ブロック登録チーム数<br />';
//print nl2br(print_r($array,true));
//print nl2br(print_r($registTeamDatas,true));

?>
<?php
// HTMLヘッダ読み込み
include_once "block/header.php"; ?>

<script language="javascript" type="text/javascript">
//<![CDATA[
<!--

	// フォームデータを送信
	function sendpages(mode, sts, tid) {
	document.fm.action = "<?php echo $changeScriptName; ?>";
	document.fm.mode.value = mode;
	document.fm.sts.value = sts;
	document.fm.rc.value = "<?php echo $rc; ?>";
	document.fm.teamId.value = tid;
	document.fm.submit();
	}

//-->
//]]>
</script>

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
			<h2>ブロック&nbsp;：&nbsp;<span style="color:blue;"><?php echo $selectBlockData['BLOCK_NAME'] ; ?></span></h2>
			<?php if ( $errMessage['add'] != '') : ?>
			<p style="color:red;font-size:16px;font-weight:bold;"><?php echo $errMessage['add']; ?></p>
			<?php endif ; ?>
			</form>
			<?php if ($sts == '') : ?>
			<div class="table">
				<img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
				<img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />

				<table class="sortable resizable" cellpadding="0" cellspacing="0" style="width:100%;" summary="参加チームデータ">
				<thead>
				<tr>
					<th style="width:25px;">&nbsp;</th>
					<th style="width:200px;">登録チーム名</th>
					<th>削除</th>
				</tr>
				</thead>
				<tbody>
				<?php for ($i=0; $i<$selectBlockData['REGIST_TEAM_NUM']; $i++) :?>
				<tr>
					<td>No.<?php echo ($i + 1); ?></td>
					<?php if (isset($registTeamDatas[$rc][$i])) : ?>
					<td><?php echo preg_replace("'&(amp|#38);'i", "&", $registTeamDatas[$rc][$i]['t_name']); ?></td>
					<?php if ($rarryDetails['progress'] == 0) : ?>
					<td><input type="button" value="&nbsp;削除&nbsp;" onclick="sendpages('dell','conf', '<?php echo $registTeamDatas[$rc][$i]['t_id']; ?>')" /></td>
					<?php else : ?>
					<td>大会が開始しているので登録チーム削除は出来ません</td>
					<?php endif; ?>
					<?php else : ?>
					<td>
						<form name="fmchangedel" method="post" action="main_registteam_change.php">
						<select name="teamId">
							<?php echo $fmNonRegistBlockOption; ?>
						</select>
					</td>
					<td>
						<input type="submit" value="&nbsp;登録&nbsp;" />
						<input type="hidden" name="rc" value="<?php echo $rc; ?>" />
						<input type="hidden" name="mode" value="add" />
						<input type="hidden" name="sts" value="conf" />
						</form>
					</td>
					<?php endif; ?>
				</tr>
				<?php endfor; ?>
				</tbody>
				</table>
			</div>
			<?php elseif ($sts == 'conf') : ?>
			<div>
				<h3 style="color:#0E3192;">以下のチームを<?php echo $modeView;?>しますがよろしいですか？</h3>
				<form name="fmchangedel" method="post" action="main_registteam_change.php">
				<table width="400">
				<tr>
					<th style="width:120px;">チーム名</th>
					<td style="font-size:16px;font-weight:bold;color:blue;"><?php echo $selectTeamDatas['teamName']; ?></td>
				</tr>
				<tr>
					<th>チーム代表者</th>
					<td style="font-size:16px;font-weight:bold;color:blue;"><?php echo $selectTeamDatas['teamRep']; ?></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input type="submit" value="&nbsp;<?php echo $modeView;?>する&nbsp;" />
					<input type="button" value="&nbsp;参加チーム編集へ戻る&nbsp;" onclick="sendpages('', '', '')" />
					<input type="hidden" name="teamId" value="<?php echo $teamId; ?>" />
					<input type="hidden" name="rc" value="<?php echo $rc; ?>" />
					<input type="hidden" name="team_name" value="<?php echo $selectTeamDatas['teamName']; ?>" />
					<input type="hidden" name="regist_flag" value="0" />
					<input type="hidden" name="view_flag" value="1" />
					<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
					<input type="hidden" name="sts" value="comp" />
					</td>
				</tr>
				</table>
				</form>
			</div>
			<?php elseif ($sts == 'comp') : ?>
			<div>
				<h3 style="color:#0E3192;"><?php echo $compView; ?></h3>
				<form name="fmchangedel" method="post" action="main_registteam.php?rc=<?php echo $rc; ?>">
				<table width="400">
				<tr>
					<th style="width:120px;">チーム名</th>
					<td style="font-size:16px;font-weight:bold;color:blue;"><?php echo $selectTeamDatas['teamName']; ?></td>
				</tr>
				<tr>
					<th>チーム代表者</th>
					<td style="font-size:16px;font-weight:bold;color:blue;"><?php echo $selectTeamDatas['teamRep']; ?></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
					<input type="submit" value="&nbsp;参加チーム編集へ戻る&nbsp;" />
					<input type="hidden" name="rc" value="<?php echo $rc; ?>" />
					</td>
				</tr>
				</table>
				</form>
			</div>
			<?php endif; ?>
		</div>
	</div><?php echo "\n"/* END center-column */ ?>
	</div><?php echo "\n"/* END middle */ ?>
	<div id="footer"></div>
</div><?php echo "\n"/* END main */ ?>

<form name="fm" method="post">
	<input type="hidden" name="mode" value="" />
	<input type="hidden" name="sts" value="" />
	<input type="hidden" name="rc" value="" />
	<input type="hidden" name="teamId" value="" />
</form>

<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>