<?php

// ¿ﬂƒÍ•’•°•§•Î∆…§ﬂπ˛§ﬂ
//require_once "connectionDB.inc";

class mastarHallData extends connectionDBClass {

    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    // •≥°º•»§Œ≤ÒæÏ•«°º•ø
    public function selectCoatHallData($cortId = 0) {

        $retData = False;

        # ªÓπÁ≤ÒæÏ•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " MHI.`id` AS HallId, " .
                       " MHI.`h_name` AS h_name, " .
                       " MHI.`sub_name` AS sub_name, " .
                       " MCI.`court_name` AS cort_name, " .
                       " MHI.`ryakumei` AS ryakumei, " .
                       " MHI.`zip1` AS zip1, " .
                       " MHI.`zip2` AS zip2, " .
                       " MHI.`place` AS place, " .
                       " MHI.`city` AS city, " .
                       " MHI.`address1` AS address1, " .
                       " MHI.`address2` AS address2, " .
                       " MHI.`site` AS site " .
                    " FROM `master_court_informations` MCI " .
                    " LEFT JOIN `master_hall_informations` MHI " .
                    "  ON  MCI.`hall_id` = MHI.`id` " .
                    " WHERE MCI.`id` = " . $cortId . "";
//print $sql."<BR>";
        //$rs  = PostSQL(mb_convert_encoding($sql, "UTF-8", "EUC-JP"));
        $rs  = $this->Query($sql);
        if(!$rs){ print "[mastarHallData]•≥°º•»§Œ≤ÒæÏ•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

            // ªÓπÁ≤ÒæÏ•«°º•ø
            $this->coatHallData["HallId"] = $data["HallId"];
            $this->coatHallData["h_name"] = $data["h_name"];
            $this->coatHallData["sub_name"] = $data["sub_name"];
            $this->coatHallData["cort_name"] = $data["cort_name"];
            $this->coatHallData["ryakumei"] = $data["ryakumei"];
            $this->coatHallData["zip1"] = $data["zip1"];
            $this->coatHallData["zip2"] = $data["zip2"];
            $this->coatHallData["place"] = $data["place"];
            $this->coatHallData["city"] = $data["city"];
            $this->coatHallData["address1"] = $data["address1"];
            $this->coatHallData["address2"] = $data["address2"];
            $this->coatHallData["site"] = $data["site"];

            $retData = True;
        } else {
            // ªÓπÁ≤ÒæÏ•«°º•ø§¨§ §§æÏπÁ
            $this->coatHallData["HallId"] = "";
            $this->coatHallData["h_name"] = "";
            $this->coatHallData["sub_name"] = "";
            $this->coatHallData["cort_name"] = "";
            $this->coatHallData["ryakumei"] = "";
            $this->coatHallData["zip1"] = "";
            $this->coatHallData["zip2"] = "";
            $this->coatHallData["place"] = "";
            $this->coatHallData["address1"] = "";
            $this->coatHallData["address2"] = "";
            $this->coatHallData["site"] = "";
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }


    // ªÓπÁ≤ÒæÏ•«°º•ø
    public function selectHallData($hallId = 0) {

        $retData = False;

        # ªÓπÁ≤ÒæÏ•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " MHI.`id` AS HallId, " .
                       " MHI.`h_name` AS h_name, " .
                       " MHI.`h_kana` AS h_kana, " .
                       " MHI.`sub_name` AS sub_name, " .
                       " MHI.`ryakumei` AS ryakumei, " .
                       " MHI.`zip1` AS zip1, " .
                       " MHI.`zip2` AS zip2, " .
                       " MHI.`place` AS place, " .
                       " MHI.`city` AS city, " .
                       " MHI.`address1` AS address1, " .
                       " MHI.`address2` AS address2, " .
                       " MHI.`tel` AS tel, " .
                       " MHI.`tel2` AS tel2, " .
                       " MHI.`tel3` AS tel3, " .
                       " MHI.`latitude` AS latitude, " .
                       " MHI.`longitude` AS longitude, " .
                       " MHI.`site` AS site " .
                    " FROM `master_hall_informations` MHI " .
                    " WHERE MHI.`id` = " . $hallId . "";
//print $sql."<BR>";
        //$rs  = PostSQL(mb_convert_encoding($sql, "UTF-8", "EUC-JP"));
        $rs  = $this->Query($sql);
        if(!$rs){ print "[mastarHallData]ªÓπÁ≤ÒæÏ•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

            // ªÓπÁ≤ÒæÏ•«°º•ø
            $this->hallData["HallId"] = $data["HallId"];
            $this->hallData["h_name"] = $data["h_name"];
            $this->hallData["h_kana"] = $data["h_kana"];
            $this->hallData["sub_name"] = $data["sub_name"];
            $this->hallData["ryakumei"] = $data["ryakumei"];
            $this->hallData["zip1"] = $data["zip1"];
            $this->hallData["zip2"] = $data["zip2"];
            $this->hallData["place"] = $data["place"];
            $this->hallData["city"] = $data["city"];
            $this->hallData["address1"] = $data["address1"];
            $this->hallData["address2"] = $data["address2"];
            $this->hallData["tel"] = $data["tel"];
            $this->hallData["tel2"] = $data["tel2"];
            $this->hallData["tel3"] = $data["tel3"];
            $this->hallData["latitude"] = $data["latitude"];
            $this->hallData["longitude"] = $data["longitude"];
            $this->hallData["site"] = $data["site"];

            $retData = True;
        } else {
            // ªÓπÁ≤ÒæÏ•«°º•ø§¨§ §§æÏπÁ
            $this->hallData["HallId"] = "";
            $this->hallData["h_name"] = "";
            $this->hallData["sub_name"] = "";
            $this->hallData["ryakumei"] = "";
            $this->hallData["zip1"] = "";
            $this->hallData["zip2"] = "";
            $this->hallData["place"] = "";
            $this->hallData["address1"] = "";
            $this->hallData["address2"] = "";
            $this->hallData["tel"] = "";
            $this->hallData["tel2"] = "";
            $this->hallData["tel3"] = "";
            $this->hallData["latitude"] = "";
            $this->hallData["longitude"] = "";
            $this->hallData["site"] = "";
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }


    // ¡¥ªÓπÁ≤ÒæÏ•«°º•ø
    public function allHallData($rarryId=0) {

        $retData = False;
        (string)$sqlWhere = '';

        if (($rarryId > 0) AND is_numeric($rarryId)) {
            $sqlWhere = " FROM `rarry_use_halls` RUH, " . dbTableName::M_HALL_INFO . " MHI " .
                        " WHERE RUH.`hall_id` = MHI.`id` " .
                        "  AND  RUH.`r_id` = '" . $rarryId . "' ";
        } else {
            $sqlWhere = " FROM " . dbTableName::M_HALL_INFO . " MHI " ;
        }

        # ªÓπÁ≤ÒæÏ•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " DISTINCT(MHI.`id`) AS HallId, " .
                       " `h_name` AS HallName, " .
                       " `sub_name` AS HallSubName, " .
                       //" `coat` AS HallCort, " .
                       " `ryakumei` AS HallRyakumei, " .
                       " `zip1` AS HallZip1, " .
                       " `zip2` AS HallZip2, " .
                       " `place` AS HallPlace, " .
                       " `city` AS HallCity, " .
                       " `address1` AS HallAddress1, " .
                       " `address2` AS HallAddress2, " .
                       " `tel` AS HallTel1, " .
                       " `tel2` AS HallTel2, " .
                       " `tel3` AS HallTel3, " .
                       " `latitude` AS HallLatitude, " .
                       " `longitude` AS HallLongitude, " .
                       " `site` AS HallSite " .
                    $sqlWhere .
                    " ORDER BY  `place`, `h_name`";
//print $sql."<BR>";
        //$rs  = PostSQL(mb_convert_encoding($sql, "UTF-8", "EUC-JP"));
        $rs  = $this->Query($sql);
        if(!$rs){ print "[mastarHallData]¡¥ªÓπÁ≤ÒæÏ•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // ªÓπÁ≤ÒæÏ•«°º•ø
                $this->AllHallDatas[$i]["HallId"] = $data["HallId"];
                $this->AllHallDatas[$i]["HallName"] = $data["HallName"];
                $this->AllHallDatas[$i]["HallSubName"] = $data["HallSubName"];
                //$this->AllHallDatas[$i]["HallCort"] = $data["HallCort"];
                $this->AllHallDatas[$i]["HallRyakumei"] = $data["HallRyakumei"];
                $this->AllHallDatas[$i]["HallZip1"] = $data["HallZip1"];
                $this->AllHallDatas[$i]["HallZip2"] = $data["HallZip2"];
                $this->AllHallDatas[$i]["HallPlace"] = $data["HallPlace"];
                $this->AllHallDatas[$i]["HallCity"] = $data["HallCity"];
                $this->AllHallDatas[$i]["HallAddress1"] = $data["HallAddress1"];
                $this->AllHallDatas[$i]["HallAddress2"] = $data["HallAddress2"];
                $this->AllHallDatas[$i]["HallTel1"] = $data["HallTel1"];
                $this->AllHallDatas[$i]["HallTel2"] = $data["HallTel2"];
                $this->AllHallDatas[$i]["HallTel3"] = $data["HallTel3"];
                $this->AllHallDatas[$i]["HallLatitude"] = $data["HallLatitude"];
                $this->AllHallDatas[$i]["HallLongitude"] = $data["HallLongitude"];
                $this->AllHallDatas[$i]["HallSite"] = $data["HallSite"];
            }
            $this->count = $i;

            $retData = True;
        } else {
                // ªÓπÁ≤ÒæÏ•«°º•ø§¨§ §§æÏπÁ
                $this->AllHallDatas[0]["HallId"] = "";
                $this->AllHallDatas[0]["HallName"] = "";
                $this->AllHallDatas[0]["HallSubName"] = "";
                $this->AllHallDatas[0]["HallCort"] = "";
                $this->AllHallDatas[0]["HallRyakumei"] = "";
                $this->AllHallDatas[0]["HallZip1"] = "";
                $this->AllHallDatas[0]["HallZip2"] = "";
                $this->AllHallDatas[0]["HallPlace"] = "";
                //$this->AllHallDatas[0]["HallCity"] = "";
                $this->AllHallDatas[0]["HallAddress1"] = "";
                $this->AllHallDatas[0]["HallAddress2"] = "";
                $this->AllHallDatas[0]["HallSite"] = "";
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    // ¬Á≤Ò Ãª»Õ—≤ÒæÏ•«°º•ø
    public function rarryUseHallData($rarryId=0) {

        $retData = False;
        (string)$sqlWhere = '';

        if (($rarryId > 0) AND is_numeric($rarryId)) {
            $sqlWhere = " FROM `rarry_use_halls` RUH, " . dbTableName::M_HALL_INFO . " MHI " .
                        " WHERE RUH.`hall_id` = MHI.`id` " .
                        "  AND  RUH.`r_id` = '" . $rarryId . "' ";
        } else {
            $sqlWhere = " FROM " . dbTableName::M_HALL_INFO . " MHI " ;
        }

        # ªÓπÁ≤ÒæÏ•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " MHI.`id` AS HallId, " .
                       " MHI.`h_name` AS HallName, " .
                       " MHI.`sub_name` AS HallSubName, " .
                       " MHI.`ryakumei` AS HallRyakumei, " .
                       " MHI.`zip1` AS HallZip1, " .
                       " MHI.`zip2` AS HallZip2, " .
                       " MHI.`place` AS HallPlace, " .
                       " MHI.`city` AS HallCity, " .
                       " MHI.`address1` AS HallAddress1, " .
                       " MHI.`address2` AS HallAddress2, " .
                       " MHI.`tel` AS HallTel1, " .
                       " MHI.`tel2` AS HallTel2, " .
                       " MHI.`tel3` AS HallTel3, " .
                       " MHI.`latitude` AS HallLatitude, " .
                       " MHI.`longitude` AS HallLongitude, " .
                       " MHI.`site` AS HallSite " .
                " FROM " . dbTableName::RARRY_USE_HALL . " RUH " .
                " LEFT JOIN " . dbTableName::M_HALL_INFO . " MHI " .
                "       ON  RUH.`hall_id` = MHI.`id` " .
                " WHERE `r_id` = " . $rarryId . " " .
                " ORDER BY  RUH.`seq_no`";
//print $sql."<BR>";
        //$rs  = PostSQL(mb_convert_encoding($sql, "UTF-8", "EUC-JP"));
        $rs  = $this->Query($sql);
        if(!$rs){ print "[rarryUseHallData]¬Á≤Ò Ãª»Õ—≤ÒæÏ•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // ªÓπÁ≤ÒæÏ•«°º•ø
                $this->rarryUseHallDatas[$i]["HallId"] = $data["HallId"];
                $this->rarryUseHallDatas[$i]["HallName"] = $data["HallName"];
                $this->rarryUseHallDatas[$i]["HallSubName"] = $data["HallSubName"];
                //$this->rarryUseHallDatas[$i]["HallCort"] = $data["HallCort"];
                $this->rarryUseHallDatas[$i]["HallRyakumei"] = $data["HallRyakumei"];
                $this->rarryUseHallDatas[$i]["HallZip1"] = $data["HallZip1"];
                $this->rarryUseHallDatas[$i]["HallZip2"] = $data["HallZip2"];
                $this->rarryUseHallDatas[$i]["HallPlace"] = $data["HallPlace"];
                $this->rarryUseHallDatas[$i]["HallCity"] = $data["HallCity"];
                $this->rarryUseHallDatas[$i]["HallAddress1"] = $data["HallAddress1"];
                $this->rarryUseHallDatas[$i]["HallAddress2"] = $data["HallAddress2"];
                $this->rarryUseHallDatas[$i]["HallTel1"] = $data["HallTel1"];
                $this->rarryUseHallDatas[$i]["HallTel2"] = $data["HallTel2"];
                $this->rarryUseHallDatas[$i]["HallTel3"] = $data["HallTel3"];
                $this->rarryUseHallDatas[$i]["HallLatitude"] = $data["HallLatitude"];
                $this->rarryUseHallDatas[$i]["HallLongitude"] = $data["HallLongitude"];
                $this->rarryUseHallDatas[$i]["HallSite"] = $data["HallSite"];
            }
            $this->count = $i;

            $retData = True;
        } else {
                // ªÓπÁ≤ÒæÏ•«°º•ø§¨§ §§æÏπÁ
                $this->rarryUseHallDatas[0]["HallId"] = "";
                $this->rarryUseHallDatas[0]["HallName"] = "";
                $this->rarryUseHallDatas[0]["HallSubName"] = "";
                $this->rarryUseHallDatas[0]["HallCort"] = "";
                $this->rarryUseHallDatas[0]["HallRyakumei"] = "";
                $this->rarryUseHallDatas[0]["HallZip1"] = "";
                $this->rarryUseHallDatas[0]["HallZip2"] = "";
                $this->rarryUseHallDatas[0]["HallPlace"] = "";
                //$this->rarryUseHallDatas[0]["HallCity"] = "";
                $this->rarryUseHallDatas[0]["HallAddress1"] = "";
                $this->rarryUseHallDatas[0]["HallAddress2"] = "";
                $this->rarryUseHallDatas[0]["HallSite"] = "";
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }


    // ≤ÒæÏ•≥°º•»•«°º•ø
    public function hallCortData($hallId) {

        $retData = False;
        $this->hallCortDatas = array();

        # ≤ÒæÏ•≥°º•»•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " `id` AS CortId, " .
                       " `court_name` AS CortName " .
                    " FROM `master_court_informations` MCI " .
                    " WHERE `hall_id` = " . $hallId . " " .
                    " ORDER BY  `view_no`";
//print $sql."<BR>";
        //$rs  = PostSQL(mb_convert_encoding($sql, "UTF-8", "EUC-JP"));
        $rs  = $this->Query($sql);
        if(!$rs){ print "[mastarHallData]≤ÒæÏ•≥°º•»•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // ≤ÒæÏ•≥°º•»•«°º•ø
                $this->hallCortDatas[$i]["CortId"] = $data["CortId"];
                $this->hallCortDatas[$i]["CortName"] = $data["CortName"];
            }
            $this->count = $i;

            $retData = True;
        } else {
                // ≤ÒæÏ•≥°º•»•«°º•ø§¨§ §§æÏπÁ
                $this->hallCortDatas[0]["CortId"] = "";
                $this->hallCortDatas[0]["CortName"] = "";
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    // ≤ÒæÏ•≥°º•»•«°º•ø
    public function allCortData() {

        $retData = False;

        # ≤ÒæÏ•≥°º•»•«°º•ø§ŒºË∆¿
        $sql = "SELECT " .
                       " `id` AS CortId, " .
                       " `hall_id` AS HallId, " .
                       " `court_name` AS CortName " .
                    " FROM `master_court_informations` MCI " .
                    //" WHERE `hall_id` = " . $hallId . " " .
                    " ORDER BY  `hall_id`, `view_no`";
//print $sql."<BR>";
        //$rs  = PostSQL(mb_convert_encoding($sql, "UTF-8", "EUC-JP"));
        $rs  = $this->Query($sql);
        if(!$rs){ print "[mastarHallData]¡¥≤ÒæÏ•≥°º•»•«°º•ø§ŒºË∆¿•®•È°º§«§π°£<br />".$sql."<br />"; return false; }

        // •«°º•øøÙ§ÚºË∆¿
        $num        = $this->GetRowCount($rs);

        if($num > 0){

            for ($i = 0; $i < $num; $i++) {

                $data   = $this->FetchRow($rs);       // £±π‘£«£Â£Ù

                // ≤ÒæÏ•≥°º•»•«°º•ø
                $this->allCortDatas[$i]["CortId"] = $data["CortId"];
                $this->allCortDatas[$i]["HallId"] = $data["HallId"];
                $this->allCortDatas[$i]["CortName"] = $data["CortName"];
            }

            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

        return $retData;
    }

    /**
     * ≤ÒæÏ§Œø∑µ¨≈–œø
     * @param $insertDatas ≈–œø•«°º•ø
     * @return boolean true:¿Æ∏˘ false:≈–œøº∫«‘
     */
    public function insertHallData($insertDatas) {

        $ret = false;

        $insSql = "INSERT INTO " . dbTableName::M_HALL_INFO . " (
                   h_name
                  ,h_kana
                  ,sub_name
                  ,ryakumei
                  ,zip1
                  ,zip2
                  ,place
                  ,city
                  ,address1
                  ,address2
                  ,tel
                  ,tel2
                  ,tel3
                  ,site
                  ,latitude
                  ,longitude
                  ) VALUES (
                   '".$insertDatas['h_name']."'
                  ,'".$insertDatas['h_kana']."'
                  ,'".$insertDatas['sub_name']."'
                  ,'".$insertDatas['ryakumei']."'
                  ,'".$insertDatas['zip1']."'
                  ,'".$insertDatas['zip2']."'
                  ,'".$insertDatas['place']."'
                  ,'".$insertDatas['city']."'
                  ,'".$insertDatas['address1']."'
                  ,'".$insertDatas['address2']."'
                  ,'".$insertDatas['tel']."'
                  ,'".$insertDatas['tel2']."'
                  ,'".$insertDatas['tel3']."'
                  ,'".$insertDatas['site']."'
                  ,'".$insertDatas['latitude']."'
                  ,'".$insertDatas['longitude']."'
                  )";
    $rs = $this->Query($insSql);
//print $insSql."<br />";
        if ($rs) {
            $ret = true;
        }
        return $ret;
    }

    /**
     * ≤ÒæÏ•≥°º•»§Œø∑µ¨≈–œø
     * @param $hallId ≈–œø≤ÒæÏ
     * @param $insertDatas ≈–œø•«°º•ø
     * @return boolean true:¿Æ∏˘ false:≈–œøº∫«‘
     */
    public function insertHallCortData($hallId, $insertDatas) {

        $ret = false;

        $insDataNum = count($insertDatas);

        // ∞Ï≥Á≈–œøÕ—SQL¿∏¿Æ
        if ($insDataNum > 0) {
            for ($i = 0; $i<$insDataNum; $i++) {
                $sqlValue .= "(
                               ".$hallId."
                               ,'".$insertDatas[$i]['name']."'
                               ,".($i + 1)."
                               ),";
            }
        } else {
            return false;
        }
        // ∫«∏Â§Œ•´•Û•ﬁ§ÚΩ¸µÓ
        $sqlValue = substr($sqlValue, 0, -1);

        $insSql = "INSERT INTO " . dbTableName::M_HALL_CORT . " (
                   hall_id
                  ,court_name
                  ,view_no
                  ) VALUES
                  ".$sqlValue."
                  ";
        $rs = $this->Query($insSql);
//print $insSql."<br />";

        if ($rs) {
            $ret = true;
        }
        return $ret;
    }

    /**
     * ≤ÒæÏ§Œππø∑
     * @param $hallId ππø∑≤ÒæÏ
     * @param $updateDatas ≈–œø•«°º•ø
     * @return boolean true:¿Æ∏˘ false:≈–œøº∫«‘
     */
    public function updateHallData($hallId, $updateDatas) {

        $ret = false;

        $upSql = "UPDATE " . dbTableName::M_HALL_INFO . " SET
                   h_name = '".$updateDatas['h_name']."'
                  ,h_kana = '".$updateDatas['h_kana']."'
                  ,sub_name = '".$updateDatas['sub_name']."'
                  ,ryakumei = '".$updateDatas['ryakumei']."'
                  ,zip1 = '".$updateDatas['zip1']."'
                  ,zip2 = '".$updateDatas['zip2']."'
                  ,place = '".$updateDatas['place']."'
                  ,city = '".$updateDatas['city']."'
                  ,address1 = '".$updateDatas['address1']."'
                  ,address2 = '".$updateDatas['address2']."'
                  ,tel = '".$updateDatas['tel']."'
                  ,tel2 = '".$updateDatas['tel2']."'
                  ,tel3 = '".$updateDatas['tel3']."'
                  ,site = '".$updateDatas['site']."'
                  ,latitude = '".$updateDatas['latitude']."'
                  ,longitude = '".$updateDatas['longitude']."'
                  WHERE id = " . $hallId . "
                  ";
        $rs = $this->Query($upSql);
//print $upSql."<br />";
        if ($rs) {
            $ret = true;
        }
        return $ret;
    }


    //------------------------------------------------------
    // ªÓπÁ≤ÒæÏ•«°º•ø
    //------------------------------------------------------
    /*
     * ªÓπÁ≤ÒæÏ•«°º•ø§Ú ÷§π(•≥°º•»¡™¬Ú)
     */
    function getCoatHallData() {
        return $this->coatHallData;
    }

    /*
     * ªÓπÁ≤ÒæÏ•«°º•ø§Ú ÷§π(•≥°º•»¡™¬Ú)
     */
    function getHallData() {
        return $this->hallData;
    }

    /*
     * ¡¥ªÓπÁ≤ÒæÏ•«°º•ø§Ú ÷§π
     */
    function getAllHallDatas() {
        return $this->AllHallDatas;
    }

    /*
     * ¬Á≤Ò Ãª»Õ—≤ÒæÏ•«°º•ø§Ú ÷§π
     */
    function getRarryUseHallDatas() {
        return $this->rarryUseHallDatas;
    }

    /*
     * ≤ÒæÏ•≥°º•»•«°º•ø§Ú ÷§π
     */
    function getHallCortDatas() {
        return $this->hallCortDatas;
    }

    /*
     * ¡¥≤ÒæÏ•≥°º•»•«°º•ø§Ú ÷§π
     */
    function getAllCortDatas() {
        return $this->allCortDatas;
    }

}

?>
