<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja-JP" lang="ja-JP">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="author" content="今井厚文" />
<meta name="generator" content="php_editor" />

<link rel="stylesheet" href="./css/base.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/hiside_image.css" type="text/css" media="screen" />
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
    //myReturn = confirm(modeView+"を変更しますがよろしいですか？");

    //if ( myReturn == true ) {
      document.fmteamdata.mode.value = mode;
      document.fmteamdata.sts.value = 'comp';
      document.fmteamdata.submit();
    //}
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
  // チーム登録選手削除の確認アラート
  function deleteMember(mode, mid){
    if (mode == "removeMember") {
        myReturn = confirm("{/literal}{$preRarryDataArray.rarry_sub_name}{literal}登録選手から抹消しますがよろしいですか？");
    } else if (mode == "dischargeMember") {
        myReturn = confirm("選手を放出しますがよろしいですか？\n注：申請後は取り消し出来ませんのでご注意ください");
    }

    if ( myReturn == true ) {
      document.fmmember.action = './teamInfo.php#registmember';
      document.fmmember.mode.value = mode;
      document.fmmember.memberId.value = mid;
      document.fmmember.submit();
    }
  }
  // チーム選手登録完了の確認アラート
  function memberRegistComplete(mode){
    myReturn = confirm("選手登録を完了しますがよろしいですか？\n注：申請後はメンバー登録(新規登録・移籍)が出来ませんのでご注意ください");
    if ( myReturn == true ) {
      document.fmmember.action = './teamInfo_test.php#registmember';
      document.fmmember.mode.value = mode;
      document.fmmember.submit();
    }
  }
  // 選手情報登録・修正フォーム表示
  function memberNewAndTransfer(page, mode) {
    document.fmback.action = "./" + page + ".php";
    document.fmback.mode.value = mode;
    document.fmback.submit();
  }
  // 選手情報修正フォーム表示
  function memberDetailChangeWindow(mid) {
    subwin = window.open('', "regist", "width=500,height=500,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no,location=no,directories=no")
    window.document.fmmember.action = "./memberChange.php";
    window.document.fmmember.target = "regist" ;
    window.document.fmmember.memberId.value = mid;
    window.document.fmmember.submit();
    subwin.focus();
  }
  // チーム写真登録画面を表示する
  function pictureChange(rid, tid, mode) {
    subwin = window.open('', "pictures", "width=900,height=550,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no,location=no,directories=no")
    window.document.fmpicture.action = "./team_edit_picture.php";
    window.document.fmpicture.rarryId.value = rid;
    window.document.fmpicture.tid.value = tid;
    window.document.fmpicture.mode.value = mode;
    window.document.fmpicture.target = 'pictures';
    window.document.fmpicture.submit();
    subwin.focus();
  }

//-->
//]]>
</script>

<style type="text/css">
/*<![CDATA[*/
<!--
  .imezen { ime-mode: active; }
  .imehan1 { ime-mode: disabled; }
  .imehan2 { ime-mode: inactive; }
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
  select .light-red { background:#ff0000;color:#ｆｆ0066; }
  select .purple { background:#800080;color:#FFFFFF; }
  select .light-purple { background:#9999ff; }
  select .pink { background:#FF99FF; }
  select .green { background:#008000;color:#FFFFFF; }
  select .light-green { background:#99cc00; }
  select .lime { background:#00FF00; }
  select .olive { background:#808000;color:#FFFFFF; }
  select .yellow { background:#FFFF00; }
  select .light-yellow { background:#FFFF99; }
  select .navy { background:#000080;color:#FFFFFF; }
  select .blue { background:#0000FF;color:#FFFFFF; }
  select .light-blue { background:#3366ff; }
  select .teal { background:#008080;color:#FFFFFF; }
  select .aqua { background:#ccffff; }
  select .orange { background:#FF9900; }
  select .gold { background:#FFD700; }
/*
  #member table {
    width:100%;border: 0px #E3E3E3 solid;border-collapse: collapse;border-spacing: 0;
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
*/
-->
/*]]>*/
</style>
{/literal}


<title>リーガ東海　チーム情報登録変更[TEST]</title>
</head>

<body>

<div id="mainContents">
  <div id="headerArea">
{if $adminMode}
    <a href="teamInfo_test.php">画面更新</a>&nbsp;|&nbsp;<a href="http://liga-tokai.com/">リーガ東海HOME</a>&nbsp;|&nbsp;<a href="./login_admin.php?loginAdminMode=samurai21">ログアウト</a>
{else}
    <a href="teamInfo_test.php">画面更新</a>&nbsp;|&nbsp;<a href="http://liga-tokai.com/">リーガ東海HOME</a>&nbsp;|&nbsp;<a href="./login.php">ログアウト</a>
{/if}
  </div>
  <div id="titleArea">{$rarryDataArray.rarry_name}&nbsp;{$rarryDataArray.rarry_sub_name}&nbsp;{$subName}</div>
  <div id="teamDataArea">
    <h1>チーム情報</h1>
{$teamDataChangeModeValue}
    <form name="fmteams" method="post" action="teamInfo.php{$fmAction}">
{** チーム情報変更モード **}
{if $mode == "teamChange"}
    <table id="teamDataTable">
      <thead>
      <tr>
        <td colspan="2" style="padding:7px 30px;background-color:#BAD1FC;text-align:center;font-size:16px;font-weight:bold;">チーム情報修正フォーム [ {$teamChangeStsValue} ]</td>
      </tr>
      </thead>
      <tbody>
{if $rarryDataArray.RARRY_TYPE == 1}
      <tr>
        <th>リーグクラス</th><td>{$rarryClass}</td>
      </tr>
{/if}
      <tr>
        <th>チーム名</th>
        <td>
          {$teamDatas.teamName|replace:"amp;":""}&nbsp;{$errorValue.teamName}
          <input type="hidden" name="teamName" value="{$teamDatas.teamName|replace:"amp;":""}" />
        </td>
<!--
        <td><input type="text" size="50" maxlength="50" name="teamName" value="{$teamDatas.teamName|replace:"amp;":""}" id="imezen" />{$errorValue.teamName}</td>
-->
      </tr>
      <tr>
        <th>チーム名(カナ)</th>
<!--
        <td>
          {$teamDatas.teamKana|replace:"amp;":""}&nbsp;{$errorValue.teamKana}
          <input type="hidden" name="teamKana" value="{$teamDatas.teamKana|replace:"amp;":""}" />
        </td>
-->
        <td><input type="text" size="50" maxlength="50" name="teamKana" value="{$teamDatas.teamKana|replace:"amp;":""}" class="imezen" />(カタカナのみ){$errorValue.teamKana}</td>
      </tr>
      <tr>
        <th>代表者名</th><td><input type="text" size="30" maxlength="30" name="teamRep" value="{$teamDatas.teamRep|replace:"amp;":""}" class="imezen" />{$errorValue.teamRep}</td>
      </tr>
      <tr>
        <th>代表者携帯番号</th><td><input type="text" size="30" maxlength="30" name="teamRepTel" value="{$teamDatas.teamRepTel}" class="imehan1" />(半角数字のみ){$errorValue.teamRepTel}</td>
      </tr>
      <tr>
        <th>代表者メールアドレス(PC)</th><td><input type="text" size="50" maxlength="100" name="teamRepMail" value="{$teamDatas.teamRepMail}" class="imehan1" />(半角英数字のみ){$errorValue.teamRepMail}</td>
      </tr>
      <tr>
        <th>代表者メールアドレス(携帯)</th>
        <td>
          <input type="text" size="50" maxlength="100" name="teamRepMobileAddress" value="{$teamDatas.teamRepMobileAddress}" class="imehan1" />(半角英数字のみ)
          &nbsp;@&nbsp;
          {$teamDatas.teamRepMobileDomain}{$errorValue.teamRepMobileAddress}{$errorValue.teamRepMobileDomain}
        </td>
      </tr>
      <tr>
        <th>副代表者名</th><td><input type="text" size="30" maxlength="30" name="teamSubRep" value="{$teamDatas.teamSubRep|replace:"amp;":""}" class="imezen" />{$errorValue.teamSubRep}</td>
      </tr>
      <tr>
        <th>副代表者携帯番号</th><td><input type="text" size="30" maxlength="30" name="teamSubRepTel" value="{$teamDatas.teamSubRepTel}" class="imehan1" />(半角数字のみ){$errorValue.teamSubRepTel}</td>
      </tr>
      <tr>
        <th>副代表者メールアドレス</th><td><input type="text" size="50" maxlength="100" name="teamSubRepMail" value="{$teamDatas.teamSubRepMail}" class="imehan1" />(半角英数字のみ){$errorValue.teamSubRepMail}</td>
      </tr>
      <tr>
        <th>主な活動場所</th>
        <td>
{$teamDatas.teamDistrict}
&nbsp;<input type="text" size="30" maxlength="30" name="teamPlace" value="{$teamDatas.teamPlace}" id="imezen" />{$errorValue.teamDistrict|replace:"amp;":""}{$errorValue.teamPlace}
        </td>
      </tr>
      <tr>
        <th>ホームカラー</th>
        <td>{$teamDatas.teamHomeColor}{$errorValue.teamHomeColor}</td>
      </tr>
      <tr>
        <th>アウェイカラー</th>
        <td>{$teamDatas.teamAwayColor}{$errorValue.teamAwayColor}</td>
      </tr>
      <tr>
        <td colspan="2" style="color:#ff0000">
          ・代表並びに副代表者のメールアドレスは「<strong>{$ligaMail}</strong>」からのメールが届くようにして下さい。<br />
          ・携帯電話等の迷惑メール防止でドメイン指定受信をされている場合は必ず上記メールを受信可能設定にして下さい。
        </td>
      </tr>
      </tbody>
      <tfoot>
      <tr>
        <td colspan="2" style="text-align:center;">{$fmButtonValue}</td>
      </tr>
      </tfoot>
    </table>
    <input type="hidden" name="mode" value="{$modeValue}" />
    <input type="hidden" name="sts" value="{$sts}" />
    </form>
  </div>
{** チーム情報表示・変更確認・変更完了モード **}
{else}
    <table id="teamDataTable">
      {if $mode == "teamChangeConf"}
      <thead>
      <tr>
        <td colspan="2" style="padding:7px 30px;background-color:#BAD1FC;text-align:center;font-size:16px;font-weight:bold;">チーム情報修正フォーム [ {$teamChangeStsValue} ]</td>
      </tr>
      </thead>
      {/if}
      <tbody>
{if $rarryDataArray.RARRY_TYPE == 1}
      <tr>
        <th>リーグクラス</th><td>{$rarryClass}</td>
      </tr>
{/if}
      <tr>
        <th>チーム名</th><td>{$teamDatas.teamName|replace:"amp;":""}&nbsp;</td>
      </tr>
      <tr>
        <th>チーム名(カナ)</th>
        {if $teamDatas.teamKana ne '未登録'}
        <td>{$teamDatas.teamKana}&nbsp;</td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <th>代表者氏名</th>
        {if $teamDatas.teamRep ne '未登録'}
        <td>{$teamDatas.teamRep|replace:"amp;":""}&nbsp;</td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <th>代表者携帯番号</th>
        {if $teamDatas.teamRepTel ne '未登録'}
        <td>{$teamDatas.teamRepTel}&nbsp;</td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <th>代表者メールアドレス(PC)</th>
        {if $teamDatas.teamRepMail ne '未登録'}
        <td>{$teamDatas.teamRepMail}&nbsp;</td>
        {elseif $teamDatas.noMobile}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;">※&nbsp;登録してください。(任意)</span></td>
        {/if}
      </tr>
      <tr>
        <th>代表者メールアドレス(携帯)</th>
        {if $teamDatas.teamRepMobileAddress ne '未登録'}
        <td>{$teamDatas.teamRepMobileAddress}@{$teamDatas.teamRepMobileDomain}&nbsp;</td>
        {elseif $teamDatas.noMobile}
        <td>&nbsp;</td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <th>副代表者氏名</th>
        {if $teamDatas.teamSubRep ne '未登録'}
        <td>{$teamDatas.teamSubRep|replace:"amp;":""}&nbsp;</td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <th>副代表者携帯番号</th>
        {if $teamDatas.teamSubRepTel ne '未登録'}
        <td>{$teamDatas.teamSubRepTel}&nbsp;</td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <th>副代表者メールアドレス</th>
        {if $teamDatas.teamSubRepMail ne '未登録'}
        <td>{$teamDatas.teamSubRepMail}&nbsp;</td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <th>活動場所</th>
        {if $teamDatas.teamDistrict ne '未登録'}
        <td>{$teamDatas.teamDistrict}&nbsp;{$teamDatas.teamPlace|replace:"amp;":""}</td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <th>ホームカラー</th>
        {if $teamDatas.teamHomeColor ne '未登録'}
        <td>
{if $sts == 'conf'}
        	<span style="background-color:{$formDatas.teamHomeColor};border:solid 1px #000;">　　</span>
{else}
        	<span style="background-color:{$teamDatas.teamHomeColor};border:solid 1px #000;">　　</span>
{/if}
        	{$teamDatas.teamHomeColorView}&nbsp;({$teamDatas.teamHomeColor})
        </td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <th>アウェイカラー</th>
        {if $teamDatas.teamAwayColor ne '未登録'}
        <td>
{if $sts == 'conf'}
        	<span style="background-color:{$formDatas.teamAwayColor};border:solid 1px #fff;">　　</span>
{else}
        	<span style="background-color:{$teamDatas.teamAwayColor};border:solid 1px #fff;">　　</span>
{/if}
        	{$teamDatas.teamAwayColorView}&nbsp;({$teamDatas.teamAwayColor})
        </td>
        {else}
        <td><span style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">※&nbsp;登録してください。</span></td>
        {/if}
      </tr>
      <tr>
        <td colspan="2" style="color:#ff0000">
          ・代表並びに副代表者のメールアドレスは「<strong>{$ligaMail}</strong>」からのメールが届くようにして下さい。<br />
          ・携帯電話等の迷惑メール防止でドメイン指定受信をされている場合は必ず上記メールを受信可能設定にして下さい。
        </td>
      </tr>
      </tbody>
      <tfoot>
      <tr>
        <td colspan="2" style="text-align:center;">
          {$fmButtonValue}
          <input type="hidden" name="mode" value="{$modeValue}" />
          <input type="hidden" name="sts" value="{$sts}" />
        </td>
      </tr>
      </tfoot>
    </table>
    </form>
  </div>
{if $sts != 'conf'}
  <div>
    <h1>チーム登録写真</h1>
    <table id="teamImageTable">
      <tr>
        <th style="width:240px">ホーム側</th>
        <th style="width:240px">アウェイ側</th>
      </tr>
      <tr>
        <td>
          {if $appliesPictureHome}
          <div style="color:blue;font-weight:bold;">投稿画像申請中です</div>
          {/if}
          {if $teamDatas.teamPlace != '未登録'}
          <img src="./team_picutre.php?rarryId={$rarryId}&amp;teamId={$teamId}&amp;view=thumb_home" />
          {/if}
        </td>
        <td>
          {if $appliesPictureAway}
          <div style="color:blue;font-weight:bold;">投稿画像申請中です</div>
          {/if}
          {if $teamDatas.teamPlace != '未登録'}
          <img src="./team_picutre.php?rarryId={$rarryId}&amp;teamId={$teamId}&amp;view=thumb_away" />
          {/if}
        </td>
      </tr>
      <tr style="text-align:center;">
        <td><input type="button" value="登録・更新" onclick="pictureChange({$rarryId}, {$teamId}, 'home')" /></td>
        <td><input type="button" value="登録・更新" onclick="pictureChange({$rarryId}, {$teamId}, 'away')" /></td>
      </tr>
    </table>
  </div>
{/if}
  <div>&nbsp;</div>
{/if}
<!--    <div class='highslide-caption' id='caption1'>-->
<!--      【&nbsp;TEAM&nbsp;{$teamName}&nbsp;】-->
<!--    </div>-->

{if $mode == "" OR $mode == "memberAhead" OR $mode == "teamChangeComp" OR $mode == "dischargeMember" OR $mode == "removeMember" OR $mode == "memberRegistComplete"}
{* メンバー表示欄
 * memberData.tpl     : シーズン更新時
 * memberDataSub.tpl  : 中間登録時
 *}
{include file='./memberData.tpl'}
{/if}
</div>

<!--<hr class="example" />-->

  <form name="fmteamdata" method="post" action="">
    <input type="hidden" name="teamName" value="{$formDatas.teamName}" />
    <input type="hidden" name="teamKana" value="{$formDatas.teamKana}" />
    <input type="hidden" name="teamRep" value="{$formDatas.teamRep}" />
    <input type="hidden" name="teamRepTel" value="{$formDatas.teamRepTel}" />
    <input type="hidden" name="teamRepMail" value="{$formDatas.teamRepMail}" />
    <input type="hidden" name="teamRepMobileAddress" value="{$formDatas.teamRepMobileAddress}" />
    <input type="hidden" name="teamRepMobileDomain" value="{$formDatas.teamRepMobileDomain}" />
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
  <form name="fmmember" method="post" action="">
    <input type="hidden" name="mode" value="" />
    <input type="hidden" name="memberId" value="" />
  </form>

  <form name="fmpicture" method="post" action="">
    <input type="hidden" name="rarryId" value="" />
    <input type="hidden" name="tid" value="" />
    <input type="hidden" name="mode" value="" />
    <input type="hidden" name="sts" value="" />
  </form>

{* フッタ *}
{include file='./blockTmp/footer.tpl'}
