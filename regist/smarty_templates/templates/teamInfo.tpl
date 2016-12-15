<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja-JP" lang="ja-JP">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="author" content="今井厚文" />
<meta name="generator" content="php_editor" />

<link rel="stylesheet" href="./css/hiside_image.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/base.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/teaminfo.css" type="text/css" media="screen" />
<link rel="contents" href="../index.htm" />
<link rev="made" href="mailto:web@liga-tokai.com" />

<title>リーガ東海　チーム情報登録変更</title>
</head>

<body>

<div id="mainContents">
  <div id="headerArea">
{if $adminMode}
    <a href="teamInfo.php">画面更新</a>&nbsp;|&nbsp;<a href="http://liga-tokai.com/">リーガ東海HOME</a>&nbsp;|&nbsp;<a href="./login_admin.php?loginAdminMode=samurai21">ログアウト</a>
{else}
    <a href="teamInfo.php">画面更新</a>&nbsp;|&nbsp;<a href="http://liga-tokai.com/">リーガ東海HOME</a>&nbsp;|&nbsp;<a href="./login.php">ログアウト</a>
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
          { if $teamDatas.pictureHome == '未登録' or $mode == "memberAhead" or $mode == "removeMember" }
          <img src="../common/images/no_photo_available_180x120.jpg" alt="未登録" />
          {else}
          <img src="./team_picutre.php?rarryId={$rarryId}&amp;teamId={$teamId}&amp;view=thumb_home" alt="チーム写真[HOME]" />
          {/if}
        </td>
        <td>
          {if $appliesPictureAway}
          <div style="color:blue;font-weight:bold;">投稿画像申請中です</div>
          {/if}
          { if $teamDatas.pictureHome == '未登録' or $mode == "memberAhead" or $mode == "removeMember" }
          <img src="../common/images/no_photo_available_180x120.jpg" alt="未登録" />
          {else}
          <img src="./team_picutre.php?rarryId={$rarryId}&amp;teamId={$teamId}&amp;view=thumb_away" alt="チーム写真[AWAY]" />
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
    <input type="hidden" name="mailSends" value="" />
    <input type="hidden" name="mailSendOther" value="" />
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

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
<script type="text/javascript" src="./js/teamInfo.js"></script>

</body>
</html>
