<?php
/**
 * Template part: Casino card
 * Used by AJAX load-more and archive listings
 *
 * @package kasino-kantei
 */

$pid     = get_the_ID();
$rank    = (int) kasino_get( $pid, 'rank' );
$hue     = kasino_get( $pid, 'hue' ) ?: '#3A352E';
$badge   = kasino_get( $pid, 'badge' ) ?: '良';
$score   = kasino_get( $pid, 'score' );
$bonus   = kasino_get( $pid, 'bonus' );
$payout  = kasino_get( $pid, 'payout' );
$license = kasino_get( $pid, 'license' );
$tag_jp  = kasino_get( $pid, 'tag_jp' );
$name_jp = kasino_get( $pid, 'name_jp' );
$kanji_num = [ '', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十' ];
$k = isset( $kanji_num[ $rank ] ) ? $kanji_num[ $rank ] : $rank;
$rank_label = "第{$k}位 · No.{$rank}";
?>
<article id="post-<?php the_ID(); ?>" class="rank-card">
  <div class="rank-card-hero" style="background:<?php echo esc_attr( $hue ); ?>;">
    <div class="rank-card-hero-top">
      <span class="rank-card-badge-pill"><?php echo esc_html( $rank_label ); ?></span>
      <span class="rank-card-hanko"><?php echo esc_html( $badge ); ?></span>
    </div>
    <div>
      <div class="rank-card-jp"><?php echo esc_html( $name_jp ?: get_the_title() ); ?></div>
      <div class="rank-card-en"><?php echo esc_html( strtoupper( get_the_title() ) ); ?></div>
    </div>
  </div>
  <div class="rank-card-body">
    <div class="rank-card-score-row">
      <div class="rank-card-score"><?php echo esc_html( $score ); ?><small>/5</small></div>
      <span class="d-tag gold"><?php echo esc_html( $tag_jp ); ?></span>
    </div>
    <div class="rank-card-facts">
      <?php foreach ( [ [ 'ボーナス', $bonus ], [ '出金', $payout ], [ 'ライセンス', $license ] ] as $f ) :
        if ( ! $f[1] ) continue;
      ?>
      <div class="fact-row">
        <span class="fact-label"><?php echo esc_html( $f[0] ); ?></span>
        <span class="fact-value"><?php echo esc_html( $f[1] ); ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <a href="<?php the_permalink(); ?>"
       class="rank-card-cta"
       title="<?php the_title_attribute(); ?> の鑑定書を読む">鑑定書を読む →</a>
  </div>
</article>
