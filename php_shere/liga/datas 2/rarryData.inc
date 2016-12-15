<?php

// 設定ファイル読み込み
//require_once "connectionDB.inc";

class rarryData extends connectionDBClass {

    /**
     * コンストラクター.
     */
    public function __construct() {
    }

    /**
     * 概要 : 大会内容データの取得
     *
     * 説明 : 大会概要・大会勝ち点・その他データの取得
     *
     * @param $rarryId    大会ID
    */
    public function rarryDetails($rarryId) {

        $retData = False;

        $sql = "SELECT " .
                     " LRI.`rarry_name` AS rarry_name, " .
                     " LRI.`rarry_sub_name` AS rarry_sub_name, " .
                     " LRI.`define` AS define, " .
                     " LRI.`type` AS type, " .
                     " LRI.`parent_id` AS parent_id, " .
                     " LRI.`progress` AS progress, " .
                     " LRI.`finish_flg` AS finish_flg, " .
                     " LRI.`created` AS created, " .
                     " LRI.`modified` AS modified, " .
//                     " MRT.`RARRY_TYPE` AS RARRY_TYPE, " .
//                     " MRT.`TYPE_VIEW` AS TYPE_VIEW, " .
                     " LYP.`win` AS win, " .
                     " LYP.`lose` AS lose, " .
                     " LYP.`draw` AS draw, " .
                     " LYP.`antiwar_win` AS antiwar_win, " .
                     " LYP.`antiwar_lose` AS antiwar_lose, " .
                     " LYP.`special1` AS special1, " .
                     " LYP.`special_exp1` AS special_exp1, " .
                     " LYP.`special2` AS special2, " .
                     " LYP.`special_exp2` AS special_exp2, " .
                     " LYP.`special3` AS special3, " .
                     " LYP.`special_exp3` AS special_exp3, " .
                     " LYP.`special4` AS special4, " .
                     " LYP.`special_exp4` AS special_exp4, " .
                     " LYP.`special5` AS special5, " .
                     " LYP.`special_exp5` AS special_exp5 " .
               " FROM " . dbTableName::LT_RARRY_INFO . " AS LRI " .
               " LEFT JOIN " . dbTableName::LT_YEAR_POINT . " AS LYP " .
               "  ON  LRI.`id` = LYP.`r_id` " .
//               " LEFT JOIN `m_rally_type` AS MRT " .
//               "  ON  LRI.`type` = MRT.`RARRY_TYPE` " .
               " WHERE LRI.`id` = '" . $rarryId . "' " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]大会内容データ取得エラーです。<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if ($num > 0){

            $data   = $this->FetchRow($rs);       // １行Ｇｅｔ

//            $this->RarryName = $data["RARRY_NAME"];
//            $this->RarrySubName = $data["RARRY_SUB_NAME"];
//            $this->RarryType = $data["RARRY_TYPE"];
//            $this->TypeView = $data["TYPE_VIEW"];
//            $this->WinPoint = $data["WIN_P"];
//            $this->LosePoint = $data["LOSE_P"];
//            $this->DrowPoint = $data["DROW_P"];
//            $this->AntiWinPoint = $data["ANT_WIN_P"];
//            $this->AntiLosePoint = $data["ANT_LOSE_P"];
//            $this->SpecialPoint1 = $data["SP_P1"];

            $this->rarryDetail['rarry_name'] = $data["rarry_name"];
            $this->rarryDetail['rarry_sub_name'] = $data["rarry_sub_name"];
            $this->rarryDetail['season'] = $data["season"];
            $this->rarryDetail['type'] = $data["type"];
            $this->rarryDetail['parent_id'] = $data["parent_id"];
            $this->rarryDetail['progress'] = $data["progress"];
            $this->rarryDetail['finish_flg'] = $data["finish_flg"];
            $this->rarryDetail['created'] = $data["created"];
            $this->rarryDetail['modified'] = $data["modified"];
//            $this->rarryDetails['RARRY_TYPE'] = $data["RARRY_TYPE"];
//            $this->rarryDetails['TYPE_VIEW'] = $data["TYPE_VIEW"];
            $this->rarryDetail['win'] = $data["win"];
            $this->rarryDetail['lose'] = $data["lose"];
            $this->rarryDetail['draw'] = $data["draw"];
            $this->rarryDetail['antiwar_win'] = $data["antiwar_win"];
            $this->rarryDetail['antiwar_lose'] = $data["antiwar_lose"];
            $this->rarryDetail['special1'] = $data["special1"];
            $this->rarryDetail['special_exp1'] = $data["special_exp1"];
            $this->rarryDetail['special2'] = $data["special2"];
            $this->rarryDetail['special_exp2'] = $data["special_exp2"];
            $this->rarryDetail['special3'] = $data["special3"];
            $this->rarryDetail['special_exp3'] = $data["special_exp3"];
            $this->rarryDetail['special4'] = $data["special4"];
            $this->rarryDetail['special_exp4'] = $data["special_exp4"];
            $this->rarryDetail['special5'] = $data["special5"];
            $this->rarryDetail['special_exp5'] = $data["special_exp5"];

            $retData = True;
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : 大会クラスデータの取得
     *
     * 説明 : 大会のクラスデータの取得
     *
     * @param $rarryId    大会ID
    */
    public function rarryClassData($rarryId) {

        $retData = False;

/*
        $sql = "SELECT " .
                     " DISTINCT LRT.`class` AS RARRY_CLASS_ID, " .
                     " MCI.`class_neme` AS RARRY_CLASS_NAME " .
               " FROM `lt_regist_team` AS LRT " .
               " LEFT JOIN `rarry_class_info` AS MCI " .
               "  ON   LRT.`class` = MCI.`class` " .
               " WHERE LRT.`r_id` = '" . $rarryId . "' " ;
               " ORDER BY RARRY_CLASS_ID ";
*/
        $sql = "SELECT " .
                     " RCI.`id` AS RARRY_BLOCK_ID, " .
                     " RCI.`block_name` AS RARRY_BLOCK_NAME " .
               " FROM " . dbTableName::RARRY_USE_CLASS . " AS RUC " .
               " LEFT JOIN " . dbTableName::RARRY_CLASS_INFO . " AS RCI " .
               "  ON  RUC.`class` = RCI.`id` " .
               " WHERE RUC.`r_id` = '" . $rarryId . "' " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]大会クラスデータの取得エラーです。<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if ($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = mysql_fetch_array($rs);       // １行Ｇｅｔ

                $this->RarryClass[$i]["RARRY_BLOCK_ID"] = $data["RARRY_BLOCK_ID"];
                $this->RarryClass[$i]["RARRY_BLOCK_NAME"] = $data["RARRY_BLOCK_NAME"];

            }

            $retData = True;
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : 大会登録チームの取得
     *
     * 説明
     *
     * @param $rarryId    大会ID
     * @param $season     大会年度
     * @param $class      大会クラス区分
     */
    public function rarryProgressSection($rarryId, $class) {

        $retData = False;

        $sql = "SELECT " .
                     " MAX(LS.`section`) AS PROGRESS_SECTION, " .
                     " MAX(LS.`exe_days`) AS LAST_EXE_DAYS " .
               " FROM `lt_rarry_info` AS LRI, `lt_score` AS LS " .
               " WHERE LRI.`r_id` = LS.`r_id` " .
               "  AND  LRI.`r_id` = '" . $rarryId . "' " .
               //"  AND  LRI.`define` = '" . $define . "' " .
               "  AND  LS.`class` = '" . $class . "' " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]大会登録チームの取得エラーです。<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if ($num > 0){

            $data   = $this->FetchRow($rs);       // １行Ｇｅｔ

            $this->ProgressSection = $data["PROGRESS_SECTION"];
            $this->LastExeDays = $data["LAST_EXE_DAYS"];

            $retData = True;
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : 大会進行中の節の取得
     *
     * 説明
     *
     * @param $rarryId    大会ID
     * @param $season     大会年度
     * @param $class      大会クラス区分
     */
    public function rarryRegistTeam($rarryId, $class) {

        $retData = False;

        $sql = "SELECT " .
                     " LTI.`t_id` AS TEAM_ID, " .
                     " LTI.`t_name` AS TEAM_NAME, " .
                     " LTI.`t_kana` AS TEAM_KANA, " .
                     " RCI.`block_neme` AS CLASS_NAME " .
               " FROM `lt_regist_team` AS LRT, `lt_team_info` AS LTI, `lt_rarry_info` AS LRI, `rarry_class_info` AS RCI " .
               " WHERE LRT.`t_id` = LTI.`t_id` " .
               "  AND  LTI.`class` = RCI.`block_id` " .
               "  AND  LRT.`r_id` = LRI.`r_id` " .
               "  AND  LRT.`r_id` = '" . $rarryId . "' " .
               //"  AND  LRI.`define` = '" . $define . "' " .
               "  AND  LRT.`class` = '" . $class . "' " .
               " ORDER BY LTI.`t_kana` ";

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]大会進行中の節の取得エラーです。<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // １行Ｇｅｔ

                // クラス表示名は統一なので一件目のみ取得
                if ($i == 0) {
                    $this->ClassName = $data["CLASS_NAME"];
                }

                $this->ret["TEAM_ID"][$i] = $data["TEAM_ID"];
                $this->ret["TEAM_NAME"][$i] = $data["TEAM_NAME"];
                $this->ret["TEAM_KANA"][$i] = $data["TEAM_KANA"];

            }

            $retData = True;
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : 試合をした日の取得
     *
     * 説明
     *
     * @param $rarryId    大会ID
     * @param $season     大会年度
     * @param $class      大会クラス区分
     */
    /* リーグ形式の勝ち点計算
     * ホームチームとアウェイチーム)の得失点差で勝ち点を決めて合計する
     * ただし、リーグ重要会議不参加・不戦勝・敗が起こっていれば優先する
     * 同時に得失点差も計算
     */
    public function rarryExeGameDays($rarryId, $class) {

        $retData = False;

        $sql = "SELECT " .
                     " LS.`exe_days` AS EXE_DAYS " .
/*
                     " DATE_FORMAT(LS.`exe_days`, '%Y') AS EXE_YEAR, " .
                     " DATE_FORMAT(LS.`exe_days`, '%m') AS EXE_MONTH, " .
                     " DATE_FORMAT(LS.`exe_days`, '%d') AS EXE_DAY, " .
*/
               " FROM `lt_rarry_info` AS LRI, `lt_score` AS LS " .
               " WHERE LRI.`r_id` = LS.`r_id` " .
               "  AND  LRI.`r_id` = '" . $rarryId . "' " .
               //"  AND  LRI.`define` = '" . $define . "' " .
               //"  AND  LS.`class` = '" . $class . "' " .
               "  AND  LS.`exe_days` != '0000-00-00' " .
               " GROUP BY LS.`exe_days` " .
               " ORDER BY LS.`exe_days` DESC " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]試合をした日の取得エラーです。<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // １行Ｇｅｔ

                // クラス表示名は統一なので一件目のみ取得
                //if ($i == 0) {
                //    $this->ClassName = $data["CLASS_NAME"];
                //}

                $this->RarryExeGameDays[$i] = $data["EXE_DAYS"];

            }

            $retData = True;
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : リーグ大会履歴の取得
     *
     * 説明
     *
     * @param $rarryId    大会ID
     * @param $season     大会年度
     * @param $class      大会クラス区分
     */
    public function rarryReagueHistory() {

	    $retData = False;

        $sql = "SELECT " .
                     " `id` , " .
                     " `rarry_name` , " .
                     " `rarry_sub_name`, " .
                     " `season`, " .
                     " `parent_id`, " .
                     " `finish_flg` " .
               " FROM ".dbTableName::LT_RARRY_INFO." " .
//               " WHERE `type` = '1' " .
               " ORDER BY `finish_flg`, `season` DESC, `parent_id`, `id` " ;

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]リーグ大会履歴の取得エラーです。<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = $this->FetchRow($rs);       // １行Ｇｅｔ

                $this->RarryReagueHistory[$i]["rarryId"] = $data["id"];
                $this->RarryReagueHistory[$i]["rarryName"] = $data["rarry_name"];
                $this->RarryReagueHistory[$i]["rarrySubName"] = $data["rarry_sub_name"];
                $this->RarryReagueHistory[$i]["season"] = $data["season"];
                $this->RarryReagueHistory[$i]["parentId"] = $data["parent_id"];
                $this->RarryReagueHistory[$i]["finish_flg"] = $data["finish_flg"];

            }

            $retData = True;
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : 登録されている大会データの取得
     *
     * 説明 : DB登録の全大会データの取得
     *
     */
    public function rarryAllDatas() {

        $retData = False;

        $colomns = array(
                         'id',
                         'rarry_name',
                         'rarry_sub_name',
                         'season',
                         'type',
                         'parent_id',
                         'progress',
                         'finish_flg',
                         'created',
                         'modified',
                         );
        $sqlColomns = implode("`,`", $colomns);

        $sql = "SELECT " .
                     " `" . $sqlColomns . "` " .
               " FROM ".dbTableName::LT_RARRY_INFO .
               " ORDER BY `season`, `id`";

//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[datas/rarryData]大会データの取得エラーです。<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        if ($num > 0){

            for ($i=0; $i<$num; $i++) {

                $data   = mysql_fetch_array($rs);       // １行Ｇｅｔ

                foreach ($colomns as $val) {
                    $this->rarryAllData[$i][$val] = $data[$val];
                }
            }

            $retData = True;
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * 概要 : 大会情報を更新する
     * 説明 : 大会データを更新する
     * @param resource  $insertData   更新データ
     * @return boolean  True：更新完了　False：更新エラー
     * @see ErrorMessageValue  エラーメッセージ内容
     * @access public
     */
    public function rarryUpdate($updateDatas) {

        $ret = false;

        $upSql = "UPDATE " . dbTableName::LT_RARRY_INFO . " SET
                   rarry_name = '".$updateDatas['rarry_name']."'
                  ,rarry_sub_name = '".$updateDatas['rarry_sub_name']."'
                  ,season = '".$updateDatas['season']."'
                  ,type = '".$updateDatas['type']."'
                  ,parent_id = '".$updateDatas['parent_id']."'
                  ,progress = '".$updateDatas['progress']."'
                  ,finish_flg = '".$updateDatas['finish_flg']."'
                  ,modified = NOW()
                  WHERE id = " . $updateDatas['rid'] . "
                  ";
        $rs = $this->Query($upSql);
//print $upSql."<br />";

        // データ登録
        $rs  = $this->Query($upSql);

        if ( !$rs ) {
            // エラー内容
            $this->DbErrorValue = $this->GetLastError();
            // ロールバック処理
            $this->Query( "ROLLBACK" );
            print var_dump($this->DbErrorValue);
        } else {
            $retData = true;
            // コミット処理
            //$this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

        return $ret;
    }

    /**
     * 概要 : 大会情報を登録する
     * 説明 : 大会データを新規登録する
     * @param resource  $insertData   登録データ
     * @return boolean  True：登録完了　False：登録エラー
     * @see ErrorMessageValue  エラーメッセージ内容
     * @access public
     */
    public function rarryInsert($insertData) {

//print nl2br(print_r($insertData, true));

        $retData = false;

        # データの取得
        $sql = "INSERT INTO ".dbTableName::LT_RARRY_INFO."  (" .
                   " `rarry_name`, " .
                   " `rarry_sub_name`, " .
                   " `season`, " .
                   " `type`, " .
                   " `parent_id`, " .
                   " `progress`, " .
                   " `created`, " .
                   " `modified` " .
                   " ) VALUES (" .
                   "  '" . $insertData['rarry_name'] . "' " .
                   ", '" . $insertData['rarry_sub_name'] . "' " .
                   ", '" . $insertData['season'] . "' " .
                   ", '" . $insertData['type'] . "' " .
                   ", '" . $insertData['parent_id'] . "' " .
                   ", '" . $insertData['progress'] . "' " .
                   ",  NOW() " .
                   ", '0000-00-00 00:00:00' " .
                   ")";
//print $sql;
        // トランザクション開始
        $this->Query("BEGIN");

        // データ登録
        $rs  = $this->Query($sql);

        if ( !$rs ) {
            // エラー内容
            $this->DbErrorValue = $this->GetLastError();
            // ロールバック処理
            $this->Query( "ROLLBACK" );
            print var_dump($this->DbErrorValue);
        } else {
            $retData = true;
            // コミット処理
            //$this->Query( "ROLLBACK" );
            $this->Query( "COMMIT" );
        }

/*
        // メモリ解放
        $this->FreeQuery($rs);
*/
        return $retData;
    }


    /*
     * 登録している全大会情報を返す
     */
    function getRarryAllDatas() {
        return $this->rarryAllData;
    }

    /*
     * 大会情報を返す
     */
    function getRarryDetail() {
        return $this->rarryDetail;
    }

    /*
     * 大会名を返す
     */
    function getRarryName() {
        return $this->RarryName;
    }

    /*
     * 大会名副題を返す
     */
    function getRarrySubName() {
        return $this->RarrySubName;
    }

    /*
     * 大会タイプIDを返す
     */
    function getRarryType() {
        return $this->RarryType;
    }

    /*
     * 大会タイプ表示名を返す
     */
    function getTypeView() {
        return $this->TypeView;
    }

    /*
     * 試合：勝利時の勝ち点ポイントを返す
     */
    function getWinPoint() {
        return $this->WinPoint;
    }

    /*
     * 試合：敗戦時の勝ち点ポイントを返す
     */
    function getLosePoint() {
        return $this->LosePoint;
    }

    /*
     * 試合：同点時の勝ち点ポイントを返す
     */
    function getDrowPoint() {
        return $this->DrowPoint;
    }

    /*
     * 試合：不戦勝時の勝ち点ポイントを返す
     */
    function getAntiWinPoint() {
        return $this->AntiWinPoint;
    }

    /*
     * 試合：不戦負時の勝ち点ポイントを返す
     */
    function getAntiLosePoint() {
        return $this->AntiLosePoint;
    }

    /*
     * 試合：大会特別処理の勝ち点ポイントを返す
     */
    function getSpecialPoint1() {
        return $this->SpecialPoint1;
    }

    /*
     * 大会クラス別IDを返す
     */
    function getRarryClass() {
        return $this->RarryClass;
    }

    /*
     * クラス表示名を返す
     */
    function getClassName() {
        return $this->ClassName;
    }

    /*
     * 大会参加チームデータを返す
     */
    function getRegistTeamData() {
        return $this->ret;
    }

    /*
     * 大会進行中の節を返す
     */
    function getProgressSection() {
        return $this->ProgressSection;
    }

    /*
     * 試合をした日付を返す
     */
    function getRarryExeGameDays() {
        return $this->RarryExeGameDays;
    }

    /*
     * リーグ大会履歴情報を返す
     */
    function getRarryReagueHistory() {
        return $this->RarryReagueHistory;
    }


}

?>
