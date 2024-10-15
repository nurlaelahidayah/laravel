@extends('layout.main')

@section('barang_keluar', 'active')

@section('content')


<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <strong>Tambah Barang Keluar</strong>
            </div>
            <div class="card-body card-block">
                <form action="{{ route('tbhBarang_keluar') }}" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="kode_bk">Kode Barang Keluar</label>
                            <input type="text" class="form-control" id="kode_bk" name="kode_bk" value="{{ $kode_bk }}"
                                readonly>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="search_pegawai">Cari Pegawai</label>
                            <input type="text" class="form-control" id="search_pegawai" placeholder="Cari Pegawai...">
                            <select id="nama_pegawai" name="nama_pegawai" class="form-control"  mt-2">
                                <option selected>Pilih Pegawai...</option>
                                @foreach ($pegawai as $item)
                                <option value="{{ $item->id_pegawai }}">{{ $item->nama_pegawai }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="tgl_keluar">Tanggal Keluar</label>
                            <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar">
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="nama">Pilih Nama Barang</label>
                            <div class="dropdown">
                                <input type="text" placeholder="Cari Barang..." id="search_barang" onkeyup="filterBarang()" class="form-control">
                                    <button onclick="toggleDropdown()" class="form-control dropbtn">Pilih Barang...</button>
                                        <div id="barangDropdown" class="dropdown-content">
                                            @foreach ($barang as $item)
                                                <a href="javascript:void(0)" onclick="selectBarang('{{ $item->id_barang }}', '{{ $item->nama }}')">{{ $item->nama }}</a>
                                            @endforeach
                                        </div>
                            </div>
                            <input type="hidden" id="id_barang" name="nama">
                    </div>

                    
                    <div class="form-row">
                        <table class="table table-striped">
                            <thead>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Jumlah</th>
                                <th></th>
                            </thead>
                            <tbody class="isi">

                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary" style="float: right">Tambah</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection


@section('lihat-barang')
<script type="text/javascript">
    function barang_id() {
        var id_barang = document.getElementById("nama_barang").value;
        var url = "{{ url('tampil_bk') }}" + '/' + id_barang;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                var nilai = `
                    <tr>
                        <td>
                            ${data.data_bk.kode_barang}
                            <input type="hidden" value="${data.data_bk.id_barang}" id="id_barang[]" name="id_barang[]">
                        </td>
                        <td>${data.data_bk.nama}</td>
                        <td>${data.data_bk.jumlah} ${data.data_bk.satuan}</td>
                        <td>
                            <input type="number" value="0" min="0" class="form-control" id="jml[]" name="jml[]">
                            <input type="hidden" value="${data.data_bk.satuan}" id="satuan[]" name="satuan[]">
                        </td>
                        <td>
                            <button class="btn btn-sm btn-danger hapus" onclick="hapusBarang(this)"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                `;

                document.querySelector('.isi').insertAdjacentHTML('beforeend', nilai);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    }

    function hapusBarang(button) {
        // Menghapus baris yang berisi tombol yang diklik
        var row = button.closest('tr');
        if (row) {
            row.remove();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        $('#data_bk').DataTable();


        function toggleDropdown() {
        document.getElementById("barangDropdown").classList.toggle("show");
    }

    function filterBarang() {
    var input = document.getElementById("search_barang");
    var filter = input.value;

    if (filter.length > 0) {
        fetch(`/search-barang?query=${filter}`)
            .then(response => response.json())
            .then(data => {
                var dropdown = document.getElementById("barangDropdown");
                dropdown.innerHTML = ''; // Kosongkan dropdown sebelum menambahkan item baru
                data.forEach(item => {
                    var a = document.createElement('a');
                    a.href = "javascript:void(0)";
                    a.onclick = function() { selectBarang(item.id_barang, item.nama); };
                    a.textContent = item.nama;
                    dropdown.appendChild(a);
                });
                toggleDropdown(); // Tampilkan dropdown
            });
    } else {
        // Jika input kosong, bisa menampilkan semua barang atau menyembunyikan dropdown
        document.getElementById("barangDropdown").innerHTML = ''; // Kosongkan dropdown
    }}
    

    function selectBarang(id_barang, nama) {
        document.getElementById("id_barang").value = id_barang;
        document.getElementById("search_barang").value = nama;
        toggleDropdown();
    }

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

        document.getElementById('search_pegawai').addEventListener('input', function () {
            var searchValue = this.value;
            // Implement AJAX call to filter pegawai based on searchValue
        });




    });
</script>

<style>
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 230px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {background-color: #f1f1f1}

    .show {display: block;}
</style>
@endsection