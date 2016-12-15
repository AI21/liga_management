<?php

class sqlCodeClass extends connectionDBClass {

    public $dbError;
    //private $error;

    /**
     * コンストラクター.
     */
    public function __construct() {
    }

    /**
     * 概要 : データベース文字コードの指定
     *
     * 説明 : データの取得・更新の文字コードを任意に指定する
     *
     * @param string     $code   登録データ
     *
     * @return bool
     *
     * @access public
     */
    public function dbSetCode($code) {
        // 文字コード設定SQL
        $set_code_sql = " SET NAMES ".$code.";";

        // クエリ発行
        $rs  = $this->Query($set_code_sql);

        if ( !$rs ) {
            //$sqlError = False;
            $this->dbError = $this->GetLastError();
            return $this->dbError;
        }
    }

}

?>
