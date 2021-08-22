@extends('customers.layout', ['title' => 'My Order - Silungkang Venue'])

@section('content')
  @auth('customer')
		@php
			$cs = auth()->guard('customer')->user();
			if(preg_match('/^\+\d\d(\d{3})(\d{4})(\d{4})$/',$cs->cst_no_telp,$matches)) {
				$cs->cst_no_telp = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
			}
		@endphp
  @endauth
<!-- .modal -->
<div class="modal fade" id="order-detail-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success position-absolute bottom-0 start-50 translate-middle-x" data-bs-dismiss="modal">Oke</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
{{-- <!-- Start Bradcaump area --> --}}
<section>
	<div class="black-layer opc55">
		<div class="fixed-bg2" style="background-image: url({{ asset('assets') }}/images/details-gedung-head.jpg)"></div>
		<div class="container">
			<div class="pg-tp-wrp text-center">
				<div class="pg-tp-inr mt-5">
					<h1 itemprop="headline">Daftar Pemesanan</h1>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('welcome') }}" title="" itemprop="url">Home</a>
						</li>
						<li class="breadcrumb-item active" id="title-mo-page" data_id="{{ $cs->cst_id }}">{{ $cs->cst_name }} Orders</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>
{{-- <!-- Order List View --> --}}
<section>
	<div class="top-spac70">
		<div class="container">
      <h2 style="text-align: left;" id="myorder-title">Pesanan Anda</h2>
      <div class="container pt-5">
        <div class="explr-wrp-tbs">
          <ul class="nav nav-tabs mb-0">
            <li class="nav-item">
              <a class="nav-link active" data-bs-toggle="tab" href="#explr-tb1">Gedung/Ruangan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="tab" href="#explr-tb2">Produk</a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane fade show active" id="explr-tb1">
              <div class="row">
                <div class="col">
                  <div class="explr-inf" id="vnu_orders"></div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="explr-tb2">
              <div class="row">
                <div class="col">
                  <div class="explr-inf" id="pdct_orders">
                    <p>Order Product</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('js')
<script src="{{ asset('assets') }}/js/MyOrder.js"></script>
@endpush