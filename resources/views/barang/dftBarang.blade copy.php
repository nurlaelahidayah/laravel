@extends('layout.main')

@section('barang', 'active')

@section('content')

<style>
    
</style>

<a href="/tbhBarang" class="btn btn-sm btn-primary mb-3"><i class="fa fa-plus"></i> Tambah Barang</a>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <strong class="card-title">Daftar Barang</strong>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="bootstrap-data-table" class="table table-striped table-bordered display nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Kategori</th>
                                <th>Supplier</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga Beli</th>
                                <th>Gambar</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kode_barang }}</td>
                                <td>{{ $item->kategori->kategori }}</td>
                                <td>{{ $item->pemasok->nama }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>@if ($item->stok)
                                        {{ number_format($item->stok->jumlah) }}
                                    @else
                                        {{ 0 }}
                                    @endif
                                    {{ $item->satuan }}
                                </td>
                                <td>Rp. {{ number_format($item->harga_ambil) }}</td>
                                <td><img src="{{ asset('Image/'.$item->gambar) }}" alt=""></td>
                                <td>
                                @if (auth()->user()->role == 'Admin')
                                    <a href="/edtBarang/{{ $item->id_barang }}" class="btn btn-sm btn-success"><i
                                            class="fa fa-pencil-square-o"></i></a>
                                    <form action="{{ route('hpsBarang', $item->id_barang) }}" method="POST" style="display:inline;">
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
