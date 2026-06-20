@extends('layouts.app')

@section('title', 'Checkout')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <h4 class="fw-bold mb-4">Checkout</h4>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="row g-4">

            {{-- Kiri: Alamat & Pembayaran --}}
            <div class="col-lg-8">

                {{-- Alamat --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Pilih Alamat Pengiriman</h6>
                            <a href="{{ route('addresses.create') }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Alamat
                            </a>
                        </div>

                        @forelse($addresses as $address)
                            <div class="form-check mb-3 p-3 rounded-3 border {{ $address->is_primary ? 'border-warning' : '' }}">
                                <input class="form-check-input" type="radio"
                                       name="address_id" value="{{ $address->id }}"
                                       {{ $address->is_primary ? 'checked' : '' }}>
                                <label class="form-check-label ms-2">
                                    <span class="fw-semibold">{{ $address->label }}</span>
                                    @if($address->is_primary)
                                        <span class="badge ms-1" style="background:#9A7B67;font-size:0.7rem">Utama</span>
                                    @endif
                                    <div class="text-muted" style="font-size:0.85rem">
                                        {{ $address->recipient_name }} · {{ $address->phone }}
                                    </div>
                                    <div class="text-muted" style="font-size:0.85rem">
                                        {{ $address->address }}, {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                    </div>
                                </label>
                            </div>
                        @empty
                            <div class="alert alert-warning">
                                Belum ada alamat tersimpan. Silakan
                                <a href="{{ route('addresses.create') }}">tambah alamat</a> terlebih dahulu sebelum checkout.
                            </div>
                        @endforelse

                    </div>
                </div>

                {{-- Pilih Kurir & Ongkir --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Pilih Kurir</h6>

                        <div class="row g-2 mb-3">
                            @foreach(['jne' => 'JNE', 'tiki' => 'TIKI', 'pos' => 'POS Indonesia'] as $key => $label)
                                <div class="col-4">

                                        <button type="button" class="btn btn-outline-secondary w-100 btn-kurir"
                                                data-kurir="{{ $key }}">
                                            {{ $label }}
                                        </button>
                                </div>
                            @endforeach
                        </div>

                        <div id="ongkirResult" style="display:none">
                            <label class="form-label fw-semibold">Pilih Layanan</label>
                            <div id="ongkirOptions"></div>
                        </div>

                        <div id="ongkirLoading" style="display:none" class="text-muted">
                            <i class="bi bi-arrow-repeat"></i> Menghitung ongkir...
                        </div>

                        <input type="hidden" name="courier" id="selectedCourier">
                        <input type="hidden" name="courier_service" id="selectedService">
                        <input type="hidden" name="shipping_cost" id="selectedShippingCost" value="0">
                    </div>
                </div>

                {{-- Kode Voucher --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Kode Voucher</h6>
                        <div class="d-flex gap-2">
                            <input type="text" id="voucherInput" class="form-control" placeholder="Masukkan kode voucher">
                            <button type="button" class="btn btn-outline-secondary" onclick="applyVoucher()">Pakai</button>
                        </div>
                        <div id="voucherMessage" class="mt-2" style="font-size:0.85rem"></div>

                        <input type="hidden" name="voucher_code" id="appliedVoucherCode">
                        <input type="hidden" name="discount" id="appliedDiscount" value="0">
                    </div>
                </div>

                {{-- Metode Pembayaran --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Metode Pembayaran</h6>
                        <div class="row g-3">
                            @foreach(['Transfer Bank', 'GoPay', 'OVO', 'DANA', 'COD'] as $method)
                                <div class="col-6 col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="payment_method" value="{{ $method }}"
                                            {{ $loop->first ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2 fw-semibold" style="font-size:0.88rem">
                                            {{ $method }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- Kanan: Summary --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Ringkasan Order</h6>

                        @foreach($cart->items as $item)
                            <div class="d-flex justify-content-between mb-2" style="font-size:0.88rem">
                                <span class="text-muted">{{ $item->product->name }} x{{ $item->quantity }}</span>
                                <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between mb-2" style="font-size:0.88rem">
                            <span class="text-muted">Ongkos Kirim</span>
                            <span id="ongkirDisplay" class="text-muted">— Pilih kurir dulu</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" id="discountRow" style="display:none">
                            <span class="text-muted">Diskon</span>
                            <span id="discountDisplay" class="text-danger">- Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold" id="totalDisplay" style="color:#9A7B67;font-size:1.1rem">
                                Rp {{ number_format($cart->total, 0, ',', '.') }}
                            </span>
                        </div>

                        <button type="submit" class="btn btn-theme w-100">
                            <i class="bi bi-bag-check me-1"></i> Buat Pesanan
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection

@section('js')
<script>
    const addressRadios = document.querySelectorAll('input[name="address_id"]');
    const btnKurir      = document.querySelectorAll('.btn-kurir');
    let selectedAddress = document.querySelector('input[name="address_id"]:checked')?.value;
    let selectedKurir   = null;

    addressRadios.forEach(r => r.addEventListener('change', function() {
        selectedAddress = this.value;
        if (selectedKurir) fetchOngkir();
    }));

    btnKurir.forEach(btn => {
        btn.addEventListener('click', function() {
            btnKurir.forEach(b => b.classList.remove('active', 'btn-secondary'));
            this.classList.add('active', 'btn-secondary');
            selectedKurir = this.dataset.kurir;
            document.getElementById('selectedCourier').value = selectedKurir;
            if (selectedAddress) fetchOngkir();
        });
    });

    function fetchOngkir() {
        document.getElementById('ongkirLoading').style.display = 'block';
        document.getElementById('ongkirResult').style.display  = 'none';

        fetch('{{ route("checkout.ongkir") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                address_id: selectedAddress,
                courier: selectedKurir
            })
        })
        .then(res => res.json())
        .then(costs => {
            document.getElementById('ongkirLoading').style.display = 'none';
            document.getElementById('ongkirResult').style.display  = 'block';

            const container = document.getElementById('ongkirOptions');
            container.innerHTML = '';

            costs.forEach((cost, i) => {
                const etd   = cost.cost[0].etd;
                const price = cost.cost[0].value;
                container.innerHTML += `
                    <div class="form-check p-3 border rounded-3 mb-2">
                        <input class="form-check-input" type="radio" name="service_option"
                               value="${cost.service}" ${i === 0 ? 'checked' : ''}
                               data-price="${price}" data-service="${cost.service}"
                               onchange="selectService(this)">
                        <label class="form-check-label ms-2">
                            <span class="fw-semibold">${cost.service}</span> - ${cost.description}<br>
                            <span class="text-muted" style="font-size:0.82rem">
                                Estimasi ${etd} hari · Rp ${price.toLocaleString('id-ID')}
                            </span>
                        </label>
                    </div>`;
            });

            // set default pilihan pertama
            if (costs.length > 0) {
                document.getElementById('selectedService').value      = costs[0].service;
                document.getElementById('selectedShippingCost').value = costs[0].cost[0].value;
                updateTotal();
            }
        });
    }

    function selectService(el) {
        document.getElementById('selectedService').value      = el.dataset.service;
        document.getElementById('selectedShippingCost').value = el.dataset.price;
        updateTotal();
    }

    function updateTotal() {
        const ongkir    = parseInt(document.getElementById('selectedShippingCost').value) || 0;
        const subtotal  = {{ $cart->total }};
        const total     = subtotal + ongkir;
        document.getElementById('totalDisplay').innerText =
            'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('ongkirDisplay').innerText =
            'Rp ' + ongkir.toLocaleString('id-ID');
    }

    let currentDiscount = 0;

    function applyVoucher() {
        const code = document.getElementById('voucherInput').value.trim();
        const msgEl = document.getElementById('voucherMessage');

        if (!code) return;

        fetch('{{ route("checkout.voucher") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                currentDiscount = data.discount;
                document.getElementById('appliedVoucherCode').value = data.code;
                document.getElementById('appliedDiscount').value = data.discount;

                msgEl.innerHTML = `<span class="text-success"><i class="bi bi-check-circle"></i> ${data.message}</span>`;
                document.getElementById('discountRow').style.display = 'flex';
                document.getElementById('discountDisplay').innerText = '- Rp ' + data.discount.toLocaleString('id-ID');
            } else {
                currentDiscount = 0;
                document.getElementById('appliedVoucherCode').value = '';
                document.getElementById('appliedDiscount').value = 0;
                document.getElementById('discountRow').style.display = 'none';

                msgEl.innerHTML = `<span class="text-danger"><i class="bi bi-x-circle"></i> ${data.message}</span>`;
            }
            updateTotal();
        });
    }

    function updateTotal() {
        const ongkir   = parseInt(document.getElementById('selectedShippingCost').value) || 0;
        const subtotal = {{ $cart->total }};
        const total    = subtotal + ongkir - currentDiscount;

        document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('ongkirDisplay').innerText = 'Rp ' + ongkir.toLocaleString('id-ID');
    }
</script>
@endsection