<?php

// 半角文字処理
function mbHan($str) {

    if (is_array($str)){
        foreach($str as $key => $val){
            if (is_array($val)){
                //$buf[$key] = mb_convert_kana_array($str[$key], "ask", "SJIS-WIN");
                $buf[$key] = mb_convert_kana_array($str[$key], "ask");
            } else {
                //$buf[$key] = mb_convert_kana($val, "ask", "SJIS-WIN");
                $buf[$key] = mb_convert_kana($val, "ask");
            }
        }
    } else {
        //$buf = mb_convert_kana($str, "ask", "SJIS-WIN");
        $buf = mb_convert_kana($str, "ask");
    }

    return $buf;
}

// 全角文字処理
function mbZen($str) {

    if (is_array($str)){
        foreach($str as $key => $val){
            if (is_array($val)){
                //$buf[$key] = mb_convert_kana_array($str[$key], "ask", "SJIS-WIN");
                $buf[$key] = mb_convert_kana_array($str[$key], "KV");
            } else {
                //$buf[$key] = mb_convert_kana($val, "ask", "SJIS-WIN");
                $buf[$key] = mb_convert_kana($val, "KV");
            }
        }
    } else {
        //$buf = mb_convert_kana($str, "ask", "SJIS-WIN");
        $buf = mb_convert_kana($str, "KV");
    }

    return $buf;
}

// 特殊文字のエンコード処理
function encode($str) {

    if (is_array($str)){
        foreach($str as $key => $val){
            if (is_array($val)){
                $array[$key] = htmlspecialchars($str[$key], ENT_QUOTES);
            } else {
                $array[$key] = htmlspecialchars($val, ENT_QUOTES);
            }
        }
    } else {
        $array = htmlspecialchars($str, ENT_QUOTES);
    }
    return $array;
}

// 特殊文字のデコード処理
function decode($str) {

    if (is_array($str)){
        foreach($str as $key => $val){
            if (is_array($val)){
                $array[$key] = htmlspecialchars_decode($str[$key], ENT_NOQUOTES);
            } else {
                $array[$key] = htmlspecialchars_decode($val, ENT_NOQUOTES);
            }
        }
    } else {
        $array = htmlspecialchars_decode($str, ENT_NOQUOTES);
    }

    return $array;
}

// Shift_JISへの文字エンコーディング処理
function convertSjis($str) {

    if (is_array($str)){
        foreach($str as $key => $val){
            if (is_array($val)){
                $array[$key] = mb_convert_encoding($str[$key],"SJIS","UTF-8, eucjp-win, sjis-win");
            } else {
                $array[$key] = mb_convert_encoding($val,"SJIS","UTF-8, eucjp-win, sjis-win");
            }
        }
    } else {
        $array = mb_convert_encoding($str,"SJIS","UTF-8, eucjp-win, sjis-win");
    }

    return $array;
}

// UTF-8への文字エンコーディング処理
function convertUtf8($str) {

    if (is_array($str)){
        foreach($str as $key => $val){
            if (is_array($val)){
                $array[$key] = mb_convert_encoding($str[$key],"UTF-8","SJIS");
            } else {
                $array[$key] = mb_convert_encoding($val,"UTF-8","SJIS");
            }
        }
    } else {
        $array = mb_convert_encoding($str,"UTF-8","SJIS");
    }

    return $array;
}

// EUC-JPへの文字エンコーディング処理
function convertEuc($str) {

    if (is_array($str)){
        foreach($str as $key => $val){
            if (is_array($val)){
                $array[$key] = mb_convert_encoding($str[$key],"EUC-JP","SJIS");
            } else {
                $array[$key] = mb_convert_encoding($val,"EUC-JP","SJIS");
            }
        }
    } else {
        $array = mb_convert_encoding($str,"EUC-JP","SJIS");
    }

    return $array;
}

// 絵文字除去処理
function convertEmoji($str){

    $pattern = '/[\\x1B][\\x24][G|E|F|O|P|Q][\\x21-\\x7E]+([\\x0F]|$)/';
    preg_match_all($pattern, $in_string, $arr);
    $search = $arr[0];	// $arr[0]に対象絵文字が格納される

    // 絵文字除去
    $buf = str_replace($search, array(), $in_string);

    return $buf;
}

// UTF-8への文字エンコーディング処理
function adminConvertUtf8($str) {

    if (is_array($str)){
        foreach($str as $key => $val){
            if (is_array($val)){
                $array[$key] = mb_convert_encoding($str[$key],"UTF-8","auto");
            } else {
                $array[$key] = mb_convert_encoding($val,"UTF-8","auto");
            }
        }
    } else {
        $array = mb_convert_encoding($str,"UTF-8","auto");
    }
    return $array;
}

// クオート文字の除去
function strStripslashes($str) {
    if (is_array($str)) {
        $buf = array_map('strStripslashes', $str);
    } else {
        $buf = stripslashes($str);
    }
    return $buf;
}

?>