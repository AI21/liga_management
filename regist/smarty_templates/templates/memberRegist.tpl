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
<link rel="stylesheet" href="./css/regist.css" type="text/css" media="screen" />

<title>{$rarryDataArray.rarry_name}&nbsp;{$rarryDataArray.rarry_sub_name}&nbsp;選手登録・変更</title>
</head>

<body>

<div id="mainContents">
  <div id="headerArea"><a href="http://liga-tokai.com/">リーガ東海HOME</a>&nbsp;|&nbsp;<a href="./login.php">ログアウト</a></div>
  <div id="titleArea">{$rarryDataArray.rarry_name}&nbsp;{$rarryDataArray.rarry_sub_name}&nbsp;新規{$subName}</div>

  <div id="teamDataArea">

    <div style="margin-top:10px;">
      <input type=button value="&nbsp;チーム編集画面へ戻る&nbsp;" onclick="pageBack('back')" />&nbsp;
      <input type=button value="&nbsp;選手検索・移籍登録&nbsp;" onclick="pageTransfer()" />
    </div>
    <div>&nbsp;</div>

    <form name="fmmember" method="post" action="memberRegist.php">
    <table id="mamberDataTable">
{if $mode == "new"}
      <thead>
      <tr>
        <td colspan="9" style="text-align:left;background-color:#FFFFFF;">
          <dl id="attention">
            <dt>登録について</dt>
	            <dd>一度に&nbsp;10&nbsp;名まで登録出来ます。<br />
	            	【&nbsp;名前&nbsp;】に記入がある選手のみ登録対象となります。<br />
	            	移籍選手・チーム未登録かつ選手データがある場合は移籍選手登録画面にて申請してください。<br />
	            	<span style="color:#ff0000;font-weight:bold;">
	            	※&nbsp;重複登録防止のため「名前(姓・名) 」は登録後に修正は出来ませんのでご注意下さい。<br />
	            	　　└&nbsp;間違えて登録した場合は「再登録はしない」で、事務局まで連絡してください。
	            	</span></dd>
            <dt>年齢について</dt>
	            <dd>年齢は誕生日から自動計算されます。<br />
	            	誕生日(年)が未入力の時は&nbsp;{$ages_array.default_age}&nbsp;歳&nbsp;(西暦&nbsp;{$ages_array.default_seireki}&nbsp;年&nbsp;1&nbsp;月&nbsp;1&nbsp;日)に設定されます。<br />
	            	年齢は誕生日から自動計算されます。</dd>
            <dt>入力文字について</dt>
	            <dd>背番号　&nbsp;：&nbsp;全半角数値のみ<br />
	            	読みカナ&nbsp;：&nbsp;カタカナのみ<br />
	            	身　長　&nbsp;：&nbsp;全半角数値のみ<br />
	            	誕生日　&nbsp;：&nbsp;全半角数値のみ</dd>
            <dt>入力範囲について</dt>
	            <dd>背番号&nbsp;：&nbsp;00&nbsp;～&nbsp;99&nbsp;の範囲&nbsp;(&nbsp;00&nbsp;～&nbsp;09&nbsp;も可能&nbsp;)<br />
	            	身　長&nbsp;：&nbsp;250&nbsp;以下<br />
	            	誕生日：&nbsp;満&nbsp;{$age_low}&nbsp;歳以上満&nbsp;{$age_hi}&nbsp;歳以下になるようにしてください<br />
	            	├&nbsp;西暦&nbsp;選択&nbsp;：&nbsp;{$ages_array.seireki_low}&nbsp;～&nbsp;{$ages_array.seireki_hi}<br />
	            	├&nbsp;平成&nbsp;選択&nbsp;：&nbsp;{$ages_array.heisei_low}&nbsp;～&nbsp;{$ages_array.heisei_hi}<br />
	            	└&nbsp;昭和&nbsp;選択&nbsp;：&nbsp;{$ages_array.showa_low}&nbsp;～&nbsp;{$ages_array.showa_hi}</dd>
            <dt>氏名の重複について</dt>
	            <dd>名前がリーガ登録選手と一致した選手は登録出来ませんのでご注意ください<br />
	            	リーガ東海登録済み選手と同姓同名の選手は事務局にお知らせください。事務局側にて直接登録致します<br />
	            	事務局連絡先&nbsp;：&nbsp;<a href="mailto:{$ligaMail}">{$ligaMail}</a>　※チーム名・選手詳細をお知らせ下さい</dd>
          </dl>
        </td>
      </tr>
      <tr>
        <th style="">背番号</th>
        <th style="">名前(姓・名)&nbsp;<span style="font-size:10px;color:red;">必須</span></th>
        <th style="">読みカナ(姓・名)&nbsp;<span style="font-size:10px;color:red;">必須</span></th>
        <th style="">ポジション</th>
        <th style="">身長&nbsp;<span style="font-size:10px;color:red;">必須</span></th>
        <th style="">誕生日&nbsp;<span style="font-size:10px;color:red;">必須</span>&nbsp;　&nbsp;<a href="nenrei.php" target="_blank">年齢早見表</a></th>
      </tr>
      </thead>
      <tbody>
      {section name=i start=0 loop=$registNum}
      <tr>
        <td style="">
          <input type="text" size="2" maxlength="2" name="number[{$smarty.section.i.index}]" value="{$number[$smarty.section.i.index]}" class="imehan1" />
        </td>
        <td style="">
          <input type="text" size="8" maxlength="16" name="name_f[{$smarty.section.i.index}]" value="{$name_f[$smarty.section.i.index]}" class="imezen" />&nbsp;
          <input type="text" size="8" maxlength="16" name="name_s[{$smarty.section.i.index}]" value="{$name_s[$smarty.section.i.index]}" class="imezen" />
        </td>
        <td style="">
          <input type="text" size="8" maxlength="16" name="kana_f[{$smarty.section.i.index}]" value="{$kana_f[$smarty.section.i.index]}" class="imezen" />&nbsp;
          <input type="text" size="8" maxlength="16" name="kana_s[{$smarty.section.i.index}]" value="{$kana_s[$smarty.section.i.index]}" class="imezen" />
        </td>
        <td style="">
          <select name="posision[{$smarty.section.i.index}]">
            <option value="1"{if $posision[$smarty.section.i.index] == 1} selected="selected"{/if}>PG</option>
            <option value="2"{if $posision[$smarty.section.i.index] == 2} selected="selected"{/if}>SG</option>
            <option value="3"{if $posision[$smarty.section.i.index] == 3} selected="selected"{/if}>SF</option>
            <option value="4"{if $posision[$smarty.section.i.index] == 4} selected="selected"{/if}>PF</option>
            <option value="5"{if $posision[$smarty.section.i.index] == 5} selected="selected"{/if}>C</option>
          </select>
        </td>
        <td style="">
          <input type="text" size="3" maxlength="3" name="tall[{$smarty.section.i.index}]" value="{$tall[$smarty.section.i.index]}" class="imehan1" />cm
        </td>
        <td style="">
          <select name="bd_type[{$smarty.section.i.index}]">
            <option value="1"{if $bd_type[$smarty.section.i.index] == 1} selected="selected"{/if}>西暦</option>
            <option value="2"{if $bd_type[$smarty.section.i.index] == 2} selected="selected"{/if}>平成</option>
            <option value="3"{if $bd_type[$smarty.section.i.index] == 3} selected="selected"{/if}>昭和</option>
          </select>
          <input type="text" name="bd_y[{$smarty.section.i.index}]" size="4" maxlength="4" value="{$bd_y[$smarty.section.i.index]}" class="imehan1" />年
          <select name="bd_m[{$smarty.section.i.index}]">
            {section name=k start=1 loop=13}
            <option value="{$smarty.section.k.index}"{if $bd_m[$smarty.section.i.index] == $smarty.section.k.index} selected="selected"{/if}>{$smarty.section.k.index}</option>
            {/section}
          </select>月
          <select name="bd_d[{$smarty.section.i.index}]">
            {section name=l start=1 loop=32}
            <option value="{$smarty.section.l.index}"{if $bd_d[$smarty.section.i.index] == $smarty.section.l.index} selected="selected"{/if}>{$smarty.section.l.index}</option>
            {/section}
          </select>日
        </td>
      </tr>
      {if $errorValue[$smarty.section.i.index] != ""}
      <tr>
        <td colspan="9" style="padding-left:60px;text-align:left;background-color:#FFFFFF;">
          {$errorValue[$smarty.section.i.index]}
        </td>
      </tr>
      {/if}
      {/section}
      </tbody>
      <tfoot>
      <tr>
        <td colspan="10" style="text-align:center;padding:10px">
{$fmButtonValue}
          <input type="hidden" name="mode" value="playerInsert" />
          <input type="hidden" name="sts" value="conf" />
          <input type="submit" value="　選手登録確認　" />
        </td>
      </tr>
      </tfoot>
{else}
      <thead>
      <tr>
        <th style="">背番号</th>
        <th style="">名前</th>
        <th style="">読みカナ</th>
        <th style="">ポジション</th>
        <th style="">身長</th>
        <th style="">誕生日</th>
        <th style="">年齢</th>
      </tr>
      </thead>
      <tbody>
      {section name=i start=0 loop=$registNum}
      {if $insertDatas[i].name_f != ""}
      <tr>
        <td style="text-align:right;">{$insertDatas[i].numberValue}</td>
        <td style="">{$insertDatas[i].name_f}&nbsp;{$insertDatas[i].name_s}</td>
        <td style="">{$insertDatas[i].kana_f}&nbsp;{$insertDatas[i].kana_s}</td>
        <td style="">{$insertDatas[i].posisionValue}</td>
        <td style="">{$insertDatas[i].tall}</td>
        <td style="">{$insertDatas[i].bithdayType}&nbsp;{$insertDatas[i].bd_y}年{$insertDatas[i].bd_m}月{$insertDatas[i].bd_d}日</td>
        <td style="">{$insertDatas[i].age}才</td>
      </tr>
      {/if}
        <input type="hidden" name="number[{$smarty.section.i.index}]" value="{$insertDatas[i].number}" />
        <input type="hidden" name="name_f[{$smarty.section.i.index}]" value="{$insertDatas[i].name_f}" />
        <input type="hidden" name="name_s[{$smarty.section.i.index}]" value="{$insertDatas[i].name_s}" />
        <input type="hidden" name="kana_f[{$smarty.section.i.index}]" value="{$insertDatas[i].kana_f}" />
        <input type="hidden" name="kana_s[{$smarty.section.i.index}]" value="{$insertDatas[i].kana_s}" />
        <input type="hidden" name="posision[{$smarty.section.i.index}]" value="{$insertDatas[i].posision}" />
        <input type="hidden" name="tall[{$smarty.section.i.index}]" value="{$insertDatas[i].tall}" />
        <input type="hidden" name="bd_type[{$smarty.section.i.index}]" value="{$insertDatas[i].bd_type}" />
        <input type="hidden" name="bd_y[{$smarty.section.i.index}]" value="{$insertDatas[i].bd_y}" />
        <input type="hidden" name="bd_m[{$smarty.section.i.index}]" value="{$insertDatas[i].bd_m}" />
        <input type="hidden" name="bd_d[{$smarty.section.i.index}]" value="{$insertDatas[i].bd_d}" />
      {/section}
      </tbody>
      <tfoot>
      <tr>
        <td colspan="10" style="text-align:center;padding:10px;background-color:#FFFFFF;">
          <input type="hidden" name="mode" value="new" />
          <input type="hidden" name="sts" value="" />
{$fmButtonValue}
        </td>
      </tr>
      </tfoot>
{/if}
    </table>
    </form>

  </div>
</div>

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
