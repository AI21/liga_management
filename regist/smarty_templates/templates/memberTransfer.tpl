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
<link rel="stylesheet" href="./css/transfer.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/simplePagination.css" type="text/css" media="screen" />

<title>{$rarryDataArray.rarry_name}&nbsp;{$rarryDataArray.rarry_sub_name}&nbsp;選手検索・移籍登録</title>
</head>
<body>
<div id="mainContents">
	<div id="headerArea"><a href="http://liga-tokai.com/">リーガ東海HOME</a>&nbsp;|&nbsp;<a href="./login.php">ログアウト</a></div>
	<div id="titleArea">{$rarryDataArray.rarry_name}&nbsp;{$rarryDataArray.rarry_sub_name}&nbsp;選手検索・移籍{$subName}</div>
	<div id="teamDataArea">
		<div id="pagemove">
			<input type=button value="&nbsp;チーム編集画面へ戻る&nbsp;" onclick="pageBack('back')" />
			<input type=button value="新規選手登録" onclick="memberNewAndTransfer('memberRegist', 'new')" />
		</div>
		<div id="member-search">
			<fieldset>
			<legend>[&nbsp;チーム未登録・移籍可能&nbsp;]&nbsp;選手検索フォーム</legend>
			<!--div style="margin:3px 0;text-align:left;">※&nbsp;移籍選手は「放出」登録をしないと移籍先チームに登録出来ませんのでよろしくお願いいたします。</div-->
			<table id="memberfm" align="center">
				<tbody>
				<tr>
					<th colspan="3">所属チームから検索</th>
				</tr>
				<tr>
					<form name="membertaking" method="post" action="memberTransfer.php">
					<td colspan="2">
						<span>所属チーム&nbsp;:&nbsp;</span><select name="teamRetrieval">
							{section name=i start=0 loop=$preRarryRegistTeamDatas}
							<option value="{$preRarryRegistTeamDatas[$smarty.section.i.index].teamId}"{if $teamRetrieval == $preRarryRegistTeamDatas[$smarty.section.i.index].teamId} selected="selected"{/if}>{$preRarryRegistTeamDatas[$smarty.section.i.index].teamName|replace:"amp;":""}</option>
							{/section}
						</select>
					</td>
					<td><input type=submit value="　選手検索　" /></td>
					<input type="hidden" name="mode" value="retrieval" />
					<input type="hidden" name="sts" value="" />
					</form>
				</tr>
				<tr>
					<th colspan="3">名前(漢字)から検索</th>
				</tr>
				<tr>
					<form name="membertaking" method="post" action="memberTransfer.php">
					<td>
						<span>姓　&nbsp;:&nbsp;</span><input type="text" size="8" maxlength="16" name="name_f_retrieval" value="{$name_f_retrieval}" class="imezen" />&nbsp;
					</td>
					<td>
						<span>名　&nbsp;:&nbsp;</span><input type="text" size="8" maxlength="16" name="name_s_retrieval" value="{$name_s_retrieval}" class="imezen" />
					</td>
					<td><input type=submit value="　選手検索　" /></td>
					<input type="hidden" name="mode" value="retrieval" />
					<input type="hidden" name="sts" value="" />
					</form>
				</tr>
				<tr>
					<th colspan="3">読みカナから検索</th>
				</tr>
				<tr>
				<tr>
					<form name="membertaking" method="post" action="memberTransfer.php">
					<td>
						<span>セイ&nbsp;:&nbsp;</span><input type="text" size="8" maxlength="16" name="kana_f_retrieval" value="{$kana_f_retrieval}" class="imezen" />&nbsp;
					</td>
					<td>
						<span>メイ&nbsp;:&nbsp;</span><input type="text" size="8" maxlength="16" name="kana_s_retrieval" value="{$kana_s_retrieval}" class="imezen" />
					</td>
					<td><input type=submit value="　選手検索　" /></td>
					<input type="hidden" name="mode" value="retrieval" />
					<input type="hidden" name="sts" value="" />
					</form>
				</tr>
				</tbody>
				<tfoot>
				<tr>
					<td colspan="4">
						※&nbsp;検索に該当する指定チームに登録された全選手が表示されます。<br />
						※&nbsp;名前・読みカナ検索をした場合は、チーム検索は含まれません。
					</td>
				</tr>
				</tfoot>
			</table>
			</fieldset>
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
				<th style="">現在(最終)所属チーム</th>
				<th style="">背番号</th>
				<th style="">身長</th>
				<th style="">生年月日</th>
				<th style="">登録</th>
				<th style="">備考</th>
			</tr>
			</thead>
			<tbody>
			{section name=i start=0 loop=$retrievalPlayersData}
			{if $retrievalPlayersData[$smarty.section.i.index].memberId > 0}
			<tr style="">
				<td style="">
					{$retrievalPlayersData[$smarty.section.i.index].nameFirst}&nbsp;{$retrievalPlayersData[$smarty.section.i.index].nameSecond|replace:"amp;":""}
				</td>
				<td style="">
					{$retrievalPlayersData[$smarty.section.i.index].preTeamName|replace:"amp;":""}
				</td>
				<td style="padding-right:15px;text-align:right;">
					{$retrievalPlayersData[$smarty.section.i.index].preNumber}
				</td>
				<td style="">
					{$retrievalPlayersData[$smarty.section.i.index].height}cm
				</td>
				<td style="">
					{$retrievalPlayersData[$smarty.section.i.index].birthday}
				</td>
				{* 今シーズンのチーム登録をしているとき *}
				{if $retrievalPlayersData[$smarty.section.i.index].nextTeamName != "---"}
				<td style="">
					<span style="color:red;font-weight:bold;">登録不可</span>
				</td>
				<td style="text-align:left;">「{$retrievalPlayersData[$smarty.section.i.index].nextTeamName|replace:"amp;":""}」選手で登録済みです。</td>
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

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
<script type="text/javascript" src="./js/jquery.simplePagination.js"></script>
<script type="text/javascript" src="./js/transfer.js"></script>

</body>
</html>
