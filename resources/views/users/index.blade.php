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
          <form class="form-horizontal" action="" method="" id="form">
              @csrf
              <div class="box-body">
                  <div class="form-group">
                      <label for="email">Email</label>
                      <div class="col-sm-12">
                          <input type="email" class="form-control form-inputs" placeholder="admin@gmail.com" id="email" name="email">
                          <div aria-hidden="true" id="email-alrt" class="alert alert-danger" role="alert">
                              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                              <span class="sr-only">Error:</span>
                              Email tidak boleh kosong!
                          </div>
                      </div>
                  </div>
                  <div class="form-group" id="pass-txtbox">
                      <label for="password">Password</label>
                      <div class="col-sm-12">
                          <input type="password" class="form-control form-inputs" placeholder="Password123" id="password" name="password">
                          <div aria-hidden="true" id="password-alrt" class="alert alert-danger" role="alert">
                              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                              <span class="sr-only">Error:</span>
                              Password tidak boleh kosong!
                          </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="nama">Nama Admin</label>
                      <div class="col-sm-12">
                          <input type="text" class="form-control form-inputs" placeholder="Admin Siapa" id="nama" name="nama">
                      </div>
                  </div>
              </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-success" id="btn-add-user">Tambah</button>
          <button type="button" class="btn btn-success" id="btn-edit-user">Simpan</button>
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
          <h5 class="modal-title" id="DeleteModalTitle">Hapus User Admin</h5>
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
<div class="header bg-gradient-red pb-8 pt-5 pt-md-8"></div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col">
            <div class="card bg-default shadow shadow-dark">
                <!-- Card header -->
                <div class="card-header bg-transparent border-0">
                    <h3 class="text-white mb-0">User Admin</h3>
                    <div class="pull-right">
                        <a href="#" class="btn btn-success" role="button" data-toggle="modal" data-target="#AddEditModal" id="Add-btn">Add User</a>
                    </div>
                </div>
                <!-- Dark table -->
                <div class="table-responsive">
                    <table class="table align-items-center table-dark table-flush">
                    <thead class="thead-dark">
                        <tr>
                        <th scope="col" class="sort" data-sort="name">Nama</th>
                        <th scope="col" class="sort" data-sort="email">Email</th>
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
                                            <span class="name mb-0 text-sm">{{ $item->name }}</span>
                                        </div>
                                    </div>
                                </th>
                                <td class="email">{{ $item->email }}</td>
                                <td class="created">{{ $item->created_at }}</td>
                                <td class="action">
                                    <a class="btn btn-sm btn-info btn-table" data-toggle="tooltip" data-html="true" title="Lihat Detail Admin" id="btndetail{{ $loop->index }}" role="button" data_id="{{ $item->id }}" onclick="ShowDetails(this)">Detail</a>
                                    <a class="btn btn-sm btn-primary btn-table" data-toggle="tooltip" data-html="true" title="Ubah Data Admin" id="btnedit{{ $loop->index }}" role="button" data_id="{{ $item->id }}" onclick="ShowEditModals(this)">Ubah</a>
                                    <a class="btn btn-sm btn-danger btn-table" data-toggle="tooltip" data-html="true" title="Hapus Data Admin" id="btndelete{{ $loop->index }}" role="button" data_id="{{ $item->id }}" data_name="{{ $item->name }}" onclick="DeleteUser(this)">Hapus</a>
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
    <script src="{{ asset('assets') }}/js/Users.js"></script>
@endpush