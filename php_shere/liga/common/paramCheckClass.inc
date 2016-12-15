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
class paramCheckClass /* extends errorMessage */ {

    /**
     * コンストラクター.
     */
    public function __construct() {
        //$errorMessage = new errorMessage;   # データベース接続オブジェクト
    }

    /**
     * 概要 :
     *
     * 説明 :
     *
     * @param object    $errorMessage   エラーメッセージクラスオブジェクト
     * @param string    $values   説明
     * @param integer   $numMin   検索カラム
     * @param integer   $numMax      検索データ
     *
     * @return boolean  True：エラー無し　False：エラーあり
     *
     * @see ErrorMessageValue  エラーメッセージ内容
     *
     * @access public
     */
    // 空かチェック
    public function isNullCheck($errorMessage, $values, $numMin, $numMax) {
        $values = convertUtf8($values);
        mb_regex_encoding("utf-8");
        if ($values == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_VALUE");
            return False;
        } else if ((strlen($values) < $numMin) AND $numMin > 0) {
            $this->ErrorMessageValue = $numMin.$errorMessage->getErrorMessage("LENGTH_LOW");
            return False;
        } else if (strlen($values) > $numMax) {
            $this->ErrorMessageValue = $numMax.$errorMessage->getErrorMessage("LENGTH_OVER");
            return False;
        } else {
            return True;
        }
    }
    // 半角数字かチェック
    public function isNumberCheck($errorMessage, $values, $numMin, $numMax) {
        $values = convertUtf8($values);
        mb_regex_encoding("utf-8");
        if ($values == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_VALUE");
            return False;
        } else if (!preg_match("/^[0-9]+$/",$values)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NUMBER_ONLY");
            return False;
        } else if ((strlen($values) < $numMin) AND $numMin > 0) {
            $this->ErrorMessageValue = $numMin.$errorMessage->getErrorMessage("LENGTH_LOW");
            return False;
        } else if (strlen($values) > $numMax) {
            $this->ErrorMessageValue = $numMax.$errorMessage->getErrorMessage("LENGTH_OVER");
            return False;
        } else {
            return True;
        }
    }
    // 半角英数が含まれていないかチェック
    public function there_alnum($errorMessage, $values, $numMin, $numMax) {
        $values = convertUtf8($values);
        mb_regex_encoding("utf-8");
        if ($values == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_VALUE");
            return False;
        } else if (!preg_match("/^[0-9A-Za-z-_]+$/", $values)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("EISUU_ONLY");
            return False;
        } else if ((strlen($values) < $numMin) AND $numMin > 0) {
            $this->ErrorMessageValue = $numMin.$errorMessage->getErrorMessage("LENGTH_LOW");
            return False;
        } else if (strlen($values) > $numMax) {
            $this->ErrorMessageValue = $numMax.$errorMessage->getErrorMessage("LENGTH_OVER");
            return False;
        } else {
            return True;
        }
    }

    // 半角英数が含まれていないかチェック(日本語)
    public function there_zenkaku($errorMessage, $values, $numMin, $numMax, $enc="utf-8") {
        $checkStr = convertUtf8($values);
        mb_regex_encoding("utf-8");
        if ($values == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_VALUE");
            return False;
        } else if (preg_match("/^[0-9A-Za-z]+$/", $checkStr)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("ZENKAKU_ONLY");
            return False;
        } else if ((mb_strlen($values, $enc) < $numMin) AND $numMin > 0) {
            $this->ErrorMessageValue = $numMin.$errorMessage->getErrorMessage("LENGTH_LOW");
            return False;
        } else if (mb_strlen($values, $enc) > $numMax) {
            $this->ErrorMessageValue = $numMax.$errorMessage->getErrorMessage("LENGTH_OVER");
            return False;
        } else {
            return True;
        }
    }

    // カタカナのみかどうかのチェック(前半角共通)
    public function there_katakana($errorMessage, $values, $numMin, $numMax, $enc="utf-8") {
        $checkStr = mbZen($values);
        mb_regex_encoding($enc);
        $pat = "^[アァイィウゥエェオォカガキギクグケゲコゴサザシジスズセゼソゾタダチヂツッヅテデトドナニヌネノハバパヒビピフブプヘベペホボポマミムメモヤャユュヨョラリルレロワヲンー-]+$";
        if ($values == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_VALUE");
            return False;
        } else if (!mb_ereg_match($pat, $checkStr)) {
        //} else if (!mb_ereg("^[ア-ン]+$", $values)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("KATAKANA_ONLY");
            return False;
        } else if ((mb_strlen($values, $enc) < $numMin) AND $numMin > 0) {
            $this->ErrorMessageValue = $numMin.$errorMessage->getErrorMessage("LENGTH_LOW");
            return False;
        } else if (mb_strlen($values, $enc) > ($numMax)) {
            $this->ErrorMessageValue = $numMax.$errorMessage->getErrorMessage("LENGTH_OVER");
            return False;
        } else {
            return True;
        }
    }

    // カタカナのみかどうかのチェック(前半角共通)
    public function admin_there_katakana($errorMessage, $values, $numMin, $numMax, $enc="utf-8") {
        // mb_regex_encoding($enc);
        mb_regex_encoding("utf-8");
        if ($values == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_VALUE");
            return False;
        } else if (!mb_ereg("^[ア-ンー-]+$", $values)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("KATAKANA_ONLY");
            return False;
        } else if ((mb_strlen($values, $enc) < $numMin) AND $numMin > 0) {
            $this->ErrorMessageValue = $numMin.$errorMessage->getErrorMessage("LENGTH_LOW");
            return False;
        } else if (mb_strlen($values, $enc) > ($numMax)) {
            $this->ErrorMessageValue = $numMax.$errorMessage->getErrorMessage("LENGTH_OVER");
            return False;
        } else {
            return True;
        }
    }

    // 名前形式チェック(全角＆半角英字のみ)
    public function isNameCheck($errorMessage, $values, $numMin, $numMax, $enc="utf-8") {
        // mb_regex_encoding($enc);
        mb_regex_encoding("utf-8");
        if ($values == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_VALUE");
            return False;
        // } else if (!mb_ereg("^[亜-腕弌-熙々ーあ-んア-ンA-Za-z]+$", $values)) {
        } else if (!preg_match("/^[ぁ-んァ-ヶー一-龠々A-Za-z]+$/u", $values)) {
        //} else if (!preg_match("/^(
		//	\xe4[\xb8-\xbf][\x80-\xbf]|[\xe5-\xe9][\x80-\xbf][\x80-\xbf]|\xef\xa4\xa9|\xef\xa7\x9c|\xef\xa8[\x8e-\xad]		// 漢字
		//	|\x82[\x9f-\xf1]|\x81[\x4a\x54\x55]|\xa4[\xa1-\xf3]|\xa1[\xb5\xb6\xab]|\xe3\x81[\x81-\xbf]|\xe3\x82[\x80-\x9e]	// ひらがな
		//	|\xe3\x82[\xa1-\xbf]|\xe3\x83[\x80-\xbe]|\xa5[\xa1-\xf6]|\xa1[\xb3\xb4\xbc]|\x83[\x40-\x96]|\x81[\x52\x53\x5b]	// カタカナ
		//	|[ーA-Za-z])+$/u", $values)) {
        // } else if (!preg_match("/^[ぁ-んァ-ヶー一-龠A-Za-z]+/u", $values)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_NAME_TYPE");
            return False;
        } else if ((mb_strlen($values, $enc) < $numMin) AND $numMin > 0) {
            $this->ErrorMessageValue = $numMin.$errorMessage->getErrorMessage("LENGTH_LOW");
            return False;
        } else if (mb_strlen($values, $enc) > ($numMax)) {
            $this->ErrorMessageValue = $numMax.$errorMessage->getErrorMessage("LENGTH_OVER");
            return False;
        } else {
            return True;
        }
    }

    // アドレス形式チェック
    public function isMailCheck($errorMessage, $email) {
        mb_regex_encoding("utf-8");
        if ($email == "") {
//            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_VALUE");
//            return False;
        } else if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("EMAIL_ERROR");
            return False;
        } else {
            return True;
        }
    }

    // 携帯番号形式チェック
    public function isMobileCheck($errorMessage, $mtel) {
        //$mtel = convertEUC($mtel);
        //mb_regex_encoding("utf-8");
        if ((mb_strlen($mtel) < 11)) {
            $this->ErrorMessageValue = "11".$errorMessage->getErrorMessage("LENGTH_LOW");
            return False;
        } else if (mb_strlen($mtel) > 13) {
            $this->ErrorMessageValue = "13".$errorMessage->getErrorMessage("LENGTH_OVER");
            return False;
        }
        if ((substr($mtel, 0 , 3) == '080') OR (substr($mtel, 0 , 3) == '090')) {
            return True;
        } else {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("MOBILE_ERROR");
            return False;
        }
    }

    // 日付形式チェック
    public function isDateCheck($errorMessage, $month, $day, $year) {
        //$mtel = convertUtf8($mtel);
        //mb_regex_encoding("UTF-8");
        if ($month == "" OR $day == "" OR $year == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NON_DATE");
            return False;
        } else if (!is_numeric($month) OR !is_numeric($day) OR !is_numeric($year)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("DATE_ERROR");
            return False;
        } else if (checkdate($month, $day, $year)) {
            return True;
        } else {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("DATE_ERROR");
            return False;
        }
    }

    // 時間形式チェック
    public function isTimeCheck($errorMessage, $hour, $minute, $second) {
        //$mtel = convertUtf8($mtel);
        //mb_regex_encoding("UTF-8");
        if ($hour == "" OR $minute == "" OR $second == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NON_TIME");
            return False;
        } else if (!is_numeric($hour) OR !is_numeric($minute) OR !is_numeric($second)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("TIME_ERROR");
            return False;
        } else if ($hour < 0 OR $hour > 23) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("TIME_ERROR");
            return False;
        } else if ($minute < 0 OR $minute > 59) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("TIME_ERROR");
            return False;
        } else if ($second < 0 OR $second > 59) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("TIME_ERROR");
            return False;
        } else {
            return True;
        }
    }

    // 緯度経度チェック
    public function isLatLonCheck($errorMessage, $values) {
        $values = convertEUC($values);
        mb_regex_encoding("utf-8");

        list($c, $s) = explode(".", $values);

        if ($values == "") {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("NO_VALUE");
            return False;
//        } else if (!is_float($values)) {
//            $this->ErrorMessageValue = $errorMessage->getErrorMessage("FLOAT_ERROR");
//            return False;
//        } else if (!is_numeric($values) OR !is_float($values)) {
//            $this->ErrorMessageValue = $errorMessage->getErrorMessage("FLOAT_ERROR");
//            return False;
        } else if (!is_numeric($c) OR !is_numeric($s)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("FLOAT_ERROR");
            return False;
        } else if ((strlen($c) < 2) AND (strlen($c) > 3)) {
            $this->ErrorMessageValue = $numMin.$errorMessage->getErrorMessage("NOT_LATLON");
            return False;
        } else if (strlen($s) > 15) {
            $this->ErrorMessageValue = $numMin.$errorMessage->getErrorMessage("NOT_LATLON");
            return False;
        } else {
            return True;
        }
    }

    // ホームページアドレスチェック
    function isUrlCheck($errorMessage, $values) {
        if (!preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $values)) {
            $this->ErrorMessageValue = $errorMessage->getErrorMessage("URL_ERROR");
            return False;
        } else {
            return True;
        }
    }


    //------------------------------------------------------
    // エラーデータ
    //------------------------------------------------------
    /*
     * エラー内容を返す
     */
    function getErrorMessageValue() {
        return $this->ErrorMessageValue;
    }

}

?>