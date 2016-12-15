
// SUBMIT処理
function sendPages(mode, sts) {
  document.fmteamdata.mode.value = mode;
  document.fmteamdata.sts.value = sts;
  document.fmteamdata.submit();
}

// データ削除の確認アラート
function changeConf(mode){
  var modeView = 'チーム情報';
  //myReturn = confirm(modeView+"を変更しますがよろしいですか？");

  //if ( myReturn == true ) {
	document.fmteamdata.mode.value = mode;
	document.fmteamdata.sts.value = 'comp';
	document.fmteamdata.submit();
  //}
}
// 戻りボタン処理
function pageBack(mode) {
  if (mode == "input") {
	document.fmback.mode.value = '';
	document.fmback.submit();
  } else if (mode == "conf") {
	document.fmteams.action = "./regist.php";
	document.fmteams.mode.value = mode;
	document.fmteams.sts.value = 'input';
	document.fmteams.submit();
  } else if (mode == "comp") {
	document.fmteams.action = "./message.php";
	document.fmteams.mode.value = '';
	document.fmteams.submit();
  }
}

// 大会登録年度の選手データ切り替え
function changeRarry(rid, season) {
  document.fmrarry.rarryId.value = rid;
  document.fmrarry.season.value = season;
  document.fmrarry.submit();
}
// 前大会登録選手データ取り込み
function memberTaking() {
  myReturn = confirm("チェックした選手を今シーズン選手に取り込みますがよろしいですか？");
  if ( myReturn == true ) {
	document.membertaking.submit();
  }
}
// チーム登録選手削除の確認アラート
function deleteMember(mode, mid){
  if (mode == "removeMember") {
	  var mainTitle = $('#titleArea').text();
	  myReturn = confirm(mainTitle + "から抹消しますがよろしいですか？");
  } else if (mode == "dischargeMember") {
	  myReturn = confirm("選手を放出しますがよろしいですか？\n注：申請後は取り消し出来ませんのでご注意ください");
  }

  if ( myReturn == true ) {
	document.fmmember.action = './teamInfo.php#registmember';
	document.fmmember.mode.value = mode;
	document.fmmember.memberId.value = mid;
	document.fmmember.submit();
  }
}
// チーム選手登録完了の確認アラート
function memberRegistComplete(mode){
  document.fmmember.action = './teamInfo.php#registmember';
  document.fmmember.mode.value = mode;
  var str="";
  var objR = document.compSendMails.mail_represent;
  var objM = document.compSendMails.mail_represent_mobile;
  var objS = document.compSendMails.mail_sub_represent;
  var objO = document.compSendMails.compMailOther;
  if (objR) {if (objR.checked) {
	  str += objR.value;
  }}
  if (objM) {if (objM.checked) {
	  if( str != "" ) str=str+","; str += objM.value;
  }}
  if (objS) {if (objS.checked) {
	  if( str != "" ) str=str+","; str += objS.value;
  }}
//	alert (str);
  if (str == '' && objO.value == '') {
	  alert('一つ以上メールの配信先をチェックしてください。\n注：登録は完了されません。');
  } else if (objO.value != '' && !objO.value.match(/.+@.+\..+/)){
	  alert(objO.value+'入力のメールアドレスの形式が違います。\n注：登録は完了されません。');
  } else {
	  myReturn = confirm("選手登録を完了しますがよろしいですか？\n注：申請後はメンバー登録をしていない選手が全て「放出登録」されます。");
	  if ( myReturn == true ) {
		  document.fmmember.mailSends.value = str;
		  document.fmmember.mailSendOther.value = objO.value;
		  document.fmmember.submit();
	  }
  }
}
// 選手情報登録・修正フォーム表示
function memberNewAndTransfer(page, mode) {
  document.fmback.action = "./" + page + ".php";
  document.fmback.mode.value = mode;
  document.fmback.submit();
}
// 選手情報修正フォーム表示
function memberDetailChangeWindow(mid) {
  subwin = window.open('', "regist", "width=500,height=500,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no,location=no,directories=no")
  window.document.fmmember.action = "./memberChange.php";
  window.document.fmmember.target = "regist" ;
  window.document.fmmember.memberId.value = mid;
  window.document.fmmember.submit();
  subwin.focus();
}
// チーム写真登録画面を表示する
function pictureChange(rid, tid, mode) {
  subwin = window.open('', "pictures", "width=900,height=550,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no,location=no,directories=no")
  window.document.fmpicture.action = "./team_edit_picture.php";
  window.document.fmpicture.rarryId.value = rid;
  window.document.fmpicture.tid.value = tid;
  window.document.fmpicture.mode.value = mode;
  window.document.fmpicture.target = 'pictures';
  window.document.fmpicture.submit();
  subwin.focus();
}

//全選択・全解除をクリックしたとき
$('#allCheck, #allCheck label').click(function(){
    console.log($("input:checkbox[name='aheadPlayer']").val());
    if($("input:checkbox[name='aheadPlayer[]']").prop('checked') == false){
      $("input:checkbox[name='aheadPlayer[]']").prop({'checked':true});
    }else{
      $("input:checkbox[name='aheadPlayer[]']").prop({'checked':false});
    }
    // var items = $(this).closest('#outMamberDataTable').next().find('input');
    // console.log(items);
    // if($(this).is(':checked')) { //全選択・全解除がcheckedだったら
    //     $(items).prop('checked', true); //アイテムを全部checkedにする
    // } else { //全選択・全解除がcheckedじゃなかったら
    //     $(items).prop('checked', false); //アイテムを全部checkedはずす
    // }
});