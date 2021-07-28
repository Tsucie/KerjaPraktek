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
        <form class="form-horizontal" action="#"></form>
        @csrf
        <div class="box-body">
            <div class="form-group">
              <label for="cst_name">Nama Customer</label>
              <div class="col-sm-12">
                <input class="form-control form-inputs" type="text" name="cst_name" id="cst_name" disabled>
              </div>
            </div>
            <div class="form-group">
              <label for="order_type">Memesan</label>
              <div class="col-sm-12">
                <input class="form-control form-inputs" type="text" name="order_type" id="order_type" disabled>
              </div>
            </div>
            
        </div>
      </div>
    </div>
  </div>
</div>
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
                                @switch($item->fb_order_status)
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
                                </td>
                                <td class="created">{{ $item->created_at }}</td>
                                <td class="action">
                                    <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->fb_id }}" data_nama="{{ $item->cst_name }}" onclick="ShowDetails(this)">Detail Feedback</a>
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