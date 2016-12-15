<?


// =============================================================================
// ページ選択
// -----------------------------------------------------------------------------
/**
 * @param[IN]	$view 			1ページあたりの表示数
 * @param[IN]	$page 			閲覧ページ番号
 * @param[IN]	$all_data 		全データ数
 * @param[IN]	$action 		出力フォーム用action値
 * @param[IN]	$buf	 		出力データ
 
 * @retval		成功：	パラメータの値 失敗：空文字
 */
// =============================================================================
function PageSelect($view, $page, $all_data, $action)
{
	# * page           : 1ページに表示する数
	# * view           : 表示ページ数
	# * sql_start_num  : SQL範囲の始まり値
	# * sql_end_num    : SQL範囲の終了値
	
	// 表示件数の初期値(外部からのリンクのとき)
	if (!$page) $page = 1;
	
	// データ範囲指定の下限値と上限値の設定
	$start_num  = $view * ($page - 1) + 1;
	$end_num    = $view * ($page - 1) + $view;
	
	// 全件表示以外のときは必要に応じてリンクタグの作成
	#if ($view != $all_data) {
		
		// 次ページリンク設定
		$next_page     = $page + 1;
		$next          = "[<a href=\"javaScript:pageView(" . $next_page . ")\">次の" . $view . "件</a>]\n";
		#現在表示のページが最終データ終わりのときは次ページリンクの削除
		if ($end_num >= $all_data) $next = "]\n";
		
		// 前ページリンク設定
		$back_page     = $page - 1;
		$back          = "[<a href=\"javaScript:pageView(" . $back_page . ")\">前の" . $view . "件</a>\n";
		#最初のページビューの時は前ページリンクの削除
		if ($start_num <= 1) $back = "";
		
		// 次・前ページ双方あるときは中間に区切り線を入れる
		if ($next != "]\n" && $back) $next = "｜<a href=\"javaScript:pageView(" . $next_page . ")\">次の" . $view . "件</a>]\n";
		
		// データ数が表示件数以下のときは閉じ括弧の削除
		if ($view > $all_data) $next = "";
		
		// 表示件数での合計ページ(小数点以下を切上げ)
		$total_page = ceil($all_data / $view);
		
		// 1ページあたりの表示ページ選択数
		$page_num      = $view;
		
		// 閲覧ページが設定の選択件数ページ以上の時は1ページ目のリンクタグの生成
		if ($page > $page_num) {
			$page_select .= "  <a href=\"javaScript:pageView(1)\">1..</a>\n";
		}
		if (($page - $page_num) > 0 && $page >= $page_num) {
			// 閲覧ページが設定の選択件数xnページに来た時(例：選択件数7件で14ページ目に来た時など)
			if (($page % $page_num) == 0) {
				$rest = $page - ($page_num - 1);
			} else {
				$rest = $page - (($page % $page_num) - 1);
			}
			// 前の設定の選択件数のリンクは「<<」表示にする
			$page_select .= "			<a href=\"javaScript:pageView(" . ($rest - 1) . ")\">&lt;&lt;</a>\n";
		// 設定の選択件数番以内のページは1ページ目に設定
		} else {
			$rest = 1;
		}
		// 番号リンクタグの生成
		for ($i=$rest; $i<=$total_page; $i++) {
			// 設定の選択件数以上のリンクは「>>」表示にして終了
			if ($i >= ($rest + $page_num)) {
				$page_select .= "  <a href=\"javaScript:pageView(" . $i . ")\">&gt;&gt;</a>\n";
				// 最終ページへのリンクタグの生成
				$page_select .= "  <a href=\"javaScript:pageView(" . $total_page . ")\">.." . $total_page . "</a>\n";
				break;
			}
			if ($i == $page) {
				// 番号ページを閲覧時はリンクを外す
				$page_select .= "  " . $i . "\n";
			} else {
				// 通常
				$page_select .= "  <a href=\"javaScript:pageView(" . $i . ")\">" . $i . "</a>\n";
			}
		}
#	} else {
		// 全件表示は設定の最後の物に変更
#		$view = $PAGE_VIEW_COUNT[count($PAGE_VIEW_COUNT) - 1];
#	}
	
	// ページ選択専用「JavaScript・フォーム」データ
	$buf["script"]           = "\n<script language=\"JavaScript\" type=\"text/javascript\">\n<!--\n";
	$buf["script"]          .= "  // ページ選択\n";
	$buf["script"]          .= "  function pageView(cnt){\n";
	$buf["script"]          .= "  var fm        = document.fm;\n";
	$buf["script"]          .= "  fm.page.value = cnt;\n";
	$buf["script"]          .= "  fm.action     = \"".$action."\";\n";
	$buf["script"]          .= "  fm.submit();\n";
	$buf["script"]          .= "  }\n";
	$buf["script"]          .= "//-->\n</script>\n";
	$buf["script"]          .= "<form name=\"fm\" method=\"post\" action=\"\">\n";
	$buf["script"]          .= "  <input type=\"hidden\" name=\"page\" value=\"\" />\n";
	$buf["script"]          .= "</form>\n";
	
	
	$buf["start"]            = $start_num -1;
	$buf["end"]              = $end_num - 1;
	$buf["next"]             = $next;
	$buf["back"]             = $back;
	$buf["page_select"]     .= $page_select;
	
/*
print $view.					" = VIEW<br>";
print $end_num.					" = END<br>";
print $start_num.				" = START<br>";
print $all_data.				" = ALL<br>";
print $page.					" = 閲覧ページ<BR>";
print $rest.					" = 10LINK_START<BR>";
print $back_page.				" = back_page<br>";
print $next.					" = next LINK<br>";
print $back.					" = back LINK<br>";
print $total_page.				" = TOTALページ数<BR>";
*/
	return $buf;
}

?>