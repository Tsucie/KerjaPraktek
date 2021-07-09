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
                  <!-- Images -->
                  <div class="form-group">
                      <label for="images">Gambar-gambar Produk</label>
                      <input type="file" name="images" id="gallery-photo-add" multiple>
                      <div class="gallery" id="images-gallery"></div>
                  </div>
                  <div class="form-group">
                      <label for="nama">Nama Produk</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control form-inputs" placeholder="Nama Produknya" id="nama" name="nama">
                          <div aria-hidden="true" id="nama-alrt" class="alert alert-danger" role="alert">
                              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                              <span class="sr-only">Error:</span>
                              Nama Produk tidak boleh kosong!
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="desc">Deskripsi</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control form-inputs" placeholder="Detail deskripsi Produknya" id="desc" name="desc">
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="harga">Harga</label>
                      <div class="col-sm-12">
                          <input type="tel" class="form-control form-inputs" placeholder="Harganya" id="harga" name="harga">
                          <div aria-hidden="true" id="harga-alrt" class="alert alert-danger" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">Error:</span>
                            Harga Produk tidak boleh kosong atau nol!
                        </div>
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="stock">Stok Produk</label>
                    <div class="col-sm-12">
                        <input type="tel" class="form-control form-inputs" placeholder="stock produknya" id="stock" name="stock">
                        <div aria-hidden="true" id="stock-alrt" class="alert alert-danger" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">Error:</span>
                            Stock Produk tidak boleh kosong!
                        </div>
                    </div>
                  </div>
              </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success" id="btn-add-pdct">Tambah</button>
          <button type="button" class="btn btn-success" id="btn-edit-pdct">Simpan</button>
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
          <h5 class="modal-title" id="DeleteModalTitle">Hapus Layanan Produk</h5>
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
<div class="header bg-gradient-green pb-8 pt-5 pt-md-8"></div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow shadow-dark">
                <!-- Card header -->
                <div class="card-header bg-transparent border-0">
                    <h3 class="text-white mb-0">Produk</h3>
                    <div class="pull-right">
                        <a href="#" class="btn btn-success" role="button" data-toggle="modal" data-target="#AddEditModal" id="Add-btn">Tambah Produk</a>
                    </div>
                </div>
                <!-- Dark table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col" class="sort" data-sort="photo">Photo
                            <span class="sort" data-sort="name">&nbsp;&nbsp;&nbsp;&nbsp;Nama</span>
                        </th>
                        <th scope="col" class="sort" data-sort="kode">Kode</th>
                        <th scope="col" class="sort" data-sort="price">Harga</th>
                        <th scope="col" class="sort" data-sort="stock">Stok</th>
                        <th scope="col" class="sort" data-sort="action">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse ($data as $item)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="#" class="service-photo rounded-circle mr-3">
                                            @if ($item->pp_photo != null)
                                                <img src="data:image/{{ pathinfo($item->pp_filename, PATHINFO_EXTENSION) }};base64,{{ $item->pp_photo }}" alt="img" width="64px" height="auto">
                                            @else
                                                {{ __('No Photo') }}
                                            @endif
                                        </a>
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ $item->pdct_nama }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="kode">{{ $item->pdct_kode }}</td>
                                <td class="harga">Rp {{ number_format($item->pdct_harga, 2) }}</td>
                                <td class="stock">{{ $item->pdct_stock }}</td>
                                <td class="action">
                                    <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="See All Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->pdct_id }}" onclick="ShowDetails(this)">Detail</a>
                                    <a class="btn btn-sm btn-primary btn-table" data-toggle="tooltip" data-html="true" title="Edit Data" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->pdct_id }}" onclick="ShowEditModals(this)">Ubah</a>
                                    <a class="btn btn-sm btn-danger btn-table" data-toggle="tooltip" data-html="true" title="Delete Data" id="btndelete{{ $loop->index }}" role="button" data_id="{{ $item->pdct_id }}" data_name="{{ $item->pdct_nama }}" onclick="DeleteProduct(this)">Hapus</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
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
    <script src="{{ asset('assets') }}/js/Products.js"></script>
@endpush