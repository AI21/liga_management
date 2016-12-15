<?php

//require_once $_SERVER['DOCUMENT_ROOT']."/php/pc/shere/database/dbSetting.inc";
require_once "dbSetting.inc";

// =============================================================================
// $Id: $
/**
 * @file    db.cgi
 * @brief   システムライブラリ データベース関数群
 * @author
 * @date    Last updated:
 *
 *          Copyright(c) 2006
 */
// =============================================================================
 /**
 * データベース接続クラス
 */
// =============================================================================
class connectionDBClass extends dbSetting {

    private $link_id;
    private $pconnect;
    private $result_id;
    private $last_error_no;
    private $last_error_msg;

    function __construct($presistency = true){

        $this->pconnect = $presistency;

        $server   = $this->server;
        $user     = $this->user;
        $password = $this->password;
        $database = $this->database;
//print $server." = SERVER<br />";
//print $user." = USER<br />";
//print $password." = PW<br />";
//print $database." = DB<br />";
        if($presistency){
            $this->link_id = mysql_pconnect($server,$user,$password);
        }
        else{
            $this->link_id = mysql_connect($server,$user,$password);
        }
        if($this->link_id){
            if($this->database != ""){
                $db = mysql_select_db($database,$this->link_id);
                if(!$db){
                    mysql_close($this->link_id);
                    return false;
                }
                return $this->link_id;
            }
        }
        return false;
    }
    function __destruct(){
        if($this->link_id){
            if(!$this->pconnect){
                mysql_close($this->link_id);
            }
        }
    }
    // クエリを実行する
    function Query($sql){
        if($sql != ""){
            //$this->result_id = mysql_query($sql, $this->link_id);
            $this->result_id = mysql_query($sql);
            return $this->result_id;
        }
    }
    // クエリを開放する
    function FreeQuery($query_id = 0){

        $query_id = $this->GetQueryID($query_id);

        if ($query_id){
            mysql_free_result($query_id);
        } else {
            return false;
        }
        return true;
    }
    //
    function FetchRow($query_id = 0){

        $query_id = $this->GetQueryID($query_id);

        if($query_id){
            $row = mysql_fetch_array($query_id);
            return $row;
        } else {
            return false;
        }
    }
    function FetchDynadset($query_id = 0){

        $query_id = $this->GetQueryID($query_id);

        if($query_id){
            while($row = mysql_fetch_array($query_id)){
                $dynaset[] = $row;
            }
            return $dynaset;
        } else {
            return false;
        }
    }
    function ExecuteSQL($sql){
        if($sql != ""){
            $this->result_id = mysql_query($sql, $this->link_id);
            return $this->result_id;
        }
    }
    // 実行クエリのデータ数を取得する
    function GetRowCount($query_id = 0)
    {
        $query_id = $this->GetQueryID($query_id);

        if($query_id){
            $result = mysql_num_rows($query_id);
            return $result;
        } else {
            return false;
        }
    }
    function GetExecRowCount()
    {
        if($this->link_id){
            $result = mysql_affected_rows($this->link_id);
            return $result;
        } else {
            return false;
        }
    }
    protected function GetQueryID($query_id){
        if(!$query_id){
            $id = $this->result_id;
        } else {
            $id = $query_id;
        }
        return $id;
    }
    function Move($query_id = 0,$rownum){

        $query_id = $this->GetQueryID($query_id);

        if($query_id){
            $result = mysql_data_seek($query_id, $rownum);
            return $result;
        } else {
            return false;
        }
    }
    function GetCurrentID(){
        if($this->link_id){
            $next_id = mysql_insert_id($this->link_id);
            return $next_id;
        } else{
            return false;
        }
    }
    function GetLastError()
    {
        #$error["code"] = mysql_errno($this->link_id);
        #$error["message"] = mysql_error($this->link_id);
        $error["code"] = mysql_errno();
        $error["message"] = mysql_error();
        return $error;
    }


}
?>