<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja-JP" lang="ja-JP">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="keywords" content="参加チーム,バスケ,バスケット,バスケットボール,愛知,名古屋,尾張,春日井,小牧,東海,リーガ,リーガ東海,バスケットリーグ,バスケリーグ,リーグ,トーナメント" />
<meta name="description" content="アマチュアバスケットボールリーグ[リーガ東海]の参加チームです" />
<meta name="author" content="今井厚文" />
<meta name="generator" content="php_editor" />

<link rel="stylesheet" href="./css/base.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/team.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/member.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/hiside_image.css" type="text/css" media="screen" />
<link rel="contents" href="../index.htm" />
<link rev="made" href="mailto:web@liga-tokai.com" />

<script type="text/javascript" src="../js/highslide/highslide.js"></script>
<script type="text/javascript" src="../js/prototype.js"></script>
<script type="text/javascript" src="../js/popup.js"></script>

<!-- 画像処理 -->
{literal}
<script language="javascript" type="text/javascript">
//<![CDATA[
<!--
  //hs.graphicsDir = '../js/highslide/graphics/';
  //hs.outlineType = 'rounded-white';
  //window.onload = function() {
  //  hs.preloadImages();
  //}
  // SUBMIT処理
  function sendPages(mode, sts) {
    document.fmteamdata.mode.value = mode;
    document.fmteamdata.sts.value = sts;
    document.fmteamdata.submit();
  }

  // データ削除の確認アラート
  function changeConf(mode){
    var modeView = 'チーム情報';
    myReturn = confirm(modeView+"を変更しますがよろしいですか？");

    if ( myReturn == true ) {
      document.fmteamdata.mode.value = mode;
      document.fmteamdata.sts.value = 'comp';
      document.fmteamdata.submit();
    }
  }
  // 戻りボタン処理
  function pageBack(mode) {
    if (mode == "input") {
      document.fmback.mode.value = '';
      document.fmback.submit();
    } else if (mode == "conf") {
      document.fmteams.action = "./regist.php";
      document.fmteams.mode.value = mode;
      document.fmteams.sts.value = 'input';
      document.fmteams.submit();
    } else if (mode == "comp") {
      document.fmteams.action = "./message.php";
      document.fmteams.mode.value = '';
      document.fmteams.submit();
    }
  }

  // 大会登録年度の選手データ切り替え
  function changeRarry(rid, season) {
    document.fmrarry.rarryId.value = rid;
    document.fmrarry.season.value = season;
    //document.fmrarry.sts.value = sts;
    document.fmrarry.submit();
  }
  // 前大会登録選手データ取り込み
  function memberTaking() {
    myReturn = confirm("チェックした選手を今シーズン選手に取り込みますがよろしいですか？");
    if ( myReturn == true ) {
      document.membertaking.submit();
    }
  }

//-->
//]]>
</script>

<style type="text/css">
/*<![CDATA[*/
<!--
  #team table {
    width:100%;border: 1px #E3E3E3 solid;border-collapse: collapse;border-spacing: 0;
  }
  #team th {
    padding-left:10px;border:1px #E3E3E3 solid;white-space: nowrap;text-align:left;font-weight:nomal;
  }
  #team td {
    width:100%;padding-left:10px;border:1px #E3E3E3 solid;white-space: nowrap;text-align:left;
  }

  #teamfm table {
    border: 1px #E3E3E3 solid;border-collapse: collapse;border-spacing: 0;
  }
  #teamfm th {
    padding-left:10px;border:1px #E3E3E3 solid;white-space: nowrap;text-align:left;
  }
  #teamfm td {
    width:100%;padding-left:10px;border:1px #E3E3E3 solid;white-space: nowrap;text-align:left;
  }
  select .black { background:#000000;color:#FFFFFF; }
  select .gray { background:#808080; }
  select .silver { background:#C0C0C0; }
  select .white { background:#FFFFFFE; }
  select .maroon { background:#800000;color:#FFFFFF; }
  select .red { background:#FF0000;color:#FFFFFF; }
  select .purple { background:#800080;color:#FFFFFF; }
  select .pink { background:#FF00FF; }
  select .green { background:#008000;color:#FFFFFF; }
  select .lime { background:#00FF00; }
  select .olive { background:#808000;color:#FFFFFF; }
  select .yellow { background:#FFFF00; }
  select .navy { background:#000080;color:#FFFFFF; }
  select .blue { background:#0000FF;color:#FFFFFF; }
  select .teal { background:#008080;color:#FFFFFF; }
  select .aqua { background:#00FFFF; }
  select .orange { background:#FF9900; }

  #member table {
    width:100%;border: 1px #E3E3E3 solid;border-collapse: collapse;border-spacing: 0;
  }
  #member th {
    border:1px #E3E3E3 solid;white-space: nowrap;text-align:canter;font-weight:nomal;background-color:#D1DFA8;
  }
  #member td {
    padding:1px 10px;border:1px #E3E3E3 solid;background-color:#F4F1EC;
  }

  #memberfm table {
    width:100%;border: 1px #E3E3E3 solid;border-collapse: collapse;border-spacing: 0;
  }
  #memberfm th {
    border:1px #E3E3E3 solid;white-space: nowrap;text-align:canter;font-weight:nomal;background-color:#80A6E8;
  }
  #memberfm td {
    padding:1px 10px;border:1px #E3E3E3 solid;background-color:#F4F1EC;
  }
-->
/*]]>*/
</style>
{/literal}

<title>リーガ東海　チーム情報登録変更</title>
</head>

<body>

<div style="text-align:center;">
  <div style="width:700px;text-align:right;"><a href="http://liga-tokai.com/">リーガ東海HOME</a>&nbsp;|&nbsp;<a href="./login.php">ログアウト</a></div>
  <div class="teamname">リーガ東海 2009SEASON LEAGUE {$teamName}</div>
  <div style="width:700px;">
  <div class="bar">チーム情報</div>
  <div>
{$teamDataChangeModeValue}
    <div style="width:100%;">
      <form name="fmteams" method="post" action="teamInfo.php{$fmAction}">
{** チーム情報変更モード **}
{if $mode == "teamChange"}
      <table id="teamfm">
        <thead>
        <tr>
          <td colspan="2" style="padding:7px 30px;background-color:#BAD1FC;text-align:center;font-size:16px;font-weight:bold;">チーム情報修正フォーム [ {$teamChangeStsValue} ]</td>
        </tr>
        </thead>
        <tbody>
        <tr>
          <th>リーグクラス</th><td>{$rarryClass}</td>
        </tr>
        <tr>
          <th>チーム名</th>
          <td><input type="text" size="50" maxlength="50" name="teamName" value="{$teamDatas.teamName}" />{$errorValue.teamName}</td>
        </tr>
        <tr>
          <th>チーム名(カナ)</th>
          <td><input type="text" size="50" maxlength="50" name="teamKana" value="{$teamDatas.teamKana}" />(カタカナのみ){$errorValue.teamKana}</td>
        </tr>
        <tr>
          <th>代表者名</th><td><input type="text" size="30" maxlength="30" name="teamRep" value="{$teamDatas.teamRep}" />{$errorValue.teamRep}</td>
        </tr>
        <tr>
          <th>代表者携帯番号</th><td><input type="text" size="30" maxlength="30" name="teamRepTel" value="{$teamDatas.teamRepTel}" />(半角数字のみ){$errorValue.teamRepTel}</td>
        </tr>
        <tr>
          <th>代表者メールアドレス</th><td><input type="text" size="50" maxlength="30" name="teamRepMail" value="{$teamDatas.teamRepMail}" />(半角英数字のみ){$errorValue.teamRepMail}</td>
        </tr>
        <tr>
          <th>副代表者名</th><td><input type="text" size="30" maxlength="30" name="teamSubRep" value="{$teamDatas.teamSubRep}" />{$errorValue.teamSubRep}</td>
        </tr>
        <tr>
          <th>副代表者携帯番号</th><td><input type="text" size="30" maxlength="30" name="teamSubRepTel" value="{$teamDatas.teamSubRepTel}" />(半角数字のみ){$errorValue.teamSubRepTel}</td>
        </tr>
        <tr>
          <th>副代表者メールアドレス</th><td><input type="text" size="50" maxlength="30" name="teamSubRepMail" value="{$teamDatas.teamSubRepMail}" />(半角英数字のみ){$errorValue.teamSubRepMail}</td>
        </tr>
        <tr>
          <th>主な活動場所</th>
          <td>
{$teamDatas.teamDistrict}
&nbsp;<input type="text" size="30" maxlength="30" name="teamPlace" value="{$teamDatas.teamPlace}" />{$errorValue.teamDistrict}{$errorValue.teamPlace}
          </td>
        </tr>
        <tr>
          <th>ホームカラー</th>
          <td>
{$teamDatas.teamHomeColor}{$errorValue.teamHomeColor}
          </td>
        </tr>
        <tr>
          <th>アウェイカラー</th>
          <td>
{$teamDatas.teamAwayColor}{$errorValue.teamAwayColor}
          </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td colspan="2" style="text-align:center;">
{$fmButtonValue}
          </td>
        </tr>
        </tfoot>
        <input type="hidden" name="mode" value="{$modeValue}" />
        <input type="hidden" name="sts" value="{$sts}" />
      </table>
{** チーム情報表示・変更確認・変更完了モード **}
{else}
      <table id="team">
        {if $mode == "teamChangeConf"}
        <thead>
        <tr>
          <td colspan="2" style="padding:7px 30px;background-color:#BAD1FC;text-align:center;font-size:16px;font-weight:bold;">チーム情報修正フォーム [ {$teamChangeStsValue} ]</td>
        </tr>
        </thead>
        {/if}
        <tbody>
        <tr>
          <th>リーグクラス</th><td>{$rarryClass}</td>
        </tr>
        <tr>
          <th>チーム名</th><td>{$teamDatas.teamName}&nbsp;</td>
        </tr>
        <tr>
          <th>チーム名(カナ)</th><td>{$teamDatas.teamKana}&nbsp;</td>
        </tr>
        <tr>
          <th>代表者氏名</th><td>{$teamDatas.teamRep}&nbsp;</td>
        </tr>
        <tr>
          <th>代表者携帯番号</th><td>{$teamDatas.teamRepTel}&nbsp;</td>
        </tr>
        <tr>
          <th>代表者メールアドレス</th><td>{$teamDatas.teamRepMail}&nbsp;</td>
        </tr>
        <tr>
          <th>副代表者氏名</th><td>{$teamDatas.teamSubRep}&nbsp;</td>
        </tr>
        <tr>
          <th>副代表者携帯番号</th><td>{$teamDatas.teamSubRepTel}&nbsp;</td>
        </tr>
        <tr>
          <th>副代表者メールアドレス</th><td>{$teamDatas.teamSubRepMail}&nbsp;</td>
        </tr>
        <tr>
          <th>活動場所</th><td>{$teamDatas.teamDistrict}&nbsp;{$teamDatas.teamPlace}</td>
        </tr>
        <tr>
          <th>ホームカラー</th><td>{$teamDatas.teamHomeColor}&nbsp;</td>
        </tr>
        <tr>
          <th>アウェイカラー</th><td>{$teamDatas.teamAwayColor}&nbsp;</td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
          <td colspan="2" style="text-align:center;">
{$fmButtonValue}
          </td>
        </tr>
        </tfoot>
        <input type="hidden" name="mode" value="{$modeValue}" />
        <input type="hidden" name="sts" value="{$sts}" />
      </table>
{/if}
      </form>
    </div>
    <div class='highslide-caption' id='caption1'>
      【&nbsp;TEAM&nbsp;{$teamName}&nbsp;】
    </div>
  </div>

{* メンバー表示欄 *}
{include file='./memberData.tpl'}

</div>
</div>

<hr class="example" />

  <form name="fmteamdata" method="post" action="">
    <input type="hidden" name="teamName" value="{$formDatas.teamName}" />
    <input type="hidden" name="teamKana" value="{$formDatas.teamKana}" />
    <input type="hidden" name="teamRep" value="{$formDatas.teamRep}" />
    <input type="hidden" name="teamRepTel" value="{$formDatas.teamRepTel}" />
    <input type="hidden" name="teamRepMail" value="{$formDatas.teamRepMail}" />
    <input type="hidden" name="teamSubRep" value="{$formDatas.teamSubRep}" />
    <input type="hidden" name="teamSubRepTel" value="{$formDatas.teamSubRepTel}" />
    <input type="hidden" name="teamSubRepMail" value="{$formDatas.teamSubRepMail}" />
    <input type="hidden" name="teamDistrict" value="{$formDatas.teamDistrict}" />
    <input type="hidden" name="teamPlace" value="{$formDatas.teamPlace}" />
    <input type="hidden" name="teamHomeColor" value="{$formDatas.teamHomeColor}" />
    <input type="hidden" name="teamAwayColor" value="{$formDatas.teamAwayColor}" />
    <input type="hidden" name="mode" value="{$mode}" />
    <input type="hidden" name="sts" value="{$sts}" />
  </form>
  <form name="fmrarry" method="post" action="">
    <input type="hidden" name="rarryId" value="" />
    <input type="hidden" name="season" value="" />
  </form>
  <form name="fmback" method="post" action="">
    <input type="hidden" name="mode" value="" />
    <input type="hidden" name="sts" value="" />
  </form>

{* フッタ *}
{include file='./blockTmp/footer.tpl'}
