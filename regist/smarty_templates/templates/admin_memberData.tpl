  <div class="bar">所属選手<a name="registmember">&nbsp;</a></div>

  <div id="main">
    <div id="header">
<!-- メインコンテンツ -->
      <ul id="top-navigation">
        <li class="active"><span><span>2009&nbsp;SEASON&nbsp;登録選手</span></span></li>
      </ul>
    </div>
    <div id="middle">
    {* 今シーズン登録選手一覧 *}
    {if count($nextMemberDatas) > 0 }
      <table id="member" style="width:90%;">
        <caption style="text-align:left;font-size:14px;font-weight:bold;">
          チーム登録人数　：　{$nextMembarNum}&nbsp;人<br />
          選手登録金額　　：　{$indiRegistYen}&nbsp;円
        </caption>
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
      </table>
    {else}
      <div style="margin:20px 0 5px 20px;color:red;text-align:left;font-size:18px;font-weight:bold;">※&nbsp;2009シーズンの選手が登録されていません。</div>
    {/if}
{* 前シーズン登録選手一覧 *}
{if count($preMemberDatas) > 0 }
      <fieldset style="width:70%;">
      <legend>2007-2008&nbsp;SEASON&nbsp;登録選手(今シーズン未登録選手のみ表示)</legend>
      <table id="memberfm" style="width:95%;">
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
          {if $preMemberDatas[i].discharge_date == "0000-00-00 00:00:00"}
          <td style="text-align:center;">未放出</td>
          {else}
          <td style="text-align:center;">放出済み</td>
          {/if}
        </tr>
        {/section}
        </tbody>
      </table>
      </fieldset>
{/if}
    </div>
  </div>
