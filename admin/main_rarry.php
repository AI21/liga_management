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
(string)$backScriptName = "main_rarry.php";
(string)$mode = '';
(string)$sts = '';
(string)$paramError = array();
(string)$allHallIds = array();
(string)$rarryUseHallId = array();
(string)$useHall = array();
(string)$useBlock = array();

require_once './common.inc';

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "GET") {
    while(list ($key, $val) = each($_GET)) {
        $$key = $val;
//print $key ." = ". $val."<BR>";
    }
} else if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = $val;
//print $key ." = ". $val."<BR>";
    }
}

// 大会情報の取得
if ($rarryDataObj->rarryDetails($_SESSION['rarryId']) == true) {
    $rarryDetails = $rarryDataObj->getRarryDetail();
    $rarryType = $rarryDetails['type'];
    switch ($rarryType) {
        case  1 : $rarryTypeView = 'リーグ戦'; break;
        case  2 : $rarryTypeView = 'トーナメント戦'; break;
        default : $rarryTypeView = 'その他';
    }
} else {
    header("Location: ./");
}

// 確認・登録モード
if ($sts == 'comp' OR $sts == 'conf') {

	// 登録モード
    if ($sts == 'comp') {
        $sts = 'complet';
        $titleName = '登録完了画面';
//print nl2br(print_r($insertData, true));
        $insertData = $_POST;

        // データベース更新
//        $dbInsUp = $rarryDataObj->rarryDetailUpdate($insertData);

    }
    // 確認モード
    if ($sts == 'conf') {

        /*
         * パラメータチェック
         */
        // 使用会場
        if (count($useHall) == 0) {
            $paramError['useHall'] = '<span style="font-weight:bold;color:#FF0000;">使用会場を一つ以上選択してください。</sapn>';
            $fmError++;
        }
        if ($rarryType == 1) {
            // 使用ブロック・クラス
            if (count($useBlock['id']) == 0) {
                $paramError['useBlock'] = '<div style="clear:left;font-weight:bold;color:#FF0000;">使用ブロック・クラスを一つ以上選択してください。</div>';
                $fmError++;
            }
        }
//        if ($paramCheckClassObj->isNullCheck($errorMessageObj, $rarry_name, 4, 60) == false) {
//            $paramError['rarry_name'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
//            $fmError++;
//        }
//        if ($paramCheckClassObj->isNullCheck($errorMessageObj, $rarry_sub_name, 4, 60) == false) {
//            $paramError['rarry_sub_name'] = '<span style="font-weight:bold;color:#FF0000;">'.$paramCheckClassObj->getErrorMessageValue().'</sapn>';
//            $fmError++;
//        }

        $sts = 'comp';
        $titleName = '内容確認画面';
    }

    // フォームエラーなし
    if ($fmError == 0) {
    }
}
//print nl2br(print_r($useBlock,true));
//print $sts." = STS<br>";
// フォームやり直しorフォームエラー発生時
if ($sts == 'fmBack' OR $fmError > 0) {
    $sts = 'conf';
//    $rarryDetails['rarry_name'] = $rarry_name;
//    $rarryDetails['rarry_sub_name'] = $rarry_sub_name;
//    $rarryDetails['define'] = $define;
//    $rarryDetails['type'] = $type;
//    $rarryDetails['parent_id'] = $parent_id;
//    $rarryDetails['progress'] = $progress;
//    $blockDatas[$j]['REGIST_TEAM_NUM'] = $blockDatas[$j]['REGIST_TEAM_NUM'];
    if (isset($useBlock) AND count($useBlock) > 0) {
        for ($i = 0; $i < count($useBlock); $i++) {
//print $key." = KEYs<br>";
            for ($j = 0; $j < count($useBlock['id']); $j++) {
////print $key[$i]." = KEY<br>";
                $blockDatas[$j]['RARRY_CLASS'] = $useBlock['id'][$j];
                $blockDatas[$j]['REGIST_TEAM_NUM'] = $useBlock['teamNum'][$j];
            }
//            for ($j = 0; $j < count($useBlock['teamNum']); $j++) {
//                $blockDatas[$j]['REGIST_TEAM_NUM'] = $blockDatas[$j]['RARRY_CLASS'];
//            }
        }

    }
//    $blockDatas[$j]['REGIST_TEAM_NUM'] = ;
}

    // ブロックマスターデータ情報の取得
    if ($mBlockDataObj->masterBlockData() == true) {
        $mastertBlockDatas = $mBlockDataObj->getMastertBlockDatas();
        if ($sts == '') {
            // 大会使用ブロック(クラス)情報の取得
            if ($mBlockDataObj->rarryRegistBlockData($_SESSION['rarryId']) == true) {
                $blockDatas = $mBlockDataObj->getRarryRegistBlockData();
                for ($i = 0; $i < count($blockDatas); $i++) {
                    $useBlockId[] = $blockDatas[$i]['BLOCK_ID'];
                }
            }
        } else {
//            $blockDatas = $useBlock;
            $useBlockId = $useBlock['id'];
        }
    }

    // 全会場データの取得
    if ($mHallDataObj->allHallData() == true) {
        $allHallDatas = $mHallDataObj->getAllHallDatas();
        if ($sts == '') {
            // 大会別の使用会場データの取得
            if ($mHallDataObj->rarryUseHallData($_SESSION['rarryId']) == true) {
                $rarryUseHallDatas = $mHallDataObj->getRarryUseHallDatas();
                for ($i = 0; $i < count($rarryUseHallDatas); $i++) {
                    $rarryUseHallId[] = $rarryUseHallDatas[$i]['HallId'];
                }
            }
        } else {
            $rarryUseHallId = $useHall;
        }
    }


// 初期基本データ取得
if ($sts == '' OR $sts == 'conf') {
    // ステータスパラメータ
    $sts = 'conf';
}

//print nl2br(print_r($rarryUseHallId,true));
//print nl2br(print_r($allHallDatas,true));
//print nl2br(print_r($hallDatas,true));
//print nl2br(print_r($rarryDetails,true));
//print nl2br(print_r($mastertBlockIds,true));
//print nl2br(print_r($useBlock,true));
//print nl2br(print_r($mastertBlockDatas,true));
//print nl2br(print_r($blockDatas,true));
//print nl2br(print_r($useHall,true));
//print nl2br(print_r($paramError,true));
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
          <tbody>
          <tr>
            <th>大会名</th>
            <td style="text-align:left;"><?php echo $rarryDetails['rarry_name']; ?>&nbsp;<?php echo $rarryDetails['rarry_sub_name']; ?></td>
          </tr>
          <tr>
            <th>タイプ</th>
            <td style="text-align:left;"><?php echo $rarryTypeView; ?></td>
          </tr>
          <tr>
            <th>使用会場</th>
            <td style="text-align:left;">
              <?php if ($sts == 'conf') : ?>
              <?php for ($i = 0; $i < count($allHallDatas); $i++) : ?>
              <div>
                <input type="checkbox" name="useHall[]" value="<?php echo $allHallDatas[$i]['HallId']; ?>"<?php if (in_array($allHallDatas[$i]['HallId'], $rarryUseHallId)) { echo ' checked="checked"'; } ?> id="bk<?php echo $i; ?>" />
                &nbsp;<label for="bk<?php echo $i; ?>"><?php echo $allHallDatas[$i]['HallName']; ?></label>
              </div>
              <?php endfor; ?>
              <?php echo $paramError['useHall']; ?>
              <?php else : ?>
              <?php for ($i = 0; $i < count($useHall); $i++) : ?>
              <?php for ($j = 0; $j < count($allHallDatas); $j++) : ?>
              <?php if ($allHallDatas[$j]['HallId'] == $useHall[$i]) : ?>
              <div>
                <?php echo $allHallDatas[$j]['HallName']; ?><input type="hidden" name="useHall[]" value="<?php echo $useHall[$i]; ?>" />
              </div>
              <?php break;endif; ?>
              <?php endfor; ?>
              <?php endfor; ?>
              <?php endif; ?>
            </td>
          </tr>
          <?php if (isset($rarryType)) :?>
          <?php if ($rarryType == 1) :?>
          <!-- リーグ設定 -->
          <tr>
            <td colspan="2" class="subdata">リーグ設定</td>
          </tr>
          <tr>
            <th>同一チーム<br />対戦カード数</th>
            <td style="text-align:left;">
              <?php if ($sts == 'conf') : ?>
              <select name="vs_card">
                <option value="1"<?php if ($rarryDetails['vs_card'] == 1) { echo 'selected="selected"'; } ?>>1回</option>
              </select>&nbsp;回(非搭載機能)
              <?php else : ?>
              <?php echo $vs_card; ?>&nbsp;回(非搭載機能)<input type="hidden" name="win" value="<?php echo $vs_card; ?>" />
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>ブロック(クラス)</th>
            <td style="text-align:left;">
              <?php if ($sts == 'conf') : ?>
              <?php for ($i = 0; $i < count($mastertBlockDatas); $i++) : ?>
              <div style="clear:left;width:100%;">
                <div style="float:left;width:100px;">
                  <input type="checkbox" name="useBlock[id][<?php echo $mastertBlockDatas[$i]['id']; ?>]" value="<?php echo $mastertBlockDatas[$i]['id']; ?>"<?php if (in_array($mastertBlockDatas[$i]['id'], $useBlockId)) { echo ' checked="checked"'; } ?> id="bk<?php echo $i; ?>" />
                  &nbsp;<label for="bk<?php echo $i; ?>"><?php echo $mastertBlockDatas[$i]['block_name']; ?></label>
                  <input type="hidden" name="useBlock[name][<?php echo $mastertBlockDatas[$i]['id']; ?>]" value="<?php echo $mastertBlockDatas[$i]['block_name']; ?>" />
                </div>
                <div style="float:left;">
                  <?php
                  $registTeamNum = '';
                  for ($j = 0; $j < count($blockDatas); $j++) {
                      if ($blockDatas[$j]['RARRY_CLASS'] == $mastertBlockDatas[$i]['id']) {
                          $registTeamNum = $blockDatas[$j]['REGIST_TEAM_NUM']; break;
                      }
                  }
                  ?><input type="text" size="4" name="useBlock[teamNum][<?php echo $mastertBlockDatas[$i]['id']; ?>]" value="<?php echo $registTeamNum; ?>" />&nbsp;チーム構成
                </div>
              </div>
              <?php endfor; ?>
              <?php echo $paramError['useBlock']; ?>
              <?php else : // 確認・完了画面 ?>
              <?php for ($i = 0; $i < count($useBlock['id']); $i++) : ?>
              <div style="clear:left;width:100%;">
                <div style="float:left;width:100px;">
                  <?php echo $useBlock['name'][$useBlock['id'][$i]]; ?>
                </div>
                <div style="float:left;">
                  <?php echo $useBlock['teamNum'][$useBlock['id'][$i]]; ?>&nbsp;チーム構成
                  <input type="hidden" name="useBlock[id][]" value="<?php echo $useBlock['id'][$i]; ?>" />
                  <input type="hidden" name="useBlock[teamNum][]" value="<?php echo $useBlock['teamNum'][$useBlock['id'][$i]]; ?>" />
                </div>
              </div>
              <?php endfor; ?>
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>勝ち点</th>
            <td style="text-align:left;">
              勝利：
              <?php if ($sts == 'conf') : ?>
              <select name="win">
                <option value="5"<?php if ($rarryDetails['win'] == '5') { echo 'selected="selected"'; } ?>>5</option>
                <option value="4"<?php if ($rarryDetails['win'] == '4') { echo 'selected="selected"'; } ?>>4</option>
                <option value="3"<?php if ($rarryDetails['win'] == '3') { echo 'selected="selected"'; } ?>>3</option>
                <option value="2"<?php if ($rarryDetails['win'] == '2') { echo 'selected="selected"'; } ?>>2</option>
                <option value="1"<?php if ($rarryDetails['win'] == '1') { echo 'selected="selected"'; } ?>>1</option>
                <option value="0"<?php if ($rarryDetails['win'] == '0') { echo 'selected="selected"'; } ?>>0</option>
                <option value="-1"<?php if ($rarryDetails['win'] == '-1') { echo 'selected="selected"'; } ?>>-1</option>
                <option value="-2"<?php if ($rarryDetails['win'] == '-2') { echo 'selected="selected"'; } ?>>-2</option>
                <option value="-3"<?php if ($rarryDetails['win'] == '-3') { echo 'selected="selected"'; } ?>>-3</option>
                <option value="-4"<?php if ($rarryDetails['win'] == '-4') { echo 'selected="selected"'; } ?>>-4</option>
                <option value="-5"<?php if ($rarryDetails['win'] == '-5') { echo 'selected="selected"'; } ?>>-5</option>
              </select>
              <?php else : ?>
              <?php echo $win; ?>&nbsp;点<input type="hidden" name="win" value="<?php echo $win; ?>" />
              <?php endif; ?>
              &nbsp;敗戦：
              <?php if ($sts == 'conf') : ?>
              <select name="lose">
                <option value="5"<?php if ($rarryDetails['lose'] == '5') { echo 'selected="selected"'; } ?>>5</option>
                <option value="4"<?php if ($rarryDetails['lose'] == '4') { echo 'selected="selected"'; } ?>>4</option>
                <option value="3"<?php if ($rarryDetails['lose'] == '3') { echo 'selected="selected"'; } ?>>3</option>
                <option value="2"<?php if ($rarryDetails['lose'] == '2') { echo 'selected="selected"'; } ?>>2</option>
                <option value="1"<?php if ($rarryDetails['lose'] == '1') { echo 'selected="selected"'; } ?>>1</option>
                <option value="0"<?php if ($rarryDetails['lose'] == '0') { echo 'selected="selected"'; } ?>>0</option>
                <option value="-1"<?php if ($rarryDetails['lose'] == '-1') { echo 'selected="selected"'; } ?>>-1</option>
                <option value="-2"<?php if ($rarryDetails['lose'] == '-2') { echo 'selected="selected"'; } ?>>-2</option>
                <option value="-3"<?php if ($rarryDetails['lose'] == '-3') { echo 'selected="selected"'; } ?>>-3</option>
                <option value="-4"<?php if ($rarryDetails['lose'] == '-4') { echo 'selected="selected"'; } ?>>-4</option>
                <option value="-5"<?php if ($rarryDetails['lose'] == '-5') { echo 'selected="selected"'; } ?>>-5</option>
              </select>
              <?php else : ?>
              <?php echo $lose; ?>&nbsp;点<input type="hidden" name="lose" value="<?php echo $lose; ?>" />
              <?php endif; ?>
              &nbsp;同点：
              <?php if ($sts == 'conf') : ?>
              <select name="draw">
                <option value="5"<?php if ($rarryDetails['draw'] == '5') { echo 'selected="selected"'; } ?>>5</option>
                <option value="4"<?php if ($rarryDetails['draw'] == '4') { echo 'selected="selected"'; } ?>>4</option>
                <option value="3"<?php if ($rarryDetails['draw'] == '3') { echo 'selected="selected"'; } ?>>3</option>
                <option value="2"<?php if ($rarryDetails['draw'] == '2') { echo 'selected="selected"'; } ?>>2</option>
                <option value="1"<?php if ($rarryDetails['draw'] == '1') { echo 'selected="selected"'; } ?>>1</option>
                <option value="0"<?php if ($rarryDetails['draw'] == '0') { echo 'selected="selected"'; } ?>>0</option>
                <option value="-1"<?php if ($rarryDetails['draw'] == '-1') { echo 'selected="selected"'; } ?>>-1</option>
                <option value="-2"<?php if ($rarryDetails['draw'] == '-2') { echo 'selected="selected"'; } ?>>-2</option>
                <option value="-3"<?php if ($rarryDetails['draw'] == '-3') { echo 'selected="selected"'; } ?>>-3</option>
                <option value="-4"<?php if ($rarryDetails['draw'] == '-4') { echo 'selected="selected"'; } ?>>-4</option>
                <option value="-5"<?php if ($rarryDetails['draw'] == '-5') { echo 'selected="selected"'; } ?>>-5</option>
              </select>
              <?php else : ?>
              <?php echo $draw; ?>&nbsp;点<input type="hidden" name="draw" value="<?php echo $draw; ?>" />
              <?php endif; ?>
              &nbsp;不戦勝：
              <?php if ($sts == 'conf') : ?>
              <select name="antiwar_win">
                <option value="5"<?php if ($antiwar_win == '5') { echo 'selected="selected"'; } ?>>5</option>
                <option value="4"<?php if ($rarryDetails['antiwar_win'] == '4') { echo 'selected="selected"'; } ?>>4</option>
                <option value="3"<?php if ($rarryDetails['antiwar_win'] == '3') { echo 'selected="selected"'; } ?>>3</option>
                <option value="2"<?php if ($rarryDetails['antiwar_win'] == '2') { echo 'selected="selected"'; } ?>>2</option>
                <option value="1"<?php if ($rarryDetails['antiwar_win'] == '1') { echo 'selected="selected"'; } ?>>1</option>
                <option value="0"<?php if ($rarryDetails['antiwar_win'] == '0') { echo 'selected="selected"'; } ?>>0</option>
                <option value="-1"<?php if ($rarryDetails['antiwar_win'] == '-1') { echo 'selected="selected"'; } ?>>-1</option>
                <option value="-2"<?php if ($rarryDetails['antiwar_win'] == '-2') { echo 'selected="selected"'; } ?>>-2</option>
                <option value="-3"<?php if ($rarryDetails['antiwar_win'] == '-3') { echo 'selected="selected"'; } ?>>-3</option>
                <option value="-4"<?php if ($rarryDetails['antiwar_win'] == '-4') { echo 'selected="selected"'; } ?>>-4</option>
                <option value="-5"<?php if ($rarryDetails['antiwar_win'] == '-5') { echo 'selected="selected"'; } ?>>-5</option>
              </select>
              <?php else : ?>
              <?php echo $antiwar_win; ?>&nbsp;点<input type="hidden" name="antiwar_win" value="<?php echo $antiwar_win; ?>" />
              <?php endif; ?>
              &nbsp;不戦敗：
              <?php if ($sts == 'conf') : ?>
              <select name="antiwar_lose">
                <option value="5"<?php if ($rarryDetails['antiwar_lose'] == '5') { echo 'selected="selected"'; } ?>>5</option>
                <option value="4"<?php if ($rarryDetails['antiwar_lose'] == '4') { echo 'selected="selected"'; } ?>>4</option>
                <option value="3"<?php if ($rarryDetails['antiwar_lose'] == '3') { echo 'selected="selected"'; } ?>>3</option>
                <option value="2"<?php if ($rarryDetails['antiwar_lose'] == '2') { echo 'selected="selected"'; } ?>>2</option>
                <option value="1"<?php if ($rarryDetails['antiwar_lose'] == '1') { echo 'selected="selected"'; } ?>>1</option>
                <option value="0"<?php if ($rarryDetails['antiwar_lose'] == '0') { echo 'selected="selected"'; } ?>>0</option>
                <option value="-1"<?php if ($rarryDetails['antiwar_lose'] == '-1') { echo 'selected="selected"'; } ?>>-1</option>
                <option value="-2"<?php if ($rarryDetails['antiwar_lose'] == '-2') { echo 'selected="selected"'; } ?>>-2</option>
                <option value="-3"<?php if ($rarryDetails['antiwar_lose'] == '-3') { echo 'selected="selected"'; } ?>>-3</option>
                <option value="-4"<?php if ($rarryDetails['antiwar_lose'] == '-4') { echo 'selected="selected"'; } ?>>-4</option>
                <option value="-5"<?php if ($rarryDetails['antiwar_lose'] == '-5') { echo 'selected="selected"'; } ?>>-5</option>
              </select>
              <?php else : ?>
              <?php echo $antiwar_lose; ?>&nbsp;点<input type="hidden" name="antiwar_lose" value="<?php echo $antiwar_lose; ?>" />
              <?php endif; ?>
            </td>
          </tr>
          <tr>
            <th>特殊処理</th>
            <td style="text-align:left;">
              <?php for ($i = 1; $i<= 5; $i++) : ?>
              <div>
              <?php if ($sts == 'conf') : ?>
              勝点：
              <select name="special[<?php echo $i; ?>]">
                <option value=""<?php if ($rarryDetails['special'.$i] == '') { echo 'selected="selected"'; } ?>>&nbsp;</option>
                <option value="5"<?php if ($rarryDetails['special'.$i] == '5') { echo 'selected="selected"'; } ?>>5</option>
                <option value="4"<?php if ($rarryDetails['special'.$i] == '4') { echo 'selected="selected"'; } ?>>4</option>
                <option value="3"<?php if ($rarryDetails['special'.$i] == '3') { echo 'selected="selected"'; } ?>>3</option>
                <option value="2"<?php if ($rarryDetails['special'.$i] == '2') { echo 'selected="selected"'; } ?>>2</option>
                <option value="1"<?php if ($rarryDetails['special'.$i] == '1') { echo 'selected="selected"'; } ?>>1</option>
                <option value="0"<?php if ($rarryDetails['special'.$i] == '0') { echo 'selected="selected"'; } ?>>0</option>
                <option value="-1"<?php if ($rarryDetails['special'.$i] == '-1') { echo 'selected="selected"'; } ?>>-1</option>
                <option value="-2"<?php if ($rarryDetails['special'.$i] == '-2') { echo 'selected="selected"'; } ?>>-2</option>
                <option value="-3"<?php if ($rarryDetails['special'.$i] == '-3') { echo 'selected="selected"'; } ?>>-3</option>
                <option value="-4"<?php if ($rarryDetails['special'.$i] == '-4') { echo 'selected="selected"'; } ?>>-4</option>
                <option value="-5"<?php if ($rarryDetails['special'.$i] == '-5') { echo 'selected="selected"'; } ?>>-5</option>
              </select>
              &nbsp;内容：
              <input type="text" name="special_exp[<?php echo $i; ?>]" size="40" value="<?php echo $rarryDetails['special_exp'.$i]; ?>" /><?php echo $paramError['special_exp'.$i]; ?>
              <?php else : ?>
              勝点：&nbsp;<?php echo $special[$i]; ?>&nbsp;内容：&nbsp;<?php echo $special_exp[$i]; ?>
              <?php endif; ?>
              </div>
              <?php endfor; ?>
            </td>
          </tr>
          <?php elseif ($rarryType == 2) : // トーナメント表示 ?>
          <tr>
            <td colspan="2" class="subdata">トーナメント設定</td>
          </tr>
          <tr>
            <th>同一チーム<br />対戦カード数</th>
            <td style="text-align:left;">
              <?php if ($sts == 'conf') : ?>
              <select name="vs_card">
                <option value="1"<?php if ($rarryDetails['vs_card'] == 1) { echo 'selected="selected"'; } ?>>1回</option>
              </select>
              <?php else : ?>
              <?php echo $vs_card; ?>&nbsp;回
              <?php endif; ?>
            </td>
          </tr>
          <?php endif; ?>
          <?php endif; ?>
          <tr>
            <td colspan="2" style="background-color:#FFFFFF;text-align:center;">
              <?php if ($sts == 'complet') : ?>
              <input type="button" value="戻る" onclick="location.href='./main_rarry.php'" />&nbsp;
              <?php else : ?>
              <input type="submit" value="登録・更新" />&nbsp;
              <?php if ($sts != 'conf') : ?>
              <input type="button" value="やり直す" onclick="returnForm()" />&nbsp;
              <?php endif ;?>
              <?php endif ;?>
            </td>
          </tr>
          </tbody>
        </table>
        <input type="hidden" name="rarryType" value="<?php echo $rarryType; ?>" />
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