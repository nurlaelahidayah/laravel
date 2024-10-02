@extends('layout.main')

@section('pegawai', 'active')

@section('content')

<div class="row">
    <div class="col-lg">
        <div class="card">
            <div class="card-header"><b>Tambah Pegawai</b></div>
            <div class="card-body card-block">
                <form method="POST" action="{{ route('tbhPegawai') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="kode_pegawai" class="form-label">Kode Pegawai</label>
                        <input type="text" class="form-control" id="kode_pegawai" name="kode_pegawai"
                            value="{{ $kode_pegawai }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="nama_pegawai" class="form-label">Nama Pegawai</label>
                        <input type="text" class="form-control" id="nama_pegawai" name="nama_pegawai">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Unit Kerja</label>
                        <select name="email" id="email" class="form-control">
                            <option value="">Pilih Unit Kerja</option>
                            <option value="Sekretariat">Sekretariat</option>
                            <option value="Yandafduk">Yandafduk</option>
                            <option value="Yancapil">Yancapil</option>
                            <option value="PIAK & PD">PIAK & PD</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                    <a href="/pegawai" class="btn btn-sm btn-danger">Kembali</a>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
