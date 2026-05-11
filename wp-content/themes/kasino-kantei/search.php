<?php
/**
 * KASINO 鑑定 — Search Results
 *
 * @package kasino-kantei
 */

get_header();
?>
<div class="container" style="padding-top:32px;padding-bottom:80px;">
  <div class="archive-header">
    <h1 class="archive-title">
      「<em style="color:var(--shu);"><?php echo esc_html( get_search_query() ); ?></em>」の検索結果
    </h1>
    <p class="archive-desc"><?php echo esc_html( $wp_query->found_posts ); ?>件が見つかりました</p>
  </div>

  <!-- Search form -->
  <form role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>"
        style="display:flex;gap:0;max-width:580px;margin-bottom:32px;" aria-label="サイト検索">
    <input type="search"
           name="s"
           value="<?php echo esc_attr( get_search_query() ); ?>"
           placeholder="カジノ・ガイドを検索…"
           aria-label="検索キーワード"
           style="flex:1;padding:13px 16px;border:1.5px solid var(--line);border-right:none;border-radius:var(--r-md) 0 0 var(--r-md);font-family:var(--font-sans);font-size:14px;color:var(--ink);background:#fff;">
    <button type="submit"
            style="padding:13px 22px;background:var(--sumi);color:var(--washi);border:none;border-radius:0 var(--r-md) var(--r-md) 0;font-weight:700;font-size:14px;font-family:var(--font-sans);cursor:pointer;"
            aria-label="検索">検索</button>
  </form>

  <?php if ( have_posts() ) : ?>
  <div class="blog-grid">
    <?php while ( have_posts() ) :
      the_post();
      $is_casino = get_post_type() === 'casino';
      $cats = get_the_category();
      $cat_name = $cats ? $cats[0]->name : ( $is_casino ? 'カジノ' : '' );
      $read_t = kasino_read_time( get_the_ID() );
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-card'); ?>>
      <a href="<?php the_permalink(); ?>"
         class="blog-card-thumb"
         tabindex="-1"
         aria-hidden="true"
         title="<?php the_title_attribute(); ?>">
        <?php if ( has_post_thumbnail() ) :
          the_post_thumbnail( 'kasino-card', [ 'loading' => 'lazy', 'alt' => get_the_title() ] );
        else : ?>
        <div style="width:100%;min-height:180px;background:var(--washi-2);display:flex;align-items:center;justify-content:center;">
          <span style="font-family:var(--font-serif);font-size:40px;color:var(--line);">鑑</span>
        </div>
        <?php endif; ?>
      </a>
      <div class="blog-card-body">
        <?php if ( $cat_name ) : ?>
        <span class="chip blog-card-cat"><?php echo esc_html( $cat_name ); ?></span>
        <?php endif; ?>
        <h2 class="blog-card-title">
          <a href="<?php the_permalink(); ?>" style="color:var(--sumi);text-decoration:none;" title="<?php the_title_attribute(); ?>">
            <?php the_title(); ?>
          </a>
        </h2>
        <p class="blog-card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
        <div class="blog-card-meta">
          <?php if ( ! $is_casino ) : ?>
          <span><?php the_author(); ?></span> ·
          <span><?php echo esc_html( $read_t ); ?>分</span>
          <?php else : ?>
          <span>スコア: <?php echo esc_html( kasino_get( get_the_ID(), 'score' ) ); ?></span>
          <?php endif; ?>
        </div>
      </div>
    </article>
    <?php endwhile; ?>
  </div>
  <div class="pagination"><?php the_posts_pagination(['prev_text'=>'← 前','next_text'=>'次 →']); ?></div>
  <?php else : ?>
  <div class="bento" style="text-align:center;padding:48px;">
    <p style="color:var(--ink-mute);">「<?php echo esc_html( get_search_query() ); ?>」に一致する結果がありません。</p>
    <p style="font-size:13px;color:var(--ink-mute);">別のキーワードをお試しください。</p>
  </div>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
