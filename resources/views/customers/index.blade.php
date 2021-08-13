@extends('layouts.app')

@section('content')
<!-- .modal -->
<div class="modal fade" id="DetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Informasi Customer</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" action="" method="" id="form">
              <div class="box-body">
                  <div class="form-group">
                      <label for="nama">Nama Customer</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control form-inputs" placeholder="Tidak ada Data" id="nama" name="nama" disabled>
                      </div>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <div class="col-sm-12">
                        <input type="email" class="form-control form-inputs" placeholder="Tidak ada Data" id="email" name="email" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="no_telp">Nomor Telpon</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control form-inputs" placeholder="Tidak ada Data" id="no_telp" name="no_telp" disabled>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="alamat">Alamat Customer</label>
                    <div class="col-sm-12">
                        <textarea class="form-control form-inputs" placeholder="Tidak ada Data" name="alamat" id="alamat" disabled></textarea>
                    </div>
                  </div>
              </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<!-- /.modal -->
<div class="header bg-gradient-info pb-8 pt-5 pt-md-8"></div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow shadow-dark">
                <!-- Card header -->
                <div class="card-header bg-transparent border-0">
                    <h3 class="text-white mb-0">User Customer</h3>
                </div>
                <!-- Dark table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col" class="sort" data-sort="name">Nama</th>
                        <th scope="col" class="sort" data-sort="email">Email</th>
                        <th scope="col" class="sort" data-sort="no_telp">Nomor Telpon</th>
                        <th scope="col" class="sort" data-sort="created">Tanggal Dibuat</th>
                        <th scope="col" class="sort" data-sort="action">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @forelse ($data as $item)
                            <tr>
                                <th scope="row">
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                            <span class="name mb-0 text-sm">{{ $item->cst_name }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="email">{{ $item->cst_email }}</td>
                                <td class="no_telp">{{ $item->cst_no_telp }}</td>
                                <td class="created">{{ $item->created_at }}</td>
                                <td class="action">
                                    <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Data" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->cst_id }}" onclick="ShowDetails(this)">Informasi Customer</a>
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
    <script src="{{ asset('assets') }}/js/Customers.js"></script>
@endpush