@extends('layouts.app')

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
                        <img src="assets/hero.png" alt="Skincare Hero" class="img-fluid">
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
                                <span class="cat-count">{{ $category->products_count }}+ Produk</span>
                                <button class="cat-btn">Lihat Semua</button>
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
                        <div class="benefit-icon"><i class="bi bi-leaf"></i></div>
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
                                <img src="{{ $product->image && !str_starts_with($product->image, 'http')
                                            ? Storage::url($product->image)
                                            : $product->image }}" alt="{{ $product->name }}">

                                @if($product->badge)
                                    <span class="product-badge badge-{{ $product->badge }}">
                                        {{ ucfirst($product->badge) }}
                                    </span>
                                @endif

                                <div class="product-actions">
                                    <button class="btn-action btn-wishlist"><i class="bi bi-heart"></i></button>
                                    <button class="btn-action btn-quick-view"><i class="bi bi-eye"></i></button>
                                </div>
                            </div>
                            <div class="product-info">
                                <span class="product-brand">GLOW&CO</span>
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <div class="product-price">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </div>
                                @auth
                                    <form method="POST" action="{{ route('cart.store') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-add-cart">Tambah ke Keranjang</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn-add-cart">Tambah ke Keranjang</a>
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
            <div class="row g-4">
                <div class="col-md-4" data-reveal>
                    <div class="testimonial-card">
                        <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
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
                        <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
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
                        <div class="stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
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
        </div>
    </section>

@endsection
