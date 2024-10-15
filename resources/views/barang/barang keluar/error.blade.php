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
                        <!-- Dropdown Pilih Pegawai -->
<div class="dropdown">
  <button type="button" onclick="myFunctionPegawai()" class="dropbtn">Pilih Pegawai</button>
  <div id="myDropdownPegawai" class="dropdown-content">
    <input type="text" placeholder="Cari Pegawai..." id="myInputPegawai" onkeyup="filterFunctionPegawai()">
    @foreach ($pegawai as $item)
      <a href="#" onclick="pegawai_id('{{ $item->id_pegawai }}'); event.preventDefault();">{{ $item->nama_pegawai }}</a>
    @endforeach
  </div>
</div>

                        <div class="form-group col-md-4">
                            <label for="tgl_keluar">Tanggal Keluar</label>
                            <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar">
                        </div>
                    </div>

                        
                    
                    <div class="dropdown">
  <button type="button" onclick="myFunction()" class="dropbtn">Pilih Barang</button>
  <div id="myDropdown" class="dropdown-content">
    <input type="text" placeholder="Cari Barang..." id="myInput" onkeyup="filterFunction()">
    @foreach ($barang as $item)
      <a href="#" onclick="barang_id('{{ $item->id_barang }}'); event.preventDefault();">{{ $item->nama }}</a>
    @endforeach
  </div>
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

<script>
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

function myFunctionPegawai() {
  document.getElementById("myDropdownPegawai").classList.toggle("show");
}

function filterFunction() {
  const input = document.getElementById("myInput");
  const filter = input.value.toUpperCase();
  const div = document.getElementById("myDropdown");
  const a = div.getElementsByTagName("a");
  for (let i = 0; i < a.length; i++) {
    const txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}

function filterFunctionPegawai() {
  const input = document.getElementById("myInputPegawai");
  const filter = input.value.toUpperCase();
  const div = document.getElementById("myDropdownPegawai");
  const a = div.getElementsByTagName("a");
  for (let i = 0; i < a.length; i++) {
    const txtValue = a[i].textContent || a[i].innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      a[i].style.display = "";
    } else {
      a[i].style.display = "none";
    }
  }
}

function pegawai_id(id_pegawai) {
    // Logika untuk menangani pemilihan pegawai
    console.log("Pegawai ID yang dipilih: " + id_pegawai);
    // Tambahkan logika Anda di sini untuk menangani pemilihan pegawai
}

function barang_id(id_barang) {
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
            // Reset input pencarian setelah barang ditambahkan
            resetDropdownAndSearch();
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}

function resetDropdownAndSearch() {
    // Kosongkan input pencarian
    document.getElementById("myInput").value = ""; 
    // Sembunyikan dropdown
    document.getElementById("myDropdown").classList.remove("show");
    // Tampilkan semua opsi di dropdown
    filterFunction(); // Panggil fungsi filter untuk menampilkan semua opsi
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
});
</script>


































































































































<style>
.dropbtn {
  background-color: #04AA6D; /* Warna tombol */
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
  background-color: #3e8e41; /* Warna saat hover */
}

#myInput, #myInputPegawai {
  box-sizing: border-box;
  background-image: url('searchicon.png');
  background-position: 14px 12px;
  background-repeat: no-repeat;
  font-size: 16px;
  padding: 14px 20px 12px 45px;
  border: none;
  border-bottom: 1px solid #ddd;
}

#myInput:focus, #myInputPegawai:focus {outline: 3px solid #ddd;}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f6f6f6;
  min-width: 230px;
  overflow: auto;
  border: 1px solid #ddd;
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown a:hover {background-color: #ddd;}

.show {display: block;}
</style>



@endsection