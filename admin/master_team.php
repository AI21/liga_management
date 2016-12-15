<?php
////////////////////////////////////////////////////
/*
 * チームマスタデータ一覧
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('style');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array('prototype', 'tablekit/fastinit', 'tablekit/tablekit');

// 初期値
(string)$pageTitle = "登録チーム一覧";
(string)$scriptName = "master_team.php";
(string)$changeScriptName = "master_team_change.php";
(int)$rc = null;
(string)$registTeamDatas = array();

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

// 大会ブロックデータ
//if ($mBlockDataObj->rarryRegistBlockData($_SESSION["rarryId"]) == True) {
//    // 登録しているスケジュール
//    $masterBlockData = $mBlockDataObj->getRarryRegistBlockData();
//
//    for ($i=0; $i<count($masterBlockData); $i++) {
//
//    	(string)$blockSelected = "";
//
//        // 戻り時のセレクト値
//        if ($masterBlockData[$i]["BLOCK_ID"] == $rc) {
//            $blockSelected = ' selected="selected"';
//        }
//        $blockId    = $masterBlockData[$i]["BLOCK_ID"];
//        $blockName  = $masterBlockData[$i]["BLOCK_NAME"];
//
//        // ブロック(クラス)フォーム生成
//        $fmBlockOption .= '        <option value="'.$scriptName.'?rc='.$blockId.'"'.$blockSelected.'>'.$blockName.'</option>'."\n";
//    }
//}

// 登録チーム情報
if ($teamDataObj->selectAllTeamData() == true) {
    $registAllTeamDatas = $teamDataObj->getSelectAllTeamDatas();
}
$registAllTeamNum = count($registAllTeamDatas);
//print print nl2br(print_r($registAllTeamDatas,true));
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
        <a href="master_team_change.php?mode=new" class="button">新規追加</a>
        <h1><?php echo $pageTitle; ?></h1>
        <?php echo $pageTitle; ?>
      </div>
      <div class="select-bar">&nbsp;</div>
      <div>
<?php
/*
        <!-- クラス別大会登録チーム一覧 -->
        <form name="fmviewchange" method="post" action="#">
        <div class="select-up">
          <strong>選択: </strong>
          <select name="selectBlock" onchange="location.href=fmviewchange.selectBlock.options[document.fmviewchange.selectBlock.selectedIndex].value">
            <option value="<?php echo $scriptName; ?>">全チーム</option>
            <?php echo $fmBlockOption; ?>
          </select>
        </div>
        </form>
*/
?>
        <div class="table">
          <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
          <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
          <form name="fmchangedel" method="post" action="#">
          <table class="sortable resizable" cellpadding="0" cellspacing="0" summary="参加チームデータ">
            <thead>
            <tr>
              <th style="white-space:nowrap;" id="tid">ID</th>
              <th style="white-space:nowrap;" id="teams">登録チーム名</th>
              <th style="white-space:nowrap;" id="kanas">読みカナ</th>
              <th style="width:5px;">H</th>
              <th style="width:5px;">A</th>
              <th style="width:60px;" id="daihyo">代表者</th>
              <th style="width:70px;" id="tel">TEL</th>
              <th id="mail">E-Mail</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($registAllTeamNum > 0) : ?>
            <?php foreach ($registAllTeamDatas as $allTeamDatas) :?>
            <?php
            // チーム名のエスケープ処理
            $teamName = preg_replace("'&(amp|#38);'i", "&", $allTeamDatas['t_name']);
            // ハイフン無しの携帯番号にハイフンを入れる
            if (strlen($allTeamDatas['represent_tel']) == 11) {
            	$represent_tel = substr($allTeamDatas['represent_tel'], 0, 3).'-'.substr($allTeamDatas['represent_tel'], 3, 4).'-'.substr($allTeamDatas['represent_tel'], 7, 4);
            } else {
            	$represent_tel = $allTeamDatas['represent_tel'];
            }
            ?>
            <tr<?php /*if (($i % 2) == 0) { echo ' class="bg"'; }*/ ?>>
              <td style="text-align:center;"><?php echo $allTeamDatas['t_id']; ?></td>
              <td><a href="<?php echo $changeScriptName; ?>?tid=<?php echo $allTeamDatas['t_id']; ?>&amp;mode=up"><?php echo $teamName; ?></a></td>
              <td><?php echo $allTeamDatas['t_kana']; ?></td>
              <td><span style="text-align:center;background-color:<?php echo $allTeamDatas['home_color']; ?>;">　</span></td>
              <td><span style="text-align:center;background-color:<?php echo $allTeamDatas['away_color']; ?>;">　</span></td>
              <td><?php echo $allTeamDatas['represent']; ?></td>
              <td><?php echo $represent_tel; ?></td>
              <td><?php echo $allTeamDatas['represent_address']; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
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