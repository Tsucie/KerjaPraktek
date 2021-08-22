@extends('customers.layout', ['title' => 'Venue Details - Silungkang Venue'])

@section('content')
	{{-- PHP Script --}}
	@php
		$fasilitas = explode('-', $data[0]->vnu_fasilitas);
	@endphp
	@auth('customer')
		@php
			$cs = auth()->guard('customer')->user();
			if(preg_match('/^\+\d\d(\d{3})(\d{4})(\d{4})$/',$cs->cst_no_telp,$matches)) {
				$cs->cst_no_telp = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
			}
		@endphp
	@endauth
{{-- <!-- Modal cek gedung --> --}}
<div class="modal fade" id="cek-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="btn-close position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal" aria-label="Close">
					&times;
				</button>
				<p>Cek ketersediaan dulu yuk</p>
				<h2>Check Availability</h2>
				<form method="POST" id="form-cek-gedung">
					<input type="hidden" id="cek-vnu_id" value="{{ $data[0]->vnu_id }}">
					<div class="form-floating mb-3">
						<input class="form-control" id="input-cek-namagedung" type="text" aria-label="Disabled input example" value="{{ $data[0]->vnu_nama }}" disabled readonly />
						<label for="input-sewa-namagedung">Nama Gedung</label>
					</div>
					<div class="row">
						<div class="form-floating mb-3 col">
							<input type="date" class="form-control" id="input-cek-tanggal" placeholder="Name" required>
							<label for="input-cek-tanggal">Tanggal</label>
						</div>
						<div class="form-floating mb-3 col">
							<select class="form-select" id="input-cek-waktu" aria-label="Floating label select example">
								<option selected value="Siang">Siang</option>
								<option value="Malam">Malam</option>
							</select>
							<label for="input-cek-waktu">Waktu</label>
						</div>
					</div>
					<button class="thm-btn btn-yellow position-absolute bottom-0 start-50 translate-middle-x" id="input-cek-submit" type="submit">
						Check
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
{{-- <!-- Modal Sewa --> --}}
<div class="modal fade" id="sewa-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog w700 modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body pb-120">
				<button type="button" class="btn-close position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal" aria-label="Close">&times;</button>
				<p>Ayo Booking Sekarang</p>
				<h2>Form Penyewaan Gedung</h2>
				<form method="POST" id="form-sewa">
					<input type="hidden" id="cst_id"
					@auth('customer')
						value="{{ $cs->cst_id }}" 
					@endauth>
					<input type="hidden" id="vnu_id" value="{{ $data[0]->vnu_id }}" />
					<div class="">
						<div class="form-floating mb-3">
							<input type="text" class="form-control" id="input-sewa-nama" placeholder="name"
							@auth('customer')
								value="{{ $cs->cst_name }}" 
							@endauth
							required>
							<label for="input-sewa-nama">Nama</label>
						</div>
						<div class="row" id="div-sewa-contact">
							<div class="form-floating mb-3 col input-group">
								<div class="input-group-text">+62</div>
								<input type="tel" class="form-control" id="input-sewa-telepon" placeholder="No. Telp"
								@auth('customer')
									value="{{ $cs->cst_no_telp }}" 
								@endauth
								required>
								<label for="input-sewa-telepon">No. Telp</label>
							</div>
							<div class="form-floating mb-3 col">
								<input type="email" class="form-control" id="input-sewa-email" placeholder="Email"
								@auth('customer')
									value="{{ $cs->cst_email }}" 
								@endauth
								required>
								<label for="input-sewa-email">Email</label>
							</div>
						</div>
						<div class="form-floating mb-3">
							<input class="form-control" id="input-sewa-alamat" rows="3" placeholder="Alamat"
							@auth('customer')
								value="{{ $cs->cst_alamat }}"
							@endauth>
							<label for="input-sewa-alamat">Alamat</label>
						</div>
						<div class="form-floating mb-3 col">
							<input class="form-control" id="input-sewa-namagedung" type="text" aria-label="Disabled input example" value="{{ $data[0]->vnu_nama }}" disabled readonly />
							<label for="input-sewa-namagedung">Nama Gedung</label>
						</div>
						<div class="form-floating mb-3 col">
							<input class="form-control" id="input-sewa-keperluan" type="text" aria-label="Disabled input example" />
							<label for="input-sewa-keperluan">Keperluan Pemakaian</label>
						</div>
						<div class="row" id="div-tgl-wkt-inputs">
							<label class="mb-1">Tanggal & Waktu Pemakaian</label>
							<div class="form-floating mb-3 col">
								<input type="date" class="form-control" id="input-sewa-tanggal" placeholder="Name" required>
								<label for="input-sewa-tanggal">Tanggal</label>
							</div>
							<div class="form-floating mb-3 col">
								@if ($data[0]->vnu_tipe_waktu == 1)
									{{-- Input Dari - Sampai --}}
									<input type="number" class="form-control" id="input-sewa-waktu" aria-label="Floating label select example" required>
									<label for="input-sewa-waktu">Waktu (Dalam Jam)</label>
								@else
									<select class="form-select" id="input-sewa-waktu" aria-label="Floating label select example">
										<option selected value="Siang">Siang</option>
										<option value="Malam">Malam</option>
									</select>
									<label for="input-sewa-waktu">Waktu</label>
								@endif
							</div>
						</div>
						<div class="row" id="div-sewa-fasilitas">
							<label class="mb-1">Fasilitas Tambahan</label>
							<div class="form-floating mb-3 col-10">
								<select class="form-select" id="input-sewa-morefacility" aria-label="Floating label select example">
								</select>
								<label for="input-sewa-morefacility">Fasilitas</label>
							</div>
							<div class="form-floating mb-3 col-2">
								<input type="number" class="form-control" id="input-sewa-jumlah" placeholder="Satuan" />
								<label for="input-sewa-satuan">Jumlah</label>
							</div>
						</div>
						<div class="form-floating mb-3 col">
							<button type="button" class="btn btn-danger"  id="hps-facility-btn">Batal</button>
							<button type="button" class="btn btn-success" style="float: right" id="add-facility-btn">Tambah</button>
						</div>
						<div class="form-floating mb-3 col">
							<textarea class="form-control" id="facility-text" rows="5" placeholder="Daftar Fasilitas" style="height: auto !important;" disabled></textarea>
							<label for="facility-text">Daftar Fasilitas</label>
						</div>
					</div>
					<button class="thm-btn btn-yellow position-absolute bottom-0 start-50 translate-middle-x" id="input-sewa-submit" type="button">
						Book Now
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
						<input type="hidden" id="fb_vnu_id" value="{{ $data[0]->vnu_id }}">
						<input class="form-control" id="input-feedback-venue" type="text" aria-label="Disabled input example" value="{{ $data[0]->vnu_nama }}" disabled readonly />
						<label for="input-beli-venue">Nama Venue</label>
					</div>
					<div class="form-floating mb-3">
						<textarea class="form-control" placeholder="Berikan Feedback-mu disini" id="input-feedback-ulas" style="height: 100px"
							required></textarea>
						<label for="input-feedback-ulas">Ulasan</label>
					</div>
					<div class="form-floating mb-3">
						<div class="col-md-12">
							<p>Rating Venue Ini</p>
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
					<button class="thm-btn btn-success position-absolute bottom-0 end-0" id="input-feedback-update" type="button" style="margin: 0 70px 80px 0;">
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
					<h1 itemprop="headline">{{ $data[0]->vnu_tipe_waktu == 1 ? 'RUANGAN' : 'GEDUNG' }}</h1>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="{{ route('welcome') }}" title="" itemprop="url">Home</a>
						</li>
						<li class="breadcrumb-item active" id="title-vnu-page">{{ $data[0]->vnu_nama }}</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</section>
{{-- <!-- Venue Details --> --}}
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
									<img src="data:image/{{ pathinfo($ph->vp_filename, PATHINFO_EXTENSION) }};base64,{{ base64_encode($ph->vp_photo) }}" class="d-block w-100" alt="Gambar Venue" />
								</div>
								@endforeach
								@if ($data[0]->vnu_status_tersedia == 0)
								<h3 class="position-absolute top-50 start-50 translate-middle text-thumb"><span>Coming Soon</span></h3>
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
						<h2 class="font-weight-bold text-start">{{ $data[0]->vnu_nama }}</h2>
						<br />
						<p>
							{{ $data[0]->vnu_desc }}
						</p>
						<br />
						<span>Harga Sewa</span>
						<div class="harga">
						@if ($data[0]->vnu_status_tersedia == 1)
							@if ($data[0]->promo)
									<span style="text-shadow: 1px 2px 1px rgba(0,0,0,0.3);">Rp {{ number_format($data[0]->promo->prm_harga_promo, 2) }}</span>
									{{-- Desktop --}}
									<span class="delet">Rp {{ number_format($data[0]->vnu_harga, 2) }}</span>
									{{-- Mobile --}}
									<div id='promo-tag' class="mt-2">
										<span class='me-0 ps-3 pe-3 pt-1 pb-1 shadow-sm rounded' style='background-color: #FFE3E3; color:#FF4848; border-radius: 10px; font-size:24px; transform: translate(0px, -5px);'>{{ $data[0]->promo->prm_disc_percent }}% OFF</span>
									</div>
									<div style='font-weight: lighter; font-size: 14px;' class='mt-2 mb-4' id="promo-price">
										<span>from</span>
										<br>
										<span style='font-size: 20px; text-decoration: line-through;'>Rp {{ number_format($data[0]->vnu_harga, 2) }}</span>
									</div>
								@else
									<span>Rp {{ number_format($data[0]->vnu_harga, 2) }} <small style="font-weight: 100">/jam</small> </span>
								@endif
							</div>
							<div class="btn-group-details" style="margin-top: 4rem !important;">
								@auth('customer')
									<button type="button" class="thm-btn mb-2 btn-primary" data-email="{{ $cs->cst_email }}" data-id="{{ $data[0]->vnu_id }}" id="rvw-btn" style="width: 72%">
										Review Venue
									</button>
								@endauth
								<div class="">
									<button type="button" class="thm-btn mb-2 btn-warning" title="Cek Ketersediaan venue" data-bs-toggle="modal" data-bs-target="#cek-modal" id="avl-btn">
										Check Availability
									</button>
									<button class="thm-btn mb-2 shadow shadow-lg--hover btn-success" id="gedung_aula" type="button">
										Pesan
									</button>
								</div>
							</div>
						@else
							<h2>Coming Soon !!!</h2>
						@endif
					</div>
				</div>
				{{-- Desktop View --}}
				<div class="row pt-5 deskripsi-xs">
					<div class="col-md-12 pt-5">
						<div class="container pt-5">
							<div class="explr-wrp-tbs">
								<ul class="nav nav-tabs mb-0">
									<li class="nav-item">
										<a class="nav-link active" data-bs-toggle="tab" href="#explr-tb1">Deskripsi</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-bs-toggle="tab" href="#explr-tb2">Fasilitas</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-bs-toggle="tab" href="#explr-tb3">Harga
											Sewa</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-bs-toggle="tab" href="#explr-tb4">Waktu
											Pemakaian</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-bs-toggle="tab" href="#explr-tb5">Fasilitas
											Tambahan</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" data-bs-toggle="tab" href="#explr-tb6">Ketentuan
											Lainnya</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane fade show active" id="explr-tb1">
										<div class="row">
											<div class="col">
												<div class="explr-inf">
													<p itemprop="description">
														{{ $data[0]->vnu_desc }}
													</p>
													<br />
													<p>Untuk Info lebih lanjut</p>
													<ul>
														<li>Khairul Hasan : 0812 8109 8822</li>
														<li>Nurul : 0895 6367 50473</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="explr-tb2">
										<div class="row">
											<div class="col">
												<div class="explr-inf">
													@if ($data[0]->vnu_status_tersedia == 1)
														<ul>
														@foreach ($fasilitas as $item)
															@if ($loop->index != 0)
																<li>{{ $item }}</li>
															@endif
														@endforeach
														</ul>
													@else
														<h2>Coming Soon !!!</h2>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="explr-tb3">
										<div class="row">
											<div class="col">
												<div class="explr-inf">
													@if ($data[0]->vnu_status_tersedia == 1)
														@switch($data[0]->vnu_tipe_waktu)
																@case(0)
																	<ul>
																		<li>Senin - Jum'at</li>
																		<li style="list-style: none">Siang : Rp {{ number_format($data[0]->promo->prm_harga_promo, 2) }}
																		</li>
																		<li style="list-style: none">Malam : Rp {{ number_format($data[0]->promo->prm_harga_promo, 2) }}
																		</li>
																		<br />
																		<li>Sabtu, Minggu, & Hari Libur</li>
																		<li style="list-style: none">Siang : Rp {{ number_format($data[0]->vnu_harga, 2) }}
																		</li>
																		<li style="list-style: none">Malam : Rp {{ number_format($data[0]->vnu_harga, 2) }}
																		</li>
																	</ul>
																	@break
																@case(1)
																	<ul>
																		@if ($data[0]->promo)
																			<li>Harga</li>
																			<li style="list-style: none"><span class="delet">Rp {{ number_format($data[0]->vnu_harga, 2) }}</span></li>
																			<li>Harga Promo</li>
																			<li style="list-style: none">Rp {{ number_format($data[0]->promo->prm_harga_promo, 2) }}</li>
																		@else
																			<li>Harga</li>
																			<li style="list-style: none">Rp {{ number_format($data[0]->vnu_harga, 2) }}<small style="font-weight: 100">/jam</small></li>
																		@endif
																	</ul>
																	@break
																@default
														@endswitch
													@else
														<h2>Coming Soon !!!</h2>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="explr-tb4">
										<div class="row">
											<div class="col">
												<div class="explr-inf">
													@if ($data[0]->vnu_status_tersedia == 1)
														@switch($data[0]->vnu_tipe_waktu)
															@case(0)
																<ul>
																	<li>Siang : {{ $data[0]->vnu_jam_pemakaian_siang }}</li>
																	<li>Malam : {{ $data[0]->vnu_jam_pemakaian_malam }}</li>
																</ul>
																@break
															@case(1)
																<ul>
																	<li>Jam {{ $data[0]->vnu_jam_pemakaian_siang }} - {{ $data[0]->vnu_jam_pemakaian_malam }}</li>
																</ul>
																@break
															@default
														@endswitch
													@else
														<h2>Coming Soon !!!</h2>
													@endif
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="explr-tb5">
										<div class="row">
											<div class="col">
												<div class="explr-inf">
													<div class="row">
														<div class="col">
															<ul id="mfc_nama">
															</ul>
														</div>
														<div class="col">
															<ul style="list-style: none" id="mfc_prices_pcs">
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="tab-pane fade" id="explr-tb6">
										<div class="row">
											<div class="col">
												<div class="explr-inf">
													@if ($data[0]->vnu_status_tersedia == 1)
														@switch($data[0]->vnu_tipe_waktu)
																@case(0)
																	<ul>
																		<li>
																			Biaya pemakaian Gedung dan biaya lain-lain harus
																			dilunasi palng lambat 30 (tiga
																			puluh) hari sebelung tanggal pemakaian Gedung.
																		</li>
																		<li>DP (Down Payment) sewa Gedung minimal Rp. 2.000.000
																		</li>
																		<li>
																			Pembatalan sewa Gedung 4 bulan sebelum acara, maka
																			DP dikembalikan sebesar 50%
																		</li>
																		<li>
																			Pembatalan sewa Gedung 2 bulan sebelum acara, maka
																			DP dikembalikan sebesar 25%
																		</li>
																		<li>Pembatalan sewa Gedung kurang dari 2 bulan sebelum
																			acara, maka DP Hangus</li>
																		<li>
																			Pembayaran sewa hanya melalui Rekening Mandiri a/n
																			Perkumpulan Persatuan Keluarga
																			Silungkang
																		</li>
																		<li>
																			Kelebihan pemakaian sewa Gedung maksimal 2 jam akan
																			dikenakan denda atau charge 50%
																			dari biaya sewa Gedung
																		</li>
																	</ul>
																	@break
																@case(1)
																	<h2>Comming Soon !!!</h2>
																	@break
																@default
														@endswitch
													@else
														<h2>Comming Soon !!!</h2>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				{{-- Mobile View --}}
				<h4 class="mt-5 mb-3 deskripsi-md">Detail Gedung :</h4>
				<div id="tgl1" class="tgl-styl style3 deskripsi-md">
					<div class="tgl-itm">
						<h4>Fasilitas<i class="fa fa-plus"></i></h4>
						<div class="tgl-cnt">
							@if ($data[0]->vnu_status_tersedia == 1)
								<ul>
								@foreach ($fasilitas as $item)
									@if ($loop->index != 0)
										<li>{{ $item }}</li>
									@endif
								@endforeach
								</ul>
							@else
								<h2>Coming Soon !!!</h2>
							@endif
						</div>
					</div>
					<div class="tgl-itm">
						<h4>Harga Sewa<i class="fa fa-plus"></i></h4>
						<div class="tgl-cnt">
							@if ($data[0]->vnu_status_tersedia == 1)
								@switch($data[0]->vnu_tipe_waktu)
										@case(0)
											<ul>
												<li>Senin - Jum'at</li>
												<li style="list-style: none">Siang : Rp {{ number_format($data[0]->promo->prm_harga_promo, 2) }}
												</li>
												<li style="list-style: none">Malam : Rp {{ number_format($data[0]->promo->prm_harga_promo, 2) }}
												</li>
												<br />
												<li>Sabtu, Minggu, & Hari Libur</li>
												<li style="list-style: none">Siang : Rp {{ number_format($data[0]->vnu_harga, 2) }}
												</li>
												<li style="list-style: none">Malam : Rp {{ number_format($data[0]->vnu_harga, 2) }}
												</li>
											</ul>
											@break
										@case(1)
											<ul>
												@if ($data[0]->promo)
													<li>Harga</li>
													<li style="list-style: none"><span class="delet">Rp {{ number_format($data[0]->vnu_harga, 2) }}</span></li>
													<li>Harga Promo</li>
													<li style="list-style: none">Rp {{ number_format($data[0]->promo->prm_harga_promo, 2) }}</li>
												@else
													<li>Harga</li>
													<li style="list-style: none">Rp {{ number_format($data[0]->vnu_harga, 2) }}<small style="font-weight: 100">/jam</small></li>
												@endif
											</ul>
											@break
										@default
								@endswitch
							@else
								<h2>Coming Soon !!!</h2>
							@endif
						</div>
					</div>
					<div class="tgl-itm">
						<h4>Waktu Pemakaian<i class="fa fa-plus"></i></h4>
						<div class="tgl-cnt">
							@if ($data[0]->vnu_status_tersedia == 1)
								@switch($data[0]->vnu_tipe_waktu)
									@case(0)
										<ul>
											<li>Siang : {{ $data[0]->vnu_jam_pemakaian_siang }}</li>
											<li>Malam : {{ $data[0]->vnu_jam_pemakaian_malam }}</li>
										</ul>
										@break
									@case(1)
										<ul>
											<li>Jam {{ $data[0]->vnu_jam_pemakaian_siang }} - {{ $data[0]->vnu_jam_pemakaian_malam }}</li>
										</ul>
										@break
									@default
								@endswitch
							@else
								<h2>Coming Soon !!!</h2>
							@endif
						</div>
					</div>
					<div class="tgl-itm">
						<h4>Fasilitas Tambahan (Optional)<i class="fa fa-plus"></i></h4>
						<div class="tgl-cnt">
							<div class="row">
								<div class="col">
									<ul id="fcs_mobile_view"></ul>
								</div>
							</div>
						</div>
					</div>
					<div class="tgl-itm">
						<h4>Ketentuan Lainnya<i class="fa fa-plus"></i></h4>
						<div class="tgl-cnt">
							@if ($data[0]->vnu_status_tersedia == 1)
								@switch($data[0]->vnu_tipe_waktu)
										@case(0)
											<ul>
												<li>
													Biaya pemakaian Gedung dan biaya lain-lain harus
													dilunasi palng lambat 30 (tiga
													puluh) hari sebelung tanggal pemakaian Gedung.
												</li>
												<li>DP (Down Payment) sewa Gedung minimal Rp. 2.000.000
												</li>
												<li>
													Pembatalan sewa Gedung 4 bulan sebelum acara, maka
													DP dikembalikan sebesar 50%
												</li>
												<li>
													Pembatalan sewa Gedung 2 bulan sebelum acara, maka
													DP dikembalikan sebesar 25%
												</li>
												<li>Pembatalan sewa Gedung kurang dari 2 bulan sebelum
													acara, maka DP Hangus</li>
												<li>
													Pembayaran sewa hanya melalui Rekening Mandiri a/n
													Perkumpulan Persatuan Keluarga
													Silungkang
												</li>
												<li>
													Kelebihan pemakaian sewa Gedung maksimal 2 jam akan
													dikenakan denda atau charge 50%
													dari biaya sewa Gedung
												</li>
											</ul>
											@break
										@case(1)
											<h2>Coming Soon !!!</h2>
											@break
										@default
								@endswitch
							@else
								<h2>Comming Soon !!!</h2>
							@endif
						</div>
					</div>
					<div class="tgl-itm">
						<h4>Contact Us<i class="fa fa-plus"></i></h4>
						<div class="tgl-cnt">
							<p>Untuk Info lebih lanjut</p>
							<ul>
								<li>Khairul Hasan : 0812 8109 8822</li>
								<li>Nurul : 0895 6367 50473</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
{{-- <!-- Ulasan  --> --}}
<section id="sec-ulasan">
	<div class="remove-gap">
		<div class="tem-wrp2 black-layer2 opc95">
			<div class="fixed-bg" style="background-image: url(assets/images/details-gedung-head.jpg)"></div>
			<div class="container">
				<div class="sec-title text-center">
					<h3 itemprop="headline">Ulasan</h3>
					<span>Apa kata Mereka tentang Silungkang Venue</span>
				</div>
				<div class="row" id="venue-feedbacks">
					{{-- <div class="col-md-4 col-sm-12 col-xs-12 clearfix align-left mb-3">
						<div class="ce-feature-box-16 border margin-bottom">
							<div class="text-box text-center">
								<div class="imgbox-small round center overflow-hidden">
									<img src="http://127.0.0.1:8000/assets/img/profiles/1.jpg" alt="" class="img-responsive"/>
								</div>
								<div class="text-box">
									<h6 class="title less-mar-1 mt-1">Zikri</h6>
									<p class="subtext">Gedung Aula PKS</p>
								</div>
								<br />
								<p class="content">
									Karena tempat yang mewah menjadikan acara wisuda saya sangat berkesan.
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12 clearfix align-left mb-3">
						<div class="ce-feature-box-16 border primary margin-bottom">
							<div class="text-box text-center">
								<div class="imgbox-small round center overflow-hidden">
									<img src="http://127.0.0.1:8000/assets/img/profiles/5.jpg" alt="" class="img-responsive" /></div>
								<div class="text-box">
									<h6 class="title less-mar-1 mt-1">Rizky</h6>
									<p class="subtext">Gedung Aula PKS</p>
								</div>
								<br />
								<p class="content">
									Dengan fasilitas yang lengkap dan dekorasi yang mewah, acara saya menjadi momen yang sangat berkesan sekali.
								</p>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-12 col-xs-12 clearfix align-left mb-3">
						<div class="ce-feature-box-16 border margin-bottom">
							<div class="text-box text-center">
								<div class="imgbox-small round center overflow-hidden">
									<img src="http://127.0.0.1:8000/assets/img/profiles/30.jpg" alt="" class="img-responsive" /></div>
								<div class="text-box">
									<h6 class="title less-mar-1 mt-1">Jenny</h6>
									<p class="subtext">Gedung Aula PKS</p>
								</div>
								<br />
								<p class="content">
									Dekorasinya mewah dan instagramable sekali.
								</p>
							</div>
						</div>
					</div> --}}
				</div>
			</div>
		</div>
	</div>
</section>
{{-- <!-- Running Text --> --}}
<div class="container">
	<div class="explr-inf">
		<h4 class="" itemprop="headline">
			<span>Wujudkan Eventmu bersama</span>
		</h4>
	</div>
</div>
@endsection

@push('js')
  <script src="{{ asset('assets') }}/js/VenueDetail.js"></script>
@endpush