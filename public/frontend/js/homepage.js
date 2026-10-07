/* =======================================================================
   BESTVINHOMES — Homepage JS
   Handles: reveal-on-scroll, gallery lightbox
   ======================================================================= */
(function () {
  'use strict';

  /* ── Reveal on scroll ── */
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add('in');
          io.unobserve(e.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

    document.querySelectorAll('.bvh-hp .reveal').forEach(function (el) { io.observe(el); });
  }

  /* ── Listing card reveal ── */
  var cards = document.querySelectorAll('.bvl-grid .bvl-reveal');
  if (cards.length) {
    if (!('IntersectionObserver' in window)) {
      cards.forEach(function (el) { el.classList.add('bvl-in'); });
    } else {
      var ioCards = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) {
            e.target.classList.add('bvl-in');
            ioCards.unobserve(e.target);
          }
        });
      }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
      cards.forEach(function (el) { ioCards.observe(el); });
    }
  }

  /* ── Card image slider ── */
  document.querySelectorAll('[data-slider]').forEach(function (slider) {
    var track = slider.querySelector('.bvl-slider-track');
    var slides = slider.querySelectorAll('.bvl-slide');
    var dots = slider.querySelectorAll('.bvl-dot');
    var prev = slider.querySelector('.bvl-slider-prev');
    var next = slider.querySelector('.bvl-slider-next');
    if (!track || slides.length < 2) return;
    var i = 0;

    function go(n) {
      i = (n + slides.length) % slides.length;
      track.style.transform = 'translateX(' + (-i * 100) + '%)';
      dots.forEach(function (d, k) { d.classList.toggle('is-on', k === i); });
    }

    function stop(e) {
      e.preventDefault();
      e.stopPropagation();
    }

    if (prev) prev.addEventListener('click', function (e) { stop(e); go(i - 1); });
    if (next) next.addEventListener('click', function (e) { stop(e); go(i + 1); });

    var startX = 0;
    slider.addEventListener('touchstart', function (e) {
      startX = e.changedTouches[0].clientX;
    }, { passive: true });
    slider.addEventListener('touchend', function (e) {
      var dx = e.changedTouches[0].clientX - startX;
      if (Math.abs(dx) < 36) return;
      e.preventDefault();
      e.stopPropagation();
      go(dx < 0 ? i + 1 : i - 1);
    }, { passive: false });
  });

  /* ── Gallery lightbox — click để xem full, nút X đóng, KHÔNG prev/next ── */
  var lb    = document.getElementById('bvh-lb');
  var lbImg = lb ? lb.querySelector('.bvh-lb-img') : null;

  if (lb && lbImg) {
    document.querySelectorAll('.bvh-hp .gallery a').forEach(function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        var img = this.querySelector('img');
        lbImg.src = img ? img.src : '';
        lbImg.alt = img ? img.alt : '';
        lb.classList.add('open');
        document.body.style.overflow = 'hidden';
      });
    });

    function closeLb() {
      lb.classList.remove('open');
      lbImg.src = '';
      document.body.style.overflow = '';
    }

    lb.querySelector('.bvh-lb-close').addEventListener('click', closeLb);
    lb.addEventListener('click', function (e) { if (e.target === lb) closeLb(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeLb(); });
  }

})();
