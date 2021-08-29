@extends('layouts.app')

@section('content')
<!-- .modal -->
<div class="modal fade" id="AddEditModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" action="" method="" id="form" enctype="multipart/form-data">
              @csrf
              <div class="box-body">
                  <!-- Pemilihan Layanan -->
                  <div class="form-group">
                      <label for="select-services">Pilih Layanan</label>
                      <div class="col-sm-12">
                          <select class="form-control form-inputs" name="select-services" id="select-services">
                              <option value="Venue">Gedung/Ruangan</option>
                              <option value="Product">Produk</option>
                          </select>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="services" id="label-service">Pilih Gedung/Ruangan</label>
                      <div class="col-sm-12">
                          <select class="form-control form-inputs" name="services" id="services"></select>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="harga">Harga Produk</label>
                      <div class="col-sm-12">
                        <input type="tel" class="form-control form-inputs" placeholder="Harga Produknya" id="harga" name="harga" disabled>
                        <div aria-hidden="true" id="harga-alrt" class="alert alert-danger" role="alert">
                          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                          <span class="sr-only">Error:</span>
                          Pilih Layanan untuk mendapatkan Harganya!
                        </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="nama">Nama Promo</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control form-inputs" placeholder="Nama Promonya" id="nama" name="nama">
                          <div aria-hidden="true" id="nama-alrt" class="alert alert-danger" role="alert">
                              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                              <span class="sr-only">Error:</span>
                              Nama Promo tidak boleh kosong!
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="desc">Deskripsi Promo</label>
                      <div class="col-sm-12">
                          <textarea class="form-control form-inputs" name="desc" id="desc" rows="4" placeholder="Deskripsi detail promonya" required></textarea>
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="diskon">Diskon Promo</label>
                    <div class="col-sm-12">
                        <input type="tel" class="form-control form-inputs" placeholder="diskonnya dalam persen. Contoh: 50% maka ditulis 50" id="diskon" name="diskon">
                        <div aria-hidden="true" id="diskon-alrt" class="alert alert-danger" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">Error:</span>
                            Diskon Promo tidak boleh kosong atau nol!
                        </div>
                    </div>
                  </div>
                  <div class="form-group" id="harga-promo">
                    <label for="prm_harga">Harga Promo</label>
                    <div class="col-sm-12">
                        <input type="tel" class="form-control form-inputs" placeholder="" id="prm_harga" name="prm_harga" disabled>
                    </div>
                  </div>
              </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success" id="btn-add-prm">Tambah</button>
          <button type="button" class="btn btn-success" id="btn-edit-prm">Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<!-- /.modal -->

<!-- .modal-dialog -->
<div class="modal fade" id="DeleteModal" tabindex="-1" role="dialog" aria-labelledby="DeleteModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="DeleteModalTitle">Hapus Promo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-danger" id="btn-hps" data_id="" onclick="Delete(this)">Hapus</button>
        </div>
      </div>
    </div>
  </div>
<!-- /.modal-dialog -->
<div class="header bg-gradient-info pb-8 pt-5 pt-md-8"></div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow shadow-dark">
                <!-- Card header -->
                <div class="card-header bg-transparent border-0">
                    <h3 class="text-white mb-0">Promo</h3>
                    <div class="pull-right">
                        <a href="#" class="btn btn-success" role="button" data-toggle="modal" data-target="#AddEditModal" id="Add-btn">Buat Promo</a>
                    </div>
                </div>
                <!-- Dark table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col" class="sort" data-sort="photo">Photo
                            <span class="sort" data-sort="name">&nbsp;&nbsp;&nbsp;&nbsp;Nama Produk</span>
                        </th>
                        <th scope="col" class="sort" data-sort="prm_nama">Nama Promo</th>
                        <th scope="col" class="sort" data-sort="price">Harga Promo</th>
                        <th scope="col" class="sort" data-sort="diskon">Diskon</th>
                        <th scope="col" class="sort" data-sort="createdby">Dibuat Oleh</th>
                        <th scope="col" class="sort" data-sort="action">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse ($data as $item)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="#" class="service-photo rounded-circle mr-3">
                                            @if ($item->photo != null)
                                                <img src="data:image/{{ pathinfo($item->filename, PATHINFO_EXTENSION) }};base64,{{ $item->photo }}" alt="img" width="64px" height="auto">
                                            @else
                                                {{ __('No Photo') }}
                                            @endif
                                        </a>
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ $item->nama }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="prm_nama">{{ $item->prm_nama }}</td>
                                <td class="price">Rp {{ number_format($item->prm_harga_promo, 2) }}</td>
                                <td class="diskon">{{ $item->prm_disc_percent }}%</td>
                                <td class="createdby">{{ $item->created_by }}</td>
                                <td class="action">
                                    <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->prm_id }}" onclick="ShowDetails(this)">Detail</a>
                                    <a class="btn btn-sm btn-primary btn-table" data-toggle="tooltip" data-html="true" title="Ubah Data" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->prm_id }}" onclick="ShowEditModals(this)">Ubah</a>
                                    <a class="btn btn-sm btn-danger btn-table" data-toggle="tooltip" data-html="true" title="Hapus Data" id="btndelete{{ $loop->index }}" role="button" data_id="{{ $item->prm_id }}" data_name="{{ $item->prm_nama }}" onclick="DeletePromo(this)">Hapus</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <h4 style="text-align: center; color: white;">Tidak ada Data</h4>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
    <script src="{{ asset('assets') }}/js/Promo.js"></script>
@endpush