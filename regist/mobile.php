<?php

// クラスファイル読み込み
/*
 * @file commons.inc          共通関数
 */
require_once "./common.inc";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="content-language" content="ja" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<title>携帯アクセスエラー</title>
</head>
<body>
<center>
<div><?php echo $rarryDataArray['rarry_name']; ?>&nbsp;<?php echo $rarryDataArray['rarry_sub_name']; ?><br /><?php echo SUB_NAME; ?></div>
<div>&nbsp;</div>
<hr />
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>携帯からのアクセスは出来ません。</div>
<div>パソコンから操作して下さい。</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>&nbsp;</div>
<div>
<hr />
<address style="text-align:center;">Copyright&nbsp;&copy;&nbsp;2000-<?php echo date("Y"); ?>&nbsp;Liga-Tokai.&nbsp;All&nbsp;rights&nbsp;reserved.</address>
<hr />
</div>
</center>
</body>
</html>
