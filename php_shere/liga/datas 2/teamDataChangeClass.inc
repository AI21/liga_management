<?php

/**
 * •Ø•È•π§Œ¿‚Ã¿
 *
 * @package  •«°º•ø•Ÿ°º•π≈–œø°¶ππø∑°¶∫ÔΩ¸•Ø•È•π
 * @author   √¯∫Óº‘ <a-imai@p-sol.co.jp>
 * @since    PHP 5.2.5
 * @create   2008/08/29
 */
class teamDataChangeClass extends connectionDBClass {


    private $uid;
    private $fname;
    private $sname;
    private $fkana;
    private $skana;
    private $mtel;
    private $email;
    private $career;
    private $eid;
    private $lid;
    private $passwd;
    private $userAgent;
    private $RegisturlHash;
    #private $afterMtel;
    #private $afterEmail;
    #private $afterCareer;

    private $upNum;


    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    /**
     * ≥µÕ◊ :  ∏ª˙ŒÛ•œ•√•∑•Â
     *
     * ¿‚Ã¿ : •«°º•ø§Œ∞≈πÊ≤Ω
     *
     * @param string    $str    ∞≈πÊ≤Ω§π§Î ∏ª˙ŒÛ
     *
     * @return void
     *
     * @access protected
     */
    protected function dataHash($str){
        //$ctx = hash_init('sha1');
        $hashPass = hash('ripemd160', $str);
        return $hashPass;
    }

    /**
     * ≥µÕ◊ : •ﬁ•§•Ø•Ì§«•∑°º•…§Ú¿ﬂƒÍ§π§Î
     *
     * ¿‚Ã¿ :
     *
     * @return void
     *
     * @access protected
     */
    protected function make_seed() {
        list($usec, $sec) = explode(' ', microtime());
        $buf = (float) $sec + ((float) $usec * 100000);
        return $buf;
    }

    /**
     * ≥µÕ◊ : •Ê°º•∂°º•«°º•ø≈–œø
     *
     * ¿‚Ã¿ : ≤æ≤Ò∞˜§Œ•«°º•ø§Ú≈–œø§π§Î
     *
     * @param mixed     $insertDatas   ≈–œø•«°º•ø
     *
     * @return bool
     *
     * @access public
     */
    public function teamDataInsert($insertDatas) {

        $retData = false;

		$this->teamName = "";
		$this->teamKana = "";
		$this->abb_name = "";
		$this->teamClass = "";
		$this->teamSite = "";
		$this->teamPhoto = "";
		$this->teamHomeColor = "";
		$this->teamAwayColor = "";
		$this->teamDistrictId = 0;
		$this->teamPlace = "";
		$this->teamRegion = "";
		$this->teamWeek = "";
		$this->teamSubLeague = "";
		$this->teamComment = "";
		$this->teamRep = "";
		$this->teamRepTel = "";
		$this->teamRepMail = "";
		$this->represent_mobile_address = "";
		$this->represent_mobile_domain = 0;
		$this->teamSubRep = "";
		$this->teamSubRepTel = "";
		$this->teamSubRepMail = "";
		$this->umpire_1 = 0;
		$this->umpire_2 = 0;
		$this->umpire_3 = 0;
		$this->login_id = "";

        $dbTable = array(
                         "teamName" => "var",
                         "teamKana" => "var",
                         "abb_name" => "var",
                         "teamClass" => "num",
                         "teamSite" => "var",
                         "teamPhoto" => "var",
                         "teamHomeColor" => "var",
                         "teamAwayColor" => "var",
                         "teamDistrictId" => "num",
                         "teamPlace" => "var",
                         "teamRegion" => "var",
                         "teamWeek" => "var",
                         "teamSubLeague" => "var",
                         "teamComment" => "var",
                         "teamRep" => "var",
                         "teamRepTel" => "var",
                         "teamRepMail" => "var",
                         "represent_mobile_address" => "var",
                         "represent_mobile_domain" => "num",
                         "teamSubRep" => "var",
                         "teamSubRepTel" => "var",
                         "teamSubRepMail" => "var",
                         "teamUmpire1" => "num",
                         "teamUmpire2" => "num",
                         "teamUmpire3" => "num",
                         "login_id" => "var",
                         "password" => "var",
                        );

        // ¡˜øÆ•«°º•ø§Ú«€ŒÛ§À —¥π
        foreach ($insertDatas AS $key => $val) {

            if (array_key_exists($key, $dbTable)) {
                if ($val == "") {
                    switch ($dbTable[$key]) {
                      case "var" : $this->$key = ""; break;
                      case "num" : $this->$key = 0; break;
                      default : $this->$key = "";
                    }
                } else {
                    // øÙ√Õ∞ ≥∞§Ú•Ø•™°º•»§π§Î
                    if (!is_numeric($val)) {
                        $val = mysql_real_escape_string($val);
                    }
                    $this->$key = encode($val);
                }
            } else {
                if (array_key_exists($key, array_keys($dbTable))) {
                    switch ($dbTable[$key]) {
                      case "var" : $this->$key = "''"; break;
                      case "num" : $this->$key = 0; break;
                      default : $this->$key = "''";
                    }
                }
            }
        }

        // •—•π•Ô°º•…¿∏¿Æ
        if ($passwd != '') {
	        $passwd = $this->dataHash($this->passwd);
        }

        # •«°º•ø§ŒºË∆¿
        $sql = "INSERT INTO ".dbTableName::LT_TEAM_INFO."  (" .
                   " `t_name`, " .
                   " `t_kana`, " .
                   " `abb_name`, " .
                   " `class`, " .
                   " `site`, " .
                   " `photo`, " .
                   " `home_color`, " .
                   " `away_color`, " .
                   " `activity_district`, " .
                   " `activity_place`, " .
                   " `region`, " .
                   " `week`, " .
                   " `sub_reague`, " .
                   " `t_comment`, " .
                   " `represent`, " .
                   " `represent_tel`, " .
                   " `represent_address`, " .
                   " `represent_mobile_address`, " .
                   " `represent_mobile_domain`, " .
                   " `sub_represent`, " .
                   " `sub_represent_tel`, " .
                   " `sub_represent_address`, " .
                   " `umpire_1`, " .
                   " `umpire_2`, " .
                   " `umpire_3`, " .
                   " `login_id`, " .
                   " `password`, " .
                   " `created`, " .
                   " `modified` " .
                   " ) VALUES (" .
                   " '".$this->teamName."', " .
                   " '".$this->teamKana."', " .
                   " '".$this->abb_name."', " .
                   0 . ", " .
                   " '".$this->teamSite."', " .
                   " '".$this->teamPhoto. "', " .
                   " '".$this->teamHomeColor."', " .
                   " '".$this->teamAwayColor."', " .
                   $this->teamDistrictId . ", " .
                   " '".$this->teamPlace."', " .
                   " '".$this->teamRegion."', " .
                   " '".$this->teamWeek. "', " .
                   " '".$this->teamSubLeague."', " .
                   " '".$this->teamComment."', " .
                   " '".$this->teamRep."', " .
                   " '".$this->teamRepTel."', " .
                   " '".$this->teamRepMail."', " .
                   " '".$this->represent_mobile_address. "', " .
                   " '".$this->represent_mobile_domain."', " .
                   " '".$this->teamSubRep."', " .
                   " '".$this->teamSubRepTel."', " .
                   " '".$this->teamSubRepMail."', " .
                   $this->umpire_1 . ", " .
                   $this->umpire_2 . ", " .
                   $this->umpire_3 . ", " .
                   " '".$this->login_id."', " .
                   " '".$passwd."', " .
                   " NOW(), " .
                   " '0000-00-00 00:00:00' " .
                   " )";
//print $sql."<BR>";
        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            print "[daas/teamDataChangeClass]•Ê°º•∂°º•«°º•ø≈–œø•®•È°º§«§π°£<br />".$sql;
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
     * ≥µÕ◊ : •¡°º•‡æ Û§Ú —ππ§π§Î
     *
     * ¿‚Ã¿ : •¡°º•‡§Œ•«°º•ø§Ú —ππ°¶ππø∑§π§Î
     *
     * @param mixed     $rarryId		¬Á≤ÒID
     * @param mixed     $teamId			 —ππ•¡°º•‡ID
     * @param mixed     $changeDatas	 —ππ•«°º•ø
     *
     * @return bool
     *
     * @access public
     */
    public function teamDataChange($rarryId, $teamId, $changeDatas/*, $beforUserDatas*/) {

        $retData = false;

        $dbColomn = array(
                         "teamName" => "var",
                         "teamKana" => "var",
                         "abb_name" => "var",
                         "site" => "var",
                         "photo" => "var",
                         "teamHomeColor" => "var",
                         "teamAwayColor" => "var",
                         "teamDistrict" => "num",
                         "teamPlace" => "var",
                         "region" => "var",
                         "week" => "var",
                         "sub_reague" => "var",
                         "t_comment" => "var",
                         "teamRep" => "var",
                         "teamRepTel" => "num",
                         "teamRepMail" => "var",
                         "teamRepMobileAddress" => "var",
                         "teamRepMobileDomain" => "num",
                         "teamSubRep" => "var",
                         "teamSubRepTel" => "num",
                         "teamSubRepMail" => "var",
                         "umpire_1" => "num",
                         "umpire_2" => "num",
                         "umpire_3" => "num",
                         "login_id" => "var"
                         );

        // ¡˜øÆ•«°º•ø§Ú«€ŒÛ§À —¥π
        foreach ($changeDatas AS $key => $val) {
            if (array_key_exists($key, $dbColomn)) {
                if ($val == "") {
                    switch ($dbColomn[$key]) {
                      case "var" : $$key = ""; break;
                      case "num" : $$key = ''; break;
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
                      case "num" : $$key = ''; break;
                      default : $$key = "''";
                    }
                }
            }
        }

        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

/*
        //  —ππ¡∞§Œ•Ê°º•∂°º•«°º•ø
        foreach ($beforUserDatas AS $key => $val) {
            $$key = str_replace("&nbsp;","",$val);
        }
print $name_first    ." = ".$this->fname."<br>\n";
print $name_second   ." = ".$this->sname."<br>\n";
print $kana_first    ." = ".$this->fkana."<br>\n";
print $kana_second   ." = ".$this->skana."<br>\n";
//print $user_agent    ." = ".$this->userAgent."<br>\n";
print $login_id      ." = ".$this->lid."<br>\n";
print $email         ." = ".$this->email."<br>\n";
print $employ_id     ." = ".$this->eid."<br>\n";
print $mobile_career ." = ".$this->career."<br>\n";
print $mobile_tel    ." = ".$this->mtel."<br>\n";

        // •Ê°º•∂°º•«°º•ø§¨ —ππ§µ§Ï§∆§§§Ï§–Õ˙ŒÚ•∆°º•÷•Î§À≈–œø
        if (
            ($name_first    != $teamName) OR
            ($name_second   != $this->sname) OR
            ($kana_first    != $this->fkana) OR
            ($kana_second   != $this->skana) OR
            //($user_agent    != $this->userAgent) OR
            ($login_id      != $this->lid) OR
            ($email         != $this->email) OR
            ($employ_id     != $this->eid) OR
            ($mobile_career != $this->career) OR
            ($mobile_tel    != $this->mtel)
            ) {
            if (self::teamDataHistoryPreservation() == False) {
                // •Ì°º•Î•–•√•ØΩËÕ˝
                $this->Query( "ROLLBACK" );
                return $retData;
            }
        }
*/
        $abbNameSql = (isset($abbNameSql) AND $abb_name != '') ? " `abb_name` = '" . $abb_name . "', " : "" ;

        # •¡°º•‡•«°º•ø§Úππø∑
        $sql = "UPDATE ".dbTableName::LT_TEAM_INFO." SET " .
                     " `t_name` = '" . $teamName . "', " .
                     " `t_kana` = '" . $teamKana . "', " .
                     $abbNameSql .
                     " `home_color` = '" . $teamHomeColor . "', " .
                     " `away_color` = '" . $teamAwayColor . "', " .
                     " `activity_district` = '" . $teamDistrict . "', " .
                     " `activity_place` = '" . $teamPlace . "', " .
                     " `represent` = '" . $teamRep . "', " .
                     " `represent_tel` = '" . $teamRepTel . "', " .
                     " `represent_address` = '" . $teamRepMail . "', " .
                     " `represent_mobile_address` = '" . $teamRepMobileAddress . "', " .
                     " `represent_mobile_domain` = '" . $teamRepMobileDomain . "', " .
                     " `sub_represent` = '" . $teamSubRep . "', " .
                     " `sub_represent_tel` = '" . $teamSubRepTel . "', " .
                     " `sub_represent_address` = '" . $teamSubRepMail . "', " .
                     " `modified` = NOW( ) " .
                     " WHERE `id` = " . $teamId . " " ;
//print $sql."<BR>\n";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/teamDataChangeClass::teamDataChange]•¡°º•‡•«°º•ø§Œππø∑•®•È°º§«§π°£<br />";
           // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            $this->Query( "ROLLBACK" );
        } else {
            if (self::registTeamDataChange($rarryId, $teamId, $teamName) == False) {
                // •Ì°º•Î•–•√•ØΩËÕ˝
                $this->Query( "ROLLBACK" );
            } else {
                $retData = True;
                // •≥•ﬂ•√•»ΩËÕ˝
//                $this->Query( "ROLLBACK" );
                $this->Query( "COMMIT" );
            }
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : ≈–œø¡™ºÍ§´§È≥∞§π
     *
     * ¿‚Ã¿ : •¡°º•‡§Œ≈–œø¡™ºÍ§´§È•«°º•ø§Ú∫ÔΩ¸§π§Î
     *
     * @param integer   $rarryId         ¬Á≤ÒID
     * @param string    $seasonId        •∑°º•∫•ÛID
     * @param string    $memberId        •·•Û•–°ºID
     *
     * @return bool
     *
     * @access public
     */
    public function removeTeamPlayer($rarryId, $seasonId, $memberId) {

        $retData = False;

        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

        # ≤Ò∞˜•’•È•∞§Úππø∑
        $sql = "DELETE  FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `define` = " . $seasonId . " " .
               "  AND  `m_id` = " . $memberId . " " ;
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/teamDataChangeClass::removeTeamPlayer]≈–œø¡™ºÍ§´§È≥∞§π•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            $this->Query( "ROLLBACK" );
        } else {
            $retData = True;
            // •≥•ﬂ•√•»ΩËÕ˝
            //$this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •¡°º•‡≈–œø¡™ºÍ§´§È ¸Ω–§π§Î
     *
     * ¿‚Ã¿ : •¡°º•‡§Œ≈–œø¡™ºÍ§´§È•«°º•ø§Ú ¸Ω–§π§Î
     *
     * @param integer   $rarryId         ¬Á≤ÒID
     * @param string    $seasonId        •∑°º•∫•ÛID
     * @param string    $memberId        •·•Û•–°ºID
     *
     * @return bool
     *
     * @access public
     */
    public function dischargeTeamPlayer($rarryId, $seasonId, $memberId) {

        $retData = False;

        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

        # ¡™ºÍ•«°º•ø§Úππø∑
        $sql = "UPDATE ".dbTableName::LT_REGIST_TEAM_MEMBER." SET " .
                     " `modified` = NOW( ) " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `define` = " . $seasonId . " " .
               "  AND  `m_id` = " . $memberId . "";
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/teamDataChangeClass::dischargeTeamPlayer]•¡°º•‡≈–œø¡™ºÍ§´§È ¸Ω–§π§Î•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            $this->Query( "ROLLBACK" );
        } else {
            $retData = True;
            // •≥•ﬂ•√•»ΩËÕ˝
            //$this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •¡°º•‡≈–œø¡™ºÍ§´§È ¸Ω–§π§Î
     *
     * ¿‚Ã¿ : •¡°º•‡§Œ≈–œø¡™ºÍ§´§È•«°º•ø§Ú ¸Ω–§π§Î
     *
     * @param integer   $rarryId         ¬Á≤ÒID
     * @param string    $seasonId        •∑°º•∫•ÛID
     * @param string    $memberId        •·•Û•–°ºID
     *
     * @return bool
     *
     * @access public
     */
    public function dischargeTeamAllPlayer($rarryId, $seasonId, $teamId) {

        $retData = False;

        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

        # ¡™ºÍ•«°º•ø§Úππø∑
        $sql = "UPDATE ".dbTableName::LT_REGIST_TEAM_MEMBER." SET " .
                     " `modified` = NOW( ) " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `define` = " . $seasonId . " " .
               "  AND  `t_id` = " . $teamId . "";
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/teamDataChangeClass::dischargeTeamAllPlayer]•¡°º•‡≈–œø¡™ºÍ§´§È ¸Ω–§π§Î•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            $this->Query( "ROLLBACK" );
        } else {
            $retData = True;
            // •≥•ﬂ•√•»ΩËÕ˝
            //$this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •¡°º•‡≈–œøÃææŒ§Œ —ππ
     *
     * ¿‚Ã¿ : •¡°º•‡§Œ≈–œøÃææŒ§Œ —ππ
     *
     * @param integer   $rarryId         ¬Á≤ÒID
     * @param string    $seasonId        •∑°º•∫•ÛID
     * @param string    $memberId        •·•Û•–°ºID
     *
     * @return bool
     *
     * @access public
     */
    private function registTeamDataChange($rarryId, $teamId, $teamName) {

        $retData = False;

        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

        // •∑°º•∫•Û≈–œøæ Û§¨§ §±§Ï§– —ππ¬–æ›§«§œ§ §§§Œ§«Ω™Œª
        if (self::checkRarryRegistTeam($rarryId, $teamId) == true) {
        	return true;
        }

        # •¡°º•‡•«°º•ø§Úππø∑
        $sql = "UPDATE ".dbTableName::LT_REGIST_TEAM." SET " .
                     " `team_name` = '" . $teamName . "' " .
               " WHERE `r_id` = " . $rarryId . " " .
               //"  AND  `class` = " . $class . " " .
               "  AND  `t_id` = " . $teamId . " " ;
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/teamDataChangeClass::registTeamDataChange]•¡°º•‡≈–œøÃææŒ§Œ —ππ•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            $this->Query( "ROLLBACK" );
        } else {
            $retData = True;
            // •≥•ﬂ•√•»ΩËÕ˝
            //$this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •—•π•Ô°º•… —ππ§π§Î
     *
     * ¿‚Ã¿ : •¡°º•‡§Œ•—•π•Ô°º•…§Ú —ππ§π§Î
     *
     * @param integer   $userId          •Ê°º•∂°ºID
     * @param string    $password         —ππ•—•π•Ô°º•…
     * @param string    $beforPassword    —ππ¡∞•—•π•Ô°º•…
     *
     * @return bool
     *
     * @access public
     */
    public function teamPasswordChange($teamId, $password, $beforPassword) {

        $retData = False;

        $changePassword = $this->dataHash($password);

        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

        //  —ππ¡∞§Œ•—•π•Ô°º•…§»≈–œø•—•π•Ô°º•…§¨∞„§®§–Õ˙ŒÚ•∆°º•÷•Î§Àµ≠œø
        if ($beforPassword != $changePassword) {
            if (self::userDataHistoryPreservation() == False) {
                // •Ì°º•Î•–•√•ØΩËÕ˝
                $this->Query( "ROLLBACK" );
                return $retData;
            }
        }

        # ≤Ò∞˜•’•È•∞§Úππø∑
        $sql = "UPDATE ".dbTableName::LT_TEAM_INFO." SET " .
                     " `password` = '" . $changePassword . "', " .
                     " `update_date` = NOW( ) " .
                     " WHERE ".dbTableName::LT_TEAM_INFO.".`t_id` = " . $teamId . "";
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/teamDataChangeClass::teamPasswordChange]•¡°º•‡•—•π•Ô°º•…§Œ —ππ•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            $this->Query( "ROLLBACK" );
        } else {
            $retData = True;
            // •≥•ﬂ•√•»ΩËÕ˝
            //$this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : ¬Á≤Ò§À•¡°º•‡§¨≈–œø§µ§Ï§∆§§§Î§´•¡•ß•√•Ø§π§Î
     *
     * ¿‚Ã¿ : ¬Á≤Ò≈–œø•¡°º•‡•∆°º•÷•Î§Ú¡‹∫∫§π§Î°£
     *
     * @param integer   $rarryId         ¬Á≤ÒID
     * @param integer   $teamId          •¡°º•‡ID
     *
     * @return bool
     *
     * @access private
     */
    public function checkRarryRegistTeam($rarryId, $teamId) {

        # ≤Ò∞˜•«°º•ø§Úππø∑
        $sql = "SELECT `t_id`
                FROM ".dbTableName::LT_REGIST_TEAM."
                WHERE `r_id` = " . $rarryId . "
                  AND `t_id` = " . $teamId;
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if(!$rs){ print "[daas/teamDataChangeClass::checkRarryRegistTeam]•¡°º•‡•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; $this->DbErrorValue = $this->GetLastError(); return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        // •«°º•ø§¨§¢§Ï§–¬Á≤Ò§À≈–œø∫—§ﬂ§ §Œ§«false
        if($num > 0){
            return false;
        }

        return true;
    }

    /**
     * ≥µÕ◊ : ¬Á≤Ò•¡°º•‡≈–œø
     *
     * ¿‚Ã¿ : ¬Á≤Ò≈–œø•¡°º•‡•∆°º•÷•Î§À•¡°º•‡æ Û§Ú≈–œø§π§Î
     *
     * @param mixed     $insertDatas   ≈–œø•«°º•ø
     *
     * @return bool
     *
     * @access public
     */
    public function rarryRegistTeamInsert($insertDatas) {
        $retData = False;

        $this->r_id = 0;
        $this->class = 0;
        $this->t_id = 0;
        $this->team_name = "";
        $this->regist_flag = 0;
        $this->view_flag = 1;

        $dbTable = array(
                         "r_id" => "num",
                         "class" => "num",
                         "t_id" => "num",
                         "team_name" => "var",
                         "regist_flag" => "num",
                         "view_flag" => "num"
                         );

        // ¡˜øÆ•«°º•ø§Ú«€ŒÛ§À —¥π
        foreach ($insertDatas AS $key => $val) {

            if (array_key_exists($key, $dbTable)) {
                if ($val == "") {
                    switch ($dbTable[$key]) {
                      case "var" : $this->$key = ""; break;
                      case "num" : $this->$key = 0; break;
                      default : $this->$key = "";
                    }
                } else {
                    // øÙ√Õ∞ ≥∞§Ú•Ø•™°º•»§π§Î
                    if (!is_numeric($val)) {
                        $val = mysql_real_escape_string($val);
                    }
                    $this->$key = encode($val);
                }
            } else {
                if (array_key_exists($key, array_keys($dbTable))) {
                    switch ($dbTable[$key]) {
                      case "var" : $this->$key = "''"; break;
                      case "num" : $this->$key = 0; break;
                      default : $this->$key = "''";
                    }
                }
            }
        }

        # •«°º•ø§ŒºË∆¿
        $sql = "INSERT INTO ".dbTableName::LT_REGIST_TEAM."  (" .
                   " `r_id`, " .
                   " `class`, " .
                   " `t_id`, " .
                   " `team_name`, " .
                   " `regist_flag`, " .
                   " `view_flag` " .
                   " ) VALUES (" .
                   " '".$this->r_id."', " .
                   " '".$this->class."', " .
                   " '".$this->t_id."', " .
                   " '".$this->team_name."', " .
                   " '".$this->regist_flag."', " .
                   " '".$this->view_flag. "' " .
                   " )";
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            print "[dats/rarryRegistTeamInsert]¬Á≤Ò•¡°º•‡≈–œø•®•È°º§«§π°£<br />".$sql;
        } else {
            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
//        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •÷•Ì•√•Ø≈–œø•¡°º•‡§Ú∫ÔΩ¸§π§Î
     *
     * ¿‚Ã¿ : •÷•Ì•√•Ø≈–œø•¡°º•‡•∆°º•÷•Î§´§ÈªÿƒÍ§Œ•¡°º•‡•«°º•ø§Ú∫ÔΩ¸§π§Î
     *
     * @param integer   $rarryId         ¬Á≤ÒID
     * @param integer   $blockId        •÷•Ì•√•ØID
     * @param integer   $teamId         •¡°º•‡ID
     *
     * @return bool
     *
     * @access public
     */
    public function removeRegistTeam($rarryId, $blockId, $teamId) {
//print $rarryId."<BR>";
//print $blockId."<BR>";
//print $teamId."<BR>";
        $retData = false;

        if ($rarryId == '' OR $blockId == '' OR $teamId == '') {
            return $retData;
        }

        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        $this->Query("BEGIN");

        # ≤Ò∞˜•’•È•∞§Úππø∑
        $sql = "DELETE  FROM ".dbTableName::LT_REGIST_TEAM." " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `class` = " . $blockId . " " .
               "  AND  `t_id` = " . $teamId . " " ;
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/teamDataChangeClass::removeTeamPlayer]≈–œø¡™ºÍ§´§È≥∞§π•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
        } else {
            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •¡°º•‡≈–œøºÃøø§Œ —ππ
     *
     * ¿‚Ã¿ : •¡°º•‡§Œ≈–œøºÃøø§Œ —ππ
     *
     * @param integer   $rarryId         ¬Á≤ÒID
     * @param string    $teamId         •¡°º•‡ID
     * @param string    $colomns       ≈–œø•´•È•‡
     *
     * @return bool
     *
     * @access public
     */
    public function registTeamPictureChange($rarryId, $teamId, $colomns, $vinaryData="") {
//print $rarryId." = rid<BR>";
//print $teamId." = teamId<BR>";
//print $colomns." = colomns<BR>";
        $retData = false;

        if ($rarryId == "" OR $teamId == "" OR  $colomns == "") {
            return $retData;
        }

        // ¡™ºÍ•«°º•ø§Úππø∑
        $sql = "UPDATE ".dbTableName::LT_REGIST_TEAM." SET " .
                     " " . $colomns . " = '" . $vinaryData . "' " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `t_id` = " . $teamId . " " ;
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/registTeamPictureChange::registTeamDataChange]•¡°º•‡ºÃøø§Œ —ππ•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
        } else {
        	$retData = true;
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≥µÕ◊ : •¡°º•‡≈–œøºÃøø§ŒøΩ¿¡•’•È•∞ —ππ
     *
     * ¿‚Ã¿ : •«°º•ø•Ÿ°º•π§Œ•¡°º•‡≈–œøºÃøøøΩ¿¡•’•È•∞ —ππ
     *
     * @param integer   $rarryId		¬Á≤ÒID
     * @param string    $teamId			•¡°º•‡ID
     * @param string    $haMode			HOME°¶AWAY
     * @param string    $flag			ON°¶OFF•’•È•∞
     *
     * @return bool
     *
     * @access public
     */
    public function registTeamAppliesPictureFlagChange($rarryId, $teamId, $haMode, $flag=true) {

    	$retData = false;

    	$colomns = 'applies_pic_h';

        if ($rarryId == "" OR $teamId == "" OR  $haMode == "") {
            return $retData;
        }

        if ($haMode == 'away') {
        	$colomns = 'applies_pic_a';
        }

        # ¡™ºÍ•«°º•ø§Úππø∑
        $sql = "UPDATE ".dbTableName::LT_REGIST_TEAM." SET " .
                     " " . $colomns . " = '" . $flag . "' " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `t_id` = " . $teamId . " " ;
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/teamDataChange::registTeamAppliesPictureFlagChange]•¡°º•‡ºÃøøøΩ¿¡√Ê•’•È•∞§Œ —ππ•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
        } else {
        	$retData = true;
        }

        // •·•‚•Í≤Ú ¸
        //$this->FreeQuery($rs);

        return $retData;
    }


    //------------------------------------------------------
    // •®•È°º•«°º•ø
    //------------------------------------------------------
    /*
     * •®•È°º∆‚Õ∆§Ú ÷§π
     * @return void
     */
    function getDbErrorValue() {
        return $this->DbErrorValue;
    }

    /*
     * ¿∏¿Æ§∑§øURL•œ•√•∑•Â§Ú ÷§π
     * @return void
     */
    function getRegistUrlHash() {
        return $this->RegisturlHash;
    }

}

?>