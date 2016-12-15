<?php
////////////////////////////////////////////////////
/*
 * 会場データ追加・編集・削除
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('all', 'style');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array();

(string)$pageTitle = '会場設定・編集';
(string)$titleName = '修正・変更';
(string)$scriptName = "master_hall_change.php";
(string)$changeScriptName = "master_hall_change.php";
(string)$backScriptName = "master_hall.php";
(string)$mode = '';
(string)$sts = '';

(string)$h_name = '';
(string)$h_kana = '';
(string)$sub_name = '';
(string)$ryakumei = '';
(string)$zip1 = '';
(string)$zip2 = '';
(string)$place = '';
(string)$city = '';
(string)$address1 = '';
(string)$address2 = '';
(string)$tel = '';
(string)$tel2 = '';
(string)$tel3 = '';
(float)$latitude = 35.184962781;
(float)$longitude = 136.89960479;
(string)$site = '';
(string)$cort = array();
(string)$paramError = array();
(string)$errorStyle = ' style="font-weight:bold;color:#FF0000;"';
(int)$nullCort = 0;
(boolean)$dbSet = false;
(string)$insCortData = array();
(string)$commitText = '';

require_once './common.inc';

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "GET") {
    while(list ($key, $val) = each($_GET)) {
        $$key = encode($val);
    }
    if (!isset($hid)) {
        $mode = 'new';
        $titleName = '新規登録';
    } else {
        $mode = 'update';
//        $sts = 'conf';
        $dbData = true;
    }
} else if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
        $dbDatas[$key] = $$key;
//print $key." = ".$val."<br />";
    }
}

/*
 * フォーム処理
 */
if ($sts == 'comp') {
	(int)$errorNum = 0;
    // [ 会場名 ]フォームチェック
    if ($paramCheckClassObj->isNullCheck($errorMessageObj, $h_name, 4, 60) == false) {
        $paramError['h_name'] = "<div$errorStyle>".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ 読みカナ ]フォームチェック
    if ($paramCheckClassObj->admin_there_katakana($errorMessageObj, $h_kana, 4, 60) == false) {
        $paramError['h_kana'] = "<div$errorStyle>".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ 略名 ]フォームチェック
    if ($paramCheckClassObj->isNullCheck($errorMessageObj, $ryakumei, 4, 30) == false) {
        $paramError['ryakumei'] = "<div$errorStyle>".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ 郵便番号1 ]フォームチェック
    if ($paramCheckClassObj->isNumberCheck($errorMessageObj, $zip1, 3, 3) == false) {
        $paramError['zip1'] = "<div$errorStyle>郵便番号1：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ 郵便番号2 ]フォームチェック
    if ($paramCheckClassObj->isNumberCheck($errorMessageObj, $zip2, 4, 4) == false) {
        $paramError['zip2'] = "<div$errorStyle>郵便番号2：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ 都道府県 ]フォームチェック
    if ($paramCheckClassObj->there_zenkaku($errorMessageObj, $place, 2, 20) == false) {
        $paramError['place'] = "<div$errorStyle>都道府県：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ 区・市 ]フォームチェック
    if ($paramCheckClassObj->isNameCheck($errorMessageObj, $city, 2, 50) == false) {
        $paramError['city'] = "<div$errorStyle>区・市：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ 町村＋丁目番地 ]フォームチェック
    if ($paramCheckClassObj->isNullCheck($errorMessageObj, $address1, 3, 50) == false) {
        $paramError['address1'] = "<div$errorStyle>町村＋丁目番地：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ 住所その他 ]フォームチェック
    if ($address2 != '') {
        if ($paramCheckClassObj->isNullCheck($errorMessageObj, $address2, 4, 50) == false) {
            $paramError['address2'] = "<div$errorStyle>その他：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
            $errorNum++;
        }
    }
    // [ TEL1 ]フォームチェック
    if ($paramCheckClassObj->isNumberCheck($errorMessageObj, $tel, 2, 6) == false) {
        $paramError['tel'] = "<div$errorStyle>TEL1：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ TEL2 ]フォームチェック
    if ($paramCheckClassObj->isNumberCheck($errorMessageObj, $tel2, 2, 6) == false) {
        $paramError['tel2'] = "<div$errorStyle>TEL2：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ TEL3 ]フォームチェック
    if ($paramCheckClassObj->isNumberCheck($errorMessageObj, $tel3, 4, 6) == false) {
        $paramError['tel3'] = "<div$errorStyle>TEL3：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
        $errorNum++;
    }
    // [ 緯度 ]フォームチェック
    if ($latitude != '') {
        if ($paramCheckClassObj->isLatLonCheck($errorMessageObj, $latitude) == false) {
            $paramError['latitude'] = "<div$errorStyle>緯度：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
            $errorNum++;
        }
    }
    // [ 経度 ]フォームチェック
    if ($longitude != '') {
        if ($paramCheckClassObj->isLatLonCheck($errorMessageObj, $longitude) == false) {
            $paramError['longitude'] = "<div$errorStyle>経度：".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
            $errorNum++;
        }
    }
    // [ HPアドレス ]フォームチェック
    if ($site != '') {
        if ($paramCheckClassObj->isUrlCheck($errorMessageObj, $site) == false) {
            $paramError['site'] = "<div$errorStyle>".$paramCheckClassObj->getErrorMessageValue()."</div>\n";
            $errorNum++;
        }
    }
    // [ 使用コート ]フォームチェック
    if (count($cort) > 0) {
    	$cortNo = 0;
        for ($i=0; $i<7; $i++) {
            if ($cort[$i] == '') {
                $nullCort++;
                continue;
            } else {
                $insCortData[$cortNo]['id'] = $cortId[$i];
                $insCortData[$cortNo]['name'] = $cort[$i];
                $cortNo++;
            }
            if ($paramCheckClassObj->isNullCheck($errorMessageObj, $cort[$i], 4, 50) == false) {
                $paramError['cort'.$i] = "<span$errorStyle>".$paramCheckClassObj->getErrorMessageValue()."</span>\n";
                $errorNum++;
            }
        }
    }
    if ($nullCort == 7) {
        $paramError['noCort'] = "<div$errorStyle>使用コートを1つ以上入力してください。</div>\n";
        $errorNum++;
    }
    if ($errorNum == 0) {
        $dbSet = true;
    }
}
//print nl2br(print_r($paramError,true));

// データベース登録
if ($dbSet == true) {
    switch ($mode) {
        case 'new' :
            (boolean)$insertFlag = false;
            // トランザクション開始
            $connectionDBObj->Query('bigin');
            // 新規会場登録
            if ($mHallDataObj->insertHallData($dbDatas) == true) {
                // 登録した会場のIDを取得
                $insertHallId = $connectionDBObj->GetCurrentID();
                // 新規コート登録
                if ($mHallDataObj->insertHallCortData($insertHallId, $insCortData) == true) {
                    $insertFlag = true;
                }
            }

            if ($insertFlag) {
                // コミット
                $connectionDBObj->Query('commit');
//                $connectionDBObj->Query('rollback');
                $hid = $insertHallId;
                $mode = 'update';
                $dbData = true;
                $commitText = '<div style="padding:5px;color:blue;font-size:140%;font-weight:bold;">会場を登録しました。</div>';
            } else {
                // ロールバック
                $connectionDBObj->Query('rollback');
            }
            // 付属コート登録
            break;
        case 'update' :
            // トランザクション開始
            $connectionDBObj->Query('bigin');
            // 会場更新
            if ($mHallDataObj->updateHallData($hid, $dbDatas) == true) {
            }
            // 付属コート更新
            $commitText = '<div style="padding:5px;color:blue;font-size:140%;font-weight:bold;">会場を更新しました。</div>';
            break;
        default :
    }
}

// 更新モード
//if ($mode == 'update' OR $sts == 'conf' AND isset($hid)) {
if ($dbData) {

    $sts = 'comp';

    // 会場情報
    if ($mHallDataObj->selectHallData($hid) == true) {
        $hallDatas = $mHallDataObj->getHallData();
        foreach ($hallDatas as $key => $val) {
            $$key = $val;
        }
    }
    $hallDatasNum = count($hallDatas);

    // アリーナ情報
    if ($mHallDataObj->hallCortData($hallDatas['HallId']) == true) {
        $hallCortDatas = $mHallDataObj->getHallCortDatas();
    }
    $hallCortDataNum = count($hallCortDatas);
    for ($i = 0; $i < $hallCortDataNum; $i++) {
        $cort[$i] = $hallCortDatas[$i]['CortName'];
        $cortId[$i] = $hallCortDatas[$i]['CortId'];
    }
}

if ($mode == 'new') {
	$sts = 'comp';
}
?>
<?php

// HTMLヘッダ読み込み
include_once "block/header-hallChange.php"; ?>

<body onload="load(),initialize()" onunload="GUnload()">
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
      <?php echo $commitText; ?>
      <div>
<?php
//print nl2br(print_r($hallDatas,true));
//print nl2br(print_r($hallCortDatas,true));
?>
        <form name="fmHall" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table class="listing">
          <tbody>
          <tr>
            <th>会場名</th>
            <td style="text-align:left;"><input type="text" name="h_name" size="50" value="<?php echo $h_name; ?>" /><?php echo $paramError['h_name']; ?></td>
          </tr>
          <tr>
            <th>読みカナ</th>
            <td style="text-align:left;"><input type="text" name="h_kana" size="50" value="<?php echo $h_kana; ?>" /><?php echo $paramError['h_kana']; ?></td>
          </tr>
          <tr>
            <th>略名</th>
            <td style="text-align:left;"><input type="text" name="ryakumei" size="50" value="<?php echo $ryakumei; ?>" /><?php echo $paramError['ryakumei']; ?></td>
          </tr>
          <tr>
            <th>郵便番号</th>
            <td style="text-align:left;">
              <input type="text" name="zip1" size="8" value="<?php echo $zip1; ?>" />-
              <input type="text" name="zip2" size="8" value="<?php echo $zip2; ?>" />
              <?php echo $paramError['zip1']; ?><?php echo $paramError['zip2']; ?>
            </td>
          </tr>
          <tr>
            <th>住所</th>
            <td style="text-align:left;">
              <select name="place" id="place">
                <option value="愛知県"<?php if ($place == '愛知県') { echo 'selected="selected"'; } ?>>愛知県</option>
                <option value="岐阜県"<?php if ($place == '岐阜県') { echo 'selected="selected"'; } ?>>岐阜県</option>
                <option value="三重県"<?php if ($place == '三重県') { echo 'selected="selected"'; } ?>>三重県</option>
                <option value="静岡県"<?php if ($place == '静岡県') { echo 'selected="selected"'; } ?>>静岡県</option>
              </select>
              <br />
              <input type="text" name="city" size="50" value="<?php echo $city; ?>" id="city" />&nbsp;区・市<br />
              <input type="text" name="address1" size="50" value="<?php echo $address1; ?>" id="address1" />&nbsp;町村＋丁目番地<br />
              <input type="text" name="address2" size="50" value="<?php echo $address2; ?>" />&nbsp;その他
              <?php echo $paramError['place']; ?>
              <?php echo $paramError['city']; ?>
              <?php echo $paramError['address1']; ?>
              <?php echo $paramError['address2']; ?>
            </td>
          </tr>
          <tr>
            <th>TEL</th>
            <td style="text-align:left;">
              <input type="text" name="tel" size="8" value="<?php echo $tel; ?>" />-
              <input type="text" name="tel2" size="8" value="<?php echo $tel2; ?>" />-
              <input type="text" name="tel3" size="8" value="<?php echo $tel3; ?>" />
              <?php echo $paramError['tel']; ?>
              <?php echo $paramError['tel2']; ?>
              <?php echo $paramError['tel3']; ?>
            </td>
          </tr>
          <tr>
            <th>緯度・経度</th>
            <td style="text-align:left;">
              緯度：<input id="show_y" type="text" name="latitude" size="25" value="<?php echo $latitude; ?>" />&nbsp;
              経度：<input id="show_x" type="text" name="longitude" size="25" value="<?php echo $longitude; ?>" />
              <?php echo $paramError['latitude']; ?>
              <?php echo $paramError['longitude']; ?>

            </td>
          </tr>
          <tr>
            <th>HPアドレス</th>
            <td style="text-align:left;"><input type="text" name="site" size="100" value="<?php echo $site; ?>" /><?php echo $paramError['site']; ?></td>
          </tr>
          <tr>
            <th>使用コート</th>
            <td style="text-align:left;">
              No.1&nbsp;:&nbsp;<input type="text" name="cort[]" size="20" value="<?php echo $cort[0]; ?>" /><input type="hidden" name="cortId[]" value="<?php echo $cortId[0]; ?>" /><?php echo $paramError['cort0']; ?><br />
              No.2&nbsp;:&nbsp;<input type="text" name="cort[]" size="20" value="<?php echo $cort[1]; ?>" /><input type="hidden" name="cortId[]" value="<?php echo $cortId[1]; ?>" /><?php echo $paramError['cort1']; ?><br />
              No.3&nbsp;:&nbsp;<input type="text" name="cort[]" size="20" value="<?php echo $cort[2]; ?>" /><input type="hidden" name="cortId[]" value="<?php echo $cortId[2]; ?>" /><?php echo $paramError['cort2']; ?><br />
              No.4&nbsp;:&nbsp;<input type="text" name="cort[]" size="20" value="<?php echo $cort[3]; ?>" /><input type="hidden" name="cortId[]" value="<?php echo $cortId[3]; ?>" /><?php echo $paramError['cort3']; ?><br />
              No.5&nbsp;:&nbsp;<input type="text" name="cort[]" size="20" value="<?php echo $cort[4]; ?>" /><input type="hidden" name="cortId[]" value="<?php echo $cortId[4]; ?>" /><?php echo $paramError['cort4']; ?><br />
              No.6&nbsp;:&nbsp;<input type="text" name="cort[]" size="20" value="<?php echo $cort[5]; ?>" /><input type="hidden" name="cortId[]" value="<?php echo $cortId[5]; ?>" /><?php echo $paramError['cort5']; ?><br />
              No.7&nbsp;:&nbsp;<input type="text" name="cort[]" size="20" value="<?php echo $cort[6]; ?>" /><input type="hidden" name="cortId[]" value="<?php echo $cortId[6]; ?>" /><?php echo $paramError['cort6']; ?>
              <?php echo $paramError['noCort']; ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="background-color:#FFFFFF;">
              <input type="button" value="登録・更新" onclick="halldataChangeConfirm()" />&nbsp;
              <input type="reset" value="リセット" />&nbsp;
              <input type="button" value="会場一覧に戻る" onclick="location.href='<?php echo $backScriptName; ?>'" />
            </td>
          </tr>
          </tbody>
        </table>
        <input type="hidden" name="hid" value="<?php echo $hid; ?>" />
        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
        <input type="hidden" name="sts" value="<?php echo $sts; ?>" />
        </form>
        <div id="map" style="width: 700px; height: 400px"></div>
      </div>
    </div><?php echo "\n"/* END center-column */ ?>
  </div><?php echo "\n"/* END middle */ ?>
  <div id="footer"></div>
</div><?php echo "\n"/* END main */ ?>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>