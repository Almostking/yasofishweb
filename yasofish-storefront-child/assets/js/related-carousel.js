(function () {
  function setupCarousel(section) {
    var track = section.querySelector('ul.products');
    if (!track || section.querySelector('.yf-related-carousel')) return;
    track.classList.add('yf-related-track');
    var carousel = document.createElement('div');
    carousel.className = 'yf-related-carousel';
    var previous = document.createElement('button');
    previous.type = 'button';
    previous.className = 'yf-related-arrow';
    previous.setAttribute('aria-label', 'Ver productos anteriores');
    previous.textContent = '‹';
    var next = document.createElement('button');
    next.type = 'button';
    next.className = 'yf-related-arrow';
    next.setAttribute('aria-label', 'Ver más productos de pesca');
    next.textContent = '›';
    var dots = document.createElement('div');
    dots.className = 'yf-related-dots';
    dots.setAttribute('aria-label', 'Páginas de productos recomendados');
    track.parentNode.insertBefore(carousel, track);
    carousel.appendChild(previous);
    carousel.appendChild(track);
    carousel.appendChild(next);
    section.appendChild(dots);
    var pages = 1;
    function update() {
      var page = Math.min(pages - 1, Math.round(track.scrollLeft / track.clientWidth));
      dots.querySelectorAll('button').forEach(function (dot, index) {
        dot.classList.toggle('active', index === page);
        dot.setAttribute('aria-current', index === page ? 'true' : 'false');
      });
      previous.disabled = track.scrollLeft < 4;
      next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
    }
    function drawDots() {
      pages = Math.max(1, Math.ceil(track.scrollWidth / track.clientWidth));
      dots.innerHTML = '';
      for (var i = 0; i < pages; i += 1) {
        var dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'yf-related-dot' + (i === 0 ? ' active' : '');
        dot.setAttribute('aria-label', 'Ver grupo ' + (i + 1));
        (function (page) {
          dot.addEventListener('click', function () {
            track.scrollTo({ left: page * track.clientWidth, behavior: 'smooth' });
          });
        }(i));
        dots.appendChild(dot);
      }
      update();
    }
    previous.addEventListener('click', function () { track.scrollBy({ left: -track.clientWidth, behavior: 'smooth' }); });
    next.addEventListener('click', function () { track.scrollBy({ left: track.clientWidth, behavior: 'smooth' }); });
    track.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', drawDots);
    drawDots();
  }
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.related.products').forEach(setupCarousel);
  });
}());