@extends('layouts.app')

@section('content')
<div class="header bg-gradient-info pb-8 pt-5 pt-md-8"></div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow shadow-dark">
                <!-- Card header -->
                <div class="card-header bg-transparent border-0">
                    <h3 class="text-white mb-0">Feedback</h3>
                </div>
                <!-- Dark table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col" class="sort" data-sort="name">Nama Customer</th>
                        <th scope="col" class="sort" data-sort="text">Kritik & Saran</th>
                        <th scope="col" class="sort" data-sort="status">Status Order</th>
                        <th scope="col" class="sort" data-sort="created">Tanggal Dibuat</th>
                        <th scope="col" class="sort" data-sort="action">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse ($data as $item)
                            <tr>
                                <td class="name">{{ $item->cst_name }}</td>
                                <td class="text">{{ $item->fb_text }}</td>
                                <!-- status_order -->
                                <td>
                                @if ($item->op_status_order)
                                    @switch($item->op_status_order)
                                        @case(3)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-green"></i>
                                                <span class="status" title="Order {{ 'atas nama'.$item->cst_name }} Sudah Dikirim">Sudah Dikirim</span>
                                            </span>
                                            @break
                                        @case(4)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-danger"></i>
                                                <span class="status" title="Order {{ 'atas nama '.$item->cst_name }} Dibatalkan">Dibatalkan</span>
                                            </span>
                                            @break
                                        @default
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-secondary"></i>
                                                <span class="status" title="Order {{ 'atas nama '.$item->cst_name }} Tidak Diketahui">Tidak Diketahui</span>
                                            </span>
                                    @endswitch
                                @else
                                    @switch($item->ov_status_order)
                                        @case(3)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-green"></i>
                                                <span class="status" title="Order {{ 'atas nama'.$item->cst_name }} Sudah Lunas">Sudah Lunas</span>
                                            </span>
                                            @break
                                        @case(4)
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-danger"></i>
                                                <span class="status" title="Order {{ 'atas nama '.$item->cst_name }} Dibatalkan">Dibatalkan</span>
                                            </span>
                                            @break
                                        @default
                                            <span class="badge badge-dot mr-4">
                                                <i class="bg-secondary"></i>
                                                <span class="status" title="Order {{ 'atas nama '.$item->cst_name }} Tidak Diketahui">Tidak Diketahui</span>
                                            </span>
                                    @endswitch
                                @endif
                                </td>
                                <td class="created">{{ $item->created_at }}</td>
                                <td class="action">
                                    <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->fb_id }}" onclick="ShowDetails(this)">Detail Feedback</a>
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
    <script src="{{ asset('assets') }}/js/Feedbacks.js"></script>
@endpush