<?php

namespace App\Http\Controllers;

use App\Models\Barang;
//use App\Models\Stok;
use App\Models\Kategori;
use App\Models\Pemasok;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
     public function index()
     {
         $barang = Barang::all();
     
          //$barang = DB::table('barang')
        //->select('barang.*', 'stok.stok')
        //->join('stok', 'stok.id_barang', '=', 'barang.id_barang')
        //->get();
        return view('barang.dftBarang', compact('barang'));
     }

    
    public function search(Request $request)
    {
        $query = $request->get('query');
        $barang = Barang::where('nama', 'LIKE', "%{$query}%")->get();
     
        return response()->json($barang);
    }

    public function create()
    {
        $kategori = Kategori::all();
        $supplier = Pemasok::all();

        $thn = Carbon::now()->year;
        $var = 'BF';
        $bms = Barang::count();
        if ($bms == 0) {
            $awal = 10001;
            $kode_b = $var.$thn.$awal;
            // BM2021001
        } else {
           $last = Barang::all()->last();
           $awal = (int)substr($last->kode_barang, -5) + 1;
           $kode_b = $var.$thn.$awal;
        }

        return view('barang.tbhBarang', compact('kategori', 'supplier', 'kode_b'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required',
            'kategori_id' => 'required',
            'pemasok_id' => 'required',
            'nama' =>'required|unique:barang',
            'satuan' => 'required',
            'harga_ambil' => 'required',
            'gambar' => 'required|mimes:jpg,jpeg,png'
        ]);
        

         $gm = $request->gambar;
         $namaFile = $gm->getClientOriginalName();

         $barang = new Barang;
         $barang->kode_barang = $request->kode_barang;
         $barang->kategori_id = $request->kategori_id;
         $barang->pemasok_id = $request->pemasok_id;
         $barang->nama = $request->nama;
         //$barang->jumlah = 0;
         $barang->satuan = $request->satuan;
         $barang->harga_ambil = $request->harga_ambil;
         $barang->gambar = $namaFile;
         $gm->move(public_path() . '/Image', $namaFile);
         $barang->save();

         alert()->success('Berhasil','Barang Baru Berhasil Ditambahkan.');
         return back();
    }

    public function edit($id)
    {
        $barang = Barang::where('id_barang', $id)->get();
        $kategori = Kategori::where('id_kategori','!=', $barang[0]->kategori_id)->get();
        $supplier = Pemasok::where('id_pemasok', '!=', $barang[0]->pemasok_id)->get();
        
        return view('barang.edtBarang', compact('barang', 'kategori', 'supplier'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'kode_barang' => 'required',
            'kategori_id' => 'required',
            'pemasok_id' => 'required',
            'nama' =>'required',
            'satuan' => 'required',
            'harga_ambil' => 'required',
            'gambar' => 'mimes:jpg,jpeg,png' #batasan format
        ];

        $validate = $request->validate($rules);

        if ($request->file('gambar')) {
            $gm = $request->gambar;
            $namaFile = $gm->getClientOriginalName();

            $validate['gambar'] = $namaFile;
            $gm->move(public_path() . '/Image', $namaFile);
        }

        DB::table('barang')->where('id_barang', $id)->update($validate);

        alert()->success('Berhasil','Data Barang Berhasil Diupdate.');
        return redirect('/barang');
    }

    //public function destroy($id)
   // {
       // Barang::where('id_barang', $id)->delete();
        //alert()->success('Berhasil','Barang Berhasil Dihapus.');
        //return back();
    //}
    
   public function destroy($id_barang)
    {
    // Mencari dan menghapus barang keluar berdasarkan ID
        $barang = Barang::findOrFail($id_barang);
    
    // Menghapus barang keluar
        $barang->delete();

    //Redirect dengan pesan sukses
        alert()->success('Berhasil','Data Barang Berhasil Dihapus.');
        return redirect('/barang');
        
        //return redirect()->route('barang.dftBarang')->with('success', 'Barang berhasil dihapus.');
    }
    
   // public function destroy($id)
    //{
      //  Barang::where('id_barang', $id)->delete();
        //alert()->success('Berhasil','Data Barang Keluar Berhasil Dihapus.');
        //return back();
    //}
}
