<?php
////////////////////////////////////////////////////
/*
 * チームマスタデータ追加・編集・削除
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('all', 'style', 'input_table');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array();

(string)$pageTitle = '大会情報設定・編集';
(string)$titleName = '修正・変更';
(string)$backScriptName = "master_rarry.php";
(string)$mode = '';
(string)$sts = '';
(string)$paramError = array();
(int)$rid = null;
(int)$fmError = 0;
(int)$rarryAllDataCount = 0;
(boolean)$dbInsUp = false;
$rarryDetails = array();
$dataChange = array('type' => true, 'season' => true, 'parent' => true, 'all' => true, );

require_once './common.inc';

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "GET") {
    while(list ($key, $val) = each($_GET)) {
        $$key = encode($val);
//print $key ." = ". $val."<BR>";
    }
} else if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
//print $key ." = ". $val."<BR>";
    }
}

// 確認・登録モード
if ($sts == 'comp' OR $sts == 'conf') {

	// 登録モード
    if ($sts == 'comp') {
        $sts = 'complet';
        $titleName = '登録完了画面';
//print nl2br(print_r($insertData, true));
        $insertData = $_POST;

        switch ($mode) {
            case 'up' :
                // 更新登録
                $dbInsUp = $rarryDataObj->rarryUpdate($insertData);
                break;
            default :
                // 新規登録
                $dbInsUp = $rarryDataObj->rarryInsert($insertData);
        }
    }
    // 確認モード
    if ($sts == 'conf') {

        // パラメータチェック
        if ($paramCheckClassObj->isNullCheck($errorMessageObj, $rarry_name, 4, 60) == false) {
            $paramError['rarry_name'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }
        if ($paramCheckClassObj->isNullCheck($errorMessageObj, $rarry_sub_name, 4, 60) == false) {
            $paramError['rarry_sub_name'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }

        $sts = 'comp';
        $titleName = '内容確認画面';
    }

    // フォームエラーなし
    if ($fmError == 0) {
        switch ($type) {
            case  1 : $rarryType = 'リーグ戦'; break;
            case  2 : $rarryType = 'トーナメント戦'; break;
            default : $rarryType = 'その他';
        }
        switch ($progress) {
            case  1 : $progressView = '開始直後'; break;
            case  2 : $progressView = '中間Ａ'; break;
            case  3 : $progressView = '中間Ｂ'; break;
            case  4 : $progressView = '中間Ｃ'; break;
            case  5 : $progressView = '中間Ｄ'; break;
            case  6 : $progressView = '中間Ｅ'; break;
            case  7 : $progressView = '中間Ｆ'; break;
            case  8 : $progressView = '最終'; break;
            case  9 : $progressView = '大会終了'; break;
            default : $progressView = '開始前';
        }
        if ($parent_id > 0) {
            if ($rarryDataObj->rarryDetails($parent_id) == true) {
                $parentRarryDetails = $rarryDataObj->getRarryDetail();
                $parentName = $parentRarryDetails['rarry_name'].'&nbsp;'.$parentRarryDetails['rarry_sub_name'];
            }
        } else {
            $parentName = 'なし';
        }
        if ($finish_flg > 0) {
            $finishView = '終了';
        } else {
            $finishView = '進行中';
        }
    }
}

// 更新モードの時は大会データの取得
if ($mode == 'up' AND $sts == '') {
    // 大会情報の取得
    if ($rarryDataObj->rarryDetails($rid) == true) {
        $rarryDetails = $rarryDataObj->getRarryDetail();
    } else {
        header("Location: ./");
    }
    //print nl2br(print_r($rarryDetails,true));

    // ブロックマスターデータ情報の取得
    if ($mBlockDataObj->masterBlockData() == true) {
        $mastertBlockDatas = $mBlockDataObj->getMastertBlockDatas();
    //    for ($i = 0; $i < count($mastertBlockDatas); $i++) {
    //        $mastertBlockIds[] = $mastertBlockDatas[$i]['id'];
    //    }
    }
    // 大会使用ブロック(クラス)情報の取得
    if ($mBlockDataObj->rarryRegistBlockData($_SESSION['rarryId']) == true) {
        $blockDatas = $mBlockDataObj->getRarryRegistBlockData();
        for ($i = 0; $i < count($blockDatas); $i++) {
            $useBlockId[] = $blockDatas[$i]['BLOCK_ID'];
        }
    }
    // ステータスパラメータ
    $sts = 'conf';
//print nl2br(print_r($mastertBlockIds,true));
//print nl2br(print_r($useBlockId,true));
}
if ($mode == 'new' AND $sts == '') {
    // ステータスパラメータ
    $sts = 'conf';
    $rarryDetails['season'] = date('Y');
}

// フォームやり直しorフォームエラー発生時
if ($sts == 'fmBack' OR $fmError > 0) {
    $sts = 'conf';
    $rarryDetails['rarry_name'] = $rarry_name;
    $rarryDetails['rarry_sub_name'] = $rarry_sub_name;
    $rarryDetails['season'] = $season;
    $rarryDetails['type'] = $type;
    $rarryDetails['parent_id'] = $parent_id;
    $rarryDetails['progress'] = $progress;
    $rarryDetails['finish_flg'] = $finish_flg;
}

// 大会情報の取得
if ($rarryDataObj->rarryAllDatas() == true) {
    $rarryAllDatas = $rarryDataObj->getRarryAllDatas();
    $rarryAllDataCount = count($rarryAllDatas);
}
//print nl2br(print_r($rarryAllDatas, true));

// 各種状況チェック
switch ($rarryDetails['progress']) {
    case  1 :
    case  2 :
    case  3 :
    case  4 :
    case  5 :
    case  6 :
    case  7 :
    case  8 : $dataChange['type'] = false;
              $dataChange['season'] = false;
              $dataChange['parent'] = false;
              break;
    case  9 : $dataChange['all'] = false; break;
    default :
}

?>
<?php

// HTMLヘッダ読み込み
include_once "block/header.php"; ?>
<body>

<script language="javascript" type="text/javascript">
//<![CDATA[

  // 大会更新登録
//  function rarryDataChangeConfirm() {
//    var myReturn = confirm("大会データを更新しますがよろしいですか？");
//    if ( myReturn == true ) {
//      document.fmRarry.submit();
//    }
//  }
//
  // フォームやり直し
  function returnForm() {
    document.fmRarry.sts.value = 'fmBack';
    document.fmRarry.submit();
  }

//]]>
</script>

<div id="main">
<!-- メインコンテンツ -->
<?php include_once "block/menu.php"; ?>
  <div id="middle">
<!-- ページ内サブコンテンツ -->
<?php include_once "block/contents.php"; ?>
    <div id="center-column">
      <div class="top-bar">
        <h1><?php echo $pageTitle; ?></h1>
        <a href="<?php echo $backScriptName; ?>"><?php echo $pageTitle; ?></a>&nbsp;&#187;&nbsp;<?php echo $titleName; ?>
      </div>
      <div class="select-bar">&nbsp;</div>
      <?php if ($sts == 'comp') : ?>
      <p style="text-align:center;font-size:140%;font-weight:bold;color:blue;">下記内容でよろしいですか？</p>
      <?php elseif ($sts == 'complet') : ?>
      <p style="text-align:center;font-size:140%;font-weight:bold;color:blue;">大会登録が完了しました</p>
      <?php endif ;?>
      <div>
        <form name="fmRarry" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table id="input_table">
          <?php if ($sts == 'conf') :?>
          <tbody>
          <tr>
            <th>大会名</th>
            <td style="text-align:left;">
              <?php if ($dataChange['all'] == false) : ?>
              <?php echo $rarryDetails['rarry_name']; ?>
              <input type="hidden" name="rarry_name" value="<?php echo $rarryDetails['rarry_name']; ?>" />
              <?php else : ?>
              <input type="text" name="rarry_name" size="50" value="<?php echo $rarryDetails['rarry_name']; ?>" />&nbsp;<?php echo $paramError['rarry_name']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>副題名</th>
            <td style="text-align:left;">
              <?php if ($dataChange['all'] == false) : ?>
              <?php echo $rarryDetails['rarry_sub_name']; ?>
              <input type="hidden" name="rarry_sub_name" value="<?php echo $rarryDetails['rarry_sub_name']; ?>" />
              <?php else : ?>
              <input type="text" name="rarry_sub_name" size="50" value="<?php echo $rarryDetails['rarry_sub_name']; ?>" />&nbsp;<?php echo $paramError['rarry_sub_name']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>シーズン</th>
            <td style="text-align:left;">
              <?php if ($dataChange['all'] == false OR $dataChange['season'] == false) : ?>
              <?php echo $rarryDetails['season']; ?>
              <input type="hidden"  name="season" value="<?php echo $rarryDetails['season']; ?>" />
              <?php else : ?>
              <select name="season">
                <option value="2000"<?php if ($rarryDetails['season'] == 2000) { echo 'selected="selected"'; } ?>>2000</option>
                <option value="2001"<?php if ($rarryDetails['season'] == 2001) { echo 'selected="selected"'; } ?>>2001</option>
                <option value="2002"<?php if ($rarryDetails['season'] == 2002) { echo 'selected="selected"'; } ?>>2002</option>
                <option value="2003"<?php if ($rarryDetails['season'] == 2003) { echo 'selected="selected"'; } ?>>2003</option>
                <option value="2004"<?php if ($rarryDetails['season'] == 2004) { echo 'selected="selected"'; } ?>>2004</option>
                <option value="2005"<?php if ($rarryDetails['season'] == 2005) { echo 'selected="selected"'; } ?>>2005</option>
                <option value="2006"<?php if ($rarryDetails['season'] == 2006) { echo 'selected="selected"'; } ?>>2006</option>
                <option value="2007"<?php if ($rarryDetails['season'] == 2007) { echo 'selected="selected"'; } ?>>2007</option>
                <option value="2008"<?php if ($rarryDetails['season'] == 2008) { echo 'selected="selected"'; } ?>>2008</option>
                <option value="2009"<?php if ($rarryDetails['season'] == 2009) { echo 'selected="selected"'; } ?>>2009</option>
                <option value="2010"<?php if ($rarryDetails['season'] == 2010) { echo 'selected="selected"'; } ?>>2010</option>
                <option value="2011"<?php if ($rarryDetails['season'] == 2011) { echo 'selected="selected"'; } ?>>2011</option>
                <option value="2012"<?php if ($rarryDetails['season'] == 2012) { echo 'selected="selected"'; } ?>>2012</option>
              </select>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>タイプ</th>
            <td style="text-align:left;">
              <?php if ($dataChange['all'] == false OR $dataChange['type'] == false) : ?>
              <?php switch ($rarryDetails['type']) { case 1 : $typeName = 'リーグ戦'; break; case 2 : $typeName = 'トーナメント'; break; default : $typeName = 'その他'; }?>
              <?php echo $typeName."\n"; ?>
              <input type="hidden"  name="type" value="<?php echo $rarryDetails['type']; ?>" />
              <?php else : ?>
              <select name="type">
                <option value="0"<?php if ($rarryDetails['type'] == 0) { echo 'selected="selected"'; } ?>>その他</option>
                <option value="1"<?php if ($rarryDetails['type'] == 1) { echo 'selected="selected"'; } ?>>リーグ戦</option>
                <option value="2"<?php if ($rarryDetails['type'] == 2) { echo 'selected="selected"'; } ?>>トーナメント</option>
              </select>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>親大会</th>
            <td style="text-align:left;">
              <?php if ($dataChange['all'] == false OR $dataChange['parent'] == false) : ?>
              <?php
              for ($i = 0; $i < $rarryAllDataCount; $i++) {
                  if ($rarryDetails['parent_id'] == $rarryAllDatas[$i]['id']) { $parentName = $rarryAllDatas[$i]['rarry_name'].'&nbsp;'.$rarryAllDatas[$i]['rarry_sub_name']; break; }
                  else { $parentName = 'なし'; }
              }
              ?>
              <?php echo $parentName."\n"; ?>
              <input type="hidden"  name="parent_id" value="<?php echo $rarryDetails['parent_id']; ?>" />
              <?php else : ?>
              <select name="parent_id">
                <option value="0">なし</option>
                <?php for ($i = 0; $i < $rarryAllDataCount; $i++) : ?>
                <?php if ($rid == $rarryAllDatas[$i]['id']) {continue;} ?>
                <option value="<?php echo $rarryAllDatas[$i]['id']; ?>"<?php if ($rarryDetails['parent_id'] == $rarryAllDatas[$i]['id']) { echo 'selected="selected"'; } ?>><?php echo $rarryAllDatas[$i]['rarry_sub_name']; ?></option>
<?php /*
                <option value="0"<?php if ($rarryDetails['parent_id'] == 0) { echo 'selected="selected"'; } ?>>なし</option>
                <option value="1"<?php if ($rarryDetails['parent_id'] == 1) { echo 'selected="selected"'; } ?>><?php echo $rarryAllDatas[1]; ?>2007-2008シーズン</option>
                <option value="2"<?php if ($rarryDetails['parent_id'] == 2) { echo 'selected="selected"'; } ?>>2007-2008 プレイオフ</option>
                <option value="3"<?php if ($rarryDetails['parent_id'] == 3) { echo 'selected="selected"'; } ?>>2009シーズン</option>
*/ ?>
                <?php endfor; ?>
              </select>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>区切り</th>
            <td style="text-align:left;">
              <?php if ($mode == 'up') : ?>
              <select name="progress">
                <option value="0"<?php if ($rarryDetails['progress'] == 0) { echo ' selected="selected"'; } ?>>開始前</option>
                <option value="1"<?php if ($rarryDetails['progress'] == 1) { echo ' selected="selected"'; } ?>>初期</option>
                <option value="2"<?php if ($rarryDetails['progress'] == 2) { echo ' selected="selected"'; } ?>>中間Ａ</option>
                <option value="3"<?php if ($rarryDetails['progress'] == 3) { echo ' selected="selected"'; } ?>>中間Ｂ</option>
                <option value="4"<?php if ($rarryDetails['progress'] == 4) { echo ' selected="selected"'; } ?>>中間Ｃ</option>
                <option value="5"<?php if ($rarryDetails['progress'] == 5) { echo ' selected="selected"'; } ?>>中間Ｄ</option>
                <option value="6"<?php if ($rarryDetails['progress'] == 6) { echo ' selected="selected"'; } ?>>中間Ｅ</option>
                <option value="7"<?php if ($rarryDetails['progress'] == 7) { echo ' selected="selected"'; } ?>>中間Ｆ</option>
                <option value="8"<?php if ($rarryDetails['progress'] == 8) { echo ' selected="selected"'; } ?>>最終</option>
                <option value="9"<?php if ($rarryDetails['progress'] == 9) { echo ' selected="selected"'; } ?>>大会終了</option>
              </select>
              <?php else : ?>
              開始前
              <input type="hidden" name="progress" value="0" />
              <?php endif ; ?>
            </td>
          </tr>
          <?php if ($mode == 'up') : ?>
          <tr>
            <th>大会終了</th>
            <td style="text-align:left;">
              <input type="checkbox" name="finish_flg" value="1"<?php if ($rarryDetails['finish_flg'] == 1) { echo ' checked="checked"'; } ?> />
              大会が終了の場合はチェック
            </td>
          </tr>
          <?php endif ; ?>
          <tr>
            <td colspan="2" style="background-color:#FFFFFF;text-align:center;">&nbsp;
              <input type="submit" value="登録・更新確認" />&nbsp;
              <input type="reset" value="リセット" />&nbsp;
              <input type="button" value="大会一覧へ戻る" onclick="location.href='./master_rarry.php'" />
              <?php if ($mode != 'up') : ?>
              <input type="hidden" name="finish_flg" value="0" />
              <?php endif ; ?>
            </td>
          </tr>
          </tbody>
          <?php elseif ($sts == 'comp' OR $sts == 'complet') :?>
          <tbody>
          <tr>
            <th>大会名</th>
            <td style="text-align:left;"><?php echo $rarry_name; ?></td>
          </tr>
          <tr>
            <th>副題名</th>
            <td style="text-align:left;"><?php echo $rarry_sub_name; ?></td>
          </tr>
          <tr>
            <th>シーズン</th>
            <td style="text-align:left;"><?php echo $season; ?></td>
          </tr>
          <tr>
            <th>タイプ</th>
            <td style="text-align:left;"><?php echo $rarryType; ?></td>
          </tr>
          <tr>
            <th>親大会</th>
            <td style="text-align:left;"><?php echo $parentName; ?></td>
          </tr>
          <tr>
            <th>区切り</th>
            <td style="text-align:left;"><?php echo $progressView; ?></td>
          </tr>
          <?php if ($mode == 'up') : ?>
          <tr>
            <th>大会終了</th>
            <td style="text-align:left;"><?php echo $finishView; ?></td>
          </tr>
          <?php endif ; ?>
          <tr>
            <td colspan="2" style="background-color:#FFFFFF;text-align:center;">
              <input type="hidden" name="rarry_name" value="<?php echo $rarry_name; ?>" />
              <input type="hidden" name="rarry_sub_name" value="<?php echo $rarry_sub_name; ?>" />
              <input type="hidden" name="season" value="<?php echo $season; ?>" />
              <input type="hidden" name="type" value="<?php echo $type; ?>" />
              <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
              <input type="hidden" name="progress" value="<?php echo $progress; ?>" />
              <input type="hidden" name="finish_flg" value="<?php echo $finish_flg; ?>" />
              <?php if ($sts == 'complet') : ?>
              <input type="button" value="大会一覧へ戻る" onclick="location.href='./master_rarry.php'" />&nbsp;
              <?php else : ?>
              <input type="submit" value="登録・更新" />&nbsp;
              <input type="button" value="やり直す" onclick="returnForm()" />&nbsp;
              <?php endif ;?>
            </td>
          </tr>
          </tbody>
          <?php endif ; ?>
        </table>
        <input type="hidden" name="rid" value="<?php echo $rid; ?>" />
        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
        <input type="hidden" name="sts" value="<?php echo $sts; ?>" />
        </form>
      </div>
      <div style="font-weight:bold;color:blue;">
        <ul>
          <li>※&nbsp;大会稼働中は「シーズン」「タイプ」「親大会」の変更は出来ません。</li>
          <li>※&nbsp;大会終了後のデータ変更は出来ません。</li>
        </ul>
      </div>
    </div><?php echo "\n"/* END center-column */ ?>
  </div><?php echo "\n"/* END middle */ ?>
  <div id="footer"></div>
</div><?php echo "\n"/* END main */ ?>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>