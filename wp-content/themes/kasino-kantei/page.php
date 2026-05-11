<?php
/**
 * KASINO 鑑定 — Generic Page Template
 *
 * @package kasino-kantei
 */

get_header();
the_post();
?>

<div class="container" style="padding-top:32px;padding-bottom:80px;">
  <div class="post-layout">
    <article class="post-body">
      <h1 style="font-family:var(--font-serif);font-weight:900;font-size:clamp(22px,3.5vw,38px);color:var(--sumi);margin:0 0 24px;border-bottom:2px solid var(--sumi);padding-bottom:12px;">
        <?php the_title(); ?>
      </h1>
      <?php if ( has_post_thumbnail() ) : ?>
      <div style="margin-bottom:28px;border-radius:var(--r-lg);overflow:hidden;">
        <?php the_post_thumbnail( 'kasino-hero', [ 'loading' => 'lazy', 'alt' => get_the_title(), 'style' => 'width:100%;height:auto;display:block;' ] ); ?>
      </div>
      <?php endif; ?>
      <?php the_content(); ?>
    </article>
    <aside class="post-toc" aria-label="目次">
      <h5>目次 <em>CONTENTS</em></h5>
      <ol id="toc-list"><li style="color:var(--ink-mute);font-size:12px;">読み込み中…</li></ol>
    </aside>
  </div>
</div>

<?php get_footer(); ?>
