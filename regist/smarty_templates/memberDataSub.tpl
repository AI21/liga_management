  <div id="mamberDataArea">
  <h1>��°����<a name="registmember">&nbsp;</a></h1>
{* ��������λ *}
{if $removeComp == "ok"}
  <div style="margin: auto;width:800px;text-align:left;">
    <dl><dt style="font-weight:bold;color:blue;">��{$deleteMemberData.name_first}&nbsp;{$deleteMemberData.name_second}����������Ф��ޤ�����</dt></dl>
  </div>
{/if}
    <div id="header">
<!-- �ᥤ�󥳥�ƥ�� -->
      <ul id="top-navigation">
        <li class="active"><span><span>{$rarryDataArray.RARRY_SUB_NAME}&nbsp;��Ͽ����</span></span></li>
{if $readMode ne 'readOnly'}
        <li style="padding-left:250px;"><span><span><input type=button value="����������Ͽ" onclick="memberNewAndTransfer('memberRegist', 'new')" /></span></span></li>
        <li style="padding-left:10px;"><span><span><input type=button value="����������Ͽ" onclick="memberNewAndTransfer('memberTransfer2', '')" /></span></span></li>
{/if}
      </ul>
    </div>
    <div id="middle">
    {* ������������Ͽ������� *}
{if count($nextMemberDatas) > 0 }
{if count($nextMemberDatas) < 5 }
      <div style="margin:5px 0 5px 20px;color:red;text-align:left;font-size:14px;font-weight:bold;">��&nbsp;��������Ͽ������&nbsp;���Ͱʾ�&nbsp;�ۤˤ��Ƥ���������</div>
      {else}
        {if INDI_PAYMENT_FLAG }
        <div style="float:left;margin:5px 0 5px 20px;text-align:left;font-size:14px;">
        	���С���Ͽ�Կ���<span style="font-weight:bold;">{$nextMemberDatas|@count}��</span>��Ͽ<br />
        	������Ͽ�θĿ���Ͽ���Ѥϡ�&nbsp;<span style="font-weight:bold;color:red;">{$nextMemberNumMoney}&nbsp;��</span>&nbsp;�ۤȤʤ�ޤ���
        </div>
        {/if}
{/if}
    {* ������������Ͽ������� *}
      <table id="mamberDataTable">
        <thead>
        <tr>
          <th style="">���ֹ�</th>
          <th style="">̾��</th>
          <th style="">�ɤߥ���</th>
          <th style="">�ݥ������</th>
          <th style="">��Ĺ</th>
          <th style="">ǯ��</th>
          <th style="">������</th>
          <th style="">&nbsp;</th>
        </tr>
        </thead>
{if $member_discharge == false}
        <tfoot>
        <tr>
          <td colspan="8" style="padding:8px;text-align:right;font-weight:bold;">��&nbsp;��Ͽ�ѤߤΥ��С��ϡ�&nbsp;<span style="color:#0000FF;">����</span>&nbsp;�פǤ��ޤ���</td>
        </tr>
        </tfoot>
{/if}
        <tbody>
        <!-- �������°���С��ꥹ�� -->
        {section name=i start=0 loop=$nextMemberDatas}
        <tr class="{cycle values="td_w,td_g"}">
          {if $nextMemberDatas[i].number != ""}
          <td style="text-align:right;">{$nextMemberDatas[i].number}</td>
          {else}
          <td style="text-align:center;color:#FF0000;font-weight:bold;">̤</td>
          {/if}
          <td style="text-align:left;">
            {if $nextMemberDatas[i].created > $registStartDate}
            <img src="./img/new.gif" width="16" height="16" alt="��������" border="0" />
            {$nextMemberDatas[i].nameFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].nameSecond|replace:"amp;":""}
            {else}
            <span style="padding-left:20px;">{$nextMemberDatas[i].nameFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].nameSecond|replace:"amp;":""}</span>
            {/if}
          </td>
          <td style="text-align:left;">{$nextMemberDatas[i].kanaFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].kanaSecond|replace:"amp;":""}</td>
          {if $nextMemberDatas[i].posision != "̤��Ͽ"}
          <td style="text-align:left;">{$nextMemberDatas[i].posision}</td>
          {else}
          <td style="text-align:left;color:#FF0000;font-weight:bold;">{$nextMemberDatas[i].posision}</td>
          {/if}
          <td>{$nextMemberDatas[i].height}</td>
          <td>{$nextMemberDatas[i].age}</td>
          <td>{$nextMemberDatas[i].birthday}</td>
          {if $nextMemberDatas[i].discharge_date == "0000-00-00 00:00:00"}
          <td>
            <input type=button value="����" onclick="memberDetailChangeWindow('{$nextMemberDatas[i].nemberId}')" />&nbsp;
{if $readMode ne 'readOnly' and $member_discharge == true}
            <input type=button value="����" onclick="deleteMember('dischargeMember', '{$nextMemberDatas[i].nemberId}')" />
{elseif $nextMemberDatas[i].created > $registStartDate}
            <input type=button value="����" onclick="deleteMember('dischargeMember', '{$nextMemberDatas[i].nemberId}')" />
{/if}
          </td>
          {else}
          <td style="text-align:center;">���кѤ�</td>
          {/if}
        </tr>
        {if $nextMemberDatas[i].number == "" OR $nextMemberDatas[i].posision == "̤��Ͽ"}
        <tr>
          <td colspan="8" style="text-indent:10px;text-align:left;color:#0000FF;font-weight:bold;">
            <div>��
            {if $nextMemberDatas[i].number eq ""}
            �����ֹ��
            {/if}
            {if $nextMemberDatas[i].kanaFirst eq "" OR $nextMemberDatas[i].kanaSecond eq ""}
            ���ɤߥ��ʡ�
            {/if}
            {if $nextMemberDatas[i].posision eq "̤��Ͽ"}
            �֥ݥ�������
            {/if}
            ����Ͽ���Ƥ���������</div>
          </td>
        </tr>
        {/if}
        {/section}
        </tbody>
      </table>
{else}
      <div style="padding:30px 0px;">��Ͽ���꤬����ޤ���Τǿ���������Ͽ������������Ͽ�򤷤Ƥ���������</div>
{/if}
{* ����������� *}
{if count($preMemberDatas) > 0 }
      <form name="membertaking" method="post" action="teamInfo.php#registmember">
      <fieldset style="width:auto;">
      <legend>��������</legend>
      <table id="outMamberDataTable">
        <thead>
        <tr>
          <th style="">���ֹ�</th>
          <th style="">̾��</th>
          <th style="">�ɤߥ���</th>
          <th style="">�ݥ������</th>
          <th style="">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <!-- �������°���С��ꥹ�� -->
        {section name=i start=0 loop=$preMemberDatas}
        <tr>
          <td style="text-align:right;">{$preMemberDatas[i].number}</td>
          <td style="">{$preMemberDatas[i].nameFirst|replace:"amp;":""}&nbsp;{$preMemberDatas[i].nameSecond|replace:"amp;":""}</td>
          <td style="">{$preMemberDatas[i].kanaFirst|replace:"amp;":""}&nbsp;{$preMemberDatas[i].kanaSecond|replace:"amp;":""}</td>
          <td style="text-align:center;">{$preMemberDatas[i].posision}</td>
          <td style="text-align:center;">���кѤ�</td>
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
