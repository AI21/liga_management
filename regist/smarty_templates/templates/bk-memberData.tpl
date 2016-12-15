  <div class="bar">所属選手</div>

{* 選手取り込み結果 *}
{if $mode == "memberAhead"}
  {if count($registPlayers) > 0}
  <div style="text-align:left;">
    <dl>
      <dt style="color:blue;">下記選手の2009シーズン選手登録が完了しました。</dt>
      {foreach from=$registPlayers key="key" item=playersData}
      <dd><span style="width:80px;">No.&nbsp;{$playersData.number}</span><span>{$playersData.firstName}&nbsp;{$playersData.secondName}</span></dd>
      {/foreach}
    </dl>
  </div>
  {/if}
  {if count($registSameTeamPlayers) > 0}
  <div style="text-align:left;">
    <dl>
      <dt style="color:green;">下記選手はすでに[&nbsp;{$teamDatas.teamName}&nbsp;]チームで登録されています。</dt>
      {foreach from=$registSameTeamPlayers key="key" item=playersData}
      <dd><span style="width:80px;">No.&nbsp;{$playersData.number}</span><span>{$playersData.firstName}&nbsp;{$playersData.secondName}</span></dd>
      {/foreach}
    </dl>
  </div>
  {/if}
  {if count($registdiffTeamPlayers) > 0}
  <div style="text-align:left;">
    <dl>
      <dt style="color:red;">下記選手は別チームで登録されていますので選手登録が出来ませんでした。</dt>
      {foreach from=$registdiffTeamPlayers key="key" item=playersData}
      <dd><span style="width:100px;">{$playersData.firstName}&nbsp;{$playersData.secondName}</span><span>[&nbsp;{$playersData.teamName}&nbsp;]で登録されています</span></dd>
      {/foreach}
    </dl>
  </div>
  {/if}
{/if}
  <div id="main">
    <div id="header">
<!-- メインコンテンツ -->
      <ul id="top-navigation">
{if $rarryId == $currentRarry}
        <li class="active"><span><span><a href="#" onclick="changeRarry('2', '1')">2009&nbsp;1st&nbsp;SEASON</a></span></span></li>
        <li><span><span><a href="#" onclick="changeRarry('1', '3')">2007-2008&nbsp;SEASON</a></span></span></li>
        <li style="padding-left:200px;"><span><span><input type=button value="新規選手登録" onclick="changeConf('aaa')" /></span></span></span></li>
{else}
        <li><span><span><a href="#" onclick="changeRarry('2', '1')">2009&nbsp;SEASON</a></span></span></li>
        <li class="active"><span><span><a href="#" onclick="changeRarry('1', '3')">2007-2008&nbsp;SEASON</a></span></span></li>
{/if}
      </ul>
    </div>
    <div id="middle">
{if count($memberDataArray) > 0 }
  { *表示年度が本年度の場合* }
  {if $rarryId == $currentRarry}
      <table id="member">
        <thead>
        <tr>
          <th style="">背番号</th>
          <th style="">名前</th>
          <th style="">読みカナ</th>
          <th style="">ポジション</th>
          <th style="">身長</th>
          <th style="">年齢</th>
          <th style="">誕生日</th>
          <th colspan="2" style="">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <!-- チーム所属メンバーリスト -->
        {section name=i start=0 loop=$memberDataArray}
        <tr>
          {if $memberDataArray[i].number != ""}
          <td style="text-align:right;">{$memberDataArray[i].number}</td>
          {else}
          <td style="text-align:center;color:#FF0000;font-weight:bold;">未</td>
          {/if}
          <td style="width:90px;">{$memberDataArray[i].nameFirst}&nbsp;{$memberDataArray[i].nameSecond}</td>
          <td style="width:90px;">{$memberDataArray[i].kanaFirst}&nbsp;{$memberDataArray[i].kanaSecond}</td>
          <td style="text-align:center;">{$memberDataArray[i].posision}</td>
          <td>{$memberDataArray[i].height}</td>
          <td>{$memberDataArray[i].age}</td>
          <td>{$memberDataArray[i].birthday}</td>
          <td><input type=button value="修正" onclick="changeConf('aaa')" /></td>
          <td><input type=button value="放出" onclick="changeConf('aaa')" /></td>
        </tr>
        {/section}
        </tbody>
  {else}
      <form name="membertaking" method="post" action="teamInfo.php">
      <table id="memberfm">
        <thead>
        <tr>
          <th style="">&nbsp;</th>
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
        {section name=i start=0 loop=$memberDataArray}
        <tr>
          <td><input type=checkbox name="aheadPlayer[]" value="{$memberDataArray[i].nemberId}" checked="checked" /></td>
          <td style="text-align:right;">{$memberDataArray[i].number}</td>
          <td style="width:90px;">{$memberDataArray[i].nameFirst}&nbsp;{$memberDataArray[i].nameSecond}</td>
          <td style="width:90px;">{$memberDataArray[i].kanaFirst}&nbsp;{$memberDataArray[i].kanaSecond}</td>
          <td style="text-align:center;">{$memberDataArray[i].posision}</td>
          <td>{$memberDataArray[i].height}</td>
          <td>{$memberDataArray[i].age}</td>
          <td>{$memberDataArray[i].birthday}</td>
        </tr>
        {/section}
        </tbody>
        <tfoot>
        <tr>
          <td colspan="2" style=""><input type=button value="取り込み" onclick="memberTaking()" /></td>
          <td colspan="6" style="">今シーズンにそのまま登録する場合はチェックを入れて「取り込み」ボタンをクリックしてください。</td>
        </tr>
        </tfoot>
        <input type="hidden" name="takingRarryId" value="{$rarryId}" />
        <input type="hidden" name="takingSeasonId" value="{$season}" />
  {/if}
      </table>
      <input type="hidden" name="mode" value="memberAhead" />
      </form>
{else}
      <div style="padding:30px 0px;">登録選手がありませんので昨年度からの選手データの取り込み、もしくは新規選手登録をしてください。</div>
{/if}
    </div>
  </div>
