@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')

@section('title', 'GLOW&CO | Skincare & Beauty Online Shop')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')


    <!-- Hero -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-reveal>
                    <span class="hero-tag">✨ New Arrival 2024</span>
                    <h1 class="hero-title">Raih Kulit <span>Glowing</span> Impianmu</h1>
                    <p class="hero-subtitle">Temukan rangkaian skincare premium dengan formula terbaik yang diformulasikan khusus untuk kulit Indonesia. Aman, halal, dan BPOM certified.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#products" class="btn-hero-primary">Belanja Sekarang <i class="bi bi-arrow-right ms-2"></i></a>
                        <a href="#categories" class="btn-hero-outline">Lihat Katalog</a>
                    </div>
                    <div class="hero-stats">
                        <div class="hero-stat">
                            <div class="number">50K+</div>
                            <div class="label">Happy Customers</div>
                        </div>
                        <div class="hero-stat">
                            <div class="number">4.9</div>
                            <div class="label">Rating</div>
                        </div>
                        <div class="hero-stat">
                            <div class="number">100%</div>
                            <div class="label">Original</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0" data-reveal>
                    <div class="hero-image-wrap">
                        <img src="{{ asset('images/hero.png') }}" alt="Skincare Hero" class="img-fluid">
                        <div class="hero-float-card card-1">
                            <div class="fc-label">Best Seller</div>
                            <div class="fc-value">Glow Serum</div>
                            <div class="fc-badge">BPOM ✓</div>
                        </div>
                        <div class="hero-float-card card-2">
                            <div class="fc-label">Flash Sale</div>
                            <div class="fc-value">Diskon 50%</div>
                            <div class="fc-badge">Limited</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ticker -->
    <div class="ticker-wrap">
        <div class="ticker-text">
            <div class="ticker-item">Free Ongkir Seluruh Indonesia</div>
            <div class="ticker-item">100% Original & BPOM</div>
            <div class="ticker-item">Garansi Uang Kembali</div>
            <div class="ticker-item">Konsultasi Gratis dengan Dermatologist</div>
            <div class="ticker-item">Cruelty Free & Vegan</div>
            <div class="ticker-item">Flash Sale Setiap Jumat</div>
        </div>
    </div>

    <!-- Categories -->
    <section id="categories">
        <div class="container">
            <div class="text-center mb-5" data-reveal>
                <span class="section-tag">Kategori</span>
                <h2 class="section-title">Temukan Perawatan <span>Terbaik</span></h2>
            </div>
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-md-4" data-reveal>
                        <div class="cat-card">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}">
                            <div class="cat-overlay">
                                <h3 class="cat-title">{{ $category->name }}</h3>
                                <span class="cat-count">{{ $category->products_count }} Produk</span>
                                <a href="{{ route('category.show', $category->slug) }}" class="cat-btn text-decoration-none">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section class="bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-lg-3" data-reveal>
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="bi bi-shield-check"></i></div>
                        <h5>BPOM Certified</h5>
                        <p>Semua produk terdaftar resmi dan aman digunakan</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-reveal>
                    <div class="benefit-card">
                        <div class="benefit-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-leaf" viewBox="0 0 16 16">
                                    <path d="M1.4 1.7c.216.289.65.84 1.725 1.274 1.093.44 2.884.774 5.834.528l.37-.023c1.823-.06 3.117.598 3.956 1.579C14.16 6.082 14.5 7.41 14.5 8.5c0 .58-.032 1.285-.229 1.997q.198.248.382.54c.756 1.2 1.19 2.563 1.348 3.966a1 1 0 0 1-1.98.198c-.13-.97-.397-1.913-.868-2.77C12.173 13.386 10.565 14 8 14c-1.854 0-3.32-.544-4.45-1.435-1.125-.887-1.89-2.095-2.391-3.383C.16 6.62.16 3.646.509 1.902L.73.806zm-.05 1.39c-.146 1.609-.008 3.809.74 5.728.457 1.17 1.13 2.213 2.079 2.961.942.744 2.185 1.22 3.83 1.221 2.588 0 3.91-.66 4.609-1.445-1.789-2.46-4.121-1.213-6.342-2.68-.74-.488-1.735-1.323-1.844-2.308-.023-.214.237-.274.38-.112 1.4 1.6 3.573 1.757 5.59 2.045 1.227.215 2.21.526 3.033 1.158.058-.39.075-.782.075-1.158 0-.91-.288-1.988-.975-2.792-.626-.732-1.622-1.281-3.167-1.229l-.316.02c-3.05.253-5.01-.08-6.291-.598a5.3 5.3 0 0 1-1.4-.811"/>
                                </svg>
                        </div>
                        <h5>Natural Ingredients</h5>
                        <p>Bahan alami pilihan tanpa paraben dan alkohol</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-reveal>
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="bi bi-truck"></i></div>
                        <h5>Free Ongkir</h5>
                        <p>Gratis ongkos kirim ke seluruh Indonesia</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-reveal>
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="bi bi-chat-heart"></i></div>
                        <h5>Konsultasi Free</h5>
                        <p>Konsultasi skin type gratis dengan expert kami</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products -->
    <section id="products">
        <div class="container">
            <div class="d-md-flex align-items-end justify-content-between mb-5" data-reveal>
                <div>
                    <span class="section-tag">Best Sellers</span>
                    <h2 class="section-title">Produk <span>Terlaris</span></h2>
                </div>
                <div class="filter-tabs mt-3 mt-md-0">
                    <button class="filter-tab active" data-filter="all">Semua</button>
                    @foreach($categories as $category)
                        <button class="filter-tab" data-filter="{{ $category->slug }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-6 col-lg-3 product-item" data-cat="{{ $product->category->slug }}" data-reveal>
                        <div class="product-card">
                            <div class="product-img-wrap">
                                <a href="{{ route('product.show', $product->slug) }}">
                                    <img src="{{ $product->image && !str_starts_with($product->image, 'http')
                                        ? Storage::url($product->image)
                                        : $product->image }}" alt="{{ $product->name }}">
                                 </a>
                                @if($product->badge)
                                    <span class="product-badge badge-{{ $product->badge }}">
                                        {{ ucfirst($product->badge) }}
                                    </span>
                                @endif

                                <div class="product-actions">
                                    <button class="btn-action btn-wishlist {{ in_array($product->id, $wishlistIds ?? []) ? 'active' : '' }}"
                                            data-product-id="{{ $product->id }}"
                                            onclick="toggleWishlist({{ $product->id }}, this)">
                                        <i class="bi bi-heart{{ in_array($product->id, $wishlistIds ?? []) ? '-fill' : '' }}"></i>
                                    </button>
                                    <button class="btn-action btn-quick-view"
                                            data-name="{{ $product->name }}"
                                            data-price="Rp {{ number_format($product->price, 0, ',', '.') }}"
                                            data-brand="GLOW&CO"
                                            data-img="{{ $product->image && !str_starts_with($product->image, 'http') ? Storage::url($product->image) : $product->image }}"
                                            data-desc="{{ $product->description ?? 'Produk skincare premium dengan bahan aktif terbaik. Cocok untuk semua jenis kulit. BPOM certified.' }}"
                                            data-rating="{{ $product->avg_rating }}"
                                            data-reviews="{{ $product->reviews_count }}"
                                            data-link="{{ route('product.show', $product->slug) }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="product-info">
                                <span class="product-brand">GLOW&CO</span>
                                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                    <h3 class="product-name">{{ $product->name }}</h3>
                                </a>
                                {{-- Rating --}}
                                <div class="product-rating mb-2">
                                    @if($product->reviews_count > 0)
                                        @php $rating = round($product->avg_rating); @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $rating)
                                                <i class="bi bi-star-fill"></i>
                                            @elseif($i - 0.5 <= $product->avg_rating)
                                                <i class="bi bi-star-half"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                        <span>({{ $product->reviews_count }})</span>
                                    @else
                                        <i class="bi bi-star text-muted"></i>
                                        <i class="bi bi-star text-muted"></i>
                                        <i class="bi bi-star text-muted"></i>
                                        <i class="bi bi-star text-muted"></i>
                                        <i class="bi bi-star text-muted"></i>
                                        <span class="text-muted" style="font-size:0.78rem">Belum ada ulasan</span>
                                    @endif
                                </div>

                                <div class="product-price">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                    @if($product->isOnSale())
                                        <span class="product-price-old">Rp {{ number_format($product->original_price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                @auth
                                    <button class="btn-add-cart"
                                            data-product-id="{{ $product->id }}"
                                            onclick="addToCart({{ $product->id }}, this)">
                                        Tambah ke Keranjang
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn-add-cart d-block text-center text-decoration-none">
                                        Tambah ke Keranjang
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Promo -->
    <section id="promo" class="promo-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8" data-reveal>
                    <div class="promo-card">
                        <span class="promo-tag">⏰ Limited Time</span>
                        <h3>Flash Sale Skincare</h3>
                        <p>Diskon hingga 50% untuk semua produk serum dan essence. Jangan sampai kehabisan!</p>
                        <div class="countdown">
                            <div class="countdown-item"><div class="num" id="cd-hours">05</div><div class="lbl">Jam</div></div>
                            <div class="countdown-item"><div class="num" id="cd-minutes">30</div><div class="lbl">Menit</div></div>
                            <div class="countdown-item"><div class="num" id="cd-seconds">00</div><div class="lbl">Detik</div></div>
                        </div>
                        <button class="btn-promo">Ambil Diskon <i class="bi bi-lightning-fill ms-1"></i></button>
                    </div>
                </div>
                <div class="col-lg-4" data-reveal>
                    <div class="promo-card-2 text-center d-flex flex-column justify-content-center">
                        <i class="bi bi-envelope-heart-fill text-white mb-3" style="font-size:2.5rem"></i>
                        <h4 class="text-white mb-2" style="font-family:'Playfair Display',serif">Beauty Newsletter</h4>
                        <p class="text-white-50 mb-4" style="font-size:0.9rem">Dapatkan tips skincare & promo eksklusif langsung ke email kamu.</p>
                        <form id="newsletterForm" class="newsletter-form">
                            <input type="email" class="form-control mb-3" placeholder="Email kamu..." required>
                            <button type="submit" class="btn-subscribe w-100">Subscribe ✨</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials">
        <div class="container">
            <div class="text-center mb-5" data-reveal>
                <span class="section-tag">Testimoni</span>
                <h2 class="section-title">Apa Kata <span>Mereka</span></h2>
            </div>

            @if($testimonials->isNotEmpty())
                <div class="row g-4">
                    @foreach($testimonials as $review)
                        <div class="col-md-4" data-reveal>
                            <div class="testimonial-card">

                                {{-- Bintang --}}
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>

                                {{-- Komentar --}}
                                <p class="quote">"{{ $review->comment }}"</p>

                                {{-- Reviewer --}}
                                <div class="reviewer">
                                    @if($review->user->avatar)
                                        <img src="{{ Storage::url($review->user->avatar) }}"
                                            alt="{{ $review->user->name }}"
                                            class="reviewer-avatar">
                                    @else
                                        <div class="reviewer-avatar d-flex align-items-center justify-content-center fw-bold"
                                            style="background:#9A7B67;color:white;font-size:1.1rem">
                                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="reviewer-name">{{ $review->user->name }}</div>
                                        <div class="reviewer-tag">
                                            Verified Buyer
                                            <i class="bi bi-patch-check-fill verified"></i>
                                        </div>
                                        <div class="text-muted" style="font-size:0.75rem">
                                            {{ $review->product->name ?? '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Fallback statis kalau belum ada review --}}
                <div class="row g-4">
                    <div class="col-md-4" data-reveal>
                        <div class="testimonial-card">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="quote">"Serumnya ringan banget, cepat meresap dan bikin kulit glowing dalam 2 minggu pemakaian. Love it!"</p>
                            <div class="reviewer">
                                <img src="https://i.pravatar.cc/150?u=skincare1" alt="User" class="reviewer-avatar">
                                <div>
                                    <div class="reviewer-name">Dinda Safitri</div>
                                    <div class="reviewer-tag">Verified Buyer <i class="bi bi-patch-check-fill verified"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" data-reveal>
                        <div class="testimonial-card">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <p class="quote">"Cleanser-nya gentle banget di kulit sensitif aku. Udah repurchase 3x. Packaging-nya juga cantik!"</p>
                            <div class="reviewer">
                                <img src="https://i.pravatar.cc/150?u=skincare2" alt="User" class="reviewer-avatar">
                                <div>
                                    <div class="reviewer-name">Rani Maharani</div>
                                    <div class="reviewer-tag">Verified Buyer <i class="bi bi-patch-check-fill verified"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4" data-reveal>
                        <div class="testimonial-card">
                            <div class="stars">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                            <p class="quote">"Moisturizer gel-nya bikin kulit kenyal seharian. Nggak lengket sama sekali, cocok buat cuaca tropis!"</p>
                            <div class="reviewer">
                                <img src="https://i.pravatar.cc/150?u=skincare3" alt="User" class="reviewer-avatar">
                                <div>
                                    <div class="reviewer-name">Maya Anggraini</div>
                                    <div class="reviewer-tag">Verified Buyer <i class="bi bi-patch-check-fill verified"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </section>

    <!-- Back to Top -->
    <button id="backToTop"><i class="bi bi-arrow-up"></i></button>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container-custom"></div>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-body p-0">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal"></button>
                    <div class="row g-0">
                        <div class="col-md-6">
                            <img src="" id="qvProductImg" class="img-fluid rounded-start-4" alt="Product" style="height:100%;object-fit:cover">
                        </div>
                        <div class="col-md-6 p-4 p-lg-5">
                            <span id="qvProductBrand" class="text-muted fw-bold small text-uppercase"></span>
                            <h2 id="qvProductName" class="h4 mt-1 mb-3 fw-bold" style="font-family:'Playfair Display',serif"></h2>
                            <div class="mb-3" id="qvProductRating" style="color:var(--accent)"></div>
                            <div id="qvProductPrice" class="h4 fw-bold mb-4" style="color:var(--secondary)"></div>
                            <p id="qvProductDesc" class="text-muted mb-4" style="font-size:0.9rem;line-height:1.7">
                                Produk skincare premium dengan bahan aktif terbaik. Cocok untuk semua jenis kulit. BPOM certified.
                            </p>
                            <a href="#" id="qvProductLink" class="btn-add-cart w-100 d-block text-center text-decoration-none">
                                Lihat Detail Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
