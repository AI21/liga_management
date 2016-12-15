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
class unspecificationDataClass extends connectionDBClass {

    /**
     * •≥•Û•π•»•È•Ø•ø°º.
     */
    public function __construct() {
    }

    /**
     * ≥µÕ◊ : ªÿƒÍDB•∆°º•÷•Î§Œæ Û§Ú¡¥§∆ ÷§π
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
    private function __call($methodName, $datas) {

        $retData = False;

        if (!isset($methodName)) {
          return $retData;
        }

        # ∑»¬”•«°º•ø§ŒºË∆¿
        $sql = "SELECT * FROM ".$methodName." " ;
//print $sql;
        $rs  = $this->Query($sql);
        if(!$rs){ $this->DbErrorValue = $this->GetLastError(); return false; }

        // •«°º•ø§¨§¢§√§ø§È≈–œøæı¬÷§ŒºË∆¿
        if($this->GetRowCount($rs) > 0){

            //for ($i=0; $i<$this->GetRowCount($rs); $i++) {
                $this->dataArray   = $this->FetchDynadset($rs);
            //}
            $retData = True;
        }

        // •·•‚•Í≤Ú ¸
        $this->FreeQuery($rs);

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