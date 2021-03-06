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
                      <label for="images">Gambar-gambar Gedung/Ruangan</label>
                      <input type="file" name="images" id="gallery-photo-add" multiple>
                      <div class="gallery" id="images-gallery"></div>
                  </div>
                  <div class="form-group">
                      <label for="nama">Nama Gedung/Ruangan</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control form-inputs" placeholder="Nama Gedungnya" id="nama" name="nama">
                          <div aria-hidden="true" id="nama-alrt" class="alert alert-danger" role="alert">
                              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                              <span class="sr-only">Error:</span>
                              Nama Gedung tidak boleh kosong!
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="desc">Deskripsi</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control form-inputs" placeholder="Detail deskripsi Gedungnya" id="desc" name="desc">
                      </div>
                  </div>
                  <!-- Fasilitas -->
                  <div class="form-group">
                      <label for="fasilitas">Fasilitas</label>
                      <div class="col-sm-12">
                        <textarea class="form-control form-inputs" rows="5" name="fasilitas" id="fasilitas" placeholder="Contoh Format:
- Gedung Full AC"
                        required></textarea>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="harga">Harga</label>
                      <div class="col-sm-12">
                          <input type="tel" class="form-control form-inputs" placeholder="Harga sewa nya" id="harga" name="harga">
                          <div aria-hidden="true" id="harga-alrt" class="alert alert-danger" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">Error:</span>
                            Harga Gedung tidak boleh kosong atau nol!
                        </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="tipe_waktu">Tipe Waktu Sewa</label>
                      <div class="col-sm-12">
                        <select class="form-control form-inputs" name="tipe_waktu" id="tipe_waktu">
                            <option value="0">Per-Setengah Hari</option>
                            <option value="1">Per-Jam</option>
                        </select>
                      </div>
                  </div>
                  <div class="form-group" id="siang">
                    <label for="jam_siang">Jam Pemakaian Siang</label>
                    <div class="col-sm-12">
                        <input type="tel" class="form-control form-inputs" placeholder="Jam Pemakaian waktu Siang" id="jam_siang" name="jam_siang">
                    </div>
                  </div>
                  <div class="form-group" id="malam">
                    <label for="jam_malam">Jam Pemakaian Malam</label>
                    <div class="col-sm-12">
                        <input type="tel" class="form-control form-inputs" placeholder="Jam Pemakaian waktu Malam" id="jam_malam" name="jam_malam">
                    </div>
                  </div>
                  <div class="form-group">
                      <label for="ketentuan_sewa">Ketentuan Sewa Gedung</label>
                      <div class="col-sm-12">
                        <textarea class="form-control form-inputs" name="ketentuan_sewa" id="ketentuan_sewa" rows="5" placeholder="Contoh Format:
- DP (Down Payment) sewa Gedung minimal Rp. 2.000.000"
                        ></textarea>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="status_tersedia">Status Tersedia</label>
                      <div class="col-sm-12">
                          <select class="form-control form-inputs" name="status_tersedia" id="status_tersedia">
                              <option value="1">Tersedia</option>
                              <option value="0">Tidak Tersedia</option>
                          </select>
                      </div>
                  </div>
              </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success" id="btn-add-vnu">Tambah</button>
          <button type="button" class="btn btn-success" id="btn-edit-vnu">Simpan</button>
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
          <h5 class="modal-title" id="DeleteModalTitle">Hapus Layanan Penyewaan Gedung/Ruangan</h5>
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
                    <h3 class="text-white mb-0">Gedung</h3>
                    <div class="pull-right">
                        <a href="#" class="btn btn-success" role="button" data-toggle="modal" data-target="#AddEditModal" id="Add-btn">Tambah Gedung</a>
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
                        <th scope="col" class="sort" data-sort="price">Harga</th>
                        <th scope="col" class="sort" data-sort="status">Status Tersedia</th>
                        <th scope="col" class="sort" data-sort="action">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse ($data as $item)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <a href="#" class="service-photo rounded-circle mr-3">
                                            @if ($item->vp_photo != null)
                                                <img src="data:image/{{ pathinfo($item->vp_filename, PATHINFO_EXTENSION) }};base64,{{ $item->vp_photo }}" alt="img" width="64px" height="auto">
                                            @else
                                                {{ __('No Photo') }}
                                            @endif
                                        </a>
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ $item->vnu_nama }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="harga">Rp {{ number_format($item->vnu_harga, 2) }}</td>
                                <td>
                                    @if ($item->vnu_status_tersedia == 1)
                                        <span class="badge badge-dot mr-4">
                                            <i class="bg-success"></i>
                                            <span class="status" title="{{ $item->vnu_nama }} Tersedia">Tersedia</span>
                                        </span>
                                    @else
                                        <span class="badge badge-dot mr-4">
                                            <i class="bg-danger"></i>
                                            <span class="status" title="{{ $item->vnu_nama }} Tidak Tersedia">Tidak Tersedia</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="action">
                                    <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->vnu_id }}" onclick="ShowDetails(this)">Detail</a>
                                    <a class="btn btn-sm btn-primary btn-table" data-toggle="tooltip" data-html="true" title="Ubah Data" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->vnu_id }}" onclick="ShowEditModals(this)">Ubah</a>
                                    <a class="btn btn-sm btn-danger btn-table" data-toggle="tooltip" data-html="true" title="Hapus Data" id="btndelete{{ $loop->index }}" role="button" data_id="{{ $item->vnu_id }}" data_name="{{ $item->vnu_nama }}" onclick="DeleteVenue(this)">Hapus</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
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
    <script src="{{ asset('assets') }}/js/Venues.js"></script>
@endpush