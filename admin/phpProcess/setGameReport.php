<?php
////////////////////////////////////////////////////
/*
 * ��祹�����塼��ǡ����Խ������
 */
////////////////////////////////////////////////////

// �إå����ɤ߹���CSS�ե���������
$readCssFile = array('all', 'style');
// �إå����ɤ߹���javascript�ե���������
$readJsFile = array();

// �����
(string)$pageTitle = '��祹�����塼���Խ�����';
(string)$titleView = "����";
(string)$scriptName = "main_schejule_change_del.php";
(string)$sts = '';
(string)$stsComment = '';
(string)$errorValue .= '';
(string)$jsHtml = '';
(int)$level = 0;
(int)$distinctGame = 3; // Ʊ����1������λ������
(int)$setNum = 0;
(int)$setOfNum = 0;
(int)$insNumber = 0;
(boolean)$fmError = True;
(boolean)$sqlError = True;

require_once './common.inc';

//$_SESSION["schejuleInsertData"]["error"] = array();

// �ѥ�᡼���δĶ��ѿ�
$strRequestMethod = $_SERVER["REQUEST_METHOD"];

// ���饤����Ȥ���POST�Ǽ���ä��ǡ������ѿ�����Ȥ�
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

// ��ե��顼����Ͽ�ڡ�����URL���ʤ����TOP�ءʲ�������
if (!preg_match ("/schejule/i", $_SERVER["HTTP_REFERER"])) {
    header("Location: http://".$_SERVER["SERVER_NAME"]."/management.liga-tokai.com/admin/");
}

// ���å���󥯥ꥢ
if ($sts == '') {
    unset($_SESSION["schejuleChangeData"]);
}

if ($mode == "delete") {
    $titleView = "���";
}
if ($sts == "conf") {
    $titleView .= "��ǧ";
    $stsComment = '����'.$titleView.'���ƤǤ�����Ǥ��礦����';
} elseif ($sts == "conf") {
    $titleView .= "��λ";
    $stsComment = '��祹�����塼���'.$titleView.'����λ���ޤ�����';
}

// �����Ͽ������ǡ���
if ($teamDataObj->LeagueTeam($_SESSION["rarryId"]) == true) {
    $registTeamDatas = $teamDataObj->getLeagueTeam();
}
// �����ѥ��饹�ǡ���
if ($rarryDataObj->rarryClassData($_SESSION["rarryId"])) {
    $rarryBlockDatas = $rarryDataObj->getRarryClass();
}
//print nl2br(print_r($registTeamDatas,true));
//print nl2br(print_r($rarryBlockDatas,true));

if ($sts != 'comp') {
    // ������μ���
    if ($schejuleDataObj->selectGameData($teamDataObj, $_SESSION["rarryId"], $gameId) == True) {
        $selectGameData = $schejuleDataObj->getSelectSchejuleData();
    }
    //print nl2br(print_r($selectGameData,true));

    // �����ǡ�������
    if ($mHallDataObj->selectCoatHallData($selectGameData['court']) == true) {
        $selectCourtData = $mHallDataObj->getCoatHallData();
    }
}

if ($mode == "change") {

    if ($sts == '') {
        // �����Υե�����ǡ�������
        $hallJs = getFormJsToHallCourt($selectGameData, $mHallDataObj, $selectCourtData['HallId']);
        $jsHtml .= $hallJs['js'];

        $htmlForm = getHtmlForm($selectGameData, $hallJs['hallOption']);

        $sts = 'conf';
    } elseif ($sts == 'conf') {
        $htmlForm = getHtmlConfForm();
        $sts = 'comp';
    } elseif ($sts == 'comp') {
        // �����ǡ�����������Ѵ�
        foreach ($_POST AS $key => $val) {
            //if ($key == "mode") continue;
            $changeDatas[$key] = encode(mbZen($val));
        }

        // �������塼�빹��
        if ($schejuleDataChangeObj->schejuleDataChange($_SESSION["rarryId"], $changeDatas) == true) {
            $stsComment = '�������Ƥ˹������ޤ�����';
        } else {
            $stsComment = '�����˼��Ԥ��ޤ�����';
        }

        $htmlForm = getHtmlConfForm();
        $sts = '';
    }
}
if ($mode == "delete") {
    if ($sts == '') {
        // �����λ���å����γ���
        unset($_SESSION['deleteComp']);

        $gemeYear = $selectGameData['year'];
        $gemeMonth = $selectGameData['month'];
        $gemeDay = $selectGameData['day'];
        $gemeHours = substr($selectGameData['times'], 0, 2);
        $gemeMinutes = substr($selectGameData['times'], 5, 2);
        $gemeCort = $selectGameData['court'];
        $gemeBlock = $selectGameData['class'];
        $gemeSection = $selectGameData['section'];
        $gemeHome = $selectGameData['homeTeamId'];
        $gemeAway = $selectGameData['awayTeamId'];
        $gemeOfficialA = $selectGameData['ofisialAid'];
        $gemeOfficialB = $selectGameData['ofisialBid'];

        $htmlForm = getHtmlConfForm();

        $sts = 'comp';

    } elseif ($sts == 'comp') {

        // ����̥ǡ��������뤫�����å�
        if ($schejuleDataObj->checkScoreData($gameId) == true) {
            if (!isset($_SESSION['deleteComp']) OR $_SESSION['deleteComp'] == '') {
                // �������塼����
                if ($schejuleDataChangeObj->schejuleDelete($gameId) == true) {
                    $stsComment = '�����������塼��������ޤ�����';
                    $_SESSION['deleteComp'] = 'ok';
                } else {
                    $stsComment = '�������塼�����˼��Ԥ��ޤ�����';
                }
            }
        } else {
            $stsComment = '����̥ǡ���������ΤǺ������ޤ���';
        }

        if ($_SESSION['deleteComp'] == 'ok') {
            $stsComment = '�����������塼��������ޤ�����';
        }

        $htmlForm = getHtmlConfForm();
        $sts = '';
    }
}

/*
 * �������塼�빹����HTML�ե�����
 */
function getHtmlForm($gameData, $hallOption) {

    global $registTeamDatas;
    global $rarryBlockDatas;
    global $titleView;

    (string)$gameYearOptions = '';
    (string)$gameMonthOptions = '';
    (string)$gameDayOptions = '';
    (string)$gameHoursOptions = '';
    (string)$gameMinutesOptions = '';
    (string)$gameBlockOptions = '';
    (string)$gameSectionOptions = '';
    (string)$gameHomeTeamOptions = '';
    (string)$gameAwayTeamOptions = '';
    (string)$gameOfficialATeamOptions = '';
    (string)$gameOfficialBTeamOptions = '';
    (string)$selectCortDatas = '';

    $toYear = date('y');
    $toMonth = date('m');
    $toDay = date('d');

    // �ե����४�ץ�����͡�ǯ��
    for ($i=0; $i<=1; $i++) {
        $valYear = $toYear + $i;
        $selected = '';
        if ($valYear == $gameData["year"]) { $selected = ' selected="selected"'; }
        $gameYearOptions .= '                    <option value="' . $valYear . '"' . $selected . '>20' . $valYear . '</option>'."\n";
    }
    // �ե����४�ץ�����͡ʷ��
    for ($i=1; $i<=12; $i++) {
        $selected = '';
        if ($i == $gameData["month"]) { $selected = ' selected="selected"'; }
        $gameMonthOptions .= '                    <option value="' . sprintf("%02d", $i) . '"' . $selected . '>' . sprintf("%02d", $i) . '</option>'."\n";
    }
    // �ե����४�ץ�����͡�����
    for ($i=1; $i<=31; $i++) {
        $selected = '';
        if ($i == $gameData["day"]) { $selected = ' selected="selected"'; }
        $gameDayOptions .= '                    <option value="' . sprintf("%02d", $i) . '"' . $selected . '>' . sprintf("%02d", $i) . '</option>'."\n";
    }
    // �ե����४�ץ�����͡ʻ���
    for ($i=9; $i<=20; $i++) {
        $selected = '';
        if ($i == (int)substr($gameData["times"], 0, 2)) { $selected = ' selected="selected"'; }
        $gameHoursOptions .= '                    <option value="' . sprintf("%02d", $i) . '"' . $selected . '>' . sprintf("%02d", $i) . '</option>'."\n";
    }
    // �ե����४�ץ�����͡�ʬ��
    for ($i=0; $i<=55; $i++) {
        if (($i % 5) != 0) { continue; }
        $selected = '';
        if ($i == (int)substr($gameData["times"], 5, 2)) { $selected = ' selected="selected"'; }
        $gameMinutesOptions .= '                    <option value="' . sprintf("%02d", $i) . '"' . $selected . '>' . sprintf("%02d", $i) . '</option>'."\n";
    }
    // �ե����४�ץ�����͡ʥ֥�å���
    for ($i=0; $i<count($rarryBlockDatas); $i++) {
        $selected = '';
        if ($gameData["class"] == $rarryBlockDatas[$i]['RARRY_BLOCK_ID']) { $selected = ' selected="selected"'; }
        $gameBlockOptions .= '                    <option value="' . $rarryBlockDatas[$i]['RARRY_BLOCK_ID'] . '"' . $selected . '>' . $rarryBlockDatas[$i]['RARRY_BLOCK_NAME'] . '</option>'."\n";
    }
    // �ե����४�ץ�����͡����
    for ($i=0; $i<=20; $i++) {
        $selected = '';
        if ($i == $gameData["section"]) { $selected = ' selected="selected"'; }
        $gameSectionOptions .= '                    <option value="' . $i . '"' . $selected . '>��&nbsp;' . $i . '&nbsp;��</option>'."\n";
    }
    // �ե����४�ץ�����͡�HOME��AWAY�����ե������������
    if (count($registTeamDatas) > 0) {
        foreach ($registTeamDatas as $blockDatas => $teamDatas) {

            $gameHomeTeamOptions .= '<optgroup label="'.$teamDatas[0]['block_name'].'">';
            $gameAwayTeamOptions .= '<optgroup label="'.$teamDatas[0]['block_name'].'">';
            $gameOfficialATeamOptions .= '<optgroup label="'.$teamDatas[0]['block_name'].'">';
            $gameOfficialBTeamOptions .= '<optgroup label="'.$teamDatas[0]['block_name'].'">';

            foreach ($teamDatas as $datas) {
                $selectedHome = '';
                $selectedAway = '';
                $selectedOfficialA = '';
                $selectedOfficialB = '';
                $teamName = preg_replace ('/amp;/', '', $datas['t_name']);
                if ($gameData['homeTeamId'] == $datas['t_id']) { $selectedHome = ' selected="selected"'; }
                $gameHomeTeamOptions .= '                    <option value="' . $datas['t_id'] . '"' . $selectedHome . '>' . $teamName . '</option>'."\n";
                if ($gameData['awayTeamId'] == $datas['t_id']) { $selectedAway = ' selected="selected"'; }
                $gameAwayTeamOptions .= '                    <option value="' . $datas['t_id'] . '"' . $selectedAway . '>' . $teamName . '</option>'."\n";
                if ($gameData['ofisialAid'] == $datas['t_id']) { $selectedOfficialA = ' selected="selected"'; }
                $gameOfficialATeamOptions .= '                    <option value="' . $datas['t_id'] . '"' . $selectedOfficialA . '>' . $teamName . '</option>'."\n";
                if ($gameData['ofisialBid'] == $datas['t_id']) { $selectedOfficialB = ' selected="selected"'; }
                $gameOfficialBTeamOptions .= '                    <option value="' . $datas['t_id'] . '"' . $selectedOfficialB . '>' . $teamName . '</option>'."\n";
            }

            $gameHomeTeamOptions .= '</optgroup>';
            $gameAwayTeamOptions .= '</optgroup>';
            $gameOfficialATeamOptions .= '</optgroup>';
            $gameOfficialBTeamOptions .= '</optgroup>';

        }
    }

    $buf = '
          <table style="width:100%;">
            <tbody>
              <tr>
                <td style="width:100px;">������</td>
                <td>
                  <select name="gemeYear">
' . $gameYearOptions . '              </select>&nbsp;ǯ&nbsp;
                  <select name="gemeMonth">
' . $gameMonthOptions . '              </select>&nbsp;��&nbsp;
                  <select name="gemeDay">
                    <option value="00">̤��</option>
' . $gameDayOptions . '              </select>&nbsp;��
                </td>
              </tr>
              <tr>
                <td>�������</td>
                <td>
                  <select name="gemeHours">
                    <option value="00">̤��</option>
' . $gameHoursOptions . '              </select>&nbsp;��&nbsp;
                  <select name="gemeMinutes">
' . $gameMinutesOptions . '              </select>&nbsp;ʬ
                </td>
              </tr>
              <tr>
                <td>���</td>
                <td>
              <select name="gemeHall" onchange="selecthall(this.options[this.options.selectedIndex].value)">
' . $hallOption . '              </select>
              &nbsp;
              <select name="gemeCort">
                <option>��������</option>
              </select>
                </td>
              </tr>
              <tr>
                <td>����֥�å�����</td>
                <td>
                  <select name="gemeBlock">
' . $gameBlockOptions . '              </select>&nbsp;
                  <select name="gemeSection">
' . $gameSectionOptions . '              </select>
</td>
              </tr>
              <tr>
                <td>���參����</td>
                <td>
                  <select name="gemeHome">
                    <option value="0">̤��</option>
' . $gameHomeTeamOptions . '              </select>&nbsp;vs&nbsp;
                  <select name="gemeAway">
                    <option value="0">̤��</option>
' . $gameAwayTeamOptions . '              </select>
                </td>
              </tr>
              <tr>
                <td>���ե������</td>
                <td>��Ⱦ��
                  <select name="gemeOfficialA">
                    <option value="0">̤��</option>
' . $gameOfficialATeamOptions . '              </select><br />
                  ��Ⱦ��
                  <select name="gemeOfficialB">
                    <option value="0">̤��</option>
' . $gameOfficialBTeamOptions . '              </select>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr style="text-align:center;">
                <td colspan="2">
                  <input type="submit" value="&nbsp;'.$titleView.'��ǧ&nbsp;" />&nbsp;
                  <input type="button" value="&nbsp;�Ĥ���&nbsp;" onclick="window.close();" />
                </td>
              </tr>
            </tfoot>
          </table>
';
    return $buf;
}

/*
 * �������塼�빹����HTML�ե�����
 */
function getHtmlConfForm() {

    global $mHallDataObj, $selectCourtData;
    global $registTeamDatas, $selectGameData;
    global $gemeYear, $gemeMonth, $gemeDay, $gemeHours, $gemeMinutes;
    global $gemeHall, $gemeCort;
    global $gemeBlock, $gemeSection;
    global $gemeHome, $gemeAway;
    global $gemeOfficialA, $gemeOfficialB;
    global $mode, $sts;

    if (count($registTeamDatas) > 0) {
        foreach ($registTeamDatas as $registTeam => $blockDatas) {
            foreach ($blockDatas as $datas) {
//print nl2br(print_r($datas,true));
                if ($datas['t_id'] == $gemeHome) { $selectHomeTeam = $datas['t_name']; }
                if ($datas['t_id'] == $gemeAway) { $selectAwayTeam = $datas['t_name']; }
                if ($datas['t_id'] == $gemeOfficialA) { $selectOfficialATeam = $datas['t_name']; }
                if ($datas['t_id'] == $gemeOfficialB) { $selectOfficialBTeam = $datas['t_name']; }
            }
        }
    }
    // �����ǡ�������
    if ($mHallDataObj->selectCoatHallData($gemeCort) == true) {
        $selectGameCourtData = $mHallDataObj->getCoatHallData();
    }

    $gemeDayConf = ($gemeDay == '00') ? '̤��' : $gemeDay;
    $gemeHoursConf = ($gemeHours == '00') ? '̤��' : $gemeHours;
    $selectHomeTeam = (!isset($selectHomeTeam) OR $selectHomeTeam == '') ?  '̤��' : $selectHomeTeam;
    $selectAwayTeam = (!isset($selectAwayTeam) OR $selectAwayTeam == '') ?  '̤��' : $selectAwayTeam;
    $selectOfficialATeam = (!isset($selectOfficialATeam) OR $selectOfficialATeam == '') ?  '̤��' : $selectOfficialATeam;
    $selectOfficialBTeam = (!isset($selectOfficialBTeam) OR $selectOfficialBTeam == '') ?  '̤��' : $selectOfficialBTeam;

    $spanStart = '<span style="font-weight:bold;color:blue;">';
    $spanEnd = '</span>';

    $gemeYearView      = ($selectGameData['year'] != $gemeYear) ? $spanStart.'20'.$gemeYear.$spanEnd : '20'.$gemeYear;
    $gemeMonthView     = ($selectGameData['month'] != $gemeMonth) ? $spanStart.$gemeMonth.$spanEnd : $gemeMonth;
    $gemeDayView       = ($selectGameData['day'] != $gemeDay) ? $spanStart.$gemeDayConf.$spanEnd.'&nbsp;��' : $gemeDayConf;
    $gemeHoursView     = (substr($selectGameData['times'], 0, 2) != $gemeHours) ? $spanStart.$gemeHoursConf.$spanEnd.'&nbsp;��' : $gemeHoursConf;
    $gemeMinutesView   = (substr($selectGameData['times'], 5, 2) != $gemeMinutes) ? $spanStart.$gemeMinutes.$spanEnd : $gemeMinutes;
    $gemeHallView      = ($selectCourtData['HallId'] != $selectGameCourtData['HallId']) ? $spanStart.$selectGameCourtData['h_name'].$spanEnd : $selectCourtData['h_name'];
    $gemeCortView      = ($selectGameData['court'] != $gemeCort) ? $spanStart.$selectGameCourtData['cort_name'].$spanEnd : $selectCourtData['cort_name'];
    $gemeBlockView     = ($selectGameData['class'] != $gemeBlock) ? $spanStart.$gemeBlock.$spanEnd : $gemeBlock;
    $gemeSectionView   = ($selectGameData['section'] != $gemeSection) ? $spanStart.$gemeSection.$spanEnd : $gemeSection;
    $gemeHomeView      = ($selectGameData['homeTeamId'] != $gemeHome) ? $spanStart.$selectHomeTeam.$spanEnd : $selectHomeTeam;
    $gemeAwayView      = ($selectGameData['awayTeamId'] != $gemeAway) ? $spanStart.$selectAwayTeam.$spanEnd : $selectAwayTeam;
    $gemeOfficialAView = ($selectGameData['ofisialAid'] != $gemeOfficialA) ? $spanStart.$selectOfficialATeam.$spanEnd : $selectOfficialATeam;
    $gemeOfficialBView = ($selectGameData['ofisialBid'] != $gemeOfficialB) ? $spanStart.$selectOfficialBTeam.$spanEnd : $selectOfficialBTeam;

    if (preg_match ("/̤��/i", $gemeHoursView)) {
        $gemeTimeView = $gemeHoursView;
    } else {
        $gemeTimeView = $gemeHoursView . '&nbsp;' . $gemeMinutesView . '&nbsp;ʬ';
    }

    // ����
    if (!preg_match ("/̤��/i", $gemeDayView)) {
        $searchWeek = date ('w', mktime(0, 0, 0, (int)$gemeMonth, (int)$gemeDay, $gemeYear));
        switch ($searchWeek) {
            case 0: $weekView = '&nbsp;(��)'; break;
            case 1: $weekView = '&nbsp;(<span style="font-weight:bold;color:red">��</span>)'; break;
            case 2: $weekView = '&nbsp;(<span style="font-weight:bold;color:red">��</span>)'; break;
            case 3: $weekView = '&nbsp;(<span style="font-weight:bold;color:red">��</span>)'; break;
            case 4: $weekView = '&nbsp;(<span style="font-weight:bold;color:red">��</span>)'; break;
            case 5: $weekView = '&nbsp;(<span style="font-weight:bold;color:red">��</span>)'; break;
            case 6: $weekView = '&nbsp;(��)'; break;
        }
    } else {
        $weekView = '';
    }

    if ($mode == 'change') {
        if ($sts == 'conf') {
            $fmSubmitVIew = '                  <input type="button" value="&nbsp;���ľ��&nbsp;" onclick="javascript:history.back();" />
                  <input type="submit" value="&nbsp;��������&nbsp;" />&nbsp;'."\n";
        } else {
            $fmSubmitVIew = '<input type="button" value="&nbsp;�Ĥ���&nbsp;" onclick="windowClose();" />'."\n";
        }
    } elseif ($mode == 'delete') {
        if ($sts == '') {
            $fmSubmitVIew = '                  <input type="button" value="&nbsp;�������&nbsp;" onclick="deleteConfrim()" />
                  <input type="button" value="&nbsp;�Ĥ���&nbsp;" onclick="window.close();" />&nbsp;'."\n";
        } else {
            $fmSubmitVIew = '<input type="button" value="&nbsp;�Ĥ���&nbsp;" onclick="windowClose();" />'."\n";
        }
    }

    $buf = '
          <table style="width:100%;">
            <tbody>
              <tr>
                <td style="width:100px;">������</td>
                <td>
                  ' . $gemeYearView . '&nbsp;ǯ&nbsp;' . $gemeMonthView . '&nbsp;��&nbsp;' . $gemeDayView . $weekView . '
                  <input type="hidden" name="gemeYear" value="' . $gemeYear . '" />
                  <input type="hidden" name="gemeMonth" value="' . $gemeMonth . '" />
                  <input type="hidden" name="gemeDay" value="' . $gemeDay . '" />
                </td>
              </tr>
              <tr>
                <td>�������</td>
                <td>
                  ' . $gemeTimeView . '
                  <input type="hidden" name="gemeHours" value="' . $gemeHours . '" />
                  <input type="hidden" name="gemeMinutes" value="' . $gemeMinutes . '" />
                </td>
              </tr>
              <tr>
                <td>���</td>
                <td>
                  ' . $gemeHallView . '&nbsp;' . $gemeCortView . '
                  <input type="hidden" name="gemeHall" value="' . $gemeHall . '" />
                  <input type="hidden" name="gemeCort" value="' . $gemeCort . '" />
                </td>
              </tr>
              <tr>
                <td>����֥�å�����</td>
                <td>
                  ' . $gemeBlockView . '&nbsp;��&nbsp;��&nbsp;' . $gemeSectionView . '&nbsp;��
                  <input type="hidden" name="gemeBlock" value="' . $gemeBlock . '" />
                  <input type="hidden" name="gemeSection" value="' . $gemeSection . '" />
                </td>
              </tr>
              <tr>
                <td>���參����</td>
                <td>
                  ' . $gemeHomeView . '&nbsp;vs&nbsp;' . $gemeAwayView . '
                  <input type="hidden" name="gemeHome" value="' . $gemeHome . '" />
                  <input type="hidden" name="gemeAway" value="' . $gemeAway . '" />
                </td>
              </tr>
              <tr>
                <td>���ե������</td>
                <td>
                  ��Ⱦ��' . $gemeOfficialAView . '
                  ��Ⱦ��' . $gemeOfficialBView . '
                  <input type="hidden" name="gemeOfficialA" value="' . $gemeOfficialA . '" />
                  <input type="hidden" name="gemeOfficialB" value="' . $gemeOfficialB . '" />
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr style="text-align:center;">
                <td colspan="2">
'.$fmSubmitVIew.'
                </td>
              </tr>
            </tfoot>
          </table>
';
    return $buf;
}

/*
 * �������塼�빹����HTML�ե�����
 */
function getFormJsToHallCourt($gameData, $mHallDataObj, $hallId) {

    $buf = array();

    $buf['js'] = "
  // ��������Υ���������ե������ڤ��ؤ�
  function selecthall(area){
    var t=new Array();//option�ι���(text)
    var v=new Array();//option��value
";

    // �������ǡ���
    if ($mHallDataObj->allHallData($_SESSION["rarryId"]) == True) {

        $AllHall = $mHallDataObj->getAllHallDatas();

        $buf['hallOption'] = '';

        for ($i=0; $i<count($AllHall); $i++) {
            if ($hallId == $AllHall[$i]["HallId"]) $opionSelect = "selected";
            else                                   $opionSelect = "";
            $buf['hallOption'] .= "                <option value=\"".$AllHall[$i]['HallId']."\" ".$opionSelect.">".$AllHall[$i]['HallName']/*."��".$AllHall[$i]["HallCort"]*/."</option>\n";

            $jsHall = "";

            // �����ǡ������֥������Ȥμ���
            if ($mHallDataObj->hallCortData($AllHall[$i]['HallId']) ==True) {
                $selectHallCortData = $mHallDataObj->getHallCortDatas();

                // Javascript������
                for ($j=0; $j<count($selectHallCortData); $j++) {
                    $jsHall .= "      t[".$j."]='" . $selectHallCortData[$j]["CortName"] . "';v[".$j."]='" . $selectHallCortData[$j]["CortId"] . "';\n";
                    if ($gameData['court'] == $selectHallCortData[$j]["CortId"]) {
                        $defaultSetCourt = $j;
//                        $buf['defaultCourtOption'] .= "                <option value=\"".$selectHallCortData[$j]["CortId"]."\" ".$opionSelect.">".$selectHallCortData[$j]["CortName"]."</option>\n";
                    }
                }
            }

            // Javascript�Ѥ˲��ID���ѿ�������
            $cortId = $AllHall[$i]["HallId"];

            // Javascript������
            if ($i == 0) {
    $buf['js'] .= "
if(area == '".$cortId."'){
".$jsHall."
    }
";
            } else {

    $buf['js'] .= "
 else if(area == '".$cortId."'){
".$jsHall."
    }
";
            }
        }
    }

    $buf['js'] .= "
 else{
      t[0]='��������'
    }
    var obj=document.frm.gemeCort.options;
    obj.length=0;
    for(i=0;i<t.length;i++){
      obj[i]=new Option(t[i]);
      obj[i].value=v[i];
    }
    obj[" . $defaultSetCourt ."].selected=true;
  }
  ";
  return $buf;
}

?>
<?php include_once "block/header.php" ?>
<script language="javascript" type="text/javascript">
//<![CDATA[

<?php echo $jsHtml; ?>

  // �Ĥ���ܥ������
  function windowClose() {
    window.opener.location.reload(true);
    window.close();
  }

  // �����ǧ��������ɽ��
  function deleteConfrim() {

    // ��OK�׻��ν������� �� ��ǧ����������ɽ��
    if(window.confirm('������ޤ���������Ǥ�����')){
      var frm;
      document.frm.submit();
    }
  }

//-->
//]]>
</script>
<?php if ($mode == 'change' AND $sts == 'conf') : ?>
<body onload="selecthall(<?php echo $selectCourtData['HallId']; ?>)">
<?php else : ?>
<body>
<?php endif; ?>
<div id="main" style="width:500px;height:auto;">
  <div id="middle" style="width:480px;height:auto;">
    <div id="center-column" style="width:460px;height:auto;">
      <div class="top-bar" style="width:450px;">
        <h1>�������塼��<?php echo $titleView; ?>����</h1>
        <?php echo $pageTitle; ?>&nbsp;&#187;&nbsp;�������塼��<?php echo $titleView; ?>
      </div>
      <div class="select-bar" style="width:450px;">&nbsp;</div>
      <div>
        <h3>�������塼�����</h3>
        <?php if ($stsComment != '') : ?>
        <p style="font-weight:bold;color:blue;"><?php echo $stsComment; ?></p>
        <?php endif; ?>
        <?php if ($errorValue != '') : ?>
        <p><?php echo $errorValue; ?></p>
        <?php endif; ?>
        <form name="frm" action="<?php echo $scriptName; ?>" method="post">
<?php echo $htmlForm; ?>
          <input type="hidden" name="gameId" value="<?php echo $gameId; ?>" />
          <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
          <input type="hidden" name="sts" value="<?php echo $sts; ?>" />
        </form>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
    <div id="footer" style="width:505px;"></div>
  <div>&nbsp;</div>

<?php include_once "block/footer.php" ?>
