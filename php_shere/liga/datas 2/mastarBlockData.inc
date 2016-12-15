<?php

class mastarBlockData extends connectionDBClass {

    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    /**
     * ≥µÕ◊ : •÷•Ì•√•Ø•ﬁ•π•ø°º•«°º•ø§ŒºË∆¿
     *
     * ¿‚Ã¿ : •÷•Ì•√•Ø•ﬁ•π•ø°º•«°º•ø§ŒºË∆¿
     *
     */
    public function masterBlockData() {

        $retData = False;

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " `id` " .
                       " ,`seq_no` " .
                       " ,`view_no` " .
                       " ,`block_name` " .
                       " ,`sub_name` " .
                    " FROM " . dbTableName::RARRY_CLASS_INFO . " " ;
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[mastarBlockData]•÷•Ì•√•Ø•ﬁ•π•ø°º•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø
                $this->mastertBlockDatas[$i]["id"] = $data["id"];
                $this->mastertBlockDatas[$i]["seq_no"] = $data["seq_no"];
                $this->mastertBlockDatas[$i]["view_no"] = $data["view_no"];
                $this->mastertBlockDatas[$i]["block_name"] = $data["block_name"];
                $this->mastertBlockDatas[$i]["sub_name"] = $data["sub_name"];

            }
            $retData = True;
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
    */
    public function rarryRegistBlockData($rarryId) {

        $retData = False;

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " MCI.`id` AS BLOCK_ID, " .
                       " MCI.`seq_no` AS SEQ_NO, " .
                       " MCI.`block_name` AS BLOCK_NAME, " .
                       " MCI.`sub_name` AS SUB_NAME, " .
                       " RUC.`class` AS RARRY_CLASS, " .
                       " RUC.`regist_team_num` AS REGIST_TEAM_NUM " .
                    " FROM " . dbTableName::RARRY_USE_CLASS . " RUC " .
                    " LEFT JOIN " . dbTableName::RARRY_CLASS_INFO . " MCI " .
                    "       ON  RUC.`class` =  MCI.`id` " .
                    " WHERE RUC.`r_id` = " . $rarryId . "" .
                    " ORDER BY MCI.`seq_no`, MCI.`view_no`";
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[mastarBlockData]ªÓπÁ≤ÒæÏ•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø
                $this->RarryRegistBlockData[$i]["BLOCK_ID"] = $data["BLOCK_ID"];
                $this->RarryRegistBlockData[$i]["SEQ_NO"] = $data["SEQ_NO"];
                $this->RarryRegistBlockData[$i]["BLOCK_NAME"] = $data["BLOCK_NAME"];
                $this->RarryRegistBlockData[$i]["SUB_NAME"] = $data["SUB_NAME"];
                $this->RarryRegistBlockData[$i]["RARRY_CLASS"] = $data["RARRY_CLASS"];
                $this->RarryRegistBlockData[$i]["REGIST_TEAM_NUM"] = $data["REGIST_TEAM_NUM"];

            }
            $retData = True;
        } else {
            // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§¨§ §§æÏπÁ
            $this->RarryRegistBlockData[0]["BLOCK_ID"] = "";
            $this->RarryRegistBlockData[0]["SEQ_NO"] = "";
            $this->RarryRegistBlockData[0]["BLOCK_NAME"] = "";
            $this->RarryRegistBlockData[0]["SUB_NAME"] = "";
            $this->RarryRegistBlockData[0]["RARRY_CLASS"] = "";
            $this->RarryRegistBlockData[0]["REGIST_TEAM_NUM"] = "";
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
    public function selectRrarryRegistBlockData($rarryId, $blockId) {

        $retData = False;

        # ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " MCI.`id` AS BLOCK_ID " .
                       " ,MCI.`block_name` AS BLOCK_NAME " .
                       " ,MCI.`sub_name` AS SUB_NAME " .
                       " ,RUC.`regist_team_num` AS REGIST_TEAM_NUM " .
                    " FROM " . dbTableName::RARRY_USE_CLASS . " RUC " .
                    " LEFT JOIN " . dbTableName::RARRY_CLASS_INFO . " MCI " .
                    "       ON  RUC.`class` = MCI.`id` " .
                    " WHERE RUC.`r_id` = " . $rarryId . "" .
                    "  AND  RUC.`class` = " . $blockId . "";
//print $sql."<BR>";
        $rs  = $this->Query($sql);
        if(!$rs){ print "[mastarBlockData]¬Á≤Ò§Œ•÷•Ì•√•Ø•«°º•øB§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

            // ¬Á≤Ò•÷•Ì•√•Ø(•Ø•È•π)•«°º•ø
            $this->SelectBlockData["BLOCK_ID"] = $data["BLOCK_ID"];
            $this->SelectBlockData["BLOCK_NAME"] = $data["BLOCK_NAME"];
            $this->SelectBlockData["SUB_NAME"] = $data["SUB_NAME"];
            $this->SelectBlockData["REGIST_TEAM_NUM"] = $data["REGIST_TEAM_NUM"];

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }


    //------------------------------------------------------
    // •™•÷•∏•ß•Ø•»
    //------------------------------------------------------
    /*
     * •÷•Ì•√•Ø•ﬁ•π•ø°º•«°º•ø§Ú ÷§π
     */
    function getMastertBlockDatas() {
        return $this->mastertBlockDatas;
    }

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

}

?>
