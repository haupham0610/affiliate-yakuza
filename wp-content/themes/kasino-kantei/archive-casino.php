<?php
/**
 * KASINO 鑑定 — Casino Archive (Rankings page)
 * URL: /casino/
 *
 * @package kasino-kantei
 */

get_header();

$paged = max( 1, get_query_var( 'paged' ) );

$casino_query = new WP_Query([
    'post_type'      => 'casino',
    'posts_per_page' => 10,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'meta_key'       => '_casino_rank',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
]);
?>

<div class="container" style="padding-top:28px;">

  <!-- Archive header -->
  <div class="archive-header">
    <div class="d-kicker">
      <span class="bar" aria-hidden="true"></span>
      <span>RANKINGS · 鑑定ランキング</span>
      <span class="bar" aria-hidden="true"></span>
    </div>
    <h1 class="archive-title">
      オンラインカジノ
      <em style="font-style:normal;color:var(--shu);">ランキング</em>
    </h1>
    <p class="archive-desc">
      <?php echo esc_html( date_i18n( 'Y年n月' ) ); ?>最新版 — ライセンス・出金速度・サポート品質・RTPの4軸で独立評価した<?php echo esc_html( $casino_query->found_posts ); ?>サイトの総合順位。
    </p>
  </div>

  <?php if ( $casino_query->have_posts() ) : ?>

  <!-- Desktop rank table -->
  <div class="rank-table" role="table" aria-label="カジノランキング一覧">
    <div class="rank-row head" role="row">
      <div role="columnheader">順位</div>
      <div role="columnheader">カジノ名</div>
      <div role="columnheader">ボーナス</div>
      <div role="columnheader">出金速度</div>
      <div role="columnheader">ライセンス</div>
      <div role="columnheader">特長</div>
      <div role="columnheader" style="text-align:center;">鑑定スコア</div>
    </div>

    <?php while ( $casino_query->have_posts() ) :
      $casino_query->the_post();
      $pid = get_the_ID();
      $rank    = (int) kasino_get( $pid, 'rank' );
      $badge   = kasino_get( $pid, 'badge' ) ?: '良';
      $score   = kasino_get( $pid, 'score' );
      $bonus   = kasino_get( $pid, 'bonus' );
      $payout  = kasino_get( $pid, 'payout' );
      $license = kasino_get( $pid, 'license' );
      $tag_jp  = kasino_get( $pid, 'tag_jp' );
      $name_jp = kasino_get( $pid, 'name_jp' );
    ?>
    <div class="rank-row" role="row" style="<?php echo $rank <= 3 ? 'background:linear-gradient(to right, rgba(201,169,97,0.05), transparent);' : ''; ?>">
      <div class="rank-row-num" role="cell">
        <?php if ( $rank <= 3 ) : ?>
          <span style="font-family:var(--font-serif);font-weight:900;font-size:28px;color:<?php echo $rank === 1 ? 'var(--gold-deep)' : ( $rank === 2 ? 'var(--ink-soft)' : 'var(--gold-2)' ); ?>;"><?php echo esc_html( $rank ); ?></span>
        <?php else : ?>
          <?php echo esc_html( $rank ); ?><em>位</em>
        <?php endif; ?>
      </div>
      <div class="rank-row-name" role="cell">
        <a href="<?php the_permalink(); ?>"
           style="color:var(--sumi);text-decoration:none;"
           title="<?php the_title_attribute(); ?> レビュー">
          <?php echo esc_html( $name_jp ?: get_the_title() ); ?>
        </a>
        <span class="en"><?php the_title(); ?></span>
      </div>
      <div class="rank-row-val" role="cell"><small>BONUS</small><?php echo esc_html( $bonus ); ?></div>
      <div class="rank-row-val" role="cell"><small>PAYOUT</small><?php echo esc_html( $payout ); ?></div>
      <div class="rank-row-val" role="cell"><small>LICENSE</small><?php echo esc_html( $license ); ?></div>
      <div role="cell"><span class="d-tag gold"><?php echo esc_html( $tag_jp ); ?></span></div>
      <div role="cell" style="display:flex;align-items:center;gap:10px;justify-content:flex-end;">
        <div class="rank-row-score"><small>SCORE</small><?php echo esc_html( $score ); ?></div>
        <a href="<?php the_permalink(); ?>"
           class="rank-row-btn-mini"
           title="<?php the_title_attribute(); ?> の鑑定書を読む">詳細 →</a>
      </div>
    </div>
    <?php endwhile;
    wp_reset_postdata(); ?>
  </div>

  <!-- Mobile card list -->
  <div class="mobile-rank-list" style="display:none;" aria-label="カジノランキング（モバイル）">
    <?php
    $casino_query->rewind_posts();
    while ( $casino_query->have_posts() ) :
      $casino_query->the_post();
      $pid     = get_the_ID();
      $rank    = (int) kasino_get( $pid, 'rank' );
      $hue     = kasino_get( $pid, 'hue' ) ?: '#3A352E';
      $score   = kasino_get( $pid, 'score' );
      $bonus   = kasino_get( $pid, 'bonus' );
      $payout  = kasino_get( $pid, 'payout' );
      $tag_jp  = kasino_get( $pid, 'tag_jp' );
      $jp_rnks = [ '', '第一位', '第二位', '第三位', '第四位', '第五位', '第六位', '第七位', '第八位', '第九位', '第十位' ];
      $rk_lbl  = $jp_rnks[ $rank ] ?? "第{$rank}位";
    ?>
    <a href="<?php the_permalink(); ?>"
       class="bento mobile-rank-row"
       title="<?php the_title_attribute(); ?> レビュー">
      <div class="mobile-rank-badge" style="background:<?php echo esc_attr( $hue ); ?>;">
        <span class="mobile-rank-badge-jp"><?php echo esc_html( $rk_lbl ); ?></span>
        <span class="mobile-rank-badge-num">RANK <?php echo str_pad( $rank, 2, '0', STR_PAD_LEFT ); ?></span>
      </div>
      <div class="mobile-rank-info">
        <div style="display:flex;align-items:center;gap:5px;margin-bottom:1px;">
          <span class="mobile-rank-name"><?php the_title(); ?></span>
          <?php if ( $tag_jp ) : ?>
          <span class="chip" style="font-size:8.5px;padding:1px 5px;"><?php echo esc_html( $tag_jp ); ?></span>
          <?php endif; ?>
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
    <?php endwhile;
    wp_reset_postdata(); ?>
  </div>

  <!-- Pagination -->
  <div class="pagination">
    <?php
    echo paginate_links([
        'total'     => $casino_query->max_num_pages,
        'current'   => $paged,
        'prev_text' => '← 前',
        'next_text' => '次 →',
        'type'      => 'list',
    ]);
    ?>
  </div>

  <?php else : ?>
  <div class="bento" style="text-align:center;padding:48px;margin:24px 0;">
    <p style="color:var(--ink-mute);font-size:15px;">現在カジノレビューはありません。</p>
    <?php if ( current_user_can( 'edit_posts' ) ) : ?>
    <a href="<?php echo esc_url( admin_url( 'post-new.php?post_type=casino' ) ); ?>"
       class="btn primary" style="margin-top:16px;">カジノを追加する →</a>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div><!-- .container -->

<style>
/* Show mobile list, hide desktop table on small screens */
@media (max-width: 767px) {
  .rank-table { display: none !important; }
  .mobile-rank-list { display: flex !important; }
}
</style>

<?php get_footer(); ?>
