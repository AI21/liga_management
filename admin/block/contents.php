<?php

// 登録してある大会情報の取得
if ($rarryDataObj->rarryReagueHistory() == True) {
    $rarryDataArray = $rarryDataObj->getRarryReagueHistory();
}
//print nl2br(print_r($rarryDataArray,true));

// スクリプトのパスをスラッシュで分割
$programs = explode("/",$_SERVER["SCRIPT_NAME"]);
// 逆順
$programs = array_reverse($programs);
// ファイル名
$programName = $programs[0];

// ファイル名をアンダーバーで分割
$belongsContents = explode("_", $programName);

$viewContents = $belongsContents[0];
if ($viewContents == 'index.php') {
    $viewContents = 'main';
}

(string)$contentsList = "";

// コンテンツ設定
$contentsLists = array(
                  'main' 	=> array(
                                  "スケジュール編集" => "main_schejule.php",
                                  "参加チーム編集" => "main_registteam.php",
                                  "大会設定編集" => "main_rarry.php",
                                  ),
                  'master' 	=> array(
                                  "大会設定" => "master_rarry.php",
                                  "チーム情報編集" => "master_team.php",
                                  "選手情報編集" => "master_player.php",
                                  "選手マスタ情報設定" => "master_playersetting.php",
                                  "大会クラス設定" => "master_rarryclass.php",
                                  "会場設定" => "master_hall.php",
                                  ),
                  );

$contents = $contentsLists[$viewContents];

// リンクタグの生成
foreach ($contents AS $key => $val) {
    ($val == $programName) ? $css = " class=\"active\"" : $css = "";
    $contentsList .= '        <li><a href="' . $val . '"' . $css . '>' . $key . '</a></li>'."\n";
}

?>
    <div id="left-column">
      <?php if ($viewContents != 'master') :?>
      <h3>SEASON SELECT</h3>
      <form name="fmseasonchange" method="post" action="#">
      <ul class="nav">
        <li>
          <select name="serchMode" onchange="location.href=fmseasonchange.serchMode.options[document.fmseasonchange.serchMode.selectedIndex].value">
<?php
$breakParentId2 = '';
// シーズン選択フォームタグの生成
for ($i=0; $i<count($rarryDataArray); $i++) {
	$breakParentId = $rarryDataArray[$i]["define"];
    if ($i > 0 AND $breakParentId != $breakParentId2) {
print <<<EOF
            </optgroup>

EOF;
	}
	if ($breakParentId != $breakParentId2) {
print <<<EOF
            <optgroup label="{$rarryDataArray[$i]["rarrySubName"]}">

EOF;
	}
	if ($rarryDataArray[$i]["rarryId"] == $_SESSION["rarryId"]) {
print <<<EOF
            <option value="index.php?rarryId={$rarryDataArray[$i]["rarryId"]}" selected="selected">{$rarryDataArray[$i]["rarrySubName"]}</option>

EOF;
    } else {
print <<<EOF
            <option value="index.php?rarryId={$rarryDataArray[$i]["rarryId"]}">{$rarryDataArray[$i]["rarrySubName"]}</option>

EOF;
    }
	$breakParentId2 = $rarryDataArray[$i]["define"];
}
?>
            </optgroup>
          </select>
        </li>
      </ul>
      </form>
      <?php endif; ?>
      <h3>Header</h3>
      <ul class="nav">
<?php print $contentsList; ?>
      </ul>
      <a href="http://www.liga-tokai.com/" target="_blank" class="link">リーガ東海HOME</a>
      <a href="http://www.liga-tokai.com/league/" target="_blank" class="link">リーガ東海[LEAGUE]</a>
      <a href="http://www.liga-tokai.com/league/m/" target="_blank" class="link">Mobile&nbsp;HOME</a>
      <a href="http://www.liga-tokai.com/league/blog/" target="_blank" class="link">リーガBLOG</a>
      <a href="http://www.liga-tokai.com/league/blog/wp-login.php" target="_blank" class="link">リーガBLOG管理</a>
      <a href="http://www.liga-tokai.com/3on3/" target="_blank" class="link">LIGATOKAI 3on3</a>
      <a href="http://www.liga-tokai.com/3on3/wp/wp-login.php" target="_blank" class="link">3on3管理</a>
<!--
      <a href="http://tm2011.liga-tokai.com/" target="_blank" class="link">2011トーナメント</a>
      <a href="http://tm2011.liga-tokai.com/wp-login.php" target="_blank" class="link">トーナメント管理</a>
      <a href="http://faq.liga-tokai.com/" target="_blank" class="link">FAQ</a>
      <a href="http://faq.liga-tokai.com/admin.php" target="_blank" class="link">FAQ管理</a>
-->
      <a href="https://liga-tokai-com.ssl-netowl.jp/management.liga-tokai.com/regist/login_admin.php?loginAdminMode=samurai21" target="_blank" class="link">チーム情報変更画面</a>
      <a href="https://liga-tokai-com.ssl-netowl.jp/management.liga-tokai.com/regist/login.php" target="_blank" class="link">チーム情報変更(ユ)</a>
      <a href="https://sv13.firebird.netowl.jp/phpmyadmin/" target="_blank" class="link">データベース管理</a>
      <a href="https://secure.netowl.jp/netowl/?service=firebird" target="_blank" class="link">ドメイン管理</a>
      <a href="http://demo.liga-tokai.com/" target="_blank" class="link">リーガ東海デモサイト</a>
      <a href="http://demo.liga-tokai.com/m/" target="_blank" class="link">Mobileデモサイト</a>
    </div>
