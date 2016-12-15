
  // 戻りボタン処理
  function pageBack(mode) {
    if (mode == "back") {
      document.fmback.action = "./teamInfo.php";
      document.fmback.mode.value = '';
      document.fmback.submit();
    }
  }
  function confSubmit(mode, sts) {
    // myReturn = confirm("選手登録をしてよろしいですか？");
    // if ( myReturn == true ) {
      document.fmmember.mode.value = mode;
      document.fmmember.sts.value = sts;
      document.fmmember.submit();
    // }
  }
  // 戻りボタン処理
  function confBack() {
    document.fmmember.mode.value = 'new';
    document.fmmember.submit();
  }
  // 戻りボタン処理
  function pageTransfer() {
    document.fmback.action = "./memberTransfer.php";
    document.fmback.mode.value = '';
    document.fmback.submit();
  }
