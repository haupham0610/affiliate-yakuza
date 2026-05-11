<?php
/**
 * KASINO 鑑定 — 404 Not Found
 *
 * @package kasino-kantei
 */

get_header();
?>
<div class="container" style="padding:80px 24px;text-align:center;">
  <div style="font-family:var(--font-serif);font-size:96px;color:var(--shu);line-height:1;margin-bottom:16px;" aria-hidden="true">鑑</div>
  <h1 style="font-family:var(--font-serif);font-weight:900;font-size:32px;color:var(--sumi);margin:0 0 12px;">
    ページが見つかりません
  </h1>
  <p style="color:var(--ink-soft);max-width:480px;margin:0 auto 28px;font-size:15px;line-height:1.7;">
    お探しのページは移動・削除されたか、URLが間違っている可能性があります。<br>
    トップページまたはカジノランキングをご覧ください。
  </p>
  <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
    <a href="<?php echo esc_url( home_url('/') ); ?>" class="btn primary" title="トップページへ">トップページへ →</a>
    <a href="<?php echo esc_url( home_url('/casino/') ); ?>" class="btn outline" title="カジノランキング">カジノランキング</a>
  </div>
</div>
<?php get_footer(); ?>
