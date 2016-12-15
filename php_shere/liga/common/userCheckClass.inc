<?php

// =============================================================================
// $Id: $
/**
 * @file    errorCheck.inc
 * @brief   システムライブラリ エラーチェック関数
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
class userCheckClass extends connectionDBClass {

    /**
     * コンストラクター.
     */
    public function __construct() {
    }

    /**
     * 概要 : 重複登録チェックMagicMethod関数
     *
     * 説明 :
     *
     * @param resource  $methodName   メソッド名
     * @param mixed     $datas   検索データ
     *
     * @return boolean  True：会員重複登録無し　False：会員重複登録あり
     *
     * @see ErrorMessageValue  エラーメッセージ内容
     *
     * @access private
     */
    private function __call($methodName, $datas) {

        $retData = True;

        switch ($methodName) {
          case "distinctUserMobileTelCheck" :
            $sqlWhere = "`mobile_tel` = '" . $datas[0] . "'";
            break;
          case "distinctMailCheck" :
            $sqlWhere = "`email` = '" . $datas[0][0] . "'" .
                        " AND `mobile_career` = " . $datas[0][1] . "";
            break;
          case "distinctLoginIdCheck" :
            $sqlWhere = "`login_id` = '" . $datas[0] . "'";
            break;
          default : return $retData;
        }

        //$connectDbClass = new connectionDBClass;   # データベース接続オブジェクト

        # 携帯データの取得
        $sql = "SELECT " .
                       " `user_id`, " .
                       " `regist_flg` " .
                    " FROM ".dbTableName::USER_DATA." " .
                    " WHERE " . $sqlWhere . " ";
//print $sql;
        $rs  = $this->Query($sql);
        if(!$rs){
            print $sql."<br />[common/userCheckClass::__call]SQLエラーです。<br />".$sql."<br />";
            $this->DbErrorValue = $this->GetLastError();
            return false;
        }

        // データがあったら登録状態の取得
        if($this->GetRowCount($rs) > 0){
            $data   = $this->FetchRow($rs);
            $this->UserId = $data["user_id"];
            $this->RegistMode = $data["regist_flg"];
            $errorMessage = new errorMessage;   # エラーメッセージオブジェクト
            if ($methodName == "distinctUserMobileTelCheck") {
                $this->ErrorMessageValue = $errorMessage->getErrorMessage("M_TEL_DISTINCT");
            } else if ($methodName == "distinctLoginIdCheck") {
                $this->ErrorMessageValue = $errorMessage->getErrorMessage("M_LOGIN_ID_DISTINCT");
            }
            $retData = False;
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : ログインチェック
     *
     * 説明 :
     *
     * @param string  $loginId  ログインID
     * @param string  $pass     パスワード
     *
     * @return boolean  True：会員重複登録無し　False：会員重複登録あり
     *
     * @see ErrorMessageValue  エラーメッセージ内容
     *
     * @access public
     */
    public function teamInfoLoginCheck($errorMessageValues, $loginId, $passwd) {

        $retData = False;

        # 携帯データの取得
        $sql = "SELECT " .
                       " `id` " .
                    " FROM ".dbTableName::LT_TEAM_INFO." " .
                    " WHERE `login_id` = '" . $loginId . "' " .
                    "  AND  `password` = '" . hash('ripemd160', $passwd) . "' " ;
//print $sql."<br>";
        $rs  = $this->Query($sql);
        if(!$rs){
            print $sql."<br />[common/userCheckClass::teamInfoLoginCheck]ログインチェックエラーです。<br />".$sql."<br />";
            $this->DbErrorValue = $this->GetLastError();
            return false;
        }

        // データがあったらTrue
        if($this->GetRowCount($rs) > 0){
            $retData = True;
            $data   = $this->FetchRow($rs);
            $this->TeamDatas["teamId"] = $data["id"];
        } else {
            $this->ErrorMessageValue = $errorMessageValues->getErrorMessage("LOGIN_ERROR");
        }

        // メモリ解放
        $this->FreeQuery($rs);

//print $pass." = pass<br>";

        return $retData;
    }

    /**
     * 概要 : 大会登録チームチェック
     *
     * 説明 :
     *
     * @param string  $rarryId         大会ID
     * @param string  $teamId          ユーザーID
     *
     * @return boolean  True：大会登録チームにチームIDがあるとき　False：大会未登録チームのとき
     *
     * @see ErrorMessageValue  エラーメッセージ内容
     *
     * @access public
     */
    public function teamRarryRegistCheck($errorMessageValues, $rarryId, $seasonId, $teamId) {

        $retData = False;

        # 携帯データの取得
        $sql = "SELECT " .
                       " `class` " .
                    " FROM ".dbTableName::LT_REGIST_TEAM." " .
                    " WHERE `r_id` = '" . $rarryId . "' " .
                    "  AND  `t_id` = '" . $teamId . "' ";
//print $sql."<br>";
        $rs  = $this->Query($sql);
        if(!$rs){
            print $sql."<br />[common/userCheckClass::teamRarryRegistCheck]大会登録チームチェックエラーです。<br />".$sql."<br />";
            $this->DbErrorValue = $this->GetLastError();
            return false;
        }

        // データがあったらTrue
        if($this->GetRowCount($rs) > 0){
            $retData = True;
            $data   = $this->FetchRow($rs);
            $this->TeamRarryRegistDatas["class"] = $data["class"];
        } else {
            $this->ErrorMessageValue = $errorMessageValues->getErrorMessage("RARRY_REGIST_ERROR");
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : チーム選手登録チェック
     *
     * 説明 : 選手が大会に登録しているかチェックする
     *
     * @param string  $rarryId           大会ID
     * @param string  $teamId            チームID
     * @param string  $memberId          ユーザーID
     *
     * @return boolean  True：選手が大会登録していないとき　False：大会未登録選手のとき
     *
     * @see ErrorMessageValue  エラーメッセージ内容
     *
     * @access public
     */
    public function memberRarryRegistCheck($errorMessageValues, $rarryId, $takingRarryId, $seasonId, $takingSeasonId, $teamId, $memberId) {

        $retData = true;

        $this->takingRarryId = $takingRarryId;
        $this->takingSeasonId = $takingSeasonId;
        $this->teamId = $teamId;
        $this->memberId = $memberId;

        # 携帯データの取得
        $sql = "SELECT " .
                       " LRTM.`t_id`, " .
                       " LRTM.`number`, " .
                       " LTI.`t_name`, " .
                       " MMI.`name_first`, " .
                       " MMI.`name_second` " .
                    " FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
                    " LEFT JOIN ".dbTableName::LT_TEAM_INFO." LTI " .
                    "        ON LRTM.`t_id` = LTI.`id`" .
                    " LEFT JOIN ".dbTableName::LT_MEMBER_INFO." MMI " .
                    "        ON LRTM.`m_id` = MMI.`id`" .
                    " WHERE LRTM.`r_id` = '" . $rarryId . "' " .
                    "  AND  LRTM.`m_id` = '" . $memberId . "' ";
//print $sql."<br>";
        $rs  = $this->Query($sql);
        if(!$rs){
            print $sql."<br />[common/userCheckClass::memberRarryRegistCheck]チーム選手登録チェックエラーです。<br />".$sql."<br />";
            $this->DbErrorValue = $this->GetLastError();
            return false;
        }

        // データがあったら登録済み選手
        if($this->GetRowCount($rs) > 0){
            $retData = false;
            $data   = $this->FetchRow($rs);
            $this->memberRarryRegistDatas["teamName"] = $data["t_name"];
            $this->memberRarryRegistDatas["number"] = $data["number"];
            $this->memberRarryRegistDatas["firstName"] = $data["name_first"];
            $this->memberRarryRegistDatas["secondName"] = $data["name_second"];
            // チームIDと登録済みチームIDが同じのとき
            if ($teamId == $data["t_id"]) {
                $this->memberRarryRegistDatas["sameFlag"] = "sameTeam";
            } else {
                $this->memberRarryRegistDatas["sameFlag"] = "diffTeam";
            }
        } else {
            if (self::selectMemberData() == true) {
/*
                $this->memberRarryRegistDatas["number"] = $this->selectMemberDatas["number"];
                $this->memberRarryRegistDatas["posision"] = $this->selectMemberDatas["posision"];
                $this->memberRarryRegistDatas["firstName"] = $this->selectMemberDatas["firstName"];
                $this->memberRarryRegistDatas["secondName"] = $this->selectMemberDatas["secondName"];
*/
                $this->memberRarryRegistDatas = $this->selectMemberDatas;
            }
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : メンバーチェック
     *
     * 説明 :
     *
     * @param $memberId    選手ID
    */
    public function distinctMemberCheck($nameFirst, $nameSecond) {

        $ret = False;

        $sql = "SELECT " .
                       " LRTM.`number` AS number, " .
                       " LRTM.`p_id` AS posision, " .
                       " LTI.`t_name` AS t_name, " .
                       " MMI.`name_first` AS name_first, " .
                       " MMI.`name_second` AS name_second " .
                    " FROM ".dbTableName::LT_MEMBER_INFO." MMI " .
                    " LEFT JOIN ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
                    "        ON MMI.`id` = LRTM.`m_id`" .
                    " LEFT JOIN ".dbTableName::LT_TEAM_INFO." LTI " .
                    "        ON MMI.`id` = LRTM.`m_id`" .
                    " WHERE MMI.`name_first` IS NOT NULL " .
                    "  AND  MMI.`name_first` = '" . $nameFirst . "' " .
                    "  AND  MMI.`name_second` = '" . $nameSecond . "' " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){
            print $sql."<br />[common/userCheckClass::distinctMemberCheck]メンバーチェックエラーです。<br />".$sql."<br />";
            $this->DbErrorValue = $this->GetLastError();
            return false;
        }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        // データが1件以上あればばTrueを返す
        if($num > 0){
            $ret = True;
            $data   = $this->FetchRow($rs);
            $this->distinctMemberData["number"] = $data["number"];
            $this->distinctMemberData["posision"] = $data["posision"];
            $this->distinctMemberData["teamName"] = $data["t_name"];
            $this->distinctMemberData["nameFirst"] = $data["name_first"];
            $this->distinctMemberData["nameSecond"] = $data["name_second"];
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $ret;
    }

    /**
     * 概要 : メンバーデータを返す
     *
     * 説明 :
     *
     * @param $memberId    選手ID
    */
    private function selectMemberData() {

        $ret = false;

        $sql = "SELECT " .
                       " LRTM.`number`, " .
                       " LRTM.`p_id`, " .
                       " MMI.`name_first`, " .
                       " MMI.`name_second` " .
                    " FROM ".dbTableName::LT_REGIST_TEAM_MEMBER." LRTM " .
                    " LEFT JOIN ".dbTableName::LT_MEMBER_INFO." MMI " .
                    "        ON LRTM.`m_id` = MMI.`id`" .
                    " WHERE LRTM.`r_id` = " . $this->takingRarryId . " " .
                    "  AND  LRTM.`define` = " . $this->takingSeasonId . " " .
                    "  AND  LRTM.`m_id` = " . $this->memberId . " " .
                    "  AND  LRTM.`t_id` = " . $this->teamId . " " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){
            print $sql."<br />[common/userCheckClass::selectMemberData]メンバーデータを返すエラーです。<br />".$sql."<br />";
            $this->DbErrorValue = $this->GetLastError();
            return false;
        }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        // データが1件以上あればばTrueを返す
        if($num > 0){
            $ret = true;
            $data   = $this->FetchRow($rs);
            $this->selectMemberDatas["number"] = $data["number"];
            $this->selectMemberDatas["posision"] = $data["p_id"];
            $this->selectMemberDatas["firstName"] = $data["name_first"];
            $this->selectMemberDatas["secondName"] = $data["name_second"];
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $ret;
    }


    //------------------------------------------------------
    // エラーデータ
    //------------------------------------------------------
    /*
     * DBエラー内容を返す
     */
    function getDbErrorValue() {
        return $this->DbErrorValue;
    }
    /*
     * エラー内容を返す
     */
    function getErrorMessageValue() {
        return $this->ErrorMessageValue;
    }
    /*
     * 登録済みのチームIDを返す
     */
    function getTeamDatas() {
        return $this->TeamDatas;
    }
    /*
     * 大会登録クラスを返す
     */
    function getTeamRarryRegistDatas() {
        return $this->TeamRarryRegistDatas;
    }
    /*
     * 登録済み選手データを返す
     */
    function getMemberRarryRegistDatas() {
        return $this->memberRarryRegistDatas;
    }
    /*
     * 登録済み選手データを返す
     */
    function getDistinctMemberData() {
        return $this->distinctMemberData;
    }
}


?>