<!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
  <?php wp_head(); ?>

  <!-- Preconnect Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php $current_lang = kasino_get_lang(); ?>
<!-- ══════════════════════════════════════════════════════════
     MOBILE APPBAR (visible <1024px) — matches Shell.jsx AppBar
══════════════════════════════════════════════════════════ -->
<div class="mobile-header" role="banner" aria-label="サイトヘッダー（モバイル）"
     data-lang="<?php echo esc_attr( $current_lang ); ?>">

  <!-- Left: hamburger + brand -->
  <div class="appbar-left">
    <button class="icon-btn"
            id="mobile-menu-btn"
            aria-label="メニューを開く"
            aria-expanded="false"
            aria-controls="mobile-nav-drawer">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <line x1="3" y1="6" x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
      </svg>
    </button>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
       class="mobile-brand"
       title="KASINO 鑑定 — トップページへ">
      <span class="brand-jp" data-t="brand_jp"><?php echo esc_html( kasino_t( 'brand_jp' ) ); ?></span>
      <span class="brand-main" data-t="brand"><?php echo esc_html( kasino_t( 'brand' ) ); ?></span>
    </a>
  </div>

  <!-- Right: lang toggle + search + bell -->
  <div class="appbar-right">
    <button class="lang-toggle"
            id="lang-toggle-btn"
            aria-label="言語切替"
            aria-pressed="<?php echo $current_lang === 'en' ? 'true' : 'false'; ?>"
            title="Switch language / 言語を切り替え">
      <?php echo esc_html( kasino_t( 'lang_toggle' ) ); ?>
    </button>
    <button class="icon-btn"
            id="mobile-search-btn"
            aria-label="検索"
            title="サイト内検索">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
    </button>
    <button class="icon-btn mobile-bell-btn" aria-label="通知">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
        <path d="M13.73 21a2 2 0 01-3.46 0"/>
      </svg>
      <span class="bell-dot" aria-hidden="true"></span>
    </button>
  </div>
</div>

<!-- ══════════════════════════════════════════════════════════
     DESKTOP HEADER (visible ≥1024px)
══════════════════════════════════════════════════════════ -->
<header class="site-header" role="banner" aria-label="サイトヘッダー">

  <!-- Utility bar -->
  <div class="site-util" aria-label="ユーティリティバー">
    <div class="container">
      <span class="site-util-left">KASINO 鑑定 · 編集部による独立鑑定 · SINCE 2024</span>
      <nav class="site-util-right" aria-label="ユーティリティナビゲーション">
        <button class="lang-toggle"
                style="background:transparent;border:1px solid rgba(251,247,240,0.25);color:var(--washi);font-size:10.5px;letter-spacing:0.1em;padding:3px 10px;border-radius:4px;cursor:pointer;"
                aria-label="言語切替"
                title="Switch language">
          <?php echo esc_html( kasino_t( 'lang_toggle' ) ); ?>
        </button>
        <?php $rg_page = get_page_by_path( 'responsible-gambling' );
              $rg_url  = $rg_page ? esc_url( get_permalink( $rg_page ) ) : esc_url( home_url( '/responsible-gambling/' ) ); ?>
        <a href="<?php echo $rg_url; ?>" title="責任あるギャンブル">20歳未満ご利用不可</a>
        <a href="<?php echo $rg_url; ?>" title="責任あるギャンブル">RG · 責任あるプレイ</a>
        <a href="<?php echo esc_url( wp_login_url() ); ?>"
           class="register-link"
           title="会員登録">会員登録</a>
        <a href="<?php echo esc_url( wp_login_url() ); ?>"
           title="ログイン">ログイン</a>
      </nav>
    </div>
  </div>

  <!-- Brand row -->
  <div class="site-brand">
    <div class="site-brand-mark">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
         class="site-brand-stamp"
         title="KASINO 鑑定 — トップページへ"
         aria-label="KASINO 鑑定 ホームへ">鑑</a>
      <div class="site-brand-text">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="site-wordmark"
           title="KASINO 鑑定 トップページ"
           style="text-decoration:none;">
          KASINO<em>鑑定</em>
        </a>
        <div class="site-tagline">オンラインカジノ専門レビュー · 編集長 葵 監修</div>
      </div>
    </div>
  </div>

  <!-- Primary navigation -->
  <nav class="site-nav" aria-label="メインナビゲーション" id="main-nav">
    <div class="container">
      <?php
      // Desktop nav items (custom since WP nav_menu adds complex markup)
      $nav_items = [
        [ 'url' => home_url('/'),                            'kanji' => 'ホーム',     'roma' => 'HOME',     'id' => '' ],
        [ 'url' => home_url('/casino/'),                     'kanji' => 'ランキング', 'roma' => 'RANKINGS', 'id' => 'rank' ],
        [ 'url' => home_url('/guide/'),                      'kanji' => 'ガイド',     'roma' => 'GUIDES',   'id' => 'guide' ],
        [ 'url' => home_url('/casino-cat/compare/'),         'kanji' => '比較',       'roma' => 'COMPARE',  'id' => 'compare' ],
        [ 'url' => home_url('/news/'),                       'kanji' => '記事',       'roma' => 'ARTICLES', 'id' => 'article' ],
        [ 'url' => home_url('/news/'),                       'kanji' => '速報',       'roma' => 'NEWS',     'id' => 'news' ],
      ];
      ?>
      <ul class="site-nav-list" role="list">
        <?php foreach ( $nav_items as $item ) :
          $is_current = ( is_front_page() && $item['id'] === '' )
            || ( ! is_front_page() && is_page() && trailingslashit( get_permalink() ) === trailingslashit( $item['url'] ) )
            || ( get_post_type() === 'casino' && $item['id'] === 'rank' )
            || ( is_single() && get_post_type() === 'post' && $item['id'] === 'article' );
        ?>
        <li>
          <a href="<?php echo esc_url( $item['url'] ); ?>"
             <?php if ( $is_current ) : ?>aria-current="page" class="current"<?php endif; ?>
             title="<?php echo esc_attr( $item['kanji'] ); ?>">
            <span class="nav-kanji"><?php echo esc_html( $item['kanji'] ); ?></span>
            <span class="nav-roma"><?php echo esc_html( $item['roma'] ); ?></span>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>

      <div class="site-nav-spacer" aria-hidden="true"></div>

      <a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>"
         class="site-nav-search"
         aria-label="検索"
         title="カジノ・ガイドを検索">
        <span>検索 · Search casinos, guides…</span>
        <kbd aria-label="キーボードショートカット">⌘K</kbd>
      </a>
    </div>
  </nav>
</header>

<!-- ══════════════════════════════════════════════════════════
     MOBILE NAV DRAWER (hidden by default)
══════════════════════════════════════════════════════════ -->
<div id="mobile-nav-drawer"
     role="dialog"
     aria-modal="true"
     aria-label="ナビゲーションメニュー"
     style="display:none; position:fixed; inset:0; background:rgba(28,28,30,0.72); z-index:200; backdrop-filter:blur(2px);">
  <div class="mobile-nav-drawer-panel">
    <div class="mobile-nav-header">
      <div class="mobile-nav-brand">
        KASINO <span style="color:var(--shu);">鑑定</span>
      </div>
      <button id="mobile-nav-close"
              class="mobile-nav-close"
              aria-label="メニューを閉じる">×</button>
    </div>
    <nav aria-label="モバイルナビゲーション">
      <ul style="list-style:none;padding:0;margin:0;">
        <?php
        $mobile_nav = [
          [ home_url('/'),                     kasino_t('nav_home'),    '鑑定ランキング最新版',  'nav_home' ],
          [ home_url('/casino/'),              kasino_t('nav_rank'),    'TOP10 カジノ一覧',      'nav_rank' ],
          [ home_url('/guide/'),               kasino_t('nav_guide'),   '入門・戦術・入出金',    'nav_guide' ],
          [ home_url('/casino-cat/compare/'),  kasino_t('nav_compare'), 'カジノを並べて比較',    'nav_compare' ],
          [ home_url('/news/'),                kasino_t('nav_article'), '業界ニュース・分析',    'nav_article' ],
        ];
        foreach ( $mobile_nav as $m ) :
        ?>
        <li style="border-bottom:1px solid var(--line-2);">
          <a href="<?php echo esc_url( $m[0] ); ?>"
             title="<?php echo esc_attr( $m[1] ); ?>"
             style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;text-decoration:none;color:var(--ink);">
            <div>
              <div style="font-family:var(--font-serif);font-weight:700;font-size:15px;color:var(--sumi);"
                   data-t="<?php echo esc_attr( $m[3] ); ?>"><?php echo esc_html( $m[1] ); ?></div>
              <div style="font-size:10px;color:var(--ink-mute);letter-spacing:0.1em;margin-top:2px;"><?php echo esc_html( $m[2] ); ?></div>
            </div>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--ink-mute)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <!-- Lang toggle inside drawer -->
    <div style="padding:16px 20px;border-top:1px dashed var(--line);">
      <button class="lang-toggle" style="width:100%;justify-content:center;padding:10px 16px;font-size:12px;"
              aria-label="言語切替">
        <?php echo esc_html( kasino_t( 'lang_toggle' ) ); ?>
      </button>
    </div>
    <div style="padding:12px 20px 40px;text-align:center;">
      <div style="font-size:10px;color:var(--ink-mute);line-height:1.8;">
        20歳未満のご利用は禁止<br>責任あるギャンブルを · GamCare
      </div>
    </div>
  </div>
</div>

<!-- MAIN content starts -->
<main id="main-content" role="main">
