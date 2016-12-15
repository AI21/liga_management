<?php

// ¿ﬂƒÍ•’•°•§•Î∆…§ﬂπ˛§ﬂ
//require_once "teamData.inc";

class schejuleData extends connectionDBClass {


    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    /**
     * ≥µÕ◊    ªÿƒÍ§ŒªÓπÁ•«°º•ø
     *
     * ¿‚Ã¿
     *
     * @param string    $arg   ¿‚Ã¿
     *
     * @return string          ¿‚Ã¿
     * @return void
     */
    function selectGameData($teamDataObj, $rarryId, $gameId) {

        $retData = False;

        # ªÿƒÍ•≤°º•‡•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
               " LSCH.`id` AS game_id, " .
               " LSCH.`r_id`, " .
               " LSCH.`times` AS g_time, " .
               " DATE_FORMAT(LSCH.`times`, '%y') AS year, " .
               " DATE_FORMAT(LSCH.`times`, '%m') AS month, " .
               " DATE_FORMAT(LSCH.`times`, '%d') AS day, " .
               " DATE_FORMAT(LSCH.`times`, '%H : %i') AS times, " .
               " LSCH.`class`, " .
               " LSCH.`level`, " .
               " LSCH.`section`, " .
               " LSCH.`court`, " .
               " LSCH.`home_team`, " .
               " LSCH.`away_team`, " .
               " LSCH.`ofisial_a`, " .
               " LSCH.`ofisial_b`, " .
               " LSCH.`importance` " .
               " FROM `rarry_schejules` LSCH " .
               " WHERE LSCH.`id` = " . $gameId . "";
//print $sql."<BR>";

        $rs  = $this->Query($sql);
        if(!$rs){ print "[schejuleData]≥∫≈ˆªÓπÁ§Œ•«°º•øºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            // •«°º•øøÙ§ÚºË∆¿
            $data = $this->FetchRow($rs);

            // •¡°º•‡æ Û§ŒºË∆¿
//            $teamDataObj = new teamDatas;

            $teamDataObj->selectTeamData($data["home_team"]);
            $homeTeamName = $teamDataObj->getTeamName();
            $teamDataObj->selectTeamData($data["away_team"]);
            $awayTeamName = $teamDataObj->getTeamName();
            $teamDataObj->selectTeamData($data["ofisial_a"]);
            $ofisialAlfaTeamName = $teamDataObj->getTeamName();
            if ($data["ofisial_b"] != 0) {
                $teamDataObj->selectTeamData($data["ofisial_b"]);
                $ofisialBetaTeamName = $teamDataObj->getTeamName();
            } else {
                $ofisialBetaTeamName = '';
            }

            // •π•±•∏•Â°º•Î•«°º•ø
            $this->selectSchejuleData["game_id"] = $data["game_id"];
            $this->selectSchejuleData["r_id"] = $data["r_id"];
            $this->selectSchejuleData["g_time"] = $data["g_time"];
            $this->selectSchejuleData["year"] = $data["year"];
            $this->selectSchejuleData["month"] = $data["month"];
            $this->selectSchejuleData["day"] = $data["day"];
            $this->selectSchejuleData["times"] = $data["times"];
            $this->selectSchejuleData["class"] = $data["class"];
            $this->selectSchejuleData["level"] = $data["level"];
            $this->selectSchejuleData["section"] = $data["section"];
            $this->selectSchejuleData["court"] = $data["court"];
            $this->selectSchejuleData["homeTeamId"] = $data["home_team"];;
            $this->selectSchejuleData["home_team"] = $homeTeamName;
            $this->selectSchejuleData["awayTeamId"] = $data["away_team"];;
            $this->selectSchejuleData["away_team"] = $awayTeamName;
            $this->selectSchejuleData["ofisialAid"] = $data["ofisial_a"];
            $this->selectSchejuleData["ofisial_a"] = $ofisialAlfaTeamName;
            $this->selectSchejuleData["ofisialBid"] = $data["ofisial_b"];
            $this->selectSchejuleData["ofisial_b"] = $ofisialBetaTeamName;
//            $this->selectSchejuleData["importance"] = $data["home_score"];

            $retData = True;

        }
        return $retData;
    }

    /**
     * ≥µÕ◊    •π•±•∏•Â°º•Î•«°º•ø
     *
     * ¿‚Ã¿
     *
     * @param string    $arg   ¿‚Ã¿
     *
     * @return string          ¿‚Ã¿
     * @return void
     */
    function SchejuleDatas($teamDataObj, $rarryId, $games = 0, $team_id = 0, $month = 0, $class = 0, $mode = "") {

        $retData = False;
        $sqlData = "";
        $sqlWhere = "";

        $mainDbTable = 'rarry_schejules';

        $today = date("Y-m-d H:i:s");
        $selectMonth = date("Y-m%");

//print $today."<BR>";
//print $mode." = mode<BR>";

        // •‚°º•…§« —ππ
        switch ($mode) {
          case "setScore" :
            $sqlWhere = " WHERE LSCH.`r_id` = '" . $rarryId . "' " .
                        "  AND  LSCH.`id` = '" . $games . "'" ;
            break;
          case "passage" :
            $sqlWhere = "LEFT JOIN `rarry_scores` LSCO " .
                        "   ON  LSCH.`id` = LSCO.`g_id` " .
                        " WHERE LSCO.`g_id` IS NULL " .
                        "  AND  LSCH.`r_id` = '" . $rarryId . "' " .
                        "  AND  LSCH.`times` <= '" . $today . "'" .
                        " ORDER BY LSCH.`court`, LSCH.`times` ASC ";
            break;
          case "scoreup" :

            if ($this->completeSelectYear AND $this->completeSelectMonth) {
                $selectMonth = $this->completeSelectYear . '-' . sprintf("%02d", $this->completeSelectMonth) . "%";
            }

            // •·•§•Û•∆°º•÷•Î§Ú¥∞Œª•∆°º•÷•Î§À —ππ
            $mainDbTable = 'rarry_complete_games';

            $sqlData =  " (HSCO.`1st` + HSCO.`2nd` + HSCO.`3rd` + HSCO.`4th` + " .
                        "  HSCO.`ot1` + HSCO.`ot2` + HSCO.`ot3` + HSCO.`ot4` + " .
                        "  HSCO.`ot5` + HSCO.`ot6` + HSCO.`ot7` + HSCO.`ot8` + HSCO.`ot9`) AS homeScore, " .
                        " (ASCO.`1st` + ASCO.`2nd` + ASCO.`3rd` + ASCO.`4th` + " .
                        "  ASCO.`ot1` + ASCO.`ot2` + ASCO.`ot3` + ASCO.`ot4` + " .
                        "  ASCO.`ot5` + ASCO.`ot6` + ASCO.`ot7` + ASCO.`ot8` + ASCO.`ot9`) AS awayScore, ";

//            $sqlWhere = "LEFT JOIN `rarry_scores` HSCO " .
//                        "  ON   LSCH.`id` = HSCO.`g_id` " .
//                        "  AND  LSCH.`home_team` = HSCO.`t_id` " .
//                        "LEFT JOIN `rarry_scores` ASCO " .
//                        "  ON   LSCH.`id` = ASCO.`g_id` " .
//                        "  AND  LSCH.`away_team` = ASCO.`t_id` " .
//                        " WHERE HSCO.`g_id` IS NOT NULL " .
//                        "  AND  ASCO.`g_id` IS NOT NULL " .
//                        "  AND  LSCH.`r_id` = '" . $rarryId . "' " .
//                        "  AND  LSCH.`times` <= '" . $today . "'" .
//                        " ORDER BY DATE_FORMAT( LSCH.`times` , '%y%m%d' ) DESC , LSCH.`class` , LSCH.`court` " .
//                        " LIMIT 150 "
//                        ;
            $sqlWhere = "LEFT JOIN `rarry_scores` HSCO " .
                        "  ON   LSCH.`id` = HSCO.`g_id` " .
                        "  AND  LSCH.`home_team` = HSCO.`t_id` " .
                        "LEFT JOIN `rarry_scores` ASCO " .
                        "  ON   LSCH.`id` = ASCO.`g_id` " .
                        "  AND  LSCH.`away_team` = ASCO.`t_id` " .
                        " WHERE HSCO.`g_id` IS NOT NULL " .
                        "  AND  ASCO.`g_id` IS NOT NULL " .
                        "  AND  LSCH.`r_id` = '" . $rarryId . "' " .
                        "  AND  LSCH.`times` LIKE '" . $selectMonth . "'" .
                        " ORDER BY DATE_FORMAT( LSCH.`times` , '%y%m%d' ) DESC , LSCH.`class` , LSCH.`court` " ;
//                        " LIMIT 150 "
//                        ;
//                        "LEFT JOIN `rarry_scores` ASCO " .
//                        "  ON   LSCH.`id` = ASCO.`g_id` " .
//                        "  AND  LSCH.`away_team` = ASCO.`t_id` " .
//                        " WHERE HSCO.`g_id` IS NOT NULL " .
//                        "  AND  ASCO.`g_id` IS NOT NULL " .
//                        "  AND  LSCH.`r_id` = '" . $rarryId . "' " .
//                        "  AND  LSCH.`times` <= '" . $today . "'" .
//                        " ORDER BY DATE_FORMAT( LSCH.`times` , '%y%m%d' ) DESC , LSCH.`class` , LSCH.`court` ";
//                        " LIMIT 150 ";
            break;
          default :
            $sqlData =  " LSCH.`event`, LSCH.`event2`, ";
          	$sqlWhere = " WHERE LSCH.`r_id` = '" . $rarryId . "' " .
                        "  AND  LSCH.`times` > '" . $today . "'" .
//                        "  AND  LSCH.`home_team` > 0 " .
//                        "  AND  LSCH.`away_team` > 0 " .
                        " ORDER BY DATE_FORMAT( LSCH.`times` , '%y%m%d' ) ASC, LSCH.`court`, DATE_FORMAT( LSCH.`times` , '%H%i%s' ) ";
        }
/*
        if ($mode == "setScore") {
            $sqlWhere = "LSCH.`id` = '" . $games . "'" .
                        //" ORDER BY LSCH.`times`DESC " .
                        " LIMIT 50 ";
        } else {
            $sqlWhere = "LSCH.`times` > '" . $today . "'" .
                        " ORDER BY LSCH.`hall`, LSCH.`times` ASC ";
        }
*/

        # •¡°º•‡•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
               " DISTINCT(LSCH.`id`), " .
               " DATE_FORMAT(LSCH.`times`, '%y') AS year, " .
               " DATE_FORMAT(LSCH.`times`, '%m') AS month, " .
               " DATE_FORMAT(LSCH.`times`, '%d') AS day, " .
               " DATE_FORMAT(LSCH.`times`, '%H : %i') AS times, " .
               " LSCH.`class`, " .
               " MCI.`block_name`, " .
               " LSCH.`section`, " .
               " LSCH.`court`, " .
               " LSCH.`home_team`, " .
               " LSCH.`away_team`, " .
               " LSCH.`ofisial_a`, " .
               " LSCH.`ofisial_b`, " .
               $sqlData .
               " LSCH.`importance` " .
               " FROM " . $mainDbTable . " LSCH " .
               " LEFT JOIN `master_class_informations` MCI " .
               "       ON   LSCH.`class` = MCI.`id` " .
               $sqlWhere;
              # " WHERE LSCH.`r_id` = '" . $rarryId . "' " .
              # "  AND  " .$sqlWhere . "";
               //" AND " .
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[schejuleData]•π•±•∏•Â°º•Î•«°º•ø•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);
//print $num."<BR>";

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // ªÓπÁ∫—§ﬂ•≤°º•‡•«°º•ø§»≥∫≈ˆªÓπÁ§Œ•π•≥•¢•«°º•ø§¨§¢§Î§´•¡•ß•√•Ø
#                if ($this->checkScoreData($data["id"]) == False) {

                    // •«°º•ø§¨¬∏∫ﬂ§π§Ï§–•π•Î°º
#                    continue;
#                }

                // •¡°º•‡æ Û§ŒºË∆¿
//                $teamDataObj = new teamData();

                $teamDataObj->selectTeamData($data["home_team"]);

                $homeTeamName = $teamDataObj->getTeamName();
                $teamDataObj->selectTeamData($data["away_team"]);
                $awayTeamName = $teamDataObj->getTeamName();
                $teamDataObj->selectTeamData($data["ofisial_a"]);
                $ofisialAlfaTeamName[$i] = $teamDataObj->getTeamName();
                if ($data["ofisial_b"] != 0) {
                    $teamDataObj->selectTeamData($data["ofisial_b"]);
                    $ofisialBetaTeamName[$i] = $teamDataObj->getTeamName();
                } else {
                    $ofisialBetaTeamName[$i] = "";
                }
                // •π•±•∏•Â°º•Î•«°º•ø
                $this->schejuleData[$i]["gameId"] = $data["id"];
                $this->schejuleData[$i]["year"] = $data["year"];
                $this->schejuleData[$i]["month"] = $data["month"];
                $this->schejuleData[$i]["day"] = $data["day"];
                $this->schejuleData[$i]["times"] = $data["times"];
                $this->schejuleData[$i]["class"] = $data["class"];
                $this->schejuleData[$i]["block_name"] = $data["block_name"];
                $this->schejuleData[$i]["section"] = $data["section"];
                $this->schejuleData[$i]["hall"] = $data["court"];
                $this->schejuleData[$i]["homeTeamId"] = $data["home_team"];
                $this->schejuleData[$i]["home_team"] = $homeTeamName;
                $this->schejuleData[$i]["awayTeamId"] = $data["away_team"];
                $this->schejuleData[$i]["away_team"] = $awayTeamName;
                $this->schejuleData[$i]["ofisialAid"] = $data["ofisial_a"];
                $this->schejuleData[$i]["ofisial_a"] = $ofisialAlfaTeamName[$i];
                $this->schejuleData[$i]["ofisialBid"] = $data["ofisial_b"];
                $this->schejuleData[$i]["ofisial_b"] = $ofisialBetaTeamName[$i];
                $this->schejuleData[$i]["importance"] = $data["importance"];
                $this->schejuleData[$i]["event"] = $data["event"];
                $this->schejuleData[$i]["event2"] = $data["event2"];

                if ($mode == "scoreup") {
                    $this->schejuleData[$i]["homeScore"] = $data["homeScore"];
                    $this->schejuleData[$i]["awayScore"] = $data["awayScore"];
                }
            }
            $this->count = $i;

            $retData = True;
        } else {
                // •·•Û•–°º•«°º•ø(«€ŒÛÃæ§œCSS§ÀπÁ§Ô§ª§Îªˆ)
                $this->schejuleData[0]["gameId"] = "";
                $this->schejuleData[0]["year"] = "";
                $this->schejuleData[0]["month"] = "";
                $this->schejuleData[0]["day"] = "";
                $this->schejuleData[0]["times"] = "";
                $this->schejuleData[0]["class"] = "";
                $this->schejuleData[0]["block_name"] = "";
                $this->schejuleData[0]["section"] = "";
                $this->schejuleData[0]["hall"] = "";
                $this->schejuleData[0]["homeTeamId"] = "";
                $this->schejuleData[0]["home_team"] = "";
                $this->schejuleData[0]["awayTeamId"] = "";
                $this->schejuleData[0]["away_team"] = "";
                $this->schejuleData[0]["ofisialAid"] = "";
                $this->schejuleData[0]["ofisial_a"] = "";
                $this->schejuleData[0]["ofisialBid"] = "";
                $this->schejuleData[0]["ofisial_b"] = "";
                $this->schejuleData[0]["importance"] = "";
                $this->schejuleData[0]["event"] = "";
                $this->schejuleData[0]["event2"] = "";
        }

        return $retData;
    }

    // ªÓπÁ∑Î≤Ã§¨§¢§Î§´§…§¶§´•¡•ß•√•Ø§π§Î
    /**
     * ≥µÕ◊
     *
     * ¿‚Ã¿
     *
     * @param string    $arg   ¿‚Ã¿
     *
     * @return string          ¿‚Ã¿
     * @return void
     */
    function checkScoreData($gameId) {

        $retData = True;

        # ªÓπÁ§¨π‘§Ô§Ï§ø•≤°º•‡§‚§∑§Ø§œªÓπÁ∑Î≤Ã§¨§¢§Î•≤°º•‡§Œ•«°º•ø§ŒºË∆¿
        $sql = "SELECT count(*) AS COUNT " .
               " FROM `rarry_scores` LS, `rarry_complete_games` LSCA " .
               " WHERE LS.`g_id` = " . $gameId . " " .
               "  OR  LSCA.`id` = " . $gameId . "";
               //" WHERE LS.`game_id` = LSCA.`id` " .
               //"  AND  `game_id` = " . $gameId . "";
               //" AND " .
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[schejuleData]≥∫≈ˆªÓπÁ§Œ•«°º•øºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $data = $this->FetchRow($rs);

        // •«°º•ø§¨£±∑Ô∞ æÂ§¢§Ï§–False
        if ($data["COUNT"] > 0){
            $retData = False;
        }
        return $retData;
    }

    // ¬–¿Ô¿·§ŒºË∆¿
    /**
     * ≥µÕ◊
     *
     * ¿‚Ã¿
     *
     * @param string    $arg   ¿‚Ã¿
     *
     * @return string          ¿‚Ã¿
     * @return void
     */
    function versusSectionNumber($rarryId, $home_team, $away_team, $blockNum) {

//print $blockNum." = blockNum<BR>";
        $retData = True;

        # •€°º•‡•¡°º•‡§Œ≈–œø∫—§ﬂ¬–¿Ô¿‚§ŒºË∆¿
        $home_sql = "SELECT " .
                    " `section` " .
                    " FROM `rarry_schejules` " .
                    " WHERE `r_id` = " . $rarryId . " " .
                    "  AND  (`home_team` = " . $home_team . " " .
                    "  OR   `away_team` = " . $home_team . ") " .
                    " ORDER BY `section` ";
//print $home_sql."<BR>";
        $h_rs  = $this->Query($home_sql);
        if(!$h_rs){ print "[schejuluData]•€°º•‡•¡°º•‡§Œ≈–œø∫—§ﬂ¬–¿Ô¿‚§ŒºË∆¿•®•È°º§«§π°£<br />".$home_sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $h_num        = $this->GetRowCount($h_rs);

        if($h_num > 0){

            for ($i = 0; $i < $h_num; $i++) {

                $h_data   = $this->FetchRow($h_rs);       // £±π‘£«£Â£Ù

                // •«°º•ø§Ú•´•È•‡Ãæ§Œ«€ŒÛ§À§π§Î
                $h_sec_buf[$i] = $h_data["section"];
            }

            /* «€ŒÛ§Œ•≠°º§»√Õ§Ú»ø¬–§À§π§Î
             * •ª•√•»§µ§Ï§∆§§§Î¿·»÷πÊ§Ú•≠°º§À§π§Î
             */
            $set_h_buf = array_flip($h_sec_buf);

            // •€°º•‡•¡°º•‡§ŒÃ§æ√≤Ω¿·§ÚºË∆¿
            for ($j = 1; $j <= $blockNum; $j++) {

                // •«°º•ø§¨§ §§»÷πÊ(¿·)§Úø∑µ¨«€ŒÛ§À§π§Î
                if (!isset($set_h_buf[$j])) {

                    $notHomeSection[] = (int)$j;
                }
            }
        }

        # •¢•¶•ß•§•¡°º•‡§Œ≈–œø∫—§ﬂ¬–¿Ô¿‚§ŒºË∆¿
        $away_sql = "SELECT " .
                    " `section` " .
                    " FROM `rarry_schejules` " .
                    " WHERE `r_id` = " . $rarryId . " " .
                    "  AND  (`home_team` = " . $away_team . " " .
                    "  OR   `away_team` = " . $away_team . ") " .
                    " ORDER BY `section` ";
//print $away_sql."<BR>";
        $a_rs  = $this->Query($away_sql);
        if(!$a_rs){ print "[schejuluData]•¢•¶•ß•§•¡°º•‡§Œ≈–œø∫—§ﬂ¬–¿Ô¿‚§ŒºË∆¿•®•È°º§«§π°£<br />".$away_sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $a_num        = $this->GetRowCount($a_rs);

        if($a_num > 0){

            for ($i = 0; $i < $a_num; $i++) {

                $a_data   = $this->FetchRow($a_rs);       // £±π‘£«£Â£Ù

                // •«°º•ø§Ú•´•È•‡Ãæ§Œ«€ŒÛ§À§π§Î
                $a_sec_buf[] = $a_data["section"];
            }

            /* «€ŒÛ§Œ•≠°º§»√Õ§Ú»ø¬–§À§π§Î
             * •ª•√•»§µ§Ï§∆§§§Î¿·»÷πÊ§Ú•≠°º§À§π§Î
             */
            $set_a_buf = array_flip($a_sec_buf);

            // •€°º•‡•¡°º•‡§ŒÃ§æ√≤Ω¿·§ÚºË∆¿
            for ($j = 1; $j <= $blockNum; $j++) {

                // •«°º•ø§¨§ §§»÷πÊ(¿·)§Úø∑µ¨«€ŒÛ§À§π§Î
                if (!isset($set_a_buf[$j])) {

                    $notAwaySection[] = $j;
                }
            }
        }


        // Œæ•¡°º•‡§»§‚§À¬–¿Ô§∑§∆§§§ §§¿·
        $bothNotTeamSections = array_uintersect($notHomeSection, $notAwaySection, "strcasecmp");

        foreach ($bothNotTeamSections AS $key => $val ) {

            $this->bothNotTeamSection = $val;
            break;// ∞Ï∏ƒÃ‹§Œ§ﬂºË∆¿
        }

//print print_r($h_sec_buf)." = HOME≈–œø¿·<BR>";
//print print_r($notHomeSection)."/".$home_team." = HOMEÃ§≈–œø¿·<BR>";
//print print_r($a_sec_buf)." = AWAY≈–œø¿·<BR>";
//print print_r($notAwaySection)."/".$away_team." = AWAYÃ§≈–œø¿·<BR>";
//print print_r($bothNotTeamSections)." = Ã§≈–œø¿·<BR>";

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •π•±•∏•Â°º•Î≈–œø∫—§ﬂ•«°º•ø§ŒºË∆¿
     *
     * ¿‚Ã¿ : ªÿƒÍ∆¸§Œ•π•±•∏•Â°º•Î≈–œø∫—§ﬂ•«°º•ø§ŒºË∆¿
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $blockId    ¬Á≤Ò•÷•Ì•√•ØID
     * @param $teamId     •¡°º•‡ID
    */
    public function adminSchejuleDatesData($rarryId, $year, $month, $day) {

        $retData = False;

        $examDates = $year."-".$month."-".$day;

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " CAST( `times` AS SIGNED ) AS datetimes, " .
                       " DATE_FORMAT( `times` , '%k' ) AS minuits, " .
                       " DATE_FORMAT( `times` , '%i' ) AS second, " .
                       " `class`, " .
                       " `section`, " .
                       " `court`, " .
                       " `home_team`, " .
                       " `away_team`, " .
                       " `ofisial_a`, " .
                       " `ofisial_b`, " .
                       " `importance` " .
                    " FROM `rarry_schejules`  " .
                    " WHERE `r_id` = " . $rarryId . " " .
                    "  AND  DATE_FORMAT( `times` , '%Y-%m-%d' ) = '" . $examDates . "' " .
                    " ORDER BY `class` " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[schejuluData]•π•±•∏•Â°º•Î≈–œø∫—§ﬂ•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return $this->GetLastError(); }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø
                $this->SchejuleDatesData[$data["class"]][$i]["datetimes"] = $data["datetimes"];
                $this->SchejuleDatesData[$data["class"]][$i]["minuits"] = $data["minuits"];
                $this->SchejuleDatesData[$data["class"]][$i]["second"] = $data["second"];
                $this->SchejuleDatesData[$data["class"]][$i]["section"] = $data["section"];
                $this->SchejuleDatesData[$data["class"]][$i]["hall"] = $data["hall"];
                $this->SchejuleDatesData[$data["class"]][$i]["home_team"] = $data["home_team"];
                $this->SchejuleDatesData[$data["class"]][$i]["away_team"] = $data["away_team"];
                $this->SchejuleDatesData[$data["class"]][$i]["ofisial_a"] = $data["ofisial_a"];
                $this->SchejuleDatesData[$data["class"]][$i]["ofisial_b"] = $data["ofisial_b"];
                $this->SchejuleDatesData[$data["class"]][$i]["importance"] = $data["importance"];

            }
            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : ¡™¬Ú¬Á≤Ò§Œ¥∞Œª•π•±•∏•Â°º•Î§Œ«Ø∑Ó•«°º•ø§ŒºË∆¿
     *
     * ¿‚Ã¿ : ªÿƒÍ¬Á≤Ò§Œ•π•±•∏•Â°º•Î≈–œø∫—§ﬂ«Ø∑Ó•«°º•ø§ŒºË∆¿
     *
     * @param $rarryId    ¬Á≤ÒID
    */
    public function adminCompleteGameYearMonthData($rarryId, $selectYear=null, $selectMonth=null) {

        $retData = False;

        if (!is_null($selectYear) AND is_numeric($selectYear)) {
            $this->completeSelectYear = $selectYear;
        }
        if (!is_null($selectMonth) AND is_numeric($selectMonth)) {
            $this->completeSelectMonth = $selectMonth;
        }

        $sql = "SELECT " .
                       " DISTINCT(DATE_FORMAT(`times`, '%Y-%m')) AS times " .
                       " , DATE_FORMAT(`times`, '%Y') AS years " .
                       " , DATE_FORMAT(`times`, '%c') AS months " .
               " FROM `rarry_complete_games` " .
               " WHERE `r_id` = " . $rarryId . " " .
               " ORDER BY times " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[schejuluData]¥∞Œª•π•±•∏•Â°º•Î≈–œø∫—§ﬂ«Ø∑Ó•«°º•ø•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return $this->GetLastError(); }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){
            for ($i = 0; $i < $num; $i++) {
                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù
                $this->completeGameYearData[$data["years"]][] = $data["months"];
            }
            if (!isset($this->completeSelectYear) OR !isset($this->completeSelectMonth)) {
                $this->completeSelectYear = $data["years"];
                $this->completeSelectMonth = $data["months"];
            }
            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }





    //------------------------------------------------------
    //
    //------------------------------------------------------
    /*
     * •π•±•∏•Â°º•Î•«°º•ø§Ú ÷§π
     */
    function getSchejuleData() {
        return $this->schejuleData;
    }

    /*
     * ¬–¿Ô•¡°º•‡§Œ¬–¿Ô¿·§Ú ÷§π
     */
    function getBothNotTeamSection() {
        return $this->bothNotTeamSection;
    }

    /*
     * ªÿƒÍ§Œ•π•±•∏•Â°º•Î•«°º•ø§Ú ÷§π
     */
    function getSelectSchejuleData() {
        return $this->selectSchejuleData;
    }

    /*
     * ªÿƒÍ∆¸§Œ•π•±•∏•Â°º•Î•«°º•ø§Ú ÷§π
     */
    function getSchejuleDatesData() {
        return $this->SchejuleDatesData;
    }

    /*
     * ªÿƒÍ¬Á≤Ò§Œ≥´∫≈«Ø∑Ó•π•±•∏•Â°º•Î•«°º•ø§Ú ÷§π
     */
    function getCompleteGameYearData() {
        return $this->completeGameYearData;
    }

}

?>
