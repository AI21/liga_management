<?php

class versusResult extends connectionDBClass {

    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    /**
     * ≥µÕ◊ : Ã§¬–¿Ô•¡°º•‡•«°º•ø§ŒºË∆¿
     *
     * ¿‚Ã¿ : ¬Á≤Ò§«§ŒÃ§¬–¿Ô•¡°º•‡•«°º•ø§ŒºË∆¿
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $blockId    ¬Á≤Ò•÷•Ì•√•ØID
     * @param $teamId     •¡°º•‡ID
    */
    public function adminVersusResultTeam($rarryId, $teamId) {

        $retData = False;

        $today = date("Y-m-d");

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       //" RCI.`block_id` AS BLOCK_ID, " .
                       " RSCO.`vs_team` AS vs_team, " .
                       " RSCO.`away_team` AS SUB_NAME " .
                    " FROM `rarry_use_classes` RUC, `rarry_schejules` RSCH, `rarry_scores` RSCO " .
                    " WHERE RUC.`rarry_id` = RSCH.`r_id` " .
                    "  AND  RUC.`rarry_id` = RSCH.`r_id` " .
                    "  AND  RSCH.`rarry_id` = RSCO.`r_id` " .
                    //"  AND  RCI.`block_id` = LSCR.`game_id` " .
                    "  AND  RUC.`rarry_id` = " . $rarryId . " " .
                    "  AND  (RSCH.`home_team` = 1 OR RSCH.`away_team` = " . $teamId . ") " .
                    "  AND  RSCO.`team_id` = " . $teamId . " " .
                    "  AND  RSCO.`times` > " . $today . " " .
                    " ORDER BY RUC.`block_id` " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[adminVersusResult]Ã§¬–¿Ô•¡°º•‡•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø
                $this->RarryRegistBlockData[$i]["BLOCK_ID"] = $data["BLOCK_ID"];
                $this->RarryRegistBlockData[$i]["BLOCK_NAME"] = $data["BLOCK_NAME"];
                $this->RarryRegistBlockData[$i]["SUB_NAME"] = $data["SUB_NAME"];

            }
            $retData = True;
        } else {
            // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§¨§ §§æÏπÁ
            $this->RarryRegistBlockData[0]["BLOCK_ID"] = "";
            $this->RarryRegistBlockData[0]["BLOCK_NAME"] = "";
            $this->RarryRegistBlockData[0]["SUB_NAME"] = "";
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }


    /**
     * ≥µÕ◊ : ¬Á≤Ò§Œ•÷•Ì•√•Ø•«°º•ø§ŒºË∆¿
     *
     * ¿‚Ã¿ : ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $classId    •÷•Ì•√•ØID
    */
    public function selecRrarryRegistBlockData($rarryId, $blockId) {

        $retData = False;

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " RCI.`block_id` AS BLOCK_ID, " .
                       " RCI.`block_neme` AS BLOCK_NAME, " .
                       " RCI.`sub_name` AS SUB_NAME " .
                    " FROM `rarry_class_info` RCI " .
                    " WHERE RCI.`rarry_id` = " . $rarryId . "";
                    "  AND  RCI.`id` = " . $blockId . "";
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[adminVersusResult]¬Á≤Ò§Œ•÷•Ì•√•Ø•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

            // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø
            $this->SelectBlockData["BLOCK_ID"] = $data["BLOCK_ID"];
            $this->SelectBlockData["BLOCK_NAME"] = $data["BLOCK_NAME"];
            $this->SelectBlockData["SUB_NAME"] = $data["SUB_NAME"];

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •π•≥•¢•«°º•ø≈–œø∫—§ﬂ•¡•ß•√•Ø¥ÿøÙ
     *
     * ¿‚Ã¿ : ¬Á≤Ò∆‚§«¬–¿Ô§∑§∆§§§Î§´§Œ•¡•ß•√•Ø
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $classId    •÷•Ì•√•ØID
    */
    public function checkResultScoreGames($rarryId, $home_team, $away_team) {

        $retData = True;

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " DATE_FORMAT(`times`, '%y') AS year, " .
                       " DATE_FORMAT(`times`, '%c') AS month, " .
                       " DATE_FORMAT(`times`, '%d') AS day, " .
                       " `section` " .
                    " FROM `rarry_complete_games` " .
                    " WHERE `r_id` = " . $rarryId . " " .
                    "  AND  ((`home_team` = " . $home_team . " AND `away_team` = " . $away_team . ")" .
                    "         OR   (`away_team` = " . $away_team . " AND `home_team` = " . $home_team . "))" ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[adminVersusResult]¬–¿Ô≈–œø∫—§ﬂ•¡•ß•√•Ø•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        // •«°º•ø§¨1∑Ô∞ æÂ§¢§Ï§–NG
        if($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

            // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø
            $this->checkResultScoreGameData["year"] = $data["year"];
            $this->checkResultScoreGameData["month"] = $data["month"];
            $this->checkResultScoreGameData["day"] = $data["day"];
            $this->checkResultScoreGameData["section"] = $data["section"];

            $retData = False;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •π•±•∏•Â°º•Î≈–œø∫—§ﬂ•¡•ß•√•Ø¥ÿøÙ
     *
     * ¿‚Ã¿ : ¬Á≤Ò∆‚§«¬–¿Ô§∑§∆§§§Î§´§Œ•¡•ß•√•Ø
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $classId    •÷•Ì•√•ØID
    */
    public function checkSchejuleGames($rarryId, $home_team, $away_team) {

        $retData = True;

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " DATE_FORMAT(`times`, '%y') AS year, " .
                       " DATE_FORMAT(`times`, '%c') AS month, " .
                       " DATE_FORMAT(`times`, '%d') AS day, " .
                       " DATE_FORMAT(`times`, '%H : %i') AS times, " .
                       " `section` " .
                    " FROM `rarry_schejules` " .
                    " WHERE `r_id` = " . $rarryId . " " .
                    "  AND  ((`home_team` = " . $home_team . " AND `away_team` = " . $away_team . ")" .
                    "         OR   (`home_team` = " . $away_team . " AND `away_team` = " . $home_team . "))" ;
#print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[adminVersusResult]•π•±•∏•Â°º•Î≈–œø∫—§ﬂ•¡•ß•√•Ø•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        // •«°º•ø§¨1∑Ô∞ æÂ§¢§Ï§–NG
        if($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

            // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø
            $this->checkShejuleGameData["year"] = $data["year"];
            $this->checkShejuleGameData["month"] = $data["month"];
            $this->checkShejuleGameData["day"] = $data["day"];
            $this->checkShejuleGameData["times"] = $data["times"];
            $this->checkShejuleGameData["section"] = $data["section"];

            $retData = False;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •¡°º•‡•π•≥•¢≈–œø∫—§ﬂ•¡•ß•√•Ø¥ÿøÙ
     *
     * ¿‚Ã¿ : •¡°º•‡•π•≥•¢≈–œø∫—§ﬂ§∑§∆§§§Î§´§Œ•¡•ß•√•Ø
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $gameId     •≤°º•‡ID
    */
    public function checkSetGameScore($rarryId, $gameId) {

        $retData = True;

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " `g_id` " .
                    " FROM `rarry_scores` " .
                    " WHERE `r_id` = " . $rarryId . " " .
                    "  AND  `g_id` = " . $gameId . " " ;
#print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[adminVersusResult]•¡°º•‡•π•≥•¢≈–œø∫—§ﬂ•¡•ß•√•Ø•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        // •«°º•ø§¨1∑Ô∞ æÂ§¢§Ï§–NG
        if($num > 0){

            $retData = False;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •¡°º•‡•π•≥•¢≈–œø∫—§ﬂ•¡•ß•√•Ø¥ÿøÙ2
     *
     * ¿‚Ã¿ : •¡°º•‡•π•≥•¢≈–œø∫—§ﬂ§∑§∆§§§Î§´§Œ•¡•ß•√•Ø
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $gameId     •≤°º•‡ID
    */
    public function checkSetGameRarryScore($rarryId, $gameId) {

        $retData = True;

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " `g_id` " .
                    " FROM `rarry_scores` " .
                    " WHERE `r_id` = " . $rarryId . " " .
                    "  AND  `g_id` = " . $gameId . " " ;
#print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[adminVersusResult]•¡°º•‡•π•≥•¢≈–œø∫—§ﬂ•¡•ß•√•Ø2•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        // •«°º•ø§¨1∑Ô∞ æÂ§¢§Ï§–NG
        if($num > 0){

            $retData = False;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }


    //------------------------------------------------------
    // •™•÷•∏•ß•Ø•»
    //------------------------------------------------------
    /*
     * ªÓπÁ≤ÒæÏ•«°º•ø§Ú ÷§π
     */
    function getRarryRegistBlockData() {
        return $this->RarryRegistBlockData;
    }

    /*
     * ªÓπÁ≤ÒæÏ•«°º•ø§Ú ÷§π
     */
    function getSelectBlockData() {
        return $this->SelectBlockData;
    }

    /*
     * ¬–¿Ô∫—§ﬂ•π•≥•¢•«°º•ø§Ú ÷§π
     */
    function getCheckResultScoreGameData() {
        return $this->checkResultScoreGameData;
    }

    /*
     * •π•±•∏•Â°º•Î≈–œø∫—§ﬂ•«°º•ø§Ú ÷§π
     */
    function getCheckShejuleGameData() {
        return $this->checkShejuleGameData;
    }

}

?>
