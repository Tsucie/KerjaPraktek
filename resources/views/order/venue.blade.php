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
                <h5>Informasi Penyewa Gedung/Ruangan</h5>
                <div class="form-group">
                  <label for="cst_name">Nama Pemesan</label>
                  <div class="col-sm-12">
                      <input class="form-control form-inputs" type="text" name="cst_name" id="cst_name" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label for="gst_nama">Nama Penyewa</label>
                  <div class="col-sm-12">
                      <input class="form-control form-inputs" type="text" name="gst_nama" id="gst_nama" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label for="gst_alamat">Alamat Penyewa</label>
                  <div class="col-sm-12">
                      <textarea class="form-control form-inputs" name="gst_alamat" id="gst_alamat" rows="5" disabled></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="gst_no_telp">No. Telp/Hp Penyewa</label>
                  <div class="col-sm-12">
                      <input class="form-control form-inputs" type="text" name="gst_no_telp" id="gst_no_telp" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label for="gst_rencana_pemakaian">Rencana Pemakaian</label>
                  <div class="col-sm-12">
                      <input class="form-control form-inputs" type="text" name="gst_rencana_pemakaian" id="gst_rencana_pemakaian" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label for="gst_rencana_pemakaian">Waktu Pemakaian</label>
                  <div class="col-sm-12">
                      <input class="form-control form-inputs" type="text" name="gst_waktu_pemakaian" id="gst_waktu_pemakaian" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label for="gst_keperluan_pemakaian">Keperluan Pemakaian</label>
                  <div class="col-sm-12">
                      <input class="form-control form-inputs" type="text" name="gst_keperluan_pemakaian" id="gst_keperluan_pemakaian" disabled>
                  </div>
                </div>
                <h5>Ketentuan Pemakaian Gedung</h5>
                <div class="form-grout">
                    <label>Jam Pemakaian Gedung</label>
                    <div class="col-sm-12">
                        <input class="form-control form-inputs" type="text" id="vnu_jam_pemakaian_siang" disabled>
                        <input class="form-control form-inputs" type="text" id="vnu_jam_pemakaian_malam" disabled>
                    </div>
                </div>
                <div class="form-group">
                  <label for="vnu_nama">Nama Gedung/Ruangan</label>
                  <div class="col-sm-12">
                      <input class="form-control form-inputs" type="text" name="vnu_nama" id="vnu_nama" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label for="vnu_harga">Harga Sewa Gedung/Ruangan</label>
                  <div class="col-sm-12">
                    <input class="form-control form-inputs" type="text" name="vnu_harga" id="vnu_harga" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_no_telp">No. Telp/Hp</label>
                  <div class="col-sm-12">
                    <input class="form-control form-inputs" type="text" name="ov_no_telp" id="ov_no_telp" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_nama_catering">Nama Catering</label>
                  <div class="col-sm-12">
                    <input class="form-control form-inputs" type="text" name="ov_nama_catering" id="ov_nama_catering" placeholder="Nama Cateringnya">
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_fee_catering">Fee Catering</label>
                  <div class="col-sm-12">
                    <input class="form-control form-inputs" type="tel" name="ov_fee_catering" id="ov_fee_catering" placeholder="Biaya Catering">
                  </div>
                </div>
                <div class="form-group" id="pl_fee">
                  <label for="ov_fee_pelaminan">Fee Pelaminan</label>
                  <div class="col-sm-12">
                    <input class="form-control form-inputs" type="tel" name="ov_fee_pelaminan" id="ov_fee_pelaminan" placeholder="Biaya Pelaminan">
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_biaya_lain">Biaya Lain-lain</label>
                  <div class="col-sm-12">
                    <input class="form-control form-inputs" type="tel" name="ov_biaya_lain" id="ov_biaya_lain" placeholder="Biaya tambahan lainnya">
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_more_facilities">Fasilitas Tambahan</label>
                  <div class="col-sm-12">
                    <textarea class="form-control form-inputs" name="ov_more_facilities" id="ov_more_facilities" rows="5" placeholder="Fasilitas tambahan yang diminta pemesan"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_lain_lain">Total Biaya Fasilitas</label>
                  <div class="col-sm-12">
                    <input class="form-control form-inputs" type="tel" name="ov_lain_lain" id="ov_lain_lain" placeholder="Total Biaya Fasilitas Tambahannya">
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_sum_lain_lain">Subtotal Biaya Lain-lain</label>
                  <div class="col-sm-12">
                    {{-- <!-- Generated from sum('ov_biaya_lain','ov_fee_catering','ov_fee_pelaminan','ov_lain_lain') --> --}}
                    <input class="form-control form-inputs" type="tel" name="ov_sum_lain_lain" id="ov_sum_lain_lain" placeholder="Subtotal Biaya Lain-lain">
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_sum_biaya">Total Biaya</label>
                  <div class="col-sm-12">
                    {{-- <!-- Generated from sum('vnu_harga','ov_sum_lain_lain') --> --}}
                    <input class="form-control form-inputs" type="tel" name="ov_sum_biaya" id="ov_sum_biaya" placeholder="Total Biaya">
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_down_payment">DP (Uang Muka)</label>
                  <div class="col-sm-12">
                    <input class="form-control form-inputs" type="tel" name="ov_down_payment" id="ov_down_payment" placeholder="Uang muka yang sudah dibayar oleh Penyewa">
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_remaining_payment">Kekurangan Biaya</label>
                  <div class="col-sm-12">
                    <input class="form-control form-inputs" type="tel" name="ov_remaining_payment" id="ov_remaining_payment" placeholder="Kekurangan Biaya yang harus dilunasi">
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_status_order">Ubah Status Order</label>
                  <div class="col-sm-12">
                    <select class="form-control form-inputs" name="ov_status_order" id="ov_status_order">
                      <option value="0">Dalam Proses</option>
                      <option value="1">Terverifikasi</option>
                      <option value="2">Sudah Bayar uang muka</option>
                      <option value="3">Sudah Lunas</option>
                      <option value="4">Ditolak</option>
                    </select>
                  </div>
                </div>
                <div class="form-group d-none" id="div-input-bukti">
                  <label for="images">Bukti Transfer</label>
                    <input type="file" name="images" id="ov_bukti_transfer_file" accept="image/*">
                    <div class="gallery" id="images-gallery"></div>
                </div>
                <div class="form-group">
                  <label for="ov_contact_customer">Sudah Menghubungi Customer via WA/Telp?</label>
                  <div class="col-sm-12">
                    <select class="form-control form-inputs" name="ov_contact_customer" id="ov_contact_customer">
                      <option value="0">Belum</option>
                      <option value="1">Sudah</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label for="ov_note_to_customer">Catatan untuk Customer</label>
                  <div class="col-sm-12">
                    <textarea class="form-control form-inputs" name="ov_note_to_customer" id="ov_note_to_customer" rows="5"></textarea>
                  </div>
                </div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success" id="btn-edit-ov">Simpan</button>
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
          <h5 class="modal-title" id="DeleteModalTitle">Hapus Order Penyewaan Gedung/Ruangan</h5>
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
                    <h3 class="text-white mb-0">Order Gedung/Ruangan</h3>
                </div>
                <!-- Dark table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col" class="sort" data-sort="name">Nama Gedung</th>
                        <th scope="col" class="sort" data-sort="gst_nama">Atas Nama</th>
                        <th scope="col" class="sort" data-sort="gst_rencana_pemakaian">Rencana Pemakaian</th>
                        <th scope="col" class="sort" data-sort="price">Biaya</th>
                        <th scope="col" class="sort" data-sort="status">Status Order</th>
                        <th scope="col" class="sort" data-sort="action">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse ($data as $item)
                            <tr>
                                <td class="name">{{ $item->ov_vnu_nama }}</td>
                                <td class="gst_nama">{{ $item->gst_nama }}</td>
                                <td class="gst_rencana_pemakaian">{{ $item->gst_rencana_pemakaian.' - '. $item->gst_waktu_pemakaian }}</td>
                                <td class="harga">Rp {{ $item->ov_sum_biaya }}</td>
                                <td>
                                    @switch($item->ov_status_order)
                                        @case(0)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-warning"></i>
                                                <span class="status" title="Order {{ $item->ov_vnu_nama.' atas nama '.$item->gst_nama }} Butuh Proses">Butuh Proses</span>
                                            </span>
                                            @break
                                        @case(1)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-success"></i>
                                                <span class="status" title="Order {{ $item->ov_vnu_nama.' atas nama '.$item->gst_nama }} Sudah Terverifikasi">Sudah Terverifikasi</span>
                                            </span>
                                            @break
                                        @case(2)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-primary"></i>
                                                <span class="status" title="Order {{ $item->ov_vnu_nama.' atas nama '.$item->gst_nama }} Sudah Bayar Uang Muka">Sudah Bayar Uang Muka</span>
                                            </span>
                                            @break
                                        @case(3)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-green"></i>
                                                <span class="status" title="Order {{ $item->ov_vnu_nama.' atas nama'.$item->gst_nama }} Sudah Lunas">Sudah Lunas</span>
                                            </span>
                                            @break
                                        @case(4)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-danger"></i>
                                                <span class="status" title="Order {{ $item->ov_vnu_nama.' atas nama '.$item->gst_nama }} Ditolak">Ditolak</span>
                                            </span>
                                            @break
                                        @default
                                    @endswitch
                                </td>
                                <td class="action">
                                    @if ($item->ov_status_order == 0)
                                        <a class="btn btn-sm btn-success btn-table" data-toggle="tooltip" data-html="true" title="Proses Order" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->ov_id }}" onclick="ShowEditModals(this)" data_text="Proses Pemesanan">Proses Order</a>
                                    @endif
                                    @if ($item->ov_status_order == 1 || $item->ov_status_order == 2)
                                        <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->ov_id }}" onclick="ShowDetails(this)">Detail Order</a>
                                        <a class="btn btn-sm btn-primary btn-table" data-toggle="tooltip" data-html="true" title="Ubah Data" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->ov_id }}" onclick="ShowEditModals(this)" data_text="Ubah Pemesanan">Ubah Order</a>
                                    @endif
                                    @if ($item->ov_status_order == 3 || $item->ov_status_order == 4)
                                        <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->ov_id }}" onclick="ShowDetails(this)">Detail Order</a>
                                        <a class="btn btn-sm btn-danger btn-table" data-toggle="tooltip" data-html="true" title="Hapus Data" id="btndelete{{ $loop->index }}" role="button" data_id="{{ $item->ov_id }}" data_name="{{ $item->gst_nama }}" onclick="DeleteOrder(this)">Hapus</a>
                                    @endif
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
                    {{-- Pagination --}}
                    <div class="card-footer bg-transparent d-flex justify-content-end">
                        {!! $data->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.footers.auth')
</div>
@endsection

@push('js')
    <script src="{{ asset('assets') }}/js/OrderVenues.js"></script>
@endpush