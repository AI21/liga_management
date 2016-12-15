$(function(){

	// イベント開始
	addEvent();
	
});

function addEvent() {

	// AJAX起動時の動作
	$(document).ajaxStart(lockWindow)
				.ajaxSuccess(lockWindow)
				.ajaxStop(unlockWindow)
				.ajaxError(lockWindow)
				;
	
	$("#dialog_gameReport").css('display', 'none');
	$("#dialog-complete").css('display', 'none');
	$("#dialog-error").css('display', 'none');
	$("#dialog-incomplete").css('display', 'none');
	
	$('#gameReportBtn').click(function() {

		$("#dialog_gameReport").dialog({
			width: 800,
			height: 400,
			buttons: {
				"OK": function() {

					var tgt_higher_home = encodeURIComponent($("#txt_best_higher_home").val());
					var tgt_higher_away = encodeURIComponent($("#txt_best_higher_away").val());
					var tgt_report = encodeURIComponent($("#txt_report").val());
					var game_id = $(':hidden[name="gameId"]').val();
					//TEXTAREA文言取得
					console.log(game_id);
					console.log(tgt_higher_home);
					console.log(tgt_higher_away);
					console.log(tgt_report);

					$( this ).dialog("close");
					doExec(game_id, tgt_higher_home, tgt_higher_away, tgt_report);
				},
				"キャンセル": function(){
					console.log("キャンセル");
					$(this).dialog("close");
				}
			}
		});
	});
	var doExec = function(in_game_id, in_best_higher_home, in_best_higher_away, in_report){
		//Ajax発行
		$.ajax({
			type:"POST",
			url:'./phpProcess/setGameScore.php',
			dataType:"json",
			data:{
				mode: 'report',
				game_id: in_game_id,
				gameReport: in_report,
				bestHigherHome: in_best_higher_home,
				bestHigherAway: in_best_higher_away
			},
			success:function(data, dataType){
				if(data.result){
					//処理成功
					console.log(data.message);
					$("#dialog-complete").dialog(getDialogDispOnly()).dialog("open");
					$('#dialog-complete').dialog({
						buttons:{
							"OK": function(){
								$(this).dialog("close");
								location.reload();
							}
						}
					});
				}else{
					//処理失敗
					$("#dialog-error span.span_msg").html(data.msg);
					$("#dialog-error").dialog(getDialogDispOnly()).dialog("open");
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				console.log("処理エラー");
				$("#dialog-incomplete").dialog(getDialogDispOnly()).dialog("open");
			}
		});
	}

	$('body').click(function() {
		//$("#dialog_gameReport").dialog('close');
	});
	
	//表示専用ダイアログ(jQuery.UI)設定
	function getDialogDispOnly(){
		return {
			autoOpen: false,
			modal: true,
			resizable: false,
			buttons:{
				"閉じる": function(){
					$( this ).dialog("close");
				}
			}
		};
	}
	
	// AJAX通信中に画面をロックする
	function lockWindow(){
		$(".lockOff").addClass("lockOn")
	}
	function unlockWindow(){
		$(".lockOff").removeClass("lockOn");
	}
//	$("body").append('<div class="graylayer"></div>');
//	$(".graylayer").click(function() {
//		$(this).fadeOut();
//		$('#setGameReportWindow').fadeOut();
//	});
//	
//	$('#gameReportBtn').click(function() {
//		//var secHeight = $(".window").height() *0.7
//		//var secWidth = $(".window").width() *0.7
//		var secHeight = $(".window").height() *0.5
//		var secWidth = $(".window").width() *0.3
//		var Heightpx = secHeight * -4;
//		var Widthpx = secWidth * -1;
//		var ml = window.innderWidth * -1 / 2;
//		$(".graylayer").fadeIn();
//		$("#setGameReportWindow").fadeIn().css({
//			"margin-top" : Heightpx + "px" ,
//			"margin-left" : Widthpx + "px"
//		});
//		return false;
//	});
}

function antiwarSet() {
	for(i=0; i<2; i++) {
		document.setTeamScore.antiwar[i].disabled = !(document.setTeamScore.antiwarGames.checked);
	}
	document.setTeamScore.antiwarScore.disabled = !(document.setTeamScore.antiwarGames.checked);
}

function changeDates(cOBJ,fmName1,fmName2) {
	document.setTeamScore[fmName1].disabled = !cOBJ.checked;
	if (fmName1) document.setTeamScore[fmName1].disabled = !cOBJ.checked;
	if (fmName2) document.setTeamScore[fmName2].disabled = !cOBJ.checked;
}

// 不戦敗ゲーム登録フォームの出力切り替え
function toggleDiv(divid) {
	if(document.getElementById(divid).style.display == 'none'){
		document.getElementById(divid).style.display = 'block';
	}else{
		document.getElementById(divid).style.display = 'none';
	}
}

function sendTeam(sts) {
	document.setTeamScore.status.value = sts;
	document.setTeamScore.mode.value = 'change';
	document.setTeamScore.submit();
}

// 個人スコアのやり直し
function sendIndi(sts) {
	switch (sts) {
		case 'homeIndiDataChange' : var fm = document.setHomeIndiScore; break;
		case 'awayIndiDataChange' : var fm = document.setAwayIndiScore; break;
		default : var fm = document.setHomeIndiScore;
	}
	fm.status.value = sts;
	fm.mode.value = 'change';
	fm.submit();
}

// 個人出場チェックボックスの全選択or非選択
function allBoxChecked(check){
	for(count = 0; count < document.setHomeIndiScore.r1.length; count++){
		document.form1.r1[count].checked = check;	//チェックボックスをON/OFFにする
	}
}
