<?php

//    require_once dirname(dirname(dirname(__FILE__)))."/shere/database/dbTableName.inc";
require_once "dbTableName.inc";

/**
 * クラスの説明
 *
 * @package  データベース接続設定ファイル
 * @author   今井 厚文 <a-imai@p-sol.co.jp>
 * @since    PHP 5.1
 * @success  2008/04/18
 */

class dbSetting extends dbTableName {

    //////////////////
    // 接続設定     //
    //////////////////
/*
    // LOCAL-HOST用
    protected $server     = 'localhost';
    protected $user       = 'root';
    protected $password   = 'vertrigo';
    protected $database   = 'liga';
    // 2010本番用
    protected $server     = '121.119.177.144';
    protected $user       = 'liga_master';
    protected $password   = 'samurai8';
    protected $database   = 'liga_tokai_master';
*/
    // 2013本番用
    protected $server     = 'mysql3.firebird.netowl.jp';
    protected $user       = 'worksai_liga2013';
    protected $password   = 'samurai21';
    protected $database   = 'worksai_ligatokai';
}

?>
