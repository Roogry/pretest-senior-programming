@extends('layouts')
@section('title', 'Semua Penjualan')
@section('content')
<div class="row">
    <h2 class="col-md-8 col-12">Tambah Penjualan</h2>

    @if ($messages = Session::get('error'))
    <div class="col-md-8 col-12 mt-2">
        <div class="alert alert-danger mb-0">
            <ul class="mb-0 ps-3">
            @foreach ($messages as $message)
            <li>{{ $message }}</li>
            @endforeach
            </ul>
        </div>
    </div>
    @endif
</div>
<form action="{{ route('penjualan.store') }}" method="POST">
    @csrf
    <div class="row my-4">
        <div class="col-md-8 col-12">
            <div class="card rounded-4 border-light shadow-sm">
                <div class="card-body px-4">
                    <div class="row mt-3 align-items-center">
                        <div class="col-3">
                            <h5 class="mb-0 mx-2">Pilih Barang</h5>
                        </div>
                        {{-- <div class="col-md-6 col-12 ms-auto">
                            <div class="d-flex mx-2">
                                <div class="flex-fill me-3">
                                    <input type="text" name="search" class="form-control py-2" placeholder="Cari Barang">
                                </div>
                                <button class="btn btn-primary rounded-3 py-2">
                                    Cari
                                </button>
                            </div>
                        </div> --}}
                    </div>

                    <table class="table mt-3 align-middle text-black-50 border-light">
                        <thead class="text-body">
                            <tr>
                                {{-- <th scope="col" class="ps-4 border-light">No</th> --}}
                                <th scope="col" class="border-light">Nama Barang</th>
                                <th scope="col" class="border-light">Jenis</th>
                                <th scope="col" class="border-light">Harga</th>
                                <th scope="col" class="border-light" width="50px">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $itemBarang)
                                <tr>
                                    {{-- <th scope="row" class="ps-4">{{ $loop->iteration }}</th> --}}
                                    <td class="fw-bolder">
                                        <p class="m-0">{{ $itemBarang->nama_barang }}</p>
                                        @if ($itemBarang->stok < 1)
                                        <small class="text-danger fw-normal"><i class="bi bi-exclamation-triangle"></i> Stok habis</small>
                                        @else
                                        <small class="text-secondary fw-normal">Sisa : {{ $itemBarang->stok }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $itemBarang->jenis->nama_jenis }}</td>
                                    <td>{{ currency($itemBarang->harga) }}</td>
                                    <td>
                                        <input type="hidden" name="barang[{{ $loop->index }}][id_barang]" value="{{ $itemBarang->id_barang }}">
                                        <input type="hidden" name="barang[{{ $loop->index }}][harga_jual]" value="{{ $itemBarang->harga }}">
                                        <input type="number" name="barang[{{ $loop->index }}][qty]" class="form-control" min="0" max="{{ $itemBarang->stok }}" value="0"/>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- <div class="d-flex justify-content-end">
                        {!! $barang->links() !!}
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card rounded-4 border-light shadow-sm">
                <div class="card-body px-4">
                    <h5 class="mb-2">Pilih Pelanggan</h5>
                    <small class="text-secondary">Setelah memilih barang, pilihlah pelanggannya</small>
                    <select name="id_pelanggan" class="form-select mt-3 mb-3" required>
                        <option selected class="text-muted" disabled>Pilih Pelanggan</option>
                        @foreach ($pelanggan as $itemPelanggan)
                        @if ($itemPelanggan->id_pelanggan == old('id_pelanggan'))
                        <option value="{{ $itemPelanggan->id_pelanggan }}" selected>{{ $itemPelanggan->nama_pelanggan }}</option>
                        @else
                        <option value="{{ $itemPelanggan->id_pelanggan }}">{{ $itemPelanggan->nama_pelanggan }}</option>
                        @endif
                        @endforeach
                    </select>


                    <label for="tgl_jual" class="form-label">Tanggal Jual</label>
                    <input id="tgl_jual" name="tgl_jual" class="form-control" type="date" value="{{ old('tgl_jual', date("Y-m-d")) }}" />
                    <button type="submit" class="btn btn-primary w-100 mt-3 mb-2">Tambah Penjualan</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
