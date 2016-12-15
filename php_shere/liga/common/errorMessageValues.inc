<?php

/**
 * 各種エラー時のメッセージ内容
 *
 * @package  ファイル
 * @author   今井 厚文 <a-imai@p-sol.co.jp>
 * @since    PHP 5.1
 * @success  2008/04/18
 */

class errorMessageValues {

    private $errorValue;

    // パラメータチェック系
    const EISUU_ONLY             = "英数字とハイフン・アンダーバーのみにしてください。";
    const ZENKAKU_ONLY           = "全角文字のみにしてください。";
    const KATAKANA_ONLY          = "カタカナのみにしてください。";
    const NUMBER_ONLY            = "数字のみにしてください。";
    const EMAIL_ERROR            = "形式が違います。";
    const MOBILE_ERROR           = "形式が違います。";
    const LENGTH_LOW             = "文字以上にしてください。";
    const LENGTH_OVER            = "文字以内にしてください。";
    const NO_VALUE               = "未入力です。";
    const PASSWORD_NO_SAME       = "パスワードが不一致です。<br>同じ値を入れてください";
    const FLOAT_ERROR            = "小数点で登録してください。";
    const NOT_LATLON             = "緯度経度の形式が違います。";
    const URL_ERROR              = "URLの形式が違います。";

    // ログイン系
    const LOGIN_ERROR            = "入力されたﾛｸﾞｲﾝIDまたはﾊﾟｽﾜｰﾄﾞが誤っています。";

    const REPRESENT_SAME         = "代表者と副代表者を同じには出来ません。";
    const REPRESENT_TEL_SAME     = "代表者と副代表者の携帯番号を同じには出来ません。";
    const REPRESENT_MAIL_SAME    = "代表者と副代表者のメールアドレスを同じには出来ません。";
    const MOBILEDOMAIN_SAME      = "携帯アドレスは登録できません。";
    const MOBILEDOMAIN_NO_SELECT = "ドメインを選択してください。";
    const DISTRICT_NO_SELECT     = "活動場所を選択してください。";
    const PLACE_NOT_DATA         = "活動場所を記入してください。";
    const TEAMCOLOR_NO_SELECT    = "カラーを選択してください。";

    const PLAYER_REGIST_SAME     = "登録済みの選手です。";
    const NO_NAME_TYPE           = "漢字と全角ひらがな・カタカナと半角英字のみにしてください。";

    // データ取得系
    const NOT_TEAMDATA           = "チーム情報が取得出来ませんでした。";
    const RARRY_REGIST_ERROR     = "選択したチームが大会に登録されていません。";

    // エラーメッセージ未設定の時
    const NOT_MESSAGE            = "エラーメッセージが設定されていません。";

    // エラーメッセージを返す
    public function getErrorMessage($consValue) {

        if (defined("self::".$consValue)) {
            //$this->errorValue = mb_convert_encoding(constant("self::".$consValue),"SJIS","UTF-8");
            $this->errorValue = constant("self::".$consValue);
        } else {
            $this->errorValue = constant("self::NOT_MESSAGE");
        }
        return $this->errorValue;
    }

}

?>
