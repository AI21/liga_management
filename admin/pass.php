<?php
//require_once "./common.inc";

//if (isset($_GET["id"]) AND isset($_GET["loginid"]) AND isset($_GET["pw"])) {
//print nl2br(print_r($_SERVER,true));
/*
    // トランザクション開始
    $connectDbClass->Query("BEGIN");
    # チームデータの取得
    $sql = "SELECT
                    `login_id`
                 FROM `lt_team_info`
                 WHERE `t_id` = ".$_GET["id"];
print $sql."<BR>";
    $rs  = $connectDbClass->Query($sql);
    if(!$rs){ print "エラーです。"; return false; }

    // データ数を取得
    $num        = $connectDbClass->GetRowCount($rs);

    if($num > 0){

        $data   = $connectDbClass->FetchRow($rs);
*/
        $loginId = $_GET["pw"];
        $changePassword = hash('ripemd160', $loginId);
print $changePassword;
/*
        # 会員フラグを更新
        $sql = "UPDATE `lt_team_info` SET " .
                     " `login_id` = '" . $_GET["loginid"] . "', " .
                     " `password` = '" . $changePassword . "' " .
                     " WHERE `t_id` = " . $_GET["id"] . "";
print $sql."<BR>";
        // データ登録
        $rs  = $connectDbClass->Query($sql);

        if ( !$rs ) {
print "ROLLBACK<BR>";
            // ロールバック処理
            $connectDbClass->Query( "ROLLBACK" );
        } else {
print "COMIT<BR>";
            // コミット処理
            //$this->Query( "ROLLBACK" );
            $connectDbClass->Query( "COMIT" );
        }
//    }
*/
    // メモリ解放
    //$connectDbClass->FreeQuery($rs);
//}
 ?>