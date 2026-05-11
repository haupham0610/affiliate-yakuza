<?php
/**
 * KASINO 鑑定 — functions.php
 * Theme setup, enqueue, Custom Post Types, Custom Fields, REST API, Schema Markup
 */

defined( 'ABSPATH' ) || exit;

// i18n helper (cookie-based JA/EN)
require_once get_template_directory() . '/inc/i18n.php';

// ══════════════════════════════════════════════════════════════
// 1. THEME SETUP
// ══════════════════════════════════════════════════════════════
function kasino_setup() {
    load_theme_textdomain( 'kasino-kantei', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add thumbnail sizes
    add_image_size( 'kasino-hero',    1280, 560, true );
    add_image_size( 'kasino-card',     640, 360, true );
    add_image_size( 'kasino-thumb',    320, 180, true );
    add_image_size( 'kasino-square',   300, 300, true );

    // Navigation menus
    register_nav_menus( [
        'primary'  => __( 'ナビゲーション (Primary Nav)', 'kasino-kantei' ),
        'footer'   => __( 'フッター (Footer Links)', 'kasino-kantei' ),
        'mobile'   => __( 'モバイルメニュー (Mobile Tab Bar)', 'kasino-kantei' ),
        'utility'  => __( 'ユーティリティバー (Utility Bar)', 'kasino-kantei' ),
    ] );
}
add_action( 'after_setup_theme', 'kasino_setup' );

// ══════════════════════════════════════════════════════════════
// 2. ENQUEUE ASSETS
// ══════════════════════════════════════════════════════════════
function kasino_enqueue() {
    $ver = wp_get_theme()->get( 'Version' );

    // Google Fonts — preconnect handled in header.php
    wp_enqueue_style(
        'kasino-gfonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700;800;900&family=Noto+Serif+JP:wght@500;600;700;800;900&display=swap',
        [],
        null
    );

    // Design tokens (CSS custom properties)
    wp_enqueue_style( 'kasino-tokens', get_template_directory_uri() . '/assets/css/tokens.css', [ 'kasino-gfonts' ], $ver );

    // Main stylesheet
    wp_enqueue_style( 'kasino-main', get_template_directory_uri() . '/assets/css/main.css', [ 'kasino-tokens' ], $ver );

    // Theme style.css (required for WP)
    wp_enqueue_style( 'kasino-style', get_stylesheet_uri(), [ 'kasino-main' ], $ver );

    // i18n script (loads before main.js — sets window.KASINO_I18N)
    wp_enqueue_script(
        'kasino-i18n',
        get_template_directory_uri() . '/assets/js/i18n.js',
        [],
        $ver,
        [ 'strategy' => 'defer', 'in_footer' => true ]
    );

    // Main JS (defer, no jQuery dependency)
    wp_enqueue_script(
        'kasino-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [ 'kasino-i18n' ],
        $ver,
        [ 'strategy' => 'defer', 'in_footer' => true ]
    );

    // Pass PHP data to JS
    wp_localize_script( 'kasino-main', 'KASINO', [
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'kasino_ajax' ),
        'lang'     => kasino_get_lang(),
        'themeUrl' => get_template_directory_uri(),
        'homeUrl'  => home_url( '/' ),
    ] );
}
add_action( 'wp_enqueue_scripts', 'kasino_enqueue' );

// ══════════════════════════════════════════════════════════════
// 3. CUSTOM POST TYPE — カジノレビュー (casino)
// ══════════════════════════════════════════════════════════════
function kasino_register_cpt() {
    // Post type: casino
    register_post_type( 'casino', [
        'labels' => [
            'name'               => 'カジノ (Casino)',
            'singular_name'      => 'カジノ (Casino)',
            'add_new'            => '新規追加 (Add New)',
            'add_new_item'       => 'カジノを追加 (Add Casino)',
            'edit_item'          => 'カジノを編集 (Edit Casino)',
            'search_items'       => 'カジノを検索 (Search Casinos)',
            'all_items'          => 'すべてのカジノ (All Casinos)',
            'not_found'          => 'カジノが見つかりません (None found)',
        ],
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'casino', 'with_front' => false ],
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ],
        'menu_icon'          => 'dashicons-awards',
        'menu_position'      => 5,
        'show_in_rest'       => true,
        'taxonomies'         => [ 'casino_category', 'casino_tag' ],
    ] );

    // Taxonomy: casino_category
    register_taxonomy( 'casino_category', 'casino', [
        'labels' => [
            'name'          => 'カジノカテゴリー (Categories)',
            'singular_name' => 'カテゴリー (Category)',
            'all_items'     => 'カジノカテゴリー (All Categories)',
            'add_new_item'  => 'カテゴリーを追加 (Add Category)',
        ],
        'hierarchical'   => true,
        'rewrite'        => [ 'slug' => 'casino-cat' ],
        'show_in_rest'   => true,
    ] );

    // Taxonomy: casino_tag
    register_taxonomy( 'casino_tag', 'casino', [
        'labels' => [
            'name'          => 'カジノタグ (Tags)',
            'singular_name' => 'タグ (Tag)',
            'all_items'     => 'カジノタグ (All Tags)',
            'add_new_item'  => 'タグを追加 (Add Tag)',
        ],
        'hierarchical' => false,
        'rewrite'      => [ 'slug' => 'casino-tag' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'kasino_register_cpt' );

// ══════════════════════════════════════════════════════════════
// 4. DEFAULT CATEGORIES & TAGS SETUP
// ══════════════════════════════════════════════════════════════
// Run once on theme activation; safe to run multiple times (wp_insert_term is idempotent for existing terms)
function kasino_seed_taxonomies() {
    // ── Casino categories ──
    $casino_cats = [
        'スロット'     => 'Slots',
        'ライブカジノ'  => 'Live Casino',
        '仮想通貨対応'  => 'Crypto',
        '初心者向け'    => 'Beginner-Friendly',
        'ハイローラー'  => 'High Roller',
        'モバイル特化'  => 'Mobile',
        'スポーツベット'=> 'Sportsbook',
        'VIP専用'       => 'VIP',
    ];
    foreach ( $casino_cats as $name => $description ) {
        if ( ! term_exists( $name, 'casino_category' ) ) {
            wp_insert_term( $name, 'casino_category', [ 'description' => $description ] );
        }
    }

    // ── Casino tags ──
    $casino_tags = [
        'JCB対応', 'PayPay対応', '銀行送金', '仮想通貨',
        '24時間サポート', '即時出金', 'MGAライセンス', 'Curaçaoライセンス',
        'Anjouanライセンス', 'フリースピン', 'キャッシュバック', '日本語対応',
    ];
    foreach ( $casino_tags as $tag ) {
        if ( ! term_exists( $tag, 'casino_tag' ) ) {
            wp_insert_term( $tag, 'casino_tag' );
        }
    }

    // ── Post categories (for articles/guides) ──
    $post_cats = [
        '入門'    => [ 'slug' => 'beginners',  'description' => '初心者向けガイド', 'parent' => 0 ],
        '戦術'    => [ 'slug' => 'strategy',   'description' => 'ゲーム戦略', 'parent' => 0 ],
        '入出金'  => [ 'slug' => 'payment',    'description' => '入出金ガイド', 'parent' => 0 ],
        '特典'    => [ 'slug' => 'bonuses',    'description' => 'ボーナス・特典', 'parent' => 0 ],
        '安全'    => [ 'slug' => 'safety',     'description' => 'ライセンス・安全情報', 'parent' => 0 ],
        '比較'    => [ 'slug' => 'compare',    'description' => 'カジノ比較', 'parent' => 0 ],
        '速報'    => [ 'slug' => 'news',       'description' => '業界ニュース', 'parent' => 0 ],
    ];
    foreach ( $post_cats as $name => $args ) {
        if ( ! term_exists( $name, 'category' ) ) {
            wp_insert_term( $name, 'category', [
                'slug'        => $args['slug'],
                'description' => $args['description'],
            ] );
        }
    }
}
add_action( 'after_switch_theme', 'kasino_seed_taxonomies' );

// ══════════════════════════════════════════════════════════════
// 5. CUSTOM META FIELDS (without ACF dependency)
//    Fields stored as post meta; ACF alternative approach included
// ══════════════════════════════════════════════════════════════
function kasino_register_meta() {
    // Casino score fields
    $casino_fields = [
        '_casino_score'          => [ 'type' => 'number',  'label' => '総合スコア (0–5)',         'step' => '0.1', 'min' => '0', 'max' => '5' ],
        '_casino_rank'           => [ 'type' => 'number',  'label' => '順位 (ランキング)',          'step' => '1',   'min' => '1' ],
        '_casino_score_license'  => [ 'type' => 'number',  'label' => 'ライセンススコア (0–5)',     'step' => '0.1', 'min' => '0', 'max' => '5' ],
        '_casino_score_payout'   => [ 'type' => 'number',  'label' => '出金速度スコア (0–5)',      'step' => '0.1', 'min' => '0', 'max' => '5' ],
        '_casino_score_support'  => [ 'type' => 'number',  'label' => 'サポート品質スコア (0–5)',  'step' => '0.1', 'min' => '0', 'max' => '5' ],
        '_casino_score_rtp'      => [ 'type' => 'number',  'label' => 'RTP & ゲーム品質スコア',   'step' => '0.1', 'min' => '0', 'max' => '5' ],
        '_casino_score_mobile'   => [ 'type' => 'number',  'label' => 'モバイルスコア (0–5)',      'step' => '0.1', 'min' => '0', 'max' => '5' ],
        '_casino_score_jp'       => [ 'type' => 'number',  'label' => '日本市場対応スコア (0–5)', 'step' => '0.1', 'min' => '0', 'max' => '5' ],
        '_casino_name_jp'        => [ 'type' => 'text',    'label' => 'カジノ名（日本語）' ],
        '_casino_badge'          => [ 'type' => 'text',    'label' => '印鑑バッジ文字 (例: 殿堂, 優, 良)' ],
        '_casino_hue'            => [ 'type' => 'text',    'label' => 'カラーコード (例: #6B1F2A)' ],
        '_casino_bonus'          => [ 'type' => 'text',    'label' => 'ウェルカムボーナス' ],
        '_casino_wager'          => [ 'type' => 'text',    'label' => '賭け条件 (例: 20×)' ],
        '_casino_payout'         => [ 'type' => 'text',    'label' => '平均出金速度 (例: 24h)' ],
        '_casino_license'        => [ 'type' => 'text',    'label' => 'ライセンス種別' ],
        '_casino_games'          => [ 'type' => 'number',  'label' => 'ゲーム数' ],
        '_casino_min_deposit'    => [ 'type' => 'text',    'label' => '最低入金額' ],
        '_casino_founded'        => [ 'type' => 'text',    'label' => '設立年' ],
        '_casino_currency'       => [ 'type' => 'text',    'label' => '対応通貨 (例: JPY · USD · BTC)' ],
        '_casino_payments'       => [ 'type' => 'text',    'label' => '支払い方法 (カンマ区切り)' ],
        '_casino_pros'           => [ 'type' => 'textarea','label' => 'メリット (改行区切り)' ],
        '_casino_cons'           => [ 'type' => 'textarea','label' => 'デメリット (改行区切り)' ],
        '_casino_affiliate_url'  => [ 'type' => 'url',     'label' => '公式サイトURL (アフィリエイト)' ],
        '_casino_tag_jp'         => [ 'type' => 'text',    'label' => '短縮タグ (例: 一押し, 新登場)' ],
        '_casino_review_count'   => [ 'type' => 'number',  'label' => 'レビュー件数' ],
        '_casino_editor_note'    => [ 'type' => 'textarea','label' => '編集長コメント' ],
        '_casino_updated'        => [ 'type' => 'text',    'label' => '最終更新日 (例: 2026年4月)' ],
        // Article-specific
        '_article_read_time'     => [ 'type' => 'number',  'label' => '推定読了時間（分）', 'post_type' => 'post' ],
        '_article_source'        => [ 'type' => 'text',    'label' => '情報ソース/引用元',  'post_type' => 'post' ],
    ];

    // Register with WP REST API for block editor support
    foreach ( $casino_fields as $key => $field ) {
        $pt = isset( $field['post_type'] ) ? $field['post_type'] : 'casino';
        register_post_meta( $pt, $key, [
            'show_in_rest'  => true,
            'single'        => true,
            'type'          => $field['type'] === 'number' ? 'number' : ( $field['type'] === 'textarea' ? 'string' : $field['type'] ),
            'auth_callback' => function() { return current_user_can( 'edit_posts' ); },
        ] );
    }
}
add_action( 'init', 'kasino_register_meta' );

// ══════════════════════════════════════════════════════════════
// 6. ADMIN META BOXES FOR CASINO FIELDS
// ══════════════════════════════════════════════════════════════
function kasino_add_meta_boxes() {
    add_meta_box( 'kasino_casino_scores', '鑑定スコア · SCORES',    'kasino_render_scores_box',  'casino', 'normal', 'high' );
    add_meta_box( 'kasino_casino_info',   '基本情報 · CASINO INFO', 'kasino_render_info_box',    'casino', 'normal', 'high' );
    add_meta_box( 'kasino_casino_aff',    '公式リンク · AFFILIATE', 'kasino_render_aff_box',     'casino', 'side',   'high' );
    add_meta_box( 'kasino_article_meta',  '記事情報 · ARTICLE META','kasino_render_article_meta','post',   'side',   'default' );
}
add_action( 'add_meta_boxes', 'kasino_add_meta_boxes' );

function kasino_render_scores_box( $post ) {
    wp_nonce_field( 'kasino_meta', 'kasino_meta_nonce' );
    $fields = [
        '_casino_score'         => '総合スコア (Overall Score)',
        '_casino_rank'          => '順位 (Rank)',
        '_casino_score_license' => 'ライセンス (License Score)',
        '_casino_score_payout'  => '出金速度 (Payout Score)',
        '_casino_score_support' => 'サポート (Support Score)',
        '_casino_score_rtp'     => 'RTP & ゲーム (RTP & Games)',
        '_casino_score_mobile'  => 'モバイル (Mobile Score)',
        '_casino_score_jp'      => '日本市場対応 (Japan Fit)',
    ];
    echo '<table class="form-table">';
    foreach ( $fields as $key => $label ) {
        $val = get_post_meta( $post->ID, $key, true );
        $step = $key === '_casino_rank' ? '1' : '0.1';
        $max  = $key === '_casino_rank' ? '100' : '5';
        printf(
            '<tr><th><label for="%1$s">%2$s</label></th><td><input type="number" id="%1$s" name="%1$s" value="%3$s" step="%4$s" min="0" max="%5$s" class="small-text" /></td></tr>',
            esc_attr( $key ), esc_html( $label ), esc_attr( $val ), $step, $max
        );
    }
    echo '</table>';
}

function kasino_render_info_box( $post ) {
    $text_fields = [
        '_casino_name_jp'     => 'カジノ名（日本語）(JP Name)',
        '_casino_badge'       => '印鑑バッジ (Badge: 殿堂 / 優 / 良)',
        '_casino_bonus'       => 'ウェルカムボーナス (Welcome Bonus)',
        '_casino_wager'       => '賭け条件 (Wagering Req.)',
        '_casino_payout'      => '平均出金速度 (Avg. Payout Speed)',
        '_casino_license'     => 'ライセンス (License)',
        '_casino_currency'    => '対応通貨 (Currencies)',
        '_casino_payments'    => '支払い方法 (Payment Methods, comma-sep.)',
        '_casino_min_deposit' => '最低入金額 (Min. Deposit)',
        '_casino_founded'     => '設立年 (Founded Year)',
        '_casino_tag_jp'      => '短縮タグ (Short Tag)',
        '_casino_updated'     => '最終更新日 (Last Updated)',
    ];
    $hue_val = get_post_meta( $post->ID, '_casino_hue', true ) ?: '#6B1F2A';
    echo '<table class="form-table">';
    foreach ( $text_fields as $key => $label ) {
        $val = get_post_meta( $post->ID, $key, true );
        printf(
            '<tr><th><label for="%1$s">%2$s</label></th><td><input type="text" id="%1$s" name="%1$s" value="%3$s" class="regular-text" /></td></tr>',
            esc_attr( $key ), esc_html( $label ), esc_attr( $val )
        );
        // Insert color picker row right after the badge row
        if ( $key === '_casino_badge' ) {
            printf(
                '<tr><th><label for="_casino_hue">ブランドカラー (Brand Color)</label></th><td>' .
                '<input type="color" id="_casino_hue_picker" value="%1$s" style="width:44px;height:32px;padding:2px;border:1px solid #8c8f94;border-radius:3px;cursor:pointer;vertical-align:middle;">' .
                '<input type="text" id="_casino_hue" name="_casino_hue" value="%1$s" style="width:110px;margin-left:8px;font-family:monospace;vertical-align:middle;" placeholder="#6B1F2A" maxlength="7">' .
                '<span id="_casino_hue_preview" style="display:inline-block;width:20px;height:20px;border-radius:50%%;background:%1$s;border:1px solid #8c8f94;margin-left:8px;vertical-align:middle;"></span>' .
                '<script>(function(){' .
                'var p=document.getElementById("_casino_hue_picker");' .
                'var t=document.getElementById("_casino_hue");' .
                'var s=document.getElementById("_casino_hue_preview");' .
                'p.addEventListener("input",function(){t.value=this.value;s.style.background=this.value;});' .
                't.addEventListener("input",function(){if(/^#[0-9a-fA-F]{6}$/.test(this.value)){p.value=this.value;s.style.background=this.value;}});' .
                '})();</script>' .
                '</td></tr>',
                esc_attr( $hue_val )
            );
        }
    }
    // Textarea fields
    $ta_fields = [
        '_casino_pros'        => 'メリット (Pros — one per line)',
        '_casino_cons'        => 'デメリット (Cons — one per line)',
        '_casino_editor_note' => '編集長コメント (Editor\'s Note)',
    ];
    foreach ( $ta_fields as $key => $label ) {
        $val = get_post_meta( $post->ID, $key, true );
        printf(
            '<tr><th><label for="%1$s">%2$s</label></th><td><textarea id="%1$s" name="%1$s" rows="4" class="large-text">%3$s</textarea></td></tr>',
            esc_attr( $key ), esc_html( $label ), esc_textarea( $val )
        );
    }
    // Games count
    $games = get_post_meta( $post->ID, '_casino_games', true );
    echo '<tr><th><label for="_casino_games">ゲーム数 (Game Count)</label></th><td><input type="number" id="_casino_games" name="_casino_games" value="' . esc_attr( $games ) . '" class="small-text" min="0" /></td></tr>';
    $reviews = get_post_meta( $post->ID, '_casino_review_count', true );
    echo '<tr><th><label for="_casino_review_count">レビュー件数 (Review Count)</label></th><td><input type="number" id="_casino_review_count" name="_casino_review_count" value="' . esc_attr( $reviews ) . '" class="small-text" min="0" /></td></tr>';
    echo '</table>';
}

function kasino_render_aff_box( $post ) {
    $url = get_post_meta( $post->ID, '_casino_affiliate_url', true );
    echo '<table class="form-table"><tr><th><label for="_casino_affiliate_url">公式サイトURL (Official Site URL)</label></th>';
    echo '<td><input type="url" id="_casino_affiliate_url" name="_casino_affiliate_url" value="' . esc_attr( $url ) . '" class="regular-text" /></td></tr></table>';
    echo '<p class="description">rel="nofollow sponsored" が自動付与されます。(Added automatically)</p>';
}

function kasino_render_article_meta( $post ) {
    wp_nonce_field( 'kasino_article_meta', 'kasino_article_meta_nonce' );
    $read_time = get_post_meta( $post->ID, '_article_read_time', true );
    $source    = get_post_meta( $post->ID, '_article_source', true );
    echo '<table class="form-table">';
    echo '<tr><th><label for="_article_read_time">読了時間（分）(Read Time, min.)</label></th><td><input type="number" id="_article_read_time" name="_article_read_time" value="' . esc_attr( $read_time ) . '" class="small-text" min="1" /></td></tr>';
    echo '<tr><th><label for="_article_source">情報ソース/引用元 (Source / Citation)</label></th><td><input type="text" id="_article_source" name="_article_source" value="' . esc_attr( $source ) . '" class="regular-text" /></td></tr>';
    echo '</table>';
}

// Save meta boxes
function kasino_save_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( ! isset( $_POST['kasino_meta_nonce'] ) || ! wp_verify_nonce( $_POST['kasino_meta_nonce'], 'kasino_meta' ) ) {
        // Check article meta nonce too
        if ( ! isset( $_POST['kasino_article_meta_nonce'] ) || ! wp_verify_nonce( $_POST['kasino_article_meta_nonce'], 'kasino_article_meta' ) ) {
            return;
        }
    }

    $all_keys = [
        '_casino_score', '_casino_rank', '_casino_score_license', '_casino_score_payout',
        '_casino_score_support', '_casino_score_rtp', '_casino_score_mobile', '_casino_score_jp',
        '_casino_name_jp', '_casino_badge', '_casino_hue', '_casino_bonus', '_casino_wager',
        '_casino_payout', '_casino_license', '_casino_games', '_casino_currency', '_casino_payments',
        '_casino_min_deposit', '_casino_founded', '_casino_tag_jp', '_casino_updated',
        '_casino_review_count', '_casino_pros', '_casino_cons', '_casino_editor_note',
        '_casino_affiliate_url', '_article_read_time', '_article_source',
    ];

    foreach ( $all_keys as $key ) {
        if ( ! isset( $_POST[ $key ] ) ) continue;
        if ( in_array( $key, [ '_casino_pros', '_casino_cons', '_casino_editor_note' ], true ) ) {
            update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
        } elseif ( $key === '_casino_affiliate_url' ) {
            update_post_meta( $post_id, $key, esc_url_raw( wp_unslash( $_POST[ $key ] ) ) );
        } else {
            update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
        }
    }
}
add_action( 'save_post', 'kasino_save_meta' );

// ══════════════════════════════════════════════════════════════
// 7. HELPER FUNCTIONS
// ══════════════════════════════════════════════════════════════

/**
 * Render star rating HTML
 * @param float $score  Score out of 5
 * @param int   $max    Maximum stars
 */
function kasino_stars( float $score, int $max = 5 ): string {
    $html  = '<span class="stars" aria-label="' . esc_attr( $score . '/' . $max ) . '">';
    $full  = (int) floor( $score );
    $empty = $max - $full;
    for ( $i = 0; $i < $full; $i++ )  $html .= '★';
    for ( $i = 0; $i < $empty; $i++ ) $html .= '<span class="empty">★</span>';
    $html .= '</span>';
    return $html;
}

/**
 * Get casino meta convenience wrapper
 */
function kasino_get( int $post_id, string $field ): string {
    return (string) get_post_meta( $post_id, '_casino_' . $field, true );
}

/**
 * Render hanko stamp
 */
function kasino_hanko( string $badge, string $extra_class = '' ): string {
    return '<span class="hanko ' . esc_attr( $extra_class ) . '">' . esc_html( $badge ) . '</span>';
}

/**
 * Render JP rank label
 */
function kasino_jp_rank( int $rank ): string {
    $kanji = [ '', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十' ];
    $k = $rank <= 10 ? ( '第' . $kanji[ $rank ] . '位' ) : "第{$rank}位";
    return esc_html( $k );
}

/**
 * Read-time estimate — used if meta not set
 */
function kasino_read_time( int $post_id ): int {
    $stored = (int) get_post_meta( $post_id, '_article_read_time', true );
    if ( $stored > 0 ) return $stored;
    $content    = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    return max( 1, (int) ceil( $word_count / 400 ) ); // ~400 words/min JP reading speed
}

/**
 * Affiliate link with proper rel attributes
 */
function kasino_affiliate_link( int $post_id, string $text = '公式サイトへ', string $class = 'btn gold full' ): string {
    $url = get_post_meta( $post_id, '_casino_affiliate_url', true );
    if ( ! $url ) return '';
    return sprintf(
        '<a href="%s" class="%s" rel="nofollow sponsored noopener" target="_blank" title="%s 公式サイト">%s ↗</a>',
        esc_url( $url ),
        esc_attr( $class ),
        esc_attr( get_the_title( $post_id ) ),
        esc_html( $text )
    );
}

/**
 * Section header component (kanji + roma + optional "more" link)
 */
function kasino_section_header( string $kanji, string $roma, string $more_url = '', string $more_text = '', string $kanji_t = '', string $more_t = '' ): void {
    $more_text = $more_text ?: kasino_t( 'see_all' );
    $kdt = $kanji_t ? ' data-t="' . esc_attr( $kanji_t ) . '"' : '';
    $mdt = $more_t  ? ' data-t="' . esc_attr( $more_t )  . '"' : '';
    echo '<div class="section-h">';
    echo '<div class="titles">';
    echo '<div class="kanji"' . $kdt . '>' . esc_html( $kanji ) . '</div>';
    echo '<div class="roma">' . esc_html( $roma ) . '</div>';
    echo '</div>';
    if ( $more_url ) {
        echo '<a href="' . esc_url( $more_url ) . '" class="more"' . $mdt . '>' . esc_html( $more_text ) . ' →</a>';
    }
    echo '</div>';
}

/**
 * Desktop section header
 */
function kasino_d_section_header( string $jp, string $en, string $more_url = '', string $more_text = '全て見る' ): void {
    echo '<div class="d-secthead">';
    echo '<div class="k">' . esc_html( $jp ) . '<em>' . esc_html( $en ) . '</em></div>';
    if ( $more_url ) {
        echo '<a href="' . esc_url( $more_url ) . '" class="more">' . esc_html( $more_text ) . ' →</a>';
    }
    echo '</div>';
}

// ══════════════════════════════════════════════════════════════
// 8. SCHEMA MARKUP (JSON-LD)
// ══════════════════════════════════════════════════════════════
function kasino_schema_markup(): void {
    global $post;

    // Organization schema — always output on all pages
    $org = [
        '@context'  => 'https://schema.org',
        '@type'     => 'Organization',
        'name'      => 'KASINO 鑑定',
        'url'       => home_url(),
        'logo'      => get_template_directory_uri() . '/assets/images/logo.png',
        'description' => 'オンラインカジノを編集部の目で鑑定。ライセンス・出金速度・サポート品質・RTPの4軸で多角的にレビュー。',
        'sameAs'    => [],
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $org, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";

    // WebSite with SearchAction — on home page
    if ( is_front_page() ) {
        $website = [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            'name'            => 'KASINO 鑑定',
            'url'             => home_url(),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [
                    '@type'      => 'EntryPoint',
                    'urlTemplate' => home_url( '/?s={search_term_string}' ),
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
        echo '<script type="application/ld+json">' . wp_json_encode( $website, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
        return;
    }

    if ( ! is_singular() || ! $post ) return;

    // Casino review schema
    if ( $post->post_type === 'casino' ) {
        $score       = (float) get_post_meta( $post->ID, '_casino_score', true );
        $review_count = (int) get_post_meta( $post->ID, '_casino_review_count', true ) ?: 1;
        $aff_url     = get_post_meta( $post->ID, '_casino_affiliate_url', true );

        $review = [
            '@context'     => 'https://schema.org',
            '@type'        => 'Review',
            'name'         => get_the_title() . ' レビュー',
            'description'  => get_the_excerpt() ?: wp_trim_words( get_the_content(), 30 ),
            'url'          => get_permalink(),
            'datePublished'=> get_the_date( 'c' ),
            'dateModified' => get_the_modified_date( 'c' ),
            'author'       => [
                '@type' => 'Organization',
                'name'  => 'KASINO 鑑定 編集部',
                'url'   => home_url(),
            ],
            'publisher'    => [
                '@type' => 'Organization',
                'name'  => 'KASINO 鑑定',
                'url'   => home_url(),
            ],
            'itemReviewed' => [
                '@type'  => 'Organization',
                'name'   => get_the_title(),
                'url'    => $aff_url ?: '',
                'image'  => get_the_post_thumbnail_url( $post->ID, 'full' ) ?: '',
            ],
            'reviewRating' => [
                '@type'       => 'Rating',
                'ratingValue' => $score,
                'bestRating'  => 5,
                'worstRating' => 1,
            ],
            'reviewBody'  => wp_strip_all_tags( get_the_content() ),
        ];
        echo '<script type="application/ld+json">' . wp_json_encode( $review, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
    }

    // Article schema for blog posts
    if ( is_single() && $post->post_type === 'post' ) {
        $article = [
            '@context'      => 'https://schema.org',
            '@type'         => 'Article',
            'headline'      => get_the_title(),
            'description'   => get_the_excerpt() ?: wp_trim_words( get_the_content(), 30 ),
            'url'           => get_permalink(),
            'datePublished' => get_the_date( 'c' ),
            'dateModified'  => get_the_modified_date( 'c' ),
            'author'        => [
                '@type' => 'Person',
                'name'  => get_the_author_meta( 'display_name' ),
            ],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => 'KASINO 鑑定',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => get_template_directory_uri() . '/assets/images/logo.png',
                ],
            ],
            'image'         => get_the_post_thumbnail_url( $post->ID, 'full' ) ?: home_url( '/assets/images/og-default.jpg' ),
            'articleSection'=> implode( ', ', wp_list_pluck( get_the_category(), 'name' ) ),
            'wordCount'     => str_word_count( wp_strip_all_tags( get_the_content() ) ),
        ];
        echo '<script type="application/ld+json">' . wp_json_encode( $article, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'kasino_schema_markup' );

// ══════════════════════════════════════════════════════════════
// 9. AJAX — Load more posts
// ══════════════════════════════════════════════════════════════
function kasino_ajax_load_more() {
    check_ajax_referer( 'kasino_ajax', 'nonce' );

    $paged    = (int) $_POST['paged'] ?? 1;
    $cat      = sanitize_text_field( $_POST['cat'] ?? '' );
    $post_type = sanitize_key( $_POST['post_type'] ?? 'post' );

    $args = [
        'post_type'      => $post_type,
        'posts_per_page' => 9,
        'paged'          => $paged,
        'post_status'    => 'publish',
    ];

    if ( $cat ) {
        if ( $post_type === 'casino' ) {
            $args['tax_query'] = [[ 'taxonomy' => 'casino_category', 'field' => 'slug', 'terms' => $cat ]];
        } else {
            $args['category_name'] = $cat;
        }
    }

    $query = new WP_Query( $args );
    $html  = '';

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            ob_start();
            if ( $post_type === 'casino' ) {
                get_template_part( 'template-parts/card', 'casino' );
            } else {
                get_template_part( 'template-parts/card', 'article' );
            }
            $html .= ob_get_clean();
        }
        wp_reset_postdata();
    }

    wp_send_json_success( [
        'html'       => $html,
        'max_pages'  => $query->max_num_pages,
        'found'      => $query->found_posts,
    ] );
}
add_action( 'wp_ajax_kasino_load_more',        'kasino_ajax_load_more' );
add_action( 'wp_ajax_nopriv_kasino_load_more', 'kasino_ajax_load_more' );

// ══════════════════════════════════════════════════════════════
// 10. PERFORMANCE OPTIMIZATIONS
// ══════════════════════════════════════════════════════════════

// Remove unused default WP head items
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// Disable Gutenberg block library CSS on front-end (we write our own)
add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' );
    wp_dequeue_style( 'classic-theme-styles' );
}, 100 );

// Add lazy loading to all images via WP 5.5+ native support
add_filter( 'wp_lazy_loading_enabled', '__return_true' );

// Add WebP support (if using WP 5.8+)
add_filter( 'wp_get_attachment_image_attributes', function( $attr ) {
    if ( ! isset( $attr['loading'] ) ) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
} );

// ══════════════════════════════════════════════════════════════
// 11. YOAST / RANKMATH COMPATIBILITY
// ══════════════════════════════════════════════════════════════
// Remove our schema if Yoast or RankMath is active (they handle it)
add_action( 'plugins_loaded', function() {
    if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) ) {
        remove_action( 'wp_head', 'kasino_schema_markup' );
    }
} );

// ══════════════════════════════════════════════════════════════
// 12. WIDGETS
// ══════════════════════════════════════════════════════════════
function kasino_widgets_init() {
    register_sidebar([
        'name'          => __( '記事サイドバー', 'kasino-kantei' ),
        'id'            => 'article-sidebar',
        'before_widget' => '<div id="%1$s" class="bento widget %2$s" style="margin-bottom:16px;">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title" style="font-family:var(--font-serif);font-size:14px;font-weight:700;margin:0 0 12px;color:var(--sumi);">',
        'after_title'   => '</h3>',
    ]);
    register_sidebar([
        'name'          => __( 'カジノサイドバー', 'kasino-kantei' ),
        'id'            => 'casino-sidebar',
        'before_widget' => '<div id="%1$s" class="bento widget %2$s" style="margin-bottom:16px;">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title" style="font-family:var(--font-serif);font-size:14px;font-weight:700;margin:0 0 12px;color:var(--sumi);">',
        'after_title'   => '</h3>',
    ]);
}
add_action( 'widgets_init', 'kasino_widgets_init' );

// ══════════════════════════════════════════════════════════════
// 13. TITLE SEO
// ══════════════════════════════════════════════════════════════
add_filter( 'document_title_parts', function( $title ) {
    $title['tagline'] = 'KASINO 鑑定';
    return $title;
} );

// ══════════════════════════════════════════════════════════════
// 14. REWRITE FLUSH ON ACTIVATION
// ══════════════════════════════════════════════════════════════
function kasino_activate() {
    kasino_register_cpt();
    flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'kasino_activate' );
