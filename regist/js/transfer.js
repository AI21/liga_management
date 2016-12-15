// ページネーション
// $('#memberTable').pagination({
//     items: 100,
//     itemsOnPage: 10,
//     // cssStyle: 'light-theme'
// });


// 戻りボタン処理
function pageBack(mode) {
	if (mode == "back") {
		document.fmback.action = "./teamInfo.php";
		document.fmback.mode.value = '';
		document.fmback.submit();
	}
}
// 選手情報登録・修正フォーム表示
function memberNewAndTransfer(page, mode) {
	document.fmback.action = "./" + page + ".php";
	document.fmback.mode.value = mode;
	document.fmback.submit();
}
// 選手登録
function teamPlayerRegist(mode, status, mid, name) {
    var mainTitle = $('#titleArea').text();
	myReturn = confirm(name + "さんをチーム選手に登録しますがよろしいですか？");
	if ( myReturn == true ) {
		document.fmmemberregist.memberId.value = mid;
		document.fmmemberregist.mode.value = mode;
		document.fmmemberregist.sts.value = status;
		document.fmmemberregist.submit();
	}
}
