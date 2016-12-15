<?php
////////////////////////////////////////////////////
/*
 * チームマスタデータ追加・編集・削除
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('all', 'style');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array();

(string)$pageTitle = 'チームマスタ情報設定・編集';
(string)$titleName = '修正・変更';
(string)$backScriptName = "master_team.php";
(string)$mode = '';
(string)$sts = '';
(string)$paramError = array();
(string)$requiredData = '<span style="color:#ff3333;">★</span>';
(int)$rid = null;
(int)$fmError = 0;
(boolean)$dbInsUp = false;
$rarryDetails = array();
$teamColor = array(
                   "black" => "黒色",
                   "silver" => "シルバー",
                   "white" => "白色",
                   "maroon" => "マルーン",
                   "red" => "赤色",
                   "purple" => "紫色",
                   "pink" => "ピンク",
                   "green" => "濃緑色",
                   "lime" => "緑色",
                   "yellow" => "黄色",
                   "navy" => "紺色",
                   "blue" => "青色",
                   "aqua" => "水色",
                   "orange" => "オレンジ",
                   "gold" => "ゴールド"
                   );

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
//print nl2br(print_r($_SESSION, true));

// 確認・登録モード
if ($sts == 'comp' OR $sts == 'conf') {

	// 登録モード
    if ($sts == 'comp') {
        $sts = 'complet';
        $titleName = '登録完了画面';
        $insertData = $_POST;
//print nl2br(print_r($insertData, true));

        switch ($mode) {
            case 'up' :
                // 更新登録
                $dbInsUp = $teamDataChangeObj->teamDataChange($_SESSION['rarryId'], $tid, $insertData, false);
                break;
            default :
                // 新規登録
                $dbInsUp = $teamDataChangeObj->teamDataInsert($insertData);
        }
    }
    // 確認モード
    if ($sts == 'conf') {
        /*
         * パラメータチェック
         */
        // チーム名称
        if ($paramCheckClassObj->isNullCheck($errorMessageObj, $teamName, 2, 64) == false) {
            $paramError['teamName'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }
        // チーム名読みカナ
        if ($paramCheckClassObj->admin_there_katakana($errorMessageObj, $teamKana, 2, 64) == false) {
            $paramError['teamKana'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }
        // チーム略名
        if ($paramCheckClassObj->isNullCheck($errorMessageObj, $abb_name, 2, 64) == false) {
            $paramError['abb_name'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }
        // ホームカラー
        if ($mode == 'up' AND $teamHomeColor != '') {
            if ($paramCheckClassObj->isNullCheck($errorMessageObj, $teamHomeColor, 1, 8) == false) {
                $paramError['teamHomeColor'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
                $fmError++;
            }
        }
        // アウェイカラー
        if ($mode == 'up' AND $teamAwayColor != '' AND $paramCheckClassObj->isNullCheck($errorMessageObj, $teamAwayColor, 1, 8) == false) {
            $paramError['teamAwayColor'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }
        // 活動地区
        if ($teamDistrictId != '' AND $paramCheckClassObj->isNumberCheck($errorMessageObj, $teamDistrictId, 1, 2) == false) {
            $paramError['teamDistrictId'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }
        // 活動場所
        if ($mode == 'up' AND $teamPlace != '' AND $paramCheckClassObj->isNullCheck($errorMessageObj, $teamPlace, 4, 60) == false) {
            $paramError['teamPlace'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }
        // 代表名
        if ($mode == 'up' AND $paramCheckClassObj->there_zenkaku($errorMessageObj, $teamRep, 2, 16) == false) {
            $paramError['teamRep'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }
        // 代表TEL
        if ($mode == 'up' AND $paramCheckClassObj->isMobileCheck($errorMessageObj, $teamRepTel) == false) {
            $paramError["teamRepTel"] = "<span style=\"font-weight:bold;color:red;\">".$paramCheckClassObj->getErrorMessageValue()."</span>\n";
            $fmError++;
        }
        // 代表E-Mail
        if ($mode == 'up' AND $paramCheckClassObj->isMailCheck($errorMessageObj, $teamRepMail) == false) {
            $paramError["teamRepMail"] = "<span style=\"font-weight:bold;color:red;\">".$paramCheckClassObj->getErrorMessageValue()."</span>\n";
            $fmError++;
        }
        // 副代表名
        if ($teamSubRep != '' AND $paramCheckClassObj->there_zenkaku($errorMessageObj, $teamSubRep, 2, 16) == false) {
            $paramError['teamSubRep'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
            $fmError++;
        }
        // 副代表TEL
        if ($teamSubRepTel != '' AND $paramCheckClassObj->isMobileCheck($errorMessageObj, $teamSubRepTel) == false) {
            $paramError["teamSubRepTel"] = "<span style=\"font-weight:bold;color:red;\">".$paramCheckClassObj->getErrorMessageValue()."</span>\n";
            $fmError++;
        }
        // 副代表E-Mail
        if ($teamSubRepMail != '' AND $paramCheckClassObj->isMailCheck($errorMessageObj, $teamSubRepMail) == false) {
            $paramError["teamSubRepMail"] = "<span style=\"font-weight:bold;color:red;\">".$paramCheckClassObj->getErrorMessageValue()."</span>\n";
            $fmError++;
        }
        // ID
        if ($mode == 'up' AND $paramCheckClassObj->there_alnum($errorMessageObj, $login_id, 4, 8) == false) {
            $paramError["login_id"] = "<span style=\"font-weight:bold;color:red;\">".$paramCheckClassObj->getErrorMessageValue()."</span>\n";
            $fmError++;
        }
        // パスワード
        if ($pw_in == '1') {
            if ($paramCheckClassObj->there_alnum($errorMessageObj, $passwd, 6, 10) == false) {
                $paramError["passwd"] = "<span style=\"font-weight:bold;color:red;\">".$paramCheckClassObj->getErrorMessageValue()."</span>\n";
                $fmError++;
            }
        } else {
            $passwd = '';
        }

        // フォームエラーなし
        if ($fmError == 0) {
            $sts = 'comp';
            $titleName = '内容確認画面';
        }
    }
}

// 更新モードの時は大会データの取得
if ($mode == 'up' AND $sts == '') {
    // チーム情報の取得
    if ($teamDataObj->selectTeamData($tid) == true) {
        $teamDetails = $teamDataObj->getSelectTeamDatas();
    } else {
        header("Location: ./");
    }
    // ステータスパラメータ
    $sts = 'conf';
}
if ($mode == 'new' AND $sts == '') {
    // ステータスパラメータ
    $sts = 'conf';
}
if ($mode == 'new') {
    $pageTitle = 'チームマスタ情報新規追加';
}

// 活動地区マスタ情報の取得
if ($teamDataObj->allDistrictData() == true) {
    $allDistrictDataArray = $teamDataObj->getDistrictAllDatas();
    $allDistrictCount = count($allDistrictDataArray);
}

// フォームやり直しorフォームエラー発生時
if ($sts == 'fmBack' OR $fmError > 0) {
    $sts = 'conf';
    $teamDetails['teamName'] = $teamName;
    $teamDetails['teamKana'] = $teamKana;
    $teamDetails['abb_name'] = $abb_name;
    $teamDetails['teamClass'] = $teamClass;
    $teamDetails['teamSite'] = $teamSite;
    $teamDetails['teamPhoto'] = $teamPhoto;
    $teamDetails['teamHomeColor'] = $teamHomeColor;
    $teamDetails['teamAwayColor'] = $teamAwayColor;
    $teamDetails['teamDistrictId'] = $teamDistrictId;
    $teamDetails['teamPlace'] = $teamPlace;
    $teamDetails['teamRegion'] = $teamRegion;
    $teamDetails['teamWeek'] = $teamWeek;
    $teamDetails['teamSubLeague'] = $teamSubLeague;
    $teamDetails['teamComment'] = $teamComment;
    $teamDetails['teamRep'] = $teamRep;
    $teamDetails['teamRepTel'] = $teamRepTel;
    $teamDetails['teamRepMail'] = $teamRepMail;
    $teamDetails['teamSubRep'] = $teamSubRep;
    $teamDetails['teamSubRepTel'] = $teamSubRepTel;
    $teamDetails['teamSubRepMail'] = $teamSubRepMail;
    $teamDetails['teamUmpire1'] = $teamUmpire1;
    $teamDetails['teamUmpire2'] = $teamUmpire2;
    $teamDetails['teamUmpire3'] = $teamUmpire3;
    $teamDetails['login_id'] = $login_id;
    $teamDetails['passwd'] = $passwd;
}

?>
<?php

// HTMLヘッダ読み込み
include_once "block/header.php"; ?>
<body>

<script language="javascript" type="text/javascript">
//<![CDATA[

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
      <p style="text-align:center;font-size:140%;font-weight:bold;color:blue;">チーム情報登録が完了しました</p>
      <?php endif ;?>
      <div>
        <form name="fmRarry" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <table class="listing" style="width:100%;font-size:12px;">
          <tbody>
          <tr>
            <th rowspan="3" style="width:100px;">チーム</th>
            <th style="width:60px;">名称</th>
            <?php if ($sts == 'conf') : ?>
            <td style="width:10px;"><?php echo $requiredData; ?></td>
            <?php endif; ?>
            <td style="width:520px;text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamName; ?>
              <?php if ($sts == 'comp') : ?>
              <input type="hidden" name="teamName" value="<?php echo $teamName; ?>" />
              <?php endif; ?>
              <?php else : ?>
              <input type="text" name="teamName" size="50" value="<?php echo $teamDetails['teamName']; ?>" />&nbsp;<?php echo $paramError['teamName']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>読みカナ</th>
            <?php if ($sts == 'conf') : ?>
            <td><?php echo $requiredData; ?></td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamKana; ?>
              <input type="hidden" name="teamKana" value="<?php echo $teamKana; ?>" />
              <?php else : ?>
              <input type="text" name="teamKana" size="50" value="<?php echo $teamDetails['teamKana']; ?>" />&nbsp;<?php echo $paramError['teamKana']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>略名</th>
            <?php if ($sts == 'conf') : ?>
            <td><?php echo $requiredData; ?></td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $abb_name; ?>
              <input type="hidden" name="abb_name" value="<?php echo $abb_name; ?>" />
              <?php else : ?>
              <input type="text" name="abb_name" size="50" value="<?php echo $teamDetails['abb_name']; ?>" />&nbsp;<?php echo $paramError['abb_name']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th colspan="2">ホームページ</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamSite; ?>
              <input type="hidden" name="teamSite" value="<?php echo $teamSite; ?>" />
              <?php else : ?>
              <input type="text" name="teamSite" size="50" value="<?php echo $teamDetails['teamSite']; ?>" />&nbsp;<?php echo $paramError['teamSite']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th colspan="2">写真</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamPhoto; ?>
              <input type="hidden" name="teamPhoto" value="<?php echo $teamPhoto; ?>" />
              <?php else : ?>
              <input type="text" name="teamPhoto" size="50" value="<?php echo $teamDetails['teamPhoto']; ?>" />&nbsp;<?php echo $paramError['teamPhoto']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th rowspan="2">チームカラー</th>
            <th>Home</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php if (count($teamColor) > 0) {
                  foreach ($teamColor as $en_name => $jp_name) {
                      if ($en_name == $teamHomeColor) { $homeColorView = $jp_name; }
                  }
              } ?>
              <?php echo $homeColorView; ?>
              <input type="hidden" name="teamHomeColor" value="<?php echo $teamHomeColor; ?>" />
              <?php else : ?>
              <select name="teamHomeColor">
                <option value="">下記より選択</option>
                <?php if (count($teamColor) > 0) : ?>
                <?php foreach ($teamColor as $en_name => $jp_name) : ?>
                <?php ($en_name == $teamDetails['teamHomeColor']) ? $hTeamColorSelected = ' selected="selected"' : $hTeamColorSelected = '' ; ?>
                <option value="<?php echo $en_name; ?>"<?php echo $hTeamColorSelected; ?> style="background-color:<?php echo $en_name; ?>;"><?php echo $jp_name; ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>Away</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php if (count($teamColor) > 0) {
                  foreach ($teamColor as $en_name => $jp_name) {
                      if ($en_name == $teamAwayColor) { $awayColorView = $jp_name; }
                  }
              } ?>
              <?php echo $awayColorView; ?>
              <input type="hidden" name="teamAwayColor" value="<?php echo $teamAwayColor; ?>" />
              <?php else : ?>
              <select name="teamAwayColor">
                <option value="">下記より選択</option>
                <?php if (count($teamColor) > 0) : ?>
                <?php foreach ($teamColor as $en_name => $jp_name) : ?>
                <?php ($en_name == $teamDetails['teamAwayColor']) ? $aTeamColorSelected = ' selected="selected"' : $aTeamColorSelected = '' ; ?>
                <option value="<?php echo $en_name; ?>"<?php echo $aTeamColorSelected; ?> style="background-color:<?php echo $en_name; ?>;"><?php echo $jp_name; ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th rowspan="4">活動</th>
            <th>地区</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php for ($i = 0; $i < $allDistrictCount; $i++) {
                  if ($allDistrictDataArray[$i]['d_id'] == $teamDistrictId) {
                      $district_name = $allDistrictDataArray[$i]['district_name'] ;
                      break;
                  }
              } ?>
              <?php echo $district_name; ?>
              <input type="hidden" name="teamDistrictId" value="<?php echo $teamDistrictId; ?>" />
              <?php else : ?>
              <select name="teamDistrictId">
                <option value="">なし</option>
                <?php for ($i = 0; $i < $allDistrictCount; $i++) : ?>
                <?php ($allDistrictDataArray[$i]['d_id'] == $teamDetails['teamDistrictId']) ? $districtSelected = ' selected="selected"' : $districtSelected = '' ; ?>
                <option value="<?php echo $allDistrictDataArray[$i]['d_id']; ?>"<?php echo $districtSelected; ?>><?php echo $allDistrictDataArray[$i]['district_name']; ?></option>
                <?php endfor; ?>
              </select>&nbsp;<?php echo $paramError['teamDistrictId']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>場所</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamPlace; ?>
              <input type="hidden" name="teamPlace" value="<?php echo $teamPlace; ?>" />
              <?php else : ?>
              <input type="text" name="teamPlace" size="50" value="<?php echo $teamDetails['teamPlace']; ?>" />&nbsp;<?php echo $paramError['teamPlace']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>Region</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamRegion; ?>
              <input type="hidden" name="teamRegion" value="<?php echo $teamRegion; ?>" />
              <?php else : ?>
              <input type="text" name="teamRegion" size="50" value="<?php echo $teamDetails['teamRegion']; ?>" />&nbsp;<?php echo $paramError['teamRegion']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>曜日</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamWeek; ?>
              <input type="hidden" name="teamWeek" value="<?php echo $teamWeek; ?>" />
              <?php else : ?>
              <input type="text" name="teamWeek" size="50" value="<?php echo $teamDetails['teamWeek']; ?>" />&nbsp;<?php echo $paramError['teamWeek']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th colspan="2">その他リーグ登録</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamSubLeague; ?>
              <input type="hidden" name="teamSubLeague" value="<?php echo $teamSubLeague; ?>" />
              <?php else : ?>
              <input type="text" name="teamSubLeague" size="50" value="<?php echo $teamDetails['teamSubLeague']; ?>" />&nbsp;<?php echo $paramError['teamSubLeague']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th colspan="2">チームコメント</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamComment; ?>
              <input type="hidden" name="teamComment" value="<?php echo $teamComment; ?>" />
              <?php else : ?>
              <textarea name="teamComment" cols="80" rows="3"><?php echo $teamDetails['teamComment']; ?></textarea>&nbsp;<?php echo $paramError['teamComment']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th rowspan="3">代表者</th>
            <th>氏名</th>
            <?php if ($sts == 'conf') : ?>
            <td><?php echo ($mode == 'up') ? $requiredData : '&nbsp;' ; ?></td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamRep; ?>
              <input type="hidden" name="teamRep" value="<?php echo $teamRep; ?>" />
              <?php else : ?>
              <input type="text" name="teamRep" size="50" value="<?php echo $teamDetails['teamRep']; ?>" />&nbsp;<?php echo $paramError['teamRep']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>TEL</th>
            <?php if ($sts == 'conf') : ?>
            <td><?php echo ($mode == 'up') ? $requiredData : '&nbsp;' ; ?></td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamRepTel; ?>
              <input type="hidden" name="teamRepTel" value="<?php echo $teamRepTel; ?>" />
              <?php else : ?>
              <input type="text" name="teamRepTel" size="50" value="<?php echo $teamDetails['teamRepTel']; ?>" />&nbsp;<?php echo $paramError['teamRepTel']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>E-Mail</th>
            <?php if ($sts == 'conf') : ?>
            <td><?php echo ($mode == 'up') ? $requiredData : '&nbsp;' ; ?></td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamRepMail; ?>
              <input type="hidden" name="teamRepMail" value="<?php echo $teamRepMail; ?>" />
              <?php else : ?>
              <input type="text" name="teamRepMail" size="80" value="<?php echo $teamDetails['teamRepMail']; ?>" />&nbsp;<?php echo $paramError['teamRepMail']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th rowspan="3">副代表</th>
            <th>氏名</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamSubRep; ?>
              <input type="hidden" name="teamSubRep" value="<?php echo $teamSubRep; ?>" />
              <?php else : ?>
              <input type="text" name="teamSubRep" size="50" value="<?php echo $teamDetails['teamSubRep']; ?>" />&nbsp;<?php echo $paramError['teamSubRep']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>TEL</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamSubRepTel; ?>
              <input type="hidden" name="teamSubRepTel" value="<?php echo $teamSubRepTel; ?>" />
              <?php else : ?>
              <input type="text" name="teamSubRepTel" size="50" value="<?php echo $teamDetails['teamSubRepTel']; ?>" />&nbsp;<?php echo $paramError['teamSubRepTel']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>E-Mail</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamSubRepMail; ?>
              <input type="hidden" name="teamSubRepMail" value="<?php echo $teamSubRepMail; ?>" />
              <?php else : ?>
              <input type="text" name="teamSubRepMail" size="80" value="<?php echo $teamDetails['teamSubRepMail']; ?>" />&nbsp;<?php echo $paramError['teamSubRepMail']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th rowspan="3">帯同審判</th>
            <th>１</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamUmpire1; ?>
              <input type="hidden" name="teamUmpire1" value="<?php echo $teamUmpire1; ?>" />
              <?php else : ?>
              <input type="text" name="teamUmpire1" size="50" value="<?php echo $teamDetails['teamUmpire1']; ?>" />&nbsp;<?php echo $paramError['teamUmpire1']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>２</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamUmpire2; ?>
              <input type="hidden" name="teamUmpire2" value="<?php echo $teamUmpire2; ?>" />
              <?php else : ?>
              <input type="text" name="teamUmpire2" size="50" value="<?php echo $teamDetails['teamUmpire2']; ?>" />&nbsp;<?php echo $paramError['teamUmpire2']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>３</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $teamUmpire3; ?>
              <input type="hidden" name="teamUmpire3" value="<?php echo $teamUmpire3; ?>" />
              <?php else : ?>
              <input type="text" name="teamUmpire3" size="50" value="<?php echo $teamDetails['teamUmpire3']; ?>" />&nbsp;<?php echo $paramError['teamUmpire3']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th colspan="2">チームID</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo $login_id; ?>
              <input type="hidden" name="login_id" value="<?php echo $login_id; ?>" />
              <?php else : ?>
              <input type="text" name="login_id" size="12" value="<?php echo $teamDetails['login_id']; ?>" />&nbsp;<?php echo $paramError['login_id']; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th colspan="2">パスワード</th>
            <?php if ($sts == 'conf') : ?>
            <td>&nbsp;</td>
            <?php endif; ?>
            <td style="text-align:left;">
              <?php if ($sts != 'conf') : ?>
              <?php echo ($passwd != '') ? $passwd : '未変更' ; ?>
              <input type="hidden" name="pw_in" value="<?php echo $pw_in; ?>" />
              <input type="hidden" name="passwd" value="<?php echo $passwd; ?>" />
              <?php else : ?>
              <input type="checkbox" name="pw_in" value="1"<?php if ($pw_in != '') { echo ' checked=checked"'; } ?> />&nbsp;
              <input type="text" name="passwd" size="12" value="<?php echo $teamDetails['passwd']; ?>" />&nbsp;<?php echo $paramError['passwd']; ?>
              <div style="color:#0000FF;font-weight:bold;">※&nbsp;パスワードを変更するときのみチェックして入力</div>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <td colspan="4" style="background-color:#FFFFFF;text-align:center;">&nbsp;
              <input type="hidden" name="tid" value="<?php echo $tid; ?>" />
              <?php if ($sts == 'conf') :?>
              <input type="submit" value="登録・更新確認" />&nbsp;
              <input type="reset" value="リセット" />&nbsp;
              <input type="button" value="一覧へ戻る" onclick="location.href='./<?php echo $backScriptName; ?>'" />
              <?php else : ?>
              <?php if ($sts == 'complet') : ?>
              <input type="button" value="チーム一覧へ戻る" onclick="location.href='<?php echo $backScriptName; ?>'" />&nbsp;
              <?php else : ?>
              <input type="submit" value="登録・更新" />&nbsp;
              <input type="button" value="やり直す" onclick="returnForm()" />&nbsp;
              <?php endif ;?>
              <?php endif ;?>
            </td>
          </tr>
          </tbody>
        </table>
        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
        <input type="hidden" name="sts" value="<?php echo $sts; ?>" />
        </form>
      </div>
    </div><?php echo "\n"/* END center-column */ ?>
  </div><?php echo "\n"/* END middle */ ?>
  <div id="footer"></div>
</div><?php echo "\n"/* END main */ ?>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>