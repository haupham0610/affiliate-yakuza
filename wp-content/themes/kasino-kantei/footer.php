</main><!-- #main-content -->

<!-- ══════════════════════════════════════════════════════════
     MOBILE BOTTOM TAB BAR (visible <1024px) — 5 tabs, active = shu
══════════════════════════════════════════════════════════ -->
<nav class="mobile-tabbar" aria-label="モバイルタブバー" role="navigation">
  <?php
  $tabs = [
    [
      'url'     => home_url( '/' ),
      'label'   => kasino_t( 'tab_home' ),
      'label_t' => 'tab_home',
      'id'      => 'home',
      'svg'     => '<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path d="M9 22V12h6v10"/>',
    ],
    [
      'url'     => home_url( '/casino/' ),
      'label'   => kasino_t( 'tab_rank' ),
      'label_t' => 'tab_rank',
      'id'      => 'rank',
      'svg'     => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>',
    ],
    [
      'url'     => home_url( '/casino-cat/compare/' ),
      'label'   => kasino_t( 'tab_compare' ),
      'label_t' => 'tab_compare',
      'id'      => 'compare',
      'svg'     => '<path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-4"/>',
    ],
    [
      'url'     => home_url( '/guide/' ),
      'label'   => kasino_t( 'tab_guide' ),
      'label_t' => 'tab_guide',
      'id'      => 'guide',
      'svg'     => '<path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>',
    ],
    [
      'url'     => is_user_logged_in() ? get_author_posts_url( get_current_user_id() ) : wp_login_url(),
      'label'   => kasino_t( 'tab_me' ),
      'label_t' => 'tab_me',
      'id'      => 'me',
      'svg'     => '<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    ],
  ];
  foreach ( $tabs as $tab ) :
    $is_active = ( is_front_page() && 'home' === $tab['id'] )
              || ( get_post_type() === 'casino' && 'rank' === $tab['id'] )
              || ( is_page() && is_page( 'guide' ) && 'guide' === $tab['id'] )
              || ( is_page() && is_page( 'compare' ) && 'compare' === $tab['id'] );
  ?>
  <a href="<?php echo esc_url( $tab['url'] ); ?>"
     <?php if ( $is_active ) : ?>class="active" aria-current="page"<?php endif; ?>
     title="<?php echo esc_attr( $tab['label'] ); ?>"
     aria-label="<?php echo esc_attr( $tab['label'] ); ?>">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <?php echo $tab['svg']; // phpcs:ignore — SVG, no user input ?>
    </svg>
    <span class="tab-label-jp" data-t="<?php echo esc_attr( $tab['label_t'] ); ?>"><?php echo esc_html( $tab['label'] ); ?></span>
  </a>
  <?php endforeach; ?>
</nav>

<!-- ══════════════════════════════════════════════════════════
     SITE FOOTER
══════════════════════════════════════════════════════════ -->
<footer class="site-footer" role="contentinfo" aria-label="サイトフッター">
  <div class="container">
    <div class="footer-grid">

      <!-- Brand + Mission -->
      <div class="footer-brand footer-col">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="site-wordmark"
           title="KASINO 鑑定 トップページ"
           style="text-decoration:none; justify-content:flex-start;">
          KASINO<em>鑑定</em>
        </a>
        <p class="footer-mission">
          ライセンス・出金速度・サポート品質・RTPの4軸で多角的に鑑定する、編集部独立運営のオンラインカジノ専門レビュー。広告主の影響を一切受けません。
        </p>
        <div class="footer-license-row">
          <div class="footer-license-badge">
            <div class="footer-license-icon">監</div>
            <div>
              <div class="footer-license-title">第三者監査済 · GLI 認定アナリスト</div>
              <div class="footer-license-en">INDEPENDENT EDITORIAL AUDIT</div>
            </div>
          </div>
          <div class="footer-license-badge">
            <div class="footer-license-icon">RG</div>
            <div>
              <div class="footer-license-title">責任あるプレイ推進 · GamCare 提携</div>
              <div class="footer-license-en">RESPONSIBLE GAMING PARTNER</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Ratings -->
      <div class="footer-col">
        <h4>鑑定<em>RATINGS</em></h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/casino/' ) ); ?>" title="総合ランキング">総合ランキング Top 10</a></li>
          <li><a href="<?php echo esc_url( home_url( '/casino-cat/' ) ); ?>" title="カテゴリー別">カテゴリー別ランキング</a></li>
          <li><a href="<?php echo esc_url( home_url( '/casino-tag/新登場/' ) ); ?>" title="新着">新着オンラインカジノ</a></li>
          <li><a href="<?php echo esc_url( home_url( '/casino-tag/殿堂/' ) ); ?>" title="殿堂入り">編集部殿堂入り</a></li>
          <li><a href="<?php echo esc_url( home_url( '/casino-cat/compare/' ) ); ?>" title="比較">比較する · Compare</a></li>
          <li><a href="<?php echo esc_url( home_url( '/methodology/' ) ); ?>" title="鑑定基準">鑑定基準 · Methodology</a></li>
        </ul>
      </div>

      <!-- Guides -->
      <div class="footer-col">
        <h4>ガイド<em>GUIDES</em></h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/category/beginners/' ) ); ?>" title="入門">入門 · Beginners</a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/strategy/' ) ); ?>" title="戦術">戦術 · Strategy</a></li>
          <li><a href="<?php echo esc_url( home_url( '/glossary/' ) ); ?>" title="用語集">用語集 · Glossary</a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/bonuses/' ) ); ?>" title="ボーナス">ボーナス解説</a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/payment/' ) ); ?>" title="出金">出金フロー</a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/safety/' ) ); ?>" title="ライセンス">ライセンスとは</a></li>
        </ul>
      </div>

      <!-- Editorial -->
      <div class="footer-col">
        <h4>記事<em>EDITORIAL</em></h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" title="編集部だより">編集部だより</a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>" title="業界ニュース">業界ニュース</a></li>
          <li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" title="市場リポート">市場リポート</a></li>
          <li><a href="<?php echo esc_url( home_url( '/category/news/' ) ); ?>" title="RG特集">RG 特集</a></li>
        </ul>
      </div>

      <!-- About -->
      <div class="footer-col">
        <h4>編集部について<em>ABOUT</em></h4>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/editorial-policy/' ) ); ?>" title="編集ポリシー">編集ポリシー</a></li>
          <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" title="編集者紹介">監修者・編集者紹介</a></li>
          <li><a href="<?php echo esc_url( home_url( '/methodology/' ) ); ?>" title="採点基準">採点基準の詳細</a></li>
          <li><a href="<?php echo esc_url( home_url( '/advertising/' ) ); ?>" title="広告掲載">広告掲載について</a></li>
          <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" title="お問い合わせ">お問い合わせ</a></li>
        </ul>
      </div>

    </div><!-- .footer-grid -->
  </div><!-- .container -->

  <!-- Disclaimer -->
  <div class="footer-disclaimer-wrap">
    <div class="container">
      <p class="footer-disclaimer">
        <strong>20歳未満の方はオンラインカジノをご利用いただけません。</strong>
        ギャンブルは依存性のある娯楽です。賭けは余剰資金の範囲で計画的に。コントロールが難しいと感じたら、ギャンブル等依存症対策センター（0570-018-018）または GamCare までご相談ください。
      </p>
    </div>
  </div>

  <!-- Bottom bar -->
  <div class="container">
    <div class="footer-bottom">
      <span>© <?php echo esc_html( date( 'Y' ) ); ?> KASINO 鑑定 編集部. All Rights Reserved. 当サイトは独立運営のレビューメディアです。</span>
      <nav class="footer-bottom-links" aria-label="フッターリンク">
        <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>" title="利用規約">利用規約</a>
        <a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>" title="プライバシーポリシー">プライバシー</a>
        <a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>" title="クッキーポリシー">クッキー</a>
        <a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>" title="免責事項">免責事項</a>
        <a href="<?php echo esc_url( home_url( '/sitemap.xml' ) ); ?>" title="サイトマップ">サイトマップ</a>
      </nav>
    </div>
  </div>

</footer><!-- .site-footer -->

<?php wp_footer(); ?>
</body>
</html>
