<?php

session_start();

$loginAdminMode = '';

// SSLページに遷移
if (!isset($_SERVER["HTTPS"]) AND ($_SERVER["HTTPS"] != 'on')) {
    header("Location: " . SERVER_HOST . "/management.liga-tokai.com/regist/login_admin.php?loginAdminMode=samurai21");
}
// 管理者モードのパラメータがなしは通常画面へ
if (!isset($_POST['Submit'])) {
    if(!isset($_GET['loginAdminMode']) AND ($_GET['loginAdminMode'] == 'samurai21')) {
        header("Location: " . SERVER_HOST . "/management.liga-tokai.com/regist/login_admin.php?loginAdminMode=samurai21");
    } else {
        $loginAdminMode = htmlspecialchars($_GET['loginAdminMode'], ENT_NOQUOTES);
    }
}

// クラスファイル読み込み
/*
 * @file commons.inc          共通関数
 */
require_once "./common.inc";

// 変数初期値
#(int)$rarryId = 2;
(int)$errorNums = 0;
(int)$teamId = 0;
(string)$mode = "";
(string)$loginId = "";
(string)$passwd = "";
(string)$teamRarryClass = "";

(int)$_SESSION['resistMember'] = array("teamId" => "", "rarryClass" => "");
(string)$errorValue = array("loginId" => "", "passwd" => "", "teamInfo" => "", "teamId" => "");

(int)$ngTeam = array("");

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
//print $key." = ".encode($val)."<br>";
    }
}

unset($_SESSION['resistMember']["admin_login"]);

// 登録チーム情報の取得
if ($teamDataClass->LeagueTeam(NEXT_RARRY_ID) == true) {
    $leagueRegistTeams = $teamDataClass->getLeagueTeam();
}
//print nl2br(print_r($leagueRegistTeams,true));
if ($mode != "") {
    // フォームデータチェック
    if ($paramCheckClass->there_alnum($errorMessageObj, $teamId, 1, 3) == False) {
        $errorValue["teamId"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        $errorNums++;
    }

    if ($errorNums == 0) {
        // 大会登録チームチェック
        if ($userCheckClass->teamRarryRegistCheck($errorMessageObj, NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId) == True) {
            $teamRarryRegistData = $userCheckClass->getTeamRarryRegistDatas();
            $_SESSION['resistMember']["rarryClass"] = $teamRarryRegistData["class"];
            $_SESSION['resistMember']["registClass"] = $teamRarryRegistData["class"];
            $teamRarryClass = $teamRarryRegistData["class"];
        } else {
            $errorValue["teamInfo"] = "<p style=\"font-weight:bold;color:red;\">".$userCheckClass->getErrorMessageValue()."</p>";
            $errorNums++;
        }

        // エラーがなければチーム情報変更画面へ
        if ($errorNums == 0) {
            $_SESSION['resistMember']["loginTime"] = time();
            $_SESSION['resistMember']["teamId"] = $teamId;
            $_SESSION['resistMember']["admin_login"] = $loginAdminMode;
            header("Location: ./teamInfo.php");
            exit;
        }
    }
}
//print nl2br(print_r($errorValue,true));

print '<?xml version="1.0" encoding="utf-8"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja-JP" lang="ja-JP">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="author" content="今井厚文" />
<meta name="generator" content="php_editor" />

<link rel="stylesheet" href="./css/team.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/member.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/hiside_image.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/ranking.css" type="text/css" media="screen" />
<link rel="stylesheet" href="./css/versus.css" type="text/css" media="screen" />
<link rel="contents" href="./index.htm" />
<link rev="made" href="mailto:web@liga-tokai.com" />

<title>チーム登録ログイン画面【管理者用】</title>

<script language="javascript" type="text/javascript">
//<![CDATA[
<!--

  // 選手情報修正フォーム表示
  function reissueWindow() {
    subwin = window.open('./reissue.php', "reissue", "width=500,height=300,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no,location=no,directories=no");
    subwin.focus();
  }

//-->
//]]>
</script>
</head>

<body>

<div style="width:700px;margin:200px;">
  <div class="teamname" style="margin-left:-30px;font-size:28px;">
    <?php echo $rarryDataArray['rarry_name']; ?>&nbsp;<?php echo $rarryDataArray['rarry_sub_name']; ?>&nbsp;<?php echo SUB_NAME; ?><br />
    <span style="font-size:24px;padding-left:50px;">チーム・メンバー情報設定</span>
  </div>
  <form name=LoginForm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
  <fieldset style="width:500px;">
  <legend style="padding:5px;">チーム・メンバー情報【確認・登録・修正】&nbsp;管理者用ログイン画面</legend>
  <?php echo $errorValue["teamInfo"]; ?>
  <table style="width:450px;">
    <tr>
      <td colspan="2"><span style="font-weight:bold;color:#282D71;">チームを選択してください。</span></td>
    </tr>
    <tr>
      <th style="width:140px;" nowrap="nowrap">チーム選択</th>
      <td nowrap="nowrap" style="text-align:left;padding-left:20px;">
        <select name="teamId">
          <?php foreach($leagueRegistTeams as $leagueBlock => $data) : ?>
          <optgroup label='<?php echo $data[0]['block_name']; ?>'>
          <?php foreach($data as $key => $val) : ?>
          <option value="<?php echo $val['t_id']; ?>"><?php echo ereg_replace ('amp;', '', $val['t_name']); ?></option>
          <?php endforeach; ?>
          </optgroup>
          <?php endforeach; ?>
        </select>&nbsp;<?php echo $errorValue["teamId"]; ?>
      </td>
    </tr>
    <tr>
      <td colspan="2"><input type=submit name=Submit value="ログイン" /></td>
    </tr>
  </table>
  <div>&nbsp;</div>
  </fieldset>
  <input type="hidden" name="mode" value="complet" />
  <input type="hidden" name="loginAdminMode" value="<?php echo $loginAdminMode; ?>" />
  </form>
</div>
</body>
</html>
