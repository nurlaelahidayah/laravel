<!DOCTYPE html>
<html>

<head>
    <title>Laporan Rekapitulasi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <style type="text/css">
        table tr td {
            font-size: 12pt;
        }

        table thead tr th {
            text-align: center;
            font-size: 11pt;
        }

        .total th {
            font-size: 11pt;
            color: red;
        }

        hr {
            margin-top: 1px;
            margin-bottom: 30px;
            border: 2px;
            color: rgb(4, 79, 102);
        }

        img {
            height: 100px;
            width: 100px;
        }

        .penandatangan {
            margin-top: 40px;
            text-align: center; /* Mengatur teks agar rata kanan */
            position: relative; /* Mengatur posisi relatif */
        }
        .penandatangan p {
            margin: 0; /* Menghilangkan spacing pada paragraf */
        }
        .underline {
            text-decoration: underline; /* Menambahkan garis bawah */
        }
    </style>
    <style>
        /* Tambahkan gaya CSS sesuai kebutuhan */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .signature-table {
            margin-top: 40px;
            width: 100%;
        }
        .signature-table th, .signature-table td {
            text-align: center;
        }
        .penandatangan {
            margin-top: 40px;
            text-align: right; /* Mengatur teks agar rata kanan */
        }
        .penandatangan p {
            margin: 0; /* Menghilangkan spacing pada paragraf */
        }
        .underline {
            text-decoration: underline; /* Menambahkan garis bawah */
        }
        .signature-section {
            display: flex; /* Menggunakan flexbox untuk tata letak */
            justify-content: space-between; /* Menjaga jarak antar tanda tangan */
            margin-top: 20px; /* Jarak atas untuk tanda tangan */
        }
        .signature-box {
            text-align: center; /* Rata tengah untuk setiap tanda tangan */
            width: 30%; /* Lebar untuk setiap tanda tangan */
        }
    </style>
    

    <center>
        <img src="images/kantor.png" alt="">
        <h5>Inventori Barang Dinas Kependudukan dan Pencatatan Sipil Kab. Tegal
            <br>Laporan Barang Keluar</h4><br>
            <h6>Tanggal : {{ date('d-M-Y', strtotiMe($dari)) }} -
                {{ date('d-M-Y', strtotime($sampai)) }}
        </h5>
    </center>
    <hr>

    <table class='table table-bordered'>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Barang Keluar</th>
                <th>Nama Pegawai</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data_keluar as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_bk }}</td>
                <td>{{ $item->pegawai->nama_pegawai }}</td>
                <td>{{ $item->barang->nama }}</td>
                <td>{{ $item->jumlah }} {{ $item->satuan }}</td>
                <td>{{ $item->tanggal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-table">
        <thead>
            <tr>
                <th>Tanggal Validasi</th>
                <th>Pengurus Barang</th>
                <th>Pejabat Penatausahaan BMD</th>
                <th>Kepala SKPD</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ date('d-m-Y') }}</td> <!-- Tanggal validasi -->
                <td>
                    <p style="margin: 60px 0 0 0; text-decoration: underline;">Moh Sabar Iman</p> 
                    <p style="margin: 0;">NIP.19700121 199011 1 002</p> 
                </td>
                <td>
                    <p style="margin: 60px 0 0 0; text-decoration: underline;">Astidar, S.E</p> 
                    <p style="margin: 0;">NIP.19691209 200003 2 002</p> 
                </td>
                <td>
                    <p style="margin: 60px 0 0 0; text-decoration: underline;">Tri Guntoro, SH.MM </p> 
                    <p style="margin: 0;">NIP.19670419 199503 1 002</p> 
                </td>
            </tr>
        </tbody>
    </table>

   
</body>

</html>
