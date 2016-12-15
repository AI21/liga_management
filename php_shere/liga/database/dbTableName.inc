<?php

/**
 * クラスの説明
 *
 * @package  データベーステーブル設定ファイル
 * @author   今井 厚文 <a-imai@p-sol.co.jp>
 * @since    PHP 5.1
 * @success  2008/04/18
 */

class dbTableName {

    //////////////////////////////////
    // データベーステーブル設定     //
    //////////////////////////////////
    ## 個人スコア
    const LT_INDIVIDUAL_SCORE     = '`individual_scores`';
    ## メンバーマスター
    const LT_MEMBER_INFO          = '`member_informations`';
    ## 大会基本マスター
    const LT_RARRY_INFO           = '`rarry_informations`';
    ## 大会別登録チーム
    const LT_REGIST_TEAM          = '`regist_teams`';
    ## チーム登録メンバー
    const LT_REGIST_TEAM_MEMBER   = '`regist_team_members`';
    ## スケジュール
    const LT_SCHEJULE             = '`rarry_schejules`';
    ## 消化試合データ
    const LT_SCHEJULE_AFTER       = '`rarry_complete_games`';
    ## チームマスター
    const LT_TEAM_INFO            = '`team_informations`';
    ## 大会中ポイントデータ
    const LT_YEAR_POINT           = '`rarry_settings`';
    ## 会場コート
    const M_HALL_CORT             = '`master_court_informations`';
    ## 会場マスター
    const M_HALL_INFO             = '`master_hall_informations`';
    ## ポジションマスター
    const M_POSISION_INFO         = '`master_posision_informations`';
    ## 大会タイプマスター
    //const M_RALLY_TYPE            = '`m_rally_type`';
    ## 大会クラスデータ
    const RARRY_CLASS_INFO        = '`master_class_informations`';
    ## 試合スコア結果
    const RARRY_SCORE             = '`rarry_scores`';
    ## 大会使用会場
    const RARRY_USE_HALL          = '`rarry_use_halls`';
    ## 大会使用ブロック・クラス
    const RARRY_USE_CLASS         = '`rarry_use_classes`';
    ## 都道府県マスター
    const M_DISTRICT_INFO         = '`master_district_informations`';
    ## 携帯キャリアマスター
    const M_MOBILE_CAREERS        = '`master_mobile_careers`';
    ## 携帯ドメインマスター
    const M_MOBILE_DOMAIN         = '`master_mobile_domain`';
    ## 個人登録費支払いテーブル
    const LT_MEMBER_PAYMENT       = '`rarry_membar_payments`';
    ## ゲームレポートテーブル
    const RARRY_SCORE_REPORT       = '`rarry_scores_report`';
}
?>
