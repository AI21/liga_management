<?php

// ¿ﬂƒÍ•’•°•§•Î∆…§ﬂπ˛§ﬂ
//require_once "connectionDB.inc";

class memberDataClass extends connectionDBClass {

    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    // •¡°º•‡≈–œø•·•Û•–°º•«°º•ø
    public function rarrySeasonMemberList($rarryId, $season, $teamId = 0) {
    //public function rarrySeasonMemberList($nextRarryId, $nextSeason, $preRarryId, $preSeason, $teamId = 0)

        $retData = False;
        $this->memberDataList = array();

        // •¡°º•‡ID§¨§ §§§»§≠§œΩ™Œª
        if (!isset($rarryId) AND !isset($season) AND !isset($teamId)) {
            return  $retData;
        }

        # •¡°º•‡≈–œø•·•Û•–°º•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " LMI.`id` AS m_id, " .
                       " LRTM.`number` AS number, " .
                       //" CASE when LRTM.`number`   IS NULL then '--' ELSE LRTM.`number` END number, " .
                       " CASE when LMI.`name_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`name_first` END name_first, " .
                       " LMI.`name_second` AS name_second, " .
                       " CASE when LMI.`kana_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`kana_first` END kana_first, " .
                       " LMI.`kana_second` AS kana_second, " .
                       //" CASE when LMI.`age`      IS NULL then 'Ã§≈–œø' ELSE LMI.`age` END age, " .
                       " CASE when LMI.`height`   IS NULL then 'Ã§≈–œø' ELSE LMI.`height` END height, " .
                       //" CASE when LMI.`weight`   IS NULL then 'Ã§≈–œø' ELSE LMI.`weight` END weight, " .
                       " CASE when LMI.`birthday` IS NULL then 'Ã§≈–œø' ELSE LMI.`birthday` END birthday, " .
                       " CASE when LMI.`birthday` IS NULL then '--'     ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages, " .
                       " CASE when MPI.`posision_jpn` IS NULL then 'Ã§≈–œø' ELSE MPI.`posision_jpn` END posision, " .
                       " LRTM.`captain_flg` AS captain_flg, " .
                       " CASE when LMI.`m_comment` = ''   then 'Ã§≤Û≈˙' ELSE LMI.`m_comment` END comment, " .
                       " LMP.`payment` AS registPayment, " .
                       " LRTM.`created` AS created, " .
                       " LRTM.`modified` AS discharge_date " .
                    " FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
                    " LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI " .
                    "        ON LRTM.`m_id` = LMI.`id`" .
                    " LEFT JOIN ".dbTableName::LT_MEMBER_PAYMENT." LMP " .
                    "        ON LRTM.`m_id` = LMP.`m_id` " .
                    "       AND LMP.`r_id` = " . $rarryId . " " .
                    " LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI " .
                    "  ON LRTM.`p_id` = MPI.`id`" .
                    " WHERE LRTM.r_id = " . $rarryId . "" .
                    "  AND  LRTM.define = " . $season . "" .
                    "  AND  LRTM.t_id = " . $teamId . "" .
                    " ORDER BY LPAD(LRTM.`number`, 2, 0), LRTM.`number` DESC";
/*
        $sql = "SELECT " .
                       " (a.m_id), r_id, number, LMT.name_first FROM (
                       select r_id, m_id, number FROM lt_regist_team_member WHERE r_id = '2' AND define = 1 AND t_id = '47'
                       UNION ALL
                       select r_id, m_id, number FROM lt_regist_team_member WHERE r_id = '1' AND define = 3 AND t_id = '47'
                       ) as a INNER JOIN lt_member_info LMT ON a.m_id = LMT.m_id";
        $sql = "SELECT LRTM.r_id, LRTM.m_id, LRTM.number
               CASE when LMI.`name_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`name_first` END name_first,
               LMI.`name_second` AS name_second,
               CASE when LMI.`kana_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`kana_first` END kana_first,
               LMI.`kana_second` AS kana_second,
               CASE when LMI.`age`      IS NULL then 'Ã§≈–œø' ELSE LMI.`age` END age,
               CASE when LMI.`height`   IS NULL then 'Ã§≈–œø' ELSE LMI.`height` END height,
               CASE when LMI.`weight`   IS NULL then 'Ã§≈–œø' ELSE LMI.`weight` END weight,
               CASE when LMI.`birthday` IS NULL then 'Ã§≈–œø' ELSE LMI.`birthday` END birthday,
               CASE when LMI.`birthday` IS NULL then '--'     ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages,
               MPI.`posision_abb` AS posision,
               LRTM.`captain_flg` AS captain_flg
               FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM
               LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI ON LRTM.m_id = LMI.m_id
               LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI ON LRTM.`posision` = MPI.`id`
               WHERE LRTM.r_id = " . $nextRarryId . "
               AND LRTM.define = " . $nextSeason . "
               AND LRTM.t_id = " . $teamId . "
               UNION
               SELECT LRTM.r_id, LRTM.m_id, LRTM.number
               CASE when LMI.`name_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`name_first` END name_first,
               LMI.`name_second` AS name_second,
               CASE when LMI.`kana_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`kana_first` END kana_first,
               LMI.`kana_second` AS kana_second,
               CASE when LMI.`age`      IS NULL then 'Ã§≈–œø' ELSE LMI.`age` END age,
               CASE when LMI.`height`   IS NULL then 'Ã§≈–œø' ELSE LMI.`height` END height,
               CASE when LMI.`weight`   IS NULL then 'Ã§≈–œø' ELSE LMI.`weight` END weight,
               CASE when LMI.`birthday` IS NULL then 'Ã§≈–œø' ELSE LMI.`birthday` END birthday,
               CASE when LMI.`birthday` IS NULL then '--'     ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages,
               MPI.`posision_abb` AS posision,
               FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM
               LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI ON LRTM.m_id = LMI.m_id
               LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI ON LRTM.`posision` = MPI.`id`
               WHERE LRTM.r_id = " . $preRarryId . "
               AND LRTM.define = " . $preSeason . "
               AND LRTM.t_id = " . $teamId . " ";
        $sql = "SELECT " .
                       " LMI.`m_id` AS m_id, " .
                       " LRTM.`number` AS number, " .
                       " CASE when LMI.`name_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`name_first` END name_first, " .
                       " LMI.`name_second` AS name_second, " .
                       " CASE when LMI.`kana_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`kana_first` END kana_first, " .
                       " LMI.`kana_second` AS kana_second, " .
                       " CASE when LMI.`age`      IS NULL then 'Ã§≈–œø' ELSE LMI.`age` END age, " .
                       " CASE when LMI.`height`   IS NULL then 'Ã§≈–œø' ELSE LMI.`height` END height, " .
                       " CASE when LMI.`weight`   IS NULL then 'Ã§≈–œø' ELSE LMI.`weight` END weight, " .
                       " CASE when LMI.`birthday` IS NULL then 'Ã§≈–œø' ELSE LMI.`birthday` END birthday, " .
                       " CASE when LMI.`birthday` IS NULL then '--'     ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages, " .
                       " MPI.`posision_abb` AS posision, " .
                       " LRTM.`captain_flg` AS captain_flg, " .
                       " CASE when LMI.`m_comment` = ''   then 'Ã§≤Û≈˙' ELSE LMI.`m_comment` END comment " .
                    " FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
                    " LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI " .
                    "        ON LRTM.`m_id` = LMI.`m_id`" .
                    " LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI " .
                    "  ON LRTM.`posision` = MPI.`id`" .
                    " WHERE LRTM.r_id = " . $nextRarryId . "" .
                    "  AND  LRTM.define = " . $nextSeason . "" .
                    "  AND  LRTM.t_id = " . $teamId . "" .
                    //" ORDER BY LPAD(LRTM.`number`, 2, 0)" .
               "UNION " .
               "SELECT " .
                       " LMI.`m_id` AS m_id, " .
                       " LRTM.`number` AS number, " .
                       " CASE when LMI.`name_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`name_first` END name_first, " .
                       " LMI.`name_second` AS name_second, " .
                       " CASE when LMI.`kana_first`   IS NULL then 'Ã§≈–œø' ELSE LMI.`kana_first` END kana_first, " .
                       " LMI.`kana_second` AS kana_second, " .
                       " CASE when LMI.`age`      IS NULL then 'Ã§≈–œø' ELSE LMI.`age` END age, " .
                       " CASE when LMI.`height`   IS NULL then 'Ã§≈–œø' ELSE LMI.`height` END height, " .
                       " CASE when LMI.`weight`   IS NULL then 'Ã§≈–œø' ELSE LMI.`weight` END weight, " .
                       " CASE when LMI.`birthday` IS NULL then 'Ã§≈–œø' ELSE LMI.`birthday` END birthday, " .
                       " CASE when LMI.`birthday` IS NULL then '--'     ELSE floor( (date_format( now( ), '%Y%m%d') - date_format(LMI.`birthday`, '%Y%m%d' ) ) /10000) END ages, " .
                       " MPI.`posision_abb` AS posision, " .
                       " LRTM.`captain_flg` AS captain_flg, " .
                       " CASE when LMI.`m_comment` = ''   then 'Ã§≤Û≈˙' ELSE LMI.`m_comment` END comment " .
                    " FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
                    " LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LMI " .
                    "        ON LRTM.`m_id` = LMI.`m_id`" .
                    " LEFT JOIN ".dbTableName::M_POSISION_INFO." MPI " .
                    "  ON LRTM.`posision` = MPI.`id`" .
                    " WHERE LRTM.r_id = " . $preRarryId . "" .
                    "  AND  LRTM.define = " . $preSeason . "" .
                    "  AND  LRTM.t_id = " . $teamId . "" ;
                    //" ORDER BY LPAD(LRTM.`number`, 2, 0)";
*/
//print $sql."<BR>";
        //$rs  = PostSQL(mb_convert_encoding($sql, "UTF-8", "EUC-JP"));
        $rs  = $this->Query($sql);
        if(!$rs){ print $sql."<br />[datas/memberDataClass::rarrySeasonMemberList]•¡°º•‡≈–œø•·•Û•–°º•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);
//print $teamId." = ".$num."<BR>";

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // •·•Û•–°º•«°º•ø(«€ŒÛÃæ§œCSS§ÀπÁ§Ô§ª§Îªˆ)
                $this->memberDataList[$i]["nemberId"] = $data["m_id"];
                $this->memberDataList[$i]["number"] = $data["number"];
                $this->memberDataList[$i]["nameFirst"] = $data["name_first"];
                $this->memberDataList[$i]["nameSecond"] = $data["name_second"];
                $this->memberDataList[$i]["kanaFirst"] = $data["kana_first"];
                $this->memberDataList[$i]["kanaSecond"] = $data["kana_second"];
                $this->memberDataList[$i]["posision"] = $data["posision"];
                $this->memberDataList[$i]["birthday"] = $data["birthday"];
                $this->memberDataList[$i]["age"] = $data["ages"];
                $this->memberDataList[$i]["height"] = $data["height"];
                $this->memberDataList[$i]["registPayment"] = $data["registPayment"];
                $this->memberDataList[$i]["created"] = $data["created"];
                $this->memberDataList[$i]["discharge_date"] = $data["discharge_date"];
                //$this->memberData[$i]["comment"] = $data["comment"];
                //$this->ret[$i]["weight"] = $data["weight"];
#print $this->ret[$i]["posision"]." = xxx<br>";
#print $data["name_first"]." = comment<br>";
            }
            $this->memberCount = $num;
//print $teamId." = ".$this->memberCount."<BR>";
            $retData = True;
        } else {
        	$this->memberCount = 0;
/*
                // •·•Û•–°º•«°º•ø(«€ŒÛÃæ§œCSS§ÀπÁ§Ô§ª§Îªˆ)
                $this->memberData[0]["no"] = "";
                $this->memberData[0]["name"] = "";
                $this->memberData[0]["posision"] = "";
                $this->memberData[0]["birthday"] = "";
                $this->memberData[0]["age"] = "";
                $this->memberData[0]["height"] = "";
                $this->memberData[0]["discharge_date"] = "";
                //$this->memberData[0]["comment"] = "";
                //$this->memberData[0]["kana"] = "";
                //$this->memberData[0]["weight"] = "";
*/
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);
//print $teamId." = ".$this->memberCount."<BR>";

        return $retData;
    }


    /**
     * ≥µÕ◊ : •¡°º•‡≈–œø•·•Û•–°º•«°º•ø§Ú ÷§π
     *
     * ¿‚Ã¿ : ∏ƒøÕ§Œ¬–¿Ô•π•≥•¢§¨≈–œø§µ§Ï§∆§§§Î§´§Œ•¡•ß•√•Ø
     *
     * @param $rarryId    ¬Á≤ÒID
     * @param $teamId     •¡°º•‡ID
     * @param $gameId     •≤°º•‡ID
     */
    public function teamRegistMemberList($rarryId, $teamId, $season) {

        $ret = False;

        $sql = "SELECT " .
                       " LISC.`m_id` AS DataCount " .
                       " FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
                          " LEFT JOIN ".dbTableName::LT_MEMBER_INFO." LRTM " .
                       "  ON   LISC.`m_id` = LRTM.`m_id` " .
                       " WHERE LISC.`r_id` = " . $rarryId . " " .
                       "  AND  LISC.`game_id` = " . $gameId . " " .
                       "  AND  LRTM.`t_id` = " . $teamId . " " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print $sql."<br />[datas/memberDataClass::teamRegistMemberList]•¡°º•‡≈–œø•·•Û•–°º•«°º•ø•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        // •«°º•ø§¨1∑Ô∞ æÂ§¢§Ï§–§–True§Ú ÷§π
        if($num > 0){

            $ret = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $ret;
    }


    /**
     * ≥µÕ◊ : ¡™ºÍ•«°º•ø§ÚºË∆¿§π§Î
     *
     * ¿‚Ã¿ : ªÿƒÍID§Œ¡™ºÍæ Û§ÚºË∆¿§π§Î
     *
     * @param $memberId   ¡™ºÍID
     */
    public function selectMemberData($memberId) {

        $ret = false;
        $this->memberData = array();

        $sql = "SELECT " .
                       " `name_first` " .
                       " ,`name_second` " .
                       " ,`kana_first` " .
                       " ,`kana_second` " .
                       " FROM ".dbTableName::LT_MEMBER_INFO." " .
                       " WHERE `id` = " . $memberId . " " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print $sql."<br />[datas/memberDataClass::selectMemberData]•·•Û•–°º•«°º•øºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        // •«°º•ø§¨1∑Ô∞ æÂ§¢§Ï§–§–True§Ú ÷§π
        if($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

            // •·•Û•–°º•«°º•ø(«€ŒÛÃæ§œCSS§ÀπÁ§Ô§ª§Îªˆ)
            $this->memberData["name_first"]    = $data["name_first"];
            $this->memberData["name_second"]   = $data["name_second"];
            $this->memberData["kana_first"]    = $data["kana_first"];
            $this->memberData["kana_second"]   = $data["kana_second"];

            $ret = true;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $ret;
    }

    /**
     * ≥µÕ◊ : ¡™ºÍ§¨∫«∏Â§ÀΩ–æÏ§∑§ø•¡°º•‡•«°º•ø§ÚºË∆¿§π§Î
     *
     * ¿‚Ã¿ : ªÿƒÍID§Œ¡™ºÍæ Û§ÚºË∆¿§π§Î
     *
     * @param $memberId   ¡™ºÍID
     */
    public function selectMemberLastRarryData($memberId, $nextRarryId, $preRarryId=0, $preRarrySeason=0) {

        $ret = false;
        $this->memberData = array();
        $sqlPreRallySelect = "";

        if ($preRarryId > 0) {
        	$sqlPreRallySelect = " AND LRT.`r_id` = " . $preRarryId;
        }
        if ($preRarrySeason > 0) {
        	$sqlPreRallySelect .= " AND LRTM.`define` = " . $preRarrySeason;
        }

        $sql = "SELECT " .
						" LRTM.`number` " .
						" ,LRT.`team_name` " .
						" ,LRTM.`modified` " .
						" FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
						" LEFT JOIN ".dbTableName::LT_REGIST_TEAM." LRT " .
						"        ON LRTM.`t_id` = LRT.`t_id` " .
						"       AND LRTM.`r_id` = LRT.`r_id` " .
//						"       AND LRT.`r_id` != " . $nextRarryId . " " .
						" LEFT JOIN ".dbTableName::LT_RARRY_INFO." LRI " .
						"        ON LRTM.`r_id` = LRI.`id` " .
						"       AND LRI.`parent_id` = 0 " .
						" WHERE LRTM.`m_id` = " . $memberId .
						$sqlPreRallySelect .
						//" AND LRTM.`r_id` NOT IN (6,8) " .
						//" ORDER BY LRT.`r_id` DESC, LRTM.`define` DESC, LRI.`define` DESC " .
						" LIMIT 1 " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print $sql."<br />[datas/memberDataClass::selectMemberLastRarryData]¡™ºÍ§¨∫«∏Â§ÀΩ–æÏ§∑§ø•¡°º•‡•«°º•øºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        // •«°º•ø§¨1∑Ô∞ æÂ§¢§Ï§–§–True§Ú ÷§π
        if($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

            // •·•Û•–°º•«°º•ø(«€ŒÛÃæ§œCSS§ÀπÁ§Ô§ª§Îªˆ)
            $this->memberLastRarryData["number"]    = $data["number"];
            $this->memberLastRarryData["team_name"]   = $data["team_name"];
            $this->memberLastRarryData["modified"]   = $data["modified"];

            $ret = true;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $ret;
    }


    //------------------------------------------------------
    // ∏ƒøÕ•«°º•ø
    //------------------------------------------------------
    /*
     * •¡°º•‡≈–œøøÕøÙ§Ú ÷§π
     */
    function getPeopleCount() {
        return $this->memberCount;
    }

    /*
     * ¡™ºÍ•Í•π•»§Ú ÷§π
     */
    function getMemberDataList() {
        return $this->memberDataList;
    }

    /*
     * ¡™ºÍæ Û§Ú ÷§π
     */
    function getMemberData() {
        return $this->memberData;
    }

    /*
     * ¡™ºÍ§¨∫«∏Â§ÀΩ–æÏ§∑§ø•¡°º•‡æ Û§Ú ÷§π
     */
    function getMemberLastRarryData() {
        return $this->memberLastRarryData;
    }

    /*
     * Ãæ¡∞§Ú ÷§π
     */
    function getName($no) {
        return $this->ret[$no]["name"];
    }

    /*
     * ∆…§ﬂ•´• §Ú ÷§π
    function getKana($no) {
        return $this->ret[$no]["kana"];
    }
     */

    /*
     * •›•∏•∑•Á•Û§Ú ÷§π
     */
    function getPosision($no) {
        return $this->ret[$no]["posision"];
    }

    /*
     * «ØŒ§Ú ÷§π
    function getAge($no) {
        return $this->ret[$no]["age"];
    }
     */

    /*
     * «ØŒ£≤(√¬¿∏∆¸§´§È∑◊ªª)§Ú ÷§π
     */
    function getAge($no) {
        return $this->ret[$no]["age"];
    }

    /*
     * ø»ƒπ§Ú ÷§π
     */
    function getHeight($no) {
        return $this->ret[$no]["height"];
    }

    /*
     * ¬ŒΩ≈§Ú ÷§π
    function getWeight($no) {
        return $this->ret[$no]["weight"];
    }
     */

    /*
     * •≥•·•Û•»§Ú ÷§π
     */
    function getComment($no) {
        return $this->ret[$no]["comment"];
    }

}

?>
