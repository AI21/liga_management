<?php

// =============================================================================
// $Id: $
/**
 * @file    errorCheck.inc
 * @brief   •∑•π•∆•‡•È•§•÷•È•Í •®•È°º•¡•ß•√•Ø¥ÿøÙ
 * @author
 * @date    Last updated:
 *
 *          Copyright(c) 2006
 */
// =============================================================================
/**
 *
 */
// =============================================================================
class memberDataChangeClass extends connectionDBClass {

    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    /**
     * ≥µÕ◊ : •·•Û•–°º≈–œøøÙ§ŒºË∆¿
     *
     * ¿‚Ã¿ :
     *
     * @return boolean  buf
     *
     * @see ErrorMessageValue  •®•È°º•·•√•ª°º•∏∆‚Õ∆
     *
     * @access private
     */
    private function maxMemberId() {

        $sql = "SELECT " .
                       " `id` " .
                    " FROM ".dbTableName::LT_MEMBER_INFO." " .
                    "  ORDER BY `id` DESC " .
                    "  LIMIT 1 " ;
//print $sql;
        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if(!$rs){
            print $sql."<br />[datas/memberDataChangeClass::maxMemberId]•¡°º•‡≈–œø•·•Û•–°º•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />";
            return false;
        }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){
            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù
            $maxMemberId = $data["id"];
        } else {
            $maxMemberId = 0;
        }

        return $maxMemberId;
    }

    /**
     * ≥µÕ◊ : •·•Û•–°º≈–œø§π§Î
     *
     * ¿‚Ã¿ :
     *
     * @param resource  $methodName   •·•Ω•√•…Ãæ
     *
     * @return boolean  True°ß≤Ò∞˜Ω≈ £≈–œøÃµ§∑°°False°ß≤Ò∞˜Ω≈ £≈–œø§¢§Í
     *
     * @see ErrorMessageValue  •®•È°º•·•√•ª°º•∏∆‚Õ∆
     *
     * @access private
     */
    public function memberInsert($rarryId, $seasonId, $teamId, $insertMemberDatasArray) {

        $retData = False;
        (string)$sqlValue = "";
        (string)$teamRegistSqlValue = "";
        (string)$teamMemberRegistDatas = array();

        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
        //$this->Query("BEGIN");

        // ≈–œø•·•Û•–°ºID
        $insertMemberId = self::maxMemberId() + 1;

        $teamMemberRegistDatas["number"] = $insertMemberDatasArray["number"];
        $teamMemberRegistDatas["posision"] = $insertMemberDatasArray["posision"];
        $teamMemberRegistDatas["captainFlag"] = 0;
        $teamMemberRegistDatas["comment"] = "";

        # •Ê°º•∂°º≈–œø
        $sql = "INSERT INTO ".dbTableName::LT_MEMBER_INFO."  (" .
                   " `id`, " .
                   " `name_first`, " .
                   " `name_second`, " .
                   " `kana_first`, " .
                   " `kana_second`, " .
                   " `height`, " .
                   " `birthday`, " .
                   " `m_comment`, " .
                   " `created`, " .
                   " `modified` " .
                   " ) VALUES (" .
                   " '".$insertMemberId."', " .
                   " '".$insertMemberDatasArray["name_first"]."', " .
                   " '".$insertMemberDatasArray["name_second"]."', " .
                   " '".$insertMemberDatasArray["kana_first"]."', " .
                   " '".$insertMemberDatasArray["kana_second"]."', " .
                   " '".$insertMemberDatasArray["height"]."', " .
                   " '".$insertMemberDatasArray["birthday"]."', " .
                   " '', " .
                   " NOW( ), " .
                   " '0000-00-00 00:00:00' " .
                   " )";
//if ($_SERVER['REMOTE_ADDR'] == '210.254.40.8') {
//	print $sql;
//}
        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            print $sql."<br />[datas/memberDataChangeClass::memberInsert]•·•Û•–°º≈–œø•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
            // •Ì°º•Î•–•√•ØΩËÕ˝
            //$this->Query( "ROLLBACK" );
        } else {

            // ΩÍ¬∞•¡°º•‡≈–œø
            $tmSql = "INSERT INTO ".dbTableName::LT_REGIST_TEAM_MEMBER."  (" .
                     " `r_id`, " .
                     " `t_id`, " .
                     " `m_id`, " .
                     " `number`, " .
                     " `define`, " .
                     " `p_id`, " .
                     " `captain_flg`, " .
                     " `comment`, " .
                     " `created`, " .
                     " `modified` " .
                   " ) VALUES " .
                     "(" .
                     " '".$rarryId."', " .
                     " '".$teamId."', " .
                     " '".$insertMemberId."', " .
                     " '".$teamMemberRegistDatas["number"]."', " .
                     " '".$seasonId."', " .
                     " '".$teamMemberRegistDatas["posision"]."', " .
                     " '".$teamMemberRegistDatas["captainFlag"]."', " .
                     " '', " .
                     " NOW( ), " .
                     " '0000-00-00 00:00:00' " .
                     ")";
//if ($_SERVER['REMOTE_ADDR'] == '210.254.40.8') {
//	print $tmSql;
//}
            // •«°º•ø≈–œø
            $tmRs  = $this->Query($tmSql);
            if ( !$tmRs ) {
                print $tmSql."<br />[datas/memberDataChangeClass::memberInsert]•·•Û•–°º≈–œø•®•È°º§«§π°£<br />";
                // •®•È°º∆‚Õ∆
                $this->DbErrorValue = $this->GetLastError();
            } else {
            	// ∏ƒøÕ≈–œø»Ò§¨…¨Õ◊§´°©
            	if (INDI_PAYMENT_FLAG) {
	                // ∏ƒøÕ≈–œø»ÒÕ—ªŸ ß§§•∆°º•÷•Î§À•«°º•ø§¨§¢§Î§´•¡•ß•√•Ø
	                if (self::memberPaymentDataCheck($rarryId, $insertMemberId) == false) {
		            	// ∏ƒøÕ≈–œø»ÒÕ—ªŸ ß§§•∆°º•÷•Î§ÿ§Œ≈–œø
		                if (self::memberPaymentTableInsert($rarryId, $insertMemberId) == true) {
		                    $retData = True;
		                }
	                }
            	} else {
            		$retData = True;
            	}
            }
        }

/*
        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);
*/
        return $retData;
    }

    /**
     * ≥µÕ◊ : ¡∞•∑°º•∫•Û§Œ¡™ºÍ§Ú∫£¥¸¡™ºÍ§ÀºË§Íπ˛§ﬂ§π§Î
     *
     * ¿‚Ã¿ :
     *
     * @param resource  $methodName   •·•Ω•√•…Ãæ
     *
     * @return boolean  True°ß≤Ò∞˜Ω≈ £≈–œøÃµ§∑°°False°ß≤Ò∞˜Ω≈ £≈–œø§¢§Í
     *
     * @see ErrorMessageValue  •®•È°º•·•√•ª°º•∏∆‚Õ∆
     *
     * @access private
     */
    public function aheadSeasonPlayerTaking($rarryId, $seasonId, $teamId, $memberArrayDatas) {

        $retData = False;
        (string)$sqlValue = "";

        if (!isset($rarryId) AND !isset($seasonId) AND !isset($teamId)) {
          return $retData;
        }
        $playersNum = count($memberArrayDatas);
        if ($playersNum == 0) {
          return $retData;
        } else {

	        // •»•È•Û•∂•Ø•∑•Á•Û≥´ªœ
	        $this->Query("BEGIN");

        	for ($i=0; $i<$playersNum; $i++) {
                $sqlValue .= "(";
                $sqlValue .= " '".$rarryId."', ";
                $sqlValue .= " '".$teamId."', ";
                $sqlValue .= " '".$memberArrayDatas[$i]["memberId"]."', ";
                $sqlValue .= " '".$memberArrayDatas[$i]["number"]."', ";
                $sqlValue .= " '".$seasonId."', ";
                $sqlValue .= " '".$memberArrayDatas[$i]["posision"]."', ";
                $sqlValue .= " '".$memberArrayDatas[$i]["captainFlag"]."', ";
                $sqlValue .= " '".$memberArrayDatas[$i]["comment"]."', ";
                $sqlValue .= " NOW( ), ";
                $sqlValue .= " '0000-00-00 00:00:00' ";
                $sqlValue .= ")";
                (($i + 1) != $playersNum) ? $sqlValue .= "," : $sqlValue .= ";";

                // ∏ƒøÕ≈–œø»Ò§¨…¨Õ◊§´°©
            	if (INDI_PAYMENT_FLAG) {
	                // •∑°º•∫•Û≥´ªœ§Œª˛§œ∏ƒøÕ≈–œø»ÒÕ—ªŸ ß§§•«°º•ø§Œ∫Ó¿Æ
//	                if ($seasonId == 1) {
		                // ∏ƒøÕ≈–œø»ÒÕ—ªŸ ß§§•∆°º•÷•Î§À•«°º•ø§¨§¢§Î§´•¡•ß•√•Ø
		                if (self::memberPaymentDataCheck($rarryId, $memberArrayDatas[$i]["memberId"]) == false) {
		                	// ∏ƒøÕ≈–œø»ÒÕ—ªŸ ß§§•∆°º•÷•Î§ÿ§Œ≈–œø
			                if (self::memberPaymentTableInsert($rarryId, $memberArrayDatas[$i]["memberId"]) == false) {
			                    return $retData;
			                }
		                }
//	                }
            	}
            }
        }

        # •«°º•ø§ŒºË∆¿
        $sql = "INSERT INTO ".dbTableName::LT_REGIST_TEAM_MEMBER."  (" .
                   " `r_id`, " .
                   " `t_id`, " .
                   " `m_id`, " .
                   " `number`, " .
                   " `define`, " .
                   " `p_id`, " .
                   " `captain_flg`, " .
                   " `comment`, " .
                   " `created`, " .
                   " `modified` " .
                   " ) VALUES " .
                   $sqlValue;
//print $sql;

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            print $sql."<br />[datas/memberDataChangeClass::aheadSeasonPlayerTaking]∫£¥¸¡™ºÍ§ÀºË§Íπ˛§ﬂ•®•È°º§«§π°£<br />";
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

/*
        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);
*/
        return $retData;
    }

    /**
     * ≥µÕ◊ : ∏ƒøÕ≈–œø»ÒÕ—§ŒªŸ ß§§•∆°º•÷•Î§À•«°º•ø§¨§¢§Î§´•¡•ß•√•Ø
     *
     * ¿‚Ã¿ : ∏ƒøÕ≈–œø»ÒÕ—ªŸ ß§§•∆°º•÷•Î§Œ•«°º•ø§Ú¡‹∫∫§π§Î
     *
     * @param integer  $rarryId    ¬Á≤ÒID
     * @param integer  $memberId   •·•Û•–°ºID
     *
     * @return boolean  True°ß•«°º•ø§¨§¢§Í°°False°ß•«°º•ø§ §∑
     *
     * @see ErrorMessageValue  •®•È°º•·•√•ª°º•∏∆‚Õ∆
     *
     * @access private
     */
    private function memberPaymentDataCheck($rarryId, $memberId) {

        $retData = false;

        // •«°º•ø§ŒºË∆¿
        $sql = "SELECT count(`m_id`) AS COUNT " .
               " FROM ".dbTableName::LT_MEMBER_PAYMENT." " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `m_id` = " . $memberId . "";
//print $sql."<BR>";

        $rs  = $this->Query($sql);
        if(!$rs){ print $sql."<br />[datas/memberDataChangeClass::memberPaymentDataCheck]∏ƒøÕ≈–œø»ÒÕ—§ŒªŸ ß§§•∆°º•÷•Î•¡•ß•√•Ø•®•È°º§«§π°£<br />"; }

        // •«°º•øøÙ§ÚºË∆¿
        $data = $this->FetchRow($rs);

        // •«°º•ø§¨£±∑Ô∞ æÂ§¢§Ï§–true
        if ($data["COUNT"] > 0){
            $retData = true;
        }
        return $retData;
    }

    /**
     * ≥µÕ◊ : ∏ƒøÕ≈–œø»ÒÕ—§ŒªŸ ß§§•∆°º•÷•Î§ÿ≈–œø§π§Î
     *
     * ¿‚Ã¿ :
     *
     * @param integer  $rarryId    ¬Á≤ÒID
     * @param integer  $memberId   •·•Û•–°ºID
     *
     * @return boolean  True°ß≈–œø¿Æ∏˘°°False°ß≈–œøº∫«‘
     *
     * @see ErrorMessageValue  •®•È°º•·•√•ª°º•∏∆‚Õ∆
     *
     * @access private
     */
    private function memberPaymentTableInsert($rarryId, $memberId) {

        $retData = false;

        // ∏ƒøÕ≈–œø»ÒÕ—ªŸ ß§§•∆°º•÷•Î§ÿ≈–œø
        $sql = "INSERT INTO ".dbTableName::LT_MEMBER_PAYMENT."  (" .
                   " `r_id`, " .
                   " `m_id`, " .
                   " `created` " .
                   " ) VALUES (" .
                   " '".$rarryId."', " .
                   " '".$memberId."', " .
                   " NOW( ) " .
                   " )";
//print $sql;
        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            print $sql."<br />[datas/memberDataChangeClass::memberPaymentTableInsert]∏ƒøÕ≈–œø»ÒÕ—§ŒªŸ ß§§•∆°º•÷•Î≈–œø•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
        } else {
            $retData = true;
        }
        return $retData;
    }

    /**
     * ≥µÕ◊ : ªÿƒÍ¡™ºÍ§Œ≈–œøŒ¡ªŸ ß§§•’•È•∞§Œππø∑
     *
     * ¿‚Ã¿ : ¡™ºÍ§Œ≈–œøŒ¡ªŸ ß§§•’•È•∞§Úππø∑§π§Î
     *
     * @param string    $memberId        •·•Û•–°ºID
     * @param string    $paymentFlag     ªŸ ß§§•’•È•∞
     *
     * @return bool
     *
     * @access public
     */
    public function memberRegistPaymentChange($rarryId, $memberId, $paymentFlag) {

        $retData = False;

        // ∏ƒøÕ≈–œø»Ò§¨…¨Õ◊§´°©
		if (INDI_PAYMENT_FLAG) {
	        // ∏ƒøÕ≈–œø»ÒÕ—§Œ•«°º•ø§¨§¢§Î§´•¡•ß•√•Ø§π§Î
	        if (self::memberPaymentDataCheck($rarryId, $memberId) == false) {
	            // •«°º•ø§¨Ãµ§§§»§≠§œ•«°º•ø≈–œø
	            if (self::memberPaymentTableInsert($rarryId, $memberId) == false) {
	                $retData = True;
	            }
	        }
		}

        # ¡™ºÍ•«°º•ø§Úππø∑
        $sql = "UPDATE ".dbTableName::LT_MEMBER_PAYMENT." SET " .
                     " `payment` = " . $paymentFlag . ", " .
                     " `modified` = NOW() " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `m_id` = " . $memberId . " " ;
//print $sql."<BR>";

        // •«°º•ø≈–œø
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/memberDataChangeClass::memberRegistPaymentChange]ªÿƒÍ¡™ºÍ§Œ≈–œøŒ¡ªŸ ß§§•’•È•∞§Œππø∑•®•È°º§«§π°£<br />";
            // •®•È°º∆‚Õ∆
            $this->DbErrorValue = $this->GetLastError();
        } else {
            $retData = True;
        }

        return $retData;
    }


    //------------------------------------------------------
    // •®•È°º•«°º•ø
    //------------------------------------------------------
    /*
     * DB•®•È°º∆‚Õ∆§Ú ÷§π
     */
    function getDbErrorValue() {
        return $this->DbErrorValue;
    }
    /*
     * •®•È°º∆‚Õ∆§Ú ÷§π
     */
    function getErrorMessageValue() {
        return $this->ErrorMessageValue;
    }
    /*
     * §π
     */
    function getDataArray() {
        return $this->dataArray;
    }
}


?>