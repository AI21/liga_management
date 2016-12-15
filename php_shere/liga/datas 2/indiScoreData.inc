<?php

/**
 * ∏ƒøÕ•π•≥•¢•«°º•ø(ªÓπÁ Ã°¶TOTAL)
 *
 * @package  •—•√•±°º•∏Ãæ
 * @author   ∫£∞Ê ∏¸ ∏ <imai21@hotmail.co.jp>
 * @since    PHP 5.2
 * @version  $Id: DB.php,v 1.58 2004/03/13 16:17:19 danielc Exp $
 */
// classÃøÃæµ¨¬ß) Log,Net_Finger,HTML_Upload_Error
class indiScoreData extends connectionDBClass {

    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    /**
     * ≥µÕ◊ : ∏ƒøÕ§Œ•≤°º•‡•π•≥•¢§¨§¢§Î§´§…§¶§´§Ú•¡•ß•√•Ø§π§Î
     *
     * ¿‚Ã¿ : ∏ƒøÕ§Œ¬–¿Ô•π•≥•¢§¨≈–œø§µ§Ï§∆§§§Î§´§Œ•¡•ß•√•Ø
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $teamId     •¡°º•‡ID
     * @param $gameId     •≤°º•‡ID
    */
    public function checkIndiGameScore($rarryId, $teamId, $gameId) {

        $ret = False;

        $sql = "SELECT " .
                       " ISCO.`m_id` AS DataCount " .
                    " FROM `regist_team_members` RTM " .
                    " LEFT JOIN `individual_scores` ISCO " .
                    "  ON   RTM.`m_id` = ISCO.`m_id` " .
                    " WHERE ISCO.`r_id` = " . $rarryId . " " .
                    "  AND  ISCO.`game_id` = " . $gameId . " " .
                    "  AND  RTM.`t_id` = " . $teamId . " " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[adminIndiScoreData]∆±•¡°º•‡ªÓπÁøÙ•¡•ß•√•Ø•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->FetchRow($rs);

        // •«°º•ø§¨1∑Ô∞ æÂ§¢§Ï§–§–True§Ú ÷§π
        if($num > 0){

            $ret = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $ret;
    }


    // ∏ƒøÕ•π•≥•¢•«°º•ø
    public function adminIndividualGameScore($rarryId, $gameId, $teamId, $season) {

        $retData = False;

        # •«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " MMI.`id` AS INDI_ID, " .
                       " CASE WHEN LISCO.`m_id` IS NOT NULL then 'part' ELSE 'unpart' END PARTICIPATION, " .
                       " LRTM.`t_id` AS TEAM_ID, " .
                       " LRTM.`number` AS NUMBER, " .
                       " CASE WHEN MMI.`name_first` IS NULL then '---' ELSE MMI.`name_first` END FIRST_NAME, " .
                       " MMI.`name_second` AS SECOND_NAME, " .
                       " CASE WHEN LISCO.`2point_success` IS NULL then 0 ELSE LISCO.`2point_success` END 2POINT_IN, " .
                       " CASE WHEN LISCO.`3point_success` IS NULL then 0 ELSE LISCO.`3point_success` END 3POINT_IN, " .
                       " CASE WHEN LISCO.`ft_success`     IS NULL then 0 ELSE LISCO.`ft_success`     END FREE_THROW_IN, " .
                       " CASE WHEN ((LISCO.`2point_success` * 2) + (LISCO.`3point_success` * 3) + (LISCO.`ft_success`)) IS NULL then 0
                              ELSE ((LISCO.`2point_success` * 2) + (LISCO.`3point_success` * 3) + (LISCO.`ft_success`)) END TOTAL_SCORE, " .
                       " CASE WHEN LISCO.`of_rebound`     IS NULL then 0 ELSE LISCO.`of_rebound`     END OFFENCE_REBOUND, " .
                       " CASE WHEN LISCO.`df_rebound`     IS NULL then 0 ELSE LISCO.`df_rebound`     END DEFENCE_REBOUND, " .
                       " CASE WHEN ((LISCO.`of_rebound`) + (LISCO.`df_rebound`)) IS NULL then 0 ELSE ((LISCO.`of_rebound`) + (LISCO.`df_rebound`)) END TOTAL_REBOUND, " .
                       " CASE WHEN LISCO.`ft_success`     IS NULL then 0 ELSE LISCO.`ft_success`     END FREE_THROW_IN, " .
                       " CASE WHEN LISCO.`assist`     IS NULL then 0 ELSE LISCO.`assist`     END ASSIST, " .
                       " CASE WHEN LISCO.`steal`     IS NULL then 0 ELSE LISCO.`steal`     END STEAL, " .
                       " CASE WHEN LISCO.`block`     IS NULL then 0 ELSE LISCO.`block`     END BLOCKSHOT " .
                    " FROM `regist_team_members` LRTM " .
                    " LEFT JOIN  `individual_scores` LISCO " .
                    "  ON   LRTM.`m_id` = LISCO.`m_id` " .
                    "  AND  LISCO.`game_id` = '" . $gameId . "' " .
                    " LEFT JOIN  `member_informations` MMI  " .
                    "  ON   LRTM.`m_id` = MMI.`id` " .
                    " WHERE LRTM.`r_id` = '" . $rarryId . "' " .
                    "  AND  LRTM.`t_id` = '" . $teamId . "' " .
                    "  AND  LRTM.`define` = '" . $season . "' " .
                    " ORDER BY CAST(LRTM.`number` AS UNSIGNED)";
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[adminIndiScoreData]∏ƒøÕ•π•≥•¢•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // •«°º•ø
                $this->indiGameData[$i]["INDI_ID"] = $data["INDI_ID"];
                $this->indiGameData[$i]["PARTICIPATION"] = $data["PARTICIPATION"];
                $this->indiGameData[$i]["TEAM_ID"] = $data["TEAM_ID"];
                $this->indiGameData[$i]["NUMBER"] = $data["NUMBER"];
                $this->indiGameData[$i]["FIRST_NAME"] = $data["FIRST_NAME"];
                $this->indiGameData[$i]["SECOND_NAME"] = $data["SECOND_NAME"];
                $this->indiGameData[$i]["2POINT_IN"] = $data["2POINT_IN"];
                $this->indiGameData[$i]["3POINT_IN"] = $data["3POINT_IN"];
                $this->indiGameData[$i]["FREE_THROW_IN"] = $data["FREE_THROW_IN"];
                $this->indiGameData[$i]["TOTAL_SCORE"] = $data["TOTAL_SCORE"];
                $this->indiGameData[$i]["OFFENCE_REBOUND"] = $data["OFFENCE_REBOUND"];
                $this->indiGameData[$i]["DEFENCE_REBOUND"] = $data["DEFENCE_REBOUND"];
                $this->indiGameData[$i]["TOTAL_REBOUND"] = $data["TOTAL_REBOUND"];
                $this->indiGameData[$i]["ASSIST"] = $data["ASSIST"];
                $this->indiGameData[$i]["STEAL"] = $data["STEAL"];
                $this->indiGameData[$i]["BLOCKSHOT"] = $data["BLOCKSHOT"];
            }
            $this->count = $i;

        } else {
                // •«°º•ø
                $this->indiGameData[0]["INDI_ID"] = "-";
                $this->indiGameData[0]["PARTICIPATION"] = "-";
                $this->indiGameData[0]["TEAM_ID"] = $teamId;
                $this->indiGameData[0]["NUMBER"] = "-";
                $this->indiGameData[0]["FIRST_NAME"] = "NoData";
                $this->indiGameData[0]["SECOND_NAME"] = "";
                $this->indiGameData[0]["2POINT_IN"] = "-";
                $this->indiGameData[0]["3POINT_IN"] = "-";
                $this->indiGameData[0]["FREE_THROW_IN"] = "-";
                $this->indiGameData[0]["TOTAL_SCORE"] = "-";
                $this->indiGameData[0]["OFFENCE_REBOUND"] = "-";
                $this->indiGameData[0]["DEFENCE_REBOUND"] = "-";
                $this->indiGameData[0]["TOTAL_REBOUND"] = "-";
                $this->indiGameData[0]["ASSIST"] = "-";
                $this->indiGameData[0]["STEAL"] = "-";
                $this->indiGameData[0]["BLOCKSHOT"] = "-";
        }

        $retData = True;

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }


    //------------------------------------------------------
    // ∏ƒøÕ•«°º•ø
    //------------------------------------------------------
    /*
     * •¡°º•‡≈–œøøÕøÙ§Ú ÷§π
     */
    function getIndiGameData() {
        return $this->indiGameData;
    }

}

?>