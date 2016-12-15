<?php

// ¿ﬂƒÍ•’•°•§•Î∆…§ﬂπ˛§ﬂ
//require_once "connectionDB.inc";

class versusData extends connectionDBClass {

	private $gameReport;

	/**
	 * •≥•Û•π•»•È•Ø•ø°º.
	 */
	public function __construct() {
		$this->GameReport["report"] = '';
		$this->GameReport["picture1"] = null;
		$this->GameReport["picture2"] = null;
		$this->GameReport["picture3"] = null;
    }

    /**
     * ≥µÕ◊ : ¬–¿Ô¿Æ¿”
     *
     * ¿‚Ã¿
     *
     * @param $gameId     ªÓπÁID
     * @param $rarryId    ¬Á≤ÒID
     * @param $season     •∑°º•∫•Û
     * @param $class      •Í°º•∞•Ø•È•π
     * @param $section    ¿·
     * @param $teamId     •¡°º•‡ID
     * @param $vsTeamId   ¬–¿Ô¡ÍºÍ•¡°º•‡ID
	 */
    public function versusDetailData($gameId, $rarryId, $season, $class) {

	    $retData = False;

        // •€°º•‡°¶•¢•¶•ß•§•¡°º•‡§Ú∏µ§À•¡°º•‡•π•≥•¢§ÚºË∆¿§π§Î
        $sql = " SELECT " .
                        " RCG.`class` AS CLASS, " .
                        " RCG.`section` AS SECTION, " .
                        " MHI.`h_name` AS HALL_NAME, " .
                        " MCI.`court_name` AS CORT_NAME, " .
                        //" MHI.`sub_name` AS HALL_SUB_NAME, " .
                        " HSCO.`t_id` AS TEAM_ID, " .
                        " ASCO.`t_id` AS VS_TEAM, " .
                        " HRT.`team_name` AS TEAM_NAME, " .
                        " ART.`team_name` AS VS_TEAM_NAME, " .
                        " HSCO.`1st` AS team_1st, " .
                        " HSCO.`2nd` AS team_2nd, " .
                        " HSCO.`3rd` AS team_3rd, " .
                        " HSCO.`4th` AS team_4th, " .
                        " (HSCO.`1st` + HSCO.`2nd` + HSCO.`3rd` + HSCO.`4th`) AS team_score, " .
                        " ASCO.`1st` AS vs_1st, " .
                        " ASCO.`2nd` AS vs_2nd, " .
                        " ASCO.`3rd` AS vs_3rd, " .
                        " ASCO.`4th` AS vs_4th, " .
                        " (ASCO.`1st` + ASCO.`2nd` + ASCO.`3rd` + ASCO.`4th`) AS vs_team_score, " .
                        " RCG.`times` AS EXE_DATE, " .
                        " DATE_FORMAT(RCG.`times`, '%Y') AS EXE_YEAR, " .
                        " DATE_FORMAT(RCG.`times`, '%m') AS EXE_MONTH, " .
                        " DATE_FORMAT(RCG.`times`, '%d') AS EXE_DAY, " .
                        " HSCO.`antiwar` AS antiwar, " .
                        " HSCO.`seizure` AS seizure, " .
                        " HSCO.`special` AS special, " .
                        " ASCO.`antiwar` AS away_antiwar, " .
                        " ASCO.`seizure` AS away_seizure, " .
                        " ASCO.`special` AS away_special " .
                " FROM `rarry_complete_games` RCG " .
                " LEFT JOIN `master_court_informations` MCI " .
                "  ON   RCG.`court` = MCI.`id` " .
                " LEFT JOIN `master_hall_informations` MHI " .
                "  ON   MHI.`id` = MCI.`hall_id` " .
                " LEFT JOIN `rarry_scores` HSCO " .
                "  ON  RCG.`id` = HSCO.`g_id` " .
                "  AND RCG.`home_team` = HSCO.`t_id` " .
                "  AND HSCO.`g_id` = '" . $gameId . "' " .
                " LEFT JOIN `regist_teams` HRT " .
                "  ON  HSCO.`r_id` = HRT.`r_id` " .
                "  AND HSCO.`t_id` = HRT.`t_id` " .
                " LEFT JOIN `rarry_scores` ASCO " .
                "  ON  RCG.`id` = ASCO.`g_id` " .
                "  AND RCG.`away_team` = ASCO.`t_id` " .
                "  AND HSCO.`g_id` = '" . $gameId . "' " .
                " LEFT JOIN `regist_teams` ART " .
                "  ON  ASCO.`r_id` = ART.`r_id` " .
                "  AND ASCO.`t_id` = ART.`t_id` " .
                " WHERE RCG.`id` = '" . $gameId . "' " ;
//                   "  AND  LS.`team_id` = '" . $homeTeam . "' " .
//                   "  AND  LS.`vs_team` = '" . $awayTeam . "' " .
//                   "  AND  LRI.`define` = '" . $define . "' " /*.
//                   "  AND  LS.`class` = '" . $class . "' "*/ ;
//print $sql."<BR>";

            $rs  = $this->Query($sql);
            if(!$rs){ print "[versusData]¬–¿Ô¿Æ¿” ºË∆¿§Àº∫«‘§∑§ﬁ§∑§ø<br />".$sql."<br />"; return false; }

            // •«°º•øøÙ§ÚºË∆¿
            $num        = $this->GetRowCount($rs);

            if($num > 0){

                // •¡°º•‡æ‹∫Ÿ•«°º•ø•Ø•È•π
//                $teamInfo = new teamData();

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

/*
                $teamId = $data["TEAM_ID"];
                $vsTeamId = $data["VS_TEAM"];

                // •¡°º•‡Ãæ§ŒºË∆¿
                if ($teamInfo->selectTeamData($teamId) == True) {
                    $teamName = $teamInfo->getTeamName();
                }
                // ¬–¿Ô•¡°º•‡Ãæ§ŒºË∆¿
                if ($teamInfo->selectTeamData($vsTeamId) == True) {
                    $vsTeamName = $teamInfo->getTeamName();
                }
*/

                // •Ø•©°º•ø°º§Œ≈¿øÙ§Ú«€ŒÛ•«°º•ø§À§π§Î
                $this->Quarter["team_1st"]   = $data["team_1st"];
                $this->Quarter["team_2nd"]   = $data["team_2nd"];
                $this->Quarter["team_3rd"]   = $data["team_3rd"];
                $this->Quarter["team_4th"]   = $data["team_4th"];
                $this->VsQuarter["vs_1st"]   = $data["vs_1st"];
                $this->VsQuarter["vs_2nd"]   = $data["vs_2nd"];
                $this->VsQuarter["vs_3rd"]   = $data["vs_3rd"];
                $this->VsQuarter["vs_4th"]   = $data["vs_4th"];

                $this->CLASS           		= $data["CLASS"];
                $this->SECTION               = $data["SECTION"];
                $this->HALL_NAME             = $data["HALL_NAME"];
                $this->CORT_NAME             = $data["CORT_NAME"];
                //$this->HALL_SUB_NAME       = $data["HALL_SUB_NAME"];
                $this->TEAM_ID               = $data["TEAM_ID"];
                //$this->TEAM_NAME           = $teamName;
                $this->TEAM_NAME             = $data["TEAM_NAME"];
                $this->TEAM_SCORE            = $data["team_score"];
                $this->VS_TEAM               = $data["VS_TEAM"];
                //$this->VS_TEAM_NAME        = $vsTeamName;
                $this->VS_TEAM_NAME          = $data["VS_TEAM_NAME"];
                $this->VS_TEAM_SCORE         = $data["vs_team_score"];
                $this->EXE_DATE              = $data["EXE_DATE"];
                $this->EXE_YEAR              = $data["EXE_YEAR"];
                $this->EXE_MONTH             = $data["EXE_MONTH"];
                $this->EXE_DAY               = $data["EXE_DAY"];
                $this->HomeFlag              = $data["home_flag"];
                $this->Antiwar               = $data["antiwar"];
                $this->Seizure               = $data["seizure"];
                $this->Special               = $data["special"];

                // •≤°º•‡•Ï•›°º•»§ŒºË∆¿
                $this->getGameReport($gameId);

                $retData = true;
            }

            // •·•‚•Í≤Ú ¸
            $this->FreeQuery($rs);
/*
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($schRs);
*/
        return $retData;
    }


    /**
     * ≥µÕ◊ : ¿·ÀË§Œ¬–¿Ô¿Æ¿”
     *
     * ¿‚Ã¿
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $class      •Í°º•∞•Ø•È•π
     * @param $section    ¿·
	 */
    public function selectSectionVersusData($rarryId, $season, $class, $mode, $section, $dates) {

        $retData = False;

        $sqlWhere = "";

        switch ($mode) {

          // ¿·ªÿƒÍ§Œ§»§≠
          case "sec" :
            $sqlWhere = "  AND  LSC.`section` = '" . $section . "' " .
                        "  AND  LS.`section` = '" . $section . "' ";
          break;
          // ∆¸…’ªÿƒÍ§Œ§»§≠
          case "days" :
            $sqlWhere = "  AND  DATE_FORMAT(LSC.`times`, '%Y-%m-%d') = '" . $dates . "' " .
                        "  AND  LS.`exe_days` = '" . $dates . "' ";
          break;
        }

        $sql = " SELECT " .
                      //" LSC.`id` AS GAME_ID, " .
                      " LS.`game_id` AS GAME_ID, " .
#                      " LSC.`id` AS GAME_ID, " .
                      " LSC.`home_team` AS HOME_ID, " .
                      " LSC.`away_team` AS AWAY_ID, " .
                      //" LTIA.`t_name` AS HOME_NAME, " .
                      //" LTIB.`t_name` AS AWAY_NAME, " .
                      " HLRT.`team_name` AS HOME_NAME, " .
                      " ALRT.`team_name` AS AWAY_NAME, " .
                      " (LS.`team_1st` + LS.`team_2nd` + LS.`team_3rd` + LS.`team_4th`) AS HOME_SCORE, " .
                      " (LS.`vs_1st`   + LS.`vs_2nd`   + LS.`vs_3rd`   + LS.`vs_4th`)   AS AWAY_SCORE " .
#                      " (LSH.`team_1st` + LSH.`team_2nd` + LSH.`team_3rd` + LSH.`team_4th`) AS HOME_SCORE, " .
#                      " (LSA.`vs_1st`   + LSA.`vs_2nd`   + LSA.`vs_3rd`   + LSA.`vs_4th`)   AS AWAY_SCORE " .
               //" FROM `lt_rarry_info` LRI, `lt_schejule` LSC, `lt_team_info` LTIA, `lt_team_info` LTIB, `lt_score` LS " .
               " FROM `lt_rarry_info` LRI, `lt_schejule` LSC, `lt_team_info` LTIB, `lt_score` LS " .
                   " LEFT JOIN `lt_regist_team` HLRT " .
                   "   ON LS.`team_id` = HLRT.`t_id` " .
                   "   AND HLRT.`r_id` = '" . $rarryId . "' " .
                   " LEFT JOIN `lt_regist_team` ALRT " .
                   "   ON LS.`vs_team` = ALRT.`t_id` " .
                   "   AND ALRT.`r_id` = '" . $rarryId . "' " .
               " WHERE LRI.`r_id` = LSC.`r_id` " .
               //"  AND  LSC.`home_team` = LTIA.`t_id` " .
               "  AND  LSC.`away_team` = LTIB.`t_id` " .
               "  AND  LSC.`home_team` = LS.`team_id` " .
               "  AND  LSC.`away_team` = LS.`vs_team` " .
#               " FROM `lt_rarry_info` LRI, `lt_schejule` LSC
#			   LEFT JOIN `lt_team_info` LTIA ON LSC.`home_team` = LTIA.`t_id`
#			   LEFT JOIN `lt_team_info` LTIB ON LSC.`away_team` = LTIB.`t_id`
#			   LEFT JOIN `lt_score` LSH ON LSC.`home_team` = LSH.`team_id`   AND  LSH.`exe_days` = '" . $dates . "'
#			   LEFT JOIN `lt_score` LSA ON LSC.`away_team` = LSA.`vs_team`   AND  LSH.`exe_days` = '" . $dates . "'" .
#               " WHERE LRI.`r_id` = LSC.`r_id` " .

               "  AND  LRI.`r_id` = '" . $rarryId . "' " .
               "  AND  LSC.`class` = '" . $class . "' " .
               //"  AND  LSC.`section` = '" . $section . "' " .
               //"  AND  LS.`section` = '" . $section . "' " .
               //"  AND  LSC.`times` = '2008-01-06' " .
               //"  AND  LS.`exe_days` = '2008-01-06' " .
               $sqlWhere .
               " GROUP BY LSC.`id` " .
               " ORDER BY LSC.`times` ";
/*
        $sql = " SELECT " .
                      //" LSC.`id` AS GAME_ID, " .
                      " LS.`id` AS GAME_ID, " .
                      " LSC.`home_team` AS HOME_ID, " .
                      " LSC.`away_team` AS AWAY_ID, " .
                      " LTIA.`t_name` AS HOME_NAME, " .
                      " LTIB.`t_name` AS AWAY_NAME, " .
                      " (LS.`team_1st` + LS.`team_2nd` + LS.`team_3rd` + LS.`team_4th`) AS HOME_SCORE, " .
                      " (LS.`vs_1st`   + LS.`vs_2nd`   + LS.`vs_3rd`   + LS.`vs_4th`)   AS AWAY_SCORE " .
               " FROM `lt_rarry_info` LRI, `lt_schejule` LSC, `lt_team_info` LTIA, `lt_team_info` LTIB, `lt_score` LS " .
               " WHERE LRI.`r_id` = LSC.`r_id` " .
               "  AND  LSC.`home_team` = LTIA.`t_id` " .
               "  AND  LSC.`AWAY_team` = LTIB.`t_id` " .
               "  AND  LSC.`home_team` = LS.`team_id` " .
               //"  AND  LSC.`home_team` = LS.`team_id` " .
               "  AND  LRI.`r_id` = '" . $rarryId . "' " .
               "  AND  LSC.`class` = '" . $class . "' " .
               "  AND  LSC.`section` = '" . $section . "' " .
               "  AND  LS.`section` = '" . $section . "' " .
               //$sqlWhere .
               " GROUP BY LSC.`id` " .
               " ORDER BY LSC.`times` ";

        // MySQL•–°º•∏•Á•Û5∞ æÂ(∑Î≤Ã§´§È§Œ…˚Ã‰§§πÁ§Ô§ª§«∆¿≈¿§ÚΩ–Œœ)
        $sql = " SELECT " .
                      " LSC.`id` AS GAME_ID, " .
                      " LSC.`home_team` AS HOME_ID, " .
                      " LSC.`away_team` AS AWAY_ID, " .
                      " LTIA.`t_name` AS HOME_NAME, " .
                      " LTIB.`t_name` AS AWAY_NAME, " .
                      " (SELECT " .
                      "         (`team_1st` + `team_2nd` + `team_3rd` + `team_4th`) " .
                      "  FROM `lt_score` " .
                      "  WHERE `r_id` = '" . $rarryId . "' " .
                      "   AND  `game_id` = GAME_ID " .
                      "   AND  `team_id` = HOME_ID " .
                      "   AND  `class` = '" . $class . "' " .
                      "   AND  `section` = '" . $section . "' " .
                      " ) AS HOME_SCORE, " .
                      " (SELECT " .
                      "        (`vs_1st` + `vs_2nd` + `vs_3rd` + `vs_4th`) " .
                      "  FROM `lt_score` " .
                      "  WHERE `r_id` = '" . $rarryId . "' " .
                      "   AND  `game_id` = GAME_ID " .
                      "   AND  `vs_team` = AWAY_ID " .
                      "   AND  `class` = '" . $class . "' " .
                      "   AND  `section` = '" . $section . "' " .
                      "  ) AS AWAY_SCORE " .
               " FROM `lt_rarry_info` LRI, `lt_schejule` LSC, `lt_team_info` LTIA, `lt_team_info` LTIB " .
               " WHERE LRI.`r_id` = LSC.`r_id` " .
               "  AND  LSC.`home_team` = LTIA.`t_id` " .
               "  AND  LSC.`AWAY_team` = LTIB.`t_id` " .
               "  AND  LRI.`r_id` = '" . $rarryId . "' " .
               "  AND  LSC.`class` = '" . $class . "' " .
               "  AND  LSC.`section` = '" . $section . "' " .
               //$sqlWhere .
               //" GROUP BY LSC.`id` " .
               " ORDER BY LSC.`times` ";
*/
//print $sql."<BR>";

        $rs  = $this->Query($sql);
        if(!$rs){ print "[versusData]¿·ÀË§Œ¬–¿Ô¿Æ¿”•«°º•øºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                $this->sectionGameData[$i]["GAME_ID"] = $data["GAME_ID"];
                $this->sectionGameData[$i]["HOME_ID"] = $data["HOME_ID"];
                $this->sectionGameData[$i]["AWAY_ID"] = $data["AWAY_ID"];
                $this->sectionGameData[$i]["HOME_NAME"] = $data["HOME_NAME"];
                $this->sectionGameData[$i]["AWAY_NAME"] = $data["AWAY_NAME"];
                $this->sectionGameData[$i]["HOME_SCORE"] = $data["HOME_SCORE"];
                $this->sectionGameData[$i]["AWAY_SCORE"] = $data["AWAY_SCORE"];
            }

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •¡°º•‡ƒÃªª¬–¿Ô¿Æ¿”
     *
     * ¿‚Ã¿
     *
     * @param $teamId     •¡°º•‡ID
     * @param $rarryId    ¬Á≤ÒID
	 */
    public function teamSeasonVersusData($teamId, $rarryId) {

	    $retData = False;

        $sql = " SELECT " .
                        " LS.`game_id` AS GAME_ID, " .
                        " LS.`section` AS SECTION, " .
                        " MHI.`h_name` AS HALL_NAME, " .
                        " MHC.`cort_name` AS CORT_NAME, " .
                        //" MHI.`sub_name` AS HALL_SUB_NAME, " .
                        " LS.`team_id` AS TEAM_ID, " .
                        //" HLTI.`t_name` AS TEAM_NAME, " .
                        " HLRT.`team_name` AS TEAM_NAME, " .
                        " LS.`vs_team` AS VS_TEAM, " .
                        //" ALTI.`t_name` AS VS_NAME, " .
                        " ALRT.`team_name` AS VS_NAME, " .
                        " LS.`team_1st` AS team_1st, " .
                        " LS.`team_2nd` AS team_2nd, " .
                        " LS.`team_3rd` AS team_3rd, " .
                        " LS.`team_4th` AS team_4th, " .
                        " (LS.`team_1st` + LS.`team_2nd` + LS.`team_3rd` + LS.`team_4th`) AS team_score, " .
                        " LS.`vs_1st` AS vs_1st, " .
                        " LS.`vs_2nd` AS vs_2nd, " .
                        " LS.`vs_3rd` AS vs_3rd, " .
                        " LS.`vs_4th` AS vs_4th, " .
                        " (LS.`vs_1st` + LS.`vs_2nd` + LS.`vs_3rd` + LS.`vs_4th`) AS vs_team_score, " .
                        " LS.`exe_days` AS EXE_DATE, " .
                        " DATE_FORMAT(LS.`exe_days`, '%Y') AS EXE_YEAR, " .
                        " DATE_FORMAT(LS.`exe_days`, '%m') AS EXE_MONTH, " .
                        " DATE_FORMAT(LS.`exe_days`, '%d') AS EXE_DAY, " .
                        " LS.`home_flag`, " .
                        " LS.`antiwar` AS antiwar, " .
                        " LS.`seizure` AS seizure, " .
                        " LS.`special` AS special " .
               " FROM `lt_rarry_info` LRI, `lt_score` LS " .
               " LEFT JOIN `m_hall_cort` MHC " .
               "  ON   LS.`hall` = MHC.`cort_id` " .
               //" LEFT JOIN `lt_team_info` HLTI " .
               //"  ON   LS.`team_id` = HLTI.`t_id` " .
               //" LEFT JOIN `lt_team_info` ALTI " .
               //"  ON   LS.`vs_team` = ALTI.`t_id` " .
               " LEFT JOIN `lt_regist_team` HLRT " .
               "  ON   LS.`team_id` = HLRT.`t_id` " .
               "  AND  HLRT.`r_id` = '" . $rarryId . "' " .
               " LEFT JOIN `lt_regist_team` ALRT " .
               "  ON   LS.`vs_team` = ALRT.`t_id` " .
               "  AND  ALRT.`r_id` = '" . $rarryId . "' " .
               " LEFT JOIN `m_hall_info` MHI " .
               "  ON   MHC.`hall_id` = MHI.`h_id` " .
               " WHERE LRI.`r_id` = '" . $rarryId . "' " .
               "  AND  LS.`r_id` = '" . $rarryId . "' " .
               "  AND  LS.`team_id` = '" . $teamId . "' " .
               " ORDER BY LS.`section` DESC " ;
//print $sql."<BR>";

        $rs  = $this->Query($sql);
        if(!$rs){ print "[versusData]¬–¿Ô¿Æ¿” ºË∆¿§Àº∫«‘§∑§ﬁ§∑§ø<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // •Ø•©°º•ø°º§Œ≈¿øÙ§Ú«€ŒÛ•«°º•ø§À§π§Î
                $this->TeamSeasonVersusData[$i]["Quarter"]["team_1st"]   = $data["team_1st"];
                $this->TeamSeasonVersusData[$i]["Quarter"]["team_2nd"]   = $data["team_2nd"];
                $this->TeamSeasonVersusData[$i]["Quarter"]["team_3rd"]   = $data["team_3rd"];
                $this->TeamSeasonVersusData[$i]["Quarter"]["team_4th"]   = $data["team_4th"];
                $this->TeamSeasonVersusData[$i]["VsQuarter"]["vs_1st"]     = $data["vs_1st"];
                $this->TeamSeasonVersusData[$i]["VsQuarter"]["vs_2nd"]     = $data["vs_2nd"];
                $this->TeamSeasonVersusData[$i]["VsQuarter"]["vs_3rd"]     = $data["vs_3rd"];
                $this->TeamSeasonVersusData[$i]["VsQuarter"]["vs_4th"]     = $data["vs_4th"];

                $this->TeamSeasonVersusData[$i]["GAME_ID"]               = $data["GAME_ID"];
                $this->TeamSeasonVersusData[$i]["SECTION"]               = $data["SECTION"];
                $this->TeamSeasonVersusData[$i]["HALL_NAME"]             = $data["HALL_NAME"];
                //$this->TeamSeasonVersusData[$i]["HALL_SUB_NAME"]         = $data["HALL_SUB_NAME"];
                $this->TeamSeasonVersusData[$i]["TEAM_ID"]               = $data["TEAM_ID"];
                $this->TeamSeasonVersusData[$i]["TEAM_NAME"]             = $data["TEAM_NAME"];
                $this->TeamSeasonVersusData[$i]["team_score"]            = $data["team_score"];
                $this->TeamSeasonVersusData[$i]["VS_TEAM"]               = $data["VS_TEAM"];
                $this->TeamSeasonVersusData[$i]["VS_NAME"]               = $data["VS_NAME"];
                $this->TeamSeasonVersusData[$i]["vs_team_score"]         = $data["vs_team_score"];
                $this->TeamSeasonVersusData[$i]["EXE_DATE"]              = $data["EXE_DATE"];
                $this->TeamSeasonVersusData[$i]["EXE_YEAR"]              = $data["EXE_YEAR"];
                $this->TeamSeasonVersusData[$i]["EXE_MONTH"]             = $data["EXE_MONTH"];
                $this->TeamSeasonVersusData[$i]["EXE_DAY"]               = $data["EXE_DAY"];
                $this->TeamSeasonVersusData[$i]["home_flag"]             = $data["home_flag"];
                $this->TeamSeasonVersusData[$i]["antiwar"]               = $data["antiwar"];
                $this->TeamSeasonVersusData[$i]["seizure"]               = $data["seizure"];
                $this->TeamSeasonVersusData[$i]["special"]               = $data["special"];
            }
            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    // •≤°º•‡•Ï•›°º•»§ŒºË∆¿
    private function getGameReport($gameId) {

	    $ret = false;

        $sql = " SELECT " .
                        " `report`, " .
                        " `picture1`, " .
                        " `picture2`, " .
                        " `picture3` " .
               " FROM `rarry_scores_report` " .
               " WHERE `game_id` = " . $gameId . " ";
//print $sql."<BR>";

        $rs  = $this->Query($sql);
        if(!$rs){ print "[versusData]¬–¿Ô¿Æ¿” ºË∆¿§Àº∫«‘§∑§ﬁ§∑§ø<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){


        	$ret = true;

			$data   = $this->FetchRow($rs);       // 1π‘£«£Â£Ù

			$this->GameReport["report"]     = $data["report"];
			$this->GameReport["picture1"]   = $data["picture1"];
			$this->GameReport["picture2"]   = $data["picture2"];
			$this->GameReport["picture3"]   = $data["picture3"];
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $ret;
    }


    /*
     * ƒÃªª¬–¿Ô¿Æ¿”§Ú ÷§π
     */
    public function getTeamSeasonVersusData() {
        return $this->TeamSeasonVersusData;
    }

    /*
     * ¬–¿Ô•Ø•È•π§Ú ÷§π
     */
    public function getClass() {
        return $this->CLASS;
    }

    /*
     * ¬–¿Ô¿·§Ú ÷§π
     */
    public function getSection() {
        return $this->SECTION;
    }

    /*
     * ªÓπÁ≤ÒæÏÃæ§Ú ÷§π
     */
    public function getHallName() {
        return $this->HALL_NAME;
    }

    /*
     * ªÓπÁ≤ÒæÏ•≥°º•»Ãæ§Ú ÷§π
     */
    public function getHallCortName() {
        return $this->CORT_NAME;
    }

    /*
     * ªÓπÁ≤ÒæÏÃæ(•µ•÷)§Ú ÷§π
     */
    public function getHallSubName() {
        return $this->HALL_SUB_NAME;
    }

    /*
     * •¡°º•‡ID§Ú ÷§π
     */
    public function getTeamId() {
        return $this->TEAM_ID;
    }

    /*
     * •¡°º•‡Ãæ§Ú ÷§π
     */
    public function getTeamName() {
        return $this->TEAM_NAME;
    }

    /*
     * •¡°º•‡πÁ∑◊•π•≥•¢§Ú ÷§π
     */
    public function getTeamScore() {
        return $this->TEAM_SCORE;
    }

    /*
     * •Ø•©°º•ø°º•π•≥•¢•«°º•ø§Ú ÷§π
     */
    public function getQuarterData() {
        return $this->Quarter;
    }

    /*
     * ¬–¿Ô•¡°º•‡ID§Ú ÷§π
     */
    public function getVsTeamId() {
        return $this->VS_TEAM;
    }

    /*
     * ¬–¿Ô•¡°º•‡Ãæ§Ú ÷§π
     */
    public function getVsTeamName() {
        return $this->VS_TEAM_NAME;
    }

    /*
     * ¬–¿Ô•¡°º•‡πÁ∑◊•π•≥•¢§Ú ÷§π
     */
    public function getVsTeamScore() {
        return $this->VS_TEAM_SCORE;
    }

    /*
     * ¬–¿Ô•¡°º•‡§Œ•Ø•©°º•ø°º•π•≥•¢•«°º•ø§Ú ÷§π
     */
    public function getVsQuarterData() {
        return $this->VsQuarter;
    }

    /*
     * ¬–¿Ô∆¸§Ú ÷§π
     */
    public function getExeDate() {
        return $this->EXE_DATE;
    }

    /*
     * ¬–¿Ô∆¸(«Ø)§Ú ÷§π
     */
    public function getExeYear() {
        return $this->EXE_YEAR;
    }

    /*
     * ¬–¿Ô∆¸(∑Ó)§Ú ÷§π
     */
    public function getExeMonth() {
        return $this->EXE_MONTH;
    }

    /*
     * ¬–¿Ô∆¸(∆¸)§Ú ÷§π
     */
    public function getExeDay() {
        return $this->EXE_DAY;
    }

    /*
     * HOME•¡°º•‡•’•È•∞§Ú ÷§π
     */
    public function getHomeFlag() {
        return $this->HomeFlag;
    }

    /*
     * …‘¿Ô•’•È•∞§Ú ÷§π
     */
    public function getAntiwar() {
        return $this->Antiwar;
    }

    /*
     * À◊º˝ªÓπÁ•’•È•∞§Ú ÷§π
     */
    public function getSeizure() {
        return $this->Seizure;
    }

    /*
     * ∆√ºÏ•Î°º•Î•’•È•∞§Ú ÷§π
     */
    public function getSpecial() {
        return $this->Special;
    }

    /*
     * ¿·ÀË§Œ•≤°º•‡•«°º•ø§Ú ÷§π
     */
    public function getSectionGameData() {
        return $this->sectionGameData;
    }

    /*
     * •≤°º•‡•Ï•›°º•»§Ú ÷§π
     */
    public function getGameReportDatas() {
        return $this->GameReport;
    }

}

?>
