@extends('layout.main')

@section('barang_masuk', 'active')

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Daftar Barang Masuk</strong>
            </div>
            <div class="card-body">
            <table id="bootstrap-data-table" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                                <th>No</th>
                                <th>Kode Kategori</th>
                                <th>Supplier</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total Anggaran</th>
                                <th>Tanggal Masuk</th>
                                
                    </tr>
                    
                    </thead>
                        <tbody>
                            @foreach ($barang_masuk as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kategori->kategori }}</td>
                                <td>{{ $item->pemasok->nama }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>Rp. {{ number_format($item->harga) }}</td>
                                <td>{{ number_format($item->jumlah) }} {{ $item->satuan }}</td>
                                <td>Rp. {{ number_format($item->tot_pengeluaran) }}</td>
                                <td>{{ date('d F Y', strtotime($item->tanggal)) }}</td>
                                <td>
                                    @if (auth()->user()->role == 'Admin')
                                    <a href="/edtBarangMasuk/{{ $item->id_barang_masuk }}" class="btn btn-sm btn-success"><i
                                            class="fa fa-pencil-square-o"></i></a>
                                    <form action="{{ route('hpsBarangMasuk', $item->id_barang_masuk) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger";">
                                            <i class="fa fa-trash"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </table>
            </div>
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
