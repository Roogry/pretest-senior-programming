<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * Menampilkan seluruh penjualan.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Mengambil data penjualan
        $penjualan = Penjualan::with('pelanggan', 'details')->orderBy('id_penjualan', 'DESC')->get();

        // menghitung total pendapatan dari tiap penjualan
        foreach ($penjualan as $itemPenjualan) {
            $totalPendapatan = 0;
            foreach ($itemPenjualan->details as $detailPenjualan) {
                $totalPendapatan += ($detailPenjualan->jumlah * $detailPenjualan->harga_jual);
            }
            $itemPenjualan['total_penjualan'] = $totalPendapatan;
        }

        // jalankan proses search apabila ada permintaannya
        if ($request->search) {
            $unsortedPenjualan = $penjualan;
            $searchQuery = (int) preg_replace('/[^0-9]/', '', $request->search); // mengambil hanya number

            // mengurutkan penjualan berdasarkan total penjualan
            $sortedPenjualan = insertionSort($unsortedPenjualan, 'total_penjualan');

            // mencari seluruh sorted penjualan yang memiliki total penjualan yang diminta
            $listFindedPenjualan = collect();
            $tempResultBinary = null;
            do {
                $tempResultBinary = (int) binarySearch($sortedPenjualan->pluck('total_penjualan'), $searchQuery);

                if ($tempResultBinary) {
                    $listFindedPenjualan[] = $sortedPenjualan[$tempResultBinary];
                    $sortedPenjualan->splice($tempResultBinary, 1);
                }
            } while ($tempResultBinary);

            $penjualan = $listFindedPenjualan;
        }

        return view('sales.index', ['title' => 'Penjualan', 'penjualan' => $penjualan]);
    }

    /**
     * Menampilkan form untuk menambah penjualan.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pelanggan = Pelanggan::all();
        $barang = Barang::orderBy('stok', 'desc')->get();

        return view('sales.create', [
            'title' => 'Tambah Penjualan',
            'pelanggan' => $pelanggan,
            'barang' => $barang,
        ]);
    }

    /**
     * Menambahkan penjualan baru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errMessage = [];
        // Hanya mengambil barang yang lebih dari 0
        $reqBarang = collect($request->barang);
        $filteredBarang = $reqBarang->filter(function ($value) {
            return $value['qty'] > 0;
        });

        // validasi adanya barang yang dibeli
        if (count($filteredBarang) < 1) {
            $errMessage[] = 'Tambahkan jumlah salah satu barang terlebih dahulu';
        }

        // validasi adanya pelanggan yang dipilih
        if (!$request->id_pelanggan) {
            $errMessage[] = 'Pelanggan belum dipilih';
        }

        // apabila ada error maka tampilkan pesan error
        if (count($errMessage) > 0) {
            return redirect()->back()->with('error', $errMessage)->withInput($request->all());
        }

        // memulai proses store penjualan
        DB::beginTransaction();
        // mendapatkan nomor penjualan sebelumnya
        $lastPenjualan = Penjualan::orderBy('id_penjualan', 'DESC')->limit(1)->get();
        $lastPenjualanNumber = preg_replace('/[^0-9]/', '', $lastPenjualan[0]->id_penjualan); // mengambil hanya number dari string id

        // sprintf akan menambahkan 0 didepan hingga memenuhi 2 digit
        $newIdPenjualan = 'PJ' . sprintf("%02d", $lastPenjualanNumber + 1);

        $penjualan = Penjualan::create([
            'id_penjualan' => $newIdPenjualan,
            'id_pelanggan' => $request->id_pelanggan,
            'tgl_jual' => $request->tgl_jual,
        ]);

        foreach ($filteredBarang as $itemBarang) {
            PenjualanDetail::create([
                'id_penjualan' => $penjualan['id_penjualan'],
                'id_barang' => $itemBarang['id_barang'],
                'jumlah' => $itemBarang['qty'],
                'harga_jual' => $itemBarang['harga_jual']
            ]);

            // mengurangi stok barang
            $barang = Barang::find($itemBarang['id_barang']);
            $barang->update([
                'stok' => $barang->stok - $itemBarang['qty']
            ]);
        }
        DB::commit();

        return redirect()->route('penjualan.index')->with('success', 'Penjualan baru berhasil ditambahkan');
    }

    /**
     * Menampilkan form untuk edit penjualan.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penjualan $penjualan)
    {
        $penjualan->load('details');
        $pelanggan = Pelanggan::all();
        $barang = Barang::orderBy('stok', 'desc')->get();

        return view('sales.edit', ['title' => 'Tambah Penjualan', 'penjualan' => $penjualan, 'pelanggan' => $pelanggan, 'barang' => $barang]);
    }

    /**
     * Mengubah penjualan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        $errMessage = [];
        // Hanya mengambil barang yang lebih dari 0
        $reqBarang = collect($request->barang);
        $filteredBarang = $reqBarang->filter(function ($value) {
            return $value['qty'] > 0;
        });

        // validasi adanya barang yang dibeli
        if (count($filteredBarang) < 1) {
            $errMessage[] = 'Tambahkan jumlah salah satu barang terlebih dahulu';
        }

        if (!$request->id_pelanggan) {
            $errMessage[] = 'Pelanggan belum dipilih';
        }

        // apabila ada error maka tampilkan pesan error
        if (count($errMessage) > 0) {
            return redirect()->back()->with('error', $errMessage)->withInput($request->all());
        }

        // memulai proses update penjualan
        DB::beginTransaction();
        $penjualan->update([
            'id_pelanggan' => $request->id_pelanggan,
            'tgl_jual' => $request->tgl_jual,
        ]);

        // menambahkan ulang data barang yang dibeli
        // serta kalkulasi ulang stok barang
        $penjualanDetail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->get();
        foreach ($penjualanDetail as $detail) {
            $barang = Barang::find($detail['id_barang']);
            $barang->update([
                'stok' => $barang->stok + $detail['jumlah']
            ]);
        }
        PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->delete();

        foreach ($filteredBarang as $itemBarang) {
            PenjualanDetail::create([
                'id_penjualan' => $penjualan['id_penjualan'],
                'id_barang' => $itemBarang['id_barang'],
                'jumlah' => $itemBarang['qty'],
                'harga_jual' => $itemBarang['harga_jual']
            ]);

            $barang = Barang::find($itemBarang['id_barang']);
            $barang->update([
                'stok' => $barang->stok - $itemBarang['qty']
            ]);
        }
        DB::commit();

        return redirect()->route('penjualan.index')->with('success', 'Perubahan penjualan berhasil disimpan');
    }

    /**
     * Menghapus penjualan.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penjualan $penjualan)
    {
        // menghapus penjualan beserta seluruh detailnya
        $penjualan->delete();
        PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)->delete();
        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil dihapus');
    }

    /**
     * Menghapus barang dari penjualan.
     *
     * @param  string  $idPenjualan
     * @param  string  $idBarang
     * @return \Illuminate\Http\Response
     */
    public function destroyDetailPenjualan($idPenjualan, $idBarang)
    {
        PenjualanDetail::where('id_penjualan', $idPenjualan)
            ->where('id_barang', $idBarang)
            ->delete();

        return redirect()->back()->with('success', 'Barang penjualan berhasil dihapus');
    }

    /**
     * Menghapus barang dari penjualan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function storeDetailPenjualan(Request $request, Penjualan $penjualan)
    {
        $barang = Barang::find($request->id_barang);

        // mengecek barang yang dipilih
        if (!$barang) {
            return redirect()->back()->with('error', 'Gagal menambahkan. Pilih salah satu barang');
        }

        // mengecek apakah stok barang cukup
        if ($barang->stok < $request->qty) {
            return redirect()->back()->with('error', 'Gagal menambahkan. Jumlah stok tidak mencukupi');
        }

        // mengecek barang yang dipilih
        if ($request->qty < 1) {
            return redirect()->back()->with('error', 'Gagal menambahkan. Jumlah barang harus lebih dari 0');
        }

        // memulai proses store detail penjualan
        DB::beginTransaction();

        // mengecek adakah penjualan detail dengan id barang
        // dan harga jual yang sudah ada di penjualan tersebut
        $penjualanDetail = PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)
            ->where('id_barang', $barang->id_barang)
            ->where('harga_jual', $barang->harga)
            ->first();

        if ($penjualanDetail) {
            // jika ada maka update jumlahnya
            PenjualanDetail::where('id_penjualan', $penjualan->id_penjualan)
                ->where('id_barang', $barang->id_barang)
                ->update([
                    'jumlah' => $penjualanDetail->jumlah + $request->qty,
                ]);
        } else {
            // jika tidak maka buat baru
            PenjualanDetail::updateOrCreate([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_barang' => $barang->id_barang,
                'jumlah' => $request->qty,
                'harga_jual' => $barang->harga
            ]);
        }

        // mengurangi stok barang
        $barang->update([
            'stok' => $barang->stok - $request->qty
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Barang penjualan berhasil ditambahkan');
    }
}
