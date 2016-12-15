<div id="mamberDataArea">
<h1>所属選手<a name="registmember">&nbsp;</a></h1>

{* 選手取り込み結果 *}
{if $mode == "memberAhead"}
	{if count($registPlayers) > 0}
	<div class="member-regist_conf">
		<dl>
			<dt style="color:blue;">下記選手の{$rarryDataArray.rarry_sub_name}選手登録が完了しました。</dt>
			{foreach from=$registPlayers key="key" item=playersData}
			<dd><span style="width:80px;">No.&nbsp;{$playersData.number}</span><span>{$playersData.firstName|replace:"amp;":""}&nbsp;{$playersData.secondName}</span></dd>
			{/foreach}
		</dl>
	</div>
	{/if}
	{if count($registSameTeamPlayers) > 0}
	<div class="member-regist_conf">
		<dl>
			<dt style="color:green;">下記選手はすでに[&nbsp;{$teamDatas.teamName|replace:"amp;":""}&nbsp;]チームで登録されています。</dt>
			{foreach from=$registSameTeamPlayers key="key" item=playersData}
			<dd><span style="width:80px;">No.&nbsp;{$playersData.number}</span><span>{$playersData.firstName}&nbsp;{$playersData.secondName}</span></dd>
			{/foreach}
		</dl>
	</div>
	{/if}
	{if count($registdiffTeamPlayers) > 0}
	<div class="member-regist_conf">
		<dl>
			<dt style="color:red;">下記選手は別チームで登録されていますので選手登録が出来ませんでした。</dt>
			{foreach from=$registdiffTeamPlayers key="key" item=playersData}
			<dd><span style="width:100px;">{$playersData.firstName|replace:"amp;":""}&nbsp;{$playersData.secondName|replace:"amp;":""}</span><span>[&nbsp;{$playersData.teamName|replace:"amp;":""}&nbsp;]で登録されています</span></dd>
			{/foreach}
		</dl>
	</div>
	{/if}
{else if $mode == "removeMember"}
	{if $removeComp == "ok"}
	<div style="text-align:left;">
		<dl>
			<dt style="color:red;">今シーズン登録選手からの削除が完了しました。</dt>
		</dl>
	</div>
	{/if}
{/if}
	<div id="main">
		<div id="header">
<!-- メインコンテンツ -->
			<ul id="top-navigation">
				<li class="active"><span><span>{$rarryDataArray.rarry_sub_name}&nbsp;登録選手</span></span></li>
				{if MEMBER_REGIST == TRUE}
				<li style="padding-left:250px;"><span><span><input type=button value="新規選手登録" onclick="memberNewAndTransfer('memberRegist', 'new')" /></span></span></li>
				<li style="padding-left:10px;"><span><span><input type=button value="選手検索・移籍登録" onclick="memberNewAndTransfer('memberTransfer', '')" /></span></span></li>
				{/if}
			</ul>
		</div>
		<div id="middle">
		{* 今シーズン登録選手一覧 *}
		{if count($nextMemberDatas) > 0 }
			{if count($nextMemberDatas) < 5 }
			<div style="margin:5px 0 5px 20px;color:red;text-align:left;font-size:14px;font-weight:bold;">※&nbsp;チーム登録選手を【&nbsp;５人以上&nbsp;】にしてください。</div>
			{else}
			<div style="margin:5px 0 5px 20px;text-align:left;font-size:14px;">
				<form name="compSendMails">
				{if $nonPaymentMembers > 0}
				<p>
					{if $rarryDataArray.type == 2}
					<span style="color:red;font-weight:bold;">登録選手合計：{$nonPaymentMembers}名となります。</span>
					{else}
					<span style="color:red;font-weight:bold;">未登録選手合計：{$nonPaymentMembers}名、個人登録費用は【&nbsp;{$nonPaymentYen|number_format}&nbsp;円&nbsp;】となります。</span><br />
					<span style="font-size:80%;">
						　※ 登録費の支払い方法については、下記「チームメンバー登録完了」ボタンを押下して頂き、折り返しの自動返信メールにて詳細をお知らせします。<br />
						　※ 背景が薄赤の選手は登録費が未清算ですので、清算完了するまでは正規登録として認められません。
					</span>
					{/if}
				</p>
				{/if}
				<fieldset><legend>登録が完了しましたら「チームメンバー登録完了」ボタンを押してください。</legend>
				<div style="float:left;padding:0.5em 1em 0;">
				<input type="button" name="compSendBtn" id="registConf" value="チームメンバー登録完了" disabled="disabled" onclick="memberRegistComplete('memberRegistComplete')" />
				</div>
				<div style="float:left;">
				<input type="checkbox" name="agreement" id="kiyakuAgreement" onclick="this.form.compSendBtn.disabled=!this.form.compSendBtn.disabled" ><label for="kiyakuAgreement" style="font-weight:bold;">チームに登録する全ての選手は「<a href="http://www.liga-tokai.com/league/rule/index/{$rarryId}" target="_blank">リーガ東海規約</a>」に同意します。</label><br /><span style="color:blue;font-size:90%;">　　※ チェックボックスをクリックしないと「チームメンバー登録完了」ボタンはクリック出来ません。</span>
				</div><br style="clear:both;" />
				{if $mode eq 'memberRegistComplete'}
				確認メールの送り先 ： {if $teamDatas.teamRepMail ne ''}<input type="checkbox" name="mail_represent" value="represent" id="represent" /><label for="represent">代表(PC)</label>{/if}
				{if $teamDatas.teamRepMobileAddress ne ''}<input type="checkbox" name="mail_represent_mobile" value="represent_mobile" id="represent_mobile" /><label for="represent_mobile">代表(携帯)</label>{/if}
				{if $teamDatas.teamSubRepMail ne ''}<input type="checkbox" name="mail_sub_represent" value="sub_represent" id="sub_represent" /><label for="sub_represent">副代表</label>{/if}
				｜ その他 </label><input type="text" name="compMailOther" size="34" value="" /><br />
				{else}
				確認メールの送り先 ： {if $teamDatas.teamRepMail ne '未登録'}<input type="checkbox" name="mail_represent" value="represent" id="represent" /><label for="represent">代表(PC)</label>{/if}
				{if $teamDatas.teamRepMobileAddress ne '未登録'}<input type="checkbox" name="mail_represent_mobile" value="represent_mobile" id="represent_mobile" /><label for="represent_mobile">代表(携帯)</label>{/if}
				{if $teamDatas.teamSubRepMail ne '未登録'}<input type="checkbox" name="mail_sub_represent" value="sub_represent" id="sub_represent" /><label for="sub_represent">副代表</label>{/if}
				｜ その他 </label><input type="text" name="compMailOther" size="34" value="" /><br />
				{/if}
				</fieldset>
				{* &nbsp;※&nbsp;登録完了後の選手追加・移籍は出来ません。<br /> *}
				&nbsp;※&nbsp;チームメンバー登録完了後、{$rarryDataArray.rarry_sub_name}に継続登録をしていない選手は全て「<span style="color:blue;font-weight:bold;">放出</span>」されます。
				{if $readMode ne 'readOnly'}
				{/if}
				</form>
			</div>
			{/if}
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
					{if MEMBER_DISCHARGE == TRUE}
					<th colspan="2" style="">登録</th>
					{else}
					<th>登録</th>
					{/if}
				</tr>
				</thead>
				<tbody>
				<!-- チーム所属メンバーリスト -->
				{section name=i start=0 loop=$nextMemberDatas}
				<tr{if $rarryDataArray.type == 1 and $nextMemberDatas[i].registPayment == 0} style="background-color:#F6CECE;"{/if}>
					{if $nextMemberDatas[i].number != ""}
					<td style="text-align:right;">{$nextMemberDatas[i].number}</td>
					{else}
					<td style="text-align:center;color:#FF0000;font-weight:bold;">未</td>
					{/if}
					<td style="">{$nextMemberDatas[i].nameFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].nameSecond|replace:"amp;":""}</td>
					<td style="">{$nextMemberDatas[i].kanaFirst|replace:"amp;":""}&nbsp;{$nextMemberDatas[i].kanaSecond|replace:"amp;":""}</td>
					{if $nextMemberDatas[i].posision != "未登録"}
					<td style="">{$nextMemberDatas[i].posision}</td>
					{else}
					<td style="text-align:center;color:#FF0000;font-weight:bold;">{$nextMemberDatas[i].posision}</td>
					{/if}
					<td>{$nextMemberDatas[i].height}</td>
					<td>{$nextMemberDatas[i].age}</td>
					<td>{$nextMemberDatas[i].birthday}</td>
					<td><input type=button value="修正" onclick="memberDetailChangeWindow('{$nextMemberDatas[i].nemberId}')" /></td>
					{if MEMBER_DISCHARGE == TRUE}
					<td><input type=button value="抹消" onclick="deleteMember('removeMember', '{$nextMemberDatas[i].nemberId}')" /></td>
					{/if}
<!--
					{if $readMode ne 'readOnly'}
					{else}
					<td colspan="2">&nbsp;</td>
					{/if}
-->
				</tr>
				{/section}
				</tbody>
			</table>
		{else}
			<div style="margin:20px 0 5px 20px;color:red;text-align:left;font-size:18px;font-weight:bold;">※&nbsp;チーム登録選手を【&nbsp;５人以上&nbsp;】にしてください。</div>
		{/if}
		<div>&nbsp;</div>
{* 前シーズン登録選手一覧 *}
{if count($preMemberDatas) > 0 }
	<div style="width:auto;">
			<form name="membertaking" method="post" action="teamInfo.php#registmember">
			<fieldset>
			<legend>{$preRarryDataArray.rarry_sub_name}&nbsp;登録選手(今シーズン未登録選手のみ表示)</legend>
			<div style="margin:3px 0;">※&nbsp;移籍選手は「放出」登録をしないと移籍先チームに登録出来ませんのでよろしくお願いいたします。</div>
			<table id="outMamberDataTable">
				<thead>
				<tr>
					{if $readMode ne 'readOnly'}<th style="">&nbsp;</th>{/if}
					<th style="">背番号</th>
					<th style="">名前</th>
					<th style="">読みカナ</th>
					<th style="">ポジション</th>
					{if $readMode ne 'readOnly'}<th style="">&nbsp;</th>{/if}
				</tr>
				</thead>
				<tbody>
				<!-- チーム所属メンバーリスト -->
				{section name=i start=0 loop=$preMemberDatas}
				<tr{if ($smarty.section.i.index % 2) > 0} style="background-color:#C4E2FF;"{/if}>
					{if $readMode ne 'readOnly'}
							{if $preMemberDatas[i].discharge_date == "0000-00-00 00:00:00"}
						<td><input type=checkbox name="aheadPlayer[]" value="{$preMemberDatas[i].nemberId}" /></td>
						{else}
						<td>&nbsp;</td>
						{/if}
					{/if}
					<td>{$preMemberDatas[i].number}</td>
					<td>{$preMemberDatas[i].nameFirst|replace:"amp;":""}&nbsp;{$preMemberDatas[i].nameSecond|replace:"amp;":""}</td>
					<td>{$preMemberDatas[i].kanaFirst|replace:"amp;":""}&nbsp;{$preMemberDatas[i].kanaSecond|replace:"amp;":""}</td>
					<td>{$preMemberDatas[i].posision}</td>
					{if $readMode ne 'readOnly'}
						{if $preMemberDatas[i].discharge_date == "0000-00-00 00:00:00"}
						<td><input type=button value="放出" onclick="deleteMember('dischargeMember', '{$preMemberDatas[i].nemberId}')" /></td>
						{else}
						<td>放出済み</td>
						{/if}
					{/if}
				</tr>
				{/section}
				</tbody>
				{if $readMode ne 'readOnly'}
				<tfoot>
				<tr>
					<td colspan="2"><input type="checkbox" id="allCheck"><label for="allCheck">全選択・全解除</label></td>
					<td><input type=button value="取り込み" onclick="memberTaking()" /></td>
					<td colspan="3" style="text-align:left;text-indent:5px;">今シーズンにそのまま選手登録する場合は取り込む選手にチェックを入れて<br />左の「取り込み」ボタンをクリックしてください。</td>
				</tr>
				</tfoot>
				{/if}
			</table>
			<input type="hidden" name="mode" value="memberAhead" />
			</fieldset>
			<input type="hidden" name="takingRarryId" value="{$rarryId}" />
			<input type="hidden" name="takingSeasonId" value="{$season}" />
			</form>
		</div>
{/if}
		</div>
	</div>
</div>
