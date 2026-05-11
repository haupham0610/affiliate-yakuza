<?php
/**
 * Template part: Article/blog card
 * Used by AJAX load-more
 *
 * @package kasino-kantei
 */

$cats     = get_the_category();
$cat_name = $cats ? $cats[0]->name : '';
$read_t   = kasino_read_time( get_the_ID() );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-card' ); ?>>
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
    <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"
       class="chip blog-card-cat"
       title="<?php echo esc_attr( $cat_name ); ?>">
      <?php echo esc_html( $cat_name ); ?>
    </a>
    <?php endif; ?>
    <h2 class="blog-card-title">
      <a href="<?php the_permalink(); ?>"
         style="color:var(--sumi);text-decoration:none;"
         title="<?php the_title_attribute(); ?>">
        <?php the_title(); ?>
      </a>
    </h2>
    <p class="blog-card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
    <div class="blog-card-meta">
      <span><?php the_author(); ?></span>
      <span aria-hidden="true">·</span>
      <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>">
        <?php echo esc_html( get_the_date('Y年n月j日') ); ?>
      </time>
      <span aria-hidden="true">·</span>
      <span><?php echo esc_html( $read_t ); ?>分</span>
    </div>
  </div>
</article>
