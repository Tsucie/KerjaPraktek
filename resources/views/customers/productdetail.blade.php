@extends('customers.layout', ['title' => 'Product Details - Silungkang Products'])

@section('content')
  {{-- PHP Script --}}
  @auth('customer')
    @php
      $cs = auth()->guard('customer')->user();
      if(preg_match('/^\+\d\d(\d{3})(\d{4})(\d{4})$/',$cs->cst_no_telp,$matches)) {
        $cs->cst_no_telp = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
      }
    @endphp
  @endauth
{{-- <!-- Modal Beli --> --}}
<div class="modal fade" id="beli-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog w700 modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body pb-120">
        <button type="button" class="btn-close position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal" aria-label="Close">
          &times;
        </button>
        <p>Pesan Sekarang</p>
        <h2>Form Pembelian Produk</h2>
        <form method="POST" id="form-beli" enctype="multipart/form-data">
          <input type="hidden" id="cst_id"
          @auth('customer')
            value="{{ $cs->cst_id }}" 
          @endauth>
          <input type="hidden" id="pdct_id" value="{{ $data[0]->pdct_id }}" />
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="input-beli-nama" placeholder="name"
            @auth('customer')
              value="{{ $cs->cst_name }}" 
            @endauth
            required>
            <label for="input-beli-nama">Nama</label>
          </div>
          <div class="row" id="div-sewa-contact">
            <div class="form-floating mb-3 col input-group">
              <div class="input-group-text">+62</div>
              <input type="tel" class="form-control" id="input-beli-telepon" placeholder="No. Telp"
              @auth('customer')
                value="{{ $cs->cst_no_telp }}" 
              @endauth
              required>
              <label for="input-beli-telepon">No. Telp</label>
            </div>
            <div class="form-floating mb-3 col">
              <input type="email" class="form-control" id="input-beli-email" placeholder="Email"
              @auth('customer')
                value="{{ $cs->cst_email }}" 
              @endauth
              required>
              <label for="input-beli-email">Email</label>
            </div>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" placeholder="Alamat" id="input-beli-alamat"
            @auth('customer')
              value="{{ $cs->cst_alamat }}" 
            @endauth
            required>
            <label for="input-beli-alamat">Alamat Rumah Anda</label>
          </div>
          <div class="row" id="product-input">
						<div class="form-floating mb-3 col">
							<input class="form-control" id="input-beli-namabarang" type="text" aria-label="Disabled input example" value="{{ $data[0]->pdct_nama }}" disabled readonly />
							<label for="input-beli-namabarang">Nama Produk</label>
						</div>
						<div class="form-floating mb-3 col">
							<input type="number" class="form-control" id="input-beli-jumlah" placeholder="Jumlah Produk" required>
							<label for="input-beli-jumlah">Jumlah Produk</label>
						</div>
					</div>
          <div class="form-floating mb-3">
            <textarea class="form-control" placeholder="Catatan pembelian" id="input-beli-catatan" style="height: 8rem"
              required></textarea>
            <label for="input-beli-catatan">Catatan</label>
          </div>
          <button class="thm-btn btn-yellow position-absolute bottom-0 start-50 translate-middle-x" id="input-beli-submit" type="submit">
            Pesan
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
{{-- <!-- Modal Feedback --> --}}
<div class="modal fade" id="feedback-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="btn-close position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal" aria-label="Close">
					&times;
				</button>
				<p>Berikan Ulasanmu</p>
				<h2>Feedback</h2>
				<form method="POST" id="form-feedback">
					<div class="row" id="div-feedback-user">
						<div class="form-floating mb-3 col">
							<input type="text" class="form-control" id="input-feedback-nama" placeholder="name"
							@auth('customer')
								value="{{ $cs->cst_name }}"
							@endauth
							required>
							<label for="input-feedback-nama">Nama</label>
						</div>
						<div class="form-floating mb-3 col">
							<input type="email" class="form-control" id="input-feedback-email" placeholder="Email"
							@auth('customer')
								value="{{ $cs->cst_email }}"
							@endauth
							required>
							<label for="input-beli-email">Email</label>
						</div>
					</div>
					<div class="form-floating mb-3">
						<input type="hidden" id="fb_pdct_id" value="{{ $data[0]->pdct_id }}">
						<input class="form-control" id="input-feedback-product" type="text" aria-label="Disabled input example" value="{{ $data[0]->pdct_nama }}" disabled readonly />
						<label for="input-beli-product">Nama Product</label>
					</div>
					<div class="form-floating mb-3">
						<textarea class="form-control" placeholder="Berikan Feedback-mu disini" id="input-feedback-ulas" style="height: 100px"
							required></textarea>
						<label for="input-feedback-ulas">Ulasan</label>
					</div>
					<div class="form-floating mb-3">
						<div class="col-md-12">
							<p>Rating product Ini</p>
							<div class="stars mb-4">
								<input class="star star-5" id="star-5" type="radio" name="star" value="5" />
								<label class="star star-5" for="star-5"></label>
								<input class="star star-4" id="star-4" type="radio" name="star" value="4" />
								<label class="star star-4" for="star-4"></label>
								<input class="star star-3" id="star-3" type="radio" name="star" value="3" />
								<label class="star star-3" for="star-3"></label>
								<input class="star star-2" id="star-2" type="radio" name="star" value="2" />
								<label class="star star-2" for="star-2"></label>
								<input class="star star-1" id="star-1" type="radio" name="star" value="1" />
								<label class="star star-1" for="star-1"></label>
							</div>
						</div>
					</div>
					<button class="thm-btn position-absolute bottom-0 end-0" id="input-feedback-submit" type="submit">
						Kirim
					</button>
					<button class="thm-btn btn-success position-absolute bottom-0 end-0" id="input-feedback-update" type="button">
						Simpan
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
{{-- <!-- Start Bradcaump area --> --}}
<section>
	<div class="black-layer opc55">
		<div class="fixed-bg2" style="background-image: url({{ asset('assets') }}/images/details-gedung-head.jpg)"></div>
		<div class="container">
			<div class="pg-tp-wrp text-center">
				<div class="pg-tp-inr mt-5">
					<h1 itemprop="headline">PRODUK</h1>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('welcome') }}" title="" itemprop="url">Home</a>
						</li>
						<li class="breadcrumb-item active" id="title-pdct-page">{{ $data[0]->pdct_nama }}</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>
{{-- Product Detail --}}
<section>
	<div class="top-spac70">
		<div class="container">
			<div class="srv-dtl-inr">
				<div class="row pt-5" id="vnu_imgs">
					<div class="col-md-6 mb-5">
						<div id="carousel-venue" class="carousel carousel-dark slide" data-bs-ride="carousel">
							<div class="carousel-inner">
								@foreach ($data[0]->photos as $ph)
								<div class="carousel-item @if($loop->index == 0)active @endif">
									<img src="data:image/{{ pathinfo($ph->pp_filename, PATHINFO_EXTENSION) }};base64,{{ base64_encode($ph->pp_photo) }}" class="d-block w-100" alt="Gambar Venue" />
								</div>
								@endforeach
								@if ($data[0]->inventories->ivty_pdct_stock == 0)
								<h3 class="position-absolute top-50 start-50 translate-middle text-thumb"><span>Stok Habis</span></h3>
								@endif
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#carousel-venue" data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#carousel-venue" data-bs-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
							@if ($data[0]->promo)
								<div>
									<h2 class="position-absolute top-0 end-0 alert-carousel">{{ $data[0]->promo->prm_nama }}</h2>
								</div>
							@endif
						</div>
					</div>
					<div class="col-md-1"></div>
					<div class="col-md-5">
						<h2 class="font-weight-bold text-start">{{ $data[0]->pdct_nama }}</h2>
						<p>{{ $data[0]->pdct_desc }}</p>
            <p>Stock : {{ $data[0]->inventories->ivty_pdct_stock }}</p>
            <br />
						<span>Harga</span>
						<div class="harga">
						@if ($data[0]->inventories->ivty_pdct_stock > 0)
							@if ($data[0]->promo)
									<span style="text-shadow: 1px 2px 1px rgba(0,0,0,0.3);">Rp {{ number_format($data[0]->promo->prm_harga_promo) }}</span>
									{{-- Desktop --}}
									<span class="delet">Rp {{ number_format($data[0]->pdct_harga) }}</span>
									{{-- Mobile --}}
									<div id='promo-tag' class="mt-2">
										<span class='me-0 ps-3 pe-3 pt-1 pb-1 shadow-sm rounded' style='background-color: #FFE3E3; color:#FF4848; border-radius: 10px; font-size:24px; transform: translate(0px, -5px);'>{{ $data[0]->promo->prm_disc_percent }}% OFF</span>
									</div>
									<div style='font-weight: lighter; font-size: 14px;' class='mt-2 mb-4' id="promo-price">
										<span>from</span>
										<br>
										<span style='font-size: 20px; text-decoration: line-through;'>Rp {{ number_format($data[0]->pdct_harga) }}</span>
									</div>
								@else
									<span>Rp {{ number_format($data[0]->pdct_harga) }}</span>
								@endif
							</div>
							<div class="btn-group-details" style="margin-top: 4rem !important;">
								@auth('customer')
									<button type="button" class="thm-btn mb-2 btn-primary" data-email="{{ $cs->cst_email }}" data-id="{{ $data[0]->pdct_id }}" id="rvw-btn">
										Review Produk
									</button>
								@endauth
								<button class="thm-btn mb-2 shadow shadow-lg--hover btn-success" id="order-btn" type="button">
                  Pesan
                </button>
							</div>
						@else
							<h2>Stok sedang habis !!!</h2>
						@endif
					</div>
				</div>
				{{-- Detail? --}}
			</div>
		</div>
	</div>
</section>
{{-- <!-- Ulasan  --> --}}
<section id="sec-ulasan">
	<div class="remove-gap">
		<div class="tem-wrp2 black-layer2 opc95">
			<div class="container">
				<div class="sec-title text-center">
					<h3 itemprop="headline">Ulasan</h3>
					<span>Apa kata mereka tentang {{ $data[0]->pdct_nama }}</span>
				</div>
				<div class="row" id="product-feedbacks"></div>
			</div>
		</div>
	</div>
</section>
{{-- <!-- Running Text --> --}}
<div class="container">
	<div class="explr-inf">
		<h4 class="" itemprop="headline">
			<span>Produk Khas dari Silungkang hanya di</span>
		</h4>
	</div>
</div>
@endsection

@push('js')
  <script src="{{ asset('assets') }}/js/ProductDetail.js"></script>
@endpush