<?php

class sendMailClass {

	/**
	 * コンストラクター.
	 */
	public function __construct($masterAddress, $sendAddress, $tempFolder) {
		$this->masterMail = $masterAddress;
		$this->sendMail = $sendAddress;
		$this->tempFolder = $tempFolder;
	}

	/**
	 * 概要 : 同日・同チーム試合数チェック（スケジュール登録）
	 *
	 * 説明 : 大会内で対戦しているかのチェック
	 *
	 * @param $sendEmail	送信先メールアドレス
	 * @param $sendEmail	送信先メールアドレス
	 * @param $sendEmail	送信先メールアドレス
	 * @param $teamId	 チームID
	 * @param $mailTemplate	  メールのテンプレートファイル
	*/
	public function autoSendMail($sendEmailArray, $paramArray, $mailTemplateFile) {

		$buf = false;

		if(file_exists($this->tempFolder.'/'.$mailTemplateFile)){
			$mail_temp = file($this->tempFolder.'/'.$mailTemplateFile);
			if ($mail_temp == false) {
				$this->mailErrorValue = '<br /><span style=\"font-weight:bold;color:red;\">
										システムがエラーを起こしました。メールのテンプレートファイルの読込みに失敗しました。<br />
										管理者までご連絡ください。</span><br />
										<a href="mailto:'.$this->masterMail.'">'.$this->masterMail.'</a>';
				$mailErrorNums++;
			} else {
				(string)$mail_body = '';
				foreach ($mail_temp as $mail_temp_col) {
					$mail_temp_col = mb_convert_encoding($mail_temp_col, 'utf-8', 'auto');

					foreach ($paramArray as $key => $val) {
						// -- 文字列の置換
						$mail_temp_col = mb_eregi_replace('__' . strtoupper($key) . '__', $val, $mail_temp_col);
						if ($key == 'mailSubject') {
							$mailSubject = $val;
						}
					}
					$mail_body .= $mail_temp_col;
				}
			}
		} else {
			$this->mailErrorValue = '<br /><span style=\"font-weight:bold;color:red;\">
									システムがエラーを起こしました。メールのテンプレートファイルが見つかりません。<br />
									管理者までご連絡ください。</span><br />
									<a href="mailto:'.$this->masterMail.'">'.$this->masterMail.'</a>';
			$mailErrorNums++;
		}

		if ($mailErrorNums == 0) {

			(string)$sendTo = "";
			(string)$sendCc = "";
			(string)$sendBcc = "";

			// メールの送信先
			foreach ($sendEmailArray as $key => $val) {
				if ($key == 'to') {
					$sendTo = implode(",", $val);
				} else if ($key == 'cc') {
					$sendCc = implode(",", $val);
				} else if ($key == 'bcc') {
					$sendBcc = implode(",", $val);
				}
			}
//$sendTo = 'imai.0221@gmail.com';
			//メールエンコーディング
			mb_language("ja");
			mb_internal_encoding("ISO-2022-JP");

			//件名エンコード
			$mailSubject = mb_convert_encoding($mailSubject, "ISO-2022-JP","utf-8");
			$mailSubject = mb_encode_mimeheader($mailSubject,"ISO-2022-JP");

			//本文エンコード
//			$mail_body = base64_encode($mail_body);
			$mail_body = mb_convert_encoding($mail_body,"ISO-2022-JP","utf-8");

			// メールヘッダ
			$mail_header  = "Content-Type: text/plain;charset=ISO-2022-JP\n";
			$mail_header .= "Content-Transfer-Encoding: 7bit\n";
			$mail_header .= "MIME-Version: 1.0\n";
			$mail_header .= "From: ".$this->sendMail."\nReply-To: ".$this->sendMail."\n";
			if ($sendCc != '') {
				$mail_header .= "Cc: ".$sendCc."\n";
			}
			if ($sendBcc != '') {
				$mail_header .= "Bcc: ".$sendBcc."\n";
			}
			$mail_header .= "Errors-To: ".$sendTo."\n";
			$mail_header .= "X-Mailer: Liga-Tokai\n";

			// メール送信処理
			if (mail($sendTo, $mailSubject, $mail_body, $mail_header)) {
//print $sendEmail." = sendEmails<br />";
//print $sendTo." = Email<br />";
//print $mail_body." = BODY<br />";
//print $mail_header." = HEADER<br />";
				$buf = true;
			} else {
				$this->mailErrorValue = '<br /><span style=\"font-weight:bold;color:red;\">メール送信が失敗しました。<br />
									管理者までご連絡ください。</span><br />
									<a href="mailto:'.$this->masterMail.'">'.$this->masterMail.'</a>';
			}
		}

		return $buf;
	}


	//------------------------------------------------------
	// オブジェクト
	//------------------------------------------------------
	/*
	 * メール送信エラー内容を返す
	 */
	function getMailErrorValue() {
		return $this->mailErrorValue;
	}
}

?>
