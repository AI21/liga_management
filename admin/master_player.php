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

(string)$pageTitle = '選手情報編集';

require_once './common.inc';

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
      </div>
      <div class="select-bar">&nbsp;</div>
      <div>
      内容
      </div>
    </div><?php echo "\n"/* END center-column */ ?>
  </div><?php echo "\n"/* END middle */ ?>
  <div id="footer"></div>
</div><?php echo "\n"/* END main */ ?>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>