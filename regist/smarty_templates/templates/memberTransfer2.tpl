<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja-JP" lang="ja-JP">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="author" content="今井厚文" />
<meta name="generator" content="php_editor" />

<link rel="contents" href="../index.htm" />
<link rev="made" href="mailto:web@liga-tokai.com" />
<link rel="stylesheet" href="./css/base.css" type="text/css" media="screen" />

{literal}
<script language="javascript" type="text/javascript">
//<![CDATA[
<!--

  // 戻りボタン処理
  function pageBack(mode) {
    if (mode == "back") {
      document.fmback.action = "./teamInfo.php";
      document.fmback.mode.value = '';
      document.fmback.submit();
    }
  }
  // 選手登録
  function teamPlayerRegist(mode, status, mid, name) {
    myReturn = confirm(name + "さんを{/literal}{$rarryDataArray.rarry_sub_name}{literal}選手に取り込みますがよろしいですか？");
    if ( myReturn == true ) {
      document.fmmemberregist.memberId.value = mid;
      document.fmmemberregist.mode.value = mode;
      document.fmmemberregist.sts.value = status;
      document.fmmemberregist.submit();
    }
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

<title>{$rarryDataArray.rarry_name}&nbsp;{$rarryDataArray.rarry_sub_name}&nbsp;選手登録・変更</title>
</head>

<body>

<div id="mainContents">
  <div id="headerArea"><a href="http://liga-tokai.com/">リーガ東海HOME</a>&nbsp;|&nbsp;<a href="./login.php">ログアウト</a></div>
  <div id="titleArea">{$rarryDataArray.rarry_name}&nbsp;{$rarryDataArray.rarry_sub_name}&nbsp;{$subName}</div>

  <div id="teamDataArea">

    <div style="margin-top:10px;"><input type=button value="&nbsp;チーム編集画面へ戻る&nbsp;" onclick="pageBack('back')" /></div>

	<div style="width:730px;margin:auto;">
    <form name="membertaking" method="post" action="memberTransfer2.php">
    <fieldset style="margin:auto;">
    <legend>[&nbsp;チーム未登録・移籍可能&nbsp;]&nbsp;選手検索フォーム</legend>
    <!--div style="margin:3px 0;text-align:left;">※&nbsp;移籍選手は「放出」登録をしないと移籍先チームに登録出来ませんのでよろしくお願いいたします。</div-->
    <table id="memberfm" align="center">
      <thead>
      <tr>
        <th style="">選手状態</th>
        <th style="">名前</th>
        <th style="">読みカナ</th>
        <th style="">チーム選択</th>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td style="">
          <select name="state">
            <option value="1"{if $state == 1} selected="selected"{/if}>問わず</option>
            <option value="2"{if $state == 2} selected="selected"{/if}>移籍可能</option>
<!--
            <option value="3"{if $state == 3} selected="selected"{/if}>未放出選手</option>
            <option value="3"{if $state == 3} selected="selected"{/if}>前チーム保留中</option>
-->
          </select>
        </td>
        <td style="">
          <span style="font-size:10px;">姓</span><input type="text" size="8" maxlength="16" name="name_f_retrieval" value="{$name_f_retrieval}" class="imezen" />&nbsp;
          <span style="font-size:10px;">名</span><input type="text" size="8" maxlength="16" name="name_s_retrieval" value="{$name_s_retrieval}" class="imezen" />
        </td>
        <td style="">
          <span style="font-size:10px;">姓</span><input type="text" size="8" maxlength="16" name="kana_f_retrieval" value="{$kana_f_retrieval}" class="imezen" />&nbsp;
          <span style="font-size:10px;">名</span><input type="text" size="8" maxlength="16" name="kana_s_retrieval" value="{$kana_s_retrieval}" class="imezen" />
        </td>
        <td style="">
          <select name="teamRetrieval">
<!--
            <option value="none">問わず</option>
            <option value="notRegist"{if $teamRetrieval == "notRegist"} selected="selected"{/if}>2007-2008シーズン未登録選手</option>
            <option value="onRegist"{if $teamRetrieval == "onRegist"} selected="selected"{/if}>{$preRarryDataArray.rarry_sub_name}登録選手</option>
            <option value="20072008"{if $teamRetrieval == "20072008"} selected="selected"{/if}>2007-2008シーズン登録選手</option>
            <option value="tm2009"{if $teamRetrieval == "tm2009"} selected="selected"{/if}>トーナメント2009登録選手</option>
            <option value="tm2009_myTeam"{if $teamRetrieval == "tm2009_myTeam"} selected="selected"{/if}>トーナメント2009登録選手(自チーム)</option>
            <option value="none"{if $teamRetrieval == "none"} selected="selected"{/if}>未所属</option>
-->
            <option value="0"{if $teamRetrieval == "0"} selected="selected"{/if}>チーム選択</option>
            {section name=i start=0 loop=$preRarryRegistTeamDatas}
            <option value="{$preRarryRegistTeamDatas[$smarty.section.i.index].teamId}"{if $teamRetrieval == $preRarryRegistTeamDatas[$smarty.section.i.index].teamId} selected="selected"{/if}>{$preRarryRegistTeamDatas[$smarty.section.i.index].teamName|replace:"amp;":""}</option>
            {/section}
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="text-align:center;background-color:#FFFFFF;"><input type=submit value="　選手検索　" /></td>
      </tr>
      </tbody>
      <tfoot>
      <tr>
        <td colspan="4" style="padding-left:10px;text-align:left;background-color:#FFFFFF;">
<!--        	※&nbsp;{$preRarryDataArray.rarry_sub_name}登録チームの選手はシーズン内に移籍を含む登録された選手が表示されます。<br />-->
<!--        	※&nbsp;その他チームは最後に出場したリーグ・トーナメントに登録された選手が表示されます。-->
			※&nbsp;チームを選択すると過去に選択したチームに登録された全選手が表示されます。<br />
			※&nbsp;名前・カナ検索の際に「チーム選択」をしないと全チームからの検索となります。<br />
        	※&nbsp;表示は選手のヨミガナ順に表示されます。
        </td>
      </tr>
      </tfoot>
    </table>
    </fieldset>
    <input type="hidden" name="mode" value="retrieval" />
    <input type="hidden" name="sts" value="" />
    </form>
    </div>
    <div>&nbsp;</div>

  {if $transferComp == "OK"}
    <p style="font-size:16px;font-weight:bold;color:blue;">{$registPlayerData.nameFirst}&nbsp;{$registPlayerData.nameSecond}&nbsp;さんの{$rarryDataArray.rarry_sub_name}選手登録が完了しました。</p>
  {/if}
  {if $teamRetrieval == "20072008"}
    <p style="font-weight:bold;"><span style="color:red;">※</span>&nbsp;{$preRarryDataArray.rarry_sub_name}に登録済みの選手は表示されません。</p>
  {/if}

  {if $mode == "retrieval"}
    {if count($retrievalPlayersData) > 0}
    <table id="member" align="center">
      <thead>
      <tr>
        <th style="">名前</th>
        <th style="padding:5px;" colspan="2">現在の所属チーム・背番号</th>
        <th style="">登録</th>
        <th style="">備考</th>
      </tr>
      </thead>
      <tbody>
      {section name=i start=0 loop=$retrievalPlayersData}
      {if $retrievalPlayersData[$smarty.section.i.index].memberId > 0}
      <tr>
        <td style="">
          {$retrievalPlayersData[$smarty.section.i.index].nameFirst}&nbsp;{$retrievalPlayersData[$smarty.section.i.index].nameSecond|replace:"amp;":""}
        </td>
        <td style="">
          {$retrievalPlayersData[$smarty.section.i.index].preTeamName|replace:"amp;":""}
        </td>
        <td style="padding-right:15px;text-align:right;">
          {$retrievalPlayersData[$smarty.section.i.index].preNumber}
        </td>
        {* 今シーズンのチーム登録をしているとき *}
        {if $retrievalPlayersData[$smarty.section.i.index].nextTeamName != "---"}
        <td style="">
          <span style="color:red;font-weight:bold;">登録不可</span>
        </td>
        <td style="text-align:left;">「{$retrievalPlayersData[$smarty.section.i.index].nextTeamName|replace:"amp;":""}」の選手登録済みです。</td>
<!--
        <td style="text-align:left;">未放出選手です。</td>
-->
        {else}
          {if $retrievalPlayersData[$smarty.section.i.index].discharge == "OK" OR $RARRY_RELATION == false}
        <td style="">
          <input type="button" value="移籍登録" onclick="teamPlayerRegist('{$mode}', 'comp', {$retrievalPlayersData[$smarty.section.i.index].memberId}, '{$retrievalPlayersData[$smarty.section.i.index].nameFirst}&nbsp;{$retrievalPlayersData[$smarty.section.i.index].nameSecond}')" />
        </td>
        <td style="text-align:left;">移籍可能です。</td>
          {else}
        <td style="">
          <span style="color:red;font-weight:bold;">登録不可{$retrievalPlayersData[$smarty.section.i.index].discharge}</span>
        </td>
        <td style="text-align:left;">放出選手の登録がされていません。</td>
          {/if}
        {/if}
      </tr>
      {/if}
      {/section}
      </tbody>
    </table>
    <input type="hidden" name="mode" value="retrieval" />
    <input type="hidden" name="sts" value="" />
    {else}
    <p style="font-size:14px;font-weight:bold;">条件に合う選手はいませんでした。</p>
    {/if}
  {/if}

  </div>
</div>

<form name="fmmemberregist" method="post" action="">
  <input type="hidden" name="state" value="{$state}" />
  <input type="hidden" name="name_f_retrieval" value="{$name_f_retrieval}" />
  <input type="hidden" name="name_s_retrieval" value="{$name_s_retrieval}" />
  <input type="hidden" name="kana_f_retrieval" value="{$kana_f_retrieval}" />
  <input type="hidden" name="kana_s_retrieval" value="{$kana_s_retrieval}" />
  <input type="hidden" name="teamRetrieval" value="{$teamRetrieval}" />
  <input type="hidden" name="memberId" value="" />
  <input type="hidden" name="mode" value="" />
  <input type="hidden" name="sts" value="" />
</form>
<form name="fmback" method="post" action="">
  <input type="hidden" name="mode" value="" />
  <input type="hidden" name="sts" value="" />
</form>

{* フッタ *}
{include file='./blockTmp/footer.tpl'}
