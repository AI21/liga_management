<?php
    
// ¿ﬂƒÍ•’•°•§•Î∆…§ﬂπ˛§ﬂ
//require_once "connectionDB.inc";

class rankingData extends connectionDB {
    
    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }
	
    /**
     * ≥µÕ◊ : •Í°º•∞•È•Û•≠•Û•∞ΩÁ∞Ã§Œ∑◊ªª
     *
     * ¿‚Ã¿ : •Í°º•∞∑¡º∞§Œæ°§¡≈¿∑◊ªª
     *      : •€°º•‡•¡°º•‡§»•¢•¶•ß•§•¡°º•‡)§Œ∆¿º∫≈¿∫π§«æ°§¡≈¿§Ú∑Ë§·§∆πÁ∑◊§π§Î
     *      : §ø§¿§∑°¢•Í°º•∞Ω≈Õ◊≤Òµƒ…‘ª≤≤√°¶…‘¿Ôæ°°¶«‘§¨µØ§≥§√§∆§§§Ï§–Õ•¿Ë§π§Î
     *      : ∆±ª˛§À∆¿º∫≈¿∫π§‚∑◊ªª
     *
     * @param $team_id             •¡°º•‡ID
     * @param $season              •Í°º•∞«Ø≈Ÿ
     * @param $rarryId             ¬Á≤ÒID
     * @param $class               •Í°º•∞•Ø•È•π
     * @param $section             ¿·
     * @param $winPoint            æ°Õ¯ª˛§Œæ°§¡≈¿
     * @param $losePoint           «‘¿Ôª˛§Œæ°§¡≈¿
     * @param $drowPoint           ∆±≈¿ª˛§Œæ°§¡≈¿
     * @param $antiwarWinPoint     …‘¿Ôæ°ª˛§Œæ°§¡≈¿
     * @param $antiwarLosePoint    …‘¿Ô«‘ª˛§Œæ°§¡≈¿
     * @param $faulPoint           À◊º˝ªÓπÁª˛§Œæ°§¡≈¿
     * @param $inportancePoint     ¬Á≤Òµ¨ÃÛ∞„»ø§Œæ°§¡≈¿
	 */
    public function getTeamRankingData($rarryId, $class) {

        $retData = False;
        $rankBreakData = "";
        $rankBreakData2 = "";

        $sql = " SELECT " .
                      " `team_id` AS TEAM_ID, " .
                      " LRT.`team_name` AS TEAM_NAME, " .
                      " LT.`t_kana` AS TEAM_KANA, " .
                      " SUM(CASE " .
                      "          when (`special` IS NOT NULL AND `exe_days` = '0000-00-00')              then " . $inportancePoint . " " .
                      "          when `seizure` IS NOT NULL then " . $inportancePoint . " " .
                      "          when (`antiwar` IS NOT NULL AND (`team_1st` + `team_2nd` + `team_3rd` + `team_4th`) > 0 )              then " . $antiwarWinPoint . " " .
                      "          when (`antiwar` IS NOT NULL AND (`team_1st` + `team_2nd` + `team_3rd` + `team_4th`) = 0 )              then " . $antiwarLosePoint . " " .
                      "          when ((`team_1st` + `team_2nd` + `team_3rd` + `team_4th`) - (`vs_1st` + `vs_2nd` + `vs_3rd` + `vs_4th`) ) > 0 then " . $winPoint . " " .
                      "          when ((`team_1st` + `team_2nd` + `team_3rd` + `team_4th`) - (`vs_1st` + `vs_2nd` + `vs_3rd` + `vs_4th`) ) = 0 then " . $drowPoint . " " .
                      "          when ((`team_1st` + `team_2nd` + `team_3rd` + `team_4th`) - (`vs_1st` + `vs_2nd` + `vs_3rd` + `vs_4th`) ) < 0 then " . $losePoint . " " .
                      "          ELSE " . $losePoint . " END) as KATITEN, " .
                      " SUM(`team_1st` + `team_2nd` + `team_3rd` + `team_4th`) AS TOKUTEN, " .
                      " SUM(`vs_1st` + `vs_2nd` + `vs_3rd` + `vs_4th`) AS SITTEN, " .
                      " SUM(`team_1st` + `team_2nd` + `team_3rd` + `team_4th`) - SUM(`vs_1st` + `vs_2nd` + `vs_3rd` + `vs_4th`) AS TOKUSITTEN " .
               " FROM `lt_rarry_info` LRI, `lt_team_info` AS LT, `lt_score` AS LRS " .
               " LEFT JOIN `lt_regist_team` LRT " .
               "  ON   LRS.`team_id` = LRT.`t_id` " .
               "  AND  LRT.`r_id` = '" . $rarryId . "' " .
               " WHERE LRS.`team_id` = LT.`t_id` " .
               "  AND  LRI.`r_id` = '" . $rarryId . "' " .
               "  AND  LRS.`class` = '" . $class . "' " .
               " GROUP BY TEAM_ID " .
               " ORDER BY KATITEN DESC, TOKUSITTEN DESC, TEAM_KANA ";
//print $sql."<BR>";

        $rs  = $this->Query($sql);
        if(!$rs){ print "•Í°º•∞•È•Û•≠•Û•∞ΩÁ∞Ã§Œ∑◊ªª•«°º•øºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);  

        if($num > 0){

            $ranking = 0;

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                $this->TeamId[$i] = $data["TEAM_ID"];
                $this->TeamName[$i] = $data["TEAM_NAME"];

                $this->TeamRanking[$i]["team_id"] = $data["TEAM_ID"];
                $this->TeamRanking[$i]["team_name"] = $data["TEAM_NAME"];
                $this->TeamRanking[$i]["KATITEN"] = $data["KATITEN"];
                $this->TeamRanking[$i]["TOKUTEN"] = $data["TOKUTEN"];
                $this->TeamRanking[$i]["SITTEN"] = $data["SITTEN"];
                $this->TeamRanking[$i]["TOKUSITTEN"] = $data["TOKUSITTEN"];

                // •÷•Ï•§•Ø•«°º•ø£±
                $rankBreakData1 = $data["KATITEN"].$data["TOKUSITTEN"];

                // •¡°º•‡•È•Û•≠•Û•∞
                if ($rankBreakData1 != $rankBreakData2) {
                    $ranking++;
                    $this->ret[$i]["RANKING"] = $ranking;
                } else {
                    $this->ret[$i]["RANKING"] = $ranking;
                    $ranking++;
                }
                // •÷•Ï•§•Ø•«°º•ø
                $rankBreakData2 = $rankBreakData1;
            }

            // 
            $this->DataCount = $i;

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }
	
}
?>