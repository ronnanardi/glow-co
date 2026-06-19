// ============================================================
// GLOW&CO SKINCARE - Main JS
// Bootstrap 5.3 | Vanilla JS | Laravel Compatible
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

  // ── Page Loader ──────────────────────────────────────────
  const loader = document.getElementById('pageLoader');
  if (loader) {
    window.addEventListener('load', () => {
      setTimeout(() => loader.classList.add('loaded'), 600);
    });
  }

  // ── Navbar Scroll Effect ──────────────────────────────────
  const navbar = document.querySelector('.navbar');
  window.addEventListener('scroll', () => {
    if (navbar) {
      window.scrollY > 50
        ? navbar.classList.add('scrolled')
        : navbar.classList.remove('scrolled');
    }
  });

  // ── Back to Top ───────────────────────────────────────────
  const backToTop = document.getElementById('backToTop');
  if (backToTop) {
    window.addEventListener('scroll', () => {
      window.scrollY > 400
        ? backToTop.classList.add('show')
        : backToTop.classList.remove('show');
    });
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ── Countdown Timer (persisten via localStorage) ──────────
  const cdHours   = document.getElementById('cd-hours');
  const cdMinutes = document.getElementById('cd-minutes');
  const cdSeconds = document.getElementById('cd-seconds');

  if (cdHours && cdMinutes && cdSeconds) {
    const pad = n => String(n).padStart(2, '0');

    const stored  = localStorage.getItem('flashSaleEnd');
    const endTime = stored
      ? new Date(parseInt(stored))
      : (() => {
          const t = Date.now() + (5 * 3600 + 30 * 60) * 1000;
          localStorage.setItem('flashSaleEnd', t);
          return new Date(t);
        })();

    const tick = setInterval(() => {
      const diff = endTime - new Date();
      if (diff <= 0) { clearInterval(tick); return; }

      cdHours.textContent   = pad(Math.floor(diff / 3600000));
      cdMinutes.textContent = pad(Math.floor((diff % 3600000) / 60000));
      cdSeconds.textContent = pad(Math.floor((diff % 60000) / 1000));
    }, 1000);
  }

  // ── Product Filter Tabs ───────────────────────────────────
  document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function () {
      document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
      this.classList.add('active');

      const filter = this.dataset.filter;
      document.querySelectorAll('.product-item').forEach(item => {
        if (filter === 'all' || item.dataset.cat === filter) {
          item.style.display = 'block';
          item.style.animation = 'fadeInUp 0.4s ease';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });

  // ── Add to Cart (Ajax, tidak redirect) ───────────────────
  window.addToCart = function(productId, btn) {
      const originalText = btn.textContent;
      btn.disabled    = true;
      btn.textContent = 'Menambahkan...';

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      if (!csrfToken) {
          // Belum login — arahkan ke login
          window.location.href = '/login';
          return;
      }

      fetch('/cart', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json',
          },
          body: JSON.stringify({ product_id: productId, quantity: 1 })
      })
      .then(res => res.json())
      .then(data => {
          if (data.success) {
              // Update badge
              const badge = document.getElementById('cartBadge');
              if (badge) {
                  badge.textContent    = data.cartCount;
                  badge.style.display  = 'flex';
              }

              // Visual feedback tombol
              btn.textContent = '✓ Ditambahkan';
              btn.style.background = '#5a8a5a';

              showToast(data.message);
          } else {
              showToast(data.message, 'error');
              btn.textContent = originalText;
          }
      })
      .catch(() => {
          showToast('Terjadi kesalahan. Coba lagi.', 'error');
          btn.textContent = originalText;
      })
      .finally(() => {
          btn.disabled = false;
          setTimeout(() => {
              btn.textContent      = originalText;
              btn.style.background = '';
          }, 2000);
      });
  };

  // ── Wishlist (visual only, belum ada backend) ─────────────
  document.querySelectorAll('.btn-wishlist').forEach(btn => {
    btn.addEventListener('click', function () {
      this.classList.toggle('active');
      const icon = this.querySelector('i');
      if (this.classList.contains('active')) {
        icon && icon.classList.replace('bi-heart', 'bi-heart-fill');
        this.style.background = '#e94560';
        this.style.color      = '#fff';
        showToast('Ditambahkan ke Wishlist ❤️');
      } else {
        icon && icon.classList.replace('bi-heart-fill', 'bi-heart');
        this.style.background = '';
        this.style.color      = '';
        showToast('Dihapus dari Wishlist');
      }
    });
  });

  // ── Toast Notification ────────────────────────────────────
  function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'alert alert-dark d-flex align-items-center gap-2 shadow-lg rounded-3 py-3 px-4 mb-2';
    toast.style.cssText = 'min-width:260px;animation:slideUp 0.3s ease;font-size:0.9rem;border:none;';
    toast.innerHTML = `<i class="bi bi-bag-check-fill text-warning"></i> ${message}`;
    container.appendChild(toast);

    setTimeout(() => {
      toast.style.animation = 'fadeOut 0.3s ease forwards';
      setTimeout(() => toast.remove(), 300);
    }, 3000);
  }

  window.showToast = showToast;

  // Tampilkan toast dari Laravel session (success/error)
  const flashSuccess = document.getElementById('flash-success');
  const flashError   = document.getElementById('flash-error');
  if (flashSuccess) showToast(flashSuccess.dataset.message, 'success');
  if (flashError)   showToast(flashError.dataset.message, 'error');

  // ── Smooth Scroll for Anchors ─────────────────────────────
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        const navCollapse = document.querySelector('.navbar-collapse');
        if (navCollapse && navCollapse.classList.contains('show')) {
          navCollapse.classList.remove('show');
        }
      }
    });
  });

  // ── Active Nav on Scroll ──────────────────────────────────
  const sections = document.querySelectorAll('section[id]');
  const navLinks  = document.querySelectorAll('.navbar-nav .nav-link');

  if (sections.length && navLinks.length) {
      const navObserver = new IntersectionObserver(entries => {
          entries.forEach(entry => {
              if (entry.isIntersecting) {
                  navLinks.forEach(link => {
                      link.classList.remove('active');

                      // Cocokkan baik href="#id" maupun href="http://.../#id"
                      const href = link.getAttribute('href') || '';
                      const hash = '#' + entry.target.id;

                      if (href === hash || href.endsWith(hash)) {
                          link.classList.add('active');
                      }
                  });
              }
          });
      }, { threshold: 0.3 });

      sections.forEach(s => navObserver.observe(s));
  }

  // ── Newsletter Form ───────────────────────────────────────
  const newsletterForm = document.getElementById('newsletterForm');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const email = this.querySelector('input[type="email"]').value;
      if (email) {
        showToast('Terima kasih telah berlangganan! 📧');
        this.reset();
      }
    });
  }

  // ── Search (filter produk by nama) ───────────────────────
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      const q = this.value.toLowerCase();
      document.querySelectorAll('.product-item').forEach(item => {
        const name = item.querySelector('.product-name')?.textContent.toLowerCase() || '';
        item.style.display = (name.includes(q) || q === '') ? 'block' : 'none';
      });
    });
  }

  // ── Scroll Reveal Animation ───────────────────────────────
  const revealEls = document.querySelectorAll('[data-reveal]');
  if (revealEls.length) {
    const revealObserver = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('revealed');
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    revealEls.forEach(el => revealObserver.observe(el));
  }

  // ── Quick View Modal ──────────────────────────────────────
  // Mengambil data dari card produk dan menampilkan di modal
  document.querySelectorAll('.btn-quick-view').forEach(btn => {
    btn.addEventListener('click', function () {
      const card  = this.closest('.product-card');
      const name  = card.querySelector('.product-name')?.textContent  || '';
      const price = card.querySelector('.product-price')?.textContent || '';
      const brand = card.querySelector('.product-brand')?.textContent || '';
      const img   = card.querySelector('.product-img-wrap img')?.src   || '';

      // Isi modal
      const qvName  = document.getElementById('qvProductName');
      const qvPrice = document.getElementById('qvProductPrice');
      const qvBrand = document.getElementById('qvProductBrand');
      const qvImg   = document.getElementById('qvProductImg');

      if (qvName)  qvName.textContent  = name;
      if (qvPrice) qvPrice.textContent = price;
      if (qvBrand) qvBrand.textContent = brand;
      if (qvImg)   qvImg.src           = img;

      // Tombol "Tambah ke Keranjang" di modal — arahkan ke link produk
      // karena quick view tidak bisa langsung POST ke Laravel tanpa product_id
      const qvBtn = document.querySelector('#quickViewModal .btn-add-cart');
      const productLink = card.querySelector('a[href*="product"]');
      if (qvBtn && productLink) {
        qvBtn.onclick = () => window.location.href = productLink.href;
        qvBtn.textContent = 'Lihat Detail Produk';
      }

      const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
      modal.show();
    });
  });

  // ── Marquee Duplicate ────────────────────────────────────
  const ticker = document.querySelector('.ticker-text');
  if (ticker && !ticker.parentElement.querySelector('.ticker-clone')) {
    const clone = ticker.cloneNode(true);
    clone.classList.add('ticker-clone');
    ticker.parentElement.appendChild(clone);
  }

  // ── CSS Animations (inject) ───────────────────────────────
  const style = document.createElement('style');
  style.textContent = `
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes slideUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeOut {
      from { opacity: 1; }
      to   { opacity: 0; }
    }
    [data-reveal] {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.6s ease, transform 0.6s ease;
    }
    [data-reveal].revealed {
      opacity: 1;
      transform: translateY(0);
    }
  `;
  document.head.appendChild(style);

});