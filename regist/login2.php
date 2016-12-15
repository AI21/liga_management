<?php

session_start();

print nl2br(print_r($_SERVER, true));
exit;

// SSLページに遷移
if (!isset($_SERVER["HTTPS"]) AND ($_SERVER["HTTPS"] != 'on')) {
    header("Location: https://management.liga-tokai.com/regist/login2.php");
}

// クラスファイル読み込み
/*
 * @file commons.inc          共通関数
 */
require_once "./common2.inc";

// 変数初期値
#(int)$rarryId = 2;
(int)$errorNums = 0;
(int)$teamId = 0;
(string)$mode = "";
(string)$loginId = "";
(string)$passwd = "";
(string)$teamRarryClass = "";

(int)$_SESSION['resistMember'] = array("teamId" => "", "rarryClass" => "");
(string)$errorValue = array("loginId" => "", "passwd" => "", "teamInfo" => "");

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
#print $key." = ".encode($val)."<br>";
    }
}

if ($mode != "") {
    // フォームデータチェック
    if ($paramCheckClass->there_alnum($errorMessageValues, $loginId, 4, 8) == False) {
        $errorValue["loginId"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        $errorNums++;
    }
    if ($paramCheckClass->there_alnum($errorMessageValues, $passwd, 4, 8) == False) {
        $errorValue["passwd"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        $errorNums++;
    }

    if ($errorNums == 0) {
        // ログインチェック
        if ($userCheckClass->teamInfoLoginCheck($errorMessageValues, $loginId, $passwd) == True) {
            $teamData = $userCheckClass->getTeamDatas();
            //$_SESSION['resistMember']["loginTime"] = time();
            //$_SESSION['resistMember']["teamId"] = $teamData["teamId"];
            $teamId = $teamData["teamId"];
            // 大会登録チームチェック
            if ($userCheckClass->teamRarryRegistCheck($errorMessageValues, NEXT_RARRY_ID, NEXT_RARRY_SEASON, $teamId) == True) {
                $teamRarryRegistData = $userCheckClass->getTeamRarryRegistDatas();
                //$_SESSION['resistMember']["rarryClass"] = $teamRarryRegistData["class"];
                $teamRarryClass = $teamRarryRegistData["class"];
            } else {
                header("Location: ./loginError2.php");
                exit;
                //$errorValue["teamRarryRegist"] = $userCheckClass->getErrorMessageValue();
                //$errorNums++;
            }
        } else {
            $errorValue["teamInfo"] = "<p style=\"font-weight:bold;color:red;\">".$userCheckClass->getErrorMessageValue()."</p>";
            $errorNums++;
        }
    }
    //
    // エラーがなければチーム情報変更画面へ
    if ($errorNums == 0) {
        header("Location: ./complete.php?tid=".$teamData["teamId"]);
        exit;
    }
}

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

<title>リーガ東海&nbsp;2009シーズン&nbsp;チーム情報確認[ログイン画面]</title>
</head>

<body>

<div style="width:460px;margin:200px;">

<div class="teamname" style="font-size:28px;">リーガ東海&nbsp;2009SEASON&nbsp;LEAGUE</div>

  <fieldset>
  <legend accesskey="t">リーガ東海&nbsp;2009シーズン&nbsp;チーム情報確認画面ログイン</legend>
  <?php echo $errorValue["teamInfo"]; ?>
  <table style="width:430px;">
    <form name=LoginForm method=post action="<?php echo $_SERVER["PHP_SELF"];?>">
    <tr>
      <td style="width:140px;">&nbsp;</td>
      <td><span style="font-weight:bold;">ログインIDとパスワードを入力してください。</span><br></td>
    </tr>
    <tr>
    <tr>
      <td nowrap>ログインID</td>
      <td nowrap style="text-align:left;padding-left:20px;">
        <input type="text" size="30" name="loginId" value="<?php echo $loginId; ?>" />&nbsp;<?php echo $_SESSION['resistMember']["teamId"].$errorValue["loginId"]; ?>
      </td>
    </tr>
    <tr>
      <td nowrap>パスワード</td>
      <td nowrap style="text-align:left;padding-left:20px;">
        <input type="password" size="30" name="passwd" />&nbsp;<?php echo $errorValue["passwd"]; ?>
      </td>
    </tr>
    <tr>
      <td></td><td><input type=submit name=Submit value="ログイン"></td>
    </tr>
    <input type="hidden" name="mode" value="complet">
    </form>
  </table>
  </fieldset>
</div>

</body>
</html>
