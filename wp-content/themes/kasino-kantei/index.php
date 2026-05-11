<?php
/**
 * KASINO 鑑定 — Blog index / Archive fallback
 * Shows article listing for posts
 *
 * @package kasino-kantei
 */

get_header();
$paged = max( 1, get_query_var( 'paged' ) );
?>

<div class="container" style="padding-top:28px;">

  <!-- Header -->
  <div class="archive-header">
    <?php if ( is_category() ) : ?>
      <div class="d-kicker"><span class="bar" aria-hidden="true"></span><span><?php single_cat_title(); ?></span><span class="bar" aria-hidden="true"></span></div>
      <h1 class="archive-title"><?php single_cat_title(); ?></h1>
      <?php if ( category_description() ) : ?>
      <p class="archive-desc"><?php echo wp_kses_post( category_description() ); ?></p>
      <?php endif; ?>

    <?php elseif ( is_tag() ) : ?>
      <h1 class="archive-title">タグ: <?php single_tag_title(); ?></h1>

    <?php elseif ( is_author() ) : ?>
      <h1 class="archive-title">執筆者: <?php the_author(); ?></h1>

    <?php elseif ( is_search() ) : ?>
      <h1 class="archive-title">
        「<em style="color:var(--shu);"><?php echo get_search_query(); ?></em>」の検索結果
      </h1>
      <p class="archive-desc"><?php echo esc_html( $wp_query->found_posts ); ?>件が見つかりました</p>

    <?php elseif ( is_archive() ) : ?>
      <h1 class="archive-title"><?php the_archive_title(); ?></h1>
      <?php the_archive_description( '<p class="archive-desc">', '</p>' ); ?>

    <?php else : ?>
      <div class="d-kicker"><span class="bar" aria-hidden="true"></span><span>EDITORIAL · 記事一覧</span><span class="bar" aria-hidden="true"></span></div>
      <h1 class="archive-title">最新記事・ガイド</h1>
      <p class="archive-desc">オンラインカジノの攻略・入出金・ボーナス解説から最新業界ニュースまで。編集部が毎週更新します。</p>

    <?php endif; ?>
  </div>

  <!-- Article grid -->
  <?php if ( have_posts() ) : ?>
  <div class="blog-grid">
    <?php while ( have_posts() ) :
      the_post();
      $cats     = get_the_category();
      $cat_name = $cats ? $cats[0]->name : '';
      $read_t   = kasino_read_time( get_the_ID() );
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card' ); ?> itemscope itemtype="https://schema.org/Article">
      <a href="<?php the_permalink(); ?>"
         class="blog-card-thumb"
         tabindex="-1"
         aria-hidden="true"
         title="<?php the_title_attribute(); ?>">
        <?php if ( has_post_thumbnail() ) : ?>
          <?php the_post_thumbnail( 'kasino-card', [
            'loading' => 'lazy',
            'alt'     => get_the_title(),
            'itemprop'=> 'image',
          ] ); ?>
        <?php else : ?>
          <!-- Placeholder pattern thumbnail -->
          <div style="width:100%;height:100%;background:var(--washi-2);display:flex;align-items:center;justify-content:center;min-height:180px;">
            <span style="font-family:var(--font-serif);font-size:48px;color:var(--line);">鑑</span>
          </div>
        <?php endif; ?>
      </a>
      <div class="blog-card-body">
        <?php if ( $cat_name ) : ?>
        <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"
           class="chip blog-card-cat"
           title="<?php echo esc_attr( $cat_name ); ?> カテゴリー">
          <?php echo esc_html( $cat_name ); ?>
        </a>
        <?php endif; ?>
        <h2 class="blog-card-title" itemprop="headline">
          <a href="<?php the_permalink(); ?>"
             title="<?php the_title_attribute(); ?>"
             style="color:var(--sumi);text-decoration:none;">
            <?php the_title(); ?>
          </a>
        </h2>
        <p class="blog-card-excerpt" itemprop="description"><?php echo esc_html( get_the_excerpt() ); ?></p>
        <div class="blog-card-meta" itemprop="author" itemscope itemtype="https://schema.org/Person">
          <span itemprop="name"><?php the_author(); ?></span>
          <span aria-hidden="true">·</span>
          <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" itemprop="datePublished">
            <?php echo esc_html( get_the_date( 'Y年n月j日' ) ); ?>
          </time>
          <span aria-hidden="true">·</span>
          <span><?php echo esc_html( $read_t ); ?>分で読める</span>
        </div>
      </div>
    </article>
    <?php endwhile; ?>
  </div>

  <!-- Pagination -->
  <div class="pagination">
    <?php
    echo paginate_links([
        'prev_text' => '← 前のページ',
        'next_text' => '次のページ →',
        'type'      => 'list',
    ]);
    ?>
  </div>

  <?php else : ?>
  <div class="bento" style="text-align:center;padding:48px;margin:24px 0;">
    <p style="color:var(--ink-mute);">記事が見つかりませんでした。</p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn primary" style="margin-top:16px;">トップへ戻る</a>
  </div>
  <?php endif; ?>

</div><!-- .container -->

<?php get_footer(); ?>
