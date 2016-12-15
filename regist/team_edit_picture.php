<?php
////////////////////////////////////////////////////
/*
 * チームマスタデータ追加・編集・削除
 */
////////////////////////////////////////////////////

// メールテンプレート
$mailTemplate = './mail/edit_picture.txt';
// メール設定
$sendEmail = 'web@liga-tokai.com';

(int)$mailErrorNums = 0;
(string)$pageTitle = 'チーム写真登録申請画面';
(string)$teamDataChangeScriptName = "";
(string)$rarryDetail = array();
(string)$teamDatas = array();
(string)$teamMemberDatas = array();
(string)$mode = '';
(string)$paymentChangeValue = '';
(string)$pic = array();
(string)$picture_view = '';

require_once './common.inc';
$thumbnailImage = new createThumbnailImage();

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "GET") {
    while(list ($key, $val) = each($_GET)) {
        $$key = $val;
//print $key." = ".$val."<br />";
    }
} else if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = $val;
//print $key." = ".$val."<br />";
    }
}

// 登録チーム情報
if ($teamDataClass->registTeamData($rarryId, $tid) == true) {
    $teamDatas = $teamDataClass->getTeamDatas();
}
//print nl2br(print_r($teamDatas,true));

switch ($mode) {
    case 'home' : $modeView = 'ユニフォーム画像【ホーム】'; break;
    case 'away' : $modeView = 'ユニフォーム画像【アウェイ】'; break;
    case 'other1' : $modeView = 'ユニフォーム画像【その他１】'; break;
    case 'other2' : $modeView = 'ユニフォーム画像【その他２】'; break;
    case 'other3' : $modeView = 'ユニフォーム画像【その他３】'; break;
}
// アップロードファイルを格納するファイルパスを指定
//$filename = "./tmp_contribute/" . $teamDatas["teamPhoto"];
$filename = "./tmp_contribute/" . $rarryId . "/" . $teamDatas["teamPhoto"];

if ($sts == '') {
	@unlink($filename.'_home_conf.jpg');
	@unlink($filename.'_away_conf.jpg');
	// チーム写真申請中フラグが立っていなければテンポラリ画像の削除
//	if (!$teamDatas['appliesPictureHome']) {
//		@unlink($filename.'_home_conf.jpg');
//	}
//	if (!$teamDatas['appliesPictureAway']) {
//		@unlink($filename.'_away_conf.jpg');
//	}
}

if ($_FILES AND $sts == 'conf') {

    if ($_FILES['imagefile']['error']) {
        $uploadFileError[] .= '画像が投稿されていません。';
    } else {

        $imageRate = false;

        $mime = $_FILES['imagefile']['type'];//mime type
        $path = $_FILES['imagefile']['tmp_name'];//uploaded file

        if ($mime != 'image/jpg' or $mime != 'image/jpeg' or $mime != 'image/pjpeg') {
            $uploadFileError[] .= '画像形式はJPEGのみにしてください。';
        } else {
            $extension = 'jpg';
        }

        list($width, $height, $type, $attr) = getimagesize($path);

//print nl2br(print_r($_FILES,true));
//print $width ." = WIDTH<br />";
//print $height ." = height<br />";
//print $type ." = type<br />";
//print $attr ." = attr<br />";
//print round(($width / $height), 2)." = SIZE";

        // 画像サイズチェック
        if ($_FILES['imagefile']['size'] > 500000) {
            $uploadFileError[] .= '画像サイズが大きすぎます。【&nbsp;500KB&nbsp;】以内にしてください。';
        }
        if ($width < 479) {
            $uploadFileError[] .= '画像の横幅が小さすぎます。【&nbsp;480px&nbsp;】以上にしてください。';
        }
        if ($height < 319) {
            $uploadFileError[] .= '画像の縦幅が小さすぎます。【&nbsp;320px&nbsp;】以上にしてください。';
        }
        if ($width < $height) {
            $uploadFileError[] .= '横幅が縦幅より小さいです。';
        }
        if ($width / $height === 1.5) {
            $imageRateMode = "3:2";
            $imageRate = true;
        }
        if (round(($width / $height), 2) === 1.33) {
            $imageRateMode = "4:3";
            $imageRate = true;
        }
        if ($imageRate == false) {
            $uploadFileError[] .= '横幅と縦幅を【４：３(640x480クラス)】か【３：２(720x480クラス)】の比率にしてください。';
        }
    }

    if (!isset($uploadFileError)) {
        if (is_uploaded_file($path)) {

            if ($sts == 'conf') {
                $filename = $filename.'_'.$mode.'_conf.'.$extension;
                $result = @move_uploaded_file($path, $filename);
                if (is_file($filename)) {
                    if ($imageRateMode == "3:2") {
                        // 画像サイズが720x480より大きい時は720x480に縮小
                        if ($width > 721) {
                            $thumbnailImage->load($filename);
                            $thumbnailImage->resize(720, 480);
                            $thumbnailImage->save($filename);
                        }
                    }
                    if ($imageRateMode == "4:3") {
                        // 画像サイズが640x480より大きい時は640x480に縮小
                        if ($width > 641) {
                            $thumbnailImage->load($filename);
                            $thumbnailImage->resize(640, 480);
                            $thumbnailImage->save($filename);
                        }
                    }
                    $picture_view = '<img src="./' . $filename . '" />';
                } else {
                    $uploadFileError[] .= 'アップロードに失敗しました。';
                    $sts = '';
                    $picture_view = '<img src="./team_picutre.php?rarryId=' . $rarryId . '&amp;teamId=' . $tid . '&amp;view=' . $mode . '" />';
                }
            }
        }
    } else {
        $sts = '';
        $picture_view = '<img src="./team_picutre.php?rarryId=' . $rarryId . '&amp;teamId=' . $tid . '&amp;view=' . $mode . '" />';
    }
} else {
    // 画像登録
    if ($sts == 'comp') {

        $insertPictureFlg = false;

//        if (!isset($_SESSION['resistMember']['insertPictures'])) {

            // メール送信処理
            if(file_exists($mailTemplate)){
                $mail_temp = file($mailTemplate);
                if ($mail_temp == false) {
                    $uploadFileError[] = 'システムがエラーを起こしました。メールのテンプレートファイルの読込みに失敗しました。<br />
                                            管理者までご連絡ください。<br />
                                            <a href="mailto:'.LIGA_MAIL.'">'.LIGA_MAIL.'</a>';
                    $mailErrorNums++;
                    $sts = '';
                } else {
                    (string)$mail_body = '';
                    foreach ($mail_temp as $mail_temp_col) {
                        $mail_temp_col = mb_convert_encoding($mail_temp_col, 'utf-8', 'auto');

                        // -- 文字列の置換
                        $mail_temp_col = mb_eregi_replace('__TEAM_NAME__', $teamDatas['teamName'], $mail_temp_col);
                        $mail_temp_col = mb_eregi_replace('__IMAGE_MODE__', $modeView, $mail_temp_col);

                        $mail_body .= $mail_temp_col;
                    }
                }
            } else {
                $uploadFileError[] = 'システムがエラーを起こしました。メールのテンプレートファイルが見つかりません。<br />
                                        管理者までご連絡ください。<br />
                                        <a href="mailto:'.LIGA_MAIL.'">'.LIGA_MAIL.'</a>';
                $mailErrorNums++;
                $sts = '';
            }
            // 申請フラグ更新
            if (!$teamDataChangeClass->registTeamAppliesPictureFlagChange($rarryId, $tid, $mode, true)) {
            	$uploadFileError[] = 'システムがエラーを起こしました。管理者までご連絡ください。<br />
                                        <a href="mailto:'.LIGA_MAIL.'">'.LIGA_MAIL.'</a>';
                $mailErrorNums++;
                $sts = '';
            }

            if ($mailErrorNums == 0) {
//$email = 'imai21@hotmail.co.jp';
                // メールヘッダ
                $mail_header = "From: ".$sendEmail."\n";
                $mail_header .= "Errors-To: ".LIGA_MAIL."\n";
                $mail_header .= "X-Mailer: Liga-Tokai\n";
//print $mail_header."<hr />";
//print $mail_body;
                // メール送信処理
                $mailSubject = 'チーム【'.$teamDatas['teamName'].'】から写真投稿あり';
                if (mb_send_mail(LIGA_MAIL, $mailSubject, $mail_body, $mail_header)) {
                    $sendMailFlg = true;
                    if (copy($filename.'_'.$mode.'_conf.jpg', $filename.'_'.$mode.'.jpg')) {
                    	@unlink($filename.'_'.$mode.'_conf.jpg');
                    }
                    $picture_view = '<img src="./team_picutre.php?rarryId=' . $rarryId . '&amp;teamId=' . $tid . '&amp;view=' . $mode . '" />';
                } else {
                    $uploadFileError[] = 'メール送信が失敗しました。<br />
                                        管理者までご連絡ください。<br />
                                        <a href="mailto:'.LIGA_MAIL.'">'.LIGA_MAIL.'</a>';
                    $sts = '';
                    $picture_view = '&nbsp;';
                    @unlink($filename.'_'.$mode.'.jpg');
                }
            } else {
            	@unlink($filename.'_'.$mode.'.jpg');
            }
//        }
    } else {
//        unset($_SESSION['resistMember']["insertPictures"]);
        // 投稿中の画像チェック
        if(file_exists('./tmp_contribute/' . $rarryId . '/'.$teamDatas["teamPhoto"].'_'.$mode.'.jpg')){
            $picture_view = '<div style="color:blue;font-weight:bold;">申請中画像</div>
                             <img src="./tmp_contribute/' . $rarryId . '/'.$teamDatas["teamPhoto"].'_'.$mode.'.jpg" />';
        } else {
            $picture_view = '&nbsp;';
        }
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja-JP" lang="ja-JP">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="author" content="今井厚文" />
<meta name="generator" content="php_editor" />

<link rel="stylesheet" href="./css/base.css" type="text/css" media="screen" />

<body>

<script language="javascript" type="text/javascript">
//<![CDATA[
<!--

  // 閉じるボタン処理
  function windowClose() {
    window.opener.location.reload(true);
    //window.opener.document.fmback.action = "./teamInfo.php#registmember";
    //window.opener.document.fmback.submit();
    window.close();
  }

//-->
//]]>
</script>

<div id="main">
  <div id="middle" style="width:785px;">
    <div id="center-column" style="width:780px;">
      <div class="top-bar">
        <h2>【<?php echo $teamDatas['teamName']; ?>】<?php echo $pageTitle; ?></h2>
      </div>
      <div class="select-bar">&nbsp;</div>
      <?php echo ($paymentChangeValue) ? '<div style="font-size:16px;font-weight:bold;color:blue;">'.$paymentChangeValue.'</div>' : '' ; ?>
      <div>
        <h3><?php echo $modeView; ?></h3>
        <form action="#" name="pic" method='post' enctype='multipart/form-data'>
        <table class="listing">
          <?php if ($sts == '') : ?>
          <tr>
            <td width="800">登録・変更：
              <input type="file" name='imagefile' />
              <input type="hidden" name="rarryId" value="<?php echo $rarryId; ?>" />
              <input type="hidden" name="tid" value="<?php echo $tid; ?>" />
              <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
              <input type="hidden" name="sts" value="conf" />
              <input type="submit"' name='upload' value="&nbsp;確認&nbsp;" />
              <div style="color:red;font-weight:bold;">
                ※登録申請する画像は一枚のみです。<br />
                　画像サイズは【&nbsp;480&nbsp;x&nbsp;320&nbsp;】以上で登録すること。<br />
                　720x480サイズを超える画像は720x480に変換されます。<br />
                　申請中画像の変更は可能です。
              </div>
              <?php if (isset($uploadFileError)) : ?>
              <p style="padding-left:50px;font-weight:bold;font-size:14px;text-align:left;">
                <span>投稿画像に下記エラーがあります。</span>
                <ul>
                  <?php foreach ($uploadFileError as $errVal) : ?>
                  <li style="margin-left:30px;font-weight:bold;color:red;text-align:left;font-size:16px;"><?php echo $errVal; ?></li>
                  <?php endforeach; ?>
                </ul>
              </p>
              <?php endif; ?>
            </td>
          </tr>
          <?php elseif ($sts == 'conf') : ?>
          <tr>
            <td width="800">
              下記画像で登録しますか？よろしければ登録申請ボタンをクリックしてください。<br />
              <input type="hidden" name="rarryId" value="<?php echo $rarryId; ?>" />
              <input type="hidden" name="tid" value="<?php echo $tid; ?>" />
              <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
              <input type="hidden" name="extension" value="<?php echo $extension; ?>" />
              <input type="hidden" name="sts" value="comp" />
              <input type="submit"' name='upload' value="&nbsp;上記画像で登録申請&nbsp;" />&nbsp;
              <input type="button" value="&nbsp;戻る&nbsp;" onclick="location.href='./team_edit_picture.php?rarryId=<?php echo $rarryId; ?>&amp;tid=1&amp;<?php echo $tid; ?>&amp;mode=<?php echo $mode; ?>&amp;sts='" />
            </td>
          </tr>
          <?php elseif ($sts == 'comp') : ?>
          <tr>
            <td width="800">
              <div style="width:550px;padding-left:200px;font-size:14px;font-weight:bold;color:blue;text-align:left;">
                画像登録申請が完了しました。<br />
                投稿して頂いた画像につきましては事務局チェック後、<br />
                代表者様にメールにてお知らせ致しますのでしばらくお待ちください。
              </div>
              <div>&nbsp;</div>
              <input type="button" value="&nbsp;閉じる&nbsp;" onclick="windowClose();" />
            </td>
          </tr>
          <?php endif; ?>
          <tr>
            <td><?php echo $picture_view; ?></td>
          </tr>
          <?php if ($sts == '') : ?>
          <tr>
            <td width="800">
              <input type="button" value="&nbsp;閉じる&nbsp;" onclick="window.close();" />
            </td>
          </tr>
          <?php endif; ?>
        </table>
        <input type='hidden' name='MAX_FILE_SIZE' value='1677215' />
        </form>
      </div>
    </div><?php echo "\n"/* END center-column */ ?>
  </div><?php echo "\n"/* END middle */ ?>
  <div id="footer" style="width:780px;"></div>
</div><?php echo "\n"/* END main */ ?>
<div style="crear:left;float:left;width:100%;">
<hr size="2" style="color: #ED8822; filter:alpha(opacity=100,finishopacity=0,style=3)" />
<address style="text-align:center;">Copyright &copy; 2000-<?php echo date('Y'); ?> Liga-Tokai. All rights reserved.</address>
<hr size="2" style="color: #ED8822; filter:alpha(opacity=100,finishopacity=0,style=3)" />
</div>
</body>
</html>
