@extends('layout.main')

@section('barang_keluar', 'active')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <strong>Edit Barang Keluar</strong>
            </div>
            <div class="card-body card-block">
                @foreach ($barang_keluar as $item)
                <form action="/edtBarangKeluar/{{ $item->id_barang_keluar }}" method="POST" enctype="multipart/form-data">
                    @csrf    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Kode Barang Keluar</label>
                            <input type="text" class="form-control" id="kode_bk" name="kode_bk"
                                value="{{ $item->kode_bk }}" readonly>
                    </div>
                        <div class="form-group col-md-6">
                            <label for="pegawai_id">Nama Pegawai</label>
                                <select id="pegawai_id" class="form-control" name="pegawai_id" >
                                @foreach ($barang_keluar as $item)
                                    <option value="{{ $item->pegawai_id }}">{{ $item->pegawai->nama_pegawai }}</option>
                                @endforeach
                                @foreach ($pegawai as $item)
                                    <option value="{{ $item->id_pegawai }}">{{ $item->nama_pegawai }}</option>
                                @endforeach
                                </select>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                            <label for="barang_id">Nama Barang</label>
                            <select id="barang_id" name="barang_id" class="form-control" onchange="updateSatuan()">
                            @foreach ($barang_keluar as $item)
                                <option value="{{ $item->barang_id }}" data-satuan="{{ $item->satuan }}">{{ $item->barang->nama }}</option>
                            @endforeach
                            @foreach ($barang as $item)
                                <option value="{{ $item->id_barang }}" data-satuan="{{ $item->satuan }}">{{ $item->nama }}</option>
                            @endforeach
                            </select>
                        </div>
          
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="satuan">Satuan</label>
                            <select id="satuan" name="satuan" class="form-control">
                                @foreach ($barang as $item)
                                <option value="{{ $item->satuan }}">{{ $item->satuan }}</option>
                                @endforeach
                            </select>

                            <!--<input type="text" name="satuan" id="satuan" class="form-control"
                                value="{{ $item->satuan }}"> -->
                        </div>
                            <script>
                                function updateSatuan() {
                                const barangSelect = document.getElementById('barang_id');
                                const satuanSelect = document.getElementById('satuan');
                                const selectedOption = barangSelect.options[barangSelect.selectedIndex];

                                // Mengambil satuan dari data atribut
                                const satuan = selectedOption.getAttribute('data-satuan');

                                // Mengatur nilai satuan sesuai dengan barang yang dipilih
                                satuanSelect.value = satuan;
    }
                            </script>
                        @foreach ($barang_keluar as $item)
                        <div class="form-group col-md-6">
                            <label for="Jumlah">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ $item->jumlah }}"></value>   
                        </div>
                        @endforeach
                    </div>
                    
                    @endforeach
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i>
                        Edit</button>
                    <a href="/barang_keluar" class="btn btn-sm btn-danger">Kembali</a>
                 
                    
                </form>
               
            </div>
        </div>
    </div>
</div>
@endsection

@section('table')
<script type="text/javascript">
    $(document).ready(function () {
        $('#bootstrap-data-table-export').DataTable();
    });

</script>
@endsection

@section('lihat-gambar')
<script>
    function tampilGambar() {
        const image = document.querySelector('#gambar');
        const lihatImg = document.querySelector('.lihat-gambar');

        lihatImg.style.display = 'block';

        const oFReader = new FileReader();
        oFReader.readAsDataURL(image.files[0]);

        oFReader.onload = function (oFREvent) {
            lihatImg.src = oFREvent.target.result;
        }
    }

</script>
@endsection
