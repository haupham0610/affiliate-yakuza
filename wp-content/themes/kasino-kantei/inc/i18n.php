<?php
/**
 * KASINO 鑑定 — PHP i18n helper
 * Cookie-based bilingual JA/EN: reads kasino_lang cookie for server-side rendering.
 * JS (i18n.js) handles dynamic toggling without page reload.
 *
 * @package kasino-kantei
 */

defined( 'ABSPATH' ) || exit;

/**
 * Returns 'en' if the kasino_lang cookie is set to 'en', otherwise 'ja'.
 */
function kasino_get_lang(): string {
    if ( isset( $_COOKIE['kasino_lang'] ) && 'en' === $_COOKIE['kasino_lang'] ) {
        return 'en';
    }
    return 'ja';
}

/**
 * Returns the full JA/EN dictionary array.
 */
function kasino_i18n_dict(): array {
    return [
        'ja' => [
            'brand'           => 'KASINO 鑑定',
            'brand_jp'        => '日本カジノ鑑定',
            'lang_toggle'     => 'JA / EN',
            'search'          => '検索',
            'tab_home'        => 'ホーム',
            'tab_rank'        => 'ランキング',
            'tab_compare'     => '比較',
            'tab_guide'       => 'ガイド',
            'tab_me'          => 'マイページ',
            'nav_home'        => 'ホーム',
            'nav_rank'        => 'ランキング',
            'nav_guide'       => 'ガイド',
            'nav_compare'     => '比較',
            'nav_article'     => '記事・速報',
            'hero_kicker'     => '独立鑑定 · INDEPENDENT REVIEW',
            'hero_cta_rank'   => 'ランキングを見る',
            'hero_cta_quiz'   => '診断スタート',
            'sec_top'         => '今月のTOP3',
            'sec_top_roma'    => 'MONTHLY TOP',
            'sec_categories'  => 'ジャンル別',
            'sec_cats_roma'   => 'BY CATEGORY',
            'sec_compare'     => '比較で選ぶ',
            'sec_cmp_roma'    => 'QUICK COMPARE',
            'sec_guides'      => '今読むべき',
            'sec_guides_roma' => 'LATEST GUIDES',
            'see_all'         => '全て見る',
            'read_review'     => 'レビューを読む',
            'trust_title'     => '当サイトの審査基準',
            'footer_age'      => '20歳未満のご利用は禁止 · 責任あるギャンブルを',
            'bonus_label'     => '新規ボーナス',
            'featured'        => '編集長おすすめ',
            'method_more'     => '審査方法の詳細 · METHODOLOGY',
            'method_sub'      => '4軸採点プロセスを全公開',
            'cat_slot'        => 'スロット',
            'cat_live'        => 'ライブ',
            'cat_crypto'      => '仮想通貨',
            'cat_new'         => '初心者',
            'trust_body'      => 'ライセンス・出金速度・サポート品質・RTPの4軸で評価。実際にプレイした上で公正に採点しています。',
            'editor_tip'      => '初めての方は「賭け条件20倍以下」のボーナスを選ぶと、引き出しまでが格段に楽になります。',
            'hero_h1_main'    => '本物の',
            'hero_h1_kw'      => 'オンラインカジノ',
            'hero_h1_tail'    => 'を、編集部が鑑定する。',
            'hero_lead'       => '専門家が実際にプレイし、4軸で公正に採点した日本対応カジノ',
            'hero_lead_unit'  => 'サイト。',
        ],
        'en' => [
            'brand'           => 'KASINO Review',
            'brand_jp'        => 'JAPAN CASINO RATINGS',
            'lang_toggle'     => 'EN / JA',
            'search'          => 'Search',
            'tab_home'        => 'Home',
            'tab_rank'        => 'Ranking',
            'tab_compare'     => 'Compare',
            'tab_guide'       => 'Guides',
            'tab_me'          => 'Me',
            'nav_home'        => 'Home',
            'nav_rank'        => 'Rankings',
            'nav_guide'       => 'Guides',
            'nav_compare'     => 'Compare',
            'nav_article'     => 'Articles & News',
            'hero_kicker'     => 'INDEPENDENT REVIEW · 独立鑑定',
            'hero_cta_rank'   => 'View ranking',
            'hero_cta_quiz'   => 'Start quiz',
            'sec_top'         => "This Month's Top 3",
            'sec_top_roma'    => 'MONTHLY TOP',
            'sec_categories'  => 'By Category',
            'sec_cats_roma'   => 'BY CATEGORY',
            'sec_compare'     => 'Quick Compare',
            'sec_cmp_roma'    => 'QUICK COMPARE',
            'sec_guides'      => 'Latest Guides',
            'sec_guides_roma' => 'LATEST GUIDES',
            'see_all'         => 'See all',
            'read_review'     => 'Read review',
            'trust_title'     => 'Our review criteria',
            'footer_age'      => '20+ only · Please gamble responsibly',
            'bonus_label'     => 'Welcome Bonus',
            'featured'        => "Editor's Pick",
            'method_more'     => 'Full Methodology · 審査方法',
            'method_sub'      => 'Full 4-axis scoring process',
            'cat_slot'        => 'Slots',
            'cat_live'        => 'Live Casino',
            'cat_crypto'      => 'Crypto',
            'cat_new'         => 'Beginners',
            'trust_body'      => '4-axis scoring: license, payout speed, support, and RTP. Rated after actual play.',
            'editor_tip'      => "First-timers: pick bonuses with wagering requirements ≤20× for easier withdrawal.",
            'hero_h1_main'    => 'Authentic ',
            'hero_h1_kw'      => 'online casinos',
            'hero_h1_tail'    => ', rated by our editors.',
            'hero_lead'       => 'Expert-reviewed & scored on 4 axes —',
            'hero_lead_unit'  => 'Japan-friendly casinos.',
        ],
    ];
}

/**
 * Translates a key using the current user language (from cookie).
 */
function kasino_t( string $key ): string {
    static $dict = null;
    static $lang = null;
    if ( $dict === null ) {
        $dict = kasino_i18n_dict();
        $lang = kasino_get_lang();
    }
    return $dict[ $lang ][ $key ] ?? $dict['ja'][ $key ] ?? $key;
}
