<?php

// 携帯アクセスはエラーページに飛ばす
$agent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match("/^DoCoMo/i", $agent)){
	header("Location: ./mobile.php");
	exit;
} else if (preg_match("/^(J\-PHONE|Vodafone|MOT\-[CV]|SoftBank)/i", $agent)){
	header("Location: ./mobile.php");
	exit;
} else if (preg_match("/^KDDI\-/i", $agent) || preg_match("/UP\.Browser/i", $agent)){
	header("Location: ./mobile.php");
	exit;
}

session_start();

if (empty($_SERVER["HTTP_HOST"]) == TRUE && $_SERVER["HTTP_HOST"] != 'ligatokai.regist') {
	// SSLページに遷移
	if (!isset($_SERVER["HTTPS"]) AND ($_SERVER["HTTPS"] != 'on')) {
		header("Location: " . SERVER_HOST . "/management.liga-tokai.com/regist/login.php");
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

$_SESSION['resistMember'] = array("teamId" => "", "rarryClass" => "");
$errorValue = array("loginId" => "", "passwd" => "", "teamInfo" => "");

$ngTeam = array("");

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
//print $key." = ".encode($val)."<br>";
    }
}

if ($mode != "") {
    // フォームデータチェック
    if ($paramCheckClass->there_alnum($errorMessageObj, $loginId, 4, 10) == False) {
        $errorValue["loginId"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        $errorNums++;
    }
    if ($paramCheckClass->there_alnum($errorMessageObj, $passwd, 4, 10) == False) {
        $errorValue["passwd"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        $errorNums++;
    }

    if ($errorNums == 0) {
        // ログインチェック
        if ($userCheckClass->teamInfoLoginCheck($errorMessageObj, $loginId, $passwd) == True) {
            $teamData = $userCheckClass->getTeamDatas();
            $_SESSION['resistMember']["loginTime"] = time();
            $_SESSION['resistMember']["teamId"] = $teamData["teamId"];
            $teamId = $teamData["teamId"];
            // 大会登録チームチェック
            if ($userCheckClass->teamRarryRegistCheck($errorMessageObj, NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId) == True) {
                $teamRarryRegistData = $userCheckClass->getTeamRarryRegistDatas();
                $_SESSION['resistMember']["rarryClass"] = $teamRarryRegistData["class"];
                $_SESSION['resistMember']["registClass"] = $teamRarryRegistData["class"];
                $teamRarryClass = $teamRarryRegistData["class"];
            } else {
                //header("Location: ./loginError.php");
                //exit;
                //$errorValue["teamRarryRegist"] = $userCheckClass->getErrorMessageValue();
                //$errorNums++;
            }
            if (in_array($teamData["teamId"], $ngTeam)) {
            	header("Location: ./loginError?ngteam.php");
                exit;
            }

        } else {
        	$errorValue["teamInfo"] = "<p style=\"font-weight:bold;color:red;\">".$userCheckClass->getErrorMessageValue()."</p>";
            $errorNums++;
        }
    }
//print nl2br(print_r($errorValue,true));

    // エラーがなければチーム情報変更画面へ
    if ($errorNums == 0) {
    	header("Location: ./teamInfo.php");
        exit;
    }
}

print '<?xml version="1.0" encoding="utf-8"?>'."\n";
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
<script type="text/javascript" src="./js/jquery-1.5.1.min.js"></script>
<script type="text/javascript" src="./js/addSizes.js"></script>
<title>チーム登録ログイン画面</title>

<script language="javascript" type="text/javascript">
//<![CDATA[
<!--

  // 選手情報修正フォーム表示
  function reissueWindow() {
    subwin = window.open('./reissue.php', "reissue", "width=700,height=300,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no,location=no,directories=no");
    subwin.focus();
  }

//-->
//]]>
</script>
</head>

<body>

<div style="width:700px;margin:120px 200px;">
	<div class="teamname" style="margin-left:-30px;font-size:28px;">
		<?php echo $rarryDataArray['rarry_name']; ?>&nbsp;<?php echo $rarryDataArray['rarry_sub_name']; ?>&nbsp;<?php echo SUB_NAME; ?><br />
		<span style="font-size:24px;padding-left:50px;">チーム・メンバー情報設定</span>
	</div>
	<form name="LoginForm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
	<fieldset style="width:500px;">
	<legend style="padding:5px;">チーム・メンバー情報【確認・登録・修正】&nbsp;ログイン画面</legend>
	<?php echo $errorValue["teamInfo"]; ?>
	<table style="width:450px;">
		<tr>
			<td colspan="2"><span style="font-weight:bold;color:#282D71;">ログインIDとパスワードを入力してください。</span></td>
		</tr>
		<tr>
			<th style="width:140px;" nowrap="nowrap">ログインID</th>
			<td nowrap="nowrap" style="text-align:left;padding-left:20px;">
				<input type="text" size="30" name="loginId" value="<?php echo $loginId; ?>" />&nbsp;<?php echo $_SESSION['resistMember']["teamId"].$errorValue["loginId"]; ?>
			</td>
		</tr>
		<tr>
			<th nowrap="nowrap">パスワード</th>
			<td nowrap="nowrap" style="text-align:left;padding-left:20px;">
				<input type="password" size="30" name="passwd" />&nbsp;<?php echo $errorValue["passwd"]; ?>
			</td>
		</tr>
		<tr>
			<th>大会選択</th>
			<td>
				<select name="rid">
					<option value="34">2016-17シーズン</option>
				</select>
			</td>
		</tr>
		<tr>
		  <td colspan="2"><input type="submit" name="Submit" value="ログイン" /></td>
		</tr>
	</table>
	<div>&nbsp;</div>
	<div style="text-align:right;"><a href="#" onclick="reissueWindow()"><span style="font-size:12px;">ログインID・パスワードの確認はこちら</span></a></div>
	</fieldset>
	<input type="hidden" name="mode" value="complet" />
	</form>
	<div>
		<dl>
			<dt>マニュアル&nbsp;Download</dt>
			<dd><img src="../common/images/pdf_16.png" alt="pdf" />&nbsp;<a href="./manual/member_regist.pdf" target="_blank">操作マニュアル</a>&nbsp;<span style="font-size:85%;">(PDFファイル)</span></dd>
		</dl>
	</div>
</div>
</body>
</html>
