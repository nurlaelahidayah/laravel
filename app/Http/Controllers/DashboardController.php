<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
#use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function cetakStok()
                {
                     $barang = Barang::with(['kategori', 'pemasok', 'stok'])->get(); // Ambil data barang
                     
                     // Menggunakan view untuk mencetak
                  return view('barang.cetakStok', compact('barang'));
                }
           
    public function index()
    {
        $barang = Barang::count('id_barang');
        $barang_masuk = BarangMasuk::count('id_barang_masuk');
        $barang_keluar = BarangKeluar::count('id_barang_keluar');
        return view('dashboard', compact('barang', 'barang_masuk', 'barang_keluar'));
    }

    public function laporan()
    {
        return view('laporan.laporan');
    }

 

    public function cetak_laporan(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'jenis_laporan' => 'required'
        ]);

        $dari = $request->tgl_awal;
        $sampai = $request->tgl_akhir;
        $jenis = $request->jenis_laporan;
        $cek = Carbon::today();
        $hari_ini = $cek->toDateString();

        if ($dari > $sampai) {
            alert()->error('Data Gagal Dicetak','Tanggal Akhir Melebihi Tanggal Awal.');
            return back();
        }

        if ($dari > $hari_ini) {
            alert()->error('Data Gagal Dicetak.','Tanggal Awal Melebihi Hari Ini.');
            return back();
        }

        if ( $sampai > $hari_ini) {
            alert()->error('Data Gagal Dicetak.','Tanggal Akhir Melebihi Hari Ini.');
            return back();
        }

        if ($jenis == 'masuk') {
            $data_masuk = BarangMasuk::where('tanggal', '>=', $dari)
                        ->where('tanggal', '<=', $sampai)->get();
            $pdf = PDF::loadView('laporan.laporanBm', compact('data_masuk', 'dari', 'sampai'))->setPaper('A4', 'landscape');
            return $pdf->stream('Laporan Barang Masuk.pdf');
        } else {
            $data_keluar = BarangKeluar::where('tanggal', '>=', $dari)
                        ->where('tanggal', '<=', $sampai)
                        
                        ->with('barang') // Mengambil relasi barang
                        ->get();

        // Mengambil harga dari tabel barang
        foreach ($data_keluar as $item) {
            $item->harga = $item->barang->harga_ambil; // Asumsi 'harga_ambil' adalah kolom harga di tabel barang
        }
            $pdf = PDF::loadView('laporan.laporanBk', compact('data_keluar', 'dari', 'sampai'))->setPaper('A4', 'landscape');
            return $pdf->stream('Laporan Barang Keluar.pdf');
        }
    }


    public function cetak_laporan_uk(Request $request)
    {
        $request->validate([
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'email' => 'required'
        ]);

        $dari = $request->tgl_awal;
        $sampai = $request->tgl_akhir;
        $email = $request->email; // Mengambil email(unit_kerja) dari request
        $cek = Carbon::today();
        $hari_ini = $cek->toDateString();

        if ($dari > $sampai) {
            alert()->error('Data Gagal Dicetak','Tanggal Akhir Melebihi Tanggal Awal.');
            return back();
        }

        if ($dari > $hari_ini) {
            alert()->error('Data Gagal Dicetak.','Tanggal Awal Melebihi Hari Ini.');
            return back();
        }

        if ($sampai > $hari_ini) {
            alert()->error('Data Gagal Dicetak.','Tanggal Akhir Melebihi Hari Ini.');
            return back();
        }

         // Mengambil data berdasarkan email pegawai
            $data_keluar = BarangKeluar::select('barang_id', \DB::raw('SUM(jumlah) as total_jumlah'), 'pegawai_id')
                ->where('tanggal', '>=', $dari)
                ->where('tanggal', '<=', $sampai)
                ->whereHas('pegawai', function($query) use ($email) {
                $query->where('email', $email); // Memfilter berdasarkan unit kerja pegawai
                 })
                ->groupBy('barang_id', 'pegawai_id') // Mengelompokkan berdasarkan barang_id dan pegawai_id
                ->get();

        // Memeriksa apakah data ditemukan
            if ($data_keluar->isEmpty()) {
                alert()->error('Data Tidak Ditemukan', 'Tidak ada data barang keluar untuk kriteria yang dipilih.');
                    return back();
                }

        // Menambahkan nama_barang dan email ke setiap item data_keluar
                foreach ($data_keluar as $item) {
                        $barang = $item->barang; // Mengambil objek barang terkait
                        $item->nama_barang = $item->barang ? $item->barang->nama : 'Tidak Diketahui'; // Mengambil nama barang dari relasi
                        $item->satuan = $barang ? $barang->satuan : 'Tidak Diketahui'; // Mengambil satuan dari relasi
                        $item->email = $email; // Menyimpan unit kerja yang digunakan untuk filter
                        
                }
        // Memuat view PDF
                 $pdf = PDF::loadView('laporan.laporanBkuk', compact('data_keluar', 'dari', 'sampai', 'email'))->setPaper('A4', 'landscape');
                     return $pdf->stream('Laporan Barang Keluar.pdf');
        

        
    }
}


