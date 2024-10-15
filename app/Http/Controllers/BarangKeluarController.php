<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Stok;
use App\Models\BarangKeluar;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        $barang_keluar = BarangKeluar::all();
        return view('barang.barang keluar.dftBarangKeluar', compact('barang_keluar'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {   

        //$searchBarang = $request->input('search_barang');
        //$searchPegawai = $request->input('search_pegawai');
        //$barang = Barang::all();
        $barang = Barang::orderBy('nama', 'asc')
        //->when($searchBarang, function ($query, $searchBarang) {
          //  return $query->where('nama', 'like', '%' . $searchBarang . '%');
        //})
        ->get();
        //$pegawai = Pegawai::all();
        $pegawai = Pegawai::orderBy('nama_pegawai', 'asc')
        //->when($searchPegawai, function ($query, $searchPegawai) {
          //  return $query->where('nama_pegawai', 'like', '%' . $searchPegawai . '%');
        //})
        ->get();

        $thn = Carbon::now()->year;
        $var = 'BK';
        $bms = BarangKeluar::count();
        if ($bms == 0) {
            $awal = 10001;
            $kode_bk = $var.$thn.$awal;
            // BK2024001
        } else {
           $last = BarangKeluar::all()->last();
           $awal = (int)substr($last->kode_bk, -5) + 1;
           $kode_bk = $var.$thn.$awal;
        }
        return view('barang.barang keluar.tbhBarangKeluar', compact('kode_bk', 'barang', 'pegawai'));
    }

    public function get_barang($id)
    {
        $data_bk = Barang::where('id_barang', $id)->with('stok')->first();
      

        return response()->json([
            'data_bk' => $data_bk,
            'jumlah_stok' => $data_bk ? $data_bk->stok->jumlah : 0, // Mengambil jumlah dari stok, jika ada
        
        ]);

        // return view('barang.barang keluar.tbhBarangKeluar', compact('kode_bk', 'barang', 'pegawai', 'id', 'jml_barang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_pegawai' => 'required|not_in:Pilih Pegawai...',
        'tgl_keluar' => 'required',
        'id_barang' => 'required|array', 
        'jml' => 'required|array', 
        'satuan' => 'required|array',
    ]);

    // Ambil data dari request
    $kode_bk = $request->kode_bk;
    $nama_pegawai = $request->nama_pegawai;
    $tgl = $request->tgl_keluar;
    $id_barang = $request->id_barang;
    $jumlah = $request->jml;
    $satuan = $request->satuan;

    
    foreach ($id_barang as $key => $value) {
        if ($jumlah[$key] > 0) { 
            
             $dt_barang = Stok::find($value);

            // Cek apakah jumlah yang diminta melebihi stok
             if ($jumlah[$key] > $dt_barang->jumlah) {
                 alert()->error('Gagal', 'Jumlah Barang Melebihi Stok Barang.');
                 return back();
             }

            // Simpan record barang keluar
            BarangKeluar::create([
                'kode_bk' => $kode_bk,
                'pegawai_id' => $nama_pegawai,
                'barang_id' => $value,
                'jumlah' => $jumlah[$key],
                'satuan' => $satuan[$key],
                'tanggal' => $tgl,
            ]);
            
            // Update jumlah barang di tabel barang
            // $dt_barang->update([
            //     'jumlah' => $dt_barang->jumlah - $jumlah[$key]
            // ]);
        }
    }

    alert()->success('Berhasil', 'Kegiatan Berhasil Ditambahkan.');
    return back();
}
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
     
    
     
     public function edit($id)
     {
       $barang_keluar = BarangKeluar::where('id_barang_keluar', $id)->get();
       $barang = Barang::where('id_barang', '!=', $barang_keluar[0]->barang_id)->get();
       $pegawai = Pegawai::where('id_pegawai', '!=', $barang_keluar[0]->pegawai_id)->get();
         
       return view('barang.barang keluar.edtBarangKeluar', compact('barang', 'pegawai', 'barang_keluar'));
     }
 
     public function update(Request $request, $id)
     {
         $rules = [
             //id_barang_keluar' => 'required',
             'kode_bk' => 'required',
             'barang_id' => 'required',
             'pegawai_id' => 'required',
             'jumlah' => 'required|integer|min:0',
             'satuan' => 'required',
             //'tanggal' => 'required',
             
         ];
 
         $validate = $request->validate($rules);

                // Ambil data barang keluar yang ada
            $barang_keluar = BarangKeluar::findOrFail($id);
            $barang = Barang::findOrFail($request->barang_id);

            // Hitung selisih jumlah
         //   $selisih = $request->jumlah - $barang_keluar->jumlah;

            // Update jumlah barang di tabel barang
          //  $barang->update([
            //    'jumlah' => $barang->jumlah - $selisih
            //]);


            // Ambil data barang keluar yang ada
            $barang_keluar = BarangKeluar::findOrFail($id);
            $barang = Barang::findOrFail($request->barang_id);

            // Ambil data stok untuk barang yang dipilih
            $stok = Stok::where('id_barang', $request->barang_id)->first();

            // Cek apakah stok ada dan cukup
            if ($stok && $request->jumlah > $stok->jumlah)
             {
                // Jika jumlah yang ingin diupdate melebihi stok
                alert()->error('Gagal', 'Stok tidak mencukupi!');
                return back()->withInput();
            }   
 
         
         DB::table('barang_keluar')->where('id_barang_keluar', $id)->update($validate);
 
         alert()->success('Berhasil','Data Barang Keluar Berhasil Diupdate.');
         return redirect('/barang_keluar');
     }
 
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

        public function destroy($id)
    {
        // Ambil data barang keluar yang akan dihapus
        $barang_keluar = BarangKeluar::findOrFail($id);

        // Ambil data barang terkait
        $barang = Barang::findOrFail($barang_keluar->barang_id);

        // Kembalikan jumlah barang ke stok
       // $barang->update([
         //   'jumlah' => $barang->jumlah + $barang_keluar->jumlah // Menambahkan jumlah barang keluar ke stok
        //]);

        // Hapus data barang keluar
        $barang_keluar->delete();

        // Redirect dengan pesan sukses
        alert()->success('Berhasil', 'Data Barang Keluar Berhasil Dihapus.');
        return redirect('/barang_keluar');
    }
   
}
