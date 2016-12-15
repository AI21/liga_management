<?php

class schejuleDataChangeClass extends connectionDBClass {


    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    /**
     * ≥µÕ◊ : •¡°º•‡æ Û§Ú —ππ§π§Î
     *
     * ¿‚Ã¿ : •¡°º•‡§Œ•«°º•ø§Ú —ππ°¶ππø∑§π§Î
     *
     * @param mixed     $changeDatas    —ππ•«°º•ø
     * @param mixed     $beforUserDatas    —ππ•«°º•ø
     *
     * @return bool
     *
     * @access public
     */
    public function schejuleDataChange($rarryId, $changeDatas) {

        $retData = false;

        $dbColomn = array(
                         "gameId" => "num",
                         "gemeYear" => "num",
                         "gemeMonth" => "var",
                         "gemeDay" => "var",
                         "gemeHours" => "var",
                         "gemeMinutes" => "var",
                         "gemeCort" => "num",
                         "gemeBlock" => "num",
//                         "gemeLevel" => "num",
                         "gemeSection" => "num",
                         "gemeHome" => "num",
                         "gemeAway" => "num",
                         "gemeOfficialA" => "num",
                         "gemeOfficialB" => "num",
                         );

        // ¡˜øÆ•«°º•ø§Ú«€ŒÛ§À —¥π
        foreach ($changeDatas AS $key => $val) {
            if (array_key_exists($key, $dbColomn)) {
                if ($val == "") {
                    switch ($dbColomn[$key]) {
                      case "var" : $$key = ""; break;
                      case "num" : $$key = 0; break;
                      default : $$key = "";
                    }
                } else {
                    // øÙ√Õ∞ ≥∞§Ú•Ø•™°º•»§π§Î
                    if (!is_numeric($val)) {
                        $val = mysql_real_escape_string($val);
                    }
                    $$key = encode($val);
                }
            } else {
                if (array_key_exists($key, array_keys($dbColomn))) {
                    switch ($dbColomn[$key]) {
                      case "var" : $$key = "''"; break;
                      case "num" : $$key = 0; break;
                      default : $$key = "''";
                    }
                }
            }
        }

        $changeTime = '20'.$gemeYear . '-' . $gemeMonth . '-' . $gemeDay . ' ' . $gemeHours . ':' . $gemeMinutes . ':00';

        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

        # •¡°º•‡•«°º•ø§Úππø∑
        $sql = "UPDATE ".dbTableName::LT_SCHEJULE." SET " .
                     " `times` = '" . $changeTime . "', " .
                     " `class` = '" . $gemeBlock . "', " .
//                     " `level` = '" . $gemeLevel . "', " .
                     " `section` = '" . $gemeSection . "', " .
                     " `court` = '" . $gemeCort . "', " .
                     " `home_team` = '" . $gemeHome . "', " .
                     " `away_team` = '" . $gemeAway . "', " .
                     " `ofisial_a` = '" . $gemeOfficialA . "', " .
                     " `ofisial_b` = '" . $gemeOfficialB . "', " .
                     " `modified` = NOW( ) " .
                     " WHERE `id` = " . $gameId . " " .
                     "   AND   `r_id` = " . $rarryId ;
//print $sql."<BR>\n";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/schejuleDataChangeClass::schejuleDataChange]•π•±•∏•Â°º•Î•«°º•ø§Œππø∑•®•È°º§«§π°£<br />";
           // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            $this->Query( "ROLLBACK" );
        } else {
            $retData = True;
            // •≥•ﬂ•√•»ΩËÕ˝
//            $this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •π•±•∏•Â°º•Î§Œ∫ÔΩ¸
     *
     * ¿‚Ã¿ : •π•±•∏•Â°º•Îæ Û§Ú∫ÔΩ¸§π§Î
     *
     * @param integer   $gameId         •π•±•∏•Â°º•ÎID
     *
     * @return bool
     *
     * @access public
     */
    public function schejuleDelete($gameId) {

        $retData = False;

        // •π•±•∏•Â°º•ÎID§¨Ãµ§§§»§≠
        if (!isset($gameId) OR $gameId == "") {
            return $retData;
        }

        // •π•±•∏•Â°º•Î•«°º•ø§Ú∫ÔΩ¸
        $sql = "DELETE FROM ".dbTableName::LT_SCHEJULE." " .
               " WHERE ".dbTableName::LT_SCHEJULE.".`id` = " . $gameId ;
//print $sql."<BR>";

        // •«°º•ø∫ÔΩ¸
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[datas/schejuleDataChangeClass::schejuleDelete]•π•±•∏•Â°º•Î§Œ∫ÔΩ¸•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
        } else {
        	$retData = True;
        }

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

}

?>
