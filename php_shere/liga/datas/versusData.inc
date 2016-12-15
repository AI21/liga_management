<?php

// 設定ファイル読み込み
//require_once "connectionDB.inc";

class versusData extends connectionDBClass {

	private $gameReport;

	/**
	 * コンストラクター.
	 */
	public function __construct() {
		$this->GameReport["report"] = '';
		$this->GameReport["home_best"] = '';
		$this->GameReport["away_best"] = '';
		$this->GameReport["picture1"] = null;
		$this->GameReport["picture2"] = null;
		$this->GameReport["picture3"] = null;
    }

    /**
     * 概要 : 対戦成績
     *
     * 説明
     *
     * @param $gameId     試合ID
     * @param $rarryId    大会ID
     * @param $season     シーズン
     * @param $class      リーグクラス
     * @param $section    節
     * @param $teamId     チームID
     * @param $vsTeamId   対戦相手チームID
	 */
    public function versusDetailData($gameId, $rarryId, $season, $class) {

	    $retData = False;

        // ホーム・アウェイチームを元にチームスコアを取得する
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
            if(!$rs){ print "[versusData]対戦成績 取得に失敗しました<br />".$sql."<br />"; return false; }

            // データ数を取得
            $num        = $this->GetRowCount($rs);

            if($num > 0){

                // チーム詳細データクラス
//                $teamInfo = new teamData();

                $data   = $this->FetchRow($rs);       // １行Ｇｅｔ

/*
                $teamId = $data["TEAM_ID"];
                $vsTeamId = $data["VS_TEAM"];

                // チーム名の取得
                if ($teamInfo->selectTeamData($teamId) == True) {
                    $teamName = $teamInfo->getTeamName();
                }
                // 対戦チーム名の取得
                if ($teamInfo->selectTeamData($vsTeamId) == True) {
                    $vsTeamName = $teamInfo->getTeamName();
                }
*/

                // クォーターの点数を配列データにする
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

                // ゲームレポートの取得
                $this->getGameReport($gameId);

                $retData = true;
            }

            // メモリ解放
            $this->FreeQuery($rs);
/*
        }

        // メモリ解放
        $this->FreeQuery($schRs);
*/
        return $retData;
    }


    /**
     * 概要 : 節毎の対戦成績
     *
     * 説明
     *
     * @param $rarryId    大会ID
     * @param $class      リーグクラス
     * @param $section    節
	 */
    public function selectSectionVersusData($rarryId, $season, $class, $mode, $section, $dates) {

        $retData = False;

        $sqlWhere = "";

        switch ($mode) {

          // 節指定のとき
          case "sec" :
            $sqlWhere = "  AND  LSC.`section` = '" . $section . "' " .
                        "  AND  LS.`section` = '" . $section . "' ";
          break;
          // 日付指定のとき
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

        // MySQLバージョン5以上(結果からの副問い合わせで得点を出力)
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
        if(!$rs){ print "[versusData]節毎の対戦成績データ取得エラーです。<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // １行Ｇｅｔ

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

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : チーム通算対戦成績
     *
     * 説明
     *
     * @param $teamId     チームID
     * @param $rarryId    大会ID
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
        if(!$rs){ print "[versusData]対戦成績 取得に失敗しました<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // １行Ｇｅｔ

                // クォーターの点数を配列データにする
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

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    // ゲームレポートの取得
    private function getGameReport($gameId) {

	    $ret = false;

        $sql = " SELECT " .
                        " `report`, " .
                        " `home_best`, " .
                        " `away_best`, " .
                        " `report`, " .
                        " `picture1`, " .
                        " `picture2`, " .
                        " `picture3`, " .
                        " `picture4`, " .
                        " `picture5`, " .
                        " `picture6`, " .
                        " `picture7`, " .
                        " `picture8`, " .
                        " `picture9`, " .
                        " `picture10`, " .
                        " `picture1_thumb`, " .
                        " `picture2_thumb`, " .
                        " `picture3_thumb`, " .
                        " `picture4_thumb`, " .
                        " `picture5_thumb`, " .
                        " `picture6_thumb`, " .
                        " `picture7_thumb`, " .
                        " `picture8_thumb`, " .
                        " `picture9_thumb`, " .
                        " `picture10_thumb` " .
               " FROM `rarry_scores_report` " .
               " WHERE `game_id` = " . $gameId . " ";
//print $sql."<BR>";

        $rs  = $this->Query($sql);
        if(!$rs){ print "[versusData]対戦成績 取得に失敗しました<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if($num > 0){


        	$ret = true;

			$data   = $this->FetchRow($rs);       // 1行Ｇｅｔ

			$this->GameReport["report"]     = $data["report"];
			$this->GameReport["home_best"]     = $data["home_best"];
			$this->GameReport["away_best"]     = $data["away_best"];
			$this->GameReport["picture1"]   = $data["picture1"];
			$this->GameReport["picture2"]   = $data["picture2"];
			$this->GameReport["picture3"]   = $data["picture3"];
			$this->GameReport["picture4"]   = $data["picture4"];
			$this->GameReport["picture5"]   = $data["picture5"];
			$this->GameReport["picture6"]   = $data["picture6"];
			$this->GameReport["picture7"]   = $data["picture7"];
			$this->GameReport["picture8"]   = $data["picture8"];
			$this->GameReport["picture9"]   = $data["picture9"];
			$this->GameReport["picture10"]   = $data["picture10"];
			$this->GameReport["picture1_thumb"]   = $data["picture1_thumb"];
			$this->GameReport["picture2_thumb"]   = $data["picture2_thumb"];
			$this->GameReport["picture3_thumb"]   = $data["picture3_thumb"];
			$this->GameReport["picture4_thumb"]   = $data["picture4_thumb"];
			$this->GameReport["picture5_thumb"]   = $data["picture5_thumb"];
			$this->GameReport["picture6_thumb"]   = $data["picture6_thumb"];
			$this->GameReport["picture7_thumb"]   = $data["picture7_thumb"];
			$this->GameReport["picture8_thumb"]   = $data["picture8_thumb"];
			$this->GameReport["picture9_thumb"]   = $data["picture9_thumb"];
			$this->GameReport["picture10_thumb"]   = $data["picture10_thumb"];
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $ret;
    }


    /*
     * 通算対戦成績を返す
     */
    public function getTeamSeasonVersusData() {
        return $this->TeamSeasonVersusData;
    }

    /*
     * 対戦クラスを返す
     */
    public function getClass() {
        return $this->CLASS;
    }

    /*
     * 対戦節を返す
     */
    public function getSection() {
        return $this->SECTION;
    }

    /*
     * 試合会場名を返す
     */
    public function getHallName() {
        return $this->HALL_NAME;
    }

    /*
     * 試合会場コート名を返す
     */
    public function getHallCortName() {
        return $this->CORT_NAME;
    }

    /*
     * 試合会場名(サブ)を返す
     */
    public function getHallSubName() {
        return $this->HALL_SUB_NAME;
    }

    /*
     * チームIDを返す
     */
    public function getTeamId() {
        return $this->TEAM_ID;
    }

    /*
     * チーム名を返す
     */
    public function getTeamName() {
        return $this->TEAM_NAME;
    }

    /*
     * チーム合計スコアを返す
     */
    public function getTeamScore() {
        return $this->TEAM_SCORE;
    }

    /*
     * クォータースコアデータを返す
     */
    public function getQuarterData() {
        return $this->Quarter;
    }

    /*
     * 対戦チームIDを返す
     */
    public function getVsTeamId() {
        return $this->VS_TEAM;
    }

    /*
     * 対戦チーム名を返す
     */
    public function getVsTeamName() {
        return $this->VS_TEAM_NAME;
    }

    /*
     * 対戦チーム合計スコアを返す
     */
    public function getVsTeamScore() {
        return $this->VS_TEAM_SCORE;
    }

    /*
     * 対戦チームのクォータースコアデータを返す
     */
    public function getVsQuarterData() {
        return $this->VsQuarter;
    }

    /*
     * 対戦日を返す
     */
    public function getExeDate() {
        return $this->EXE_DATE;
    }

    /*
     * 対戦日(年)を返す
     */
    public function getExeYear() {
        return $this->EXE_YEAR;
    }

    /*
     * 対戦日(月)を返す
     */
    public function getExeMonth() {
        return $this->EXE_MONTH;
    }

    /*
     * 対戦日(日)を返す
     */
    public function getExeDay() {
        return $this->EXE_DAY;
    }

    /*
     * HOMEチームフラグを返す
     */
    public function getHomeFlag() {
        return $this->HomeFlag;
    }

    /*
     * 不戦フラグを返す
     */
    public function getAntiwar() {
        return $this->Antiwar;
    }

    /*
     * 没収試合フラグを返す
     */
    public function getSeizure() {
        return $this->Seizure;
    }

    /*
     * 特殊ルールフラグを返す
     */
    public function getSpecial() {
        return $this->Special;
    }

    /*
     * 節毎のゲームデータを返す
     */
    public function getSectionGameData() {
        return $this->sectionGameData;
    }

    /*
     * ゲームレポートを返す
     */
    public function getGameReportDatas() {
        return $this->GameReport;
    }

}

?>
