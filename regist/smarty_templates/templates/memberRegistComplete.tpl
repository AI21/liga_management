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
<link rel="stylesheet" href="../css/team.css" type="text/css" media="screen" />
<link rel="stylesheet" href="../css/member.css" type="text/css" media="screen" />

{literal}
<script language="javascript" type="text/javascript">
//<![CDATA[
<!--

  // 戻りボタン処理
  function pageBack() {
    document.memberChange.mode.value = 'back';
    document.memberChange.sts.value = '';
    document.memberChange.submit();
  }
  // 選手登録
  function playerChangeConfirm() {
    //myReturn = confirm("データを更新しますがよろしいですか？");
    //if ( myReturn == true ) {
      document.memberChange.submit();
    //}
  }
  // 閉じるボタン処理
  function windowClose() {
    window.opener.document.fmback.action = "./teamInfo.php#registmember";
    window.opener.document.fmback.submit();
    window.close();
  }

//-->
//]]>
</script>

<style type="text/css">
/*<![CDATA[*/
<!--
  #imezen { ime-mode: active; }
  #imehan1 { ime-mode: disabled; }
  #imehan2 { ime-mode: inactive; }
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
    border:1px #E3E3E3 solid;white-space: nowrap;text-align:left;font-weight:nomal;background-color:#ECE9D8;padding-left:10px;
  }
  #memberfm td {
    padding:1px 10px;border:1px #E3E3E3 solid;background-color:#F4F1EC;text-align:left;
  }
-->
/*]]>*/
</style>
{/literal}

<title>リーガ東海　選手情報変更</title>
</head>

<body>

<div style="text-align:center;">
  <div class="teamname" style="font-size:24px;">{$rarryDataArray.rarry_name}&nbsp;{$rarryDataArray.rarry_sub_name}&nbsp;{$subName}</div>

  <div style="width:450px;margin-top:15px;">

  {if $transferComp == "OK"}
    <p style="font-size:16px;font-weight:bold;color:blue;">{$registPlayerData.nameFirst}&nbsp;{$registPlayerData.nameSecond}&nbsp;さんの2009シーズン選手登録が完了しました。</p>
  {/if}

    <form name="memberChange" method="post" action="memberChange.php">
    <fieldset style="width:100%;">
    <legend>選手情報変更フォーム</legend>
  {if $sts == "conf"}
    <div style="color:blue;font-weight:bold;">変更内容確認</div>
    <table id="memberfm" style="width:100%;">
      <tbody>
      <tr>
        <th style="width:70px;">背番号</th>
        <td style="padding-left:20px;">{$number}</td>
      </tr>
      <tr>
        <th style="">名前</th>
        <td style="padding-left:20px;">{$nameFirst|replace:"amp;":""}&nbsp;{$nameSecond|replace:"amp;":""}</td>
      </tr>
      <tr>
        <th style="">読みカナ</th>
        <td style="padding-left:20px;">{$kanaFirst|replace:"amp;":""}&nbsp;{$kanaSecond|replace:"amp;":""}</td>
      </tr>
      <tr>
        <th style="">ポジション</th>
        <td style="padding-left:20px;">{$posisionView}</td>
      </tr>
      <tr>
        <th style="">身長</th>
        <td style="padding-left:20px;">{$tall}&nbsp;cm</td>
      </tr>
      <tr>
        <th style="">誕生日</th>
        <td style="padding-left:20px;">{$birthdayView}</td>
      </tr>
      </tbody>
      <tfoot>
      <tr>
        <td colspan="4" style="padding:10px 0;text-align:center;background-color:#FFFFFF;">
          <input type=button value="　選手情報変更　" onclick="playerChangeConfirm()" />&nbsp;
          <input type=button value="　戻る　" onclick="pageBack()" />
        </td>
      </tr>
      </tfoot>
      <input type="hidden" name="number" value="{$number}" />
      <input type="hidden" name="nameFirst" value="{$nameFirst}" />
      <input type="hidden" name="nameSecond" value="{$nameSecond}" />
      <input type="hidden" name="kanaFirst" value="{$kanaFirst}" />
      <input type="hidden" name="kanaSecond" value="{$kanaSecond}" />
      <input type="hidden" name="posision" value="{$posision}" />
      <input type="hidden" name="tall" value="{$tall}" />
      <input type="hidden" name="bd_type" value="{$bd_type}" />
      <input type="hidden" name="bd_y" value="{$bd_y}" />
      <input type="hidden" name="bd_m" value="{$bd_m}" />
      <input type="hidden" name="bd_d" value="{$bd_d}" />
      <input type="hidden" name="sts" value="comp" />
  {else}
    {$memberDataChangeModeValue}
    <table id="memberfm" style="width:100%;">
      <tbody>
      <tr>
        <th style="width:70px;">背番号</th>
{if $readMode ne 'readOnly'}
        <td style="padding-left:20px;"><input type="text" size="5" maxlength="2" name="number" value="{$number}" id="imehan1" />{$errorValue.number}</td>
{else}
        <td style="padding-left:20px;">{$number}<input type="hidden" name="number" value="{$number}" />{$errorValue.number}</td>
{/if}
      </tr>
      <tr>
        <th style="">名前</th>
        <td style="padding-left:20px;">
          {$nameFirst|replace:"amp;":""}&nbsp;{$nameSecond|replace:"amp;":""}{$errorValue.name}
          <input type="hidden" name="nameFirst" value="{$nameFirst|replace:"amp;":""}" />
          <input type="hidden" name="nameSecond" value="{$nameSecond|replace:"amp;":""}" />
        </td>
      </tr>
      <tr>
        <th style="">読みカナ</th>
        <td style="">
          <span style="font-size:10px;">姓</span><input type="text" size="15" maxlength="16" name="kanaFirst" value="{$kanaFirst|replace:"amp;":""}" id="imezen" />&nbsp;
          <span style="font-size:10px;">名</span><input type="text" size="15" maxlength="16" name="kanaSecond" value="{$kanaSecond|replace:"amp;":""}" id="imezen" />{$errorValue.kana}
        </td>
      </tr>
      <tr>
        <th style="">ポジション</th>
        <td style="padding-left:20px;">
          <select name="posision">
            <option value="1"{if $posision == 1} selected="selected"{/if}>ポイントガード</option>
            <option value="2"{if $posision == 2} selected="selected"{/if}>シューティングガード</option>
            <option value="3"{if $posision == 3} selected="selected"{/if}>スモールフォワード</option>
            <option value="4"{if $posision == 4} selected="selected"{/if}>パワーフォワード</option>
            <option value="5"{if $posision == 5} selected="selected"{/if}>センター</option>
         </select>
        </td>
      </tr>
      <tr>
        <th style="">身長</th>
        <td style="padding-left:20px;">
          <input type="text" size="5" maxlength="3" name="tall" value="{$tall}" id="imehan1" />&nbsp;cm{$errorValue.tall}
        </td>
      </tr>
      <tr>
        <th style="">誕生日</th>
        <td style="padding-left:20px;">
          <select name="bd_type">
            <option value="1"{if $bd_type == 1} selected="selected"{/if}>西暦</option>
            <option value="2"{if $bd_type == 2} selected="selected"{/if}>平成</option>
            <option value="3"{if $bd_type == 3} selected="selected"{/if}>昭和</option>
          </select>
          <input type="text" name="bd_y" size="4" maxlength="4" value="{$bd_y}" id="imehan1" />年
          <select name="bd_m">
            {section name=k start=1 loop=13}
            <option value="{$smarty.section.k.index}"{if $bd_m == $smarty.section.k.index} selected="selected"{/if}>{$smarty.section.k.index}</option>
            {/section}
          </select>月
          <select name="bd_d">
            {section name=l start=1 loop=32}
            <option value="{$smarty.section.l.index}"{if $bd_d == $smarty.section.l.index} selected="selected"{/if}>{$smarty.section.l.index}</option>
            {/section}
          </select>日{$errorValue.birthday}
          &nbsp;<a href="nenrei.php" target=_blank">年齢早見表</a>
        </td>
      </tr>
      </tbody>
      <tfoot>
      <tr>
        <td colspan="4" style="padding:10px 0;text-align:center;background-color:#FFFFFF;"><input type=submit value="　選手情報変更確認　" /></td>
      </tr>
      </tfoot>
      <input type="hidden" name="sts" value="conf" />
  {/if}
    </table>
    </fieldset>
    <input type="hidden" name="memberId" value="{$memberId}" />
    <input type="hidden" name="mode" value="{$mode}" />
    </form>
  </div>
</div>

<p>
注：名前の変更は出来ません。<br />　　間違いの際は下記アドレスまで詳細情報をお知らせください。<br />　　<a href="mailto:{$ligaMail}">{$ligaMail}</a>
</p>
<div style="padding:5px;text-align:center"><input type=button value="&nbsp;閉じる&nbsp;" onclick="windowClose()" /></div>

<form name="fmback" method="post" action="">
  <input type="hidden" name="mode" value="" />
  <input type="hidden" name="sts" value="" />
</form>

{* フッタ *}
{include file='./blockTmp/footer.tpl'}

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.2.min.js"><\/script>')</script>
<script type="text/javascript" src="./js/regist.js"></script>

</body>
</html>
