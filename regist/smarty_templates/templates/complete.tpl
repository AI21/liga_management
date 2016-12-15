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

{literal}
<style type="text/css">
/*<![CDATA[*/
<!--
  #imezen { ime-mode: active; }
  #imehan1 { ime-mode: disabled; }
  #imehan2 { ime-mode: inactive; }
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
-->
/*]]>*/
</style>
{/literal}

<title>リーガ東海&nbsp;2009SEASON&nbsp;チーム情報確認</title>
</head>

<body>

<div style="text-align:center;">
  <div style="width:800px;text-align:right;"><a href="http://liga-tokai.com/">リーガ東海HOME</a></div>
  <div class="teamname">リーガ東海 2009SEASON LEAGUE {$teamName}</div>
  <div style="width:800px;">
  <div class="bar">チーム情報</div>
  <div>
{$teamDataChangeModeValue}
    <div style="width:100%;">
      <table id="team">
        <tbody>
        <tr>
          <th>リーグクラス</th><td>{$rarryClass}</td>
        </tr>
        <tr>
          <th>チーム名</th><td>{$teamDatas.teamName|replace:"amp;":""}</td>
        </tr>
        <tr>
          <th>チーム名(カナ)</th><td>{$teamDatas.teamKana}&nbsp;</td>
        </tr>
        <tr>
          <th>代表者氏名</th><td>{$teamDatas.teamRep|replace:"amp;":""}&nbsp;</td>
        </tr>
        <tr>
          <th>代表者携帯番号</th><td>{$teamDatas.teamRepTel}&nbsp;</td>
        </tr>
        <tr>
          <th>代表者メールアドレス</th><td>{$teamDatas.teamRepMail}&nbsp;</td>
        </tr>
        <tr>
          <th>副代表者氏名</th><td>{$teamDatas.teamSubRep|replace:"amp;":""}&nbsp;</td>
        </tr>
        <tr>
          <th>副代表者携帯番号</th><td>{$teamDatas.teamSubRepTel}&nbsp;</td>
        </tr>
        <tr>
          <th>副代表者メールアドレス</th><td>{$teamDatas.teamSubRepMail}&nbsp;</td>
        </tr>
        <tr>
          <th>活動場所</th><td>{$teamDatas.teamDistrict}&nbsp;{$teamDatas.teamPlace|replace:"amp;":""}</td>
        </tr>
        <tr>
          <th>ホームカラー</th><td>{$teamDatas.teamHomeColor}&nbsp;</td>
        </tr>
        <tr>
          <th>アウェイカラー</th><td>{$teamDatas.teamAwayColor}&nbsp;</td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class='highslide-caption' id='caption1'>
      【&nbsp;TEAM&nbsp;{$teamName}&nbsp;】
    </div>
  </div>

  <div>&nbsp;</div>

  <div class="bar">所属選手<a name="registmember">&nbsp;</a></div>

  <div id="main">
    <div id="header">
      <ul id="top-navigation">
        <li class="active"><span><span>2009&nbsp;SEASON&nbsp;登録選手</span></span></li>
      </ul>
    </div>
    <div id="middle">
    {* 今シーズン登録選手一覧 *}
    {if count($nextMemberDatas) > 0 }
      <table id="member" style="width:90%;">
        <caption style="text-align:left;font-size:14px;font-weight:bold;">チーム登録人数　：　{$nextMembarNum}&nbsp;人</caption>
        <thead>
        <tr>
          <th style="">背番号</th>
          <th style="">名前</th>
          <th style="">読みカナ</th>
          <th style="">ポジション</th>
          <th style="">身長</th>
          <th style="">年齢</th>
          <th style="">誕生日</th>
        </tr>
        </thead>
        <tbody>
        <!-- チーム所属メンバーリスト -->
        {section name=i start=0 loop=$nextMemberDatas}
        <tr>
          {if $nextMemberDatas[i].number != ""}
          <td style="text-align:right;">{$nextMemberDatas[i].number}</td>
          {else}
          <td style="text-align:center;color:#FF0000;font-weight:bold;">未</td>
          {/if}
          <td style="">{$nextMemberDatas[i].nameFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].nameSecond|replace:"amp;":""}</td>
          <td style="">{$nextMemberDatas[i].kanaFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].kanaSecond|replace:"amp;":""}</td>
          <td style="text-align:center;">{$nextMemberDatas[i].posision}</td>
          <td>{$nextMemberDatas[i].height}</td>
          <td>{$nextMemberDatas[i].age}</td>
          <td>{$nextMemberDatas[i].birthday}</td>
        </tr>
        {/section}
        </tbody>
    {/if}
      </table>
    </div>
  </div>

</div>
</div>

<hr class="example" />

{* フッタ *}
{include file='./blockTmp/footer.tpl'}
