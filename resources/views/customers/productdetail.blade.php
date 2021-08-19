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
        <form method="POST" id="form-sewa">
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
              <input type="number" class="form-control" id="input-beli-telepon" placeholder="No. Telp"
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
            <textarea class="form-control" placeholder="Alamatmu" id="input-beli-alamat"
            @auth('customer')
              value="{{ $cs->cst_alamat }}" 
            @endauth
            required></textarea>
            <label for="input-beli-alamat">Alamat</label>
          </div>
          <div class="form-floating mb-3">
            <input class="form-control" id="input-beli-namabarang" type="text" aria-label="Disabled input example"
              disabled readonly value="{{ $data[0]->pdct_nama }}" />
            <label for="input-beli-namabarang">Nama Produk</label>
          </div>
          <div class="form-floating mb-3">
            <select class="form-select" id="input-beli-jumlah" aria-label="Floating label select example">
              <option selected value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
              <option value="5">5</option>
              <option value="6">6</option>
              <option value="7">7</option>
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
            </select>
            <label for="input-beli-jumlah">Jumlah</label>
          </div>
          <div class="form-floating mb-3">
            <textarea class="form-control" placeholder="Catatan pembelian" id="input-beli-catatan" style="height: 100px"
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
						<li class="breadcrumb-item active">{{ $data[0]->pdct_nama }}</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection

@push('js')
  <script src="{{ asset('assets') }}/js/VenueDetail.js"></script>
@endpush