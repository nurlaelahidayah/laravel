<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangMasukSementara;
use App\Models\Kategori;
use App\Models\Pemasok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barang_masuk = BarangMasuk::all();
        return view('barang.barang masuk.dftBarangMasuk', compact('barang_masuk'));
    }


    public function create()
    {
        $supplier = Pemasok::orderBy('nama', 'asc')->get();

        $thn = Carbon::now()->year;
        $var = 'BM';
        $bms = BarangMasuk::count();
        if ($bms == 0) {
            $awal = 10001;
            $kode_bm = $var.$thn.$awal;
            // BM2021001
        } else {
           $last = BarangMasuk::all()->last();
           $awal = (int)substr($last->kode_bm, -5) + 1;
           $kode_bm = $var.$thn.$awal;
        }

        return view('barang.barang masuk.tbhBarangMasuk', compact('supplier', 'kode_bm'));

    }

    public function get_barang($id)
    {
        $supplier = Pemasok::orderBy('nama', 'asc')->get();
        $produk = Barang::where('pemasok_id', $id)->get();
        // dd($produk);

        $thn = Carbon::now()->year;
        $var = 'BM';
        $bms = BarangMasuk::count();
        if ($bms == 0) {
            $awal = 10001;
            $kode_bm = $var.$thn.$awal;
            // BM2021001
        } else {
           $last = BarangMasuk::all()->last();
           $awal = (int)substr($last->kode_bm, -5) + 1;
           $kode_bm = $var.$thn.$awal;
        }

        return view('barang.barang masuk.tbhBarangMasuk', compact('supplier', 'kode_bm', 'produk', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate(['tgl_masuk' => 'required']);

        $kode_bm = $request->kode_bm;
        $id_barang = $request->id_barang;
        $supplier = $request->supplier;
        $kategori_id = $request->kategori_id;
        $nama_barang = $request->nama;
        $harga_ambil = $request->harga_ambil;
        $jumlah = $request->jumlah;
        $tgl = $request->tgl_masuk;
        $satuan = $request->satuan;
        // dd($request->all());

        foreach ($jumlah as $key => $value) {
            if ($value == 0) {
                continue;
            }
            // dd($value);
            $dt_produk = Barang::where('id_barang', $id_barang[$key])->first();
            Barang::where('id_barang', $id_barang[$key])->update([
                'jumlah' => $dt_produk->jumlah + $jumlah[$key]
            ]);
            BarangMasuk::insert([
                'kode_bm' => $kode_bm,
                'kategori_id' => $kategori_id[$key],
                'pemasok_id' => $supplier,
                'nama' => $nama_barang[$key],
                'jumlah' => $jumlah[$key],
                'satuan' => $satuan[$key],
                'harga' => $harga_ambil[$key],
                'tot_pengeluaran' => $harga_ambil[$key] * $jumlah[$key],
                'tanggal' => $tgl,
            ]);

        }

        alert()->success('Berhasil','Data Barang Berhasil Ditambahkan.');
        return redirect('/tbhBarang_masuk');

    }

 

     public function edit($id)
     {
         // Mengambil data barang masuk berdasarkan ID
        //$barang_masuk = BarangMasuk::findOrFail($id); // Mengambil data barang masuk atau 404 jika tidak ditemukan
         //$barang_masuk = BarangMasuk::where('id_barang_masuk', $id)->get();
         // Mengambil data pemasok
         //$supplier = Pemasok::orderBy('nama', 'asc')->get();
  
         // Mengambil data produk berdasarkan pemasok_id
         //$produk = Barang::where('pemasok_id', $barang_masuk->pemasok_id)->get(); // Mengambil produk berdasarkan pemasok yang terkait dengan barang masuk
         
         //$barang_masuk = BarangMasuk::where('id_barang_masuk', $id)->get();
         //$barang_masuk = BarangMasuk::findOrFail($id); // Menggunakan findOrFail untuk mendapatkan objek tunggal
         //$kategori = Kategori::all(); // Ambil semua kategori
         //$pemasok = Pemasok::all(); // Ambil semua pemasok
         //$barang = Barang::all(); // Ambil barang berdasarkan pemasok yang terkait
        
        $barang_masuk = BarangMasuk::where('id_barang_masuk', $id)->get();
        $kategori = Kategori::where('id_kategori', '!=', $barang_masuk[0]->kategori_id)->get();
        $pemasok = Pemasok::where('id_pemasok', '!=', $barang_masuk[0]->pemasok_id)->get();
        $barang = Barang::all();
         
 
         // Mengembalikan view dengan data yang diperlukan
         return view('barang.barang masuk.edtBarangMasuk', compact('pemasok', 'barang', 'kategori', 'barang_masuk'));
     }


     //public function get_edtbarang($id)
    //{
    // Mengambil data pemasok
      //  $supplier = Pemasok::orderBy('nama', 'asc')->get();

    // Mengambil data produk berdasarkan pemasok_id
        //$produk = Barang::where('pemasok_id', $id)->get();

    // Mengambil data barang masuk berdasarkan id
        //$barang_masuk = BarangMasuk::findOrFail($id); // Pastikan ini sesuai dengan ID yang Anda inginkan
        //$barang_masuk = BarangMasuk::where('id_barang_masuk', $id)->get();
        //$barang_masuk = BarangMasuk::where('id_barang_masuk', $id)->first(); // Ini mengembalikan objek tunggal atau null
    // Mengembalikan view dengan data yang diperlukan
     //   return view('barang.barang masuk.edtBarangMasuk', compact('supplier', 'produk', 'barang_masuk', 'id'));
    //}




     public function update(Request $request, $id)
     {
         $rules = [
             
            'kode_bm' => 'required',
            'kategori_id' => 'required',
            'pemasok_id' => 'required',
            'nama' => 'required|string',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required',
            'harga' => 'required',
            //'tot_pengeluaran' => 'required',
            
         ];
 
         $validate = $request->validate($rules);

                // Ambil data barang masuk yang ada
            $barang_masuk = BarangMasuk::findOrFail($id);
            $barang = Barang::where('nama', $barang_masuk->nama)->firstOrFail();
            
            //$barang = Barang::findOrFail($request->barang_id);

            // Hitung selisih jumlah
            $selisih = $request->jumlah - $barang_masuk->jumlah;
            // Cek apakah selisih tidak membuat jumlah menjadi negatif
            if ($barang->jumlah - $selisih < 0) {
            return redirect()->back()->withErrors(['jumlah' => 'Jumlah tidak boleh negatif.']);
            }


        // Update jumlah barang di tabel barang
        $barang->update([
        'jumlah' => $barang->jumlah + $selisih
        ]);

        // Hitung total pengeluaran baru
        $tot_pengeluaran = $request->harga * $request->jumlah;

        // Debug log
    \Log::info('Total Pengeluaran: ' . $tot_pengeluaran);
         
         //DB::table('barang_masuk')->where('id_barang_masuk', $id)->update($validate);
        
         // Update data barang masuk
        $barang_masuk->update([
        'kode_bm' => $request->kode_bm,
        'kategori_id' => $request->kategori_id,
        'pemasok_id' => $request->pemasok_id,
        'nama' => $request->nama,
        'jumlah' => $request->jumlah,
        'satuan' => $request->satuan,
        'harga' => $request->harga,
        'tot_pengeluaran' => $tot_pengeluaran,
       
        ]);


         alert()->success('Berhasil','Data Barang Masuk Berhasil Diupdate.');
         return redirect('/barang_masuk');
     }


 
     public function destroy($id)
    {
        // Ambil data barang keluar yang akan dihapus
        $barang_masuk = BarangMasuk::findOrFail($id);

        // Ambil data barang terkait
        //$kategori = Kategori::findOrFail($barang_masuk->kategori_id);
        //$supplier = Pemasok::findOrFail($barang_masuk->pemasok_id);
        // Ambil data barang terkait berdasarkan nama barang
        $barang = Barang::where('nama', $barang_masuk->nama)->firstOrFail();
        // Kembalikan jumlah barang ke stok
        $barang->update([
        'jumlah' => $barang->jumlah - $barang_masuk->jumlah // Mengurangi jumlah barang masuk dari stok
        ]);

         // Hapus data barang masuk
        $barang_masuk->delete();

        // Kembalikan jumlah barang ke stok
        //$barang->update([
          //  'jumlah' => $barang->jumlah + $barang_keluar->jumlah // Menambahkan jumlah barang keluar ke stok
        //]);

        // Hapus data barang keluar
        $barang_masuk->delete();

        // Redirect dengan pesan sukses
        alert()->success('Berhasil', 'Data Barang Masuk Berhasil Dihapus.');
        return redirect('/barang_masuk');
    }
}
