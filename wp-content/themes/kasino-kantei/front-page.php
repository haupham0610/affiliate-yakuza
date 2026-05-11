<?php
/**
 * KASINO 鑑定 — Front Page Template
 * Renders: Mobile hero + stats / Desktop magazine-cover hero + Top rankings + Articles + Trust
 *
 * @package kasino-kantei
 */

get_header();

// ── Casino data ──
$top_casino_query = new WP_Query([
    'post_type'      => 'casino',
    'posts_per_page' => 10,
    'post_status'    => 'publish',
    'meta_key'       => '_casino_rank',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
]);

$casinos = [];
if ( $top_casino_query->have_posts() ) {
    while ( $top_casino_query->have_posts() ) {
        $top_casino_query->the_post();
        $casinos[] = get_the_ID();
    }
    wp_reset_postdata();
}

// Fallback: if no casino CPT data yet, show design demo state
$has_casino_data = ! empty( $casinos );

// ── Article data ──
$article_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => 6,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
$articles = [];
if ( $article_query->have_posts() ) {
    while ( $article_query->have_posts() ) {
        $article_query->the_post();
        $articles[] = get_the_ID();
    }
    wp_reset_postdata();
}
?>

<div class="home-wrap">

  <!-- ══════════════════════════════════════════════════════
       MOBILE LAYOUT (hidden on desktop via CSS)
  ══════════════════════════════════════════════════════ -->
  <div class="mobile-home">

    <!-- Mobile hero -->
    <section class="mobile-hero" aria-label="ヒーローセクション">
      <div style="position:absolute;inset:0;opacity:0.7;" class="bg-asanoha" aria-hidden="true"></div>
      <div class="mobile-hero-content">
        <div class="mobile-hero-text">
          <div class="mobile-hero-kicker" data-t="hero_kicker"><?php echo esc_html( kasino_t( 'hero_kicker' ) ); ?></div>
          <?php $lang = kasino_get_lang(); ?>
          <h1>
            <span data-lang-show="ja"<?php if ( $lang === 'en' ) echo ' style="display:none"'; ?>>本物の<span><?php echo esc_html( kasino_t( 'hero_h1_kw' ) ); ?></span>を、<br>編集部が鑑定する。</span>
            <span data-lang-show="en"<?php if ( $lang === 'ja' ) echo ' style="display:none"'; ?>>Authentic <span>online casinos</span>,<br>rated by our editors.</span>
          </h1>
          <p class="mobile-hero-lead">
            <span data-lang-show="ja"<?php if ( $lang === 'en' ) echo ' style="display:none"'; ?>>専門家が実際にプレイし、4軸で公正に採点した日本対応カジノ <strong style="color:var(--sumi);">187</strong>サイト。</span>
            <span data-lang-show="en"<?php if ( $lang === 'ja' ) echo ' style="display:none"'; ?>>Expert-reviewed & scored on 4 axes — <strong style="color:var(--sumi);">187</strong> Japan-friendly casinos.</span>
          </p>
          <div class="mobile-hero-cta-row">
            <a href="<?php echo esc_url( home_url( '/casino/' ) ); ?>"
               class="btn primary"
               data-t="hero_cta_rank"
               title="カジノランキングを見る"><?php echo esc_html( kasino_t( 'hero_cta_rank' ) ); ?> →</a>
            <a href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>"
               class="btn outline"
               data-t="hero_cta_quiz"
               title="カジノ診断スタート"><?php echo esc_html( kasino_t( 'hero_cta_quiz' ) ); ?></a>
          </div>
        </div>
        <!-- 編集長 mascot portrait -->
        <div class="mobile-hero-portrait" aria-hidden="true">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/mascot-greet.svg' ); ?>"
               alt=""
               width="84"
               height="92"
               loading="eager"
               style="width:100%;height:auto;display:block;">
        </div>
      </div>
    </section>

    <!-- Mobile stats -->
    <div class="mobile-stats-grid">
      <?php
      $stats = [
        [ 'v' => '187',   'label' => '審査済',  'en' => 'Reviewed', 'color' => 'var(--sumi)' ],
        [ 'v' => '4,200', 'label' => '読者数',  'en' => 'Readers/d', 'color' => 'var(--gold-deep)' ],
        [ 'v' => '2026',  'label' => '最新版',  'en' => 'Updated',  'color' => 'var(--jade)' ],
      ];
      foreach ( $stats as $s ) :
      ?>
      <div class="bento mobile-stat-bento">
        <div class="mobile-stat-value" style="color:<?php echo esc_attr( $s['color'] ); ?>;"><?php echo esc_html( $s['v'] ); ?></div>
        <div style="font-size:10.5px;font-weight:600;color:var(--sumi);margin-top:2px;"><?php echo esc_html( $s['label'] ); ?></div>
        <div style="font-size:8.5px;color:var(--ink-mute);letter-spacing:0.12em;text-transform:uppercase;"><?php echo esc_html( $s['en'] ); ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Mobile Top 3 -->
    <?php kasino_section_header( kasino_t( 'sec_top' ), kasino_t( 'sec_top_roma' ), home_url( '/casino/' ), '', 'sec_top', 'see_all' ); ?>
    <div class="mobile-top3-grid">

      <?php if ( $has_casino_data ) :
        $top1_id    = $casinos[0] ?? 0;
        $top1_hue   = kasino_get( $top1_id, 'hue' ) ?: '#6B1F2A';
        $top1_badge = kasino_get( $top1_id, 'badge' ) ?: '殿堂';
        $top1_score = kasino_get( $top1_id, 'score' ) ?: '4.9';
        $top1_bonus = kasino_get( $top1_id, 'bonus' ) ?: '¥150,000 + 200 FS';
        $top1_pros  = array_filter( explode( "\n", kasino_get( $top1_id, 'pros' ) ) );
      ?>
      <!-- #1 big card -->
      <div class="mobile-top1-card">
        <div class="mobile-top1-header" style="background:<?php echo esc_attr( $top1_hue ); ?>;">
          <span class="mobile-top1-rank">第一位 · RANK 01</span>
          <span class="mobile-top1-score">★ <?php echo esc_html( $top1_score ); ?></span>
        </div>
        <div class="mobile-top1-body">
          <div class="mobile-top1-body-top">
            <?php echo kasino_hanko( $top1_badge, 'lg' ); ?>
            <div style="flex:1;min-width:0;">
              <div class="mobile-top1-name">
                <a href="<?php echo esc_url( get_permalink( $top1_id ) ); ?>"
                   title="<?php echo esc_attr( get_the_title( $top1_id ) ); ?> レビュー"
                   style="color:var(--sumi);text-decoration:none;">
                  <?php echo esc_html( get_the_title( $top1_id ) ); ?>
                </a>
              </div>
              <div class="mobile-top1-jp"><?php echo esc_html( kasino_get( $top1_id, 'name_jp' ) ); ?></div>
            </div>
          </div>
          <div class="mobile-top1-bonus">
            <div class="mobile-top1-bonus-label" data-t="bonus_label"><?php echo esc_html( kasino_t( 'bonus_label' ) ); ?></div>
            <div class="mobile-top1-bonus-val"><?php echo esc_html( $top1_bonus ); ?></div>
          </div>
          <div style="display:flex;flex-wrap:wrap;gap:3px;margin-bottom:8px;">
            <?php foreach ( array_slice( $top1_pros, 0, 2 ) as $pro ) : ?>
            <span class="chip" style="font-size:9px;">
              <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="var(--jade)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
              <?php echo esc_html( trim( $pro ) ); ?>
            </span>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo esc_url( get_permalink( $top1_id ) ); ?>"
             class="btn primary full"
             style="padding:8px;font-size:11px;"
             data-t="read_review"
             title="<?php echo esc_attr( get_the_title( $top1_id ) ); ?> のレビューを読む">
            <?php echo esc_html( kasino_t( 'read_review' ) ); ?>
          </a>
        </div>
      </div>

      <!-- #2, #3 stacked -->
      <?php
      $jp_ranks = [ '', '第一位', '第二位', '第三位', '第四位', '第五位' ];
      foreach ( array_slice( $casinos, 1, 2 ) as $i => $cid ) :
        $score = kasino_get( $cid, 'score' );
        $bonus = kasino_get( $cid, 'bonus' );
        $payout = kasino_get( $cid, 'payout' );
        $rank  = (int) kasino_get( $cid, 'rank' );
        $rank_label = $jp_ranks[ $rank ] ?? "第{$rank}位";
        $color = $i === 0 ? 'var(--ink-soft)' : 'var(--gold-deep)';
      ?>
      <div class="bento" style="padding:10px;">
        <div style="display:flex;align-items:center;gap:6px;margin-bottom:6px;">
          <span style="font-family:var(--font-serif);font-weight:800;font-size:13px;color:<?php echo esc_attr( $color ); ?>;line-height:1;letter-spacing:0.04em;">
            <?php echo esc_html( $rank_label ); ?>
          </span>
          <?php echo kasino_stars( (float) $score ); ?>
          <span style="font-size:10px;color:var(--ink-soft);margin-left:auto;"><?php echo esc_html( $score ); ?></span>
        </div>
        <div style="font-family:var(--font-serif);font-weight:700;font-size:13px;color:var(--sumi);line-height:1.2;">
          <a href="<?php echo esc_url( get_permalink( $cid ) ); ?>"
             style="color:var(--sumi);text-decoration:none;"
             title="<?php echo esc_attr( get_the_title( $cid ) ); ?> レビュー">
            <?php echo esc_html( get_the_title( $cid ) ); ?>
          </a>
        </div>
        <div style="font-size:9.5px;color:var(--ink-mute);margin-bottom:6px;"><?php echo esc_html( kasino_get( $cid, 'name_jp' ) ); ?></div>
        <div style="font-size:10px;color:var(--ink);border-top:1px dashed var(--line);padding-top:6px;">
          <strong style="color:var(--sumi);font-family:var(--font-serif);"><?php echo esc_html( strstr( $bonus, '+', true ) ?: $bonus ); ?></strong>
          <div style="font-size:9px;color:var(--ink-mute);">出金 <?php echo esc_html( $payout ); ?></div>
        </div>
      </div>
      <?php endforeach;

      else : // No casino data yet — show placeholder ?>
      <div class="mobile-top1-card">
        <div class="mobile-top1-header" style="background:#6B1F2A;">
          <span class="mobile-top1-rank">第一位 · RANK 01</span>
          <span class="mobile-top1-score">★ 4.9</span>
        </div>
        <div class="mobile-top1-body">
          <div class="mobile-top1-body-top">
            <span class="hanko lg">殿</span>
            <div>
              <div class="mobile-top1-name">カジノ名</div>
              <div class="mobile-top1-jp">Casino Name JP</div>
            </div>
          </div>
          <div class="mobile-top1-bonus">
            <div class="mobile-top1-bonus-label" data-t="bonus_label"><?php echo esc_html( kasino_t( 'bonus_label' ) ); ?></div>
            <div class="mobile-top1-bonus-val">¥150,000 + 200 FS</div>
          </div>
          <p style="font-size:11px;color:var(--ink-soft);text-align:center;padding:8px 0;">
            管理画面でカジノを追加してください
          </p>
        </div>
      </div>
      <div class="bento" style="padding:10px;display:flex;align-items:center;justify-content:center;">
        <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=casino' ) ); ?>" style="font-size:11px;color:var(--ink-mute);">カジノを追加 +</a>
      </div>
      <div class="bento" style="padding:10px;display:flex;align-items:center;justify-content:center;">
        <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=casino' ) ); ?>" style="font-size:11px;color:var(--ink-mute);">カジノを追加 +</a>
      </div>
      <?php endif; ?>

    </div><!-- .mobile-top3-grid -->

    <!-- Editor tip -->
    <div style="padding:12px 16px 0;">
      <div style="background:var(--washi-2);border:1px solid var(--line);border-radius:var(--r-md);padding:10px 12px;display:flex;align-items:flex-start;gap:10px;">
        <div style="width:32px;height:32px;border-radius:50%;background:var(--sumi);color:var(--gold-deep);display:flex;align-items:center;justify-content:center;font-family:var(--font-serif);font-weight:800;font-size:14px;flex-shrink:0;">葵</div>
        <div style="font-size:11.5px;color:var(--ink-soft);line-height:1.55;" data-t="editor_tip">
          <?php echo esc_html( kasino_t( 'editor_tip' ) ); ?>
        </div>
      </div>
    </div>

    <!-- Mobile categories 4-col grid -->
    <?php kasino_section_header( kasino_t( 'sec_categories' ), kasino_t( 'sec_cats_roma' ), '', '', 'sec_categories' ); ?>
    <div class="mobile-cats-grid">
      <?php
      $cat_items = [
        [ 't' => 'cat_slot',   'e' => 'Slots',  'n' => '94', 'color' => '#6B1F2A', 'bg' => 'rgba(107,31,42,0.12)', 'url' => home_url('/casino-cat/slot/'),   'kanji' => '槽' ],
        [ 't' => 'cat_live',   'e' => 'Live',   'n' => '37', 'color' => '#1F3148', 'bg' => 'rgba(31,49,72,0.12)',  'url' => home_url('/casino-cat/live/'),   'kanji' => '生' ],
        [ 't' => 'cat_crypto', 'e' => 'Crypto', 'n' => '52', 'color' => '#7C5A1A', 'bg' => 'rgba(124,90,26,0.12)','url' => home_url('/casino-cat/crypto/'), 'kanji' => '仮' ],
        [ 't' => 'cat_new',    'e' => 'New',    'n' => '28', 'color' => '#2A4A3A', 'bg' => 'rgba(42,74,58,0.12)', 'url' => home_url('/casino-cat/new/'),    'kanji' => '初' ],
      ];
      foreach ( $cat_items as $cat ) :
        $cat_name = kasino_t( $cat['t'] );
      ?>
      <a href="<?php echo esc_url( $cat['url'] ); ?>"
         class="mobile-cat-btn"
         title="<?php echo esc_attr( $cat_name ); ?>">
        <span class="mobile-cat-icon"
              style="background:<?php echo esc_attr( $cat['bg'] ); ?>;color:<?php echo esc_attr( $cat['color'] ); ?>;">
          <?php echo esc_html( $cat['kanji'] ); ?>
        </span>
        <span class="mobile-cat-name" data-t="<?php echo esc_attr( $cat['t'] ); ?>"><?php echo esc_html( $cat_name ); ?></span>
        <span class="mobile-cat-meta"><?php echo esc_html( $cat['e'] ); ?> · <?php echo esc_html( $cat['n'] ); ?></span>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- Mobile rank list (#4–#6) -->
    <?php if ( ! empty( $casinos ) ) : ?>
    <?php kasino_section_header( kasino_t( 'sec_compare' ), kasino_t( 'sec_cmp_roma' ), home_url( '/casino-cat/compare/' ), kasino_t( 'see_all' ), 'sec_compare', 'see_all' ); ?>
    <div class="mobile-rank-list">
      <?php foreach ( array_slice( $casinos, 3, 3 ) as $cid ) :
        $rank   = (int) kasino_get( $cid, 'rank' );
        $hue    = kasino_get( $cid, 'hue' ) ?: '#3A352E';
        $score  = kasino_get( $cid, 'score' );
        $bonus  = kasino_get( $cid, 'bonus' );
        $payout = kasino_get( $cid, 'payout' );
        $tag_jp = kasino_get( $cid, 'tag_jp' ) ?: 'おすすめ';
        $jp_ranks = [ '', '第一位', '第二位', '第三位', '第四位', '第五位', '第六位', '第七位', '第八位', '第九位', '第十位' ];
        $rk_label = $jp_ranks[ $rank ] ?? "第{$rank}位";
      ?>
      <a href="<?php echo esc_url( get_permalink( $cid ) ); ?>"
         class="bento mobile-rank-row"
         title="<?php echo esc_attr( get_the_title( $cid ) ); ?> レビュー">
        <div class="mobile-rank-badge" style="background:<?php echo esc_attr( $hue ); ?>;">
          <span class="mobile-rank-badge-jp"><?php echo esc_html( $rk_label ); ?></span>
          <span class="mobile-rank-badge-num">RANK <?php echo str_pad( $rank, 2, '0', STR_PAD_LEFT ); ?></span>
        </div>
        <div class="mobile-rank-info">
          <div style="display:flex;align-items:center;gap:5px;margin-bottom:1px;">
            <span class="mobile-rank-name"><?php echo esc_html( get_the_title( $cid ) ); ?></span>
            <span class="chip" style="font-size:8.5px;padding:1px 5px;"><?php echo esc_html( $tag_jp ); ?></span>
          </div>
          <div class="mobile-rank-meta">
            <?php echo kasino_stars( (float) $score ); ?>
            <span><?php echo esc_html( $score ); ?></span>
            <span style="color:var(--line);">•</span>
            <span><?php echo esc_html( strstr( $bonus, '+', true ) ?: $bonus ); ?></span>
            <span style="color:var(--line);">•</span>
            <span><?php echo esc_html( $payout ); ?></span>
          </div>
        </div>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--ink-mute)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Mobile articles -->
    <?php if ( ! empty( $articles ) ) :
      kasino_section_header( kasino_t( 'sec_guides' ), kasino_t( 'sec_guides_roma' ), home_url( '/news/' ), kasino_t( 'see_all' ), 'sec_guides', 'see_all' );
    ?>
    <div style="padding:0 16px;">
      <div class="articles-grid">
        <!-- Featured article -->
        <div class="article-featured" style="min-height:180px;">
          <div class="article-featured-pattern bg-seigaiha" aria-hidden="true"></div>
          <div class="article-featured-body">
            <span class="article-featured-chip" data-t="featured"><?php echo esc_html( kasino_t( 'featured' ) ); ?></span>
            <h3 class="article-featured-title">
              <a href="<?php echo esc_url( get_permalink( $articles[0] ) ); ?>"
                 style="color:var(--washi);text-decoration:none;"
                 title="<?php echo esc_attr( get_the_title( $articles[0] ) ); ?>">
                <?php echo esc_html( get_the_title( $articles[0] ) ); ?>
              </a>
            </h3>
            <div class="article-featured-meta">
              <div>
                <div class="article-featured-author"><?php echo esc_html( get_the_author_meta( 'display_name', get_post_field( 'post_author', $articles[0] ) ) ); ?></div>
                <div class="article-featured-date">
                  <?php echo esc_html( kasino_read_time( $articles[0] ) ); ?>分 · <?php echo esc_html( get_the_date( 'n月j日', $articles[0] ) ); ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Articles 2–3 -->
        <?php foreach ( array_slice( $articles, 1, 2 ) as $aid ) :
          $cats = get_the_category( $aid );
          $cat_name = $cats ? $cats[0]->name : 'ガイド';
        ?>
        <div class="bento article-card">
          <span class="chip outline article-card-cat"><?php echo esc_html( $cat_name ); ?></span>
          <h3 class="article-card-title">
            <a href="<?php echo esc_url( get_permalink( $aid ) ); ?>"
               style="color:var(--sumi);text-decoration:none;"
               title="<?php echo esc_attr( get_the_title( $aid ) ); ?>">
              <?php echo esc_html( get_the_title( $aid ) ); ?>
            </a>
          </h3>
          <div class="article-card-meta">
            <span class="article-author-avatar">
              <?php echo esc_html( mb_substr( get_the_author_meta( 'display_name', get_post_field( 'post_author', $aid ) ), 0, 1 ) ); ?>
            </span>
            <span><?php echo esc_html( get_the_author_meta( 'display_name', get_post_field( 'post_author', $aid ) ) ); ?></span>
            <span style="margin-left:auto;color:var(--ink-mute);"><?php echo esc_html( kasino_read_time( $aid ) ); ?>分</span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Trust card -->
    <div style="padding:16px 16px 0;">
      <div class="trust-card">
        <div class="trust-card-header">
          <div class="trust-stamp" aria-hidden="true">鑑</div>
          <div>
            <div class="trust-title" data-t="trust_title"><?php echo esc_html( kasino_t( 'trust_title' ) ); ?></div>
            <div class="trust-body" data-t="trust_body"><?php echo esc_html( kasino_t( 'trust_body' ) ); ?></div>
          </div>
        </div>
        <div class="trust-creds">
          <?php
          $creds = [
            [ '独立', 'AUDIT',       '独立監査' ],
            [ '個保', 'PRIVACY',     '個人情報保護' ],
            [ 'RG',   'RESPONSIBLE', '責任ギャンブル' ],
          ];
          foreach ( $creds as $c ) :
          ?>
          <div class="trust-cred">
            <div class="trust-cred-icon"><?php echo esc_html( $c[0] ); ?></div>
            <div class="trust-cred-label"><?php echo esc_html( $c[2] ); ?></div>
            <div class="trust-cred-en"><?php echo esc_html( $c[1] ); ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <a href="<?php echo esc_url( home_url( '/methodology/' ) ); ?>"
           style="margin-top:12px;width:100%;padding:10px;background:#fff;border:1px solid var(--line);border-radius:var(--r-sm);display:flex;align-items:center;justify-content:space-between;text-decoration:none;color:var(--sumi);"
           title="鑑定方法の詳細ページへ">
          <span>
            <span style="font-size:11px;font-weight:700;display:block;" data-t="method_more"><?php echo esc_html( kasino_t( 'method_more' ) ); ?></span>
            <span style="font-size:9px;color:var(--ink-mute);" data-t="method_sub"><?php echo esc_html( kasino_t( 'method_sub' ) ); ?></span>
          </span>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
      </div>
    </div>

    <!-- Footer kanji divider -->
    <div class="kanji-divider" style="margin:20px 0 8px;"><span>真 ・ 公 ・ 信</span></div>
    <p style="text-align:center;font-size:9.5px;color:var(--ink-mute);padding:0 16px 12px;"
       data-t="footer_age"><?php echo esc_html( kasino_t( 'footer_age' ) ); ?></p>

  </div><!-- .mobile-home -->


  <!-- ══════════════════════════════════════════════════════
       DESKTOP LAYOUT (hidden on mobile via CSS)
  ══════════════════════════════════════════════════════ -->
  <div class="desktop-home container">

    <?php if ( $has_casino_data ) :
      $d_top   = $casinos[0];
      $d_top_hue   = kasino_get( $d_top, 'hue' ) ?: '#6B1F2A';
      $d_top_badge = kasino_get( $d_top, 'badge' ) ?: '殿堂';
      $d_top_score = kasino_get( $d_top, 'score' ) ?: '4.9';
      $d_top_bonus = kasino_get( $d_top, 'bonus' ) ?: '¥150,000 + 200 FS';
      $d_top_pros  = array_filter( explode( "\n", kasino_get( $d_top, 'pros' ) ) );
      $d_top_payout  = kasino_get( $d_top, 'payout' ) ?: '24h';
      $d_top_license = kasino_get( $d_top, 'license' ) ?: 'Curaçao';
    ?>

    <!-- Magazine cover hero (Top #1) -->
    <section class="hero-desktop" style="background:<?php echo esc_attr( $d_top_hue ); ?>;" aria-label="トップカジノ鑑定">
      <div class="hero-desktop-watermark" aria-hidden="true">鑑</div>
      <div class="hero-desktop-grid">
        <!-- Left: copy -->
        <div>
          <!-- D-kicker -->
          <div class="d-kicker">
            <span class="bar" aria-hidden="true"></span>
            <span>今月の鑑定 · <?php echo esc_html( date_i18n( 'Y年n月号' ) ); ?></span>
            <span class="bar" aria-hidden="true"></span>
          </div>

          <div class="hero-rank-label">第一位 · NO.1 EDITORIAL VERDICT</div>
          <h1 class="hero-casino-jp">
            <a href="<?php echo esc_url( get_permalink( $d_top ) ); ?>"
               style="color:#fff;text-decoration:none;"
               title="<?php echo esc_attr( get_the_title( $d_top ) ); ?> レビュー">
              <?php echo esc_html( kasino_get( $d_top, 'name_jp' ) ?: get_the_title( $d_top ) ); ?>
            </a>
          </h1>
          <div class="hero-casino-en"><?php echo esc_html( strtoupper( get_the_title( $d_top ) ) ); ?></div>
          <p class="hero-lead">
            <?php echo esc_html( get_the_excerpt( $d_top ) ?: '日本円対応・即時出金・ライブ充実を高水準で揃えた今期の殿堂入り。' ); ?>
          </p>
          <div class="hero-pros">
            <?php foreach ( array_slice( $d_top_pros, 0, 3 ) as $pro ) : ?>
            <span class="hero-pro-pill"><?php echo esc_html( trim( $pro ) ); ?></span>
            <?php endforeach; ?>
          </div>
          <div class="hero-cta-row">
            <a href="<?php echo esc_url( get_permalink( $d_top ) ); ?>"
               class="hero-cta-primary"
               style="color:<?php echo esc_attr( $d_top_hue ); ?>;"
               title="<?php echo esc_attr( get_the_title( $d_top ) ); ?> 完全レビュー">
              鑑定書を読む · FULL REVIEW →
            </a>
            <a href="<?php echo esc_url( home_url( '/casino-cat/compare/?add=' . get_post_field( 'post_name', $d_top ) ) ); ?>"
               class="hero-cta-secondary"
               title="比較リストに追加">比較に追加 +</a>
            <div class="hero-byline">
              編集部執筆 · 葵<br>
              <?php echo esc_html( get_the_modified_date( 'Y年n月j日更新', $d_top ) ); ?>
            </div>
          </div>
        </div>

        <!-- Right: score card -->
        <aside class="hero-score-card" aria-label="鑑定スコアカード">
          <div class="hero-score-top">
            <div class="hero-hanko-lg"><?php echo esc_html( $d_top_badge ); ?></div>
            <div>
              <div class="hero-score-label">EDITORIAL SCORE</div>
              <div class="hero-score-num"><?php echo esc_html( $d_top_score ); ?><small>/5.0</small></div>
            </div>
          </div>

          <table class="hero-score-table">
            <tbody>
              <?php
              $axes = [
                [ 'ライセンス',   $d_top_license . ' GLI 認定', kasino_get( $d_top, 'score_license' ) ?: '4.8' ],
                [ '出金速度',     '平均 ' . $d_top_payout,       kasino_get( $d_top, 'score_payout' ) ?: '4.9' ],
                [ 'サポート品質', '4分 / JP 24h',                 kasino_get( $d_top, 'score_support' ) ?: '4.7' ],
                [ 'RTP & 品質',   '96.2% 平均',                   kasino_get( $d_top, 'score_rtp' ) ?: '4.9' ],
                [ '日本市場対応', 'JCB · PayPay 対応',            kasino_get( $d_top, 'score_jp' ) ?: '5.0' ],
              ];
              foreach ( $axes as $ax ) :
              ?>
              <tr>
                <td class="k"><?php echo esc_html( $ax[0] ); ?></td>
                <td class="v"><?php echo esc_html( $ax[1] ); ?></td>
                <td class="sc"><?php echo esc_html( $ax[2] ); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <?php if ( $note = kasino_get( $d_top, 'editor_note' ) ) : ?>
          <div class="hero-editor-note">
            <div class="note-label">EDITOR'S NOTE · 葵</div>
            「<?php echo esc_html( $note ); ?>」
          </div>
          <?php endif; ?>
        </aside>
      </div>
    </section>

    <!-- Desktop: Rank #2–#5 grid -->
    <?php kasino_d_section_header( '第二位〜第五位', 'RANKED 2–5 · TOP TIER', home_url( '/casino/' ), 'ランキング全文' ); ?>
    <div class="rank-grid-4">
      <?php
      $kanji_num = [ '', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十' ];
      foreach ( array_slice( $casinos, 1, 4 ) as $cid ) :
        $rank   = (int) kasino_get( $cid, 'rank' );
        $hue    = kasino_get( $cid, 'hue' ) ?: '#3A352E';
        $badge  = kasino_get( $cid, 'badge' ) ?: '良';
        $score  = kasino_get( $cid, 'score' ) ?: '4.0';
        $bonus  = kasino_get( $cid, 'bonus' );
        $payout = kasino_get( $cid, 'payout' );
        $license = kasino_get( $cid, 'license' );
        $tag_jp = kasino_get( $cid, 'tag_jp' ) ?: 'おすすめ';
        $k = $kanji_num[ $rank ] ?? $rank;
        $rank_jp = "第{$k}位 · No.{$rank}";
      ?>
      <article class="rank-card">
        <div class="rank-card-hero" style="background:<?php echo esc_attr( $hue ); ?>;">
          <div class="rank-card-hero-top">
            <span class="rank-card-badge-pill"><?php echo esc_html( $rank_jp ); ?></span>
            <span class="rank-card-hanko"><?php echo esc_html( $badge ); ?></span>
          </div>
          <div>
            <div class="rank-card-jp"><?php echo esc_html( kasino_get( $cid, 'name_jp' ) ?: get_the_title( $cid ) ); ?></div>
            <div class="rank-card-en"><?php echo esc_html( strtoupper( get_the_title( $cid ) ) ); ?></div>
          </div>
        </div>
        <div class="rank-card-body">
          <div class="rank-card-score-row">
            <div class="rank-card-score"><?php echo esc_html( $score ); ?><small>/5</small></div>
            <span class="d-tag gold"><?php echo esc_html( $tag_jp ); ?></span>
          </div>
          <div class="rank-card-facts">
            <?php foreach ( [ [ 'ボーナス', $bonus ], [ '出金', $payout ], [ 'ライセンス', $license ] ] as $f ) : ?>
            <div class="fact-row">
              <span class="fact-label"><?php echo esc_html( $f[0] ); ?></span>
              <span class="fact-value"><?php echo esc_html( $f[1] ); ?></span>
            </div>
            <?php endforeach; ?>
          </div>
          <a href="<?php echo esc_url( get_permalink( $cid ) ); ?>"
             class="rank-card-cta"
             title="<?php echo esc_attr( get_the_title( $cid ) ); ?> の鑑定書を読む">
            鑑定書を読む →
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <!-- Editor's Picks -->
    <?php if ( ! empty( $articles ) ) : ?>
    <div style="margin-top:40px;">
      <section class="editor-picks" aria-label="編集部おすすめ記事">
        <div class="editor-picks-left">
          <div class="editor-picks-kicker">EDITOR'S PICKS · 今週の</div>
          <h2 class="editor-picks-title">編集部が<em>今読むべき</em>と判断した記事</h2>
          <p class="editor-picks-desc">業界変動・出金フロー改善・新ライセンス制度。週次で更新。</p>
        </div>
        <div class="editor-picks-grid">
          <?php foreach ( array_slice( $articles, 0, 3 ) as $idx => $aid ) :
            $cats     = get_the_category( $aid );
            $cat_name = $cats ? $cats[0]->name : 'ガイド';
            $num      = str_pad( $idx + 1, 2, '0', STR_PAD_LEFT );
            $author   = get_the_author_meta( 'display_name', get_post_field( 'post_author', $aid ) );
            $read_t   = kasino_read_time( $aid );
          ?>
          <a href="<?php echo esc_url( get_permalink( $aid ) ); ?>"
             class="editor-pick"
             style="text-decoration:none;"
             title="<?php echo esc_attr( get_the_title( $aid ) ); ?>">
            <div class="editor-pick-num">No.<?php echo esc_html( $num ); ?><em><?php echo esc_html( strtoupper( $cat_name ) ); ?></em></div>
            <h3 class="editor-pick-title"><?php echo esc_html( get_the_title( $aid ) ); ?></h3>
            <div class="editor-pick-meta">
              <span><?php echo esc_html( $cat_name ); ?></span>
              <span>·</span>
              <span><?php echo esc_html( $read_t ); ?>分</span>
              <span>·</span>
              <span>by <?php echo esc_html( $author ); ?></span>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </section>
    </div>
    <?php endif; ?>

    <!-- Top 10 table (#6–#10) -->
    <?php if ( count( $casinos ) > 5 ) : ?>
    <?php kasino_d_section_header( '第六位〜第十位', 'RANKED 6–10 · STANDARD TIER', home_url( '/casino/' ), '採点根拠を見る' ); ?>
    <div class="rank-table" role="table" aria-label="カジノランキング第六〜第十位">
      <div class="rank-row head" role="row">
        <div role="columnheader">順位</div>
        <div role="columnheader">カジノ名</div>
        <div role="columnheader">ボーナス</div>
        <div role="columnheader">出金</div>
        <div role="columnheader">ライセンス</div>
        <div role="columnheader">得意</div>
        <div role="columnheader" style="text-align:center;">鑑定スコア</div>
      </div>
      <?php foreach ( array_slice( $casinos, 5, 5 ) as $cid ) :
        $rank    = (int) kasino_get( $cid, 'rank' );
        $score   = kasino_get( $cid, 'score' );
        $bonus   = kasino_get( $cid, 'bonus' );
        $payout  = kasino_get( $cid, 'payout' );
        $license = kasino_get( $cid, 'license' );
        $tag_jp  = kasino_get( $cid, 'tag_jp' );
      ?>
      <div class="rank-row" role="row">
        <div class="rank-row-num" role="cell"><?php echo esc_html( $rank ); ?><em>位</em></div>
        <div class="rank-row-name" role="cell">
          <a href="<?php echo esc_url( get_permalink( $cid ) ); ?>"
             style="color:var(--sumi);text-decoration:none;"
             title="<?php echo esc_attr( get_the_title( $cid ) ); ?> レビュー">
            <?php echo esc_html( kasino_get( $cid, 'name_jp' ) ?: get_the_title( $cid ) ); ?>
          </a>
          <span class="en"><?php echo esc_html( get_the_title( $cid ) ); ?></span>
        </div>
        <div class="rank-row-val" role="cell"><small>BONUS</small><?php echo esc_html( $bonus ); ?></div>
        <div class="rank-row-val" role="cell"><small>PAYOUT</small><?php echo esc_html( $payout ); ?></div>
        <div class="rank-row-val" role="cell"><small>LICENSE</small><?php echo esc_html( $license ); ?></div>
        <div role="cell"><span class="d-tag"><?php echo esc_html( $tag_jp ); ?></span></div>
        <div role="cell" style="display:flex;align-items:center;gap:10px;justify-content:flex-end;">
          <div class="rank-row-score"><small>SCORE</small><?php echo esc_html( $score ); ?></div>
          <a href="<?php echo esc_url( get_permalink( $cid ) ); ?>"
             class="rank-row-btn-mini"
             title="<?php echo esc_attr( get_the_title( $cid ) ); ?> の詳細">詳細 →</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php else : // No casino data — admin notice ?>
    <div class="bento" style="text-align:center;padding:40px;margin:32px 0;">
      <div style="font-family:var(--font-serif);font-size:48px;color:var(--shu);margin-bottom:16px;">鑑</div>
      <h2 style="font-family:var(--font-serif);font-size:22px;color:var(--sumi);margin:0 0 12px;">カジノデータを追加してください</h2>
      <p style="color:var(--ink-soft);max-width:480px;margin:0 auto 20px;">
        管理画面の「カジノ」メニューから最初のカジノレビューを追加してください。<br>
        ランキング・ヒーロー・比較表が自動的に生成されます。
      </p>
      <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=casino' ) ); ?>"
         class="btn primary">カジノを追加する →</a>
    </div>
    <?php endif; ?>

    <!-- Methodology + Newsletter -->
    <div style="display:grid;grid-template-columns:1fr 1.6fr;gap:24px;margin-top:36px;">
      <!-- Methodology -->
      <aside class="methodology" aria-label="鑑定基準">
        <div class="methodology-hd">
          <div class="methodology-icon">基</div>
          <div class="methodology-title">鑑定基準<em>METHODOLOGY · 5 AXES</em></div>
        </div>
        <ul class="methodology-list">
          <?php
          $method = [
            [ '壱', 'ライセンス & 信頼性', '30%', 'License' ],
            [ '弐', '出金速度 & 透明性',   '25%', 'Payouts' ],
            [ '参', 'サポート品質',         '20%', 'Support' ],
            [ '肆', 'RTP & ゲーム品質',     '15%', 'RTP' ],
            [ '伍', '日本市場対応',         '10%', 'JP fit' ],
          ];
          foreach ( $method as $m ) :
          ?>
          <li class="methodology-item">
            <span class="methodology-num"><?php echo esc_html( $m[0] ); ?></span>
            <span><?php echo esc_html( $m[1] ); ?><br><small style="color:var(--ink-mute);font-size:10px;letter-spacing:0.16em;"><?php echo esc_html( $m[3] ); ?></small></span>
            <span class="methodology-weight"><?php echo esc_html( $m[2] ); ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="<?php echo esc_url( home_url( '/methodology/' ) ); ?>"
           class="methodology-cta"
           title="鑑定基準の全文を読む">鑑定基準の全文を読む →</a>
      </aside>

      <!-- Newsletter -->
      <div class="newsletter" aria-label="ニュースレター登録">
        <div>
          <h3>編集部だより · Editor's Dispatch</h3>
          <p>月2回、編集部が今月の鑑定結果と業界動向を整理してお届けします。広告のないニュースレター。</p>
        </div>
        <div>
          <?php if ( function_exists( 'mc4wp_show_form' ) ) : ?>
            <?php mc4wp_show_form(); ?>
          <?php else : ?>
          <form class="newsletter-form" method="post" action="#" aria-label="ニュースレター登録フォーム">
            <?php wp_nonce_field( 'kasino_newsletter', 'newsletter_nonce' ); ?>
            <input type="email"
                   name="email"
                   placeholder="メールアドレス · your@email.jp"
                   required
                   autocomplete="email"
                   aria-label="メールアドレス">
            <button type="submit">購読する</button>
          </form>
          <p class="newsletter-note">登録は無料・いつでも解除可。20歳以上に限ります。</p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Desktop kanji divider -->
    <div class="kanji-divider" style="margin:36px 0 16px;"><span>真 ・ 公 ・ 信</span></div>
    <p style="text-align:center;font-size:11px;color:var(--ink-mute);margin-bottom:0;">
      20歳未満のご利用は禁止 · 責任あるギャンブルを · GamCare: 0570-018-018
    </p>

  </div><!-- .desktop-home -->

</div><!-- .home-wrap -->

<?php get_footer();
