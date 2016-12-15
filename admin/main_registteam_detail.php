<?php
////////////////////////////////////////////////////
/*
 * チームマスタデータ追加・編集・削除
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('all', 'style');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array('prototype', 'tablekit/fastinit', 'tablekit/tablekit');

(string)$pageTitle = 'チーム編集画面';
(string)$teamDataChangeScriptName = "main_registteam_change.php";
(string)$rarryDetail = array();
(string)$teamDatas = array();
(string)$teamMemberDatas = array();
(string)$mode = '';
(string)$paymentChangeValue = '';
(string)$pic = array();

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

// 大会情報の取得
if ($rarryDataObj->rarryDetails($_SESSION["rarryId"]) == true) {
    $rarryDetail = $rarryDataObj->getRarryDetail();
}
//print nl2br(print_r($rarryDetail,true));

// 登録チーム情報
if ($teamDataObj->registTeamData($_SESSION["rarryId"], $tid) == true) {
    $teamDatas = $teamDataObj->getTeamDatas();
}
//print nl2br(print_r($teamDatas,true));

if (isset($progress)) {
    $selectProgress = $progress;
} else {
    $selectProgress = $rarryDetail["progress"];
}

// 支払い完了モード
if ($mode != '') {
    if (isset($m_id) AND isset($regist_payment)) {
        if ($memberDataChangeObj->memberRegistPaymentChange($_SESSION["rarryId"], $m_id, $regist_payment) == true) {
            $paymentChangeValue = '支払いフラグ変更完了';
            header("Location: " . $_SERVER['PHP_SELF'] . "?tid=".$tid."&amp;mode=".$mode."&amp;contribute=".$contribute."&amp;sts=".$sts."#members");
        }
    }
}

// チーム登録メンバー
if ($memberDataObj->rarrySeasonMemberList($_SESSION["rarryId"], $selectProgress, $tid) ==true) {
    $teamMemberDatas = $memberDataObj->getMemberDataList();
}
//print nl2br(print_r($teamMemberDatas,true));

$pic['home'] = ($teamDataObj->selectTeamPicture($_SESSION["rarryId"], $tid, 'thumb_home') == true) ? 'change' : 'new' ;
$pic['away'] = ($teamDataObj->selectTeamPicture($_SESSION["rarryId"], $tid, 'thumb_away') == true) ? 'change' : 'new' ;
$pic['other1'] = ($teamDataObj->selectTeamPicture($_SESSION["rarryId"], $tid, 'thumb_other1') == true) ? 'change' : 'new' ;

?>
<?php
// HTMLヘッダ読み込み
include_once "block/header.php"; ?>

<script language="javascript" type="text/javascript">
//<![CDATA[
<!--

  // フォームデータを送信
  function pictureChange(mode, ch) {
    subwin = window.open('', "pictures", "width=850,height=550,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no,location=no,directories=no")
    window.document.fm.mode.value = mode;
    window.document.fm.contribute.value = ch;
    window.document.fm.target = 'pictures';
    window.document.fm.submit();
    subwin.focus();
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
        <?php echo $pageTitle; ?>&nbsp;&#187;&nbsp;チーム詳細
      </div>
      <div class="select-bar">&nbsp;</div>
      <?php echo ($paymentChangeValue) ? '<div style="font-size:16px;font-weight:bold;color:blue;">'.$paymentChangeValue.'</div>' : '' ; ?>
      <div>
        <h3>チーム情報</h3>
        <table class="listing" style="width:100%;font-size:12px;">
          <tbody>
            <tr>
              <th style="width:120px;">チーム名</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamName']; ?></td>
            </tr>
            <tr>
              <th>チーム名カナ</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamKana']; ?></td>
            </tr>
            <tr>
              <th>HPアドレス</th>
              <td style="text-align:left;">
                <?php if ($teamDatas['teamSite'] != '') : ?>
                <a href="http://<?php echo $teamDatas['teamSite']; ?>" target="_blank">http://<?php echo $teamDatas['teamSite']; ?></a>
                <?php else : ?>
                &nbsp;
                <?php endif ; ?>
              </td>
            </tr>
            <tr>
              <th>HOMEカラー</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamHomeColor']; ?></td>
            </tr>
            <tr>
              <th>AWAYカラー</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamAwayColor']; ?></td>
            </tr>
            <tr>
              <th>活動地区</th>
              <td style="text-align:left;">
                <?php echo $teamDatas['teamDistrict']; ?>&nbsp;<?php echo $teamDatas['teamPlace']; ?>
              </td>
            </tr>
            <tr>
              <th>代表名</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamRep']; ?></td>
            </tr>
            <tr>
              <th>代表TEL</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamRepTel']; ?></td>
            </tr>
            <tr>
              <th>代表E-Mail(PC)</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamRepMail']; ?></td>
            </tr>
            <tr>
              <th>代表E-Mail(携帯)</th>
              <td style="text-align:left;"><?php echo ($teamDatas['teamRepMobileAddress']) ? $teamDatas['teamRepMobileAddress'].'@'.$teamDatas['teamRepMobileDomain'] : '' ; ?></td>
            </tr>
            <tr>
              <th>副代表名</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamSubRep']; ?></td>
            </tr>
            <tr>
              <th>副代表TEL</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamSubRepTel']; ?></td>
            </tr>
            <tr>
              <th>副代表E-Mail</th>
              <td style="text-align:left;"><?php echo $teamDatas['teamSubRepMail']; ?></td>
            </tr>
          </tbody>
<!--          <tfoot>-->
<!--            <tr>-->
<!--              <td colspan="2" style="text-align:center;background-color:#ffffff;">-->
<!--                <form action="<?php echo $teamDataChangeScriptName; ?>" method="post">-->
<!--                  <input type="submit" value="&nbsp;チーム情報変更&nbsp;" />-->
<!--                  <input type="hidden" name="tid" value="<?php echo $tid; ?>" />-->
<!--                </form>-->
<!--              </td>-->
<!--            </tr>-->
<!--          </tfoot>-->
        </table>
      </div>
      <div>
        <h3>チーム登録写真</h3>
        <table class="listing">
          <tr>
            <th style="width:240px;">ホーム</th>
            <th style="width:240px;">アウェイ</th>
            <td style="width:240px;background-color:#ffffff;" rowspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td>
              <img src="./main_registteam_picutre.php?tid=<?php echo $tid; ?>&amp;view=thumb_home" />
            </td>
            <td>
              <img src="./main_registteam_picutre.php?tid=<?php echo $tid; ?>&amp;view=thumb_away" />
            </td>
          </tr>
          <tr style="text-align:center;">
            <?php if ($teamDatas["appliesPictureHome"] AND file_exists('../regist/tmp_contribute/'.$_SESSION["rarryId"].'/'.$teamDatas["teamPhoto"].'_home.jpg')) : /* 投稿中の画像チェック */ ?>
            <td style="background-color:#CC0033;"><input type="button" value="画像チェック" onclick="pictureChange('home', 'check')" /></td>
            <?php else : ?>
            <td><input type="button" value="登録・更新" onclick="pictureChange('home', '')" /></td>
            <?php endif; ?>
            <?php if ($teamDatas["appliesPictureAway"] AND file_exists('../regist/tmp_contribute/'.$_SESSION["rarryId"].'/'.$teamDatas["teamPhoto"].'_away.jpg')) : /* 投稿中の画像チェック */ ?>
            <td style="background-color:#CC0033;"><input type="button" value="画像チェック" onclick="pictureChange('away', 'check')" /></td>
            <?php else : ?>
            <td><input type="button" value="登録・更新" onclick="pictureChange('away', '')" /></td>
            <?php endif; ?>
          </tr>
<!--
          <tr>
            <th style="width:240px">その他１</th>
            <th style="width:240px">その他２</th>
            <th style="width:240px">その他３</th>
          </tr>
          <tr>
            <td>
              <img src="./main_registteam_picutre.php?tid=<?php echo $tid; ?>&amp;view=thumb_other1" />
            </td>
            <td>
              <img src="./main_registteam_picutre.php?tid=<?php echo $tid; ?>&amp;view=thumb_other2" />
            </td>
            <td>
              <img src="./main_registteam_picutre.php?tid=<?php echo $tid; ?>&amp;view=thumb_other3" />
            </td>
          </tr>
          <tr style="text-align:center;">
            <?php if (file_exists('../regist/tmp_contribute/'.$teamDatas["teamPhoto"].'_other1.jpg')) : /* 投稿中の画像チェック */ ?>
            <td style="background-color:#CC0033;"><input type="button" value="画像チェック" onclick="pictureChange('other1', 'check')" /></td>
            <?php else : ?>
            <td><input type="button" value="登録・更新" onclick="pictureChange('other1', '')" /></td>
            <?php endif; ?>
            <?php if (file_exists('../regist/tmp_contribute/'.$teamDatas["teamPhoto"].'_other2.jpg')) : /* 投稿中の画像チェック */ ?>
            <td style="background-color:#CC0033;"><input type="button" value="画像チェック" onclick="pictureChange('other2', 'check')" /></td>
            <?php else : ?>
            <td><input type="button" value="登録・更新" onclick="pictureChange('other2', '')" /></td>
            <?php endif; ?>
            <?php if (file_exists('../regist/tmp_contribute/'.$teamDatas["teamPhoto"].'_other3.jpg')) : /* 投稿中の画像チェック */ ?>
            <td style="background-color:#CC0033;"><input type="button" value="画像チェック" onclick="pictureChange('other3', 'check')" /></td>
            <?php else : ?>
            <td><input type="button" value="登録・更新" onclick="pictureChange('other3', '')" /></td>
            <?php endif; ?>
          </tr>
-->
        </table>
      </div>
      <div>
        <h3 id="members">チーム登録選手一覧</h3>
        <table class="sortable resizable" style="font-size:12px;">
          <caption>
            <?php for ($i = 1; $i <= $rarryDetail["progress"]; $i++) : ?>
            <span style="float:left;width:100px;margin:0 4px 4px 0;padding:4px;border:double 4px #9097A9;<?php if ($selectProgress == $i) echo 'background-color:#DDFFAC;'; ?>">
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>?tid=<?php echo $tid; ?>&amp;progress=<?php echo $i; ?>">
                第&nbsp;<?php echo $i; ?>&nbsp;シーズン
              </a>
            </span>
            <?php endfor; ?>
          </caption>
          <thead>
            <tr>
              <th>ID</th>
              <th class="sortfirstasc">No.</th>
              <th>氏名</th>
              <th>カナ</th>
              <th>ポジション</th>
              <th>年齢</th>
              <th>身長</th>
              <th>登録日</th>
              <th>登録費</th>
            </tr>
            </thead>
          <tbody>
            <?php for ($i = 0; $i < count($teamMemberDatas); $i++) : ?>
            <?php $teamNumber = (isset($teamMemberDatas[$i]['number']) and $teamMemberDatas[$i]['number'] != '') ? $teamMemberDatas[$i]['number'] : '--' ?>
            <tr<?php echo ($rarryDetail['type'] != 2 AND !$teamMemberDatas[$i]['registPayment']) ? ' style="background:#cc0033;"' : '' ; ?>>
              <td><?php echo $teamMemberDatas[$i]['nemberId']; ?></td>
              <td style="text-align:center;"><?php echo $teamNumber; ?></td>
              <td><?php echo $teamMemberDatas[$i]['nameFirst']; ?>&nbsp;<?php echo $teamMemberDatas[$i]['nameSecond']; ?></td>
              <td><?php echo $teamMemberDatas[$i]['kanaFirst']; ?>&nbsp;<?php echo $teamMemberDatas[$i]['kanaSecond']; ?></td>
              <td><?php echo $teamMemberDatas[$i]['posision']; ?></td>
              <td style="text-align:center;"><?php echo $teamMemberDatas[$i]['age']; ?>&nbsp;才</td>
              <td style="text-align:center;"><?php echo $teamMemberDatas[$i]['height']; ?>&nbsp;cm</td>
              <td><?php echo $teamMemberDatas[$i]['created']; ?></td>
              <td>
                <?php if ($rarryDetail['type'] == 2) : ?>
                &nbsp;
                <?php else : ?>
                <form method="post" action="main_registteam_detail.php">
                <input type="hidden" name="mode" value="payment" />
                <input type="hidden" name="tid" value="<?php echo $tid; ?>" />
                <input type="hidden" name="m_id" value="<?php echo $teamMemberDatas[$i]['nemberId']; ?>" />

                <?php if (!$teamMemberDatas[$i]['registPayment']) : ?>
                <input type="hidden" name="regist_payment" value="1" />
                <input type="submit" value="OK" />
                <?php else : ?>
                <input type="hidden" name="regist_payment" value="0" />
                <input type="submit" value="NG" />
                <?php endif; ?>
                <?php endif; ?>
              </form>
              </td>
            </tr>
            <?php endfor ; ?>
          </tbody>
        </table>
      </div>
    </div><?php echo "\n"/* END center-column */ ?>
  </div><?php echo "\n"/* END middle */ ?>
  <div id="footer"></div>
</div><?php echo "\n"/* END main */ ?>

<form name="fm" method="post" action="main_registteam_edit_picture.php#members">
  <input type="hidden" name="tid" value="<?php echo $tid; ?>" />
  <input type="hidden" name="mode" value="" />
  <input type="hidden" name="contribute" value="" />
  <input type="hidden" name="sts" value="" />
</form>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>