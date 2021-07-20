@extends('layouts.app')

@section('content')
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
                                <td class="harga">Rp {{ number_format($item->op_sum_harga_produk, 2) }}</td>
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
                                        <a class="btn btn-sm btn-success btn-table" data-toggle="tooltip" data-html="true" title="Proses Order" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->op_id }}" onclick="ShowEditModals(this)">Proses Order</a>
                                    @endif
                                    @if ($item->op_status_order == 1 || $item->op_status_order == 2)
                                        <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->op_id }}" onclick="ShowDetails(this)">Detail Order</a>
                                        <a class="btn btn-sm btn-primary btn-table" data-toggle="tooltip" data-html="true" title="Ubah Data" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->op_id }}" onclick="ShowEditModals(this)">Ubah Order</a>
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