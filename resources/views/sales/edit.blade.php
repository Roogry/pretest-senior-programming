@extends('layouts')
@section('title', 'Semua Penjualan')
@section('content')
    <div class="row">
        <h2 class="col-md-8 col-12">Edit Penjualan</h2>

        @if ($message = Session::get('success'))
        <div class="col-md-8 col-12">
            <div class="alert alert-success alert-dismissible fade show m-0 py-2">
                <p class="mb-0">{{ $message }}</p>
                <button type="button" class="btn-close py-2 mt-1" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if ($messages = Session::get('error'))
        <div class="col-md-8 col-12 mt-2">
            <div class="alert alert-danger alert-dismissible fade show m-0 py-2">
                @if (is_array($messages))
                <ul class="mb-0 ps-3">
                    @foreach ($messages as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
                @else
                <p class="mb-0">{{ $messages }}</p>
                @endif
                <button type="button" class="btn-close py-2 mt-1" data-bs-dismiss="alert"
                    aria-label="Close"></button>
            </div>
        </div>
        @endif
    </div>
    <form action="{{ route('penjualan.update', $penjualan->id_penjualan) }}" method="POST">
        @csrf
        @method('put')
        <div class="row my-4">
            <div class="col-md-8 col-12">
                <div class="card rounded-4 border-light shadow-sm">
                    <div class="card-body px-4">
                        <div class="d-flex justify-content-between">
                            <div class="pull-left">
                                <h5 class="mt-2 mb-0 mx-2">Barang Terjual</h5>
                            </div>
                            <div class="pull-right">
                                <button type="button" class="btn btn-outline-primary rounded-3 py-2" data-bs-toggle="modal"
                                    data-bs-target="#modalCreateDetail">
                                    <i class="bi bi-plus-lg"></i> Tambah Barang
                                </button>
                            </div>
                        </div>

                        <table class="table mt-3 align-middle text-black-50 border-light">
                            <thead class="text-body">
                                <tr>
                                    <th scope="col" class="border-light">Nama Barang</th>
                                    <th scope="col" class="border-light">Jenis</th>
                                    <th scope="col" class="border-light">Harga</th>
                                    <th scope="col" class="border-light" width="50px">Quantity</th>
                                    <th scope="col" class="border-light" width="50px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penjualan->details as $detail)
                                    <tr>
                                        <td class="fw-bolder">
                                            <p class="m-0">{{ $detail->barang->nama_barang }}</p>
                                        </td>
                                        <td>{{ $detail->barang->jenis->nama_jenis }}</td>
                                        <td>{{ currency($detail->harga_jual) }}</td>
                                        <td>
                                            <input type="hidden" name="barang[{{ $loop->index }}][id_barang]"
                                                value="{{ $detail->id_barang }}">
                                            <input type="hidden" name="barang[{{ $loop->index }}][harga_jual]"
                                                value="{{ $detail->harga_jual }}">
                                            <input type="number" name="barang[{{ $loop->index }}][qty]"
                                                class="form-control" min="0" value="{{ $detail->jumlah }}" />
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger delete-barang"
                                                data-bs-toggle="modal" data-bs-target="#modalDeleteDetail"
                                                data-id-penjualan="{{ $penjualan->id_penjualan }}"
                                                data-id-barang="{{ $detail->id_barang }}">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <div class="card rounded-4 border-light shadow-sm">
                    <div class="card-body px-4">
                        <h5 class="mb-2">Pilih Pelanggan</h5>
                        <small class="text-secondary">Setelah memilih barang, pilihlah pelanggannya</small>
                        <select name="id_pelanggan" class="form-select mt-3 mb-3" required>
                            @foreach ($pelanggan as $itemPelanggan)
                                @if ($itemPelanggan->id_pelanggan == old('id_pelanggan', $penjualan->id_pelanggan))
                                    <option value="{{ $itemPelanggan->id_pelanggan }}" selected>
                                        {{ $itemPelanggan->nama_pelanggan }}</option>
                                @else
                                    <option value="{{ $itemPelanggan->id_pelanggan }}">
                                        {{ $itemPelanggan->nama_pelanggan }}</option>
                                @endif
                            @endforeach
                        </select>

                        <label for="tgl_jual" class="form-label">Tanggal Jual</label>
                        <input id="tgl_jual" name="tgl_jual" class="form-control" type="date"
                            value="{{ old('tgl_jual', $penjualan->tgl_jual) }}" />
                        <button type="submit" class="btn btn-primary w-100 mt-3 mb-2">Simpan Penjualan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="modalDeleteDetail" tabindex="-1" aria-labelledby="modalDeleteDetail" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDeleteDetailLabel">Hapus Barang Penjualan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Apakah yakin ingin menghapus barang dari penjualan ini?</p>
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

    <div class="modal fade" id="modalCreateDetail" tabindex="-1" aria-labelledby="modalCreateDetail" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('penjualan.detail.store', $penjualan->id_penjualan) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateDetailLabel">Tambah Barang Terjual</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row gx-2">
                            <div class="col-9">
                                <select name="id_barang" class="form-select" required>
                                    <option selected>Pilih Barang</option>
                                    @foreach ($barang as $itemBarang)
                                        <option value="{{ $itemBarang->id_barang }}">
                                            {{ $itemBarang->nama_barang }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="qty" class="form-control" min="1" value="1" required/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.delete-barang', function() {
            let idPenjualan = $(this).attr('data-id-penjualan');
            let idBarang = $(this).attr('data-id-barang');
            $('#modal-form-del').attr('action', "/penjualan/" + idPenjualan + "/" + idBarang);
        });
    </script>
@endsection
