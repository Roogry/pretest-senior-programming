@extends('layouts')
@section('title', 'Semua Penjualan')
@section('content')
    <div class="margin-tb d-flex justify-content-between">
        <div class="pull-left">
            <h2 class=""> Semua Penjualan</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary rounded-3 py-2" href="{{ route('penjualan.create') }}">
                <i class="bi bi-plus-lg"></i> Buat Penjualan
            </a>
        </div>
    </div>
    <div class="card mt-4 mb-4 rounded-4 border-light shadow-sm">
        <div class="card-body px-4">
            <div class="row gy-3 gx-0 align-items-center mt-3">
                @if ($message = Session::get('success'))
                    <div class="col-md-8 col-12">
                        <div class="alert alert-success alert-dismissible fade show m-0 py-2 mx-2">
                            <p class="mb-0">{{ $message }}</p>
                            <button type="button" class="btn-close py-2 mt-1" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                <div class="col-md-4 col-12 ms-auto">
                    <form action="{{ route('penjualan.index') }}">
                        <div class="d-flex px-2">
                            <div class="flex-fill me-3">
                                <input name="search" type="text" value="{{ request('search') }}"
                                    class="form-control py-2" placeholder="Cari Total Penjualan">
                            </div>
                            <button type="submit" class="btn btn-primary rounded-3 py-2">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table mt-3 align-middle text-black-50 border-light">
                    <thead class="text-body">
                        <tr>
                            <th scope="col" class="ps-4 border-light">No</th>
                            <th scope="col" class="border-light">Nama Pelanggan</th>
                            <th scope="col" class="border-light">Total Penjualan</th>
                            <th scope="col" class="border-light">Tanggal Pembelian</th>
                            <th scope="col" class="border-light" width="100px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualan as $itemPenjualan)
                            <tr>
                                <th scope="row" class="ps-4">{{ $loop->iteration }}</th>
                                <td class="fw-bolder">{{ $itemPenjualan->pelanggan->nama_pelanggan }}</td>
                                <td>{{ currency($itemPenjualan->total_penjualan) }}</td>
                                <td>{{ $itemPenjualan->tgl_jual }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-light dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('penjualan.edit', $itemPenjualan->id_penjualan) }}">Edit</a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item delete-item" data-bs-toggle="modal"
                                                    data-bs-target="#modalDeletePenjualan"
                                                    data-id="{{ $itemPenjualan->id_penjualan }}"
                                                    type="submit">Delete</button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDeletePenjualan" tabindex="-1" aria-labelledby="modalDeletePenjualan"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeletePenjualanLabel">Hapus Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Apakah yakin ingin menghapus pengajuan? Pengajuan yang dihapus tidak akan bisa
                        dikembalikan kembali</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

                    <form id="modal-form-del" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.delete-item', function() {
            let id = $(this).attr('data-id');
            $('#modal-form-del').attr('action', "./penjualan/" + id);
        });
    </script>
@endsection
