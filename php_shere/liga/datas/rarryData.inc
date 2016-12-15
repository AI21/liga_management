<?php

// ¿ﬂƒÍ•’•°•§•Î∆…§ﬂπ˛§ﬂ
//require_once "connectionDB.inc";

class rarryData extends connectionDBClass {

    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    /**
     * ≥µÕ◊ : ¬Á≤Ò∆‚Õ∆•«°º•ø§ŒºË∆¿
     *
     * ¿‚Ã¿ : ¬Á≤Ò≥µÕ◊°¶¬Á≤Òæ°§¡≈¿°¶§Ω§Œ¬æ•«°º•ø§ŒºË∆¿
     *
     * @param $rarryId    ¬Á≤ÒID
    */
    public function rarryDetails($rarryId) {

        $retData = False;

        $sql = "SELECT " .
                     " LRI.`rarry_name` AS rarry_name, " .
                     " LRI.`rarry_sub_name` AS rarry_sub_name, " .
                     " LRI.`define` AS define, " .
                     " LRI.`type` AS type, " .
                     " LRI.`parent_id` AS parent_id, " .
                     " LRI.`progress` AS progress, " .
                     " LRI.`finish_flg` AS finish_flg, " .
                     " LRI.`created` AS created, " .
                     " LRI.`modified` AS modified, " .
//                     " MRT.`RARRY_TYPE` AS RARRY_TYPE, " .
//                     " MRT.`TYPE_VIEW` AS TYPE_VIEW, " .
                     " LYP.`win` AS win, " .
                     " LYP.`lose` AS lose, " .
                     " LYP.`draw` AS draw, " .
                     " LYP.`antiwar_win` AS antiwar_win, " .
                     " LYP.`antiwar_lose` AS antiwar_lose, " .
                     " LYP.`special1` AS special1, " .
                     " LYP.`special_exp1` AS special_exp1, " .
                     " LYP.`special2` AS special2, " .
                     " LYP.`special_exp2` AS special_exp2, " .
                     " LYP.`special3` AS special3, " .
                     " LYP.`special_exp3` AS special_exp3, " .
                     " LYP.`special4` AS special4, " .
                     " LYP.`special_exp4` AS special_exp4, " .
                     " LYP.`special5` AS special5, " .
                     " LYP.`special_exp5` AS special_exp5 " .
               " FROM " . dbTableName::LT_RARRY_INFO . " AS LRI " .
               " LEFT JOIN " . dbTableName::LT_YEAR_POINT . " AS LYP " .
               "  ON  LRI.`id` = LYP.`r_id` " .
//               " LEFT JOIN `m_rally_type` AS MRT " .
//               "  ON  LRI.`type` = MRT.`RARRY_TYPE` " .
               " WHERE LRI.`id` = '" . $rarryId . "' " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]¬Á≤Ò∆‚Õ∆•«°º•øºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if ($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

//            $this->RarryName = $data["RARRY_NAME"];
//            $this->RarrySubName = $data["RARRY_SUB_NAME"];
//            $this->RarryType = $data["RARRY_TYPE"];
//            $this->TypeView = $data["TYPE_VIEW"];
//            $this->WinPoint = $data["WIN_P"];
//            $this->LosePoint = $data["LOSE_P"];
//            $this->DrowPoint = $data["DROW_P"];
//            $this->AntiWinPoint = $data["ANT_WIN_P"];
//            $this->AntiLosePoint = $data["ANT_LOSE_P"];
//            $this->SpecialPoint1 = $data["SP_P1"];

            $this->rarryDetail['rarry_name'] = $data["rarry_name"];
            $this->rarryDetail['rarry_sub_name'] = $data["rarry_sub_name"];
            $this->rarryDetail['season'] = $data["season"];
            $this->rarryDetail['type'] = $data["type"];
            $this->rarryDetail['parent_id'] = $data["parent_id"];
            $this->rarryDetail['progress'] = $data["progress"];
            $this->rarryDetail['finish_flg'] = $data["finish_flg"];
            $this->rarryDetail['created'] = $data["created"];
            $this->rarryDetail['modified'] = $data["modified"];
//            $this->rarryDetails['RARRY_TYPE'] = $data["RARRY_TYPE"];
//            $this->rarryDetails['TYPE_VIEW'] = $data["TYPE_VIEW"];
            $this->rarryDetail['win'] = $data["win"];
            $this->rarryDetail['lose'] = $data["lose"];
            $this->rarryDetail['draw'] = $data["draw"];
            $this->rarryDetail['antiwar_win'] = $data["antiwar_win"];
            $this->rarryDetail['antiwar_lose'] = $data["antiwar_lose"];
            $this->rarryDetail['special1'] = $data["special1"];
            $this->rarryDetail['special_exp1'] = $data["special_exp1"];
            $this->rarryDetail['special2'] = $data["special2"];
            $this->rarryDetail['special_exp2'] = $data["special_exp2"];
            $this->rarryDetail['special3'] = $data["special3"];
            $this->rarryDetail['special_exp3'] = $data["special_exp3"];
            $this->rarryDetail['special4'] = $data["special4"];
            $this->rarryDetail['special_exp4'] = $data["special_exp4"];
            $this->rarryDetail['special5'] = $data["special5"];
            $this->rarryDetail['special_exp5'] = $data["special_exp5"];

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : ¬Á≤Ò•Ø•È•π•«°º•ø§ŒºË∆¿
     *
     * ¿‚Ã¿ : ¬Á≤Ò§Œ•Ø•È•π•«°º•ø§ŒºË∆¿
     *
     * @param $rarryId    ¬Á≤ÒID
    */
    public function rarryClassData($rarryId) {

        $retData = False;

/*
        $sql = "SELECT " .
                     " DISTINCT LRT.`class` AS RARRY_CLASS_ID, " .
                     " MCI.`class_neme` AS RARRY_CLASS_NAME " .
               " FROM `lt_regist_team` AS LRT " .
               " LEFT JOIN `rarry_class_info` AS MCI " .
               "  ON   LRT.`class` = MCI.`class` " .
               " WHERE LRT.`r_id` = '" . $rarryId . "' " ;
               " ORDER BY RARRY_CLASS_ID ";
*/
        $sql = "SELECT " .
                     " RCI.`id` AS RARRY_BLOCK_ID, " .
                     " RCI.`block_name` AS RARRY_BLOCK_NAME " .
               " FROM " . dbTableName::RARRY_USE_CLASS . " AS RUC " .
               " LEFT JOIN " . dbTableName::RARRY_CLASS_INFO . " AS RCI " .
               "  ON  RUC.`class` = RCI.`id` " .
               " WHERE RUC.`r_id` = '" . $rarryId . "' " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]¬Á≤Ò•Ø•È•π•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if ($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = mysql_fetch_array($rs);       // £±π‘£«£Â£Ù

                $this->RarryClass[$i]["RARRY_BLOCK_ID"] = $data["RARRY_BLOCK_ID"];
                $this->RarryClass[$i]["RARRY_BLOCK_NAME"] = $data["RARRY_BLOCK_NAME"];

            }

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : ¬Á≤Ò≈–œø•¡°º•‡§ŒºË∆¿
     *
     * ¿‚Ã¿
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $season     ¬Á≤Ò«Ø≈Ÿ
     * @param $class      ¬Á≤Ò•Ø•È•π∂Ë ¨
     */
    public function rarryProgressSection($rarryId, $class) {

        $retData = False;

        $sql = "SELECT " .
                     " MAX(LS.`section`) AS PROGRESS_SECTION, " .
                     " MAX(LS.`exe_days`) AS LAST_EXE_DAYS " .
               " FROM `lt_rarry_info` AS LRI, `lt_score` AS LS " .
               " WHERE LRI.`r_id` = LS.`r_id` " .
               "  AND  LRI.`r_id` = '" . $rarryId . "' " .
               //"  AND  LRI.`define` = '" . $define . "' " .
               "  AND  LS.`class` = '" . $class . "' " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]¬Á≤Ò≈–œø•¡°º•‡§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if ($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

            $this->ProgressSection = $data["PROGRESS_SECTION"];
            $this->LastExeDays = $data["LAST_EXE_DAYS"];

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : ¬Á≤Òø π‘√Ê§Œ¿·§ŒºË∆¿
     *
     * ¿‚Ã¿
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $season     ¬Á≤Ò«Ø≈Ÿ
     * @param $class      ¬Á≤Ò•Ø•È•π∂Ë ¨
     */
    public function rarryRegistTeam($rarryId, $class) {

        $retData = False;

        $sql = "SELECT " .
                     " LTI.`t_id` AS TEAM_ID, " .
                     " LTI.`t_name` AS TEAM_NAME, " .
                     " LTI.`t_kana` AS TEAM_KANA, " .
                     " RCI.`block_neme` AS CLASS_NAME " .
               " FROM `lt_regist_team` AS LRT, `lt_team_info` AS LTI, `lt_rarry_info` AS LRI, `rarry_class_info` AS RCI " .
               " WHERE LRT.`t_id` = LTI.`t_id` " .
               "  AND  LTI.`class` = RCI.`block_id` " .
               "  AND  LRT.`r_id` = LRI.`r_id` " .
               "  AND  LRT.`r_id` = '" . $rarryId . "' " .
               //"  AND  LRI.`define` = '" . $define . "' " .
               "  AND  LRT.`class` = '" . $class . "' " .
               " ORDER BY LTI.`t_kana` ";

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]¬Á≤Òø π‘√Ê§Œ¿·§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // •Ø•È•π…Ωº®Ãæ§œ≈˝∞Ï§ §Œ§«∞Ï∑ÔÃ‹§Œ§ﬂºË∆¿
                if ($i == 0) {
                    $this->ClassName = $data["CLASS_NAME"];
                }

                $this->ret["TEAM_ID"][$i] = $data["TEAM_ID"];
                $this->ret["TEAM_NAME"][$i] = $data["TEAM_NAME"];
                $this->ret["TEAM_KANA"][$i] = $data["TEAM_KANA"];

            }

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : ªÓπÁ§Ú§∑§ø∆¸§ŒºË∆¿
     *
     * ¿‚Ã¿
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $season     ¬Á≤Ò«Ø≈Ÿ
     * @param $class      ¬Á≤Ò•Ø•È•π∂Ë ¨
     */
    /* •Í°º•∞∑¡º∞§Œæ°§¡≈¿∑◊ªª
     * •€°º•‡•¡°º•‡§»•¢•¶•ß•§•¡°º•‡)§Œ∆¿º∫≈¿∫π§«æ°§¡≈¿§Ú∑Ë§·§∆πÁ∑◊§π§Î
     * §ø§¿§∑°¢•Í°º•∞Ω≈Õ◊≤Òµƒ…‘ª≤≤√°¶…‘¿Ôæ°°¶«‘§¨µØ§≥§√§∆§§§Ï§–Õ•¿Ë§π§Î
     * ∆±ª˛§À∆¿º∫≈¿∫π§‚∑◊ªª
     */
    public function rarryExeGameDays($rarryId, $class) {

        $retData = False;

        $sql = "SELECT " .
                     " LS.`exe_days` AS EXE_DAYS " .
/*
                     " DATE_FORMAT(LS.`exe_days`, '%Y') AS EXE_YEAR, " .
                     " DATE_FORMAT(LS.`exe_days`, '%m') AS EXE_MONTH, " .
                     " DATE_FORMAT(LS.`exe_days`, '%d') AS EXE_DAY, " .
*/
               " FROM `lt_rarry_info` AS LRI, `lt_score` AS LS " .
               " WHERE LRI.`r_id` = LS.`r_id` " .
               "  AND  LRI.`r_id` = '" . $rarryId . "' " .
               //"  AND  LRI.`define` = '" . $define . "' " .
               //"  AND  LS.`class` = '" . $class . "' " .
               "  AND  LS.`exe_days` != '0000-00-00' " .
               " GROUP BY LS.`exe_days` " .
               " ORDER BY LS.`exe_days` DESC " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]ªÓπÁ§Ú§∑§ø∆¸§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // •Ø•È•π…Ωº®Ãæ§œ≈˝∞Ï§ §Œ§«∞Ï∑ÔÃ‹§Œ§ﬂºË∆¿
                //if ($i == 0) {
                //    $this->ClassName = $data["CLASS_NAME"];
                //}

                $this->RarryExeGameDays[$i] = $data["EXE_DAYS"];

            }

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •Í°º•∞¬Á≤ÒÕ˙ŒÚ§ŒºË∆¿
     *
     * ¿‚Ã¿
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $season     ¬Á≤Ò«Ø≈Ÿ
     * @param $class      ¬Á≤Ò•Ø•È•π∂Ë ¨
     */
    public function rarryReagueHistory() {

	    $retData = False;

        $sql = "SELECT " .
                     " `id` , " .
                     " `rarry_name` , " .
                     " `rarry_sub_name`, " .
                     " `season`, " .
                     " `parent_id`, " .
                     " `finish_flg` " .
               " FROM ".dbTableName::LT_RARRY_INFO." " .
//               " WHERE `type` = '1' " .
               " ORDER BY `finish_flg`, `season` DESC, `parent_id`, `id` " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]•Í°º•∞¬Á≤ÒÕ˙ŒÚ§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                $this->RarryReagueHistory[$i]["rarryId"] = $data["id"];
                $this->RarryReagueHistory[$i]["rarryName"] = $data["rarry_name"];
                $this->RarryReagueHistory[$i]["rarrySubName"] = $data["rarry_sub_name"];
                $this->RarryReagueHistory[$i]["season"] = $data["season"];
                $this->RarryReagueHistory[$i]["parentId"] = $data["parent_id"];
                $this->RarryReagueHistory[$i]["finish_flg"] = $data["finish_flg"];

            }

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : ≈–œø§µ§Ï§∆§§§Î¬Á≤Ò•«°º•ø§ŒºË∆¿
     *
     * ¿‚Ã¿ : DB≈–œø§Œ¡¥¬Á≤Ò•«°º•ø§ŒºË∆¿
     *
     */
    public function rarryAllDatas() {

        $retData = False;

        $colomns = array(
                         'id',
                         'rarry_name',
                         'rarry_sub_name',
                         'season',
                         'type',
                         'parent_id',
                         'progress',
                         'finish_flg',
                         'created',
                         'modified',
                         );
        $sqlColomns = implode("`,`", $colomns);

        $sql = "SELECT " .
                     " `" . $sqlColomns . "` " .
               " FROM ".dbTableName::LT_RARRY_INFO .
               " ORDER BY `season`, `id`";

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]¬Á≤Ò•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if ($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = mysql_fetch_array($rs);       // £±π‘£«£Â£Ù

                foreach ($colomns as $val) {
                    $this->rarryAllData[$i][$val] = $data[$val];
                }
            }

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : ¬Á≤Òæ Û§Úππø∑§π§Î
     * ¿‚Ã¿ : ¬Á≤Ò•«°º•ø§Úππø∑§π§Î
     * @param resource  $insertData   ππø∑•«°º•ø
     * @return boolean  True°ßππø∑¥∞Œª°°False°ßππø∑•®•È°º
     * @see ErrorMessageValue  •®•È°º•·•√•ª°º•∏∆‚Õ∆
     * @access public
     */
    public function rarryUpdate($updateDatas) {

        $ret = false;

        $upSql = "UPDATE " . dbTableName::LT_RARRY_INFO . " SET
                   rarry_name = '".$updateDatas['rarry_name']."'
                  ,rarry_sub_name = '".$updateDatas['rarry_sub_name']."'
                  ,season = '".$updateDatas['season']."'
                  ,type = '".$updateDatas['type']."'
                  ,parent_id = '".$updateDatas['parent_id']."'
                  ,progress = '".$updateDatas['progress']."'
                  ,finish_flg = '".$updateDatas['finish_flg']."'
                  ,modified = NOW()
                  WHERE id = " . $updateDatas['rid'] . "
                  ";
        $rs = $this->Query($upSql);
//print $upSql."<br />";

        // •«°º•ø≈–œø
        $rs  = $this->Query($upSql);

        if ( !$rs ) {
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            $this->Query( "ROLLBACK" );
            print var_dump($this->DbErrorValue);
        } else {
            $retData = true;
            // •≥•ﬂ•√•»ΩËÕ˝
            //$this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

        return $ret;
    }

    /**
     * ≥µÕ◊ : ¬Á≤Òæ Û§Ú≈–œø§π§Î
     * ¿‚Ã¿ : ¬Á≤Ò•«°º•ø§Úø∑µ¨≈–œø§π§Î
     * @param resource  $insertData   ≈–œø•«°º•ø
     * @return boolean  True°ß≈–œø¥∞Œª°°False°ß≈–œø•®•È°º
     * @see ErrorMessageValue  •®•È°º•·•√•ª°º•∏∆‚Õ∆
     * @access public
     */
    public function rarryInsert($insertData) {

//print nl2br(print_r($insertData, true));

        $retData = false;

        # •«°º•ø§ŒºË∆¿
        $sql = "INSERT INTO ".dbTableName::LT_RARRY_INFO."  (" .
                   " `rarry_name`, " .
                   " `rarry_sub_name`, " .
                   " `season`, " .
                   " `type`, " .
                   " `parent_id`, " .
                   " `progress`, " .
                   " `created`, " .
                   " `modified` " .
                   " ) VALUES (" .
                   "  '" . $insertData['rarry_name'] . "' " .
                   ", '" . $insertData['rarry_sub_name'] . "' " .
                   ", '" . $insertData['season'] . "' " .
                   ", '" . $insertData['type'] . "' " .
                   ", '" . $insertData['parent_id'] . "' " .
                   ", '" . $insertData['progress'] . "' " .
                   ",  NOW() " .
                   ", '0000-00-00 00:00:00' " .
                   ")";
//print $sql;
        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            $this->Query( "ROLLBACK" );
            print var_dump($this->DbErrorValue);
        } else {
            $retData = true;
            // •≥•ﬂ•√•»ΩËÕ˝
            //$this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

/*
        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);
*/
        return $retData;
    }


    /*
     * ≈–œø§∑§∆§§§Î¡¥¬Á≤Òæ Û§Ú ÷§π
     */
    function getRarryAllDatas() {
        return $this->rarryAllData;
    }

    /*
     * ¬Á≤Òæ Û§Ú ÷§π
     */
    function getRarryDetail() {
        return $this->rarryDetail;
    }

    /*
     * ¬Á≤ÒÃæ§Ú ÷§π
     */
    function getRarryName() {
        return $this->RarryName;
    }

    /*
     * ¬Á≤ÒÃæ…˚¬Í§Ú ÷§π
     */
    function getRarrySubName() {
        return $this->RarrySubName;
    }

    /*
     * ¬Á≤Ò•ø•§•◊ID§Ú ÷§π
     */
    function getRarryType() {
        return $this->RarryType;
    }

    /*
     * ¬Á≤Ò•ø•§•◊…Ωº®Ãæ§Ú ÷§π
     */
    function getTypeView() {
        return $this->TypeView;
    }

    /*
     * ªÓπÁ°ßæ°Õ¯ª˛§Œæ°§¡≈¿•›•§•Û•»§Ú ÷§π
     */
    function getWinPoint() {
        return $this->WinPoint;
    }

    /*
     * ªÓπÁ°ß«‘¿Ôª˛§Œæ°§¡≈¿•›•§•Û•»§Ú ÷§π
     */
    function getLosePoint() {
        return $this->LosePoint;
    }

    /*
     * ªÓπÁ°ß∆±≈¿ª˛§Œæ°§¡≈¿•›•§•Û•»§Ú ÷§π
     */
    function getDrowPoint() {
        return $this->DrowPoint;
    }

    /*
     * ªÓπÁ°ß…‘¿Ôæ°ª˛§Œæ°§¡≈¿•›•§•Û•»§Ú ÷§π
     */
    function getAntiWinPoint() {
        return $this->AntiWinPoint;
    }

    /*
     * ªÓπÁ°ß…‘¿Ô…Èª˛§Œæ°§¡≈¿•›•§•Û•»§Ú ÷§π
     */
    function getAntiLosePoint() {
        return $this->AntiLosePoint;
    }

    /*
     * ªÓπÁ°ß¬Á≤Ò∆√ ÃΩËÕ˝§Œæ°§¡≈¿•›•§•Û•»§Ú ÷§π
     */
    function getSpecialPoint1() {
        return $this->SpecialPoint1;
    }

    /*
     * ¬Á≤Ò•Ø•È•π ÃID§Ú ÷§π
     */
    function getRarryClass() {
        return $this->RarryClass;
    }

    /*
     * •Ø•È•π…Ωº®Ãæ§Ú ÷§π
     */
    function getClassName() {
        return $this->ClassName;
    }

    /*
     * ¬Á≤Òª≤≤√•¡°º•‡•«°º•ø§Ú ÷§π
     */
    function getRegistTeamData() {
        return $this->ret;
    }

    /*
     * ¬Á≤Òø π‘√Ê§Œ¿·§Ú ÷§π
     */
    function getProgressSection() {
        return $this->ProgressSection;
    }

    /*
     * ªÓπÁ§Ú§∑§ø∆¸…’§Ú ÷§π
     */
    function getRarryExeGameDays() {
        return $this->RarryExeGameDays;
    }

    /*
     * •Í°º•∞¬Á≤ÒÕ˙ŒÚæ Û§Ú ÷§π
     */
    function getRarryReagueHistory() {
        return $this->RarryReagueHistory;
    }


}

?>
