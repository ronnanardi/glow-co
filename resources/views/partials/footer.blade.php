<footer>
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="footer-brand">GLOW<span>&CO</span></div>
                    <p class="footer-desc">Skincare premium Indonesia dengan bahan alami terbaik. Aman, halal, dan terdaftar BPOM. Raih kulit glowing impianmu bersama kami.</p>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-tiktok"></i></a>
                        <a href="#"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Company</h6>
                    <ul>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Ingredients</a></li>
                        <li><a href="#">Blog & Tips</a></li>
                        <li><a href="#">Karir</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Bantuan</h6>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Pengiriman</a></li>
                        <li><a href="#">Retur & Refund</a></li>
                        <li><a href="#">Konsultasi Kulit</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6>Hubungi Kami</h6>
                    <p class="mb-2"><i class="bi bi-geo-alt me-2"></i> Jl. Skincare Boulevard No. 12, Bandung</p>
                    <p class="mb-2"><i class="bi bi-whatsapp me-2"></i> +62 812 3456 7890</p>
                    <p class="mb-4"><i class="bi bi-envelope me-2"></i> hello@glowandco.id</p>
                    <div class="payment-icons">
                        <span>VISA</span><span>MASTER</span><span>OVO</span><span>GOPAY</span><span>DANA</span>
                    </div>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p>&copy; 2024 GLOW&CO Skincare. All rights reserved.</p>
            </div>
        </div>
    </footer>

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
                            <div class="mb-3" style="color:var(--accent)">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                            </div>
                            <div id="qvProductPrice" class="h4 fw-bold mb-4" style="color:var(--secondary)"></div>
                            <p class="text-muted mb-4" style="font-size:0.9rem;line-height:1.7">Produk skincare premium dengan bahan aktif terbaik. Cocok untuk semua jenis kulit. BPOM certified.</p>
                            <div class="mb-4">
                                <label class="form-label fw-bold small">Pilih Ukuran:</label>
                                <div class="d-flex gap-2">
                                    <span class="size-tag">15ml</span>
                                    <span class="size-tag active">30ml</span>
                                    <span class="size-tag">50ml</span>
                                </div>
                            </div>
                            <button class="btn-add-cart w-100">Tambah ke Keranjang</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>