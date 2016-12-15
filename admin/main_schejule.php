<?php

    // ヘッダー読み込みCSSファイル設定
    $readCssFile = array('admin_schejule', 'calen_input/calendar');
    // ヘッダー読み込みjavascriptファイル設定
    $readJsFile = array('calen_input/calendar');

    require_once './common.inc';

    // 初期値
    (string)$pageTitle = "スケジュール登録";
    (string)$fmError = True;
    (string)$errorValue = "";
    (string)$scriptName = "main_schejule.php";
    (string)$insAgain = "";
    (string)$mode = "";
    (string)$fmBlock = "";
    (string)$sts = "";
    (string)$fmBlockOption = "";
    (string)$serchMode = "";
    (string)$selectCortDatas = "";
    (string)$selectHallForm = "";
    (string)$rarryEndFlag = '';
    (int)$games = 0;
    (int)$team_id = 0;
    (int)$serch_month = 0;
    (int)$class = 0;
    (int)$month = date("m");
    (int)$days = date("j");
    (int)$block = 1;
    (int)$fmHall = 0;
    (int)$fmCort = 0;
    (int)$insYear = NULL;
    (int)$insMonth = NULL;
    (int)$insDays = NULL;
    (int)$scoreupSelectYear = NULL;
    (int)$scoreupSelectMonth = NULL;
    //(int)$rarryId = 1;
    (int)$classId = 3;
    (string)$selectSchejuleData = array();
    (int)$selectSchejuleDataNum = 0;

    // パラメータの環境変数
    $strRequestMethod = $_SERVER["REQUEST_METHOD"];

    // クライアントからPOSTで受取ったデータを変数に落とす
    if ($strRequestMethod == "GET") {
        while(list ($key, $val) = each($_GET)) {
            $$key = $val;
            if ($key == "rarryId") {
                $_SESSION["rarryId"] = $val;
            }
//print $key." = ".$val."<br />";
        }
    } else if ($strRequestMethod == "POST") {
        while(list ($key, $val) = each($_POST)) {
            $$key = $val;
//print $key." = ".$val."<br />";
        }
    }

    // 登録してある大会情報の取得
    if ($rarryDataObj->rarryReagueHistory() == True) {
        $rarryDatas = $rarryDataObj->getRarryReagueHistory();
    }

    if (!isset($_SESSION["rarryId"])) {
        $_SESSION["rarryId"] = $rarryDatas[0]["rarryId"];
    }

    if (count($rarryDatas) > 0) {
        foreach ($rarryDatas as $key => $rarryDetailDatas) {
            for ($i = 0; $i < count($key); $i++) {
                if ($rarryDetailDatas['rarryId'] == $_SESSION["rarryId"]) {
                    $rarryLookDatas = $rarryDetailDatas;
                }
            }
        }
    }

    if ($mode == "new" OR $mode != "fmErr") {
	//session_destroy();
         //unset($_SESSION["schejuleInsertData"]);
         $_SESSION["schejuleInsertData"] = array();
         unset($_SESSION["schejuleSet"]["ok"]);  // スケジュール登録完了フラグ
    } else {
        if ($_SESSION["schejuleInsertData"]["mainData"]["mode"] == "fmError") {
            $mode = "submit";
            $insYear = $_SESSION["schejuleInsertData"]["mainData"]["setYear"];
            $insMonth = $_SESSION["schejuleInsertData"]["mainData"]["setMonth"];
            $insDays = $_SESSION["schejuleInsertData"]["mainData"]["setDays"];
            $fmHall = $_SESSION["schejuleInsertData"]["mainData"]["hall"];
            $fmBlock = $_SESSION["schejuleInsertData"]["mainData"]["block"];
            if (isset($_SESSION["schejuleInsertData"]["error"])) {
                foreach ($_SESSION["schejuleInsertData"]["error"] AS $key => $val) {
                    $setError .= "      ".$val;
                }
            }
        }
    }
//print nl2br(print_r($_SESSION,true));
    // 大会が終了している場合は完了ページを表示
    if ($rarryLookDatas["finish_flg"] > 0) {
        $serchMode = 'scoreup';
        $rarryEndFlag = 'rarryEend';
    }
    if ($serchMode == 'scoreup') {
        if ($schejuleDataObj->adminCompleteGameYearMonthData($_SESSION["rarryId"], $y, $m) == true) {
            $completeGameYearDatas = $schejuleDataObj->getCompleteGameYearData();
        }
    }

    /*--------------------------------------------------------------------------
     * GETデータチェック
     --------------------------------------------------------------------------*/
    if ($mode == "submit") {

        // 月のチェック
        if ($insMonth == "") {
            $errorValue .= "<li>※&nbsp;月が未入力です。</li>";
            $fmError = False;
        } else if (!preg_match("/^[0-9]+$/", $insMonth)) {
            $errorValue .= "<li>※&nbsp;月の値が数値以外です。</li>";
            $fmError = False;
        } else if (strlen($insMonth) > 2 AND strlen($insMonth) < 1) {
            $errorValue .= "<li>※&nbsp;月の値を2桁以内にしてください。</li>";
            $fmError = False;
        } else if ($insMonth < 1 OR $insMonth > 12) {
            $errorValue .= "<li>※&nbsp;月の値を1?12の範囲にしてください。</li>";
            $fmError = False;
        } else if ($insMonth < date("m")) {
            //$errorValue .= "<li>※&nbsp;月の値を当月以上にしてください。</li>";
            //$fmError = False;
        }
        // 日のチェック
        if ($insDays == "") {
            $errorValue .= "<li>※&nbsp;日が未入力です。</li>";
            $fmError = False;
        } else if (!preg_match("/^[0-9]+$/", $insDays)) {
            $errorValue .= "<li>※&nbsp;日の値が数値以外です。</li>";
            $fmError = False;
        } else if (strlen($insDays) > 2 AND strlen($insDays) < 1) {
            $errorValue .= "<li>※&nbsp;日の値を2桁以内にしてください。</li>";
            $fmError = False;
        } else if (($insMonth == 1 OR $insMonth == 3 OR $insMonth == 5 OR $insMonth == 7 OR $insMonth == 8 OR $insMonth == 10 OR $insMonth == 12) AND ($insDays < 1 OR $insDays > 31)) {
            $errorValue .= "<li>※&nbsp;日の値を1?31の範囲にしてください。</li>";
            $fmError = False;
        } else if (($insMonth == 4 OR $insMonth == 6 OR $insMonth == 9 OR $insMonth == 11) AND ($insDays < 1 OR $insDays > 30)) {
            $errorValue .= "<li>※&nbsp;日の値を1?30の範囲にしてください。</li>";
            $fmError = False;
        } else if (($insMonth == 2) AND ($insDays < 1 OR $insDays > 29)) {
            $errorValue .= "<li>※&nbsp;日の値を1?29の範囲にしてください。</li>";
            $fmError = False;
        } else if (($insMonth == date("m") AND ($insDays < date("d")))) {
            $errorValue .= "<li>※&nbsp;本日より先の範囲にしてください。</li>";
            $fmError = False;
        }
        // 会場のチェック
        if (!is_numeric($fmHall)) {
            $errorValue .= "<li>※&nbsp;会場を選択してください。</li>";
            $fmError = False;
        }
        // =============================================================== //
        // エラーがなければ指定日の曜日を調べる                            //
        // =============================================================== //
        if ($fmError == True) {

            $scriptName = "main_schejule_set.php";

            // 試合会場データオブジェクトの取得
            if ($mHallDataObj->selectHallData($fmHall) ==True) {
                $hallName  = $mHallDataObj->getHallData();
                $setHallName = $hallName["h_name"]/*."&nbsp;&nbsp;".$selectHallData["coat"]*/;
            }
            switch (date("w", mktime(0,0,0,$insMonth,$insDays,$insYear))) {
                case 0 : $youbi = "日"; break;
                case 1 : $youbi = "月"; break;
                case 2 : $youbi = "火"; break;
                case 3 : $youbi = "水"; break;
                case 4 : $youbi = "木"; break;
                case 5 : $youbi = "金"; break; //$fmError = False;
                         //$errorValue .= "<li>※&nbsp;土曜もしくは日曜のみ指定可能です。</li>";
                         //break;
                case 6 : $youbi = "土"; break;
            }
            // 月と日の整形
            $insMonth = sprintf("%02d" , $insMonth);
            $insDays = sprintf("%02d" , $insDays);

            // 大会ブロックデータオブジェクトの取得
            if ($mBlockDataObj->selectRrarryRegistBlockData($_SESSION["rarryId"], $fmBlock) ==True) {
                $selectBlockData = $mBlockDataObj->getSelectBlockData();
                $insertBlockName = $selectBlockData["BLOCK_NAME"];
                $setBlockId = $selectBlockData["BLOCK_ID"];
            }

            if ($teamDataObj->LeagueTeam($_SESSION["rarryId"], '', '') == True) {

                $allTeamDatas = $teamDataObj->getLeagueTeam();
//print nl2br(print_r($allTeamDatas,true));

                // ブレイククラス(ブロック)
                //$breakClass = $allTeamDatas[0]["class"];

                $classKey = 0;

                for ($j=0; $j<10; $j++) {

                    (string)$selectCort[$j] = "";
                    (string)$officialBackForth[$j] = "";
                    (string)$blockHomeTeamSelectForm[$j] = "";
                    (string)$blockAwayTeamSelectForm[$j] = "";
                    (string)$officialTeamA[$j] = "";
                    (string)$officialTeamB[$j] = "";

                    // 試合会場データオブジェクトの取得
                    if ($mHallDataObj->hallCortData($fmHall) ==True) {
                        $selectHallCortData = $mHallDataObj->getHallCortDatas();
                        for ($i=0; $i<count($selectHallCortData); $i++) {
//print $_SESSION["schejuleInsertData"]["cort".$j.""]." = ".$selectHallCortData[$i]["CortId"]."<BR>";
                            $cortSelected = "";
                            // 戻り時のセレクト値
                            if ($selectHallCortData[$i]["CortId"] == $fmCort) {
                                $cortSelected = "selected=\"selected\"";
                            } else if (isset($_SESSION["schejuleInsertData"]["cort".$j.""]) AND $_SESSION["schejuleInsertData"]["cort".$j.""] == $selectHallCortData[$i]["CortId"]) {
                                $cortSelected = "selected=\"selected\"";
                            }
                            $selectCort[$j] .= "                  <option value=\"".$selectHallCortData[$i]["CortId"]."\" " . $cortSelected . ">".substr($selectHallCortData[$i]["CortName"], 0, 15)."</option>\n";
                       }
                    }
                    // オフィシャル選択オプション
                    for ($k=1; $k<=10; $k++) {
                        if (isset($_SESSION["schejuleInsertData"]["officials".$j.""])) {
                            if ($_SESSION["schejuleInsertData"]["officials".$j.""] == $k) {
                                $officialBFselected = "selected=\"selected\"";
                            } else {
                                $officialBFselected = "";
                            }
                        } else {
                            switch ($j) {
                              case 0 : ($k ==  2) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                              case 1 : ($k ==  1) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                              case 2 : ($k ==  4) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                              case 3 : ($k ==  3) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                              case 4 : ($k ==  6) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                              case 5 : ($k ==  5) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                              case 6 : ($k ==  8) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                              case 7 : ($k ==  7) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                              case 8 : ($k == 10) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                              case 9 : ($k ==  9) ? $officialBFselected = "selected=\"selected\"" : $officialBFselected = ""; break;
                            }
                        }
                        $officialBackForth[$j] .= "                <option value=\"".$k."\" " . $officialBFselected . ">".$k."</option>\n";
                    }

                    for ($i=0; $i<count($allTeamDatas[$fmBlock]); $i++) {

                        $homeSelected = "";
                        $awaySelected = "";
                        $officialASelected = "";
                        $officialBSelected = "";

                        // 新規登録のブロック(クラス)選択があった場合
                        if (isset($fmBlock)) {

                            $homeSelected = "";
                            $awaySelected = "";
                            $officialASelected = "";
                            $officialBSelected = "";

                            if (isset($_SESSION["schejuleInsertData"]["homeTeam".$j.""])) {
                                // 戻り時のチームセレクト
                                if ($_SESSION["schejuleInsertData"]["homeTeam".$j.""] == $allTeamDatas[$fmBlock][$i]["t_id"]) $homeSelected = "selected=\"selected\"";
                            }
                            if (isset($_SESSION["schejuleInsertData"]["awayTeam".$j.""])) {
                                if ($_SESSION["schejuleInsertData"]["awayTeam".$j.""] == $allTeamDatas[$fmBlock][$i]["t_id"]) $awaySelected = "selected=\"selected\"";
                            }
                            $blockHomeTeamSelectForm[$j] .= "                  <option value=\"".$allTeamDatas[$fmBlock][$i]["t_id"]."\" ".$homeSelected.">".substr($allTeamDatas[$fmBlock][$i]["t_name"], 0, 10)."</option>\n";
                            $blockAwayTeamSelectForm[$j] .= "                  <option value=\"".$allTeamDatas[$fmBlock][$i]["t_id"]."\" ".$awaySelected.">".substr($allTeamDatas[$fmBlock][$i]["t_name"], 0, 10)."</option>\n";
                            // オフィシャル
                            if (isset($_SESSION["schejuleInsertData"]["officialA".$j.""])) {
                                if ($_SESSION["schejuleInsertData"]["officialA".$j.""] == $allTeamDatas[$fmBlock][$i]["t_id"]) $officialASelected = "selected=\"selected\"";
                            }
                            if (isset($_SESSION["schejuleInsertData"]["officialB".$j.""])) {
                                if ($_SESSION["schejuleInsertData"]["officialB".$j.""] == $allTeamDatas[$fmBlock][$i]["t_id"]) $officialBSelected = "selected=\"selected\"";
                            }
                            $officialTeamA[$j] .= "                  <option value=\"".$allTeamDatas[$fmBlock][$i]["t_id"]."\" " . $officialASelected . ">".substr($allTeamDatas[$fmBlock][$i]["t_name"], 0, 10)."</option>\n";
                            $officialTeamB[$j] .= "                  <option value=\"".$allTeamDatas[$fmBlock][$i]["t_id"]."\" " . $officialBSelected . ">".substr($allTeamDatas[$fmBlock][$i]["t_name"], 0, 10)."</option>\n";
                        }
//print print_r($allTeamDatas);
                    }
                }
                    unset($allTeamDatas[$fmBlock]);

                for ($j=0; $j<10; $j++) {
                    $officialASelected = "";
                    $officialBSelected = "";

                    foreach ($allTeamDatas AS $key) {
//print $val;
                        for ($m=0; $m<count($key); $m++) {
                            // オフィシャル
                            //if ($_SESSION["schejuleInsertData"]["officialA".$j.""] == $allTeamDatas[$fmBlock][$i]["t_id"]) $officialASelected = "selected=\"selected\"";
                            //if ($_SESSION["schejuleInsertData"]["officialB".$j.""] == $allTeamDatas[$fmBlock][$i]["t_id"]) $officialBSelected = "selected=\"selected\"";
                            //$officialTeamA[$j] .= "                  <option value=\"".$allTeamDatas[$fmBlock][$i]["t_id"]."\" " . $officialASelected . ">".substr($allTeamDatas[$fmBlock][$i]["t_name"], 0, 10)."</option>\n";
                            //$officialTeamB[$j] .= "                  <option value=\"".$allTeamDatas[$fmBlock][$i]["t_id"]."\" " . $officialBSelected . ">".substr($allTeamDatas[$fmBlock][$i]["t_name"], 0, 10)."</option>\n";
                        //} else {
                            if (isset($_SESSION["schejuleInsertData"]["officialA".$j.""])) {
                                if ($_SESSION["schejuleInsertData"]["officialA".$j.""] == $key[$m]["t_id"]) $officialASelected = "selected=\"selected\"";
                            }
                            if (isset($_SESSION["schejuleInsertData"]["officialB".$j.""])) {
                                if ($_SESSION["schejuleInsertData"]["officialB".$j.""] == $key[$m]["t_id"]) $officialBSelected = "selected=\"selected\"";
                            }
                            $officialTeamA[$j] .= "                  <option value=\"".$key[$m]["t_id"]."\" " . $officialASelected . ">".substr($key[$m]["t_name"], 0, 10)."</option>\n";
                            $officialTeamB[$j] .= "                  <option value=\"".$key[$m]["t_id"]."\" " . $officialBSelected . ">".substr($key[$m]["t_name"], 0, 10)."</option>\n";
                        }
                    }
                }
//unset($allTeamDatas[$fmBlock]);
//print nl2br(print_r($allTeamDatas,true));
            }
        } /* エラー無し時終了 */
    } else {
    	if ($mode == 'new' AND $sts == '') {
    		$insMonth = date('m');
    	}
    }

    // スケジュールデータ
    if ($schejuleDataObj->SchejuleDatas($teamDataObj, $_SESSION["rarryId"], $games, $team_id, $serch_month, $class, $serchMode/*, $scoreupSelectYear, $scoreupSelectMonth*/) == True) {
        // 登録しているスケジュール
        $selectSchejuleData = $schejuleDataObj->getSchejuleData();
        $selectSchejuleDataNum = count($selectSchejuleData);
    }

    // 大会ブロックデータ
    if ($mBlockDataObj->rarryRegistBlockData($_SESSION["rarryId"]) == True) {
        // 登録しているスケジュール
        $masterBlockData = $mBlockDataObj->getRarryRegistBlockData();

        for ($i=0; $i<count($masterBlockData); $i++) {

            // 戻り時のセレクト値
            if ($masterBlockData[$i]["BLOCK_ID"] == $fmBlock) {
                $blockSelected = "selected=\"selected\"";
            } else {
                $blockSelected = "";
            }

            // ブロック(クラス)フォーム生成
            $fmBlockOption .= "                <option value=\"".$masterBlockData[$i]["BLOCK_ID"]."\" " . $blockSelected . ">".$masterBlockData[$i]["BLOCK_NAME"].$masterBlockData[$i]["SUB_NAME"]."</option>\n";

        }
    }

?>
<?php include_once "block/header.php"; ?>

<script language="javascript" type="text/javascript">
//<![CDATA[
<!--
<?php
  //if ($mode == "new" OR $fmError == True)
  if ($mode == "new" OR $fmError == False) {
?>
  // 新規登録フォームの会場選択後のコート選択フォーム切り替え
  function selecthall(area){
    var t=new Array();//optionの項目(text)
    var v=new Array();//optionのvalue
    <?php
    // 全試合会場データ
	if ($mHallDataObj->allHallData($_SESSION["rarryId"]) == True) {

        $AllHall = $mHallDataObj->getAllHallDatas();

        for ($i=0; $i<count($AllHall); $i++) {
            if ($fmHall == $AllHall[$i]["HallId"]) $opionSelect = "selected";
            else                                   $opionSelect = "";
            $selectHallForm .= "                <option value=\"".$AllHall[$i]['HallId']."\" ".$opionSelect.">".$AllHall[$i]['HallName']/*."　".$AllHall[$i]["HallCort"]*/."</option>\n";

            $jsHall = "";

//$mHallCortData = new mastarHallData;
//print "KIUJBKUJKASKJ";
            // 試合会場データオブジェクトの取得
            if ($mHallDataObj->hallCortData($AllHall[$i]['HallId']) ==True) {
                $selectHallCortData = $mHallDataObj->getHallCortDatas();

                // Javascriptの生成
                for ($j=0; $j<count($selectHallCortData); $j++) {
                    $jsHall .= "      t[".$j."]='" . $selectHallCortData[$j]["CortName"] . "';v[".$j."]='" . $selectHallCortData[$j]["CortId"] . "';\n";
                }
            }

            // Javascript用に会場IDを変数化する
            $cortId = $AllHall[$i]["HallId"];

            // Javascriptの生成
            if ($i == 0) {
print <<<EOF
if(area == '$cortId'){
$jsHall
    }
EOF;
            } else {

print <<<EOF
 else if(area == '$cortId'){
$jsHall
    }
EOF;
            }
        }
    }
    ?>
 else{
      t[0]='会場を選択'
    }
    var obj=document.frm.fmCort.options;
    obj.length=0;
    for(i=0;i<t.length;i++){
      obj[i]=new Option(t[i]);
      obj[i].value=v[i];
    }
    obj[0].selected=true;
  }

  <?php
  } else {
  ?>
<?php
/*
  // 新規登録フォームの会場選択後のコート選択フォーム切り替え
  function selectBlock(area, nums){
    var t2=new Array();//optionの項目(text)
    var v2=new Array();//optionのvalue
    <?php
    // 全試合会場データ
	if ($mHallDataObj->allHallData() == True) {

        $AllHall = $mHallDataObj->getAllHallDatas();

        for ($i=0; $i<count($AllHall); $i++) {
            //if ($fmHall == $AllHall[$i]["HallId"]) $opionSelect = "selected";
            //else                                   $opionSelect = "";
            $selectHallForm2 .= "                <option value=\"".$AllHall[$i]['HallId']."\" ".$opionSelect.">".$AllHall[$i]['HallName']."　".$AllHall[$i]["HallCort"]."</option>\n";

            $jsHall = "";

            $mHallCortData = new mastarHallData;
            // 試合会場データオブジェクトの取得
            if ($mHallCortDataObj->hallCortData($AllHall[$i]['HallId']) ==True) {
                $selectHallCortData = $mHallCortDataObj->getHallCortDatas();

                // Javascriptの生成
                for ($j=0; $j<count($selectHallCortData); $j++) {
                    $jsHall2 .= "      t[".$j."]='" . $selectHallCortData[$j]["CortName"] . "';v[".$j."]='" . $selectHallCortData[$j]["CortId"] . "';\n";
                }
            }

            // Javascript用に会場IDを変数化する
            $cortId2 = $AllHall[$i]["HallId"];

            // Javascriptの生成
            if ($i == 0) {
print <<<EOF
if(area == '$cortId'){
$jsHall
    }
EOF;
            } else {

print <<<EOF
 else if(area == '$cortId'){
$jsHall
    }
EOF;
            }
        }
    }
    ?>
 else{
      t[0]='会場を選択'
    }
    var hobj=document.frm.home[nums].options;
    var aobj=document.frm.away[nums].options;
    hobj.length=0;
    aobj.length=0;
    for(i=0;i<t.length;i++){
      hobj[i]=new Option(t[i]);
      aobj[i].value=v[i];
      hobj[i]=new Option(t[i]);
      aobj[i].value=v[i];
    }
    hobj[0].selected=true;
    aobj[0].selected=true;
  }
*/
?>


<?php if ($mode == "new") : ?>
  // 一覧部ブロック選択フォーム切り替え
  function selectblockteam(block, nums){
//alert ("aaaaa");
    var t=new Array();//optionの項目(text)
    var v=new Array();//optionのvalue
    <?php
//print $block;
    // 全試合会場データ
	if ($teamDataObj->LeagueTeam($_SESSION["rarryId"]) == True) {

        $leagueAllTeam = $teamDataObj->getLeagueTeam();
//print $leagueAllTeam["dataNum"]." = NUM\n";
//print print_r($leagueAllTeam)."\n";

//print count($leagueAllTeam)." = BLOCK\n";
        // ブロック毎
        foreach ($leagueAllTeam AS $rarryBlock => $blockTeamData) {

            $jsTeam = "";

            // Javascriptの生成
            for ($i=0; $i<count($blockTeamData); $i++) {
                $jsTeam .= "      t[".$i."]='" . ereg_replace("\'", "", $blockTeamData[$i]["t_name"]) . "';v[".$i."]='" . $blockTeamData[$i]["t_id"] . "';\n";
            }

            // Javascriptの生成
            if ($rarryBlock == 1) {
print <<<EOF
if(block == '$rarryBlock'){
$jsTeam    }
EOF;
            } else {
print <<<EOF
 else if(block == '$rarryBlock'){
$jsTeam    }
EOF;
            }
        }
    }
    ?>
 else{
      t[0]='下記より選択'
    }
//alert (nums);
//    var hobj=document.frm.home[nums].options;
//    hobj.length=0;
//    for(i=0;i<t.length;i++){
//      hobj[i]=new Option(t[i]);
//      hobj[i].value=v[i];
//    }
//    hobj[0].selected=true;

    var aobj=document.frm.away0.options;
    aobj.length=0;
    for(i=0;i<t.length;i++){
      aobj[i]=new Option(t[i]);
      aobj[i].value=v[i];
    }
    aobj[0].selected=true;
//    for(j=0;j<10;j++){
//      var obj=document.frm.home[j].options;
//      obj.length=0;
//      for(i=0;i<t.length;i++){
//        obj[i]=new Option(t[i]);
//        obj[i].value=v[i];
//      }
//      obj[0].selected=true;
//    }
  }
<?php endif; ?>

  // フォームデータを送信
  function sendpages(mode, gid, page) {
    var fmchangedel;
    document.fmchangedel.action = page + ".php";
    document.fmchangedel.mode.value = mode;
    document.fmchangedel.gameId.value = gid;
    document.fmchangedel.submit();
  }
  // 選手情報修正フォーム表示
  function schejuleChangeWindow(mode, gid, page) {
    subwin = window.open('', "schejuleChange", "width=580,height=500,scrollbars=yes,resizable=no,status=no,menubar=no,toolbar=no,location=no,directories=no")
    window.document.fmchangedel.action = page + ".php";
    window.document.fmchangedel.target = "schejuleChange" ;
    window.document.fmchangedel.mode.value = mode;
    window.document.fmchangedel.gameId.value = gid;
    window.document.fmchangedel.submit();
    subwin.focus();
  }

  <?php
  }
?>
  // フォームのチェックボックス制御
  function noOfficials(num) {
    var frm;
    // チェックを付けたとき
    if (document.frm.noOfficial[num].checked == true) {
    window.alert('アラートの表示');

      document.frm.officialA[num].disabled = true;
      document.frm.officialB[num].disabled = true;
    } else {
      document.frm.officialA[num].disabled = false;
      document.frm.officialB[num].disabled = false;
    }
  }
  function setAgain() {
    var fmback;
    document.fmback.action = "<?php print $_SERVER["PHP_SELF"]; ?>";
    document.fmback.mode.value = "new";
    document.fmback.submit();
  }
  function setAgainData() {
    selecthall(<?php print $fmHall; ?>);
    document.frm.block.options.selectedIndex = <?php print $block; ?>;
    document.frm.fmHall.options.selectedIndex = <?php print $fmHall; ?>;
    document.frm.fmCort.options.selectedIndex = <?php print $fmCort; ?>;
  }
  function toggleDiv(divid) {
    if(document.getElementById(divid).style.display == 'none'){
      document.getElementById(divid).style.display = 'block';
    }else{
      document.getElementById(divid).style.display = 'none';
    }
  }


//-->
//]]>
</script>

<body<?php if($sts == "insAgain") print " onload=\"setAgainData();\""; ?>>

<?php
#print nl2br(print_r($leagueAllTeam,true));
?>

<div id="main">
<!-- メインコンテンツ -->
<?php include_once "block/menu.php"; ?>
  <div id="middle">
<!-- ページ内サブコンテンツ -->
<?php include_once "block/contents.php"; ?>
    <div id="center-column">
      <div class="top-bar">
        <?php if ($rarryEndFlag != 'rarryEend') : ?>
        <a href="main_schejule.php?mode=new" class="button">新規登録</a>
        <?php endif; ?>
        <h1>Schejule編集</h1>
<?php
//for ($i=0; $i<count($rarryDataArray); $i++) {
//    if ($rarryDataArray[$i]["rarryId"] == $_SESSION["rarryId"]) {
//print <<<EOF
//        <div class="selectrarrymenu"><a href="schejule.php?rarryId={$rarryDataArray[$i]["rarryId"]}">{$rarryDataArray[$i]["rarrySubName"]}</a></div>
//EOF;
//    } else {
//print <<<EOF
//        <div class="rarrymenu"><a href="schejule.php?rarryId={$rarryDataArray[$i]["rarryId"]}">{$rarryDataArray[$i]["rarrySubName"]}</a></div>
//EOF;
//    }
//}
?>
      </div><br />
      <div class="select-bar">&nbsp;</div>
<?php
  if (isset($setError)) {
    // 最終登録のエラー表示
    print "<ul>\n";
    print $setError;
    print "</ul>\n";
  }
?>
<?php
// 登録済みスケジュール表示
if ($mode == "") {
?>
<!-- 登録済みスケジュール -->
      <form name="fmviewchange" method="post" action="<?php echo $_SERVER['PHPSELF'];?>">
      <div class="select-up">
        <strong>選択: </strong>
        <select name="serchMode" onchange="location.href=fmviewchange.serchMode.options[document.fmviewchange.serchMode.selectedIndex].value">
          <option value="main_schejule.php?serchMode=unPassage" <?php if ($serchMode == "unPassage") print "selected=\"selected\""; ?>>未経過スケジュール</option>
          <option value="main_schejule.php?serchMode=passage" <?php if ($serchMode == "passage") print "selected=\"selected\""; ?>>経過スケジュール</option>
          <option value="main_schejule.php?serchMode=scoreup" <?php if ($serchMode == "scoreup") print "selected=\"selected\""; ?>>完了スケジュール</option>
        </select>
      </div>
      </form>
<?php
// 経過済みスケジュール
if ($serchMode == "scoreup") {
print <<<EOF
      <div class="scoreup_selectYM">
EOF;
    $completeGameYearDatasNum = count($completeGameYearDatas);
    if ($completeGameYearDatasNum > 0) {
        foreach ($completeGameYearDatas as $compGameYear => $compGameMonth) {
print <<<EOF
        <div class="year">{$compGameYear}</div>
EOF;
            foreach ($compGameMonth as $val) {
print <<<EOF
        <div class="month"><a href="./{$scriptName}?serchMode=scoreup&amp;y={$compGameYear}&amp;m={$val}">{$val}月</a></div>
EOF;
            }
        }
    }
print <<<EOF
        </div>\n
EOF;

    // ページ選択リンクデータ
//    $page_data      = PageSelect(30, 1, 100, "".$scriptName."?serchMode=scoreup");
//    $all_data_value  = $page_data["script"]."\n<div style=\"float:left;\">\n<a name=\"data\"></a>\n".$page_data["page_select"].$page_data["back"].$page_data["next"]."</div>\n";
//    //print nl2br(print_r($page_data,true));
//    print $all_data_value;
}
?>
      <div class="table">
        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <form name="fmchangedel" method="post" action="#">
        <table class="listing" cellpadding="0" cellspacing="0" summary="データベースにあるスケジュールデータ">
<?php
// テーブルヘッダー「経過スケジュール」
if ($serchMode == "passage") {
	if ($selectSchejuleDataNum > 0) {
print <<<EOF
          <tr>
            <th style="width:60px;" class="first">日程</th>
            <th style="width:60px;">時　間</th>
            <th style="width:80px;">会　場</th>
            <th style="width:40px;">コート</th>
            <th style="width:30px;">CLASS</th>
            <th style="width:90px;">HOME</th>
            <th style="width:90px;">AWAY</th>
            <th style="width:90px;">ｵﾌｨｼｬﾙ・審判</th>
            <th class="last">スコア登録</th>
            <th>消化</th>
          </tr>
EOF;
	} else {
print <<<EOF
          <tr>
            <th style="width:740px;" class="first last">&nbsp;</th>
          </tr>
          <tr>
            <td style="width:100%;">スケジュールが経過＆未更新の試合はありません。</td>
          </tr>
EOF;
	}
// テーブルヘッダー「完了スケジュール」
} else if ($serchMode == "scoreup") {
print <<<EOF
          <tr>
            <th style="width:60px;" class="first">日程</th>
            <th style="width:30px;">CLASS</th>
            <th style="width:20px;">Game No</th>
            <th colspan="2" style="width:200px;">HOME</th>
            <th style="width:50px;">vs</th>
            <th colspan="2" style="width:200px;">AWAY</th>
            <th class="last">スコア修正</th>
          </tr>
EOF;
// テーブルヘッダー「未経過スケジュール」
} else {
print <<<EOF
          <tr>
            <th style="width:60px;" class="first">日程</th>
            <th style="width:45px;">時　間</th>
            <th style="width:80px;">会　場</th>
            <th style="width:40px;">コート</th>
            <th style="width:45px;">CLASS</th>
            <th style="width:90px;">HOME</th>
            <th style="width:90px;">AWAY</th>
            <th style="width:90px;">ｵﾌｨｼｬﾙ・審判</th>
            <th>修正</th>
            <th class="last">削除</th>
          </tr>
EOF;
}
?>
<?php
/****************************/
/** スケジュールデータ表示 **/
/****************************/
for ($i=0; $i<count($selectSchejuleData); $i++) {

    if ($selectSchejuleData[$i]['block_name'] == '') {
//        continue;
    }

    // チーム名のエスケープ文字を置換
    $homeTeamName = preg_replace("'&(amp|#38);'i", "&", $selectSchejuleData[$i]["home_team"]);
    $awayTeamName = preg_replace("'&(amp|#38);'i", "&", $selectSchejuleData[$i]["away_team"]);
    $ofisial_a = preg_replace("'&(amp|#38);'i", "&", $selectSchejuleData[$i]["ofisial_a"]);
    $ofisial_b = preg_replace("'&(amp|#38);'i", "&", $selectSchejuleData[$i]["ofisial_b"]);

    // 試合会場データオブジェクトの取得
    $mHallDataObj->selectCoatHallData($selectSchejuleData[$i]["hall"]);
    $coat_hall_data = $mHallDataObj->getCoatHallData();

    $breakStyleDate = "";
    $breakStyle = "";
    // 日にち・会場が別になったら行間を空ける
    $dayView = $selectSchejuleData[$i]["year"]."/".$selectSchejuleData[$i]["month"]."/".$selectSchejuleData[$i]["day"];
    if ($i > 0) {
        $dayViewBreak = $selectSchejuleData[($i-1)]["year"]."/".$selectSchejuleData[($i-1)]["month"]."/".$selectSchejuleData[($i-1)]["day"];
        if ($dayView != $dayViewBreak AND $i > 0) {//$breakStyle  = " style=\"border-top: double 4px #9097A9;\"";
print <<<EOF
          <tr>
            <td colspan="10" style="margin:1px;padding:1px;background-color:#FFF;border-top: solid 1px #9097A9;border-bottom: solid 1px #9097A9;"><span style="font-size: 2px;">&nbsp;</span></td>
          </tr>\n
EOF;
        } else if ($coat_hall_data["HallId"] != $coat_hall_data["HallId"] AND $i > 0) {
print <<<EOF
          <tr>
            <td colspan="10" style="margin:1px;padding:1px;background-color:#FFF;border-top: solid 1px #9097A9;border-bottom: solid 1px #9097A9;"><span style="font-size: 2px;">&nbsp;</span></td>
          </tr>\n
EOF;
        } else {
            // ブロックが別になったら線を入れる
            if ($selectSchejuleData[$i]["class"] != $selectSchejuleData[($i-1)]["class"] AND $i > 0) {
                $breakStyleDate  = "border-top: dotted 2px #9097A9;";
                $breakStyle  = "border-top: dotted 2px #9097A9;";
            }
        }
    }
    if ($i % 2 == 0) {
         print "          <tr class=\"bg\">\n";
    } else {
         print "          <tr>\n";
    }

print <<<EOF
            <td style="font-weight:bold;{$breakStyleDate}">{$dayView}</td>
EOF;
  // 対戦済みデータ(スコアデータ無し)
  if ($serchMode == "passage") {
print <<<EOF
            <td style="font-weight:bold;{$breakStyleDate}">{$selectSchejuleData[$i]["times"]}</td>
            <td style="{$breakStyle}">{$coat_hall_data["ryakumei"]}</td>
            <td style="{$breakStyle}">{$coat_hall_data["cort_name"]}</td>
            <td style="{$breakStyle}">{$selectSchejuleData[$i]["block_name"]}</td>
            <td style="{$breakStyle}">{$homeTeamName}</td>
            <td style="{$breakStyle}">{$awayTeamName}</td>
            <td style="{$breakStyle}">{$selectSchejuleData[$i]["ofisial_a"]}<br />{$selectSchejuleData[$i]["ofisial_b"]}</td>
            <td style="{$breakStyle}"><input type="button" value="スコア登録" onclick="sendpages('input',{$selectSchejuleData[$i]["gameId"]},'main_set_score');" /></td>
            <td style="{$breakStyle}"><input type="button" value="削除" onclick="schejuleChangeWindow('delete',{$selectSchejuleData[$i]["gameId"]},'main_schejule_change_del');" /></td>
            <input type="hidden" name="status" value="teamDataInput" />
          </tr>\n
EOF;
  // 対戦済みデータ
  } else if ($serchMode == "scoreup") {
    // 対戦成績の勝敗表示
    if ($selectSchejuleData[$i]["homeScore"] > $selectSchejuleData[$i]["awayScore"]) {
        $homeShouhai = '<div style="color:red;font-weight:bold;">WIN</div>';
        $awayShouhai = '<div style="color:blue;font-weight:bold;">LOSE</div>';
        if ($selectSchejuleData[$i]['importance']) {
            $homeShouhai = '<div style="color:red;font-weight:bold;">不戦勝</div>';
            $awayShouhai = '<div style="color:blue;font-weight:bold;">不戦敗</div>';
        }
    } else if ($selectSchejuleData[$i]["homeScore"] == $selectSchejuleData[$i]["awayScore"]) {
        $homeShouhai = '<div style="color:green;font-weight:bold;">DROW</div>';
        $awayShouhai = '<div style="color:green;font-weight:bold;">DROW</div>';
    } else {
        $homeShouhai = '<div style="color:blue;font-weight:bold;">LOSE</div>';
        $awayShouhai = '<div style="color:red;font-weight:bold;">WIN</div>';
        if ($selectSchejuleData[$i]['importance']) {
            $homeShouhai = '<div style="color:blue;font-weight:bold;">不戦敗</div>';
            $awayShouhai = '<div style="color:red;font-weight:bold;">不戦勝</div>';
        }
    }

    $homeIndiScoreAlert = '';
    $awayIndiScoreAlert = '';

    // 不戦試合以外は個人成績のチェック
    if (!$selectSchejuleData[$i]['importance']) {
        // 個人成績の登録があるか(HOME)
        if ($indiScoreDataObj->checkIndiGameScore($_SESSION["rarryId"], $selectSchejuleData[$i]['homeTeamId'], $selectSchejuleData[$i]['gameId']) == false) {
            $homeIndiScoreAlert = '&nbsp;<span style="color:red;">★</span>';
        }
        // 個人成績の登録があるか(AWAY)
        if ($indiScoreDataObj->checkIndiGameScore($_SESSION["rarryId"], $selectSchejuleData[$i]['awayTeamId'], $selectSchejuleData[$i]['gameId']) == false) {
            $awayIndiScoreAlert = '&nbsp;<span style="color:red;">★</span>';
        }
    }
//print nl2br(print_r($selectSchejuleData,true));
print <<<EOF
            <td style="{$breakStyle}">{$selectSchejuleData[$i]["block_name"]}</td>
            <td style="{$breakStyle}">{$selectSchejuleData[$i]["gameId"]}</td>
            <td style="{$breakStyle}width:150px;">{$homeTeamName}{$homeIndiScoreAlert}</td>
            <td style="{$breakStyle}width:50px;">{$homeShouhai}</td>
            <td style="{$breakStyle}">{$selectSchejuleData[$i]["homeScore"]}&nbsp;-&nbsp;{$selectSchejuleData[$i]["awayScore"]}</td>
            <td style="{$breakStyle}width:50px;">{$awayShouhai}</td>
            <td style="{$breakStyle}width:150px;">{$awayTeamName}{$awayIndiScoreAlert}</td>
            <td style="{$breakStyle}"><input type="button" value="スコア修正" onclick="sendpages('change','{$selectSchejuleData[$i]["gameId"]}','main_set_score');" /></td>
          </tr>\n
EOF;
  // 対戦前データ
  } else {
print <<<EOF
            <td style="font-weight:bold;{$breakStyleDate}">{$selectSchejuleData[$i]["times"]}</td>
            <td style="{$breakStyle}">{$coat_hall_data["ryakumei"]}</td>
            <td style="{$breakStyle}">{$coat_hall_data["cort_name"]}</td>
EOF;
    if ($selectSchejuleData[$i]['event'] != '') {
print <<<EOF
            <td style="{$breakStyle}" colspan="4">{$selectSchejuleData[$i]["event"]}</td>
EOF;
    } else {
      if ($selectSchejuleData[$i]["event2"] != '') {
print <<<EOF
            <td style="{$breakStyle}">{$selectSchejuleData[$i]["event2"]}</td>
EOF;
      } else {
print <<<EOF
            <td style="{$breakStyle}">{$selectSchejuleData[$i]["block_name"]}</td>
EOF;
      }
print <<<EOF
            <td style="{$breakStyle}">{$homeTeamName}</td>
            <td style="{$breakStyle}">{$awayTeamName}</td>
            <td style="{$breakStyle}">{$ofisial_a}<br />{$ofisial_b}</td>
EOF;
    }
print <<<EOF
            <td style="{$breakStyle}"><input type="button" value="修正" onclick="schejuleChangeWindow('change',{$selectSchejuleData[$i]["gameId"]},'main_schejule_change_del');" /></td>
            <td style="{$breakStyle}"><input type="button" value="削除" onclick="schejuleChangeWindow('delete',{$selectSchejuleData[$i]["gameId"]},'main_schejule_change_del');" /></td>
          </tr>\n
EOF;
  }
}
?>
        </table>
        <input type="hidden" name="serchMode" value="<?php echo $serchMode; ?>" />
        <input type="hidden" name="gameId" value="" />
        <input type="hidden" name="mode" value="" />
        </form>
      </div>
      <div>
<?php
} else if ($mode == "new" OR $mode == "submit") {
?>
<!-- 新規登録モード -->
      <div class="table">
        <form name="frm" action="<?php echo $scriptName; ?>" method="post">
        <img src="img/bg-th-left.gif" width="8" height="7" alt="" class="left" />
        <img src="img/bg-th-right.gif" width="7" height="7" alt="" class="right" />
        <table class="listing form" cellpadding="0" cellspacing="0" summary="スケジュール登録の大項目を設定" style="width:100%">
          <tr>
            <th class="full" colspan="14">新規スケジュール登録</th>
          </tr>
          <tr>
            <td colspan="2">日付・会場の設定</td>
            <td colspan="10" class="first style2">
            	<a href="javascript:showCalen(<?php echo date(n) + 1; ?>); void(0);"><img src="./js/calen_input/ico_calen.gif" alt="カレンダーから選択" /></a>
            	<span id="calenArea"></span>
<?php
    // 初期表示＆入力エラーの時
    if ($mode == "new" OR $fmError == False) {
print <<<EOF
              <select name="insYear">
EOF;
		for ($i=date('Y'); $i<(date('Y')+2); $i++) {
        	print "                <option value=\"".$i."\">".$i."</option>\n";
		}
print <<<EOF
                </select>
              <select name="insMonth">\n
EOF;
              for ($i=1; $i<=12; $i++) {

                  // 戻り時のセレクト値
                  if ($insMonth == $i) $monthSelected = "selected=\"selected\"";
                  else                 $monthSelected = "";
                  // ブロック(クラス)フォーム生成
                  print "                <option value=\"".$i."\" ".$monthSelected.">".$i."</option>\n";
              }
print <<<EOF
              </select>
              月
              <input type="text" size="2" maxlength="2" name="insDays" value="$insDays" id="imenumeric" />日
              <div style="padding-left: 20px;">
              <select name="fmBlock">
$fmBlockOption              </select>
              &nbsp;
              <select name="fmHall" onchange="selecthall(this.options[this.options.selectedIndex].value)">
                <option>下記より選択</option>
$selectHallForm              </select>
              &nbsp;
              <select name="fmCort">
                <option>会場を選択</option>
$selectCortDatas              </select>
              </div>\n
              <div style="padding-left: 20px;">
                  <input type="hidden" name="mode" value="submit" />
                  <input type="submit" value="決定" />
              </div>
EOF;
    //////////////////////////////////////////////////////
    // 新規登録　入力エラーがなしの時
    //////////////////////////////////////////////////////
    } else {
print <<<EOF
              $insYear 年
              $insMonth 月
              $insDays 日
              &nbsp;($youbi)
              <span style="padding:0px 20px;">
                $insertBlockName
              </span>
              <span style="padding:0px 20px;">
                $setHallName
              </span>
              <input type="button" value="やり直す" onclick="setAgain()" />\n
EOF;
    }
?>
            </td>
          </tr>
<?php
    // スケジュール詳細登録
    if ($fmError == True AND $mode == "submit") {
print <<<EOF
          <tr>
            <th style="padding:0;width:5px;">No.</th>
            <th style="padding:0;width:110px;">時間</th>
            <th style="padding:0;width:80px;">コート・クラス</th>
EOF;
            // 新規登録でブロック(クラス)の選択があったとき
            if (isset($fmBblock)) {
print <<<EOF
            <th>CLASS</th>\n
EOF;
            }
print <<<EOF
            <!-- <th>コート</th> -->
            <th style="padding:0;">対戦カード</th>
            <th>OFICIAL</th>
          </tr>\n
EOF;
        // 詳細登録フォーム
        for ($j=0; $j<10; $j++) {
            $number = $j+1;
			$eventValue = $_SESSION['schejuleInsertData']['event'.$j.''];
print <<<EOF
          <tr>
            <td>$number</td>
            <input type="hidden" name="number[$j]" value="$j" />
            <td style="padding-left:10px;text-align:left;">
            <select name="hour[$j]">\n
EOF;
            for ($k = 9; $k<21; $k++) {
                if (isset($_SESSION["schejuleInsertData"]["hour".$j.""]) AND $_SESSION["schejuleInsertData"]["hour".$j] == $k) $hourSelected = "selected=\"selected\"";
                else                             $hourSelected = "";

                print "              <option value=\"".$k."\" ".$hourSelected.">".$k."</option>\n";
            }
print <<<EOF
            </select>
            ：
            <select name="minutes[$j]">

EOF;
            $min = sprintf("%02d", 0);

            for ($k = 0; $k<12; $k++) {

                if (isset($_SESSION["schejuleInsertData"]["minutes".$j.""]) AND $_SESSION["schejuleInsertData"]["minutes".$j] == $min) $minSelected = "selected=\"selected\"";
                else                                  $minSelected = "";

                print "              <option value=\"".$min."\" ".$minSelected.">".$min."</option>\n";

                $min = sprintf("%02d", $min+5);
            }
print <<<EOF
            </select>
			<br />
			<input type="checkbox" name="events[$j]" value="1" onclick="toggleDiv('myTr$j');" />:特殊時
            </td>
            <td>
              <select name="cort[$j]">
$selectCort[$j]              </select>
              <br />
              <select name="fmBlock" disabled="disabled">
$fmBlockOption              </select>
            </td>\n
EOF;
/*
            // 新規登録でブロック(クラス)の選択があったとき
            if (isset($fmBblock)) {
print <<<EOF
            <td>
              <select name="block[$j]" onChange="selectBlock(this.options[this.options.selectedIndex].value, nums)">
$fmSetBlockOption              </select>
            </td>
EOF;
            }
*/
print <<<EOF
            <td>
              H：<select name="home[$j]">

EOF;
              /*HOME：<select name="home[$j]" onChange="selectTeam$j(this.options[this.options.selectedIndex].value)">*/
			// オフィシャルが指定のチェックが付いているか
			(string)$noOfficialCheck = ($_SESSION["schejuleInsertData"]["noOfficial".$j.""]) ? 'checked="checked"' : '' ;
print <<<EOF
                <option>下記より選択</option>
$blockHomeTeamSelectForm[$j]              </select>
			&nbsp;vs&nbsp;<select name="away[$j]">
                <option>下記より選択</option>
$blockAwayTeamSelectForm[$j]              </select>：A
				<div id="myTr$j" style="display:none;text-align:left;">
					イベント１<input type="text" name="event1_[$j]" size="12" value="$eventValue" />
					イベント２<input type="text" name="event2_[$j]" size="12" value="$eventValue2" />
				</div>
			</td>
            <td>
              <div style="float:left;text-align:left;">
              <select name="officials[$j]">
$officialBackForth[$j]
              </select>のカード
              <br />
              <input type="checkbox" name="noOfficial[$j]" value="1" onclick="noOfficials($j)" $noOfficialCheck />指定時
              </div>
              <div style="float:left;padding-left:12px;">
              前半<select name="officialA[$j]">
                <option value="0">下記より選択</option>
$officialTeamA[$j]
              </select>
              <br />
              後半<select name="officialB[$j]">
                <option value="0">下記より選択</option>
$officialTeamB[$j]
              </select>
              </div>
            </td>
          </tr>\n

EOF;
        }
print <<<EOF
          <tr>
            <td colspan="14">
              <input type="hidden" name="mode" value="confirm" />
              <input type="hidden" name="setYear" value="$insYear" />
              <input type="hidden" name="setMonth" value="$insMonth" />
              <input type="hidden" name="setDays" value="$insDays" />
              <input type="hidden" name="rarryId" value="$_SESSION[rarryId]" />
              <input type="hidden" name="hall" value="$fmHall" />
              <input type="hidden" name="block" value="$fmBlock" />
              <input type="hidden" name="level" value="0" />
EOF;
             /* <input type="submit" value="登録する" onclick="checkSameTeam()" />*/
print <<<EOF
              <input type="submit" value="登録する" />
              <input type="button" value="戻る" onclick="location.href='main_schejule.php'" />
            </td>
          </tr>\n
EOF;
    }
print <<<EOF
        </table>
        </form>
<!-- やり直し用フォームデータ -->
        <form name="fmback" method="post">
          <input type="hidden" name="mode" value="" />
          <input type="hidden" name="sts" value="insAgain" />
          <input type="hidden" name="year" value="$insYear" />
          <input type="hidden" name="insMonth" value="$insMonth" />
          <input type="hidden" name="insDays" value="$insDays" />
          <input type="hidden" name="rarryId" value="$_SESSION[rarryId]" />
          <input type="hidden" name="fmHall" value="$fmHall" />
          <input type="hidden" name="fmCort" value="$fmCort" />
          <input type="hidden" name="fmBlock" value="$fmBlock" />
          <input type="hidden" name="level" value="0" />
        </form>\n
EOF;
    if ($fmError == False AND $mode != "new") {
print <<<EOF
        <p>下記エラーが発生しまいた。</p>
        <ol style="color:#F00;">
          $errorValue
        </ol>
EOF;
    }
}
?>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
  <div id="footer"></div>
</div>
<?php include_once "block/footer.php" ?>
