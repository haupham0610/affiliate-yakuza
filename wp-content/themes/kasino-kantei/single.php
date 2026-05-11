<?php
/**
 * KASINO 鑑定 — Single Post (Article/Guide)
 *
 * @package kasino-kantei
 */

get_header();
the_post();
$pid       = get_the_ID();
$cats      = get_the_category();
$cat_name  = $cats ? $cats[0]->name : '';
$read_t    = kasino_read_time( $pid );
$source    = get_post_meta( $pid, '_article_source', true );
?>

<!-- Article hero -->
<div style="background:linear-gradient(160deg,var(--sumi) 0%,var(--sumi-2) 100%);color:var(--editorial-fg);padding:40px 0 48px;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;color:var(--gold-deep);opacity:0.08;" class="bg-seigaiha" aria-hidden="true"></div>
  <div class="container" style="position:relative;">
    <!-- Breadcrumb -->
    <nav aria-label="パンくずリスト" style="margin-bottom:14px;font-size:11px;color:rgba(251,247,240,0.55);letter-spacing:0.1em;">
      <a href="<?php echo esc_url( home_url('/') ); ?>" style="color:inherit;text-decoration:none;" title="ホーム">ホーム</a>
      <?php if ( $cat_name && $cats ) : ?>
      <span aria-hidden="true"> › </span>
      <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"
         style="color:inherit;text-decoration:none;"
         title="<?php echo esc_attr( $cat_name ); ?>"><?php echo esc_html( $cat_name ); ?></a>
      <?php endif; ?>
      <span aria-hidden="true"> › </span>
      <span aria-current="page"><?php echo esc_html( wp_trim_words( get_the_title(), 6 ) ); ?></span>
    </nav>

    <div class="d-kicker">
      <span class="bar" aria-hidden="true"></span>
      <span><?php echo esc_html( strtoupper( $cat_name ?: 'EDITORIAL' ) ); ?></span>
      <span class="bar" aria-hidden="true"></span>
    </div>

    <h1 style="font-family:var(--font-serif);font-weight:900;font-size:clamp(22px,4vw,40px);line-height:1.25;color:var(--editorial-fg);margin:10px 0 16px;max-width:820px;letter-spacing:0.02em;" itemprop="headline">
      <?php the_title(); ?>
    </h1>

    <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;font-size:12px;color:rgba(251,247,240,0.65);">
      <div style="display:flex;align-items:center;gap:8px;" itemprop="author" itemscope itemtype="https://schema.org/Person">
        <div style="width:28px;height:28px;border-radius:50%;background:var(--gold-deep);color:var(--sumi);display:flex;align-items:center;justify-content:center;font-family:var(--font-serif);font-weight:800;font-size:13px;flex-shrink:0;" aria-hidden="true">
          <?php echo esc_html( mb_substr( get_the_author(), 0, 1 ) ); ?>
        </div>
        <span itemprop="name"><?php the_author(); ?></span>
      </div>
      <time datetime="<?php echo esc_attr( get_the_date('c') ); ?>" itemprop="datePublished">
        <?php echo esc_html( get_the_date('Y年n月j日') ); ?>
      </time>
      <span><?php echo esc_html( $read_t ); ?>分で読める</span>
      <?php if ( $source ) : ?>
      <span>情報ソース: <?php echo esc_html( $source ); ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="container" style="padding-top:32px;padding-bottom:60px;">
  <div class="post-layout">

    <!-- Main article body -->
    <article class="post-body" itemscope itemtype="https://schema.org/Article">
      <?php if ( has_post_thumbnail() ) : ?>
      <div style="margin-bottom:28px;border-radius:var(--r-lg);overflow:hidden;">
        <?php the_post_thumbnail( 'kasino-hero', [
          'loading' => 'lazy',
          'alt'     => get_the_title(),
          'itemprop'=> 'image',
          'style'   => 'width:100%;height:auto;display:block;',
        ] ); ?>
      </div>
      <?php endif; ?>

      <div itemprop="articleBody">
        <?php the_content(); ?>
      </div>

      <!-- Tags -->
      <?php $tags = get_the_tags();
      if ( $tags ) : ?>
      <div style="margin-top:32px;padding-top:20px;border-top:1px solid var(--line);display:flex;flex-wrap:wrap;gap:6px;">
        <?php foreach ( $tags as $tag ) : ?>
        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
           class="chip"
           title="<?php echo esc_attr( $tag->name ); ?> タグ">
          #<?php echo esc_html( $tag->name ); ?>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- Author bio -->
      <div style="margin-top:32px;padding:20px;background:var(--washi-2);border-radius:var(--r-md);display:flex;gap:16px;align-items:flex-start;">
        <?php echo get_avatar( get_the_author_meta('ID'), 56, '', get_the_author(), [
          'class'     => 'rounded',
          'style'     => 'border-radius:50%;flex-shrink:0;',
          'loading'   => 'lazy',
        ] ); ?>
        <div>
          <div style="font-family:var(--font-serif);font-weight:700;font-size:14px;color:var(--sumi);margin-bottom:4px;"><?php the_author(); ?></div>
          <div style="font-size:9px;letter-spacing:0.2em;color:var(--ink-mute);margin-bottom:8px;">KASINO 鑑定 編集部</div>
          <p style="font-size:13px;color:var(--ink-soft);line-height:1.65;margin:0;"><?php echo esc_html( get_the_author_meta( 'description' ) ?: 'KASINO 鑑定 編集部メンバー。オンラインカジノの最新情報と攻略法を執筆。' ); ?></p>
        </div>
      </div>

    </article><!-- .post-body -->

    <!-- Table of contents sidebar -->
    <aside class="post-toc" aria-label="目次">
      <h5>目次 <em>TABLE OF CONTENTS</em></h5>
      <ol id="toc-list">
        <!-- JS fills this from h2/h3 in .post-body -->
        <li class="toc-loading" style="color:var(--ink-mute);font-size:12px;">読み込み中…</li>
      </ol>

      <!-- Related casinos widget -->
      <?php
      $rnd_casinos = new WP_Query([
          'post_type'      => 'casino',
          'posts_per_page' => 3,
          'post_status'    => 'publish',
          'meta_key'       => '_casino_rank',
          'orderby'        => 'meta_value_num',
          'order'          => 'ASC',
      ]);
      if ( $rnd_casinos->have_posts() ) :
      ?>
      <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--line);">
        <div style="font-family:var(--font-serif);font-weight:700;font-size:12px;color:var(--sumi);margin-bottom:10px;letter-spacing:0.08em;">おすすめカジノ</div>
        <?php while ( $rnd_casinos->have_posts() ) :
          $rnd_casinos->the_post();
          $r_rank  = kasino_get( get_the_ID(), 'rank' );
          $r_score = kasino_get( get_the_ID(), 'score' );
        ?>
        <a href="<?php the_permalink(); ?>"
           style="display:flex;align-items:center;gap:8px;padding:7px 0;border-bottom:1px dashed var(--line);text-decoration:none;color:var(--ink);"
           title="<?php the_title_attribute(); ?> レビュー">
          <span style="font-family:var(--font-serif);font-weight:800;font-size:13px;color:var(--ink-mute);min-width:18px;"><?php echo esc_html( $r_rank ); ?></span>
          <span style="flex:1;font-size:12px;color:var(--sumi);font-weight:600;"><?php the_title(); ?></span>
          <span style="font-family:var(--font-serif);font-weight:800;font-size:13px;color:var(--gold-deep);"><?php echo esc_html( $r_score ); ?></span>
        </a>
        <?php endwhile;
        wp_reset_postdata(); ?>
        <a href="<?php echo esc_url( home_url('/casino/') ); ?>"
           style="display:block;margin-top:10px;font-size:11px;color:var(--sumi);font-weight:700;text-align:center;text-decoration:underline;"
           title="ランキング全件">全ランキングを見る →</a>
      </div>
      <?php endif; ?>

      <?php if ( is_active_sidebar( 'article-sidebar' ) ) : ?>
        <div style="margin-top:16px;">
          <?php dynamic_sidebar( 'article-sidebar' ); ?>
        </div>
      <?php endif; ?>
    </aside>

  </div><!-- .post-layout -->

  <!-- Related articles -->
  <?php
  $related_cats = wp_list_pluck( get_the_category(), 'term_id' );
  $related = new WP_Query([
      'post_type'           => 'post',
      'posts_per_page'      => 3,
      'post__not_in'        => [ get_the_ID() ],
      'category__in'        => $related_cats,
      'orderby'             => 'date',
      'order'               => 'DESC',
      'post_status'         => 'publish',
      'ignore_sticky_posts' => 1,
  ]);
  if ( $related->have_posts() ) :
  ?>
  <div style="margin-top:48px;">
    <?php kasino_d_section_header( '関連記事', 'RELATED ARTICLES', home_url('/news/'), '記事一覧' ); ?>
    <div class="blog-grid">
      <?php while ( $related->have_posts() ) :
        $related->the_post();
        $r_cats   = get_the_category();
        $r_cat    = $r_cats ? $r_cats[0]->name : '';
        $r_read   = kasino_read_time( get_the_ID() );
      ?>
      <a href="<?php the_permalink(); ?>" class="blog-card" title="<?php the_title_attribute(); ?>">
        <div class="blog-card-thumb">
          <?php if ( has_post_thumbnail() ) :
            the_post_thumbnail( 'kasino-card', [ 'loading' => 'lazy', 'alt' => get_the_title() ] );
          else : ?>
          <div style="width:100%;min-height:180px;background:var(--washi-2);display:flex;align-items:center;justify-content:center;">
            <span style="font-family:var(--font-serif);font-size:40px;color:var(--line);">鑑</span>
          </div>
          <?php endif; ?>
        </div>
        <div class="blog-card-body">
          <?php if ( $r_cat ) : ?><span class="chip blog-card-cat"><?php echo esc_html( $r_cat ); ?></span><?php endif; ?>
          <h3 class="blog-card-title"><?php the_title(); ?></h3>
          <div class="blog-card-meta">
            <span><?php the_author(); ?></span> · <span><?php echo esc_html( $r_read ); ?>分</span>
          </div>
        </div>
      </a>
      <?php endwhile;
      wp_reset_postdata(); ?>
    </div>
  </div>
  <?php endif; ?>

</div><!-- .container -->

<?php get_footer(); ?>
