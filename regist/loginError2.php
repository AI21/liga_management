<?php

session_start();

$_SESSION['resistMember'] = array();
// セッション破棄
session_destroy();

require_once dirname(dirname(__FILE__))."/php_core/smarty/libs/Smarty.class.php";
// オブジェクトを生成
$smarty = new Smarty;

// テンプレート場所の指定
$smarty->config_dir   = './smarty_templates/configs/';
$smarty->template_dir = './smarty_templates/templates/';
$smarty->compile_dir  = './smarty_templates/templates_c/';

// スクリプト名の取得
$script_name =  basename($_SERVER['SCRIPT_NAME'],".php");

// 変数初期値
(string)$fileName = $script_name;

// テンプレート指定
$smarty->display($fileName.'.tpl');

?>