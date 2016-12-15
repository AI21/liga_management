<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja-JP">
<head>
<title>リーガ東海 管理ページ [<?php echo $pageTitle; ?>]</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<?php
// CSSファイル読み込みタグの生成
if (count($readCssFile) > 0) : ?>
<meta http-equiv="Content-Style-Type" content="text/css" />
<?php foreach ($readCssFile as $cssFiles) : ?>
<link rel="stylesheet" type="text/css" media="all" href="./css/<?php echo $cssFiles; ?>.css" />
<?php endforeach;endif; ?>
<?php
// javascriptファイル読み込みタグの生成
if (count($readJsFile) > 0) : ?>
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<?php foreach ($readJsFile as $jsFiles) : ?>
<script type="text/javascript" src="./js/<?php echo $jsFiles; ?>.js"></script>
<?php endforeach;endif; ?>
<style media="all" type="text/css">
/*<![CDATA[*/
@import "css/all.css";
/*]]>*/
</style>
</head>
