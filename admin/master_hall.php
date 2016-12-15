<?php
////////////////////////////////////////////////////
/*
 * 会場データ追加・編集・削除
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('all', 'style');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array('prototype', 'tablekit/fastinit', 'tablekit/tablekit');

(string)$pageTitle = '会場設定・編集';
(string)$titleName = '会場一覧';
(string)$scriptName = "master_hall.php";
(string)$changeScriptName = "master_hall_change.php";

require_once './common.inc';

if ($mHallDataObj->allHallData() == true) {
    $hallDatas = $mHallDataObj->getAllHallDatas();
}
//print nl2br(print_r($hallDatas,true));
$hallDatasNum = count($hallDatas);

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
        <a href="<?php echo $changeScriptName; ?>" class="button">会場登録</a>
        <h1><?php echo $pageTitle; ?></h1>
        <?php echo $pageTitle; ?>&nbsp;&#187;&nbsp;<?php echo $titleName; ?>
      </div>
      <div class="select-bar">&nbsp;</div>
      <div class="table">
        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <table class="sortable resizable">
          <thead>
          <tr>
            <th id="hall">会場名</th>
            <th id="cort">登録コート</th>
            <th id="add">住所<br />TEL</th>
            <th id="hp">HPアドレス</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($hallDatas as $key => $data) : ?>
          <tr>
            <td style="text-align:left;" nowrap="nowrap">
              <a href="<?php echo $changeScriptName; ?>?hid=<?php echo $data['HallId']; ?>"><?php echo $data['HallName']; ?></a>
            </td>
            <td style="text-align:left;" nowrap="nowrap">
              <?php
              if ($mHallDataObj->hallCortData($data['HallId']) == true) {
                  $hallCortDatas = $mHallDataObj->getHallCortDatas();
              }
//              print nl2br(print_r($hallCortDatas,true));
              $hallCortDataNum = count($hallCortDatas);
              ?>
              <?php for ($i = 0; $i < $hallCortDataNum; $i++) : ?>
              <?php echo $hallCortDatas[$i]['CortName']; ?>
              <?php if ($i != $hallCortDataNum) : ?>
              <br />
              <?php endif; ?>
              <?php endfor; ?>
            </td>
            <td style="text-align:left;" nowrap="nowrap">
              <?php echo $data['HallCity']; ?><?php echo $data['HallAddress1']; ?><?php echo $data['HallAddress2']; ?><br />
              <?php echo $data['HallTel1']; ?>-<?php echo $data['HallTel2']; ?>-<?php echo $data['HallTel3']; ?></td>
            <td style="text-align:left;">
              <a href="<?php echo $data['HallSite']; ?>" target="_blank"><?php echo $data['HallSite']; ?></a></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div><?php echo "\n"/* END center-column */ ?>
  </div><?php echo "\n"/* END middle */ ?>
  <div id="footer"></div>
</div><?php echo "\n"/* END main */ ?>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>