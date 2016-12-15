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
     * 選手マスターの最終登録IDの取得
     *
     * @return boolean  buf
     * @see ErrorMessageValue
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
        // SQL実行
        $rs  = $this->Query($sql);

        if(!$rs){
            print $sql."<br />[datas/memberDataChangeClass::maxMemberId]<br />";
            return false;
        }

        // データ数の取得
        $num        = $this->GetRowCount($rs);

        if($num > 0){
            $data   = $this->FetchRow($rs);       // 1行GET
            $maxMemberId = $data["id"];
        } else {
            $maxMemberId = 0;
        }

        return $maxMemberId;
    }

    /**
     * 選手登録
     *
     * 同時にチームへの登録
     *
     * @param resource  $methodName
     * @return boolean
     * @see ErrorMessageValue
     *
     * @access private
     */
    public function memberInsert($rarryId, $seasonId, $teamId, $insertMemberDatasArray) {

        $retData = False;
        (string)$sqlValue = "";
        (string)$teamRegistSqlValue = "";
        (string)$teamMemberRegistDatas = array();

        // $this->Query("BEGIN");

        // ID設定
        // $insertMemberId = self::maxMemberId() + 1;

        $teamMemberRegistDatas["number"] = $insertMemberDatasArray["number"];
        $teamMemberRegistDatas["posision"] = $insertMemberDatasArray["posision"];
        $teamMemberRegistDatas["captainFlag"] = 0;
        $teamMemberRegistDatas["comment"] = "";

        // 選手マスターに登録
        $sql = "INSERT INTO ".dbTableName::LT_MEMBER_INFO."  (" .
                //    " `id`, " .
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
                //    " '".$insertMemberId."', " .
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
if ($_SERVER['REMOTE_ADDR'] == '220.110.52.161') {
	// print $sql;
}
        // SQL実行
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            print $sql."<br />[datas/memberDataChangeClass::memberInsert]選手マスターの登録エラー<br />";
            // エラー内容取得
            $this->DbErrorValue = $this->GetLastError();
            // ロールバック
            // $this->Query( "ROLLBACK" );
        } else {

            // 登録したIDを取得
            // $insertMemberId = $this->GetCurrentID();
            $insertMemberIdGetSql = "SELECT LAST_INSERT_ID() as insertId";
            $getIdRs  = $this->Query($insertMemberIdGetSql);
            if ( !$getIdRs ) {
                print $insertMemberIdGetSql."<br />[datas/memberDataChangeClass::memberInsert]登録したID取得に失敗<br />";
                // エラー内容取得
                $this->DbErrorValue = $this->GetLastError();
                // ロールバック
                $this->Query( "ROLLBACK" );
            } else {
                $data   = $this->FetchRow($getIdRs);
                $insertMemberId = $data['insertId'];
if ($_SERVER['REMOTE_ADDR'] == '220.110.52.161') {
    // print nl2br(print_r($data));
    // print $insertMemberId." = insertMemberId<br />";
}

                // チームのメンバー登録
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
if ($_SERVER['REMOTE_ADDR'] == '220.110.52.161') {
	// print $tmSql;
}
                // SQL実行
                $tmRs  = $this->Query($tmSql);
                if ( !$tmRs ) {
                    print $tmSql."<br />[datas/memberDataChangeClass::memberInsert]チームのメンバー登録エラー<br />";
                    // エラー内容取得
                    $this->DbErrorValue = $this->GetLastError();
                } else {
                	// 支払い対象の大会の場合は登録費支払いフラグを立てる
                	if (INDI_PAYMENT_FLAG) {
                        // 支払い済みチェック
                        if (self::memberPaymentDataCheck($rarryId, $insertMemberId) == false) {
        	            	// 支払いフラグの登録
        	                if (self::memberPaymentTableInsert($rarryId, $insertMemberId) == true) {
        	                    $retData = True;
                                // コミット
                                //$this->Query( "ROLLBACK" );
                                // $this->Query( "COMMIT" );
        	                }
                        }
                	} else {
                		$retData = True;
                        // コミット
                        //$this->Query( "ROLLBACK" );
                        // $this->Query( "COMMIT" );
                	}
                }
            }
        }

        // SQL開放
        // $this->FreeQuery($rs);

        return $retData;
    }

    /**
     *
     *
     *
     *
     * @param resource  $methodName
     * @return boolean  True
     * @see ErrorMessageValue
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

	        // SQL生成
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

                // 支払い対象大会のチェック
            	if (INDI_PAYMENT_FLAG) {
	                // 支払い済みかどうかのチェック
	                if (self::memberPaymentDataCheck($rarryId, $memberArrayDatas[$i]["memberId"]) == false) {
	                	// 支払いフラグを立てる
		                if (self::memberPaymentTableInsert($rarryId, $memberArrayDatas[$i]["memberId"]) == false) {
		                    return $retData;
		                }
	                }
            	}
            }
        }

        // SQL生成
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

        // SQL実行
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            print $sql."<br />[datas/memberDataChangeClass::aheadSeasonPlayerTaking]<br />";
            // エラーメッセージ取得
            $this->DbErrorValue = $this->GetLastError();
            // ロールバック
            $this->Query( "ROLLBACK" );
        } else {
        	$retData = True;
            // コミット
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
     * 登録費用の支払いチェック
     *
     * @param integer  $rarryId    大会ID
     * @param integer  $memberId   選手ID
     *
     * @return boolean  True
     * @see ErrorMessageValue
     * @access private
     */
    private function memberPaymentDataCheck($rarryId, $memberId) {

        $retData = false;

        // SQL生成
        $sql = "SELECT count(`m_id`) AS COUNT " .
               " FROM ".dbTableName::LT_MEMBER_PAYMENT." " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `m_id` = " . $memberId . "";
//print $sql."<BR>";

        $rs  = $this->Query($sql);
        if(!$rs){ print $sql."<br />[datas/memberDataChangeClass::memberPaymentDataCheck]登録費用の支払いチェックに失敗<br />"; }

        // データ取得
        $data = $this->FetchRow($rs);

        // データがあればTrueを返す
        if ($data["COUNT"] > 0){
            $retData = true;
        }
        return $retData;
    }

    /**
     * 支払いテーブルへの新規登録
     *
     * @param integer  $rarryId    大会ID
     * @param integer  $memberId  選手ID
     *
     * @return boolean  True
     *
     * @see ErrorMessageValue
     *
     * @access private
     */
    private function memberPaymentTableInsert($rarryId, $memberId) {

        $retData = false;

        // SQL生成
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
        // SQL実行
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            print $sql."<br />[datas/memberDataChangeClass::memberPaymentTableInsert]支払いテーブルへの新規登録に失敗<br />";
            // エラーメッセージ取得
            $this->DbErrorValue = $this->GetLastError();
        } else {
            $retData = true;
        }
        return $retData;
    }

    /**
     *
     *
     *
     *
     * @param string    $memberId       選手ID
     * @param string    $paymentFlag     登録費の支払いフラグ
     *
     * @return bool
     *
     * @access public
     */
    public function memberRegistPaymentChange($rarryId, $memberId, $paymentFlag) {

        $retData = False;

        // 支払い対象大会のチェック
		if (INDI_PAYMENT_FLAG) {
	        // 登録費用の支払いチェック
	        if (self::memberPaymentDataCheck($rarryId, $memberId) == false) {
	            // 支払いフラグを立てる
	            if (self::memberPaymentTableInsert($rarryId, $memberId) == false) {
	                $retData = True;
	            }
	        }
		}

        // SQL生成
        $sql = "UPDATE ".dbTableName::LT_MEMBER_PAYMENT." SET " .
                     " `payment` = " . $paymentFlag . ", " .
                     " `modified` = NOW() " .
               " WHERE `r_id` = " . $rarryId . " " .
               "  AND  `m_id` = " . $memberId . " " ;
//print $sql."<BR>";

        // SQL実行
        $rs  = $this->Query($sql);

        if ( !$rs ) {
             print $sql."<br />[dats/memberDataChangeClass::memberRegistPaymentChange]<br />";
            // エラーメッセージ取得
            $this->DbErrorValue = $this->GetLastError();
        } else {
            $retData = True;
        }

        return $retData;
    }


    //------------------------------------------------------
    // データ取得関数
    //------------------------------------------------------
    /*
     * DBエラーメッセージの取得
     */
    function getDbErrorValue() {
        return $this->DbErrorValue;
    }
    /*
     * エラーメッセージの取得
     */
    function getErrorMessageValue() {
        return $this->ErrorMessageValue;
    }
    /*
     * データ配列の取得
     */
    function getDataArray() {
        return $this->dataArray;
    }
}


?>