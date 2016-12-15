<?php
////////////////////////////////////////////////////
/*
 * 大会マスタデータ追加・編集・削除
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array();
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array();

(string)$pageTitle = '大会編集';

require_once './common.inc';

// 大会情報の取得
if ($rarryDataObj->rarryAllDatas() == true) {
    $rarryAllDatas = $rarryDataObj->getRarryAllDatas();
}
//print nl2br(print_r($rarryAllDatas,true));

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
        <a href="master_rarry_change.php?mode=new" class="button">新規追加</a>
        <h1><?php echo $pageTitle; ?></h1>
        <?php echo $pageTitle; ?>
      </div>
      <div class="select-bar">&nbsp;</div>
      <div class="table">
        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <form name="fmchangedel" method="post" action="#">
        <table class="listing" cellpadding="0" cellspacing="0" summary="参加チームデータ">
          <thead>
          <tr>
            <th style="width:20px;">ID</th>
            <th style="width:80px;">大会名</th>
            <th style="width:150px;">大会副題</th>
            <th style="width:50px;">シーズン</th>
            <th style="width:20px;">親ID</th>
            <th style="width:80px;">大会タイプ</th>
            <th style="width:60px;">進捗</th>
            <th style="width:60px;">終了</th>
            <th style="width:90px;">登録日</th>
            <th style="width:90px;">更新日</th>
          </tr>
          </thead>
          <tbody>
          <?php for ($i = 0; $i<count($rarryAllDatas); $i++) : ?>
          <?php
          switch ($rarryAllDatas[$i]['type']) {
              case  1 : $rarryType = 'リーグ戦'; break;
              case  2 : $rarryType = 'トーナメント戦'; break;
              default : $rarryType = 'その他';
          }
          switch ($rarryAllDatas[$i]['progress']) {
              case  1 : $progress = '開始直後'; break;
              case  2 : $progress = '中間Ａ'; break;
              case  3 : $progress = '中間Ｂ'; break;
              case  4 : $progress = '中間Ｃ'; break;
              case  5 : $progress = '中間Ｄ'; break;
              case  6 : $progress = '中間Ｅ'; break;
              case  7 : $progress = '中間Ｆ'; break;
              case  8 : $progress = '最終'; break;
//              case  9 : $progress = '大会終了'; break;
              default : $progress = '開始前';
          }
          if ($rarryAllDatas[$i]['parent_id'] > 0) {
              $rarryName = '&nbsp;';
              $parentView = $rarryAllDatas[$i]['parent_id'];
          } else {
              $rarryName = $rarryAllDatas[$i]['rarry_name'];
              $parentView = '-';
          }
          ?>
          <tr>
            <td><?php echo $rarryAllDatas[$i]['id']; ?></td>
            <td><?php echo $rarryName; ?></td>
            <td><a href="./master_rarry_change.php?rid=<?php echo $rarryAllDatas[$i]['id']; ?>&amp;mode=up"><?php echo $rarryAllDatas[$i]['rarry_sub_name']; ?></a></td>
            <td><?php echo $rarryAllDatas[$i]['season']; ?></td>
            <td><?php echo $parentView; ?></td>
            <td><?php echo $rarryType; ?></td>
            <td><?php echo $progress; ?></td>
            <td><?php echo ($rarryAllDatas[$i]['finish_flg']) ? '大会終了' : '&nbsp;' ; ?></td>
            <td><?php echo substr($rarryAllDatas[$i]['created'], 0, 10); ?></td>
            <td><?php echo substr($rarryAllDatas[$i]['modified'], 0, 10); ?></td>
          </tr>
          <?php endfor; ?>
          </tbody>
        </table>
        </form>
      </div>
    </div><?php echo "\n"/* END center-column */ ?>
  </div><?php echo "\n"/* END middle */ ?>
</div><?php echo "\n"/* END main */ ?>
<div id="footer"></div>
<div>&nbsp;</div>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>