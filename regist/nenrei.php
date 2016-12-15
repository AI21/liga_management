<?php

session_start();

require_once "./common.inc";

// テンプレート場所の指定
$smarty->config_dir   = SMARTY_CONFIG_DIR;
$smarty->template_dir = SMARTY_TEMPLATE_DIR;
$smarty->compile_dir  = SMARTY_COMPLETE_DIR;

// スクリプト名の取得
$script_name =  basename($_SERVER['SCRIPT_NAME'],".php");

// 変数初期値
(string)$fileName = $script_name;

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>