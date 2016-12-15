<?php

// クラスファイル読み込み
/*
 * @file commons.inc          共通関数
 */
require_once "./common.inc";

// 変数初期値
(int)$errorNums = 0;
(int)$mailErrorNums = 0;
(string)$errorValue = array();
(string)$options = '';
(string)$teamPass = '';
(boolean)$getData = false;
(boolean)$sendMailFlg = false;
$errorValue["email"] = '';

// メール設定
$mailSubject = '【リーガ東海】ログインID・パスワード';
// $sendEmail = 'web@liga-tokai.com';
$sendEmail = 'ligatokai@gmail.com';
$passwdCsvFile = '../php_shere/liga/csv/teampasswd.csv';
$mailTemplate = './mail/reissue_tm2011.txt';
$rotocol = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://' ;
$loginAdress =  $rotocol.SERVER_HOST.'/liga-tokai.com/subdomains/management/httpsdocs/regist/login.php';
$registEmail = LIGA_MAIL;

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = encode($val);
//print $key." = ".encode($val)."<br>";
    }
}

// 大会登録チーム情報の取得
//$firstClassTeams = $rarryDataObj->rarryRegistTeam(NEXT_RARRY_ID, 1);
//$secondClassTeams = $rarryDataObj->rarryRegistTeam(NEXT_RARRY_ID, 2);
//$kabuAClassTeams = $rarryDataObj->rarryRegistTeam(NEXT_RARRY_ID, 6);
//$kabuBClassTeams = $rarryDataObj->rarryRegistTeam(NEXT_RARRY_ID, 7);
//$kabuCClassTeams = $rarryDataObj->rarryRegistTeam(NEXT_RARRY_ID, 8);


// ブロック別登録チーム情報
if ($teamDataClass->LeagueTeam(NEXT_RARRY_ID) == true) {
    $registTeamDatas = $teamDataClass->getLeagueTeam();
}
$registTeamNum = count($registTeamDatas);
//print nl2br(print_r($registTeamDatas,true));

if ($registTeamNum > 0) {
    foreach ($registTeamDatas as $classes => $dataArray) {
        $options .= '          <optgroup label="'.$dataArray[0]['block_name'].'">'."\n";
        foreach ($dataArray as $datas) {
            $tidSelected = (isset($tid) AND $tid == $datas['t_id']) ? ' selected="selected"' : '';
            $options .= '            <option value="'.$datas['t_id'].'"'.$tidSelected.'>'.ereg_replace ("amp;", "", $datas['t_name']).'</option>'."\n";
        }
        $options .= '          </optgroup>'."\n";
    }
}

if (isset($submit)) {
    (string)$teamIdPass = array();
    // フォームデータチェック
    if ($paramCheckClass->isNullCheck($errorMessageObj, $email, 6, 128) == false) {
        $errorValue["email"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        $errorNums++;
    } elseif ($paramCheckClass->isMailCheck($errorMessageObj, $email) == False) {
        $errorValue["email"] = "<br /><span style=\"font-weight:bold;color:red;\">".$paramCheckClass->getErrorMessageValue()."</span>\n";
        $errorNums++;
    }
    if ($errorNums == 0) {

        (int)$domainId = 0;
        // アドレスとドメインに分割
        list($address, $domain) = explode('@', $email);

        // 携帯ドメイン取得
        if ($teamDataClass->mobileDomainCommonData() == true) {
            $commonMobileDatas = $teamDataClass->getCommonMobileDatas();
            for ($i = 0; $i < count($commonMobileDatas); $i++) {
                if ($domain == $commonMobileDatas[$i]['domain']) {
                    $domainId = $commonMobileDatas[$i]['id'];
                    break;
                }

            }
        }

        // ID・パスワード取得
        $sql = "SELECT id, login_id, t_name, represent, sub_represent, represent_address, represent_mobile_address, represent_mobile_domain
                FROM ".dbTableName::LT_TEAM_INFO."
                WHERE `id` = ".$tid." AND ((`represent_mobile_address` = '".$address."' AND `represent_mobile_domain` = ".$domainId.") OR
                                           `represent_address` = '".$email."' OR
                                           `sub_represent_address` = '".$email."')";
//print $sql;
        $rs  = $connectDbClass->Query($sql);
        if(!$rs){ print "ID・パスワード取得エラーです。<br />".$sql."<br />"; break; }

        if($connectDbClass->GetRowCount($rs) > 0){
            $dbData = $connectDbClass->FetchRow($rs);       // １行Ｇｅｔ

            $teamId = $dbData["id"];
            $teamLoginId = $dbData["login_id"];
            $teamName = $dbData["t_name"];
            $teamRepresent = ($dbData["represent_address"] == $email
                              OR ($dbData["represent_mobile_address"] = $address AND $dbData["represent_mobile_address"] = $domainId))
                              ? $dbData["represent"] : $dbData["sub_represent"] ;

            // CSVファイル読み込み
            if (file_exists($passwdCsvFile)) {
                $fp = fopen($passwdCsvFile, 'r');
                while ($csvData = fgetcsv ($fp, 1000, ",")) {
                    $csvNum = count ($csvData);
                    for ($i=0; $i < $csvNum; $i++) {
                        // IDがHITしたパスワードをGET
                        if ($csvData[0] == $teamId) {
                            $teamPass = $csvData[2];
// print $teamId.' = '.$teamPass . "<br>";
                            break;
                        }
                    //    print $teamId.' = '.$csvData[$i] . "<br>";
                    }
                }
                fclose ($fp);
            }

            if(file_exists($mailTemplate)){
                $mail_temp = file($mailTemplate);
                if ($mail_temp == false) {
                    $errorValue["email"] = '<br /><span style=\"font-weight:bold;color:red;\">
                                            システムがエラーを起こしました。メールのテンプレートファイルの読込みに失敗しました。<br />
                                            管理者までご連絡ください。</span><br />
                                            <a href="mailto:'.LIGA_MAIL.'">'.LIGA_MAIL.'</a>';
                    $mailErrorNums++;
                } else {
                    (string)$mail_body = '';
                    foreach ($mail_temp as $mail_temp_col) {
                        $mail_temp_col = mb_convert_encoding($mail_temp_col, 'utf-8', 'auto');

                        // -- 文字列の置換
                        $mail_temp_col = mb_eregi_replace('__LOGIN_ID__', $teamLoginId, $mail_temp_col);
                        $mail_temp_col = mb_eregi_replace('__PASSWD__', $teamPass, $mail_temp_col);
                        $mail_temp_col = mb_eregi_replace('__TEAM_NAME__', $teamName, $mail_temp_col);
                        $mail_temp_col = mb_eregi_replace('__REPRESENT__', $teamRepresent, $mail_temp_col);
                        $mail_temp_col = mb_eregi_replace('__LOGIN_ADRESS__', $loginAdress, $mail_temp_col);
                        $mail_temp_col = mb_eregi_replace('__LIGA_MAIL__', LIGA_MAIL, $mail_temp_col);
                        $mail_temp_col = mb_eregi_replace('__REGIST_MAIL__', $registEmail, $mail_temp_col);

                        $mail_body .= $mail_temp_col;
                    }
                }
            } else {
                $errorValue["email"] = '<br /><span style=\"font-weight:bold;color:red;\">
                                        システムがエラーを起こしました。メールのテンプレートファイルが見つかりません。<br />
                                        管理者までご連絡ください。</span><br />
                                        <a href="mailto:'.LIGA_MAIL.'">'.LIGA_MAIL.'</a>';
                $mailErrorNums++;
            }

            if ($mailErrorNums == 0) {
//$email = 'imai21@hotmail.co.jp';

				//メールエンコーディング
				mb_language("ja");
				mb_internal_encoding("ISO-2022-JP");

				//件名エンコード
				$mailSubject = mb_convert_encoding($mailSubject, "ISO-2022-JP","utf-8");
				$mailSubject = mb_encode_mimeheader($mailSubject,"ISO-2022-JP");

				//本文エンコード
//				$mail_body = base64_encode($mail_body);
				$mail_body = mb_convert_encoding($mail_body,"ISO-2022-JP","utf-8");

                // メールヘッダ
                $mail_header  = "Content-Type: text/plain;charset=ISO-2022-JP\n";
                $mail_header .= "Content-Transfer-Encoding: 7bit\n";
                $mail_header .= "MIME-Version: 1.0\n";
                $mail_header .= "From: ".$sendEmail."\nReply-To: ".$sendEmail."\n";
                $mail_header .= "Errors-To: ".$email."\n";
//                $mail_header .= "X-Mailer:PHP/" . phpversion() . "\n";
                $mail_header .= "X-Mailer: Liga-Tokai\n";

                // メール送信処理
//                if (mb_send_mail($email, $mailSubject, $mail_body, $mail_header)) {
                if (mail($email, $mailSubject, $mail_body, $mail_header)) {
//                if (mail($registEmail, $mailSubject, $mail_body, $mail_header)) {
//print $sendEmail." = sendEmails<br />";
//print $email." = Email<br />";
//print $mail_body." = BODY<br />";
//print $mail_header." = HEADER<br />";
					$sendMailFlg = true;
                    $getData = true;
                } else {
                    $errorValue["email"] = '<br /><span style=\"font-weight:bold;color:red;\">メール送信が失敗しました。<br />
                                        管理者までご連絡ください。</span><br />
                                        <a href="mailto:'.LIGA_MAIL.'">'.LIGA_MAIL.'</a>';
                }
            }
        } else {
            $errorValue["email"] = "<br /><span style=\"font-weight:bold;color:red;\">入力のメールアドレスは登録されていません。</span>\n";
        }
//print nl2br(print_r($dbData,true));
//print $teamId." = ID<br />";
//print $teamLoginId." = LoginId<br />";
//print $teamPass." = PASSWD<br />";

        // 代表者にメールを送る
//        mail();
    }
}
//print $errorValue["email"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja-JP" lang="ja-JP">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" href="./css/base.css" type="text/css" media="screen" />

<title>リーガ東海&nbsp;ログインID・パスワード再発行フォーム</title>
</head>
<body>
<div style="text-align:center;">
  <div id="titleArea" style="font-size:18px;font-weight:bold;">リーガ東海&nbsp;ログインID・パスワード再発行画面</div>
  <?php if ($getData == false) : ?>
  <form name=LoginForm method=post action="<?php echo $_SERVER["PHP_SELF"]; ?>">
  <fieldset>
  <legend style="padding:5px;">入力フォーム</legend>
  <table style="width:450px;">
    <tr>
      <td>
        <span style="font-weight:bold;color:#282D71;">
        チーム【&nbsp;<span style="font-size:14px;color:#BF1212;">代表者もしくは副代表者</span>&nbsp;】のメールアドレスを入力してください。<br />
        照合されたチーム情報のID・パスワードをメールでお送りします。
        </span>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap" style="text-align:left;padding-left:20px;padding-top:10px;">
        <select name="tid">
<?php echo $options; ?>
        </select>
        <span style="padding-left:10px;">所属チームを選択してください。</span>
      </td>
    </tr>
    <tr>
      <td nowrap="nowrap" style="text-align:left;padding-left:20px;">
        <input type="text" size="80" name="email" />&nbsp;<?php echo $errorValue["email"]; ?>
      </td>
    </tr>
    <tr>
      <td colspan="2"><input type=submit name=submit value="送信" />　<input type=button value="&nbsp;画面を閉じる&nbsp;" onclick="window.close()" /></td>
    </tr>
  </table>
  <div>&nbsp;</div>
  </fieldset>
  </form>
  <?php else : ?>
  <fieldset>
  <legend style="padding:5px;">メール送信完了</legend>
  <div style="font-weight:bold;color:#000080;">ログインIDとパスワードを入力されたメールに送信しました。</div>
  <div style="padding:20px 0 10px 30px;;text-align:left;">文字化けなどの不具合やメールが届かない場合は「チーム名・TEL・E-MAIL」を明記の上、<br />リーガ東海事務局までご連絡ください。<br /><a href="mailto:<?php echo LIGA_MAIL; ?>"><?php echo LIGA_MAIL; ?></a></div>
  <div style="padding:5px;text-align:center"><input type=button value="&nbsp;画面を閉じる&nbsp;" onclick="window.close()" /></div>
  </fieldset>
  <?php endif; ?>
</div>
</body>
</html>