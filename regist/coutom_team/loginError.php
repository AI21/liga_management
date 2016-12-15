<?php

session_start();

$_SESSION['resistMember'] = array();
// セッション破棄
session_destroy();

require_once dirname(dirname(dirname(dirname(__FILE__))))."/php/smarty/libs/Smarty.class.php";
// オブジェクトを生成
$smarty = new Smarty;

// テンプレート場所の指定
$smarty->config_dir   = dirname(dirname(dirname(dirname(__FILE__)))).'/smarty_templates/pc/regist/configs/';
$smarty->template_dir = dirname(dirname(dirname(dirname(__FILE__)))).'/smarty_templates/pc/regist/templates/';
$smarty->compile_dir  = dirname(dirname(dirname(dirname(__FILE__)))).'/smarty_templates/pc/regist/templates_c/';

// スクリプト名の取得
$script_name =  basename($_SERVER['SCRIPT_NAME'],".php");

// 変数初期値
(string)$fileName = $script_name;

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>