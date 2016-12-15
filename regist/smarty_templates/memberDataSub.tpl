  <div id="mamberDataArea">
  <h1>所属選手<a name="registmember">&nbsp;</a></h1>
{* 選手削除完了 *}
{if $removeComp == "ok"}
  <div style="margin: auto;width:800px;text-align:left;">
    <dl><dt style="font-weight:bold;color:blue;">「{$deleteMemberData.name_first}&nbsp;{$deleteMemberData.name_second}」選手を放出しました。</dt></dl>
  </div>
{/if}
    <div id="header">
<!-- メインコンテンツ -->
      <ul id="top-navigation">
        <li class="active"><span><span>{$rarryDataArray.RARRY_SUB_NAME}&nbsp;登録選手</span></span></li>
{if $readMode ne 'readOnly'}
        <li style="padding-left:250px;"><span><span><input type=button value="新規選手登録" onclick="memberNewAndTransfer('memberRegist', 'new')" /></span></span></li>
        <li style="padding-left:10px;"><span><span><input type=button value="移籍選手登録" onclick="memberNewAndTransfer('memberTransfer2', '')" /></span></span></li>
{/if}
      </ul>
    </div>
    <div id="middle">
    {* 今シーズン登録選手一覧 *}
{if count($nextMemberDatas) > 0 }
{if count($nextMemberDatas) < 5 }
      <div style="margin:5px 0 5px 20px;color:red;text-align:left;font-size:14px;font-weight:bold;">※&nbsp;チーム登録選手を【&nbsp;５人以上&nbsp;】にしてください。</div>
      {else}
        {if INDI_PAYMENT_FLAG }
        <div style="float:left;margin:5px 0 5px 20px;text-align:left;font-size:14px;">
        	メンバー登録者数：<span style="font-weight:bold;">{$nextMemberDatas|@count}人</span>登録<br />
        	今回登録の個人登録費用は【&nbsp;<span style="font-weight:bold;color:red;">{$nextMemberNumMoney}&nbsp;円</span>&nbsp;】となります。
        </div>
        {/if}
{/if}
    {* 今シーズン登録選手一覧 *}
      <table id="mamberDataTable">
        <thead>
        <tr>
          <th style="">背番号</th>
          <th style="">名前</th>
          <th style="">読みカナ</th>
          <th style="">ポジション</th>
          <th style="">身長</th>
          <th style="">年齢</th>
          <th style="">誕生日</th>
          <th style="">&nbsp;</th>
        </tr>
        </thead>
{if $member_discharge == false}
        <tfoot>
        <tr>
          <td colspan="8" style="padding:8px;text-align:right;font-weight:bold;">※&nbsp;登録済みのメンバーは「&nbsp;<span style="color:#0000FF;">放出</span>&nbsp;」できません。</td>
        </tr>
        </tfoot>
{/if}
        <tbody>
        <!-- チーム所属メンバーリスト -->
        {section name=i start=0 loop=$nextMemberDatas}
        <tr class="{cycle values="td_w,td_g"}">
          {if $nextMemberDatas[i].number != ""}
          <td style="text-align:right;">{$nextMemberDatas[i].number}</td>
          {else}
          <td style="text-align:center;color:#FF0000;font-weight:bold;">未</td>
          {/if}
          <td style="text-align:left;">
            {if $nextMemberDatas[i].created > $registStartDate}
            <img src="./img/new.gif" width="16" height="16" alt="新規選手" border="0" />
            {$nextMemberDatas[i].nameFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].nameSecond|replace:"amp;":""}
            {else}
            <span style="padding-left:20px;">{$nextMemberDatas[i].nameFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].nameSecond|replace:"amp;":""}</span>
            {/if}
          </td>
          <td style="text-align:left;">{$nextMemberDatas[i].kanaFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].kanaSecond|replace:"amp;":""}</td>
          {if $nextMemberDatas[i].posision != "未登録"}
          <td style="text-align:left;">{$nextMemberDatas[i].posision}</td>
          {else}
          <td style="text-align:left;color:#FF0000;font-weight:bold;">{$nextMemberDatas[i].posision}</td>
          {/if}
          <td>{$nextMemberDatas[i].height}</td>
          <td>{$nextMemberDatas[i].age}</td>
          <td>{$nextMemberDatas[i].birthday}</td>
          {if $nextMemberDatas[i].discharge_date == "0000-00-00 00:00:00"}
          <td>
            <input type=button value="修正" onclick="memberDetailChangeWindow('{$nextMemberDatas[i].nemberId}')" />&nbsp;
{if $readMode ne 'readOnly' and $member_discharge == true}
            <input type=button value="放出" onclick="deleteMember('dischargeMember', '{$nextMemberDatas[i].nemberId}')" />
{elseif $nextMemberDatas[i].created > $registStartDate}
            <input type=button value="放出" onclick="deleteMember('dischargeMember', '{$nextMemberDatas[i].nemberId}')" />
{/if}
          </td>
          {else}
          <td style="text-align:center;">放出済み</td>
          {/if}
        </tr>
        {if $nextMemberDatas[i].number == "" OR $nextMemberDatas[i].posision == "未登録"}
        <tr>
          <td colspan="8" style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">
            <div>└
            {if $nextMemberDatas[i].number eq ""}
            「背番号」
            {/if}
            {if $nextMemberDatas[i].kanaFirst eq "" OR $nextMemberDatas[i].kanaSecond eq ""}
            「読みカナ」
            {/if}
            {if $nextMemberDatas[i].posision eq "未登録"}
            「ポジション」
            {/if}
            を登録してください。</div>
          </td>
        </tr>
        {/if}
        {/section}
        </tbody>
      </table>
{else}
      <div style="padding:30px 0px;">登録選手がありませんので新規選手登録・移籍選手登録をしてください。</div>
{/if}
{* 放出選手一覧 *}
{if count($preMemberDatas) > 0 }
      <form name="membertaking" method="post" action="teamInfo.php#registmember">
      <fieldset style="width:auto;">
      <legend>放出選手</legend>
      <table id="outMamberDataTable">
        <thead>
        <tr>
          <th style="">背番号</th>
          <th style="">名前</th>
          <th style="">読みカナ</th>
          <th style="">ポジション</th>
          <th style="">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <!-- チーム所属メンバーリスト -->
        {section name=i start=0 loop=$preMemberDatas}
        <tr>
          <td style="text-align:right;">{$preMemberDatas[i].number}</td>
          <td style="">{$preMemberDatas[i].nameFirst|replace:"amp;":""}&nbsp;{$preMemberDatas[i].nameSecond|replace:"amp;":""}</td>
          <td style="">{$preMemberDatas[i].kanaFirst|replace:"amp;":""}&nbsp;{$preMemberDatas[i].kanaSecond|replace:"amp;":""}</td>
          <td style="text-align:center;">{$preMemberDatas[i].posision}</td>
          <td style="text-align:center;">放出済み</td>
        </tr>
        {/section}
        </tbody>
        <input type="hidden" name="takingRarryId" value="{$rarryId}" />
        <input type="hidden" name="takingSeasonId" value="{$season}" />
      </table>
      <input type="hidden" name="mode" value="memberAhead" />
      </fieldset>
      </form>
{/if}
    </div>
  </div>
