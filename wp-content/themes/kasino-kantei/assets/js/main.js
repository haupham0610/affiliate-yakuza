/**
 * KASINO 鑑定 — main.js
 * Vanilla JS only. No jQuery dependency.
 * Features: mobile nav drawer, TOC generator, lazy load polyfill, load-more AJAX, search shortcut
 */

'use strict';

(function () {

  // ── Mobile nav drawer ──────────────────────────────────────────
  const openBtn  = document.getElementById('mobile-menu-btn');
  const closeBtn = document.getElementById('mobile-nav-close');
  const drawer   = document.getElementById('mobile-nav-drawer');

  function openDrawer() {
    if (!drawer) return;
    drawer.style.display = 'block';
    document.body.style.overflow = 'hidden';
    if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
    closeBtn && closeBtn.focus();
  }

  function closeDrawer() {
    if (!drawer) return;
    drawer.style.display = 'none';
    document.body.style.overflow = '';
    if (openBtn) {
      openBtn.setAttribute('aria-expanded', 'false');
      openBtn.focus();
    }
  }

  openBtn  && openBtn.addEventListener('click', openDrawer);
  closeBtn && closeBtn.addEventListener('click', closeDrawer);

  // Close on backdrop click
  drawer && drawer.addEventListener('click', function (e) {
    if (e.target === drawer) closeDrawer();
  });

  // Close on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer && drawer.style.display === 'block') closeDrawer();
  });


  // ── Table of contents generator ────────────────────────────────
  function buildTOC() {
    const tocList  = document.getElementById('toc-list');
    const postBody = document.querySelector('.post-body');
    if (!tocList || !postBody) return;

    const headings = postBody.querySelectorAll('h2, h3');
    if (!headings.length) {
      tocList.innerHTML = '<li style="color:var(--ink-mute);font-size:12px;">目次なし</li>';
      return;
    }

    tocList.innerHTML = '';
    headings.forEach(function (h, i) {
      if (!h.id) h.id = 'toc-' + i;
      const li = document.createElement('li');
      li.style.cssText = h.tagName === 'H3' ? 'padding-left:16px;' : '';
      const a = document.createElement('a');
      a.href = '#' + h.id;
      a.textContent = h.textContent.replace(/[#→]/g, '').trim();
      a.style.cssText = 'text-decoration:none;color:inherit;display:block;';
      li.appendChild(a);
      tocList.appendChild(li);
    });

    // Highlight current section on scroll
    const observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            tocList.querySelectorAll('li').forEach(function (li) { li.classList.remove('on'); });
            const active = tocList.querySelector('a[href="#' + entry.target.id + '"]');
            if (active) active.parentElement.classList.add('on');
          }
        });
      },
      { rootMargin: '-20% 0px -70% 0px' }
    );
    headings.forEach(function (h) { observer.observe(h); });
  }


  // ── Sticky nav shrink on scroll ────────────────────────────────
  function initStickyNav() {
    const nav = document.querySelector('.site-nav');
    if (!nav) return;
    let lastY = 0;
    window.addEventListener('scroll', function () {
      const y = window.scrollY;
      if (y > 80) {
        nav.style.boxShadow = '0 2px 8px rgba(28,28,30,0.08)';
      } else {
        nav.style.boxShadow = '';
      }
      lastY = y;
    }, { passive: true });
  }


  // ── Mobile search button ────────────────────────────────────────
  const mobileSearchBtn = document.getElementById('mobile-search-btn');
  if (mobileSearchBtn) {
    mobileSearchBtn.addEventListener('click', function () {
      const searchInput = document.querySelector('input[type="search"], input[name="s"]');
      if (searchInput) {
        searchInput.focus();
        searchInput.select();
      } else {
        const baseUrl = window.KASINO && window.KASINO.homeUrl ? window.KASINO.homeUrl : '/';
        window.location.href = baseUrl + '?s=';
      }
    });
  }

  // ── Search shortcut ⌘K / Ctrl+K ───────────────────────────────
  document.addEventListener('keydown', function (e) {
    if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
      e.preventDefault();
      const searchInput = document.querySelector('input[type="search"], input[name="s"]');
      if (searchInput) {
        searchInput.focus();
        searchInput.select();
      } else {
        const baseUrl = window.KASINO && window.KASINO.homeUrl ? window.KASINO.homeUrl : '/';
        window.location.href = baseUrl + '?s=';
      }
    }
  });


  // ── Load more articles (AJAX) ───────────────────────────────────
  const loadMoreBtn = document.getElementById('load-more-btn');
  if (loadMoreBtn) {
    let paged = 2;
    const postType = loadMoreBtn.dataset.postType || 'post';
    const cat      = loadMoreBtn.dataset.cat || '';
    const grid     = document.getElementById('load-more-grid');

    loadMoreBtn.addEventListener('click', function () {
      const btn = this;
      btn.textContent = '読み込み中…';
      btn.disabled = true;

      const formData = new FormData();
      formData.append('action', 'kasino_load_more');
      formData.append('nonce',  window.KASINO ? window.KASINO.nonce : '');
      formData.append('paged',  paged);
      formData.append('post_type', postType);
      if (cat) formData.append('cat', cat);

      fetch(window.KASINO ? window.KASINO.ajaxUrl : '/wp-admin/admin-ajax.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin',
      })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success && data.data.html) {
          if (grid) grid.insertAdjacentHTML('beforeend', data.data.html);
          paged++;
          if (paged > data.data.max_pages) {
            btn.textContent = 'すべて読み込みました';
            btn.disabled = true;
          } else {
            btn.textContent = 'さらに読み込む';
            btn.disabled = false;
          }
        } else {
          btn.textContent = '記事はありません';
          btn.disabled = true;
        }
      })
      .catch(function () {
        btn.textContent = 'エラー — 再試行';
        btn.disabled = false;
      });
    });
  }


  // ── Lazy load polyfill (for older browsers) ─────────────────────
  if ('loading' in HTMLImageElement.prototype === false) {
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    if ('IntersectionObserver' in window) {
      const imgObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            const img = entry.target;
            if (img.dataset.src) img.src = img.dataset.src;
            imgObserver.unobserve(img);
          }
        });
      });
      lazyImages.forEach(function (img) { imgObserver.observe(img); });
    }
  }


  // ── Compare drawer ──────────────────────────────────────────────
  const compareButtons = document.querySelectorAll('[data-compare-add]');
  const compareList    = [];

  compareButtons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const id = this.dataset.compareAdd;
      const name = this.dataset.compareName || id;
      if (!compareList.find(function (c) { return c.id === id; }) && compareList.length < 4) {
        compareList.push({ id: id, name: name });
        updateCompareDrawer();
      }
    });
  });

  function updateCompareDrawer() {
    // Future: render sticky compare drawer
  }


  // ── Animate score bars on entry ─────────────────────────────────
  function animateScoreBars() {
    const bars = document.querySelectorAll('.progress > span');
    if (!bars.length) return;

    const barObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.transition = 'width 0.6s cubic-bezier(0.16,1,0.3,1)';
          barObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });

    bars.forEach(function (bar) {
      const targetWidth = bar.style.width;
      bar.style.width = '0';
      setTimeout(function () {
        barObserver.observe(bar);
        bar.style.width = targetWidth;
      }, 100);
    });
  }


  // ── Init ────────────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', function () {
    buildTOC();
    initStickyNav();
    animateScoreBars();
  });

})();
