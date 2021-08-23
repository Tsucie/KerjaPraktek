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
                  <h5>Informasi Pemesan</h5>
                  <div class="form-group">
                    <label for="cst_name">Nama Pemesan</label>
                    <div class="col-sm-12">
                        <input class="form-control form-inputs" type="text" name="cst_name" id="cst_name" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="cst_alamat">Alamat</label>
                    <div class="col-sm-12">
                        <textarea class="form-control form-inputs"  name="cst_alamat" id="cst_alamat" rows="3"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="cst_no_telp">No. Telp/Hp</label>
                    <div class="col-sm-12">
                        <input class="form-control form-inputs" type="text" name="cst_no_telp" id="cst_no_telp" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="cst_email">Email</label>
                    <div class="col-sm-12">
                        <input class="form-control form-inputs" type="text" name="cst_email" id="cst_email" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_tanggal_order">Tanggal Order</label>
                    <div class="col-sm-12">
                        <input class="form-control form-inputs" type="text" name="op_tanggal_order" id="op_tanggal_order" disabled>
                    </div>
                  </div>
                  <h5>Detail Pemesanan Produk</h5>
                  <div class="form-group">
                    <label for="pdct_nama">Nama Produk</label>
                    <div class="col-sm-12">
                        <input class="form-control form-inputs" type="text" name="pdct_nama" id="pdct_nama" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="pdct_kode">Kode Produk</label>
                    <div class="col-sm-12">
                        <input class="form-control form-inputs" type="text" name="pdct_kode" id="pdct_kode" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="pdct_harga">Harga Produk</label>
                    <div class="col-sm-12">
                      <input class="form-control form-inputs" type="text" name="pdct_harga" id="pdct_harga" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="odp_pdct_qty">Jumlah Pemesanan</label>
                    <div class="col-sm-12">
                      <input class="form-control form-inputs" type="text" name="odp_pdct_qty" id="odp_pdct_qty" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_sum_harga_produk">Total Harga</label>
                    <div class="col-sm-12">
                      <input class="form-control form-inputs" type="tel" name="op_sum_harga_produk" id="op_sum_harga_produk" placeholder="Total Biaya Harga Produk" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_lokasi_pengiriman">Lokasi Pengiriman</label>
                    <div class="col-sm-12">
                      <textarea class="form-control form-inputs" name="op_lokasi_pengiriman" id="op_lokasi_pengiriman" rows="3"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_harga_ongkir">Harga Ongkir</label>
                    <div class="col-sm-12">
                      <input class="form-control form-inputs" type="tel" name="op_harga_ongkir" id="op_harga_ongkir" placeholder="Biaya Ongkir">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_persen_pajak">Pajak (%)</label>
                    <div class="col-sm-12">
                      <input class="form-control form-inputs" type="tel" name="op_persen_pajak" id="op_persen_pajak" placeholder="Persen pajak, contoh: 10% ditulis 10">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_nominal_pajak">Nominal Pajak (Rp)</label>
                    <div class="col-sm-12">
                      <!-- Generated from (op_sum_harga_produk * op_persen_pajak / 100) -->
                      <input class="form-control form-inputs" type="tel" name="op_nominal_pajak" id="op_nominal_pajak" placeholder="Nominal pajaknya dalam Rupiah">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_alamat_pengiriman">Alamat Pengiriman</label>
                    <div class="col-sm-12">
                      <textarea class="form-control form-inputs" name="op_alamat_pengiriman" id="op_alamat_pengiriman" rows="5"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_alamat_pemesanan">Alamat Pemesanan</label>
                    <div class="col-sm-12">
                      <textarea class="form-control form-inputs" name="op_alamat_pemesanan" id="op_alamat_pemesanan" rows="5"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_sum_biaya">Total Biaya</label>
                    <div class="col-sm-12">
                      <!-- Generated from sum('op_sum_harga_produk','op_harga_ongkir','op_nominal_pajak') -->
                      <input class="form-control form-inputs" type="tel" name="op_sum_biaya" id="op_sum_biaya" placeholder="Total Biaya">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_status_order">Ubah Status Order</label>
                    <div class="col-sm-12">
                      <select class="form-control form-inputs" name="op_status_order" id="op_status_order">
                        <option value="0">Dalam Proses</option>
                        <option value="1">Terverifikasi</option>
                        <option value="2">Sudah Bayar</option>
                        <option value="3">Sudah Dikirim</option>
                        <option value="4">Ditolak</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group d-none" id="input-bukti">
                    <label for="op_bukti_transfer_file">Bukti Transfer</label>
                    <input type="file" name="images" id="op_bukti_transfer_file" accept="image/*">
                    <div class="gallery" id="bukti-gallery"></div>
                  </div>
                  <div class="form-group d-none" id="input-resi">
                    <label for="op_resi_file">Upload Resi</label>
                    <input type="file" name="images" id="op_resi_file" accept="image/*">
                    <div class="gallery" id="resi-gallery"></div>
                  </div>
                  <div class="form-group">
                    <label for="op_contact_customer">Sudah Contact Customer via WA/Telp?</label>
                    <div class="col-sm-12">
                      <select class="form-control form-inputs" name="op_contact_customer" id="op_contact_customer">
                        <option value="0">Belum</option>
                        <option value="1">Sudah</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="op_note_to_customer">Catatan untuk Customer</label>
                    <div class="col-sm-12">
                      <textarea class="form-control form-inputs" name="op_note_to_customer" id="op_note_to_customer" rows="5"></textarea>
                    </div>
                  </div>
              </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success" id="btn-edit-op">Simpan</button>
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
          <h5 class="modal-title" id="DeleteModalTitle">Hapus Order Pembelian Produk</h5>
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
                    <h3 class="text-white mb-0">Order Produk</h3>
                </div>
                <!-- Dark table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col" class="sort" data-sort="name">Nama Produk</th>
                        <th scope="col" class="sort" data-sort="cst_name">Atas Nama</th>
                        <th scope="col" class="sort" data-sort="op_tanggal_order">Tanggal Order</th>
                        <th scope="col" class="sort" data-sort="price">Biaya</th>
                        <th scope="col" class="sort" data-sort="status">Status Order</th>
                        <th scope="col" class="sort" data-sort="action">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse ($data as $item)
                            <tr>
                                <td class="name">{{ $item->pdct_nama }}</td>
                                <td class="cst_name">{{ $item->cst_name }}</td>
                                <td class="op_tanggal_order">{{ $item->op_tanggal_order }}</td>
                                <td class="price">Rp {{ number_format($item->op_sum_biaya, 2) }}</td>
                                <!-- op_status_order -->
                                <td>
                                    @switch($item->op_status_order)
                                        @case(0)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-warning"></i>
                                                <span class="status" title="Order {{ $item->pdct_nama.' atas nama '.$item->cst_name }} Butuh Proses">Butuh Proses</span>
                                            </span>
                                            @break
                                        @case(1)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-success"></i>
                                                <span class="status" title="Order {{ $item->pdct_nama.' atas nama '.$item->cst_name }} Sudah Terverifikasi">Sudah Terverifikasi</span>
                                            </span>
                                            @break
                                        @case(2)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-primary"></i>
                                                <span class="status" title="Order {{ $item->pdct_nama.' atas nama '.$item->cst_name }} Sudah Bayar">Sudah Bayar</span>
                                            </span>
                                            @break
                                        @case(3)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-green"></i>
                                                <span class="status" title="Order {{ $item->pdct_nama.' atas nama'.$item->cst_name }} Sudah Dikirim">Sudah Dikirim</span>
                                            </span>
                                            @break
                                        @case(4)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-danger"></i>
                                                <span class="status" title="Order {{ $item->pdct_nama.' atas nama '.$item->cst_name }} Ditolak">Ditolak</span>
                                            </span>
                                            @break
                                        @default
                                    @endswitch
                                </td>
                                <td class="action">
                                    @if ($item->op_status_order == 0)
                                        <a class="btn btn-sm btn-success btn-table" data-toggle="tooltip" data-html="true" title="Proses Order" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->op_id }}" data_text="Proses pemesanan" onclick="ShowEditModals(this)">Proses Order</a>
                                    @endif
                                    @if ($item->op_status_order == 1 || $item->op_status_order == 2)
                                        <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->op_id }}" onclick="ShowDetails(this)">Detail Order</a>
                                        <a class="btn btn-sm btn-primary btn-table" data-toggle="tooltip" data-html="true" title="Ubah Data" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->op_id }}" data_text="Ubah pemesanan" onclick="ShowEditModals(this)">Ubah Order</a>
                                    @endif
                                    @if ($item->op_status_order == 3 || $item->op_status_order == 4)
                                        <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->op_id }}" onclick="ShowDetails(this)">Detail Order</a>
                                        <a class="btn btn-sm btn-danger btn-table" data-toggle="tooltip" data-html="true" title="Hapus Data" id="btndelete{{ $loop->index }}" role="button" data_id="{{ $item->op_id }}" data_name="{{ $item->cst_name }}" onclick="DeleteOrder(this)">Hapus</a>
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
    <script src="{{ asset('assets') }}/js/OrderProducts.js"></script>
@endpush