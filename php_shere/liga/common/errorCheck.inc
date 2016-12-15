<?php

class errorCheck extends connectionDBClass {

    /**
     * コンストラクター.
     */
    public function __construct() {
    }

    /**
     * 概要 : 同日・同チーム試合数チェック（スケジュール登録）
     *
     * 説明 : 大会内で対戦しているかのチェック
     *
     * @param $rarryId    大会ID
     * @param $teamId     チームID
     * @param $dates      対戦日
    */
    public function checkDistinctTeamGames($rarryId, $teamId, $dates) {

        $num = 0;

        #
//        $sql = "SELECT " .
//                       " `id` " .
//                    " FROM `lt_schejule` " .
//                    " WHERE `r_id` = " . $rarryId . " " .
//                    "  AND  (`home_team` = " . $teamId . " OR `away_team` = " . $teamId . ")" .
//                    "  AND  DATE( `times` ) = '" . $dates . "'" ;
        $sql = "SELECT " .
                       " `id` " .
                    " FROM `rarry_schejules` " .
                    " WHERE `r_id` = " . $rarryId . " " .
                    "  AND  DATE_FORMAT( `times`, '%Y-%m-%d' ) = '" . $dates . "'" .
                    "  AND  (`home_team` = " . $teamId . " OR `away_team` = " . $teamId . ")" ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[errorCheck]同チーム試合数チェックエラーです。<br />".$sql."<br />"; return false; }

        // データ数を取得
        $num        = $this->GetRowCount($rs);

        // データが1件以上あればNG
        if($num > 0){

            $retData = False;
        }

        // メモリ解放
        $this->FreeQuery($rs);

        return $num;
    }


    //------------------------------------------------------
    // オブジェクト
    //------------------------------------------------------
    /*
     * 試合会場データを返す
     */
    function getRarryRegistBlockData() {
        return $this->RarryRegistBlockData;
    }

    /*
     * 試合会場データを返す
     */
    function getSelectBlockData() {
        return $this->SelectBlockData;
    }

    /*
     * 対戦節データを返す
     */
    function getParagraphNumber() {
        return $this->paragraphNumber;
    }

}

?>
