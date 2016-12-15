<?php
////////////////////////////////////////////////////
/*
 * チームマスタデータ追加・編集・削除
 */
////////////////////////////////////////////////////

// ヘッダー読み込みCSSファイル設定
$readCssFile = array('all', 'style');
// ヘッダー読み込みjavascriptファイル設定
$readJsFile = array('prototype', 'tablekit/fastinit', 'tablekit/tablekit');

// メールテンプレート
$mailTemplate = './mail/contribute_picture.txt';
// メール設定
$sendEmail = 'web@liga-tokai.com';
$masterEmail = 'liga_tokai2009@yahoo.co.jp';

(string)$pageTitle = 'チーム写真編集';
(string)$teamDataChangeScriptName = "";
(string)$rarryDetail = array();
(string)$teamDatas = array();
(string)$teamMemberDatas = array();
(string)$mode = '';
(string)$paymentChangeValue = '';
(string)$pic = array();
(string)$picture_view = '';
(string)$contributeResult = '';
(string)$contributeComment = '';
(string)$mailError = '';
(string)$stsComment = '';
(string)$contribute = '';
(boolean)$imageDatas = false;

require_once './common.inc';
$thumbnailImage = new createThumbnailImage();

// パラメータの環境変数
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// クライアントからPOSTで受取ったデータを変数に落とす
if ($strRequestMethod == "GET") {
    while(list ($key, $val) = each($_GET)) {
        $$key = $val;
    }
} else if ($strRequestMethod == "POST") {
    while(list ($key, $val) = each($_POST)) {
        $$key = $val;
//print $key." = ".$val."<br />";
    }
}
//print nl2br(print_r($_SESSION,true));

// 大会情報の取得
if ($rarryDataObj->rarryDetails($_SESSION["rarryId"]) == true) {
    $rarryDetail = $rarryDataObj->getRarryDetail();
}
//print nl2br(print_r($rarryDetail,true));

// 登録チーム情報
if ($teamDataObj->registTeamData($_SESSION["rarryId"], $tid) == true) {
    $teamDatas = $teamDataObj->getTeamDatas();
}
//print nl2br(print_r($teamDatas,true));

switch ($mode) {
    case 'home' : $modeView = 'チーム画像【ホーム】'; break;
    case 'away' : $modeView = 'チーム画像【アウェイ】'; break;
    case 'other1' : $modeView = 'チーム画像【その他１】'; break;
    case 'other2' : $modeView = 'チーム画像【その他２】'; break;
    case 'other3' : $modeView = 'チーム画像【その他３】'; break;
}
// アップロードファイルを格納するファイルパスを指定
$filename = "tmp/" . $teamDatas["teamPhoto"];

if ($_FILES AND $sts == 'conf') {

    if ($_FILES['imagefile']['error']) {
        $uploadFileError[] .= '画像が投稿されていません。';
    } else {

        $imageRate = false;

        $mime = $_FILES['imagefile']['type'];//mime type
        $path = $_FILES['imagefile']['tmp_name'];//uploaded file

        if ($mime != 'image/jpeg') {
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
//print round(($width / $height), 2)." = SIZE<br />";

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
/*
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
*/
    }

    if (!isset($uploadFileError)) {
        if (is_uploaded_file($path)) {

            if ($sts == 'conf') {
                $result = @move_uploaded_file($path, $filename.'.'.$extension);
                if (is_file($filename . '.' . $extension)) {
                    if ($imageRateMode == "3:2") {
                        // 画像サイズが720x480より大きい時は720x480に縮小
                        if ($width > 961) {
                            $thumbnailImage->load($filename.'.'.$extension);
                            $thumbnailImage->resize(960, 640);
                            $thumbnailImage->save($filename.'.'.$extension);
                        }
                    }
                    if ($imageRateMode == "4:3") {
                        // 画像サイズが640x480より大きい時は640x480に縮小
                        if ($width > 961) {
                            $thumbnailImage->load($filename.'.'.$extension);
                            $thumbnailImage->resize(960, 640);
                            $thumbnailImage->save($filename.'.'.$extension);
                        }
                    }
                    $picture_view = '<img src="./' . $filename . '.' . $extension . '" />';
                } else {
                    $uploadFileError[] .= 'アップロードに失敗しました。';
                    $sts = '';
                    $picture_view = '<img src="./main_registteam_picutre.php?tid=' . $tid . '&amp;view=' . $mode . '" />';
                }
            }
        }
    } else {
        $sts = '';
        $picture_view = '<img src="./main_registteam_picutre.php?tid=' . $tid . '&amp;view=' . $mode . '" />';
    }
} else {
    // 画像登録
    if ($sts == 'comp') {

        $insertPictureFlg = false;
        $insertThumbFlg = false;

        if (!isset($_SESSION['insertPictures'])) {

            // 投稿画像の時
            if ($contribute != '') {
                $imagePictureFile = '../regist/tmp_contribute/'.$_SESSION["rarryId"].'/' . $teamDatas['teamPhoto'] . '_' . $mode . '.jpg';
                $imageThumbnialFile = $filename.'_thumb.'.$extension;
            } else {
                $imagePictureFile = $filename.'.'.$extension;
                $imageThumbnialFile = $filename.'_thumb.'.$extension;
            }

            $thumbnailImage->load($imagePictureFile);
            $thumbnailImage->resize(400, 300);
            $thumbnailFile = $thumbnailImage->save($imageThumbnialFile);

            $vinaryData = addslashes(file_get_contents($imagePictureFile));//一時ファイルの読み込み
            $thumbVinaryData = addslashes(file_get_contents($imageThumbnialFile));//一時ファイルの読み込み

            // 拡大画像の登録
            if (!isset($modeError) AND $teamDataChangeObj->registTeamPictureChange($_SESSION["rarryId"], $tid, 'picture_'.$mode, $vinaryData) == true) {
                $insertPictureFlg = true;
                // テンポラリ画像の消去
                @unlink($imagePictureFile);
            }
            // サムネイル画像の登録
            if (!isset($modeError) AND $teamDataChangeObj->registTeamPictureChange($_SESSION["rarryId"], $tid, 'thumbnail_'.$mode, $thumbVinaryData) == true) {
                $insertThumbFlg = true;
                // テンポラリ画像の消去
                @unlink($imageThumbnialFile);
            }

            if ($insertPictureFlg AND $insertThumbFlg) {
                $_SESSION['insertPictures'] = true;
                // 投稿画像の時は登録完了メールの送信
                if (!isset($_SESSION['sendMail'])) {
                    if ($contribute != '') {
                        $contributeResult = '採用';
                        $contributeComment = '画像チェック並びに画像登録が出来ました。
　チーム詳細画面にてご確認ください。';

                        // メール送信処理
                        if (($sendMails = sendMail($contributeResult, $contributeComment)) != '') {
                            $uploadFileError[] .= $sendMails;
                            $sts = '';
                        } else {
                            $_SESSION['sendMail'] = 'send';
                        }
                    }
                }
            }
        }
        $stsComment = '画像登録が完了しました。';
        $picture_view = '<img src="./main_registteam_picutre.php?tid=' . $tid . '&amp;view=' . $mode . '" />';
    } elseif ($sts == 'deleteConf') {
        // 表示画像
        $picture_view = '<img src="./main_registteam_picutre.php?tid=' . $tid . '&amp;view=thumb_' . $mode . '" />';
    } elseif ($sts == 'deleteComp') {

        // メイン画像の削除処理
        if (!isset($modeError) AND $teamDataChangeObj->registTeamPictureChange($_SESSION["rarryId"], $tid, 'picture_'.$mode) == true) {
            $deletePictureFlg = true;
        }
        // サムネイル画像の削除処理
        if (!isset($modeError) AND $teamDataChangeObj->registTeamPictureChange($_SESSION["rarryId"], $tid, 'thumbnail_'.$mode) == true) {
            $deleteThumbFlg = true;
        }

        if ($deletePictureFlg AND $deleteThumbFlg) {
            $stsComment = '画像をデータベースより削除しました。';
            $picture_view = '画像が登録されていません。';
        } else {
            $stsComment = '画像を削除に失敗しました。';
            $picture_view = '<img src="./main_registteam_picutre.php?tid=' . $tid . '&amp;view=' . $mode . '" />';
        }
        $sts = '';
    } elseif ($sts == 'confNG') {

        // チーム代表者に不採用メール送信
        if (!isset($_SESSION['sendMail'])) {

            $contributeResult = '不採用';
            $contributeComment = '画像チェックをしましたが、
　画像登録・掲載の許可が出来ませんでしたので
　今回は見送らさせて頂きました。';

            // メール送信処理
            if (($sendMails = sendMail($contributeResult, $contributeComment)) != '') {
                $uploadFileError[] .= $sendMails;
                $sts = '';
            } else {
                $_SESSION['sendMail'] = 'send';
                $stsComment = '代表者に画像掲載の不採用通知を致しました。';
                // テンポラリ画像の消去
                @unlink('../regist/tmp_contribute/'.$_SESSION["rarryId"].'/' . $teamDatas['teamPhoto'] . '_' . $mode . '.jpg');
            }
        }
    } else {
        // テンポラリ画像の消去
        @unlink($filename.'.jpg');
        @unlink($filename.'.png');
        @unlink($filename.'.gif');
        unset($_SESSION["insertPictures"]);
        unset($_SESSION["sendMail"]);
        // 申請中の投稿画像チェックモード
        if ($contribute != '') {
            $picture_view = '<img src="../regist/tmp_contribute/'.$_SESSION["rarryId"].'/' . $teamDatas['teamPhoto'] . '_' . $mode . '.jpg" />';
            $sts = 'conf';
        } else {
            $picture_view = '<img src="./main_registteam_picutre.php?tid=' . $tid . '&amp;view=' . $mode . '" />';
        }
    }
    if ($contribute == '') {
        // メイン画像の捜査
        if ($teamDataObj->selectTeamPicture($_SESSION["rarryId"], $tid, $mode) == true) {
            $imageDatas = true;
        }
        // サムネイル画像の捜査
        if ($teamDataObj->selectTeamPicture($_SESSION["rarryId"], $tid, 'thumb_'.$mode) == true) {
            $imageDatas = true;
        }
        if ($imageDatas == false) {
            $picture_view = '画像が登録されていません。';
        }
    }
}

if ($contribute != '') {
    $pageTitle .= '「投稿画像チェック」';
    if ($sts == 'conf') {
        unset($_SESSION["sendMail"]);
        $stsComment = '下記画像が投稿されています。';
    }
}

?>
<?php
// HTMLヘッダ読み込み
include_once "block/header.php"; ?>
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

  // 画像の削除処理
  function pictureDelete(sts) {
    document.fmdelete.sts.value = sts;
    document.fmdelete.submit();
  }

  // 投稿画像が不採用時の処理
  function pictureContributeNG() {
    var pic;
    document.pic.sts.value = 'confNG';
    document.pic.submit();
  }

//-->
//]]>
</script>

<div id="main">
  <div id="middle" style="width:785px;">
    <div id="center-column" style="width:780px;">
      <div class="top-bar">
        <h1>【<?php echo $teamDatas['teamName']; ?>】<?php echo $pageTitle; ?></h1>
      </div>
      <div class="select-bar">&nbsp;</div>
      <?php echo ($paymentChangeValue) ? '<div style="font-size:16px;font-weight:bold;color:blue;">'.$paymentChangeValue.'</div>' : '' ; ?>
      <div>
        <h3>チーム登録写真</h3>
        <?php if ($stsComment != '') : ?>
        <p style="font-weight:bold;color:blue;font-size:14px;"><?php echo $stsComment; ?></p>
        <?php endif; ?>
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
        <form action="#" name="pic" method='post' enctype='multipart/form-data'>
        <table class="listing">
          <tr>
            <th><?php echo $modeView; ?></th>
          </tr>
          <?php if ($sts == '') : ?>
          <tr>
            <td width="800">登録・変更：
              <input type="file" name='imagefile' />
              <input type="hidden" name="tid" value="<?php echo $tid; ?>" />
              <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
              <input type="hidden" name="sts" value="conf" />
              <input type="hidden" name="del" value="" />
              <input type="submit"' name='upload' value="&nbsp;確認&nbsp;" />
              <div style="color:red;font-weight:bold;">
                ※画像サイズは【&nbsp;480&nbsp;x&nbsp;320&nbsp;】以上で登録すること。<br />
                　960x640サイズを超える画像は960x640に変換されます。
              </div>
              <?php if ($imageDatas) : ?>
              <input type="button"' value="&nbsp;画像を削除する&nbsp;" onclick="pictureDelete('deleteConf');" />
              <?php endif; ?>
            </td>
          </tr>
          <?php elseif ($sts == 'comp') : ?>
          <tr>
            <td width="800">
              <input type="button" value="&nbsp;閉じる&nbsp;" onclick="windowClose();" />
            </td>
          </tr>
          <?php endif; ?>
          <tr>
            <td>
              <?php if ($sts == 'deleteConf') : ?>
              画像を削除してもよろしいでしょうか？<br />
              <?php echo $picture_view; ?><br />
              <input type="button"' value="&nbsp;画像を完全削除する&nbsp;" onclick="pictureDelete('deleteComp');" />
              <p><input type="button" value="&nbsp;閉じる&nbsp;" onclick="window.close();" /></p>
              <?php else : ?>
              <?php echo $picture_view; ?>
              <?php endif; ?>
            </td>
          </tr>
          <?php if ($sts == 'conf') : ?>
          <tr>
            <td width="800">
              <input type="hidden" name="tid" value="<?php echo $tid; ?>" />
              <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
              <input type="hidden" name="extension" value="<?php echo $extension; ?>" />
              <input type="hidden" name="sts" value="comp" />
              <input type="hidden" name="contribute" value="<?php echo $contribute; ?>" />
              <?php if ($contribute != '') : ?>
              <input type="submit"' name='upload' value="&nbsp;上記画像でＯＫ&nbsp;" />&nbsp;
              <input type="button"' value="&nbsp;不採用&nbsp;" onclick="pictureContributeNG();" />
              <?php else : ?>
              <input type="submit"' name='upload' value="&nbsp;上記画像で登録&nbsp;" />
              <input type="button" value="&nbsp;戻る&nbsp;" onclick="history.back(); return false;" />
              <?php endif; ?>
            </td>
          </tr>
          <?php endif; ?>
          <?php if ($sts == '' OR $sts == 'confNG') : ?>
          <tr>
            <td width="800">
              <input type="button" value="&nbsp;閉じる&nbsp;" onclick="windowClose();" />
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
<!-- 画像削除用フォームデータ -->
<form name="fmdelete" method="post" action="#">
  <input type="hidden" name="tid" value="<?php echo $tid; ?>" />
  <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
  <input type="hidden" name="sts" value="" />
</form>
<?php
// HTMLフッター読み込み
include_once "block/footer.php"
?>
<?php
// メール送信処理
function sendMail($contributeResult, $contributeComment) {

    global $mailTemplate;
    global $teamDatas;
    global $sendEmail;
    global $masterEmail;

    if(file_exists($mailTemplate)){
        $mail_temp = file($mailTemplate);
        if ($mail_temp == false) {
            $mailError = 'システムがエラーを起こしました。メールのテンプレートファイルの読込みに失敗しました。';
            return $mailError;
        } else {
            (string)$mail_body = '';
            foreach ($mail_temp as $mail_temp_col) {
                $mail_temp_col = mb_convert_encoding($mail_temp_col, 'utf-8', 'auto');

                // -- 文字列の置換
                $mail_temp_col = mb_eregi_replace('__TEAM_NAME__', $teamDatas['teamName'], $mail_temp_col);
                $mail_temp_col = mb_eregi_replace('__REPRESENT__', $teamDatas['teamRep'], $mail_temp_col);
                $mail_temp_col = mb_eregi_replace('__CONTRIBUTE__', $contributeResult, $mail_temp_col);
                $mail_temp_col = mb_eregi_replace('__CONTRIBUTE_COMMENT__', $contributeComment, $mail_temp_col);
                $mail_temp_col = mb_eregi_replace('__LIGA_MAIL__', $masterEmail, $mail_temp_col);

                $mail_body .= $mail_temp_col;
            }
        }
    } else {
        $mailError = 'システムがエラーを起こしました。メールのテンプレートファイルが見つかりません。';
        return $mailError;
    }

    // 送り先
    if ($teamDatas['teamRepMobileAddress'] != '') {
        $email = $teamDatas['teamRepMobileAddress'].'@'.$teamDatas['teamRepMobileDomain'];
    } else {
        $email = $teamDatas['teamRepMail'];
    }
//    $email = 'imai21@hotmail.co.jp';
    // メールヘッダ
    $mail_header = "From: ".$masterEmail."\n";
    $mail_header .= "Errors-To: ".$sendEmail."\n";
    $mail_header .= "X-Mailer: Liga-Tokai\n";
    // メール送信処理
    $mailSubject = '【リーガ東海】チーム投稿写真の採用結果';
    if (mb_send_mail($email, $mailSubject, $mail_body, $mail_header)) {
        return '';
    } else {
        $mailError = 'メール送信が失敗しました。';
        return $mailError;
    }

}

?>