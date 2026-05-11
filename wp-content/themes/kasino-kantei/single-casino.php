<?php
/**
 * KASINO 鑑定 — Single Casino Review Page
 * template: single-casino.php
 *
 * @package kasino-kantei
 */

get_header();
the_post();
$pid = get_the_ID();

// Meta
$rank    = (int) kasino_get( $pid, 'rank' );
$badge   = kasino_get( $pid, 'badge' ) ?: '良';
$hue     = kasino_get( $pid, 'hue' ) ?: 'var(--sumi)';
$score   = (float) kasino_get( $pid, 'score' );
$bonus   = kasino_get( $pid, 'bonus' );
$wager   = kasino_get( $pid, 'wager' );
$payout  = kasino_get( $pid, 'payout' );
$license = kasino_get( $pid, 'license' );
$games   = (int) kasino_get( $pid, 'games' );
$min_dep = kasino_get( $pid, 'min_deposit' );
$founded = kasino_get( $pid, 'founded' );
$currency= kasino_get( $pid, 'currency' );
$payments= array_filter( array_map( 'trim', explode( ',', kasino_get( $pid, 'payments' ) ) ) );
$pros    = array_filter( explode( "\n", kasino_get( $pid, 'pros' ) ) );
$cons    = array_filter( explode( "\n", kasino_get( $pid, 'cons' ) ) );
$name_jp = kasino_get( $pid, 'name_jp' );
$updated = kasino_get( $pid, 'updated' ) ?: get_the_modified_date( 'Y年n月' );
$review_count = (int) kasino_get( $pid, 'review_count' );
$editor_note = kasino_get( $pid, 'editor_note' );

// JP rank
$kanji_rank = [ '', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十' ];
$jp_rank_label = isset( $kanji_rank[ $rank ] ) ? "第{$kanji_rank[$rank]}位" : "第{$rank}位";

// Score axes
$score_axes = [
    [ 'ライセンス', 'License', (float) kasino_get( $pid, 'score_license' ) ?: $score ],
    [ '出金速度',   'Payout',  (float) kasino_get( $pid, 'score_payout' )  ?: $score ],
    [ 'ボーナス',   'Bonus',   (float) kasino_get( $pid, 'score_jp' )      ?: $score ],
    [ 'サポート',   'Support', (float) kasino_get( $pid, 'score_support' ) ?: $score ],
    [ 'ゲーム数',   'Games',   (float) kasino_get( $pid, 'score_rtp' )     ?: $score ],
    [ 'モバイル',   'Mobile',  (float) kasino_get( $pid, 'score_mobile' )  ?: $score ],
];
?>

<!-- Casino Hero -->
<section class="casino-hero" aria-label="<?php echo esc_attr( get_the_title() ); ?> レビュー" style="border-bottom-color:<?php echo esc_attr( $hue ); ?>;">
  <div class="casino-hero-pattern bg-seigaiha" aria-hidden="true"></div>
  <div class="container casino-hero-inner">
    <div class="casino-hero-rank">
      <span><?php echo esc_html( $jp_rank_label ); ?> · RANK <?php echo str_pad( $rank, 2, '0', STR_PAD_LEFT ); ?></span>
      <span aria-hidden="true">·</span>
      <span><?php echo esc_html( $updated ); ?>更新</span>
    </div>
    <div class="casino-hero-top">
      <div class="casino-hero-hanko" aria-label="評価: <?php echo esc_attr( $badge ); ?>"><?php echo esc_html( $badge ); ?></div>
      <div class="casino-hero-title-block">
        <h1 class="casino-title"><?php the_title(); ?></h1>
        <?php if ( $name_jp ) : ?>
        <div class="casino-subtitle"><?php echo esc_html( $name_jp ); ?></div>
        <?php endif; ?>
        <div class="casino-score-row">
          <span class="casino-score-big"><?php echo esc_html( $score ); ?></span>
          <div>
            <?php echo kasino_stars( $score ); ?>
            <?php if ( $review_count ) : ?>
            <div style="font-size:9px;opacity:0.75;"><?php echo esc_html( number_format( $review_count ) ); ?>件のレビュー</div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="casino-hero-cta-row">
      <?php echo kasino_affiliate_link( $pid, '公式サイトへ', 'btn gold' ); ?>
      <button class="btn outline"
              style="background:rgba(245,241,232,0.14);color:var(--editorial-fg);border-color:rgba(245,241,232,0.3);"
              aria-label="比較リストに追加">比較に追加 +</button>
    </div>
  </div>
</section>

<div class="container" style="padding-top:0;">

  <!-- Bonus banner -->
  <?php if ( $bonus ) : ?>
  <div class="bonus-card" style="margin-top:28px;" aria-label="ウェルカムボーナス">
    <div>
      <div class="bonus-kicker">WELCOME · 初回特典</div>
      <div class="bonus-amount"><?php echo esc_html( $bonus ); ?></div>
      <div class="bonus-wager">賭け条件 <?php echo esc_html( $wager ); ?> · 30日間有効</div>
    </div>
    <div class="bonus-circle">
      <span class="bonus-circle-label">賭</span>
      <span class="bonus-circle-val"><?php echo esc_html( $wager ); ?></span>
    </div>
  </div>
  <?php endif; ?>

  <!-- Casino layout: main + sidebar -->
  <div class="casino-layout">

    <!-- Main content -->
    <div>

      <!-- Quick facts -->
      <h2 style="font-family:var(--font-serif);font-weight:800;font-size:20px;color:var(--sumi);margin:24px 0 14px;border-bottom:2px solid var(--sumi);padding-bottom:8px;">
        基本情報 <em style="font-style:normal;font-size:11px;letter-spacing:0.22em;color:var(--ink-mute);font-weight:500;margin-left:8px;">AT A GLANCE</em>
      </h2>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:28px;">
        <?php
        $facts = [
            [ 'ライセンス', 'License', $license,              'var(--jade)' ],
            [ '出金速度',   'Payout',  $payout,               'var(--sumi)' ],
            [ 'ゲーム数',   'Games',   $games ? number_format( $games ) . '+' : '', 'var(--gold-deep)' ],
            [ '通貨',       'Currency',$currency,             'var(--indigo)' ],
            [ '最低入金',   'Min Dep', $min_dep,              'var(--ink-soft)' ],
            [ '創業',       'Founded', $founded,              'var(--ink-soft)' ],
        ];
        foreach ( $facts as $f ) :
          if ( ! $f[2] ) continue;
        ?>
        <div class="bento" style="padding:12px 10px;">
          <div style="font-size:9px;font-weight:600;color:var(--ink-mute);letter-spacing:0.18em;text-transform:uppercase;margin-bottom:4px;"><?php echo esc_html( $f[1] ); ?></div>
          <div style="font-family:var(--font-serif);font-weight:700;font-size:15px;color:<?php echo esc_attr( $f[3] ); ?>;"><?php echo esc_html( $f[2] ); ?></div>
          <div style="font-size:10px;color:var(--ink-soft);"><?php echo esc_html( $f[0] ); ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Score breakdown -->
      <h2 style="font-family:var(--font-serif);font-weight:800;font-size:20px;color:var(--sumi);margin:0 0 14px;border-bottom:2px solid var(--sumi);padding-bottom:8px;">
        鑑定スコア <em style="font-style:normal;font-size:11px;letter-spacing:0.22em;color:var(--ink-mute);font-weight:500;margin-left:8px;">SCORE BREAKDOWN</em>
      </h2>
      <div class="score-breakdown bento" style="padding:16px 20px;margin-bottom:24px;">
        <?php foreach ( $score_axes as $ax ) :
          $pct = round( ( $ax[2] / 5 ) * 100 );
        ?>
        <div class="score-axis">
          <div class="score-axis-label">
            <?php echo esc_html( $ax[0] ); ?>
            <small><?php echo esc_html( $ax[1] ); ?></small>
          </div>
          <div style="flex:1;padding:0 12px;">
            <div class="progress" style="height:5px;">
              <span style="width:<?php echo esc_attr( $pct ); ?>%;"></span>
            </div>
          </div>
          <div class="score-axis-val"><?php echo esc_html( $ax[2] ); ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Pros / Cons -->
      <?php if ( $pros || $cons ) : ?>
      <h2 style="font-family:var(--font-serif);font-weight:800;font-size:20px;color:var(--sumi);margin:0 0 14px;border-bottom:2px solid var(--sumi);padding-bottom:8px;">
        評価 <em style="font-style:normal;font-size:11px;letter-spacing:0.22em;color:var(--ink-mute);font-weight:500;margin-left:8px;">PROS & CONS</em>
      </h2>
      <div class="pros-cons bento" style="padding:16px 20px;margin-bottom:24px;">
        <div class="pros-cons-col">
          <h3 style="font-family:var(--font-serif);font-weight:700;font-size:15px;margin:0 0 12px;color:var(--sumi);">
            <span style="color:var(--jade);">✓</span> メリット
          </h3>
          <ul>
            <?php foreach ( $pros as $pro ) : ?>
            <li>
              <span class="pros-icon" aria-hidden="true">✓</span>
              <?php echo esc_html( trim( $pro ) ); ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="pros-cons-col">
          <h3 style="font-family:var(--font-serif);font-weight:700;font-size:15px;margin:0 0 12px;color:var(--sumi);">
            <span style="color:var(--shu);">✕</span> デメリット
          </h3>
          <ul>
            <?php foreach ( $cons as $con ) : ?>
            <li>
              <span class="cons-icon" aria-hidden="true">✕</span>
              <?php echo esc_html( trim( $con ) ); ?>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
      <?php endif; ?>

      <!-- Payment methods -->
      <?php if ( $payments ) : ?>
      <h2 style="font-family:var(--font-serif);font-weight:800;font-size:20px;color:var(--sumi);margin:0 0 14px;border-bottom:2px solid var(--sumi);padding-bottom:8px;">
        入出金方法 <em style="font-style:normal;font-size:11px;letter-spacing:0.22em;color:var(--ink-mute);font-weight:500;margin-left:8px;">PAYMENT METHODS</em>
      </h2>
      <div class="bento" style="padding:16px 20px;margin-bottom:24px;">
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
          <?php foreach ( $payments as $pm ) : ?>
          <span class="chip" style="font-size:11px;padding:6px 12px;">
            <span style="color:var(--jade);" aria-hidden="true">✓</span>
            <?php echo esc_html( $pm ); ?>
          </span>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Editor's note -->
      <?php if ( $editor_note ) : ?>
      <div class="bento" style="background:var(--washi-2);border-left:3px solid var(--gold-deep);padding:16px 20px;margin-bottom:24px;">
        <div style="font-size:9px;letter-spacing:0.28em;color:var(--gold-2);margin-bottom:6px;font-weight:700;">EDITOR'S NOTE · 編集長 葵</div>
        <blockquote style="font-family:var(--font-serif);font-size:15px;line-height:1.7;color:var(--ink);margin:0;font-style:normal;">
          「<?php echo esc_html( $editor_note ); ?>」
        </blockquote>
      </div>
      <?php endif; ?>

      <!-- Main review content -->
      <div class="post-body">
        <h2>
          詳細レビュー
          <em style="font-style:normal;font-size:11px;letter-spacing:0.22em;color:var(--ink-mute);font-weight:500;margin-left:8px;">FULL REVIEW</em>
        </h2>
        <?php the_content(); ?>
      </div>

    </div><!-- main -->

    <!-- Sidebar -->
    <aside class="casino-sidebar" aria-label="カジノサイドバー">

      <!-- Affiliate CTA -->
      <div class="bento" style="text-align:center;padding:20px;margin-bottom:16px;background:var(--sumi);">
        <div style="font-size:9px;letter-spacing:0.28em;color:var(--gold-deep);margin-bottom:8px;">OFFICIAL SITE · 公式サイト</div>
        <div style="font-family:var(--font-serif);font-weight:800;font-size:20px;color:var(--washi);margin-bottom:4px;"><?php echo esc_html( get_the_title() ); ?></div>
        <?php if ( $bonus ) : ?>
        <div style="font-size:12px;color:rgba(251,247,240,0.75);margin-bottom:16px;"><?php echo esc_html( $bonus ); ?></div>
        <?php endif; ?>
        <?php echo kasino_affiliate_link( $pid, '公式サイトへ ↗', 'btn gold full' ); ?>
        <div style="font-size:10px;color:rgba(251,247,240,0.5);margin-top:8px;">20歳以上 · T&C適用</div>
      </div>

      <!-- Quick score card -->
      <div class="bento" style="margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:10px;padding-bottom:12px;border-bottom:1px solid var(--line);margin-bottom:12px;">
          <span class="hanko"><?php echo esc_html( $badge ); ?></span>
          <div>
            <div style="font-size:10px;color:var(--ink-mute);letter-spacing:0.18em;">EDITORIAL SCORE</div>
            <div style="font-family:var(--font-serif);font-weight:800;font-size:28px;color:var(--gold-deep);line-height:1;"><?php echo esc_html( $score ); ?><span style="font-size:12px;color:var(--ink-mute);">/5.0</span></div>
          </div>
        </div>
        <?php foreach ( $score_axes as $ax ) :
          $pct = round( ( $ax[2] / 5 ) * 100 );
        ?>
        <div style="display:flex;align-items:center;gap:8px;padding:5px 0;font-size:11.5px;">
          <span style="flex:1;color:var(--ink-soft);"><?php echo esc_html( $ax[0] ); ?></span>
          <div class="progress" style="width:60px;height:4px;flex-shrink:0;">
            <span style="width:<?php echo esc_attr( $pct ); ?>%;"></span>
          </div>
          <span style="font-family:var(--font-serif);font-weight:700;font-size:13px;color:var(--gold-deep);min-width:28px;text-align:right;"><?php echo esc_html( $ax[2] ); ?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Related casinos -->
      <?php
      $related = new WP_Query([
          'post_type'      => 'casino',
          'posts_per_page' => 3,
          'post__not_in'   => [ $pid ],
          'meta_key'       => '_casino_rank',
          'orderby'        => 'meta_value_num',
          'order'          => 'ASC',
          'post_status'    => 'publish',
      ]);
      if ( $related->have_posts() ) :
      ?>
      <div class="bento" style="margin-bottom:16px;">
        <div style="font-family:var(--font-serif);font-weight:700;font-size:13px;color:var(--sumi);margin-bottom:12px;letter-spacing:0.04em;">関連カジノ</div>
        <?php while ( $related->have_posts() ) :
          $related->the_post();
          $r_rank = (int) kasino_get( get_the_ID(), 'rank' );
          $r_score = kasino_get( get_the_ID(), 'score' );
        ?>
        <a href="<?php the_permalink(); ?>"
           style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px dashed var(--line);text-decoration:none;color:var(--ink);"
           title="<?php the_title_attribute(); ?> レビュー">
          <span style="font-family:var(--font-serif);font-weight:800;font-size:16px;color:var(--ink-mute);min-width:24px;"><?php echo esc_html( $r_rank ); ?></span>
          <span style="flex:1;font-size:13px;font-weight:600;color:var(--sumi);"><?php the_title(); ?></span>
          <span style="font-family:var(--font-serif);font-weight:800;font-size:14px;color:var(--gold-deep);"><?php echo esc_html( $r_score ); ?></span>
        </a>
        <?php endwhile;
        wp_reset_postdata(); ?>
        <a href="<?php echo esc_url( home_url( '/casino/' ) ); ?>"
           style="display:block;margin-top:10px;font-size:11px;color:var(--sumi);font-weight:700;text-decoration:underline;text-align:center;"
           title="すべてのカジノランキング">ランキング全件を見る →</a>
      </div>
      <?php endif; ?>

      <!-- Disclaimer -->
      <div style="font-size:10px;color:var(--ink-mute);line-height:1.7;padding:12px;background:var(--washi-2);border-radius:var(--r-sm);">
        <strong style="color:var(--shu);">20歳未満のご利用は禁止。</strong>
        ギャンブルは依存性があります。余剰資金で計画的に。
        <a href="<?php echo esc_url( home_url( '/responsible-gambling/' ) ); ?>" style="color:var(--sumi);">責任あるプレイ</a>
      </div>

      <?php if ( is_active_sidebar( 'casino-sidebar' ) ) : ?>
        <div style="margin-top:16px;">
          <?php dynamic_sidebar( 'casino-sidebar' ); ?>
        </div>
      <?php endif; ?>

    </aside>
  </div><!-- .casino-layout -->

</div><!-- .container -->

<?php get_footer(); ?>
