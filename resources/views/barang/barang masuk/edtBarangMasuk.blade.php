@extends('layout.main')

@section('barang_masuk', 'active')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <strong>Edit Barang Masuk</strong>
            </div>
            <div class="card-body card-block">
            @foreach ($barang_masuk as $item)
                <form action="/edtBarangMasuk/{{ $item->id_barang_masuk }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="kode_bm">Kode Barang Masuk</label>
                            <input type="text" class="form-control" id="kode_bm" name="kode_bm" value="{{ $item->kode_bm }}" readonly>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="kategori_id">Kategori</label>
                                <select id="kategori_id" class="form-control" name="kategori_id" >
                                @foreach ($barang_masuk as $item)
                                    <option value="{{ $item->kategori_id }}">{{ $item->kategori->kategori }}</option>
                                @endforeach
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id_kategori }}">{{ $item->kategori }}</option>
                                @endforeach
                                </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="pemasok_id">Supplier</label>
                                <select id="pemasok_id" class="form-control" name="pemasok_id" >
                                @foreach ($barang_masuk as $item)
                                    <option value="{{ $item->pemasok_id }}">{{ $item->pemasok->nama }}</option>
                                @endforeach
                                @foreach ($pemasok as $item)
                                    <option value="{{ $item->id_pemasok }}">{{ $item->nama }}</option>
                                @endforeach
                                </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="nama">Nama Barang</label>
                                <select id="nama" class="form-control" name="nama" onchange="updateHarga()">
                                
                                @foreach ($barang_masuk as $item)
                                    <option value="{{ $item->nama }}" data-harga="{{ $item->harga }}" data-satuan="{{ $item->satuan }}">{{ $item->nama }}</option>
                                @endforeach
                                @foreach ($barang as $item)
                                    <option value="{{ $item->nama }}" data-harga="{{ $item->harga_ambil }}" data-satuan="{{ $item->satuan }}">{{ $item->nama }}</option>
                                @endforeach
                                </select>
                        </div>
                        @foreach ($barang_masuk as $item)
                            <div class="form-group col-md-3">
                                <label for="harga">Harga</label>
                                    <input type="text" class="form-control" id="harga" name="harga" value="{{ $item->harga }}" required readonly>
                        @endforeach
                            <!--<input type="text" name="satuan" id="satuan" class="form-control"
                                value="{{ $item->satuan }}"> -->
                            </div>
                            <script>
                                function updateHarga() {
                                const barangSelect = document.getElementById('id_barang');
                                const hargaSelect = document.getElementById('harga_ambil');
                                const satuanSelect = document.getElementById('satuan');
                                const selectedOption = barangSelect.options[barangSelect.selectedIndex];

                                // Mengambil satuan dari data atribut
                                const harga = selectedOption.getAttribute('data-harga');
                                const satuan = selectedOption.getAttribute('data-satuan')

                                // Mengatur nilai satuan sesuai dengan barang yang dipilih
                                hargaSelect.value = harga;
                                satuanSelect.value = satuan;
                                                        }
                            </script>


                        @foreach ($barang_masuk as $item)
                            <div class="form-group col-md-2">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" value="{{ $item->jumlah }}"></value>   
                            </div>
                        @endforeach
                        
                        @foreach ($barang_masuk as $item)
                            <div class="form-group col-md-3">
                                <label for="satuan">Satuan</label>
                                    <input type="text" class="form-control" id="satuan" name="satuan" value="{{ $item->satuan }}" required readonly>
                        @endforeach
                            <!--<input type="text" name="satuan" id="satuan" class="form-control"
                                value="{{ $item->satuan }}"> -->
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
</div>


@endsection

