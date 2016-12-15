<?php

// スクリプトのパスをスラッシュで分割
$programs = explode("/", $_SERVER["SCRIPT_NAME"]);
// 逆順
$programs = array_reverse($programs);
// ファイル名
$programName = $programs[0];

// ファイル名をアンダーバーで分割
$belongsContents = explode("_", $programName);

(string)$naviList = "";
(string)$css = "";
//print nl2br(print_r($programs,true));

// コンテンツ
$navigation = array(
                     "大会編集" => "main_schejule.php",
                     "マスター編集" => "master_team.php"
                   );

// リンクタグの生成
foreach ($navigation AS $key => $val) {
    if (preg_match("/$belongsContents[0]/", $val)) {
        $css = " class=\"active\"";
    } else {
        $css = "";
        if ($belongsContents[0] == "main" AND $key == "Home") {
            $css = " class=\"active\"";
        }
    }
    $naviList .= '        <li' . $css . '><span><span><a href="./' . $val . '" onclick="setnavi(' . $val . ')">' . $key . '</a></span></span></li>'."\n";
}

?>
  <div id="header">
    <a href="index.php" class="logo"><img src="img/logo.gif" width="101" height="29" alt="" /></a>
    <ul id="top-navigation">
<?php print $naviList; ?>
    </ul>
  </div>
