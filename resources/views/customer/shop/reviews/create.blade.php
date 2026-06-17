@extends('layouts.app')

@section('title', 'Beri Review')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
@endsection

@section('content')
<div class="container py-5">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('orders.show', $orderItem->order_id) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="fw-bold mb-0">Beri Review</h4>
    </div>

    <div class="card border-0 shadow-sm rounded-3" style="max-width:600px">
        <div class="card-body p-4">

            <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div style="width:50px;height:50px;border-radius:10px;background:var(--cream);display:flex;align-items:center;justify-content:center">
                    <i class="bi bi-droplet-fill" style="color:#9A7B67"></i>
                </div>
                <div class="fw-semibold">{{ $orderItem->product_name }}</div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reviews.store', $orderItem) }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold d-block">Rating Produk</label>
                    <div class="star-rating" id="starRating">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star" data-value="{{ $i }}" style="font-size:1.8rem;color:#ddd;cursor:pointer;margin-right:4px"></i>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Ulasan (opsional)</label>
                    <textarea name="comment" rows="4" class="form-control"
                              placeholder="Bagaimana pengalamanmu dengan produk ini?">{{ old('comment') }}</textarea>
                </div>

                <button type="submit" class="btn btn-theme w-100">
                    <i class="bi bi-send me-1"></i> Kirim Review
                </button>

            </form>
        </div>
    </div>

</div>
@endsection

@section('js')
<script>
    const stars = document.querySelectorAll('#starRating i');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.dataset.value;
            ratingInput.value = value;

            stars.forEach(s => {
                s.className = s.dataset.value <= value ? 'bi bi-star-fill' : 'bi bi-star';
                s.style.color = s.dataset.value <= value ? '#C9A87C' : '#ddd';
            });
        });
    });
</script>
@endsection